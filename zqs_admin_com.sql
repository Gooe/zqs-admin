/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50644
Source Host           : localhost:3306
Source Database       : zqs_admin_com

Target Server Type    : MYSQL
Target Server Version : 50644
File Encoding         : 65001

Date: 2020-04-12 20:37:50
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for zqs_admin
-- ----------------------------
DROP TABLE IF EXISTS `zqs_admin`;
CREATE TABLE `zqs_admin` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(20) NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` varchar(30) NOT NULL DEFAULT '' COMMENT '密码盐',
  `headimg` int(10) NOT NULL DEFAULT '0' COMMENT '头像',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `loginfailure` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '失败次数',
  `login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录时间',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `token` varchar(59) NOT NULL DEFAULT '' COMMENT 'Session标识',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Records of zqs_admin
-- ----------------------------
INSERT INTO `zqs_admin` VALUES ('1', 'admin', '超级管理', '22ab08e556ca8e6287d85dfd8d1d4e16', 'L3edBl', '2', 'admin@admin.com', '0', '1586522665', '1492186163', '1586626724', 'b4da902d-1223-4b6f-a2a7-83684f1d1683', '1');
INSERT INTO `zqs_admin` VALUES ('4', 'admin1', 'admin1', '5f1d7a84db00d2fce00b31a7fc73224f', '123456', '0', 'b@c.com', '0', '1586628843', '1586180353', '1586678370', '256a073a-b560-44ba-9236-355d3fcc8fdc', '1');
INSERT INTO `zqs_admin` VALUES ('5', 't1', 't1', '044dd47a7fb0dd71bbcc7feb0a21c2f0', 'Ir0Veh', '0', '11@qq.com', '0', '1586191210', '1586180889', '1586340073', '960aea62-a69f-4fe8-b2be-d02a6b00020a', '1');

