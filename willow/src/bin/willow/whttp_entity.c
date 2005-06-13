/* @(#) $Header$ */
/* This source code is in the public domain. */
/*
 * Willow: Lightweight HTTP reverse-proxy.
 * whttp_entity: HTTP entity handling.
 */

#if defined __SUNPRO_C || defined __DECC || defined __HP_cc
# pragma ident "@(#)$Header$"
#endif

/* How does this work?
 * 
 * Each HTTP request can be divided into two entities: the request and the
 * response.  The client sends the request, i.e. the headers and possibly
 * a body, to the server, which considers it and sends a reply.
 * 
 * Internally, we read the request headers and ignore the
 * body [entity_read_headers].  We then examine the headers
 * [whttp:client_read_done] and decide if it has a body.  We modify
 * the entry slightly, and send it to the backend with either no source
 * or, if it had a body, the client's FDE as the source [entity_send].
 * We then wait for the server to reply with its header.  When it does
 * [whttp:backend_headers_done], we send the request to the client, using
 * the backend's FDE as the body, if it has one, and close it.
 * 
 * See "Entity sending" below for a detailed description of how entity
 * sending works.
 * 
 * TODO: We don't have to buffer the headers, _but_ it makes things easier
 * for now and doesn't cost much.  if we start not buffering we need to
 * decide what to do when the client goes away unexpectedly.  probably it's
 * easiest to just drop the backend connection (this is wasteful of backends
 * but we don't cache them at the moment anyway).  what do we do when the
 * client sends "Foo: bar\r\n  baz\r\n" and we decide after baz that we
 * shouldn't send that header after all?
 * 
 * There is a trade-off in some places between excessive copying and
 * excessive syscalls.  In some cases we copy data (headers) when we could
 * undo the parser mangling and send them as-is.  IMO this is not likely
 * to be a worthwhile optimisation, needs profiling.
 * 
 * As for FDE backending, Unix sucks:
 * 
 *    The sendfile() function copies data  from  in_fd  to  out_fd starting
 *    at  offset  off and of length len bytes. The in_fd argument should
 *    be a  file  descriptor  to  a  regular  file opened for reading.
 */

#include <sys/uio.h>

#include <unistd.h>
#include <errno.h>
#include <string.h>
#include <stdlib.h>
#include <stdio.h>
#include <assert.h>
/*LINTED*/
#include <fcntl.h>
#include <strings.h>
#include <event.h>
#include <ctype.h>

#include "willow.h"
#include "whttp.h"
#include "whttp_entity.h"
#include "wnet.h"
#include "wlog.h"

#define ENTITY_STATE_START	0
#define ENTITY_STATE_HDR	1
#define ENTITY_STATE_DONE	2
#define ENTITY_STATE_SEND_HDR	3
#define ENTITY_STATE_SEND_BODY	4
#define ENTITY_STATE_SEND_BUF	5

static void entity_error_callback(struct bufferevent *, short, void *);
static void entity_read_callback(struct bufferevent *, void *);
static int parse_headers(struct http_entity *);
static int parse_reqtype(struct http_entity *);
static int validhost(const char *);
static int via_includes_me(const char *);

static void entity_send_headers_done(struct fde *, void *, int);
static void entity_send_fde_write_done(struct fde *, void *, int);
static void entity_send_buf_done(struct fde *, void *, int);
static void entity_send_fde_read(struct fde *);
static void entity_send_file_done(struct fde *, void *, int);
static void entity_send_target_read(struct bufferevent *, void *);
static void entity_send_target_write(struct bufferevent *, void *);
static void entity_send_target_error(struct bufferevent *, short, void *);

const char *ent_errors[] = {
	/* 0  */	"Unknown error",
	/* -1 */	"Read error",
	/* -2 */	"Could not parse request headers",
	/* -3 */	"Invalid Host",
	/* -4 */	"Invalid request type",
	/* -5 */	"Too many headers",
	/* -6 */	"Forwarding loop detected",
};
	
void
entity_free(entity)
	struct http_entity *entity;
{
	header_free(&entity->he_headers);
	if (entity->_he_frombuf)
		bufferevent_free(entity->_he_frombuf);
	if (entity->_he_tobuf)
		bufferevent_free(entity->_he_tobuf);
	if (entity->he_reqstr)
		wfree(entity->he_reqstr);
	if (!entity->he_flags.response) {
		if (entity->he_rdata.request.host)
			wfree(entity->he_rdata.request.host);
		if (entity->he_rdata.request.path)
			wfree(entity->he_rdata.request.path);
	}
	bzero(entity, sizeof(*entity));
}

