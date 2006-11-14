/* @(#) $Id$ */
/* This source code is in the public domain. */
/*
 * Willow: Lightweight HTTP reverse-proxy.
 * wconfig: configuration.
 */

#if defined __SUNPRO_C || defined __DECC || defined __HP_cc
# pragma ident "@(#)$Id$"
#endif

#include <sys/types.h>
#include <sys/socket.h>

#include <netinet/in.h>
#include <arpa/inet.h>
#include <syslog.h>

#include <cstdlib>
#include <cstdio>
#include <cstring>
#include <cerrno>
#include <climits>
#include <netdb.h>
#include <pthread.h>
#include <set>
using std::set;

#include "willow.h"
#include "wconfig.h"
#include "wbackend.h"
#include "wlog.h"
#include "whttp.h"
#include "wnet.h"
#include "confparse.h"
#include "radix.h"
#include "format.h"

using namespace conf;

map<wsocket *, int> lsn2group;
set<int> used_pools;

#define CONFIGFILE SYSCONFDIR "/willow.conf"

vector<listener *> listeners;
struct configuration config;

static void
set_backend(conf::tree_entry &e)
{
value const	*val;
int		 port = 80, family = AF_UNSPEC, gn = 0;
string		 group = "<default>";
map<string, int>::iterator it;

	if ((val = e/"port") != NULL)
		port = CONF_AINTVAL(*val);
	if ((val = e/"aftype") != NULL)
		if (val->cv_values[0].av_strval == "ipv6")
			family = AF_INET6;
		else	family = AF_INET;

	if ((val = e/"group") != NULL) {
		group = val->cv_values[0].av_strval;

		it = poolnames.find(group);
		if (it == poolnames.end()) {
			val->report_error("backend group %s does not exist",
				group.c_str());
			return;
		} else
			gn = it->second;
	}

	used_pools.insert(gn);
	bpools.find(gn)->second.add(e.item_key, port, family);
}

static void
set_listen(conf::tree_entry &e)
{
value const	*val;
int		 port = 80;
struct listener	*nl;
int		 gn = 0;
string		 group;
int		 fam = AF_UNSPEC;
addrlist	*res;

	if ((val = e/"port") != NULL)
		port = CONF_AINTVAL(*val);

	if ((val = e/"aftype") != NULL)
		if (val->cv_values[0].av_strval == "ipv6")
			fam = AF_INET6;
		else	fam = AF_INET;

	if ((val = e/"group") != NULL) {
	map<string, int>::iterator	it;
		group = val->cv_values[0].av_strval;

		it = poolnames.find(group);
		if (it == poolnames.end()) {
			val->report_error("backend group %s does not exist",
				group.c_str());
			return;
		} else
			gn = it->second;
	}

	try {
		res = addrlist::resolve(e.item_key, port, st_stream, fam);
	} catch (socket_error &ex) {
		wlog(WLOG_ERROR, format("resolving %s: %s")
		    % e.item_key % ex.what());
		return;
	}

addrlist::iterator	it = res->begin(), end = res->end();
	for (; it != end; ++it) {
		nl = new listener;
		try {
			nl->sock = it->makesocket("HTTP listener", prio_accept);
		} catch (socket_error &ex) {
			delete nl;
			delete res;
			return;
		}
		WDEBUG((WLOG_DEBUG, format("listener %d has group %d")
			% nl->sock % gn));
		lsn2group[nl->sock] = gn;
		used_pools.insert(gn);

		nl->port = port;
		nl->name = e.item_key;
		nl->group = gn;
		listeners.push_back(nl);
		wlog(WLOG_NOTICE, format("listening on %s%s (group %d)")
		     % e.item_key % it->straddr() % gn);
	}
	delete res;
}

static bool
validate_log_facility(tree_entry &, value &)
{
	return true;
}

static void
set_log_facility(tree_entry &, value &)
{
}

static void
set_cache(tree_entry &e)
{
value	*v;
	v = e/"size";
	config.caches = (cachedir *)wrealloc(config.caches, sizeof(*config.caches) * (config.ncaches + 1));
	config.caches[config.ncaches].dir = wstrdup(e.item_key.c_str());
	config.caches[config.ncaches].maxsize = v->cv_values[0].av_intval;
	wlog(WLOG_NOTICE, format("cache dir \"%s\", size %d bytes")
			% config.caches[config.ncaches].dir
			% config.caches[config.ncaches].maxsize);
	config.ncaches++;
}

static bool
v_udp_log(tree_entry &e, value &v)
{
value	*val;
bool	 ret = true;
	if ((val = e/"udp-host") == NULL) {
		v.report_error("udp-host must be specified for UDP logging");
		ret = false;
	}

	if (!v.is_single(cv_yesno)) {
		v.report_error("udp-log must be yes/no");
		ret = false;
	}

	return ret;
}

