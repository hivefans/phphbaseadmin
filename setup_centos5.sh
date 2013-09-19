yum -y groupinstall "Development Tools"
yum -y install gcc-c++
rpm -Uvh http://dl.fedoraproject.org/pub/epel/5/x86_64/epel-release-5-4.noarch.rpm
yum -y install git
yum install -y php53 php53-cli php53-devel php53-common httpd httpd-devel php53-mbstring php53-mysql php53-pdo php53-process mysql mysql-devel mysql-server wget lrzsz dos2unix pexpect libxml2 libxml2-devel MySQL-python
/sbin/service mysqld start
/sbin/service iptables stop
/sbin/chkconfig --del iptables
/sbin/service httpd start
cd /var/www/html
git clone https://github.com/hivefans/phphbaseadmin
yum -y install python26 
cd phphbaseadmin/cherrypy
 sh setuptools-0.6c11-py2.6.egg 
 tar zxvf pip-1.4.1.tar.gz
 cd pip-1.4.1
 python26 setup.py install
 pip install kazoo
 cd ..
 tar zxvf CherryPy-3.2.2.tar.gz
 cd CherryPy-3.2.2
 python26 setup.py install
 cd ..
 python26 zookeeperadmin.py start
 echo "All done.open http://your_ip/phphbaseadmin from your web browser"
