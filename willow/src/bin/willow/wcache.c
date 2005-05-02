/* @(#) $Header$ */
/* This source code is in the public domain. */
/* 
 * Willow: Lightweight HTTP reverse-proxy.
 * wcache: entity caching.
 */

#if defined __SUNPRO_C || defined __DECC || defined __HP_cc
# pragma ident "@(#)$Header$"
#endif

/*
 * Cache metadata is stored in a BerkeleyDB database, along with a key which
 * represents the filename. The objects themselves are stored on a filesystem,
 * using the key and a path constructed from the key's prefix; for example,
 * the key "123456" would be stored as "1/2/3/123456".
 *
 * This is rather flawed, because if two people try to start caching the same
 * object at the same time, duplicate files are created in the cache, and
 * won't ever be deleted.  What should happen is that finding or creating
 * a cached object is a single atomic operation.
 */
 
#include <sys/types.h>
#include <sys/stat.h>

#include <string.h>
#include <errno.h>
#include <stdlib.h>
#include <math.h>
#include <strings.h>
#include <limits.h>
#include <assert.h>

#include <db.h>
 
#include "wcache.h"
#include "wlog.h"
#include "wconfig.h"
#include "willow.h"

static DB_ENV *cacheenv;
static DB *cacheobjs;

#define CACHEDIR "__objects__"

static void dberror(const char *, int);
static void cache_writestate(struct cache_state *, DB_TXN *);
static int cache_getstate(struct cache_state *, DB_TXN *);
static int cache_next_id(void);

static struct cache_state state;
static int int_max_len;

static void dberror(txt, err)
	const char *txt;
	int err;
{
	wlog(WLOG_ERROR, "fatal database error: %s: %s", txt, db_strerror(err));
	wcache_shutdown();
	exit(8);
}

void
wcache_setupfs(void)
{
	int		 i, j, k;
struct	cachedir	*cd;
struct	cache_state	 state;
	DB_TXN		 *txn;
	
	for (cd = config.caches; cd < config.caches + config.ncaches; ++cd) {
		size_t	 len, dlen;
		char 	*dir, *env;
		
		dlen = strlen(cd->dir) + sizeof(CACHEDIR) + 1 + 6 /* 0/1/2/ */;
		if ((dir = wmalloc(dlen)) == NULL)
			outofmemory();
		
		safe_snprintf(dlen, (dir, dlen, "%s/%s", cd->dir, CACHEDIR));
		
		len = strlen(cd->dir) + sizeof("/__env__") + 1;
		if ((env = wmalloc(len)) == NULL)
			outofmemory();
		
		safe_snprintf(len, (env, len, "%s/__env__", cd->dir));
		
		/* create base directory if it doesn't exist */
		/*LINTED unsafe mkdir*/
		if (mkdir(cd->dir, 0700) < 0 || mkdir(dir, 0700) < 0 || mkdir(env, 0700)) {
			wlog(WLOG_ERROR, "%s: mkdir: %s", cd->dir, strerror(errno));
			exit(8);
		}
		
		for (i = 0; i < 10; ++i) {
			safe_snprintf(dlen, (dir, dlen, "%s/%s/%d", cd->dir, CACHEDIR, i));
			
			/*LINTED unsafe mkdir*/
			if (mkdir(dir, 0700) < 0) {
				wlog(WLOG_ERROR, "%s: mkdir: %s", dir, strerror(errno));
				exit(8);
			}
			
			for (j = 0; j < 10; ++j) {
				safe_snprintf(dlen, (dir, dlen, "%s/%s/%d/%d", cd->dir, CACHEDIR, i, j));
				/*LINTED unsafe mkdir*/
				if (mkdir(dir, 0700) < 0) {
					wlog(WLOG_ERROR, "%s: mkdir: %s", dir, strerror(errno));
					exit(8);
				}
				for (k = 0; k < 10; ++k) {
					safe_snprintf(dlen, (dir, dlen, "%s/%s/%d/%d/%d", cd->dir, CACHEDIR, i, j, k));
					/*LINTED unsafe mkdir*/
					if (mkdir(dir, 0700) < 0) {
						wlog(WLOG_ERROR, "%s: mkdir: %s", dir, strerror(errno));
						exit(8);
					}
				}
			}
		}
		wfree(dir);
		wfree(env);
		wlog(WLOG_NOTICE, "created cache directory structure for %s", cd->dir);
	}
	wcache_init(0);
	
	bzero(&state, sizeof(state));
	state.cs_id = 1000;
	if (i = cacheenv->txn_begin(cacheenv, NULL, &txn, 0))
		dberror("setupfs: txn_begin", i);
	
	cache_writestate(&state, txn);
	
	if (i = txn->commit(txn, 0))
		dberror("setupfs: commit", i);

	wlog(WLOG_NOTICE, "wrote initial cache state");
	wcache_shutdown();
}									
			