void
entity_set_response(ent, isresp)
	struct http_entity *ent;
	int isresp;
{
	if (isresp) {
		if (ent->he_flags.response)
			return;
		if (ent->he_rdata.request.path)
			wfree(ent->he_rdata.request.path);
		if (ent->he_rdata.request.host)
			wfree(ent->he_rdata.request.host);
		bzero(&ent->he_rdata.response, sizeof(ent->he_rdata.response));
		ent->he_flags.response = 1;
	} else {
		if (!ent->he_flags.response)
			return;
		bzero(&ent->he_rdata.request, sizeof(ent->he_rdata.request));
		ent->he_flags.response = 0;
	}
}

void
entity_read_headers(entity, func, udata)
	struct http_entity *entity;
	header_cb func;
	void *udata;
{
	entity->_he_cbdata = udata;
	entity->_he_func = func;
	entity->he_flags.hdr_only = 1;

	WDEBUG((WLOG_DEBUG, "entity_read_headers: starting, source %d",
			entity->he_source.fde.fde->fde_fd));
	/* XXX source for an entity header read is _always_ an fde */
	entity->_he_frombuf = bufferevent_new(entity->he_source.fde.fde->fde_fd,
				entity_read_callback, NULL, entity_error_callback, entity);
	bufferevent_disable(entity->_he_frombuf, EV_WRITE);
	bufferevent_enable(entity->_he_frombuf, EV_READ);
//	wnet_register(entity->he_source.fde.fde->fde_fd, FDE_READ, entity_read_callback, entity);
	//entity_read_callback(entity->he_source.fde);
}

void
entity_send(fde, entity, cb, data, flags)
	struct fde *fde;
	struct http_entity *entity;
	header_cb cb;
	void *data;
	int flags;
{
	char		 status[4];
	int		 wn_flags = 0;
	char		*hdr;
struct	header_list	*hl;

	entity->_he_func = cb;
	entity->_he_cbdata = data;
	entity->_he_target = fde;
	entity->he_flags.hdr_only = 0;
	entity->_he_tobuf = bufferevent_new(entity->_he_target->fde_fd,
		NULL, entity_send_target_write,
		entity_send_target_error, entity);
	bufferevent_disable(entity->_he_tobuf, EV_READ);
	bufferevent_enable(entity->_he_tobuf, EV_WRITE);
	if (entity->_he_frombuf)
		bufferevent_disable(entity->_he_frombuf, EV_READ);
	entity->_he_state = ENTITY_STATE_SEND_HDR;

	WDEBUG((WLOG_DEBUG, "entity_send: writing to %d [%s]", fde->fde_fd, fde->fde_desc));
	
	if (entity->he_flags.response) {
		evbuffer_add_printf(entity->_he_tobuf->output, "HTTP/1.1 %d %s\r\n",
			entity->he_rdata.response.status, entity->he_rdata.response.status_str);
	} else {
		evbuffer_add_printf(entity->_he_tobuf->output, "%s %s HTTP/1.1\r\n",
			request_string[entity->he_rdata.request.reqtype],
			entity->he_rdata.request.path);
	}
		
	
	//hdr = header_build(&entity->he_headers);
	//bufferevent_write(entity->_he_tobuf, hdr, strlen(hdr));
	//wfree(hdr);
	for (hl = entity->he_headers.hl_next; hl; hl = hl->hl_next)
		evbuffer_add_printf(entity->_he_tobuf->output, "%s: %s\r\n", hl->hl_name, hl->hl_value);
	bufferevent_write(entity->_he_tobuf, "\r\n", 2);

//	entity_read_callback(entity->_he_frombuf, entity);
}

static void
entity_error_callback(struct bufferevent *be, short what, void *d)
{
struct	http_entity	*entity = d;

	/*
	 * Some kind of error occured while we were reading from the backend.
	 */
	WDEBUG((WLOG_DEBUG, "entity_error_callback called, what=%hd", what));
	if (what & EVBUFFER_EOF) {
		/*
		 * End of file from backend.
		 */
		WDEBUG((WLOG_DEBUG, "entity_error_callback: EOF"));
		entity->_he_func(entity, entity->_he_cbdata, 1);
		return;
	}

	entity->he_flags.error = 1;
	entity->_he_func(entity, entity->_he_cbdata, -1);
}