-- ----------------------------
-- Table structure for zqs_admin_log
-- ----------------------------
DROP TABLE IF EXISTS `zqs_admin_log`;
CREATE TABLE `zqs_admin_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `username` varchar(30) NOT NULL DEFAULT '' COMMENT '管理员名字',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT '操作页面',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '日志标题',
  `content` text NOT NULL COMMENT '内容',
  `ip` varchar(50) NOT NULL DEFAULT '' COMMENT 'IP',
  `useragent` varchar(255) NOT NULL DEFAULT '' COMMENT 'User-Agent',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作时间',
  PRIMARY KEY (`id`),
  KEY `name` (`username`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员日志表';

-- ----------------------------
-- Records of zqs_admin_log
-- ----------------------------

-- ----------------------------
-- Table structure for zqs_attachment
-- ----------------------------
DROP TABLE IF EXISTS `zqs_attachment`;
CREATE TABLE `zqs_attachment` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '物理路径',
  `imagewidth` varchar(30) NOT NULL DEFAULT '' COMMENT '宽度',
  `imageheight` varchar(30) NOT NULL DEFAULT '' COMMENT '宽度',
  `imagetype` varchar(30) NOT NULL DEFAULT '' COMMENT '图片类型',
  `imageframes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '图片帧数',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `mimetype` varchar(30) NOT NULL DEFAULT '' COMMENT 'mime类型',
  `extparam` varchar(255) NOT NULL DEFAULT '' COMMENT '透传数据',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建日期',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `upload_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上传时间',
  `sha1` varchar(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `storage` varchar(100) NOT NULL DEFAULT 'local' COMMENT '存储位置,local,qiniu',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `sha1` (`sha1`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='附件表';

-- ----------------------------
-- Records of zqs_attachment
-- ----------------------------
INSERT INTO `zqs_attachment` VALUES ('1', 'timg.jpg', '/storage/uploads/image/20200410\\15146e6ae4819420d11a078ab14848b2.jpg', '1200', '1198', 'jpg', '0', '73528', 'image/jpeg', '', '1586507952', '1586507952', '1586507952', 'bda15df2d3a868a47fbf47732d06db64775cfceb', 'local', '1');
INSERT INTO `zqs_attachment` VALUES ('2', 'timg (1).gif', '/storage/uploads/image/20200410\\171bfeb319505617e5937b3c61f20981.gif', '177', '177', 'gif', '0', '27046', 'image/gif', '', '1586507988', '1586507988', '1586507988', '50c6c72995b431d07f5415d4552a247ec7d93d18', 'local', '1');
INSERT INTO `zqs_attachment` VALUES ('3', '76832523.jpeg', '/storage/uploads/image/20200412\\6c6d6de994a9da7e3a30d1c3520b0f0e.jpeg', '610', '610', 'jpeg', '0', '22700', 'image/jpeg', '', '1586676926', '1586676926', '1586676926', '48f1fde3ae4090fd271855100e846565b8e7d87c', 'public', '1');
INSERT INTO `zqs_attachment` VALUES ('4', 'timg (2).gif', '/storage/uploads/image/20200412\\503f70c946eec168d384b3a4f25ab538.gif', '420', '420', 'gif', '0', '477366', 'image/gif', '', '1586683297', '1586683297', '1586683297', '701657a989ccc3a6e6adeb44f9659a04e392624f', 'public', '1');

-- ----------------------------
-- Table structure for zqs_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `zqs_auth_group`;
CREATE TABLE `zqs_auth_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父组别',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '组名',
  `rules` text NOT NULL COMMENT '规则ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='分组表';

-- ----------------------------
-- Records of zqs_auth_group
-- ----------------------------
INSERT INTO `zqs_auth_group` VALUES ('1', '0', '超级管理员', '*', '0', '1497205035', '1');
INSERT INTO `zqs_auth_group` VALUES ('2', '1', '测试2', '7,9,20,21,22,23', '1586147415', '1586630723', '1');

-- ----------------------------
-- Table structure for zqs_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `zqs_auth_group_access`;
CREATE TABLE `zqs_auth_group_access` (
  `uid` int(10) unsigned NOT NULL COMMENT '会员ID',
  `group_id` int(10) unsigned NOT NULL COMMENT '级别ID',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `group_id` (`group_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限分组表';

-- ----------------------------
-- Records of zqs_auth_group_access
-- ----------------------------
INSERT INTO `zqs_auth_group_access` VALUES ('1', '1');
INSERT INTO `zqs_auth_group_access` VALUES ('4', '1');
INSERT INTO `zqs_auth_group_access` VALUES ('4', '2');
INSERT INTO `zqs_auth_group_access` VALUES ('5', '2');

-- ----------------------------
-- Table structure for zqs_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `zqs_auth_rule`;
CREATE TABLE `zqs_auth_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('menu','file') NOT NULL DEFAULT 'file' COMMENT 'menu为菜单,file为权限节点',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '规则名称',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '规则名称',
  `icon` varchar(50) NOT NULL DEFAULT '' COMMENT '图标',
  `condition` varchar(255) NOT NULL DEFAULT '' COMMENT '条件',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `ismenu` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为菜单',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`) USING BTREE,
  KEY `weigh` (`weigh`) USING BTREE,
  KEY `name` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='节点表';

-- ----------------------------
-- Records of zqs_auth_rule
-- ----------------------------
INSERT INTO `zqs_auth_rule` VALUES ('1', 'file', '0', '', '权限', 'layui-icon-user', '', '', '1', '1586088731', '1586520992', '-99', '1');
INSERT INTO `zqs_auth_rule` VALUES ('2', 'file', '1', '/auth/rule/index', '菜单节点', '', '', '', '1', '1586088759', '1586327772', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('3', 'file', '1', '/auth/adminuser/index', '管理员', '', '', '', '1', '1586089478', '1586089509', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('4', 'file', '1', '/auth/group/index', '角色组', '', '', '', '1', '1586089655', '1586089655', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('5', 'file', '2', '/auth/rule/index', '查看', '', '', '', '0', '1586090145', '1586090280', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('6', 'file', '2', '/auth/rule/add', '添加', '', '', '', '0', '1586090312', '1586090312', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('7', 'file', '0', 'config', '设置', 'layui-icon-set', '', '', '1', '1586521035', '1586630637', '-98', '1');
INSERT INTO `zqs_auth_rule` VALUES ('8', 'file', '7', '/config/system', '系统设置', '', '', '', '1', '1586528420', '1586584859', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('9', 'file', '7', '/config/index', '配置管理', '', '', '', '1', '1586528441', '1586528518', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('10', 'file', '2', '/auth/rule/edit', '编辑', '', '', '', '0', '1586614418', '1586614418', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('11', 'file', '2', '/auth/rule/delete', '删除', '', '', '', '0', '1586614439', '1586614439', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('12', 'file', '3', '/auth/adminuser/index', '查看', '', '', '', '0', '1586614496', '1586614496', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('13', 'file', '3', '/auth/adminuser/add', '添加', '', '', '', '0', '1586614511', '1586614531', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('14', 'file', '3', '/auth/adminuser/edit', '编辑', '', '', '', '0', '1586614545', '1586614545', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('15', 'file', '3', '/auth/adminuser/delete', '删除', '', '', '', '0', '1586614563', '1586614563', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('16', 'file', '4', 'auth/group/index', '查看', '', '', '', '0', '1586614585', '1586614585', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('17', 'file', '4', 'auth/group/add', '添加', '', '', '', '0', '1586614599', '1586614599', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('18', 'file', '4', 'auth/group/edit', '编辑', '', '', '', '0', '1586614612', '1586614612', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('19', 'file', '4', 'auth/group/delete', '删除', '', '', '', '0', '1586614629', '1586614629', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('20', 'file', '9', '/config/index', '查看', '', '', '', '0', '1586617847', '1586617847', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('21', 'file', '9', '/config/add', '添加', '', '', '', '0', '1586617911', '1586617911', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('22', 'file', '9', '/config/edit', '编辑', '', '', '', '0', '1586617927', '1586617927', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('23', 'file', '9', '/config/delete', '删除', '', '', '', '0', '1586617943', '1586617943', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('24', 'file', '8', '/config/system_save', '保存', '', '', '', '0', '1586682903', '1586682903', '0', '1');

-- ----------------------------
-- Table structure for zqs_config
-- ----------------------------
DROP TABLE IF EXISTS `zqs_config`;
CREATE TABLE `zqs_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '配置名称 字段名',
  `type` varchar(30) NOT NULL DEFAULT '' COMMENT '配置类型 input/select/array...',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置说明',
  `group` varchar(30) NOT NULL DEFAULT '0' COMMENT '配置分组',
  `options` varchar(255) DEFAULT NULL COMMENT '配置项',
  `value` text COMMENT '配置值',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `remark` varchar(100) DEFAULT NULL COMMENT '配置说明',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zqs_config
-- ----------------------------
INSERT INTO `zqs_config` VALUES ('1', 'config_group', 'array', '配置分组', 'system', '', 'base:基础\nsystem:系统\nupload:上传\ndevelop:开发\ndatabase:数据库', '0', '', '0', '1586605081', '1');
INSERT INTO `zqs_config` VALUES ('2', 'form_item_type', 'array', '配置类型', 'system', '', 'input:单行文本\ntextarea:多行文本\nswitch:开关\nselect:下拉菜单\nselect2:下拉菜单多选\nimage:单图上传\nicon:图标\nradio:单选\narray:数组', '0', '', '0', '1586677458', '1');
INSERT INTO `zqs_config` VALUES ('9', 'a', 'input', '单行文本', 'base', '', 'aaaa', '0', '', '1586623301', '1586684256', '1');
INSERT INTO `zqs_config` VALUES ('10', 'b', 'textarea', '多行文本', 'base', '', 'bbbb', '0', '', '1586623804', '1586684256', '1');
INSERT INTO `zqs_config` VALUES ('11', 'c', 'switch', '开关', 'base', '', '0', '0', '', '1586675857', '1586684256', '1');
INSERT INTO `zqs_config` VALUES ('12', 'd', 'select', '下拉菜单', 'base', 'a:是\nb:否\nc:中立\nd:逃', 'b', '0', '', '1586676357', '1586684256', '1');
INSERT INTO `zqs_config` VALUES ('13', 'e', 'image', '单图上传', 'base', '', '2', '0', '', '1586676780', '1586684256', '1');
INSERT INTO `zqs_config` VALUES ('14', 'f', 'icon', '图标选择', 'base', '', 'layui-icon-login-wechat', '0', '', '1586677153', '1586684256', '1');
INSERT INTO `zqs_config` VALUES ('15', 'g', 'radio', '单选', 'base', '1:x\n2:y', '1', '0', '', '1586677192', '1586684256', '1');
INSERT INTO `zqs_config` VALUES ('16', 'h', 'array', '数组', 'base', '', '1:1\n2:2', '0', '', '1586677314', '1586684256', '1');
INSERT INTO `zqs_config` VALUES ('17', 'i', 'select2', '下拉多选', 'base', 'a:1\nb:2\n3:3\n4:4', 'a', '0', '', '1586677489', '1586684256', '1');
