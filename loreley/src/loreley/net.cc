/* Loreley: Lightweight HTTP reverse-proxy.                             */
/* net: Networking.							*/
/* Copyright (c) 2005, 2006 River Tarnell <river@attenuate.org>.        */
/*
 * Permission is granted to anyone to use this software for any purpose,
 * including commercial applications, and to alter it and redistribute it
 * freely. This software is provided 'as-is', without any express or implied
 * warranty.
 */

/* @(#) $Id$ */

#include <sys/types.h>
#include <sys/socket.h>
#include <sys/ioctl.h>

namespace sfun {
	using ::bind;	/* because of conflict with boost::bind from util.h */
};

#include "autoconf.h"
#ifdef HAVE_SYS_SENDFILE_H
# include <sys/sendfile.h>
#endif

/*
 * libevent needs these
 */
#ifndef HAVE_U_INT8_T
typedef uint8_t u_int8_t;
#endif

#ifndef HAVE_U_INT16_T
typedef uint16_t u_int16_t;
#endif

#ifndef HAVE_U_INT32_T
typedef uint32_t u_int32_t;
#endif

#ifndef HAVE_U_INT64_T
typedef uint64_t u_int64_t;
#endif

#include <netinet/tcp.h>
#include <arpa/inet.h>

#include <cstdio>
#include <cstring>
#include <cstdlib>
#include <cerrno>
#include <csignal>
#include <cassert>
#include <ctime>
#include <deque>
#include <map>
using std::deque;
using std::signal;
using std::multimap;

#include <event.h>
#include <unistd.h>
#include <fcntl.h>

#include "loreley.h"
#include "net.h"
#include "config.h"
#include "log.h"
#include "http.h"

#define RDBUF_INC	8192	/* buffer in 8 KiB incrs		*/

/* see ifname_to_address.cc */
int ifname_to_address(int, sockaddr_in *, char const *);
unsigned int if_nametoindex_wrap(const char *);

::event ev_sigint;
::event ev_sigterm;
//tss<event_base> evb;
lockable ev_lock;

enum evtype_t {
	evtype_timer,
	evtype_event
};

struct event_impl {
	void schedule(int64_t);

	int64_t			 ev_when;
	event_queue		*ev_queue;
	function<void (void)>	 ev_func;
	::event			 ev_event;
};

struct ev_pending {
	ev_pending(::event *ev, int64_t to, evtype_t type)
		: ep_event(ev)
		, ep_timeout(to)
		, ep_type(type) {}

	::event	*ep_event;
	int64_t	 ep_timeout;
	evtype_t ep_type;
};

multimap<int, ev_pending> ev_pending_list;

pthread_t io_loop_thread;

struct eq_entry {
	eq_entry (net::socket *s, event_impl *ev, int flags)
		: ee_sock(s)
		, ee_event(ev)
		, ee_flags(flags) {}

	net::socket	*ee_sock;
	event_impl	*ee_event;
	int		 ee_flags;
};

struct event_queue {
	event_queue() {
		pthread_mutex_init(&eq_lock, NULL);
		pthread_cond_init(&eq_cond, NULL);
	}

	void add(net::socket *sock, event_impl *event, int flags) {
		struct lvars {
			lvars(pthread_mutex_t *l) 
				: lock(l) {
				pthread_mutex_lock(lock);
			}
			~lvars() {
				pthread_mutex_unlock(lock);
			}
			pthread_mutex_t *lock;
		} v(&eq_lock);
		WDEBUG("event_queue::add: adding event");
		eq_events.push_back(new eq_entry(sock, event, flags));
		pthread_cond_signal(&eq_cond);
	}

	pthread_mutex_t	eq_lock;
	pthread_cond_t	eq_cond;
	deque<eq_entry *>	eq_events;
};

tss<event_queue> ev_queue;

static void sig_exit(int, short, void *);

ioloop_t *ioloop;

char current_time_str[30];
char current_time_short[30];
time_t current_time;

static void secondly_sched(void);

bool wnet_exit;
vector<wsocket *>	awaks;
size_t			cawak;

void
wnet_add_accept_wakeup(wsocket *s)
{
	awaks.push_back(s);
}

net::event	secondly_ev;

static void
secondly_update(void)
{
	WDEBUG("secondly_update");
	wnet_set_time();
	secondly_sched();
}

