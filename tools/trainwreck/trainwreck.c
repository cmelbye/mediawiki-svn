/* Copyright (c) 2007 River Tarnell <river@attenuate.org>. */
/*
 * Permission is granted to anyone to use this software for any purpose,
 * including commercial applications, and to alter it and redistribute it
 * freely. This software is provided 'as-is', without any express or implied
 * warranty.
 */

/* $Id$ */

/*
 * trainwreck: multi-threaded MySQL replication tool.
 */

#include	<stdio.h>
#include	<string.h>
#include	<errno.h>
#include	<stdlib.h>
#include	<stdarg.h>
#include	<unistd.h>
#include	<regex.h>
#include	<pthread.h>
#include	<door.h>
#include	<port.h>

#include	<my_global.h>
#include	<mysql.h>

#include	"queue.h"
#include	"status.h"

typedef uint32_t logpos_t;

#define BINLOG_NAMELEN	512

static void strdup_free(char **, char const *);
static void usage(void);

static void set_thread_name(char const *);
static char const *get_thread_name(void);
static pthread_key_t threadname;

static char const *cfgfile = "trainwreck.conf";

static void read_configuration(void);
static void *read_master_logs(void *);
static void *slave_write_thread(void *);
static int process_master_logs_once(void);
static int find_event_type(char const *);
static int start_slave_write_thread(void);
static int start_master_read_thread(void);
static void stop_slave_write_thread(void);
static void stop_master_read_thread(void);
static void executed_up_to(char const *, logpos_t);
static int retrieve_binlog_position(void);
static void setup_status_door(void);
static void logmsg(char const *fmt, ...);
static char *master_host, *master_user, *master_pass;
static int master_port;
static char *slave_host, *slave_user, *slave_pass;
static int slave_port;
static MYSQL *master_conn;
static MYSQL *slave_conn;
static int debug;
static int server_id = 4123;

static pthread_t slave_thread;
static pthread_t master_thread;

static char *binlog_file;
static int64_t binlog_pos = 4;

regex_t *db_regex;

static int execute_query(MYSQL *, char const *);

#define ET_QUERY 2
#define ET_INTVAR 5
#define ET_ROTATE 4

typedef struct logentry {
	int		 le_type;
	uint32_t	 le_time;
	char		*le_file;
	int64_t		 le_pos;
	char		 le_database[128];
	char		*le_info;
	int64_t	 	 le_insert_id;
	uint64_t	 le_log_pos;
} logentry_t;

static logentry_t *parse_binlog(char unsigned const *, uint32_t len);
static void print_log_entry(logentry_t const *);
static void free_log_entry(logentry_t *);

typedef struct lq_entry {
	logentry_t	*lqe_item;
	struct lq_entry	*lqe_next;
	TAILQ_ENTRY(lq_entry) lqe_q;
} lq_entry_t;

TAILQ_HEAD(lqhead, lq_entry);

typedef struct le_queue {
	pthread_mutex_t		lq_mtx;
	pthread_cond_t		lq_cond;
	struct lqhead		lq_head;
} le_queue_t;

static void lq_init(le_queue_t *);
static void lq_put(le_queue_t *, logentry_t *);
static logentry_t *lq_get(le_queue_t *);

le_queue_t log_queue;

static int *ignorable_errno;
static int nignorable;

static int can_ignore_errno(unsigned);
static void do_ignore_errno(unsigned);

static pthread_mutex_t rst_mtx = PTHREAD_MUTEX_INITIALIZER,
		       wst_mtx = PTHREAD_MUTEX_INITIALIZER;
static status_t reader_st = ST_STOPPED, writer_st = ST_STOPPED;
static int autostart;
static int master_thread_stop;

static int ctl_port;

#define CTL_STOP	1
#define CTL_START	2