static bool
v_aftype(tree_entry &, value &v)
{
	if (!v.is_single(cv_string)) {
		v.report_error("aftype must be single unquoted string");
		return false;
	}
string	&s = v.cv_values[0].av_strval;
	if (s != "ipv4" && s != "ipv6") {
		v.report_error("aftype must be \"ipv4\" or \"ipv6\"");
		return false;
	}
	return true;
}

static void
radix_from_list(tree_entry &e, access_list &rad)
{
value		*val;
int		 immed = 0;
	if ((val = e/"apply-at") != NULL)
		if (val->cv_values[0].av_strval == "connect")
			immed = whttp_deny_connect;

	if ((val = e/"allow") != NULL) {
	vector<avalue>::iterator	it = val->cv_values.begin(),
					end = val->cv_values.end();
		for (; it != end; ++it)
			rad.allow(it->av_strval, immed);
	}

	if ((val = e/"deny") != NULL) {
	vector<avalue>::iterator	it = val->cv_values.begin(),
					end = val->cv_values.end();
		for (; it != end; ++it)
			rad.deny(it->av_strval, immed);
	}
}

static void
set_access(tree_entry &e)
{
	radix_from_list(e, config.access);
}

static void
stats_access(tree_entry &, value &v)
{
vector<avalue>::iterator	it = v.cv_values.begin(),
				end = v.cv_values.end();
	for (; it != end; ++it)
		stats.access.allow(it->av_strval);
}

static void
force_backend_access(tree_entry &, value &v)
{
vector<avalue>::iterator	it = v.cv_values.begin(),
				end = v.cv_values.end();
	for (; it != end; ++it)
		config.force_backend.allow(it->av_strval, 1);
}

static bool
radix_prefix(tree_entry &, value &v)
{
vector<avalue>::iterator	it = v.cv_values.begin(),
				end = v.cv_values.end();
prefix	p;
	for (; it != end; ++it) {
		if (it->av_type != cv_qstring) {
			v.report_error("access prefix must be a list of quoted strings");
			return false;
		}

		try {
		prefix	p(it->av_strval);
		} catch (invalid_prefix& e) {
			v.report_error("%s: %s", it->av_strval.c_str(), e.what());
			return false;
		}
	}
	return true;
}

bool
v_apply_at(tree_entry &, value &v)
{
	if (!v.is_single(cv_string)) {
		v.report_error("apply-at must be single unquoted string");
		return false;
	}
string	&s = v.cv_values[0].av_strval;
	if (s != "connect" && s != "request") {
		v.report_error("expected \"connect\" or \"request\"");
		return false;
	}
	return true;
}

bool
v_lb_type(tree_entry &, value &v)
{
	if (!v.is_single(cv_string)) {
		v.report_error("lb-type must be single unquoted string");
		return false;
	}
string	&s = v.cv_values[0].av_strval;
	if (s != "rr" && s != "carp" && s != "carp-host") {
		v.report_error("expected \"rr\", \"carp\" or \"carp-host\"");
		return false;
	}
	return true;
}

void
set_backend_group(tree_entry &e)
{
int	gn;
string	group;
map<string, int>::iterator	it;
	group = e.item_key;

	it = poolnames.find(group);
	if (it == poolnames.end()) {
		gn = nbpools;
		poolnames[group] = nbpools++;
	} else
		gn = it->second;

lb_type	 lbtype = lb_rr;
value	*v;
	if ((v = e/"lb-type") != NULL) {
	string	&s = v->cv_values[0].av_strval;
		if (s == "rr")
			lbtype = lb_rr;
		else if (s == "carp")
			lbtype = lb_carp;
		else if (s == "carp-host")
			lbtype = lb_carp_hostonly;
	}

	WDEBUG((WLOG_DEBUG, format("adding backend %d type = %d") % gn % (int) lbtype));
	bpools.insert(make_pair(gn, backend_pool(e.item_key, lbtype)));

	if ((v = e/"hosts") != NULL) {
	vector<avalue>::iterator it = v->cv_values.begin(), end = v->cv_values.end();
		for (; it != end; ++it)
			host_to_bpool[imstring(it->av_strval)] = gn;
		used_pools.insert(gn);
	}
}