static void
secondly_sched(void)
{
	secondly_ev.schedule(secondly_update, 1000);
}

pthread_cond_t iot_ready;
pthread_mutex_t iot_ready_m;

pthread_t io_thread;
void usr2_handler(int, short, void *)
{
	WDEBUG("got USR2");
	event_loopexit(NULL);
}

::event ev_sigusr2;

void *
io_start(void *)
{
	signal_set(&ev_sigusr2, SIGUSR2, usr2_handler, NULL);
	signal_add(&ev_sigusr2, NULL);

	io_loop_thread = pthread_self();
	pthread_mutex_lock(&iot_ready_m);
	pthread_cond_signal(&iot_ready);
	pthread_mutex_unlock(&iot_ready_m);

	ioloop->run();
	return NULL;
}



ioloop_t::ioloop_t(void)
{
	prepare();
}

void
ioloop_t::prepare(void)
{
size_t	 i;

	event_init();
	signal(SIGUSR2, SIG_IGN);
	pthread_mutex_init(&iot_ready_m, NULL);
	pthread_cond_init(&iot_ready, NULL);

	pthread_mutex_lock(&iot_ready_m);
	pthread_create(&io_thread, NULL, io_start, NULL);
	pthread_cond_wait(&iot_ready, &iot_ready_m);
	pthread_mutex_unlock(&iot_ready_m);

	wlog.notice(format("maximum number of open files: %d")
		% getdtablesize());
	
	signal(SIGPIPE, SIG_IGN);

	for (i = 0; i < listeners.size(); ++i) {
	listener	*lns = listeners[i];

		try {
			lns->sock->reuseaddr(true);
			lns->sock->bind();
			lns->sock->listen();
		} catch (socket_error &e) {
			wlog.error(format("creating listener %s: %s")
				% lns->sock->straddr() % e.what());
			exit(8);
		}

		lns->sock->readback(bind(&ioloop_t::_accept, this, _1, _2), -1);
	}
	wlog.notice(format("net: initialised, using libevent %s (%s)")
		% event_get_version() % event_get_method());
	secondly_sched();
}

void
ioloop_t::_accept(wsocket *s, int)
{
	wsocket		*newe;
static rate_limited_logger enfile_log(60, ll_warn, "accept error: %s");

	if ((newe = s->accept("HTTP client", prio_norm)) == NULL) {
		if (errno == ENFILE || errno == EMFILE)
			enfile_log.log(strerror(errno));
		else
			wlog.warn(format("accept error: %s") % strerror(errno));
		s->readback(bind(&ioloop_t::_accept, this, _1, _2), -1);
		return;
	}

	s->readback(bind(&ioloop_t::_accept, this, _1, _2), -1);

	newe->nonblocking(true);

	if (cawak == awaks.size())
		cawak = 0;
char	buf[sizeof(wsocket *) * 2];
	memcpy(buf, &newe, sizeof(newe));
	memcpy(buf + sizeof(newe), &s, sizeof(s));
	WDEBUG(format("_accept, lsnr=%d") % s);

	if (awaks[cawak]->write(buf, sizeof(wsocket *) * 2) < 0) {
		wlog.error(format("writing to thread wakeup socket: %s")
			% strerror(errno));
		exit(1);
	}
	cawak++;
	return;
}

void
wnet_set_time(void)
{
struct	tm	*now;
	time_t	 old = current_time;
	size_t	 n;
	
	current_time = time(NULL);
	if (current_time == old)
		return;

	now = gmtime(&current_time);

	n = strftime(current_time_str, sizeof(current_time_str), "%a, %d %b %Y %H:%M:%S GMT", now);
	assert(n);
	n = strftime(current_time_short, sizeof(current_time_short), "%Y-%m-%d %H:%M:%S", now);
	assert(n);
}