int
main(argc, argv)
	int argc;
	char *argv[];
{
int		c;
port_event_t	pe;

	pthread_key_create(&threadname, NULL);
	set_thread_name("main");

	setup_status_door();

	while ((c = getopt(argc, argv, "f:F:p:Da")) != -1) {
		switch (c) {
		case 'a':
			autostart = 1;
			break;

		case 'f':
			cfgfile = optarg;
			break;

		case 'F':
			strdup_free(&binlog_file, optarg);
			break;

		case 'p':
			binlog_pos = strtoll(optarg, NULL, 10);
			break;

		case 'D':
			debug = 1;
			break;

		default:
			usage();
			exit(1);
		}
	}

	if ((ctl_port = port_create()) == -1) {
		(void) fprintf(stderr, "cannot create control port: %s\n",
			       strerror(errno));
		return 1;
	}

	read_configuration();

	lq_init(&log_queue);

	if (autostart) {
		start_slave_write_thread();
		start_master_read_thread();
	}

	while (port_get(ctl_port, &pe, NULL) == 0) {
		if (pe.portev_source != PORT_SOURCE_USER) {
			logmsg("got ctl_port event from unknown source?");
			continue;
		}

		switch (pe.portev_events) {
		case CTL_STOP:
			stop_master_read_thread();
			stop_slave_write_thread();
			break;

		case CTL_START:
			if (start_slave_write_thread() == -1) 
				logmsg("slave thread is already running");
			if (start_master_read_thread() == -1)
				logmsg("master thread is already running");
			break;

		default:
			logmsg("got ctl_port event with unknown request");
			break;
		}
	}
	
	return 0;
}

static void
usage()
{
	(void) fprintf(stderr,
		       "usage: trainwreck [-d] [-f <cfg>] [-F binlog] [-p binlogpos]\n");
}

static void
read_configuration()
{
FILE	*f;
char	 line[1024];

	if ((f = fopen(cfgfile, "r")) == NULL) {
		(void) fprintf(stderr, "cannot open configuration file \"%s\": %s\n",
			       cfgfile, strerror(errno));
		exit(1);
	}

	while (fgets(line, sizeof(line), f)) {
	char	*opt, *value;
	size_t	 n;

		n = strlen(line) - 1;
		if (line[n] == '\n')
			line[n] = '\0';

		opt = line;
		if ((value = strchr(opt, ' ')) == NULL) {
			(void) fprintf(stderr, "syntax error in configuration file \"%s\"\n",
				       cfgfile);
			exit(1);
		}

		*value++ = '\0';

		if (!strcmp(opt, "master-host")) {
			strdup_free(&master_host, value);
		} else if (!strcmp(opt, "master-user")) {
			strdup_free(&master_user, value);
		} else if (!strcmp(opt, "master-pass")) {
			strdup_free(&master_pass, value);
		} else if (!strcmp(opt, "master-port")) {
			master_port = atoi(value);
		} else if (!strcmp(opt, "slave-host")) {
			strdup_free(&slave_host, value);
		} else if (!strcmp(opt, "slave-user")) {
			strdup_free(&slave_user, value);
		} else if (!strcmp(opt, "slave-pass")) {
			strdup_free(&slave_pass, value);
		} else if (!strcmp(opt, "slave-port")) {
			slave_port = atoi(value);
		} else if (!strcmp(opt, "ignore-errno")) {
			do_ignore_errno(atoi(value));
		} else if (!strcmp(opt, "only-replicate")) {
		int	err;
			db_regex = calloc(1, sizeof(*db_regex));
			if ((err = regcomp(db_regex, value, REG_EXTENDED | REG_NOSUB)) != 0) {
			char	errbuf[1024];
				(void) regerror(err, NULL, errbuf, sizeof(errbuf));
				(void) fprintf(stderr, "error in regular expression \"%s\": %s\n",
					       value, errbuf);
			}
		} else {
			(void) fprintf(stderr, "unknown option \"%s\" in configuration file \"%s\"\n",
				       opt, cfgfile);
			exit(1);
		}
	}
}

static void
strdup_free(s, new)
	char **s;
	char const *new;
{
	if (*s)
		free(*s);
	*s = strdup(new);
}

static void
stop_master_read_thread()
{
	master_thread_stop = 1;
	if (pthread_join(master_thread, NULL) == -1) {
		logmsg("cannot join master thread: %s",
				strerror(errno));
		return;
	}
	logmsg("master thread stopped");
}

static void
stop_slave_write_thread() {
	pthread_cancel(slave_thread);
	if (pthread_join(slave_thread, NULL) == -1) {
		logmsg("cannot join slave thread: %s",
				strerror(errno));
		return;
	}
	logmsg("slave thread stopped");
}

