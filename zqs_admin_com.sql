/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50644
Source Host           : localhost:3306
Source Database       : zqs_admin_com

Target Server Type    : MYSQL
Target Server Version : 50644
File Encoding         : 65001

Date: 2020-04-16 14:58:50
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
INSERT INTO `zqs_admin` VALUES ('1', 'admin', '超级管理A', '22ab08e556ca8e6287d85dfd8d1d4e16', 'L3edBl', '2', 'admin@admin.com', '0', '1586522665', '1492186163', '1586944893', 'b4da902d-1223-4b6f-a2a7-83684f1d1683', '1');
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
-- Table structure for zqs_article
-- ----------------------------
DROP TABLE IF EXISTS `zqs_article`;
CREATE TABLE `zqs_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cover` int(11) NOT NULL DEFAULT '0' COMMENT '封面',
  `cate_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(150) CHARACTER SET utf8 NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read` int(11) NOT NULL DEFAULT '0' COMMENT '阅读次数',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='文章表';

-- ----------------------------
-- Records of zqs_article
-- ----------------------------
INSERT INTO `zqs_article` VALUES ('19', '1', '2', '测试一篇文章', '&lt;p&gt;帮助！！！！！！！！！！！！！！！！！&lt;/p&gt;\n&lt;p&gt;&lt;img src=&quot;/storage/uploads/image/20200410\\171bfeb319505617e5937b3c61f20981.gif&quot; alt=&quot;&quot; width=&quot;177&quot; height=&quot;177&quot; /&gt;&lt;/p&gt;', '0', '1586943665', '1586947159', '1');
INSERT INTO `zqs_article` VALUES ('20', '19', '10', '文章2', '&lt;p&gt;1111???ddddddddddddddddddddddd&lt;/p&gt;', '0', '1586959973', '1586960057', '1');

-- ----------------------------
-- Table structure for zqs_article_cate
-- ----------------------------
DROP TABLE IF EXISTS `zqs_article_cate`;
CREATE TABLE `zqs_article_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cover` int(11) NOT NULL DEFAULT '0' COMMENT '封面',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父类',
  `name` varchar(20) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `show_index` tinyint(1) NOT NULL DEFAULT '0' COMMENT '首页显示',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='文章分类';

-- ----------------------------
-- Records of zqs_article_cate
-- ----------------------------
INSERT INTO `zqs_article_cate` VALUES ('2', '11', '0', '新手入门', '4', '1', '1576423429', '1576424912', '1');
INSERT INTO `zqs_article_cate` VALUES ('8', '0', '2', 'a', '0', '0', '1576483559', '1576483633', '-1');
INSERT INTO `zqs_article_cate` VALUES ('10', '19', '2', '小分类', '0', '0', '1586956312', '1586956312', '1');
INSERT INTO `zqs_article_cate` VALUES ('11', '1', '0', '分类二', '5', '0', '1586960732', '1586960732', '1');

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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='附件表';

