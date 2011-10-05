/*
SQLyog Ultimate v9.10 
MySQL - 5.1.36-community-log : Database - honeycomb2
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`honeycomb2` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `honeycomb2`;

/*Table structure for table `assertions` */

CREATE TABLE `assertions` (
  `assertion_id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) DEFAULT NULL,
  `description` text,
  `is_active` tinyint(1) DEFAULT NULL,
  `class_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`assertion_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `assertions` */

/*Table structure for table `modules` */

CREATE TABLE `modules` (
  `module_id` int(10) NOT NULL AUTO_INCREMENT,
  `identifier` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`module_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `modules` */

insert  into `modules`(`module_id`,`identifier`,`name`,`description`) values (9,'admin','admin',NULL),(10,'default','default',NULL);

/*Table structure for table `privileges` */

CREATE TABLE `privileges` (
  `privilege_id` int(10) NOT NULL AUTO_INCREMENT,
  `resource_id` int(10) DEFAULT NULL,
  `identifier` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`privilege_id`),
  KEY `FKprivileges396465` (`resource_id`)
) ENGINE=MyISAM AUTO_INCREMENT=119 DEFAULT CHARSET=latin1;

/*Data for the table `privileges` */

insert  into `privileges`(`privilege_id`,`resource_id`,`identifier`,`name`,`description`) values (92,36,'index',NULL,NULL),(93,37,'manage',NULL,NULL),(94,37,'add',NULL,NULL),(95,37,'list',NULL,NULL),(96,37,'delete',NULL,NULL),(97,37,'info',NULL,NULL),(98,37,'modules',NULL,NULL),(99,37,'controllers',NULL,NULL),(100,37,'actions',NULL,NULL),(101,38,'index',NULL,NULL),(102,38,'config',NULL,NULL),(103,39,'autocomplete',NULL,NULL),(104,39,'names',NULL,NULL),(105,39,'database',NULL,NULL),(106,39,'lucene',NULL,NULL),(107,40,'error',NULL,NULL),(108,41,'index',NULL,NULL),(109,41,'login',NULL,NULL),(110,41,'appform',NULL,NULL),(111,41,'test',NULL,NULL),(112,42,'index',NULL,NULL),(113,42,'list',NULL,NULL),(114,43,'js',NULL,NULL),(115,43,'test',NULL,NULL),(116,44,'index',NULL,NULL),(117,45,'index',NULL,NULL),(118,45,'callBack',NULL,NULL);

/*Table structure for table `resource_types` */