namespace net {

void
socket_callback(int fd, short ev, void *d)
{
wsocket	*s = (wsocket *)d;

	HOLDING(ev_lock);
	WDEBUG(format("[%d] _ev_callback: %s%son %d (%s) queue %p")
		% pthread_self()
		% ((ev & EV_READ) ? "read " : "")
		% ((ev & EV_WRITE) ? "write " : "")
		% fd % s->_desc
		% s->_queue);

	s->_queue->add(s, NULL, ev);
}

event::event(void)
{
	impl = new event_impl;
}

event::~event(void)
{
	delete impl;
};

void
event::schedule(function<void (void)> f, int64_t t)
{
	impl->ev_func = f;
	impl->schedule(t);
}

void
timer_callback(int, short, void *d)
{
event_impl	*ev = (event_impl *)d;
	HOLDING(ev_lock);
	ev->ev_queue->add(NULL, ev, 0);
}

void
socket::_register(int what, int64_t to, socket::call_type handler)
{
	_ev_flags = 0;
	_queue = (event_queue *)ev_queue;

	WDEBUG(format("_register: %s%son %d (%s), queue %p")
		% ((what & FDE_READ) ? "read " : "")
		% ((what & FDE_WRITE) ? "write " : "")
		% _s % _desc % _queue);

	HOLDING(ev_lock);

	if (what & FDE_READ) {
		_read_handler = handler;
		_ev_flags |= EV_READ;
	}
	if (what & FDE_WRITE) {
		_write_handler = handler;
		_ev_flags |= EV_WRITE;
	}

	event_set(ev, _s, _ev_flags, socket_callback, this);
	event_priority_set(ev, (int) _prio);

	WDEBUG(format("timeout = %d") % to);

	ev_pending_list.insert(make_pair(_s, ev_pending(ev, to, evtype_event)));
	pthread_kill(io_loop_thread, SIGUSR2);
}

address::address(void)
{
	memset(&_addr, 0, sizeof(_addr));
	_addrlen = 0;
	_fam = AF_UNSPEC;
	_stype = _prot = 0;
}

address::address(sockaddr *sa, socklen_t len)
{
	memcpy(&_addr, sa, len);
	_addrlen = len;
	_stype = _prot = 0;
	_fam = ((sockaddr_storage *)sa)->ss_family;
}

address::address(addrinfo *ai) 
{
	memcpy(&_addr, ai->ai_addr, ai->ai_addrlen);
	_addrlen = ai->ai_addrlen;
	_fam = ai->ai_family;
	_stype = ai->ai_socktype;
	_prot = ai->ai_protocol;
}

socket *
address::makesocket(char const *desc, sprio p) const
{
	return new socket(*this, desc, p);
}

address::address(address const &o)
	: _addrlen(o._addrlen)
	, _fam(o._fam)
	, _stype(o._stype)
	, _prot(o._prot) {
	memcpy(&_addr, &o._addr, _addrlen);
}

address &
address::operator= (address const &o)
{
	_addrlen = o._addrlen;
	_fam = o._fam;
	_stype = o._stype;
	_prot = o._prot;
	memcpy(&_addr, &o._addr, _addrlen);
	return *this;
}

string const &
address::straddr(bool lng) const
{
char	res[NI_MAXHOST];
int	i;
	if (!lng) {
		if (_shortaddr.empty()) {
			if ((i = getnameinfo(sockaddr_cast<sockaddr const *>(&_addr),
			    _addrlen, res, sizeof(res), NULL, 0, NI_NUMERICHOST)) != 0)
				throw resolution_error(i);
			_shortaddr = res; 
		}
		return _shortaddr;
	}

	if (_straddr.empty()) {
	char	port[NI_MAXSERV];
		if ((i = getnameinfo(sockaddr_cast<sockaddr const *>(&_addr),
		    _addrlen, res, sizeof(res), port, sizeof(port), 
			NI_NUMERICHOST | NI_NUMERICSERV)) != 0)
			throw resolution_error(i);
		_straddr = str(format("[%s]:%s") % res % port);
	}
	return _straddr;
}

addrlist *
addrlist::resolve(string const &addr, string const &port,
		  enum socktype socktype, int family)
{
addrinfo	 hints, *res, *ai;
int		 r;
	memset(&hints, 0, sizeof(hints));
	hints.ai_socktype = (int) socktype;
	if (family != AF_UNSPEC)
		hints.ai_family = family;

	if ((r = getaddrinfo(addr.c_str(), 
	    port.c_str(), &hints, &res)) != 0)
		throw resolution_error(r);

addrlist	*al = new addrlist;
	for (ai = res; ai; ai = ai->ai_next)
		al->_addrs.push_back(address(ai));

