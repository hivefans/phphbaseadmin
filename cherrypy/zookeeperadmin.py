#!/usr/bin/env python
# -*- coding: utf8 -*-
import os,sys,time
from signal import SIGTERM
import cherrypy
from cherrypy.process.plugins import Daemonizer,PIDFile
from kazoo.client import KazooClient

try:
    import json
except ImportError:
    import simplejson as json

class ZookeeperAdmin(object):
    def index(self):
        cherrypy.response.headers['Content-Type'] = 'text/html' 
        return json.dumps({"test":"zookeeper admin"})
    index.exposed=True

    def stat(self,server='localhost',port=2181,command='ruok'):
        result=os.popen('echo '+command+'|nc '+server+' '+ port).read()
        return result
    stat.exposed = True
    def get(self,server='localhost',port=2181,path="/"):
        conn=ZookeeperAdmin()
        connstr=conn.stat(server,port)
        if(connstr=="imok"):
            serverport='%s:%s' %(server,port)
            zk = KazooClient(serverport,10.0)
            zk.start()
            data,stat=zk.get(path)
            zk.stop()
            result='{"data":"%s","czxid":"%s","mzxid":"%s","ctime":"%s","mtime":"%s","version":"%s","cversion":"%s","aversion":"%s","ephemeralOwner":"%s","dataLength":"%s","numChildren":"%s","pzxid":"%s"}' %(data,stat.czxid,stat.mzxid,stat.ctime,stat.mtime,stat.version,stat.cversion,stat.aversion,stat.ephemeralOwner,stat.dataLength,stat.numChildren,stat.pzxid)
            return result
        else:
            result="offline"
            return result
    get.exposed = True   
    def getchild(self,server='localhost',port=2181,path="/"):
        conn=ZookeeperAdmin()
        connstr=conn.stat(server,port)
        if(connstr=="imok"):
            serverport='%s:%s' %(server,port)
            zk = KazooClient(serverport,10.0)
            zk.start()
            children=zk.get_children(path)
            zk.stop()
            result=",".join(children)
            return result
        else:
            result="offline"
            return result
    getchild.exposed=True 

pidfile="/var/run/zookeeperadmin.pid"
def stop():
    try:
        pf=file(pidfile,'r')
        pid=int(pf.read().strip())
        pf.close()
    except IOError:
        pid=None
    if not pid:
        message="pidfile %s does not exist,Daemon not running?\n"
        sys.stderr.write(message % pidfile)
    try:
        while 1:
         os.kill(pid,SIGTERM)
         time.sleep(0.1)
    except OSError,err:
        err=str(err)
        if err.find("No such process")>0:
            if os.path.exists(pidfile):
               os.remove(pidfile)
        else:
            print str(err)
            sys.exit(1)
def start():
    cherrypy.engine.autoreload.stop()
    cherrypy.engine.autoreload.unsubscribe()
    settings = {
                'global': {
                    'server.socket_port' : 2080,
                    'server.socket_host': '0.0.0.0',
                    'server.socket_file': '',
                    'server.socket_queue_size': 100,
                    'server.protocol_version': 'HTTP/1.1',
                    'server.log_to_screen': True,
                    'server.log_file': '',
                    'server.reverse_dns': False,
                    'server.thread_pool': 200,
                    'server.environment': 'production',
                    'engine.timeout_monitor.on': False
                }
        }
    d = Daemonizer(cherrypy.engine)
    d.subscribe()
    PIDFile(cherrypy.engine,pidfile).subscribe()
    cherrypy.config.update(settings)
    cherrypy.tree.mount(ZookeeperAdmin(), '/')
    cherrypy.engine.start()

if "__main__" == __name__:
    if sys.argv[1]=='start':
       start()
    elif sys.argv[1]=='stop':
       stop()
    elif sys.argv[1]=='restart':
       stop()
       start()
    else:
       print "usage: %s start|stop|restart" %sys.argv[0]
       sys.exit(2)


