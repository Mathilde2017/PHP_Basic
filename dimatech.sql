# Host: localhost  (Version: 5.5.53)
# Date: 2018-12-19 17:31:08
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "admins"
#

CREATE TABLE `admins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员id',
  `username` varchar(10) NOT NULL COMMENT '登录名',
  `truename` varchar(30) NOT NULL DEFAULT '真实姓名',
  `phone` varchar(15) NOT NULL DEFAULT '' COMMENT '联系电话',
  `sex` tinyint(1) NOT NULL DEFAULT '1' COMMENT '性别，“1：男，0：女，默认1“',
  `email` varchar(50) NOT NULL COMMENT '邮箱',
  `group` varchar(20) NOT NULL COMMENT '所属分组',
  `password` varchar(50) NOT NULL COMMENT '登录密码',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：1正常，0禁用 默认1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

#
# Data for table "admins"
#

INSERT INTO `admins` VALUES (2,'mathilde','马爸爸','18366668888',1,'18366668888@163.com','1','765cff40e075e07aa32b5a55194e84a3',1543626000,1),(3,'wangergou','王二狗','13522334455',0,'13522334455@163.com','2','133474fe34f01babf695296482bf19bc',1543626059,0),(4,'wugou','吴二狗','13466778899',1,'13466778899@163.com','2','dc77a637b0eb7394c79b5d6dd668bf6b',1544414400,1),(5,'zhangergou','张二狗','13022334455',1,'13022334455@163.com','2','be40b62ba99b6eb6d15c29760cd3bbfa',1544866200,0),(6,'shiergou','雨二狗','13655778899',0,'13655778899@163.com','3','0e47e0e437d0b00194c121aa8b738353',1545198071,1);

#
# Structure for table "news"
#

CREATE TABLE `news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '新闻ID',
  `title` varchar(255) NOT NULL COMMENT '新闻标题',
  `sort` char(20) NOT NULL COMMENT '新闻分类',
  `desc` varchar(255) NOT NULL COMMENT '新闻简介',
  `content` text NOT NULL COMMENT '新闻内容',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：1正常，0禁用 默认1',
  `time` int(10) unsigned NOT NULL COMMENT '新闻发布时间',
  `username` varchar(30) NOT NULL COMMENT '新闻发布管理员',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "news"
#