-- ----------------------------
-- Records of zqs_attachment
-- ----------------------------
INSERT INTO `zqs_attachment` VALUES ('1', 'timg.jpg', '/storage/uploads/image/20200410\\15146e6ae4819420d11a078ab14848b2.jpg', '1200', '1198', 'jpg', '0', '73528', 'image/jpeg', '', '1586507952', '1586507952', '1586507952', 'bda15df2d3a868a47fbf47732d06db64775cfceb', 'local', '1');
INSERT INTO `zqs_attachment` VALUES ('2', 'timg (1).gif', '/storage/uploads/image/20200410\\171bfeb319505617e5937b3c61f20981.gif', '177', '177', 'gif', '0', '27046', 'image/gif', '', '1586507988', '1586507988', '1586507988', '50c6c72995b431d07f5415d4552a247ec7d93d18', 'local', '1');
INSERT INTO `zqs_attachment` VALUES ('5', '429287.png', '/storage/uploads/image/20200413\\269da963ae85d5e9ee998005e5dc7cb9.png', '2561', '1601', 'png', '0', '406467', 'image/png', '', '1586764763', '1586764763', '1586764763', '472a641b8b21ebca1f9dcde7970c5c8bca209628', 'public', '1');
INSERT INTO `zqs_attachment` VALUES ('6', '1370d8f1956edd8b98976bc93a60014c.png', '/storage/uploads/image/20200413\\357d4aca6ab8130d548be02f3da8a9de.png', '2084', '2084', 'png', '0', '116888', 'image/png', '', '1586764765', '1586764765', '1586764765', '4eb6ccbd193d149cd5793d6e7d258ba7a65390bc', 'public', '1');
INSERT INTO `zqs_attachment` VALUES ('7', '2019111306.jpg', '/storage/uploads/image/20200413\\bcb6e1d4dde769c7c30db08474771d95.jpg', '582', '248', 'jpg', '0', '156158', 'image/jpeg', '', '1586764767', '1586764767', '1586764767', 'c559eec46a98dbf810cdefbf41a757089528592e', 'public', '1');
INSERT INTO `zqs_attachment` VALUES ('8', 'timg.gif', '/storage/uploads/image/20200413\\c1ab8838b06bfce08754a540c073568f.gif', '499', '333', 'gif', '0', '783918', 'image/gif', '', '1586764771', '1586764771', '1586764771', '4b19279e589a81c5cd5c6a60797d2d0f5bad6a21', 'public', '1');
INSERT INTO `zqs_attachment` VALUES ('9', 'timg (2).gif', '/storage/uploads/image/20200413\\ccc8b53e6ab004a1248bb5fc2bf2aeac.gif', '420', '420', 'gif', '0', '477366', 'image/gif', '', '1586764774', '1586764774', '1586764774', '701657a989ccc3a6e6adeb44f9659a04e392624f', 'public', '1');
INSERT INTO `zqs_attachment` VALUES ('10', 'timg (1).jpg', '/storage/uploads/image/20200413\\d0a3ee9227ddbb6961c8b6c6419e3a0a.jpg', '700', '764', 'jpg', '0', '11217', 'image/jpeg', '', '1586764776', '1586764776', '1586764776', 'f6eb12f324d278f2df8af8998e7e564fa0e6a3ff', 'public', '1');
INSERT INTO `zqs_attachment` VALUES ('11', '2019111901.jpg', '/storage/uploads/image/20200413\\bd816917bb5bdae3c33e70405df34b1f.jpg', '582', '248', 'jpg', '0', '176268', 'image/jpeg', '', '1586764779', '1586764779', '1586764779', '67eac8665d0d2ee3d037e20a423f4d9d7933fb87', 'public', '1');
INSERT INTO `zqs_attachment` VALUES ('12', '2019112606.jpg', '/storage/uploads/image/20200413\\ae22ed4ae35fe4536144423eb1013925.jpg', '582', '248', 'jpg', '0', '124864', 'image/jpeg', '', '1586764781', '1586764781', '1586764781', 'e6d87480b744d0515e44d20bedd1d987a38776dd', 'public', '1');
INSERT INTO `zqs_attachment` VALUES ('13', '2019112601.jpg', '/storage/uploads/image/20200413\\a61f5d9df504144adabc0b387b4fd7f9.jpg', '582', '248', 'jpg', '0', '65386', 'image/jpeg', '', '1586764782', '1586764782', '1586764782', '8a07469afbec263dc2e87ffa3eac5b4329f34208', 'public', '1');
INSERT INTO `zqs_attachment` VALUES ('14', '2019111305.jpg', '/storage/uploads/image/20200413\\ef24ba516506e44813a919284d345b5e.jpg', '582', '248', 'jpg', '0', '164813', 'image/jpeg', '', '1586764789', '1586764789', '1586764789', 'a07935246feea2526b799f59887cca6e97496a2f', 'public', '1');
INSERT INTO `zqs_attachment` VALUES ('15', '2019112602.jpg', '/storage/uploads/image/20200413\\2964e96a6d8129e1d2beecc912a3138f.jpg', '582', '248', 'jpg', '0', '132945', 'image/jpeg', '', '1586764794', '1586764794', '1586764794', '4f108cba81debd8a7f05b85d6e7e898f8f91210e', 'public', '1');
INSERT INTO `zqs_attachment` VALUES ('16', '2019112605.jpg', '/storage/uploads/image/20200413\\b7d0040f850ba619c2441cdce9ebc245.jpg', '582', '248', 'jpg', '0', '184443', 'image/jpeg', '', '1586764795', '1586764795', '1586764795', 'b17d01d15140eae765af50e0c01866b3dc7b2a24', 'public', '1');
INSERT INTO `zqs_attachment` VALUES ('17', '2019112701.jpg', '/storage/uploads/image/20200413\\760bef7d46ae18f5560b96e9acb0d527.jpg', '582', '248', 'jpg', '0', '109194', 'image/jpeg', '', '1586764813', '1586764813', '1586764813', '6d844fbc095608b4fc052ed4756b1cf69c5f684c', 'public', '1');
INSERT INTO `zqs_attachment` VALUES ('18', '2019112607.jpg', '/storage/uploads/image/20200413\\8e7862ee6c85a368be4d6db74ea22e25.jpg', '582', '248', 'jpg', '0', '168828', 'image/jpeg', '', '1586764827', '1586764827', '1586764827', '27dc0a71d86b06b220503dc8a55da9039b319fdb', 'public', '1');
INSERT INTO `zqs_attachment` VALUES ('19', '76832523.jpeg', '/storage/uploads/image/20200414\\d9fd9285457b8bfcb32004f6ab2cf863.jpeg', '610', '610', 'jpeg', '0', '22700', 'image/jpeg', '', '1586869843', '1586869843', '1586869843', '48f1fde3ae4090fd271855100e846565b8e7d87c', 'public', '1');
INSERT INTO `zqs_attachment` VALUES ('20', '1.gif', '/storage/uploads/image/20200414\\f116fae397ae44a64aca4e5f47f37874.gif', '364', '350', 'gif', '0', '1857558', 'image/gif', '', '1586873968', '1586873968', '1586873968', 'bb4a0745b7edf1b8db928c9a1e0eaeeafaf9f7b6', 'public', '1');

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
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COMMENT='节点表';

