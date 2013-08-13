#!/usr/bin/env python
# -*- coding: utf8 -*-
import os,sys
import cherrypy
from cherrypy.process.plugins import Daemonizer
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

cherrypy.process.plugins.Daemonizer(cherrypy.engine).subscribe()
if "__main__" == __name__:
    cherrypy.engine.autoreload.stop()
    cherrypy.engine.autoreload.unsubscribe()
    settings = {
                'global': {
                    'server.socket_port' : 8080,
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
    cherrypy.config.update(settings)
    cherrypy.tree.mount(ZookeeperAdmin(), '/')
    cherrypy.engine.start()