	freeaddrinfo(res);
	return al;
}

address
addrlist::first(string const &addr, int port,
		enum socktype socktype, int family)
{
	return first(addr, lexical_cast<string>(port), socktype, family);
}

addrlist *
addrlist::resolve(string const &addr, int port, 
		  enum socktype socktype, int family)
{
	return resolve(addr, lexical_cast<string>(port), socktype, family);
}

address
addrlist::first(string const &addr, string const &port,
		enum socktype socktype, int family)
{
addrlist	*r = addrlist::resolve(addr, port, socktype, family);
address		 res;
	res = *r->begin();
	delete r;
	return res;
}

addrlist::~addrlist(void)
{
}

addrlist::iterator
addrlist::begin(void) const
{
	return _addrs.begin();
}

addrlist::iterator
addrlist::end(void) const
{
	return _addrs.end();
}

socket *
addrlist::makesocket(char const *desc, sprio p) const
{
iterator	it = _addrs.begin(), end = _addrs.end();
	for (; it != end; ++it) {
	socket	*ns;
		if ((ns = it->makesocket(desc, p)) != NULL)
			return ns;
	}
	throw socket_error();
}

socket *
socket::create(string const &addr, int port,
	       enum socktype socktype, char const *desc, sprio p, int family)
{
	return create(addr, lexical_cast<string>(port), socktype, desc, p, family);
}

socket *
socket::create(string const &addr, string const &port,
	       enum socktype socktype, char const *desc, sprio p, int family)
{
addrlist	*al = addrlist::resolve(addr, port, socktype, family);
	return al->makesocket(desc, p);
}

pair<socket *, socket *>
socket::socketpair(enum socktype st)
{
socket	*s1 = NULL, *s2 = NULL;
int	 sv[2];
	if (::socketpair(AF_UNIX, (int) st, 0, sv) == -1)
		throw socket_error();
	s1 = new socket(sv[0], net::address(), "socketpair", prio_norm);
	try {
		s2 = new socket(sv[1], net::address(), "socketpair", prio_norm);
	} catch (...) {
		delete s1;
		throw;
	}
	return make_pair(s1, s2);
}

connect_status
socket::connect(void)
{
	if (::connect(_s, _addr.addr(), _addr.length()) == -1)
		if (errno == EINPROGRESS)
			return connect_later;
		else
			throw socket_error();
	return connect_okay;
}

socket *
socket::accept(char const *desc, sprio p)
{
int			ns;
sockaddr_storage	addr;
socklen_t		addrlen = sizeof(addr);
	if ((ns = ::accept(_s, (sockaddr *)&addr, &addrlen)) == -1)
		return NULL;
	return new socket(ns, net::address((sockaddr *)&addr, addrlen), desc, p);
}

int
socket::recvfrom(char *buf, size_t count, net::address &addr)
{
sockaddr_storage	saddr;
socklen_t		addrlen = sizeof(addr);
int			i;
	if ((i = ::recvfrom(_s, buf, count, 0, (sockaddr *)&saddr, &addrlen)) < 0)
		return i;
	WDEBUG(format("recvfrom: fam=%d") % saddr.ss_family);
	addr = net::address((sockaddr *)&saddr, addrlen);
	return i;
}

int
socket::sendto(char const *buf, size_t count, net::address const &addr)
{
	return ::sendto(_s, buf, count, 0, addr.addr(), addr.length());
}

void
socket::nonblocking(bool v)
{
int	val;
	val = fcntl(_s, F_GETFL, 0);
	if (val == -1)
		throw socket_error();
	if (v)
		val |= O_NONBLOCK;
	else	val &= ~O_NONBLOCK;

	if (fcntl(_s, F_SETFL, val) == -1)
		throw socket_error();
}

void
socket::reuseaddr(bool v)
{
int	i = v;
int	len = sizeof(i);
	setopt(SOL_SOCKET, SO_REUSEADDR, &i, len);
}

void
socket::cork(void)
{
int	one = 1;
	setopt(IPPROTO_TCP, TCP_CORK, &one, sizeof(one));
}

void
socket::uncork(void)
{
int	zero = 0;
	setopt(IPPROTO_TCP, TCP_CORK, &zero, sizeof(zero));
}

int
socket::getopt(int level, int what, void *addr, socklen_t *len) const
{
int	i;
	if ((i = getsockopt(_s, level, what, addr, len)) == -1)
		throw socket_error();
	return i;
}

int
socket::setopt(int level, int what, void *addr, socklen_t len)
{
int	i;
	if ((i = setsockopt(_s, level, what, addr, len)) == -1)
		throw socket_error();
	return i;
}

int
socket::error(void) const
{
int		error = 0;
socklen_t	len = sizeof(error);
	try {
		getopt(SOL_SOCKET, SO_ERROR, &error, &len);
		return error;
	} catch (socket_error &) {
		return 0;
	}
}

socket::socket(int s, net::address const &a, char const *desc, sprio p)
	: _addr(a)
	, _desc(desc)
	, _prio(p)
{
	ev = new ::event;
	memset(ev, 0, sizeof(*ev));
	_s = s;
}

socket::socket(net::address const &a, char const *desc, sprio p)
	: _addr(a)
	, _desc(desc)
	, _prio(p)
{
	ev = new ::event;
	memset(ev, 0, sizeof(*ev));
	_s = ::socket(_addr.family(), _addr.socktype(), _addr.protocol());
	if (_s == -1)
		throw socket_error();
}

void
socket::bind(void)
{
int	one = 1;
	setopt(SOL_SOCKET, SO_REUSEADDR, &one, sizeof(one));
	if (sfun::bind(_s, _addr.addr(), _addr.length()) == -1)
		throw socket_error();
}

void
socket::listen(int bl)
{
	if (::listen(_s, bl) == -1)
		throw socket_error();
}

socket::~socket(void)
{
	WDEBUG("closing socket");
	HOLDING(ev_lock);
	multimap<int, ev_pending>::iterator it;
	it = ev_pending_list.find(_s);
	if (it != ev_pending_list.end())
		ev_pending_list.erase(it);

	event_del(ev);
	delete ev;
	close(_s);
}

void
socket::clearbacks(void)
{
	event_del(ev);
}

void
socket::mcast_join(string const &ifname)
{
	switch (_addr.family()) {
	case AF_INET: {
	struct address	 ifaddr = address::from_ifname(_s, ifname);
	sockaddr_in	*inbind = (sockaddr_in *)_addr.addr();
	sockaddr_in	*inif   = (sockaddr_in *)ifaddr.addr();
	ip_mreq		 mr;
		memset(&mr, 0, sizeof(mr));
		mr.imr_multiaddr.s_addr = inbind->sin_addr.s_addr;
		mr.imr_interface.s_addr = inif->sin_addr.s_addr;
		WDEBUG(format("NET: %s joins mcast on if %s")
			% straddr() % ifaddr.straddr());
		setopt(IPPROTO_IP, IP_ADD_MEMBERSHIP, &mr, sizeof(mr));
		break;
	}

	case AF_INET6: {
	u_int		 ifindex = address::ifname_to_index(ifname);
	sockaddr_in6	*inbind = (sockaddr_in6 *)_addr.addr();
	ipv6_mreq	 mr;
		memset(&mr, 0, sizeof(mr));
		memcpy(&mr.ipv6mr_multiaddr, &inbind->sin6_addr,
			sizeof(mr.ipv6mr_multiaddr));
		mr.ipv6mr_interface = ifindex;
		setopt(IPPROTO_IPV6, IPV6_ADD_MEMBERSHIP, &mr, sizeof(mr));
		break;
	}

	default:
		throw socket_error("multicast join not applicable for this socket type");
	}
}

u_int
address::ifname_to_index(string const &ifname)
{
u_int	ret = if_nametoindex_wrap(ifname.c_str());
	if (ret == 0)
		throw socket_error("named interface does not exist");
	return ret;
}

address
address::from_ifname(int s, string const &ifname)
{
sockaddr_in	addr;
	if (ifname_to_address(s, &addr, ifname.c_str()) < 0)
		throw socket_error();
address	ret((sockaddr *)&addr, sizeof(sockaddr_in));
	return ret;
}

} // namespace net