bool
v_hosts(tree_entry &, value &v)
{
vector<avalue>::iterator it = v.cv_values.begin(), end = v.cv_values.end();
	for (; it != end; ++it)
		if (it->av_type != cv_qstring) {
			v.report_error("hosts must be a list of quoted strings");
			return false;
		}
	return true;
}

	
bool
read_config(string const &file)
{
conf_definer	 conf;
tree		*t;
conf
	.block("log")
		.value("level",		simple_range(0, 3),		set_int(logging.level))
		.value("file",		nonempty_qstring,		set_qstring(logging.file))
		.value("syslog",	simple_yesno,			set_yesno(logging.syslog))
		.value("facility",	func(validate_log_facility),	func(set_log_facility))
		.value("access-log",	nonempty_qstring,		set_qstring(config.access_log))
		.value("log-sample",	simple_range(1, INT_MAX),	set_int(config.log_sample))
		.value("udp-log",	func(v_udp_log),		set_yesno(config.udp_log))
		.value("udp-port",	simple_range(0, 65535),		set_int(config.udplog_port))
		.value("udp-host",	nonempty_qstring,		set_string(config.udplog_host))

	.block("cache")
		.value("expire-every",		simple_time,		set_time(config.cache_expevery))
		.value("expire-threshold",	simple_range(0, 100),	set_int(config.cache_expthresh))

	.block("http")
		.value("compress",		simple_yesno,		set_yesno(config.compress))
		.value("compress-level",	simple_range(1, 9),	set_int(config.complevel))
		.value("backend-retry",		simple_time,		set_time(config.backend_retry))
		.value("cache-private",		simple_yesno,		set_yesno(config.cache_private))
		.value("msie-http11-hack",	simple_yesno,		set_yesno(config.msie_hack))
		.value("default-host",		nonempty_qstring,	set_string(config.default_host))
		.value("force-backend",		func(radix_prefix),	func(force_backend_access))
		.value("backend-keepalive",	simple_yesno,		set_yesno(config.backend_keepalive))
		.value("client-keepalive",	simple_yesno,		set_yesno(config.client_keepalive))
		.value("keepalive-max",		simple_range(0),	set_int(config.keepalive_max))
		.value("x-follow-redirect",	simple_yesno,		set_yesno(config.x_follow))
		.value("max-redirects",		simple_range(1),	set_int(config.max_redirects))

	.block("server")
		.value("threads",	simple_range(1, 1024),	set_int(config.nthreads))
		.value("admin",		nonempty_qstring,	set_string(config.admin))
		.value("use-dio",	simple_yesno,		set_yesno(config.use_dio))

	.block("stats")
		.value("interval",	simple_range(1, INT_MAX),	set_aint(stats.interval))
		.value("allow",		func(radix_prefix),		func(stats_access))
		.value("enable",	simple_yesno,			set_yesno(config.udp_stats))
		.value("listen",	ip_address_list,		add_ip(config.stats_hosts))

	.block("cache-dir", require_name)
		.end(func(set_cache))
		.value("size", simple_time, ignore)

	.block("listen", require_name)
		.end(func(set_listen))
		.value("port",		simple_range(1, 65535), ignore)
		.value("aftype",	func(v_aftype),		ignore)
		.value("group",		nonempty_qstring,	ignore)

	.block("backend-group", require_name)
		.end(func(set_backend_group))
		.value("lb-type",	func(v_lb_type),	ignore)
		.value("hosts",		func(v_hosts),		ignore)

	.block("backend", require_name)
		.end(func(set_backend))
		.value("port",		simple_range(1, 65535), ignore)
		.value("aftype",	func(v_aftype),		ignore)
		.value("group",		nonempty_qstring,	ignore)

	.block("access")
		.end(func(set_access))
		.value("allow",		func(radix_prefix),	ignore)
		.value("deny",		func(radix_prefix),	ignore)
		.value("apply-at",	func(v_apply_at),	ignore)
	;

	if ((t = conf::parse_file(file)) == NULL)
		return false;
	if (!conf.validate(*t))
		return false;

	/*
	 * Defaults
	 */
	stats.interval = DEFAULT_STATS_INTERVAL;
	config.nthreads = 1;
	config.admin = "nobody@example.com";
	poolnames["<default>"] = 0;
	bpools.insert(make_pair(0, backend_pool("<default>", lb_rr)));
	config.backend_keepalive = true;
	config.client_keepalive = true;
	config.keepalive_max = 0;
	config.max_redirects = 1;
	config.use_dio = false;
	config.x_follow = false;

	conf.set(*t);
	whttp_reconfigure();
	global_conf_tree = *t;
	return true;
}

void
wconfig_init(const char *file)
{
int	nerrors = 0;
	if (file == NULL)
		file = CONFIGFILE;
	conf::current_file = file;

	wlog(WLOG_NOTICE, format("loading configuration from %s")
		% conf::current_file);

	if (!read_config(file)) {
		wlog(WLOG_ERROR, "cannot load configuration");
		nerrors++;
	}
	
	if (!listeners.size()) {
		wlog(WLOG_ERROR, "no listeners defined");
		nerrors++;
	}
	if (!bpools.size()) {
		wlog(WLOG_ERROR, "no backends defined");
		nerrors++;
	}

	for (map<int, backend_pool>::iterator it = bpools.begin(), end = bpools.end();
	     it != end; ++it) {
		if (!it->second.size() && used_pools.find(it->first) != used_pools.end()) {
			wlog(WLOG_ERROR, format("backend group \"%s\" is used but has no backends")
				% it->second.name());
			nerrors++;
		}
	}

	if (nerrors) {
		wlog(WLOG_ERROR, 
			format("%d error(s) in configuration file.  cannot continue.")
			% nerrors);
		exit(8);
	}
}
