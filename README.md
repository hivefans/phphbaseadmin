phphbaseadmin
=============

phphbaseadmin is a hbase admin web tool,it developed using thrift interface,php (CodeIgniter),python(cherrypy,kazoo),bootstrap,kendo ui,jquery and so on.

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
![ScreenShot](https://raw.github.com/hivefans/phphbaseadmin/master/screeshot/zookeeper.png)
![ScreenShot](https://raw.github.com/hivefans/phphbaseadmin/master/screeshot/zkdata.png) 
![ScreenShot](https://raw.github.com/hivefans/phphbaseadmin/master/screeshot/zkstattrend.png) 

<br>
##Support
. Support  CentOS 5.x or 6.x,Uses Apache Hbase or Cloudera Hbase 0.92 or later
## Prerequisites
1. apache or nginx,php,mysql server
2. Python 2.6 or later from <http://www.python.org/getit/>
3. cherrypy from <http://www.cherrypy.org/>
4. kazoo from <https://kazoo.readthedocs.org/en/latest/>
5. Apache Hbase thrift server 
##  Install:
--------------------<br>
(1) execute script setup_centos5.sh or setup_centos6.sh to install apache php mysql-server <br>
(2) Follow the standard instructions for installing and running the HBase server ,start thrift server  <br>
     hbase thrift start  or  bin/hbase-daemon.sh start thrift
<br>
(3) modify config.inc.php ,$configure['hbase_host']=your hbase thrift server <br>
(4) create database phphbaseadmin in mysql server ,import database/phphbaseadmin.sql,edit application/config/database.php,$db['default']['hostname']、 $db['default']['username'] 、$db['default']['password'] = '';
<br>
(5) open http://serverip/phphbaseadmin in your browser, default user is admin ,password is admin888
(6) after the user login,select system->user manager menu to set user table grant
(7) select Tables->view menu to view table record
##  安装方法 <br>
(1) 使用根目录中的 setup_centos5.sh 或者setup_centos6.sh 脚本安装所需环境
(2) 启动hbase thrift server     
(3) 修改根目录中的配置文件 config.inc.php,修改$configure['hbase_host']=你的thrift server服务器地址
(4) 在mysql server中创建数据库phphbaseadmin ,导入database/phphbaseadmin.sql文件，修改application/config/database.php,$db['default']['hostname']、 $db['default']['username'] 、$db['default']['password'] = '';
(5) 打开浏览器访问 http://serverip/phphbaseadmin，缺省用户名admin 密码admin888登录
(6) 登录后选择 system->user manager 菜单设置用户所属hbase table表的所属权限
(7) 选择 Tables->view 菜单即可查看hbase table 记录。

