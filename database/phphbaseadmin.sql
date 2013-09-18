/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50524
Source Host           : localhost:3306
Source Database       : phphbaseadmin

Target Server Type    : MYSQL
Target Server Version : 50524
File Encoding         : 65001

Date: 2013-09-13 18:13:52
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for member
-- ----------------------------
DROP TABLE IF EXISTS `member`;
CREATE TABLE `member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group` varchar(255) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `pass_word` varchar(32) DEFAULT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `grant` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of member
-- ----------------------------
INSERT INTO `member` VALUES ('1', 'admin', 'admin', '7fef6171469e80d32c0559f88b377245', 'shidongjie@baofeng.com', 'test');

-- ----------------------------
-- Table structure for usergroup
-- ----------------------------
DROP TABLE IF EXISTS `usergroup`;
CREATE TABLE `usergroup` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of usergroup
-- ----------------------------
INSERT INTO `usergroup` VALUES ('1', 'admin');

-- ----------------------------
-- Table structure for zookeeper_cluster
-- ----------------------------
DROP TABLE IF EXISTS `zookeeper_cluster`;
CREATE TABLE `zookeeper_cluster` (
  `cluster_id` int(11) NOT NULL AUTO_INCREMENT,
  `cluster_name` varchar(255) NOT NULL,
  `server_list` varchar(255) NOT NULL,
  PRIMARY KEY (`cluster_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of zookeeper_cluster
-- ----------------------------
INSERT INTO `zookeeper_cluster` VALUES ('6', 'gitlab', '192.168.205.6:2181');