static void
entity_read_callback(be, d)
	struct bufferevent *be;
	void *d;
{
struct	http_entity	*entity = d;
	int		 i;
#define RD_BUFSZ	 16386
	char		 buf[RD_BUFSZ];

	/*
	 * Data was available from the backend.  If state is ENTITY_STATE_SEND_BODY,
	 * we're moving the request from backend->client, so do that. Otherwise,
	 * we're still reading header information.
	 */
	WDEBUG((WLOG_DEBUG, "entity_read_callback: called, source %d", 
			entity->he_source.fde.fde->fde_fd));

	if (entity->_he_state < ENTITY_STATE_SEND_BODY) {
		if ((i = parse_headers(entity)) < 0) {
			WDEBUG((WLOG_DEBUG, "entity_read_callback: parse_headers returned -1"));
			entity->he_flags.error = 1;
			entity->_he_func(entity, entity->_he_cbdata, i);
			return;
		}

		if (entity->_he_state == ENTITY_STATE_DONE) {
			if (entity->he_flags.hdr_only) {
				WDEBUG((WLOG_DEBUG, "entity_read_callback: client is ENTITY_STATE_DONE"));
				entity->_he_func(entity, entity->_he_cbdata, 0);
				return;
			} else
				entity->_he_state = ENTITY_STATE_SEND_BODY;
		}
	}

	assert(entity->_he_state == ENTITY_STATE_SEND_BODY);

	/*
	 * While data is available, read it and forward.  If we're using chunked encoding,
	 * don't read past the end of the chunk.
	 */
	for (;;) {
		size_t read;
		size_t want = RD_BUFSZ;

		/*
		 * If we're reading chunked data, check if we're starting a new chunk.
		 */
		if ((entity->he_te & TE_CHUNKED) && entity->_he_chunk_size == 0) {
			char *chunks;
			if ((chunks = evbuffer_readline(entity->_he_frombuf->input)) == NULL)
				return;
			entity->_he_chunk_size = strtol(chunks, NULL, 16);
			free(chunks);
			WDEBUG((WLOG_DEBUG, "new chunk, size=%d", entity->_he_chunk_size));
			if (entity->_he_chunk_size == 0) {
				/*
				 * Zero-sized chunk = end of request.
				 */
				entity->he_flags.eof = 1;
				bufferevent_disable(entity->_he_frombuf, EV_READ);
//				entity->_he_func(entity, entity->_he_cbdata, 0);
				return;
			}
		}

		if (entity->_he_chunk_size) {
			want = entity->_he_chunk_size;

			read = bufferevent_read(entity->_he_frombuf, buf, want);
			WDEBUG((WLOG_DEBUG, "rw %d, got %d", want, read));
			if (read == 0)
				break;	/* No more data */
		
			entity->_he_chunk_size -= read;

			if (entity->he_cache_callback) {
				entity->he_cache_callback(buf, read, entity->he_cache_callback_data);
			}
			bufferevent_write(entity->_he_tobuf, buf, read);
		} else {
			if (entity->he_cache_callback) {
				entity->he_cache_callback(entity->_he_frombuf->input->buffer,
					entity->_he_frombuf->input->off, entity->he_cache_callback_data);
			}
			if (bufferevent_write_buffer(entity->_he_tobuf, entity->_he_frombuf->input) == 0)
				break;
		}
	}

	bufferevent_disable(entity->_he_frombuf, EV_READ);
	bufferevent_enable(entity->_he_tobuf, EV_WRITE);
}

static void
entity_send_target_read(struct bufferevent *buf, void *d)
{
	/*
	 * Read from target possible.  This never happens.
	 */
}