void
wcache_shutdown(void)
{
	int i;
	
	/* don't use dberror() here because it calls us */
	/*LINTED =/==*/
	if (cacheobjs) 
		if ((i = cacheobjs->close(cacheobjs, 0)) || (i = cacheenv->close(cacheenv, 0))) {
			wlog(WLOG_ERROR, "error closing database: %s", db_strerror(i));
			exit(8);
		}
}

void
wcache_init(readstate)
	int readstate;
{
struct	cachedir	*cd;
	int		 i;
	DB_TXN		*txn;
	
	wlog(WLOG_NOTICE, "using bdb: %s", DB_VERSION_STRING);
	
	if (config.ncaches == 0) {
		wlog(WLOG_WARNING, "no cache directories specified");
		return;
	}
	
	/* only one cache dir supported for now... */
	for (cd = config.caches; cd < config.caches + config.ncaches; ++cd) {
		size_t	 len;
		char	*dir;

		len = strlen(cd->dir) + sizeof("/__env__");		
		if ((dir = wmalloc(len)) == NULL)
			outofmemory();
		
		safe_snprintf(len, (dir, len, "%s/__env__", cd->dir));
		
		if (i = db_env_create(&cacheenv, 0))
			dberror("init: env_create", i);

		cacheenv->set_errfile(cacheenv, stderr);
		cacheenv->set_errpfx(cacheenv, "willow");

		if (i = cacheenv->open(cacheenv, dir, DB_CREATE | DB_INIT_TXN | DB_INIT_LOCK | 
				DB_INIT_MPOOL | DB_PRIVATE
#ifdef THREADED_IO
				| DB_THREAD
#endif
				, 0))
			dberror("init: env open", i);
		
		if (i = db_create(&cacheobjs, cacheenv, 0))
			dberror("init: db_create", i);
		
		if (i = cacheenv->txn_begin(cacheenv, NULL, &txn, 0))
			dberror("init: open txn_begin", i);
		
		if (i = cacheobjs->open(cacheobjs, txn, "cacheobjs.db", NULL, DB_HASH,
				DB_CREATE, 0600))
			dberror("init: db open", i);
		
		if (i = txn->commit(txn, 0))
			dberror("init: open commit", i);
		
		wfree(dir);
	}
	
	if (readstate) {
		if (i = cacheenv->txn_begin(cacheenv, NULL, &txn, 0))
			dberror("init: state txn_begin", i);
		if (cache_getstate(&state, txn) == -1) {
			wlog(WLOG_ERROR, "cache state unavailable");
			exit(8);
		}
		if (i = txn->commit(txn, 0))
			dberror("init: state commit", i);
	}
	
	int_max_len = log10(INT_MAX) + 1;
}

static char *
make_keybuf(key)
	struct cache_key *key;
{
	char	*buf;
	
	if ((buf = wmalloc(key->ck_len + 1)) == NULL)
		outofmemory();
	bcopy(key->ck_key, buf, key->ck_len + 1);
	return buf;
}

static char *
make_databuf(obj)
	struct cache_object *obj;
{
	char	*buf;
	
	if ((buf = wmalloc(sizeof(struct cache_object) + obj->co_plen + 1)) == NULL)
		outofmemory();
	
	bcopy(obj, buf, sizeof(struct cache_object));
	bcopy(obj->co_path, buf + sizeof(struct cache_object), obj->co_plen + 1);
	return buf;
}
	
struct cache_object *
wcache_find_object(key)
	struct cache_key *key;
{
	DBT		 keyt, datat;
	DB_TXN		*txn;
struct	cache_object	*data;
	int		 i, j;
	char		*keybuf;

	WDEBUG((WLOG_DEBUG, "wcache_find_object: looking for %s %d", key->ck_key, key->ck_len));
	if (cacheobjs == NULL)
		return NULL;
	
	keybuf = make_keybuf(key);	
	
	bzero(&keyt, sizeof(keyt));
	bzero(&datat, sizeof(datat));
	
	keyt.data = keybuf;
	keyt.size = key->ck_len + 1;
	
	datat.flags = DB_DBT_MALLOC;
	
	if (i = cacheenv->txn_begin(cacheenv, NULL, &txn, 0))
		dberror("find_object: txn_begin", i);
	
	if (i = cacheobjs->get(cacheobjs, txn, &keyt, &datat, 0)) {
		wfree(keybuf);
		if (j = txn->abort(txn))
			dberror("find_object: abort", j);
		if (i != DB_NOTFOUND)
			dberror("find_object: get", i);
		return NULL;
	}
	if (i = txn->commit(txn, 0))
		dberror("find_object: commit", i);

	wfree(keybuf);
			
	data = datat.data;
	data->co_path = (char *)data + sizeof(*data);
	WDEBUG((WLOG_DEBUG, "found %s, path=[%s]", key->ck_key, data->co_path));
	data->co_flags &= ~WCACHE_FREE;
	return data;
}