CREATE TABLE `resource_types` (
  `resource_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) DEFAULT NULL,
  PRIMARY KEY (`resource_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `resource_types` */

/*Table structure for table `resources` */

CREATE TABLE `resources` (
  `resource_id` int(10) NOT NULL AUTO_INCREMENT,
  `identifier` varchar(255) DEFAULT NULL,
  `module_id` int(10) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `parent_id` int(10) DEFAULT NULL,
  `resource_type_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`resource_id`),
  KEY `FKresources171067` (`module_id`),
  KEY `FKresources277327` (`parent_id`),
  KEY `FKresources518725` (`resource_type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=latin1;

/*Data for the table `resources` */

insert  into `resources`(`resource_id`,`identifier`,`module_id`,`name`,`description`,`parent_id`,`resource_type_id`) values (36,'index',9,NULL,NULL,NULL,NULL),(37,'role',9,NULL,NULL,NULL,NULL),(38,'search',9,NULL,NULL,NULL,NULL),(39,'ajax',10,NULL,NULL,NULL,NULL),(40,'error',10,NULL,NULL,NULL,NULL),(41,'index',10,NULL,NULL,NULL,NULL),(42,'reflection',10,NULL,NULL,NULL,NULL),(43,'skin',10,NULL,NULL,NULL,NULL),(44,'test',10,NULL,NULL,NULL,NULL),(45,'twitter',10,NULL,NULL,NULL,NULL);

/*Table structure for table `roles` */

CREATE TABLE `roles` (
  `role_id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `is_user_based` tinyint(4) DEFAULT NULL,
  `parent_id` int(10) DEFAULT NULL,
  `is_editable` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`role_id`),
  KEY `FKroles365864` (`parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Data for the table `roles` */

insert  into `roles`(`role_id`,`name`,`is_user_based`,`parent_id`,`is_editable`) values (1,'member',0,NULL,NULL),(2,'moderator',0,NULL,NULL),(3,'admin',0,NULL,NULL),(4,'superadmin',0,NULL,NULL),(5,'guest',0,NULL,NULL),(6,'user1',1,NULL,NULL),(7,NULL,1,NULL,NULL),(8,NULL,1,NULL,NULL),(9,NULL,1,NULL,NULL);

/*Table structure for table `roles_privileges` */

CREATE TABLE `roles_privileges` (
  `role_id` int(10) NOT NULL,
  `privilege_id` int(10) NOT NULL,
  `assertion_id` int(10) DEFAULT NULL,
  `is_deny` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`role_id`,`privilege_id`),
  KEY `FKroles_priv464608` (`role_id`),
  KEY `FKroles_priv487920` (`privilege_id`),
  KEY `FKroles_priv976039` (`assertion_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `roles_privileges` */

/*Table structure for table `users` */

CREATE TABLE `users` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `password` char(16) DEFAULT NULL,
  `sex` char(1) DEFAULT NULL,
  `age` int(2) DEFAULT NULL,
  `join_time` int(11) DEFAULT NULL,
  `last_visit_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `users` */

insert  into `users`(`user_id`,`email`,`username`,`last_name`,`first_name`,`password`,`sex`,`age`,`join_time`,`last_visit_time`) values (1,'foo@gmail.com','foo','rahul','gupta','123456','m',22,2147483647,1111111111),(2,'bar@gmail.com','bar','jason','bourne','123456','m',23,111,NULL);

/*Table structure for table `users_roles` */

CREATE TABLE `users_roles` (
  `user_id` int(10) NOT NULL,
  `role_id` int(10) NOT NULL,
  `sort_order` int(10) DEFAULT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `FKusers_role34597` (`user_id`),
  KEY `FKusers_role852519` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `users_roles` */

insert  into `users_roles`(`user_id`,`role_id`,`sort_order`) values (2,1,4),(2,2,3),(2,3,1),(2,4,2),(2,5,5),(2,6,0);

/*Table structure for table `utroy_jscss` */

CREATE TABLE `utroy_jscss` (
  `jscss_id` bigint(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `is_css` tinyint(1) NOT NULL,
  `is_js` tinyint(1) NOT NULL,
  `sha1` char(40) NOT NULL,
  `sort_order` int(3) NOT NULL,
  PRIMARY KEY (`jscss_id`)
) ENGINE=MyISAM AUTO_INCREMENT=72 DEFAULT CHARSET=latin1;

/*Data for the table `utroy_jscss` */

insert  into `utroy_jscss`(`jscss_id`,`url`,`is_css`,`is_js`,`sha1`,`sort_order`) values (56,'libs/fancybox/jquery.easing-1.3.pack.js',0,1,'55d99c8d1e3e5867724a274df57ad05e3168a5cc',11),(57,'libs/fancybox/jquery.fancybox-1.3.4.css',1,0,'dc79d46238a7dd0a7b63f640bce08ae52af73b36',1),(58,'libs/fancybox/jquery.fancybox-1.3.4.js',0,1,'7fb5ce885973c1046280461c9414abf3fbf99ed5',10),(59,'libs/fancybox/jquery.fancybox-1.3.4.pack.js',0,1,'caeb31e930068ce5820b239d44d8415f95957138',3),(60,'libs/fancybox/jquery.mousewheel-3.0.4.pack.js',0,1,'2db79bca5a365b8f631a995662e4fcb80468cb48',12),(61,'libs/jqGrid/css/ui.jqgrid.css',1,0,'92ef385dd806fcde3ce8400788451d07a9bf34d6',0),(62,'libs/jqGrid/js/grid.locale-en.js',0,1,'23975e6d05be4ef6eb670f6a755382d72741e147',1),(63,'libs/jqGrid/js/jquery.jqGrid.src.js',0,1,'65cc5c0e0f038d82015c574c55b4d10348396d1b',0),(64,'libs/jqGrid/plugins/grid.addons.js',0,1,'15e2c45d97140cc19b6c1dc151ef7bbfb7b0d4e2',2),(65,'libs/jqGrid/plugins/grid.postext.js',0,1,'db2a4591a8c8de7d28bbea3cc5941432b642f204',4),(66,'libs/jqGrid/plugins/grid.setcolumns.js',0,1,'c2c1bc14f18821902a24eeb2ae21852dec0ec9bd',5),(67,'libs/jqGrid/plugins/jquery.contextmenu.js',0,1,'77ad6d9c145a46f8e33ac5e2a3d9b69ed0135851',6),(68,'libs/jqGrid/plugins/jquery.searchFilter.js',0,1,'f18f414044507cce0fe78151492b5a343a743b98',7),(69,'libs/jqGrid/plugins/jquery.tablednd.js',0,1,'ec04e19853f17fe60a87bcb51720133f09974468',8),(70,'libs/jqGrid/plugins/ui.multiselect.css',1,0,'60f75b1179cd52f58483e06f43597b25c88199f4',2),(71,'libs/jqGrid/plugins/ui.multiselect.js',0,1,'fd91a407342400a5d2ec494f87e378d3d104145e',9);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