-- ----------------------------
-- Records of zqs_auth_rule
-- ----------------------------
INSERT INTO `zqs_auth_rule` VALUES ('1', 'file', '0', 'auth', '权限', 'layui-icon-user', '', '', '1', '1586088731', '1586706795', '-99', '1');
INSERT INTO `zqs_auth_rule` VALUES ('2', 'file', '1', '/auth/rule/index', '菜单节点', '', '', '', '1', '1586088759', '1586327772', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('3', 'file', '1', '/auth/adminuser/index', '管理员', '', '', '', '1', '1586089478', '1586089509', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('4', 'file', '1', '/auth/group/index', '角色组', '', '', '', '1', '1586089655', '1586089655', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('5', 'file', '2', '/auth/rule/index', '查看', '', '', '', '0', '1586090145', '1586090280', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('6', 'file', '2', '/auth/rule/add', '添加', '', '', '', '0', '1586090312', '1586090312', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('7', 'file', '0', 'config', '设置', 'layui-icon-set', '', '', '1', '1586521035', '1586630637', '-98', '1');
INSERT INTO `zqs_auth_rule` VALUES ('8', 'file', '7', '/config/system', '系统设置', '', '', '', '1', '1586528420', '1586584859', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('9', 'file', '7', '/config/index', '配置管理', '', '', '', '1', '1586528441', '1586707997', '-1', '1');
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
INSERT INTO `zqs_auth_rule` VALUES ('25', 'file', '0', 'cms', '内容', 'layui-icon-read', '', '', '1', '1586706681', '1586706681', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('26', 'file', '25', '/cms/article/index', '文章列表', '', '', '', '1', '1586707123', '1587020245', '1', '1');
INSERT INTO `zqs_auth_rule` VALUES ('27', 'file', '25', '/cms/cate/index', '文章分类', '', '', '', '1', '1586707151', '1586707151', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('28', 'file', '25', '/cms/attach/index', '附件管理', '', '', '', '1', '1586707187', '1586707951', '-1', '1');
INSERT INTO `zqs_auth_rule` VALUES ('29', 'file', '28', '/cms/attach/index', '查看', '', '', '', '0', '1586786084', '1586786084', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('30', 'file', '28', '/cms/attach/delete', '删除', '', '', '', '0', '1586786103', '1586786103', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('31', 'file', '26', '/cms/article/index', '查看', '', '', '', '0', '1586786349', '1587020061', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('32', 'file', '26', '/cms/article/add', '添加', '', '', '', '0', '1587019579', '1587019641', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('33', 'file', '26', '/cms/article/edit', '编辑', '', '', '', '0', '1587019678', '1587019683', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('34', 'file', '26', '/cms/article/delete', '删除', '', '', '', '0', '1587019696', '1587019696', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('35', 'file', '27', '/cms/cate/index', '查看', '', '', '', '0', '1587019729', '1587019729', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('36', 'file', '27', '/cms/cate/add', '添加', '', '', '', '0', '1587019764', '1587019764', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('37', 'file', '27', '/cms/cate/edit', '编辑', '', '', '', '0', '1587019777', '1587019777', '0', '1');
INSERT INTO `zqs_auth_rule` VALUES ('38', 'file', '27', '/cms/cate/delete', '删除', '', '', '', '0', '1587019795', '1587019795', '0', '1');

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
INSERT INTO `zqs_config` VALUES ('9', 'a', 'input', '单行文本', 'base', '', 'aaaa', '0', '', '1586623301', '1586965947', '1');
INSERT INTO `zqs_config` VALUES ('10', 'b', 'textarea', '多行文本', 'base', '', 'bbbb123', '0', '', '1586623804', '1586965947', '1');
INSERT INTO `zqs_config` VALUES ('11', 'c', 'switch', '开关', 'base', '', '1', '0', '', '1586675857', '1586965947', '1');
INSERT INTO `zqs_config` VALUES ('12', 'd', 'select', '下拉菜单', 'base', 'a:是\nb:否\nc:中立\nd:逃', 'b', '0', '', '1586676357', '1586965947', '1');
INSERT INTO `zqs_config` VALUES ('13', 'e', 'image', '单图上传', 'base', '', '2', '0', '', '1586676780', '1586965947', '1');
INSERT INTO `zqs_config` VALUES ('14', 'f', 'icon', '图标选择', 'base', '', 'layui-icon-login-wechat', '0', '', '1586677153', '1586965947', '1');
INSERT INTO `zqs_config` VALUES ('15', 'g', 'radio', '单选', 'base', '1:x\n2:y', '1', '0', '', '1586677192', '1586708021', '1');
INSERT INTO `zqs_config` VALUES ('16', 'h', 'array', '数组', 'base', '', '1:1\n2:2', '0', '', '1586677314', '1586965947', '1');
INSERT INTO `zqs_config` VALUES ('17', 'i', 'select2', '下拉多选', 'base', 'a:1\nb:2\n3:3\n4:4', 'a,b,3,4', '0', '', '1586677489', '1586965947', '1');