static int
start_master_read_thread()
{
	pthread_mutex_lock(&rst_mtx);
	if (reader_st != ST_STOPPED)
		return -1;
	
	reader_st = ST_INITIALISING;
	pthread_mutex_unlock(&rst_mtx);

	if ((master_conn = mysql_init(NULL)) == NULL) {
		logmsg("out of memory in mysql_init");
		reader_st = ST_STOPPED;
		return 0;
	}

	mysql_options(master_conn, MYSQL_READ_DEFAULT_GROUP, "trainwreck-master");

	if (mysql_real_connect(master_conn, master_host, master_user, master_pass, NULL,
				master_port, NULL, 0) == NULL) {
		logmsg("cannot connect to master %s:%d: %s",
				master_host, master_port, mysql_error(master_conn));
		reader_st = ST_STOPPED;
		return 0;
	}

	pthread_create(&master_thread, NULL, read_master_logs, NULL);
}

static void *
read_master_logs(p)
	void *p;
{
	set_thread_name("reader");

	if (retrieve_binlog_position() == 0)
		logmsg("resuming replication at %s,%lu",
			binlog_file, (unsigned long) binlog_pos);

	process_master_logs_once();

	reader_st = ST_STOPPED;
	return NULL;
}

static int
process_master_logs_once()
{
MYSQL_RES	*res;
MYSQL_ROW	 row;
char		*logsquery;
char		 lastdb[128];

	/*
	 * To retrieve binlogs we use the MySQL binlog dump protocol 
	 * (COM_BINLOG_DUMP).  This continues sending binlogs in realtime.
	 *
	 * We remember the database name so that ET_INTVAR events can have a 
	 * dbname (MySQL doesn't provide one).  This works because they are 
	 * always proceeded by a BEGIN or another query on the same database.
	 */

char		buf[BINLOG_NAMELEN + 10];
unsigned long	len;
	int4store(buf, (uint32_t) binlog_pos);
	int2store(buf + 4, (uint16_t) 2);
	int4store(buf + 6, (uint32_t) server_id);
	len = strlen(binlog_file);
	if (len > BINLOG_NAMELEN) {
		logmsg("%s,%lu: binlog name is too long",
				binlog_file, (unsigned long) binlog_pos);
		exit(1);
	}

	memcpy(buf + 10, binlog_file, len);

	if (simple_command(master_conn, COM_BINLOG_DUMP, buf, len + 10, 1) != 0) {
		logmsg("%s,%lu: error retrieving binlogs from server: (%d) %s",
				binlog_file, (unsigned long) binlog_pos,
				mysql_errno(master_conn), mysql_error(master_conn));
		exit(1);
	}

	for (;;) {
	logentry_t	*ent;
		reader_st = ST_WAIT_FOR_MASTER;
		len = cli_safe_read(master_conn);
		reader_st = ST_QUEUEING;

		if (master_thread_stop) {
			logmsg("shutting down");
			mysql_close(master_conn);
			reader_st = ST_STOPPED;
			master_thread_stop = 0;
			return 1;
		}

		if (len == packet_error) {
			logmsg("%s,%lu: error retrieving binlogs from server: (%d) %s",
				binlog_file, (unsigned long) binlog_pos,
				mysql_errno(master_conn), mysql_error(master_conn));
			exit(1);
		}

		if (len < 8 && master_conn->net.read_pos[0] == 254) {
			logmsg("no more logs!");
			reader_st = ST_STOPPED;
			return 1;
		}

		if ((ent = parse_binlog(master_conn->net.read_pos + 1, len - 1)) == NULL) {
			logmsg("failed parsing binlog");
			exit(1);
		}

		ent->le_file = strdup(binlog_file);
		binlog_pos = ent->le_pos;

		if (ent->le_database[0])
			strlcpy(lastdb, ent->le_database, sizeof(lastdb));
		else
			strlcpy(ent->le_database, lastdb, sizeof(ent->le_database));

		if (ent->le_type == ET_ROTATE && ent->le_time != 0) {
			strdup_free(&binlog_file, ent->le_info);
			logmsg("rotating to %s,4", binlog_file);
			free_log_entry(ent);
		} else {
			if ((db_regex == NULL || regexec(db_regex, ent->le_database, 0, NULL, 0) == 0) &&
			    (ent->le_type == ET_INTVAR || ent->le_type == ET_QUERY))
				lq_put(&log_queue, ent);
			else {
				free_log_entry(ent);
			}
		}
	}
}