static void
entity_send_target_write(struct bufferevent *buf, void *d)
{
struct	http_entity	*entity = d;

	if (entity->he_flags.eof) {
		bufferevent_disable(entity->_he_frombuf, EV_READ);
		entity->_he_func(entity, entity->_he_cbdata, 0);
		return;
	}

	/*
	 * Write to target completed.
	 */
	if (entity->_he_state == ENTITY_STATE_SEND_HDR) {
		/*
		 * Sending headers completed.  Decide what to do next.
		 */
		switch (entity->he_source_type) {
		case ENT_SOURCE_NONE:
			/* no body for this request */
			WDEBUG((WLOG_DEBUG, "entity_send_headers_done: no body, return immediately"));
			entity->_he_func(entity, entity->_he_cbdata, 0);
			return;
		
		case ENT_SOURCE_BUFFER:
			/* write buffer, callback when done */
			WDEBUG((WLOG_DEBUG, "entity_send_headers_done: source is buffer, %d bytes", 
					entity->he_source.buffer.len));
			entity->_he_state = ENTITY_STATE_SEND_BUF;
			bufferevent_write(entity->_he_tobuf,
				(void *)entity->he_source.buffer.addr,
				entity->he_source.buffer.len);
			return;

		case ENT_SOURCE_FILE:
			/* write file */
			bufferevent_disable(entity->_he_tobuf, EV_WRITE);
			//entity->_he_tobuf = entity->_he_frombuf = NULL;

			if (wnet_sendfile(entity->_he_target->fde_fd, entity->he_source.fd.fd, 
				entity->he_source.fd.size - entity->he_source.fd.off,
				entity->he_source.fd.off, entity_send_file_done, entity, 0) == -1) {
				entity->_he_func(entity, entity->_he_cbdata, -1);
			}
			return;
		}
		entity->_he_state = ENTITY_STATE_SEND_BODY;
	}
	
	if (entity->_he_state == ENTITY_STATE_SEND_BUF) {
		/*
		 * Writing buffer completed.
		 */
		bufferevent_disable(entity->_he_tobuf, EV_WRITE);
		bufferevent_disable(entity->_he_frombuf, EV_READ);
		entity->_he_func(entity, entity->_he_cbdata, 0);
		return;
	}

	WDEBUG((WLOG_DEBUG, "entity_send_write_callback"));
	/*
	 * Otherwise, we're sending from an FDE, and the last write completed.
	 */
	bufferevent_enable(entity->_he_frombuf, EV_READ);
	//entity_read_callback(entity->_he_frombuf, entity);
}

static void
entity_send_target_error(struct bufferevent *buf, short err, void *d)
{
struct	http_entity	*entity = d;

	/*
	 * Writing to target produced an error.
	 */
	entity->_he_func(entity, entity->_he_cbdata, -1);
}

/*ARGSUSED*/
static void
entity_send_file_done(fde, data, res)
	struct fde *fde;
	void *data;
	int res;
{
struct	http_entity	*entity = data;

	WDEBUG((WLOG_DEBUG, "entity_send_file_done: called for %d [%s], res=%d", 
		fde->fde_fd, fde->fde_desc, res));
	entity->_he_func(entity, entity->_he_cbdata, res);
	return;
}	

static int
validhost(host)
	const char *host;
{
	for (; *host; ++host) {
		WDEBUG((WLOG_DEBUG, "char %c, char_table[%d] = %d", *host, 
				(int)(unsigned char)*host, char_table[(unsigned char)*host]));
		if (!(char_table[(unsigned char)*host] & CHAR_HOST))
			return 0;
	}
	return 1;
}

static int
via_includes_me(s)
        const char *s;
{
	char    *orig = wstrdup(s);
	char    *via = orig, *comma, *space;

	do {
		comma = strchr(via, ',');
		if (comma)
			*comma++ = '\0';
		via = strchr(via, ' ');
		if (!via)
			break;
		while (*via == ' ')
			++via;
		space = strchr(via, ' ');
		if (!space) {
			wfree(orig);
			return 0;
		}
		*space = '\0';
		if (!strcmp(via, my_hostname)) {
			wfree(orig);
			return 1;
		}
		via = comma;
	} while (comma);
	wfree(orig);
	return 0;
}

#ifdef __lint
# pragma error_messages(off, E_GLOBAL_COULD_BE_STATIC)
#endif
/*
 * Header handling.
 */
void
header_free(head)
	struct header_list *head;
{
struct	header_list	*next = head->hl_next;

	while (next) {
		struct header_list *this = next;
		next = this->hl_next;
		wfree((char *)this->hl_name);
		wfree((char *)this->hl_value);
		wfree(this);
	}

	bzero(head, sizeof(*head));
}

#ifdef __lint
# pragma error_messages(default, E_GLOBAL_COULD_BE_STATIC)
#endif

