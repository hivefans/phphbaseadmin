#!/usr/bin/python
# -*- coding: utf8 -*-
import os

os.system('yum install -y php php-cli php-devel php-common httpd httpd-devel php-mbstring php-mysql php-pdo php-process mysql mysql-devel mysql-server wget lrzsz dos2unix pexpect libxml2 libxml2-devel MySQL-python')
os.system('/sbin/service mysqld start')
os.system('/sbin/service httpd start')
os.system('/sbin/service iptables stop')
os.system('/sbin/chkconfig --del iptables')
print "/*************************************************************/"
print 'Create http directory...'
os.system('mkdir -p /var/www/html/phpHBaseAdmin')
print 'Copy files to http directory....';
os.system('cp -r * /var/www/html/phpHBaseAdmin')
print 'All done. Access http://your_ip/phpHBaseAdmin from your web browser'
print "/*************************************************************/"