static logentry_t *
parse_binlog(buf, len)
	char unsigned const *buf;
	uint32_t len;
{
uint32_t	 timestamp;
uchar		 type;
logentry_t	*ent;

	/*
	 * Header:
	 *   4 bytes, timestamp
	 *   1 byte, event type
	 *     1: START_EVENT
	 *     2: QUERY_EVENT
	 *   4 bytes, server id
	 *   4 bytes, event total size
	 *   4 bytes, this event's offset
	 *   2 bytes, flags
	 *
	 * Total length: 19
	 */
	if (len < 19) {
		logmsg("binlog is too short");
		return NULL;
	}

	ent = calloc(1, sizeof(*ent));
	
#define ADVANCE(n) { buf += (n);  len -= (n); }
	ent->le_time = uint4korr(buf);
	ADVANCE(4);
	type = *buf;
	ent->le_type = type;
	ADVANCE(1);
	/* server id, don't care */
	ADVANCE(4);
	/* event size, don't care */
	ADVANCE(4);
	/* offset */
	ent->le_pos = uint4korr(buf);
	ADVANCE(4);
	/* flags, don't care */
	ADVANCE(2);

	switch (type) {
	case ET_ROTATE:
		/*
		 * 8 bytes position (always 4)
		 * rest of data is filename
		 */
		if (len <= 9) {
			logmsg("binlog is truncated");
			goto err;
		}

		ent->le_log_pos = uint8korr(buf);

		ADVANCE(8);
		ent->le_info = malloc(len + 1);
		memcpy(ent->le_info, buf, len);
		ent->le_info[len] = '\0';
		return ent;

	case ET_QUERY: {
	int	namelen;
	int	error;
		/*
		 * 4 bytes: thread id
		 * 4 bytes: execution time
		 * 1 byte: length of database name
		 * 2 bytes: error code
		 * rest of data is database name, NUL, query
		 */
		if (len <= 11) {
			logmsg("binlog is truncated");
			goto err;
		}

		ADVANCE(4); /* ignore thread id */
		ADVANCE(4); /* ignore execution time */

		namelen = *buf;
		ADVANCE(1);

		error = uint2korr(buf);
		ADVANCE(2);
		if (len <= namelen + 2) {
			logmsg("binlog is truncated");
			goto err;
		}

		strlcpy(ent->le_database, (char const *) buf, sizeof(ent->le_database));
		ADVANCE(namelen + 1);
		ent->le_info = calloc(1, len + 1);
		memcpy(ent->le_info, buf, len);
		return ent;
	}

	case ET_INTVAR:
		/*
		 * 8 bytes: next INSERT_ID
		 */
		 if (len < 8) {
			 logmsg("binlog is truncated");
			 goto err;
		 }

		 ent->le_insert_id = uint8korr(buf);
		 return ent;

	default:
		 /* don't fill anything in, we don't know/care */
		 return ent;
	}

#undef ADVANCE
err:
	free(ent);
	return NULL;
}

static void
logmsg(char const *msg, ...)
{
va_list	ap;
static pthread_mutex_t lock = PTHREAD_MUTEX_INITIALIZER;

	pthread_mutex_lock(&lock);
	(void) fprintf(stderr, "[%s] ", get_thread_name());
	va_start(ap, msg);
	(void) vfprintf(stderr, msg, ap);
	va_end(ap);
	fputs("\n", stderr);
	pthread_mutex_unlock(&lock);
}

static void
print_log_entry(e)
	logentry_t const *e;
{
	logmsg("%s,%llu : %d : %s : %s\n",
	      e->le_file, (unsigned long long) e->le_pos,
	      e->le_type,
	      e->le_database[0] ? e->le_database : "<none>",
	      (e->le_type == ET_QUERY) ? e->le_info /*"<query>"*/ : 
		(e->le_info ? e->le_info : "<null>"));
}

static void
free_log_entry(e)
	logentry_t *e;
{
	if (e->le_info)
		free(e->le_info);
	if (e->le_file)
		free(e->le_file);
	free(e);
}

static void
lq_init(q)
	le_queue_t *q;
{
	TAILQ_INIT(&q->lq_head);
}

static void
lq_put(q, e)
	le_queue_t *q;
	logentry_t *e;
{
lq_entry_t	*entry;
	pthread_mutex_lock(&q->lq_mtx);
	entry = calloc(1, sizeof(lq_entry_t));
	entry->lqe_item = e;
	TAILQ_INSERT_TAIL(&q->lq_head, entry, lqe_q);
	pthread_cond_signal(&q->lq_cond);
	pthread_mutex_unlock(&q->lq_mtx);
}

static void
lq_get_cleanup(mtx)
	void *mtx;
{
	logmsg("shutting down");
	pthread_mutex_unlock((pthread_mutex_t *) mtx);
	mysql_close(slave_conn);
	pthread_mutex_lock(&wst_mtx);
	writer_st = ST_STOPPED;
	pthread_mutex_unlock(&wst_mtx);
}