void
header_add(head, name, value)
	struct header_list *head;
	const char *name, *value;
{
struct	header_list	*new = head;

	head->hl_num++;
	
	if (head->hl_tail)
		new = head->hl_tail;
	else
		while (new->hl_next)
			new = new->hl_next;
	new->hl_next = wmalloc(sizeof(*head->hl_next));
	head->hl_tail = new->hl_next;
	head->hl_len += strlen(name) + strlen(value) + 4;
	new = new->hl_next;
	new->hl_name = name;
	new->hl_value = value;
	new->hl_next = new->hl_tail = NULL;
	new->hl_flags = 0;
}

void
header_remove(head, it)
	struct header_list *head, *it;
{
struct	header_list	*jt;

	jt = head;
	while (jt->hl_next && jt->hl_next != it)
		jt = jt->hl_next;
	jt->hl_next = jt->hl_next->hl_next;
	if (it == head->hl_tail)
		head->hl_tail = jt;
	wfree(it);
}

#ifdef __lint
# pragma error_messages(off, E_GLOBAL_COULD_BE_STATIC)
#endif
char *
header_build(head)
	struct header_list *head;
{
	char	*buf;
	size_t	 bufsz;
	size_t	 buflen = 0;

	bufsz = head->hl_len + 3;
	if ((buf = wmalloc(bufsz)) == NULL)
		outofmemory();
	
	*buf = '\0';
	while (head->hl_next) {
		head = head->hl_next;
		buflen += snprintf(buf + buflen, bufsz - buflen - 1, "%s: %s\r\n", head->hl_name, head->hl_value);
	}
	if (strlcat(buf, "\r\n", bufsz) >= bufsz)
		abort();

	return buf;
}
#ifdef __lint
# pragma error_messages(default, E_GLOBAL_COULD_BE_STATIC)
#endif

void
header_dump(head, fd)
	struct header_list *head;
	int fd;
{
	int i = 0;
struct	header_list	*h;

	h = head->hl_next;
	while (h) {
		h = h->hl_next;
		++i;
	}
	
	write(fd, &i, sizeof(i));	
	
	while (head->hl_next) {
		int i, j;
		head = head->hl_next;
		i = strlen(head->hl_name);
		write(fd, &i, sizeof(i));
		j = strlen(head->hl_value);
		write(fd, &j, sizeof(j));
		write(fd, head->hl_name, i);
		write(fd, head->hl_value, j);
	}
}

int
header_undump(head, fd, len)
	struct header_list *head;
	int fd;
	off_t *len;
{
	int		 i = 0, j = 0, n = 0;
struct	header_list	*it = head;
	ssize_t		 r;
	
	*len = 0;
	bzero(head, sizeof(*head));
	if ((r = read(fd, &n, sizeof(n))) < 0) {
		wlog(WLOG_WARNING, "reading cache file: %s", strerror(errno));
		return -1; /* XXX */
	}
	
	*len += r;
	WDEBUG((WLOG_DEBUG, "header_undump: %d entries", n));

	while (n--) {
		char *n, *v, *s;
		int k;
		
		if ((it->hl_next = wcalloc(1, sizeof(struct header_list))) == NULL)
			outofmemory();
		it = it->hl_next;
		*len += read(fd, &i, sizeof(i));	
		*len += read(fd, &j, sizeof(j));
		WDEBUG((WLOG_DEBUG, "header_undump: i=%d j=%d", i, j));
		n = wmalloc(i + j + 2);
		i = read(fd, n, i);
		*len += i;
		s = n + i;
		*s++ = '\0';
		v = s;
		k = read(fd, s, j);
		*len += k;
		s += k;
		*s = '\0';
		it->hl_name = n;
		it->hl_value = wstrdup(v);
		head->hl_len += i + j + 4;
	}
	
	head->hl_tail = it;
	return 0;
}

static int
parse_headers(entity)
	struct http_entity *entity;
{
	char *line;

