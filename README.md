phphbaseadmin
=============

phphbaseadmin is a hbase admin web tool,it developed using thrift interface、php CodeIgniter framework .

Main features：
--------------
       user grant manage
       view table record 
       create table
       batch delete tables
       search table record
       truncate table record
       delete table   
       update record
       delete record
       monitor zookeeper
![ScreenShot](https://raw.github.com/hivefans/phphbaseadmin/master/screeshot/login.png)
![ScreenShot](https://raw.github.com/hivefans/phphbaseadmin/master/screeshot/main.png)   
![ScreenShot](https://raw.github.com/hivefans/phphbaseadmin/master/screeshot/createtable.png) 
![ScreenShot](https://raw.github.com/hivefans/phphbaseadmin/master/screeshot/search.png) 
![ScreenShot](https://raw.github.com/hivefans/phphbaseadmin/master/screeshot/record.png) 
![ScreenShot](https://raw.github.com/hivefans/phphbaseadmin/master/screeshot/metadata.png) 

<br>
Install:
--------------------
(1) setup apache or nginx <br>
(2) git clone phphbaseadmin ,put phphbaseadmin directory to web app directory <br>
(3) modify config.inc.php ,$configure['hbase_host']=your hbase thrift server <br>
(4) Follow the standard instructions for installing and running the HBase server ,start thrift server  <br>
     hbase thrift start  or  bin/hbase-daemon.sh start thrift
<br>
(5) create database phphbaseadmin in mysql server ,import database/phphbaseadmin.sql,modify application/config/database.php,$db['default']['hostname']、 $db['default']['username'] 、$db['default']['password'] = '';
<br>
(6) if you use zookeeper monitor,please install cherry frame,then run cherrypy/cherry.py
<br>
 tar zxvf cherrypy/CherryPy-3.2.2.tar.gz
<br>
cd cherrypy/CherryPy-3.2.2
<br>
 python setup.py install
<br>
run cherrypy/cherrypy.py
<br>
(7) open http://serverip/phphbaseadmin in your browser, default user is admin ,password is admin888