void
make_event_base(void)
{
//static lockable meb_lock;
//	if (evb == NULL) {
//		HOLDING(meb_lock);
//		evb = (event_base *)event_init();
//		event_base_priority_init(evb, prio_max);
//		signal_set(&ev_sigint, SIGINT, sig_exit, NULL);
//		signal_add(&ev_sigint, NULL);
//		signal_set(&ev_sigterm, SIGTERM, sig_exit, NULL);
//		signal_add(&ev_sigterm, NULL);
//	}
	io_loop_thread = pthread_self();
	ev_queue = new event_queue;
}

void
sig_exit(int sig, short what, void *d)
{
	wnet_exit = true;
}

void
ioloop_t::run(void)
{
	WDEBUG(format("[%d] ioloop run: running") % pthread_self());
	while (!wnet_exit) {
		event_loop(EVLOOP_ONCE);
		WDEBUG("ioloop thread: got event");

	{

		HOLDING(ev_lock);
	multimap<int, ev_pending>::iterator  it = ev_pending_list.begin(),
						end = ev_pending_list.end();
		for (; it != end; ++it) {
			WDEBUG("ioloop thread: processing new event");
			if (event_pending(it->second.ep_event, EV_READ | EV_WRITE, NULL))
				event_del(it->second.ep_event);

			if (it->second.ep_type == evtype_event) {
				if (it->second.ep_timeout == -1) {
					event_add(it->second.ep_event, NULL);
				} else {
				timeval	tv;
				int64_t	usec = it->second.ep_timeout * 1000;
					tv.tv_sec = usec / 1000000;
					tv.tv_usec = usec % 1000000;
					WDEBUG(format("timeout: %d %d") % tv.tv_sec % tv.tv_usec);
					event_add(it->second.ep_event, &tv);
				}
			} else if (it->second.ep_type == evtype_timer) {
				timeval	tv;
				int64_t	usec = it->second.ep_timeout * 1000;
					tv.tv_sec = usec / 1000000;
					tv.tv_usec = usec % 1000000;
					WDEBUG(format("timeout: %d %d") % tv.tv_sec % tv.tv_usec);
					evtimer_add(it->second.ep_event, &tv);
			} else
				abort();
		}
		ev_pending_list.clear();
	}
	}

size_t	 i;
	for (i = 0; i < listeners.size(); ++i)
		delete listeners[i];
}