	while (line = evbuffer_readline(entity->_he_frombuf->input)) {
		char **hdr;
		char *value;

		if (!line)
			return 0;

		if (!*line) {
			entity->_he_state = ENTITY_STATE_DONE;
			free(line);
			return 0;
		}

		switch(entity->_he_state) {
		case ENTITY_STATE_START:
			entity->he_reqstr = wstrdup(line);
			if (parse_reqtype(entity) == -1) {
				free(line);
				return ENT_ERR_INVREQ;
			}
			entity->_he_state = ENTITY_STATE_HDR;
			break;
		case ENTITY_STATE_HDR:
			hdr = wstrvec(line, ":", 2);

			if (!hdr[0] || !hdr[1]) {
				free(line);
				wstrvecfree(hdr);
				return ENT_ERR_INVREQ;
			}

			value = hdr[1];
			while (isspace(*value))
				++value;
			value = wstrdup(value);

			WDEBUG((WLOG_DEBUG, "header: from [%s], [%s] = [%s]",
				line, hdr[0], value));

			if (++entity->he_headers.hl_num > MAX_HEADERS) {
				free(line);
				wfree(value);
				wstrvecfree(hdr);
				return ENT_ERR_2MANY;
			}
			if (!strcasecmp(hdr[0], "Host")) {
				if (!validhost(value)) {
					free(line);
					wfree(value);
					wstrvecfree(hdr);
					return ENT_ERR_INVHOST;
				}
				header_add(&entity->he_headers, wstrdup(hdr[0]), value);
				entity->he_rdata.request.host = wstrdup(value);
			} else if (!strcasecmp(hdr[0], "Content-Length")) {
				header_add(&entity->he_headers, wstrdup(hdr[0]), value);
				entity->he_rdata.request.contlen = atoi(value);
			} else if (!strcasecmp(hdr[0], "Via")) {
				if (via_includes_me(value)) {
					free(line);
					wfree(value);
					wstrvecfree(hdr);
					return ENT_ERR_LOOP;
				}
				header_add(&entity->he_headers, wstrdup(hdr[0]), value);
			} else if (!strcasecmp(hdr[0], "transfer-encoding")) {
				/* XXX */
				if (!strcasecmp(value, "chunked")) {
					entity->he_te |= TE_CHUNKED;
				}
				/* Don't forward transfer-encoding... */
				wfree(value);
			} else 
				header_add(&entity->he_headers, wstrdup(hdr[0]), value);

			wstrvecfree(hdr);
			break;
		}

		free(line);
	}
	return 0;
}

static int 
parse_reqtype(entity)
	struct http_entity *entity;
{
	char	*p, *s, *t;
	char	*request = entity->he_reqstr;;
	int	i;

	WDEBUG((WLOG_DEBUG, "parse_reqtype: called, response=%d", (int)entity->he_flags.response));
	
	/*
	 * These probably shouldn't be handled in the same function.
	 */
	if (entity->he_flags.response) {
		/* 
		 * HTTP/1.0
		 */
		if ((p = strchr(request, ' ')) == NULL)
			return -1;
		*p++ = '\0';
		
		/* 200 */
		if ((s = strchr(p, ' ')) == NULL)
			return -1;
		*s++ = '\0';
		entity->he_rdata.response.status = atoi(p);
		
		/* OK */
		entity->he_rdata.response.status_str = s;
		
		WDEBUG((WLOG_DEBUG, "parse_reqtype: \"%s\" \"%d\" \"%s\"",
				request, entity->he_rdata.response.status,
				entity->he_rdata.response.status_str));
		return 0;
	}	
	
	/* GET */
	if ((p = strchr(request, ' ')) == NULL)
		return -1;

	*p++ = '\0';

	for (i = 0; supported_reqtypes[i].name; i++)
		if (!strcmp(request, supported_reqtypes[i].name))
			break;

	entity->he_rdata.request.reqtype = supported_reqtypes[i].type;
	if (entity->he_rdata.request.reqtype == REQTYPE_INVALID)
		return -1;

	/* /path/to/file */
	if ((s = strchr(p, ' ')) == NULL)
		return -1;

	*s++ = '\0';
	if (*p != '/') {
		/*
		 * This normally means the request URI was of the form
		 * "http://host.tld/file".  Clients don't send this, but
		 * Squid does when it thinks we're a proxy.
		 *
		 * Extract the host and set it now.  If there's another host
		 * later, it'll overwrite it, but if the client sends two
		 * different hosts it's probably broken anyway...
		 *
		 * We could handle non-http URIs here, but there's not much
		 * points, the backend will reject it anyway.
		 */
		if (strncmp(p, "http://", 7))
			return -1;
		p += 7;
		t = strchr(p, '/');
		if (t == NULL)
			return -1;
		entity->he_rdata.request.path = wstrdup(t);
		*t = '\0';
		entity->he_rdata.request.host = p;
	} else {
		entity->he_rdata.request.path = wstrdup(p);
	}

	/* HTTP/1.0 */
	/*
	 * Ignore this for now...
	 */
	return 0;
}