static logentry_t *
lq_get(q)
	le_queue_t *q;
{
lq_entry_t	*qe;
logentry_t	*ent;
	/*
	 * This function is a cancellation point.  Read the comment in 
	 * slave_write_thread for details.
	 */
	pthread_mutex_lock(&q->lq_mtx);

	pthread_cleanup_push(lq_get_cleanup, &q->lq_mtx);
	pthread_setcancelstate(PTHREAD_CANCEL_ENABLE, NULL);

	while (TAILQ_EMPTY(&q->lq_head)) {
		pthread_cond_wait(&q->lq_cond, &q->lq_mtx);
	}

	pthread_setcancelstate(PTHREAD_CANCEL_DISABLE, NULL);
	pthread_cleanup_pop(0);

	qe = TAILQ_FIRST(&q->lq_head);
	TAILQ_REMOVE(&q->lq_head, qe, lqe_q);

	pthread_mutex_unlock(&q->lq_mtx);

	ent = qe->lqe_item;
	free(qe);
	return ent;
}

static int
start_slave_write_thread()
{
	pthread_mutex_lock(&wst_mtx);
	if (writer_st != ST_STOPPED)
		return -1;
	
	writer_st = ST_INITIALISING;
	pthread_mutex_unlock(&wst_mtx);
	
	if ((slave_conn = mysql_init(NULL)) == NULL) {
		logmsg("out of memory in mysql_init");
		return 0;
	}

	mysql_options(slave_conn, MYSQL_READ_DEFAULT_GROUP, "trainwreck-slave");

	if (mysql_real_connect(slave_conn, slave_host, slave_user, slave_pass, NULL,
				slave_port, NULL, 0) == NULL) {
		logmsg("cannot connect to slave %s:%d: %s",
				slave_host, slave_port, mysql_error(slave_conn));
		return 0;
	}

	pthread_create(&slave_thread, NULL, slave_write_thread, NULL);
	return 0;
}

static void *
slave_write_thread(p)
	void *p;
{
logentry_t	*e;
	/*
	 * This thread is cancelled by the main thread when we want to stop 
	 * replication.  However, we disallow cancellation at all times except 
	 * during lq_get().  This is okay, because most operations should be 
	 * fairly short; lq_get is the only one that blocks.  lq_get enables 
	 * cancellation while it's running and will do proper cleanup for us.
	 */
	pthread_setcancelstate(PTHREAD_CANCEL_DISABLE, NULL);

	set_thread_name("writer");

	writer_st = ST_WAIT_FOR_ENTRY;
	while ((e = lq_get(&log_queue)) != NULL) {
		logmsg("%s,%lu", e->le_file, (unsigned long) e->le_pos);

		writer_st = ST_EXECUTING;
		if (mysql_select_db(slave_conn, e->le_database) != 0) {
			logmsg("%s,%lu: cannot select \"%s\": %s",
				e->le_file, (unsigned long) e->le_pos,
				e->le_database, mysql_error(slave_conn));
			exit(1);
		}

		if (e->le_type == ET_INTVAR) {
		char	query[128];
			/* info is e.g. INSERT_ID=3 */
			snprintf(query, sizeof(query), "SET INSERT_ID=%llu",
					(unsigned long long) e->le_insert_id);
			if (execute_query(slave_conn, query) != 0) {
				logmsg("%s,%lu: query failed (%u: %s): \"%s\"",
					e->le_file, (unsigned long) e->le_pos,
					mysql_errno(slave_conn), mysql_error(slave_conn), 
					query);
				exit(1);
			}
		} else if (e->le_type == ET_QUERY) {
		char	*query;
			query = e->le_info;
			if (execute_query(slave_conn, query) != 0) {
				logmsg("%s,%lu: query failed (%u: %s): \"%s\"",
					e->le_file, (unsigned long) e->le_pos,
					mysql_errno(slave_conn), mysql_error(slave_conn), 
					query);
				exit(1);
			}
			executed_up_to(e->le_file, e->le_pos);
		}
		writer_st = ST_WAIT_FOR_ENTRY;
	}
	return NULL;
}

static int
execute_query(conn, q)
	MYSQL *conn;
	char const *q;
{
unsigned	i;

	if (debug) {
		(void) printf("would execute: [%s]\n", q);
		return 0;
	}

	if ((i = mysql_query(conn, q)) == 0)
		return 0;

	if (can_ignore_errno(mysql_errno(conn)))
		return 0;

	return i;
}