int
wcache_store_object(key, obj)
	struct cache_key *key;
	struct cache_object *obj;
{
	DBT		 keyt, datat;
	DB_TXN		 *txn;
	char		*keybuf;
	int		 i, ret = 0;
	
	WDEBUG((WLOG_DEBUG, "storing %s %d in cache, path %s", key->ck_key, key->ck_len, obj->co_path));
	
	bzero(&keyt, sizeof(keyt));
	bzero(&datat, sizeof(datat));
	keybuf = make_keybuf(key);
	
	keyt.data = keybuf;
	keyt.size = key->ck_len + 1;
	
	datat.data = make_databuf(obj);
	datat.size = sizeof(struct cache_object) + obj->co_plen + 1;
	
	if (i = cacheenv->txn_begin(cacheenv, NULL, &txn, 0))
		dberror("store_object: txn_begin", i);
	if (cacheobjs->put(cacheobjs, txn, &keyt, &datat, DB_NOOVERWRITE))
		ret = -1;
	if (i = txn->commit(txn, 0))
		dberror("store_object: commit", i);
	
	wfree(keybuf);
	wfree(datat.data);
	
	return ret;
}

struct cache_key *
wcache_make_key(host, path)
	const char *host, *path;
{
struct	cache_key	*ret;
	
	if ((ret = wmalloc(sizeof(*ret))) == NULL)
		outofmemory();
	
	ret->ck_len = strlen(host) + strlen(path);
	ret->ck_key = wmalloc(ret->ck_len + 1);
	safe_snprintf(ret->ck_len, (ret->ck_key, ret->ck_len, "%s%s", host, path));
	return ret;
}

void
wcache_free_key(key)
	struct cache_key *key;
{
	wfree(key->ck_key);
	wfree(key);
}

void
wcache_free_object(obj)
	struct cache_object *obj;
{
	if (obj->co_flags & WCACHE_FREE) {
		wfree(obj->co_path);
		wfree(obj);
	} else {
		free(obj);
	}
}

static void
cache_writestate(state, txn)
	struct cache_state *state;
	DB_TXN *txn;
{
	DBT	keyt, datat;
	int	i;
	
	WDEBUG((WLOG_DEBUG, "writing cache state"));
	
	bzero(&keyt, sizeof(keyt));
	bzero(&datat, sizeof(datat));
	
	keyt.size = 5;
	keyt.data = "STATE";
	
	datat.size = sizeof(*state);
	datat.data = state;
	
	if (i = cacheobjs->put(cacheobjs, txn, &keyt, &datat, 0))
		dberror("writestate: put", i);
}

static int
cache_getstate(state, txn)
	struct cache_state *state;
	DB_TXN *txn;
{
	DBT	keyt, datat;
	int	i;
	
	WDEBUG((WLOG_DEBUG, "reading cache state"));
	
	bzero(&keyt, sizeof(keyt));
	bzero(&datat, sizeof(datat));
	
	keyt.size = 5;
	keyt.data = "STATE";
	
	datat.ulen = sizeof(*state);
	datat.data = state;
	datat.flags = DB_DBT_USERMEM;
		
	if (i = cacheobjs->get(cacheobjs, txn, &keyt, &datat, 0))
		dberror("getstate: get", i);
	
	WDEBUG((WLOG_DEBUG, "cs_id = %d", state->cs_id));
	return 0;
}

static int
cache_next_id(void)
{
	DB_TXN	*txn;
	int	 i;
	
	if (i = cacheenv->txn_begin(cacheenv, NULL, &txn, 0))
		dberror("next_id: txn_begin", i);
	
	state.cs_id++;
	cache_writestate(&state, txn);
	
	if (i = txn->commit(txn, 0))
		dberror("next_id: commit", i);

	return state.cs_id;
}

struct cache_object *
wcache_new_object(void)
{
struct	cache_object	*ret;
	int		 i;
	char		 *p, *s, a[11];

	if ((ret = wcalloc(1, sizeof(*ret))) == NULL)
		outofmemory();
	
	ret->co_id = cache_next_id();

	assert(ret->co_id > 999);
	ret->co_plen = int_max_len + 6;
	if ((ret->co_path = wmalloc(ret->co_plen + 1)) == NULL)
		outofmemory();
	p = ret->co_path;
	safe_snprintf(10, (a, 10, "%d", ret->co_id));
	s = a + strlen(a) - 1;
	WDEBUG((WLOG_DEBUG, "id=%d a=%s", ret->co_id, a));
	
	for (i = 0; i < 3; ++i) {
		*p++ = *s--;
		*p++ = '/';
 	}
	*p = '\0';
	if (strlcat(ret->co_path, a, ret->co_plen + 1) >= ret->co_plen + 1)
		abort();
	ret->co_flags |= WCACHE_FREE;
	WDEBUG((WLOG_DEBUG, "new object path is [%s], len %d", ret->co_path, ret->co_plen));
	return ret;
}
