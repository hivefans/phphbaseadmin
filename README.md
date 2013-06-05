phphbaseadmin
=============

phphbaseadmin is a hbase admin web tool,it developed using thrift interface、php CodeIgniter framework .

Main features：<br>
       view table record <br>
       create table <br>
       batch delete tables<br>
       show table metadata<br>
       search table record<br>
       truncate table record<br>
       delete table   <br>
       update record<br>
       delete record<br>
![ScreenShot](https://raw.github.com/hivefans/phphbaseadmin/master/screeshot/main.png)   
![ScreenShot](https://raw.github.com/hivefans/phphbaseadmin/master/screeshot/createtable.png) 
![ScreenShot](https://raw.github.com/hivefans/phphbaseadmin/master/screeshot/search.png) 
![ScreenShot](https://raw.github.com/hivefans/phphbaseadmin/master/screeshot/record.png) 
![ScreenShot](https://raw.github.com/hivefans/phphbaseadmin/master/screeshot/metadata.png) 

<br>
Install:<br>
(1) setup apache or nginx <br>
(2) git clone phphbaseadmin ,put phphbaseadmin directory to web app directory <br>
(3) modify config.inc.php ,$configure['hbase_host']=your hbase thrift server <br>
(4) Follow the standard instructions for installing and running the HBase server ,start thrift server  <br>
     hbase thrift start  or  bin/hbase-daemon.sh start thrift