static void
do_ignore_errno(n)
	unsigned n;
{
	ignorable_errno = realloc(ignorable_errno, nignorable + 1);
	ignorable_errno[nignorable] = n;
	nignorable++;
}

static int
can_ignore_errno(n)
	unsigned n;
{
int	i;
	for (i = 0; i < nignorable; i++)
		if (ignorable_errno[i] == n)
			return 1;
	return 0;
}

static void
set_thread_name(name)
	char const *name;
{
char	*s;
	if ((s = pthread_getspecific(threadname)) != NULL)
		free(s);
	pthread_setspecific(threadname, strdup(name));
}

static char const *
get_thread_name()
{
char	*s;
	if ((s = pthread_getspecific(threadname)) == NULL)
		return "unknown";
	return s;
}

static void
executed_up_to(log, pos)
	char const *log;
	logpos_t pos;
{
static int	rstat = -1;
char	buf[256];
	if (rstat == -1) {
		if ((rstat = open("logpos.stat", O_WRONLY | O_CREAT, 0600)) == -1) {
			logmsg("cannot open state file \"logpos.stat\": %s",
					strerror(errno));
			exit(1);
		}
	} else
		lseek(rstat, 0, SEEK_SET);

	ftruncate(rstat, 0);
	int4store(buf, pos);
	strlcpy(buf + 4, log, sizeof(buf) - 4);
	write(rstat, buf, 4 + strlen(log));
	fdatasync(rstat);
}

static int
retrieve_binlog_position()
{
int		 rstat;
struct stat	 st;
char		*buf;
	if ((rstat = open("logpos.stat", O_RDONLY)) == -1) {
		logmsg("cannot open state file \"logpos.stat\": %s",
				strerror(errno));
		if (errno == ENOENT)
			return 1;
		exit(1);
	}

	if (fstat(rstat, &st) == -1) {
		logmsg("cannot stat state file \"logpos.stat\": %s",
				strerror(errno));
		exit(1);
	}

	buf = calloc(1, st.st_size + 1);
	if (read(rstat, buf, st.st_size) != st.st_size) {
		logmsg("short read on state file \"logpos.stat\": %s",
				strerror(errno));
		exit(1);
	}
	
	binlog_pos = uint4korr(buf);
	strdup_free(&binlog_file, buf + 4);
	return 0;
}

static int status_door;
static void service_status_request(void *, char *, size_t, door_desc_t *, uint_t);

static void
setup_status_door()
{
int	i;
	if ((status_door = door_create(service_status_request,
					NULL, 0)) == -1) {
		logmsg("creating status door: %s", strerror(errno));
		exit(1);
	}

	(void) unlink(STATUS_DOOR);
	if ((i = creat(STATUS_DOOR, 0600)) == -1) {
		logmsg("creating status door \"%s\": %s",
				STATUS_DOOR, strerror(errno));
		exit(1);
	}
	(void) close(i);

	(void) fdetach(STATUS_DOOR);
	if (fattach(status_door, STATUS_DOOR) == -1) {
		logmsg("attaching status door to \"%s\": %s",
				STATUS_DOOR, strerror(errno));
		exit(1);
	}
}

static void
service_status_request(cookie, args, arglen, desc, ndesc)
	void *cookie;
	char *args;
	size_t arglen;
	door_desc_t *desc;
	uint_t ndesc;
{
char	c[3];
	if (arglen < 1) {
		c[0] = RR_INVALID_QUERY;
		door_return(c, 1, NULL, 0);
		return;
	}

	switch (args[0]) {
	case RQ_PING:
		c[0] = RR_OK;
		door_return(c, 1, NULL, 0);
		return;

	case RQ_STATUS:
		c[0] = RR_OK;
		c[1] = reader_st;
		c[2] = writer_st;
		door_return(c, 3, NULL, 0);
		return;

	case RQ_START:
		port_send(ctl_port, CTL_START, NULL);
		c[0] = RR_OK;
		door_return(c, 1, NULL, 0);
		return;

	case RQ_STOP:
		port_send(ctl_port, CTL_STOP, NULL);
		c[0] = RR_OK;
		door_return(c, 1, NULL, 0);
		return;

	default:
		c[0] = RR_INVALID_QUERY;
		door_return(c, 1, NULL, 0);
		return;
	}
}