void
ioloop_t::thread_run(void)
{
	/*
	 * ioloop for a single thread.  uses a condition variable and a queue.
	 */
event_queue	*eq = (event_queue *)ev_queue;

	for (;;) {
	deque<eq_entry *> evs;
		WDEBUG(format("[%d] thread_run: waiting for event, eq=%p")
			% pthread_self() % eq);
		pthread_mutex_lock(&eq->eq_lock);
		if (eq->eq_events.empty())
			pthread_cond_wait(&eq->eq_cond, &eq->eq_lock);
		WDEBUG(format("[%d] thread_run: got event") % pthread_self());
		evs = eq->eq_events;
		eq->eq_events.clear();
		pthread_mutex_unlock(&eq->eq_lock);
		for (deque<eq_entry *>::iterator
		     it = evs.begin(),
		     end = evs.end(); it != end; ++it) {
			if ((*it)->ee_sock) {
				WDEBUG(format("[%d] thread_run: got event on %s")
					% pthread_self() % (*it)->ee_sock->_desc);
				if ((*it)->ee_flags & EV_READ)
					(*it)->ee_sock->_read_handler((*it)->ee_sock, false);
				if ((*it)->ee_flags & EV_WRITE)
					(*it)->ee_sock->_write_handler((*it)->ee_sock, false);
				if ((*it)->ee_flags & EV_TIMEOUT) {
					if ((*it)->ee_sock->_ev_flags & EV_READ) {
						(*it)->ee_sock->_read_handler((*it)->ee_sock, true);
					} else if ((*it)->ee_sock->_ev_flags & EV_WRITE) {
						(*it)->ee_sock->_write_handler((*it)->ee_sock, true);
					}
				}
			} else {
				WDEBUG("event thread");
				(*it)->ee_event->ev_func();
			}
			delete *it;
		}
	}
}


void
event_impl::schedule(int64_t when)
{
	HOLDING(ev_lock);
	WDEBUG(format("schedule, when=%d") % when);
	this->ev_when = when;
	this->ev_queue = (event_queue *)::ev_queue;
	evtimer_set(&ev_event, timer_callback, this);
	ev_pending_list.insert(make_pair(0, ev_pending(&ev_event, ev_when, evtype_timer)));
	pthread_kill(io_loop_thread, SIGUSR2);
}
