/*
SQLyog Enterprise - MySQL GUI v7.02 
MySQL - 5.5.29-0ubuntu0.12.04.1 : Database - pyro_admin
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`pyro_admin` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `pyro_admin`;

/*Table structure for table `{PREFIX}profiles` */

DROP TABLE IF EXISTS `{PREFIX}profiles`;

CREATE TABLE `{PREFIX}profiles` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `display_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `company` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  `bio` text COLLATE utf8_unicode_ci,
  `dob` int(11) DEFAULT NULL,
  `gender` set('m','f','') COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_line1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_line2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_line3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postcode` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated_on` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `{PREFIX}profiles` */

insert  into `{PREFIX}profiles`(`id`,`user_id`,`display_name`,`first_name`,`last_name`,`company`,`lang`,`bio`,`dob`,`gender`,`phone`,`mobile`,`address_line1`,`address_line2`,`address_line3`,`postcode`,`website`,`updated_on`) values (1,1,'{DISPLAY-NAME}','{FIRST-NAME}','{LAST-NAME}','','en',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);

/*Table structure for table `{PREFIX}users` */

DROP TABLE IF EXISTS `{PREFIX}users`;

CREATE TABLE `{PREFIX}users` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `salt` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `group_id` int(11) DEFAULT NULL,
  `ip_address` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` int(1) DEFAULT NULL,
  `activation_code` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_on` int(11) NOT NULL,
  `last_login` int(11) NOT NULL,
  `username` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `forgotten_password_code` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_code` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Registered User Information';

/*Data for the table `{PREFIX}users` */

/*Table structure for table `core_settings` */

DROP TABLE IF EXISTS `core_settings`;

CREATE TABLE `core_settings` (
  `slug` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `default` text COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`slug`),
  UNIQUE KEY `unique - slug` (`slug`),
  KEY `index - slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Stores settings for the multi-site interface';

/*Data for the table `core_settings` */

insert  into `core_settings`(`slug`,`default`,`value`) values ('date_format','g:ia -- m/d/y','g:ia -- m/d/y'),('lang_direction','ltr','ltr'),('status_message','This site has been disabled by a super-administrator.','This site has been disabled by a super-administrator.');

/*Table structure for table `core_sites` */

DROP TABLE IF EXISTS `core_sites`;

CREATE TABLE `core_sites` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `ref` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `domain` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_on` int(11) NOT NULL DEFAULT '0',
  `updated_on` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `Unique ref` (`ref`),
  UNIQUE KEY `Unique domain` (`domain`),
  KEY `ref` (`ref`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `core_sites` */

insert  into `core_sites`(`id`,`name`,`ref`,`domain`,`active`,`created_on`,`updated_on`) values (1,'Default Site','default','admin3.micanal.dev',1,1358180062,0);

/*Table structure for table `core_users` */

DROP TABLE IF EXISTS `core_users`;

CREATE TABLE `core_users` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `salt` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `group_id` int(11) DEFAULT NULL,
  `ip_address` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` int(1) DEFAULT NULL,
  `activation_code` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_on` int(11) NOT NULL,
  `last_login` int(11) NOT NULL,
  `username` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `forgotten_password_code` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_code` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Super User Information';

/*Data for the table `core_users` */

insert  into `core_users`(`id`,`email`,`password`,`salt`,`group_id`,`ip_address`,`active`,`activation_code`,`created_on`,`last_login`,`username`,`forgotten_password_code`,`remember_code`) values (1,'glopez@idigital.pe','af3db9ac2af64d73f892e481170f88d0d4e7e385','d1f2b',1,'',1,'',1358180061,1358180061,'admin',NULL,NULL);

/*Table structure for table `default_blog` */

DROP TABLE IF EXISTS `default_blog`;

CREATE TABLE `default_blog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `category_id` int(11) NOT NULL,
  `attachment` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `intro` text COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `parsed` text COLLATE utf8_unicode_ci NOT NULL,
  `keywords` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `author_id` int(11) NOT NULL DEFAULT '0',
  `created_on` int(11) NOT NULL,
  `updated_on` int(11) NOT NULL DEFAULT '0',
  `comments_enabled` int(1) NOT NULL DEFAULT '1',
  `status` enum('draft','live') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft',
  `type` set('html','markdown','wysiwyg-advanced','wysiwyg-simple') COLLATE utf8_unicode_ci NOT NULL,
  `preview_hash` char(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_title` (`title`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `default_blog` */

insert  into `default_blog`(`id`,`title`,`slug`,`category_id`,`attachment`,`intro`,`body`,`parsed`,`keywords`,`author_id`,`created_on`,`updated_on`,`comments_enabled`,`status`,`type`,`preview_hash`) values (1,'Articulo 1','articulo-1',1,'','bbbb asss dasdasd dsdsdsd','contenido','','05780b83b2cf82e6acc83d170d0e4ec5',1,1358780700,1359494516,1,'draft','wysiwyg-simple','6538b391370f75ad801d6a107477535a'),(2,'Los de arriba y los de abajo','los-de-arriba-y-los-de-abajo',1,'','intro&nbsp;Los de arriba y los de abajo','contenido&nbsp;Los de arriba y los de abajo','','',1,1358549100,1358549237,1,'live','wysiwyg-simple','');

/*Table structure for table `default_blog_categories` */

DROP TABLE IF EXISTS `default_blog_categories`;

CREATE TABLE `default_blog_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_slug` (`slug`),
  UNIQUE KEY `unique_title` (`title`),
  KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `default_blog_categories` */

insert  into `default_blog_categories`(`id`,`slug`,`title`) values (1,'categoria-1','Categoria 1');

/*Table structure for table `default_ci_sessions` */

DROP TABLE IF EXISTS `default_ci_sessions`;

CREATE TABLE `default_ci_sessions` (
  `session_id` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `user_agent` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `default_ci_sessions` */

insert  into `default_ci_sessions`(`session_id`,`ip_address`,`user_agent`,`last_activity`,`user_data`) values ('103346a58602448e476e0e12b3c7e1fa','127.0.0.1','Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.95 Safari/537.11',1359067499,'a:7:{s:9:\"user_data\";s:0:\"\";s:8:\"username\";s:5:\"admin\";s:5:\"email\";s:18:\"glopez@idigital.pe\";s:2:\"id\";s:1:\"1\";s:7:\"user_id\";s:1:\"1\";s:8:\"group_id\";s:1:\"1\";s:5:\"group\";s:5:\"admin\";}'),('38336a82be958e9ad154040339d808fd','192.168.1.42','Mozilla/5.0 (Windows NT 6.1; rv:11.0) Gecko/20100101 Firefox/11.0',1358261771,'a:7:{s:9:\"user_data\";s:0:\"\";s:8:\"username\";s:5:\"admin\";s:5:\"email\";s:18:\"glopez@idigital.pe\";s:2:\"id\";s:1:\"1\";s:7:\"user_id\";s:1:\"1\";s:8:\"group_id\";s:1:\"1\";s:5:\"group\";s:5:\"admin\";}'),('bb7b1dab66b6ec67a59c4ccffcd60e37','192.168.1.42','Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17',1358294536,'a:7:{s:9:\"user_data\";s:0:\"\";s:8:\"username\";s:5:\"admin\";s:5:\"email\";s:18:\"glopez@idigital.pe\";s:2:\"id\";s:1:\"1\";s:7:\"user_id\";s:1:\"1\";s:8:\"group_id\";s:1:\"1\";s:5:\"group\";s:5:\"admin\";}'),('faf32369748ac8d0291c717b54e8faf7','192.168.1.34','Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17',1358575112,'a:7:{s:9:\"user_data\";s:0:\"\";s:8:\"username\";s:5:\"admin\";s:5:\"email\";s:18:\"glopez@idigital.pe\";s:2:\"id\";s:1:\"1\";s:7:\"user_id\";s:1:\"1\";s:8:\"group_id\";s:1:\"1\";s:5:\"group\";s:5:\"admin\";}'),('705c514bc5cfa601474c8a560d4d5660','192.168.1.66','Mozilla/5.0 (Windows NT 6.1; rv:11.0) Gecko/20100101 Firefox/11.0',1358792604,'a:8:{s:9:\"user_data\";s:0:\"\";s:8:\"username\";s:5:\"admin\";s:5:\"email\";s:18:\"glopez@idigital.pe\";s:2:\"id\";s:1:\"1\";s:7:\"user_id\";s:1:\"1\";s:8:\"group_id\";s:1:\"1\";s:5:\"group\";s:5:\"admin\";s:17:\"flash:old:success\";s:40:\"The keyword \"san isidro\" has been added.\";}'),('9d897710fb82d5264828e880cd2ab276','192.168.1.42','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.56 Safari/537.17',1359153486,'a:7:{s:9:\"user_data\";s:0:\"\";s:8:\"username\";s:5:\"admin\";s:5:\"email\";s:18:\"glopez@idigital.pe\";s:2:\"id\";s:1:\"1\";s:7:\"user_id\";s:1:\"1\";s:8:\"group_id\";s:1:\"1\";s:5:\"group\";s:5:\"admin\";}'),('1f4d4c3653c6fc945612ab03d326f326','192.168.1.40','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17',1358964826,'a:2:{s:9:\"user_data\";s:0:\"\";s:14:\"admin_redirect\";s:5:\"admin\";}'),('290df65c2806290f2eee0adae1d8367b','192.168.1.40','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17',1358964831,'a:7:{s:9:\"user_data\";s:0:\"\";s:8:\"username\";s:5:\"admin\";s:5:\"email\";s:18:\"glopez@idigital.pe\";s:2:\"id\";s:1:\"1\";s:7:\"user_id\";s:1:\"1\";s:8:\"group_id\";s:1:\"1\";s:5:\"group\";s:5:\"admin\";}'),('b0c254045cfe58b6f6a262d1ba5d1f67','192.168.1.42','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17',1358982211,''),('7cc40061edb0819e391ddf0fab7e73a3','127.0.0.1','Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:18.0) Gecko/20100101 Firefox/18.0',1359470140,'a:7:{s:9:\"user_data\";s:0:\"\";s:8:\"username\";s:5:\"admin\";s:5:\"email\";s:18:\"glopez@idigital.pe\";s:2:\"id\";s:1:\"1\";s:7:\"user_id\";s:1:\"1\";s:8:\"group_id\";s:1:\"1\";s:5:\"group\";s:5:\"admin\";}'),('f9c95f4e9f57a025ba6633a6859fdc15','192.168.1.38','Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.57 Safari/537.17',1360096738,'a:8:{s:9:\"user_data\";s:0:\"\";s:8:\"username\";s:8:\"jessenia\";s:5:\"email\";s:21:\"jzambrano@idigital.pe\";s:2:\"id\";s:1:\"3\";s:7:\"user_id\";s:1:\"3\";s:8:\"group_id\";s:1:\"4\";s:5:\"group\";s:21:\"administrador-canales\";s:13:\"canal_usuario\";a:2:{i:0;a:2:{s:8:\"canal_id\";s:1:\"1\";s:5:\"canal\";s:18:\"America Television\";}i:1;a:2:{s:8:\"canal_id\";s:1:\"2\";s:5:\"canal\";s:7:\"Canal N\";}}}'),('1bc2e663371ba13ba1951408b6a6c43e','192.168.1.38','Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.56 Safari/537.17',1359581532,''),('3aaf8a6d29c0cbb109833552b7d70aac','192.168.1.38','Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.56 Safari/537.17',1359581661,'a:7:{s:9:\"user_data\";s:0:\"\";s:8:\"username\";s:8:\"jessenia\";s:5:\"email\";s:21:\"jzambrano@idigital.pe\";s:2:\"id\";s:1:\"3\";s:7:\"user_id\";s:1:\"3\";s:8:\"group_id\";s:1:\"4\";s:5:\"group\";s:21:\"administrador-canales\";}'),('9a39d310ecf25079dae7d374b535e206','127.0.0.1','Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.95 Safari/537.11',1359666006,''),('98d612acd1de7147b29e52bbdc487300','127.0.0.1','Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:18.0) Gecko/20100101 Firefox/18.0',1360195114,'a:8:{s:9:\"user_data\";s:0:\"\";s:8:\"username\";s:8:\"jessenia\";s:5:\"email\";s:21:\"jzambrano@idigital.pe\";s:2:\"id\";s:1:\"3\";s:7:\"user_id\";s:1:\"3\";s:8:\"group_id\";s:1:\"4\";s:5:\"group\";s:21:\"administrador-canales\";s:13:\"canal_usuario\";a:2:{i:0;a:2:{s:8:\"canal_id\";s:1:\"1\";s:5:\"canal\";s:18:\"America Television\";}i:1;a:2:{s:8:\"canal_id\";s:1:\"2\";s:5:\"canal\";s:7:\"Canal N\";}}}'),('107fd16988229fe73f17035ecc384302','192.168.1.39','Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.57 Safari/537.17',1360014199,'a:8:{s:9:\"user_data\";s:0:\"\";s:8:\"username\";s:8:\"jessenia\";s:5:\"email\";s:21:\"jzambrano@idigital.pe\";s:2:\"id\";s:1:\"3\";s:7:\"user_id\";s:1:\"3\";s:8:\"group_id\";s:1:\"4\";s:5:\"group\";s:21:\"administrador-canales\";s:13:\"canal_usuario\";a:2:{i:0;a:2:{s:8:\"canal_id\";s:1:\"1\";s:5:\"canal\";s:18:\"America Television\";}i:1;a:2:{s:8:\"canal_id\";s:1:\"2\";s:5:\"canal\";s:7:\"Canal N\";}}}'),('7cf8106a4524d3330f4ce76ee805f49f','192.168.1.66','Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.57 Safari/537.17',1360189376,'a:8:{s:9:\"user_data\";s:0:\"\";s:8:\"username\";s:8:\"jessenia\";s:5:\"email\";s:21:\"jzambrano@idigital.pe\";s:2:\"id\";s:1:\"3\";s:7:\"user_id\";s:1:\"3\";s:8:\"group_id\";s:1:\"4\";s:5:\"group\";s:21:\"administrador-canales\";s:13:\"canal_usuario\";a:2:{i:0;a:2:{s:8:\"canal_id\";s:1:\"1\";s:5:\"canal\";s:18:\"America Television\";}i:1;a:2:{s:8:\"canal_id\";s:1:\"2\";s:5:\"canal\";s:7:\"Canal N\";}}}'),('ed673d47fc2b8e90e038ffbaa5c10539','192.168.1.66','Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.57 Safari/537.17',1360189828,'a:8:{s:9:\"user_data\";s:0:\"\";s:8:\"username\";s:8:\"jessenia\";s:5:\"email\";s:21:\"jzambrano@idigital.pe\";s:2:\"id\";s:1:\"3\";s:7:\"user_id\";s:1:\"3\";s:8:\"group_id\";s:1:\"4\";s:5:\"group\";s:21:\"administrador-canales\";s:13:\"canal_usuario\";a:2:{i:0;a:2:{s:8:\"canal_id\";s:1:\"1\";s:5:\"canal\";s:18:\"America Television\";}i:1;a:2:{s:8:\"canal_id\";s:1:\"2\";s:5:\"canal\";s:7:\"Canal N\";}}}');

/*Table structure for table `default_cms_canales` */

DROP TABLE IF EXISTS `default_cms_canales`;

CREATE TABLE `default_cms_canales` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID del Canal',
  `tipo_canales_id` int(11) NOT NULL COMMENT 'ID del Tipo de Canal',
  `alias` varchar(128) DEFAULT NULL COMMENT 'Etiqueta del Canal utilizado para el SEO',
  `nombre` varchar(128) DEFAULT NULL COMMENT 'Nombre o Razon Social del Canal',
  `imagen` varchar(45) DEFAULT NULL COMMENT 'Nombre del archivo imagen o logo del Canal. No lleva ruta solo nombre de archivo.',
  `descripcion` varchar(256) DEFAULT NULL COMMENT 'Descripcion del Canal',
  `fuente` tinyint(1) DEFAULT NULL COMMENT 'Para diferenciar Canales registrados\\nT=registrado\\nF=sin registrar',
  `apikey` varchar(40) DEFAULT NULL COMMENT 'Api que permite diferenciar los videos de cada Canal(Portal dentro de LIQUID)',
  `playerkey` varchar(40) DEFAULT NULL COMMENT 'Api que permite leer los videos de un determinado canal(portal dentro de LIQUID)',
  `estado` tinyint(4) DEFAULT '0' COMMENT 'Estado del canal:\\n0 = Borrador\\n1 = Publicado\\n2 = Eliminado\\n',
  `fecha_registro` datetime DEFAULT NULL COMMENT 'Fecha y Hora del Registro del Canal',
  `usuario_registro` int(11) DEFAULT NULL COMMENT 'ID Usuario que registra el Canal',
  `fecha_actualizacion` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la Ultima actualización del registro del Canal',
  `usuario_actualizacion` int(11) DEFAULT NULL COMMENT 'ID del Ultimo Usuario que modifica el registro del Canal.',
  `estado_migracion` tinyint(4) DEFAULT '0' COMMENT 'Estado de la migración a MongoDB:\\n0 = Registro Nuevo (por migrar)\\n1 = Proceso (migrando)\\n2 = Migrado\\n\\nAdicionalmente para cuando se modifique un registro, el CMS graba un status 9, y el proceso lo re-tomará para migrarlo al MongoDB.\\n9 = Registro Actualiza /* comment truncated */',
  `fecha_migracion` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la migración por primera vez (registros nuevos)\\n',
  `fecha_migracion_actualizacion` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la migración de la ultima actualización, es decir, cuando un registro se actualiza se graba el status = 9, y el proceso lo re-toma para migrar al MongoDB',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `default_cms_canales` */

insert  into `default_cms_canales`(`id`,`tipo_canales_id`,`alias`,`nombre`,`imagen`,`descripcion`,`fuente`,`apikey`,`playerkey`,`estado`,`fecha_registro`,`usuario_registro`,`fecha_actualizacion`,`usuario_actualizacion`,`estado_migracion`,`fecha_migracion`,`fecha_migracion_actualizacion`) values (1,0,NULL,'America Television',NULL,'Canal 4',NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,0,NULL,NULL),(2,0,NULL,'Canal N',NULL,'Canal 8',NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,0,NULL,NULL),(3,0,NULL,'ATV',NULL,'Canal 9',NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,0,NULL,NULL),(4,0,NULL,'Global Television',NULL,'Canal 13',NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,0,NULL,NULL),(5,0,NULL,'ATV+',NULL,'Canal 10',NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,0,NULL,NULL),(6,0,NULL,'TuTeve',NULL,'Canal 3',NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,0,NULL,NULL);

/*Table structure for table `default_cms_categorias` */

DROP TABLE IF EXISTS `default_cms_categorias`;

CREATE TABLE `default_cms_categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID de la Categoria',
  `nombre` varchar(45) DEFAULT NULL COMMENT 'Nombre de la Categoria',
  `alias` varchar(64) DEFAULT NULL COMMENT 'Etiqueta de la Categoria utilizado para el SEO',
  `estado` tinyint(4) DEFAULT '0' COMMENT 'Status de la Categoria:\\n1 = Activo\\n0 = Inactivo',
  `fecha_registro` datetime DEFAULT NULL COMMENT 'Fecha y Hora del Registro de la Categoria',
  `usuario_registro` int(11) DEFAULT NULL COMMENT 'ID Usuario que registra la Categoría',
  `fecha_actualizacion` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la Ultima actualización del registro de la Categoría',
  `usuario_actualizacion` int(11) DEFAULT NULL COMMENT 'ID del Ultimo Usuario que modifica el registro de la Categoría.',
  `status_migracion` tinyint(4) DEFAULT '0' COMMENT 'Status de la migración a MongoDB:\\n0 = Registro Nuevo (por migrar)\\n1 = Proceso (migrando)\\n2 = Migrado\\n\\nAdicionalmente para cuando se modifique un registro, el CMS graba un status 9, y el proceso lo re-tomará para migrarlo al MongoDB.\\n9 = Registro Actualiza /* comment truncated */',
  `fecha_migracion` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la migración por primera vez (registros nuevos)',
  `fecha_migracion_actualizacion` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la migración de la ultima actualización, es decir, cuando un registro se actualiza se graba el status = 9, y el proceso lo re-toma para migrar al MongoDB',
  `categoria_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Data for the table `default_cms_categorias` */

insert  into `default_cms_categorias`(`id`,`nombre`,`alias`,`estado`,`fecha_registro`,`usuario_registro`,`fecha_actualizacion`,`usuario_actualizacion`,`status_migracion`,`fecha_migracion`,`fecha_migracion_actualizacion`,`categoria_id`) values (1,'Entretenimiento','entretenimiento',1,'2012-02-01 00:00:00',1,'2012-02-01 00:00:00',NULL,0,NULL,NULL,0),(2,'Deporte','deporte',1,'2012-02-01 00:00:00',1,'2012-02-01 00:00:00',NULL,0,NULL,NULL,0),(3,'Noticias','noticias',1,'2012-02-01 00:00:00',1,'2012-02-01 00:00:00',NULL,0,NULL,NULL,0),(4,'Inmobiliaria','musica',1,'2012-02-01 00:00:00',1,'2012-02-01 00:00:00',NULL,0,NULL,NULL,0),(5,'Gastronomía','gastronomia',1,'2012-02-01 00:00:00',1,'2012-02-01 00:00:00',NULL,0,NULL,NULL,0),(6,'Turismo','turismo',1,'2012-02-01 00:00:00',1,'2012-02-01 00:00:00',NULL,0,NULL,NULL,0),(7,'Series de TV','series-de-tv',1,'2012-02-01 00:00:00',1,'2012-02-01 00:00:00',NULL,0,NULL,NULL,1),(8,'Reality','reality',1,'2012-02-01 00:00:00',1,'2012-02-01 00:00:00',NULL,0,NULL,NULL,1),(9,'Novelas','novelas',1,'2012-02-01 00:00:00',1,'2012-02-01 00:00:00',NULL,0,NULL,NULL,1),(10,'Infantiles','infantiles',1,'2012-02-01 00:00:00',1,'2012-02-01 00:00:00',NULL,0,NULL,NULL,1),(11,NULL,NULL,0,NULL,NULL,NULL,NULL,0,NULL,NULL,1);

/*Table structure for table `default_cms_grupo_detalles` */

DROP TABLE IF EXISTS `default_cms_grupo_detalles`;

CREATE TABLE `default_cms_grupo_detalles` (
  `id` int(11) NOT NULL,
  `grupo_maestro_parent` int(11) NOT NULL,
  `grupo_maestro_id` int(11) DEFAULT NULL,
  `tipo_grupo_maestro_id` int(11) NOT NULL,
  `video_id` int(11) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `usuario_registro` int(11) DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `usuario_actualizacion` int(11) DEFAULT NULL,
  `estado_migracion` tinyint(4) DEFAULT NULL,
  `fecha_migracion` datetime DEFAULT NULL,
  `fecha_migracion_actualizacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_grupo_detalle_grupo_maestro1_idx` (`grupo_maestro_id`,`tipo_grupo_maestro_id`),
  KEY `fk_grupo_detalle_video1_idx` (`video_id`),
  KEY `fk_grupo_detalle_grupo_maestro2_idx` (`grupo_maestro_parent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `default_cms_grupo_detalles` */

/*Table structure for table `default_cms_grupo_maestros` */

DROP TABLE IF EXISTS `default_cms_grupo_maestros`;

CREATE TABLE `default_cms_grupo_maestros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `tipo_grupo_maestro_id` int(11) NOT NULL,
  `peso` int(11) DEFAULT NULL COMMENT 'Indica el orden de presentacion del grupo\\n Ejemplo: Colecciones\\n1 = Temporada 1\\n2 = Temporada 2',
  `estado` tinyint(4) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `usuario_registro` int(11) DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `usuario_actualizacion` int(11) DEFAULT NULL,
  `estado_migracion` tinyint(4) DEFAULT NULL,
  `fecha_migracion` datetime DEFAULT NULL,
  `fecha_migracion_actualizacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_grupo_maestro_tipo_grupo_maestro1_idx` (`tipo_grupo_maestro_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `default_cms_grupo_maestros` */

/*Table structure for table `default_cms_imagenes` */

DROP TABLE IF EXISTS `default_cms_imagenes`;

CREATE TABLE `default_cms_imagenes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grupo_maestros_id` int(11) DEFAULT NULL,
  `videos_id` int(11) DEFAULT NULL,
  `portadas_id` int(11) DEFAULT NULL,
  `detalle_secciones_id` int(11) DEFAULT NULL,
  `imagen` varchar(100) DEFAULT NULL,
  `tipo_imagen_id` int(11) NOT NULL,
  `estado` tinyint(4) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `usuario_registro` int(11) DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `usuario_actualizacion` int(11) DEFAULT NULL,
  `estado_migracion` tinyint(4) DEFAULT NULL,
  `fecha_migracion` datetime DEFAULT NULL,
  `fecha_migracion_actualizacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_default_cms_imagenes_default_cms_videos1_idx` (`videos_id`),
  KEY `fk_default_cms_imagenes_default_cms_grupo_maestros1_idx` (`grupo_maestros_id`),
  KEY `fk_default_cms_imagenes_default_cms_portadas1_idx` (`portadas_id`),
  KEY `fk_default_cms_imagenes_default_cms_detalle_secciones1_idx` (`detalle_secciones_id`),
  KEY `fk_default_cms_imagenes_default_cms_tipo_imagen1_idx` (`tipo_imagen_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `default_cms_imagenes` */

insert  into `default_cms_imagenes`(`id`,`grupo_maestros_id`,`videos_id`,`portadas_id`,`detalle_secciones_id`,`imagen`,`tipo_imagen_id`,`estado`,`fecha_registro`,`usuario_registro`,`fecha_actualizacion`,`usuario_actualizacion`,`estado_migracion`,`fecha_migracion`,`fecha_migracion_actualizacion`) values (1,NULL,1,NULL,NULL,'the-big-bang-theory.jpg',1,1,'2012-10-25 00:00:00',NULL,NULL,NULL,NULL,NULL,NULL);

/*Table structure for table `default_cms_tags` */

DROP TABLE IF EXISTS `default_cms_tags`;

CREATE TABLE `default_cms_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_tags_id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` mediumtext,
  `alias` varchar(100) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT '0',
  `fecha_registro` datetime DEFAULT NULL,
  `usuario_registro` int(11) DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `usuario_actualizacion` int(11) DEFAULT NULL,
  `estado_migracion` tinyint(4) DEFAULT '0' COMMENT '\\"Status de la migración a MongoDB:\\n0 = Registro Nuevo (por migrar)\\n1 = Proceso (migrando)\\n2 = Migrado\\n\\nAdicionalmente para cuando se modifique un registro, el CMS graba un status 9, y el proceso lo re-tomará para migrarlo al MongoDB.\\n9 = Registro Actualiz /* comment truncated */',
  `fecha_migracion` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la migración por primera vez (registros nuevos)',
  `fecha_migracion_actualizacion` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la migración de la ultima actualización, es decir, cuando un registro se actualiza se graba el status = 9, y el proceso lo re-toma para migrar al MongoDB	',
  `estado_migracion_sphinx` tinyint(4) DEFAULT NULL,
  `fecha_migracion_sphinx` datetime DEFAULT NULL,
  `fecha_migracion_actualizacion_sphinx` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `default_cms_tags` */

insert  into `default_cms_tags`(`id`,`tipo_tags_id`,`nombre`,`descripcion`,`alias`,`estado`,`fecha_registro`,`usuario_registro`,`fecha_actualizacion`,`usuario_actualizacion`,`estado_migracion`,`fecha_migracion`,`fecha_migracion_actualizacion`,`estado_migracion_sphinx`,`fecha_migracion_sphinx`,`fecha_migracion_actualizacion_sphinx`) values (1,1,'tag 1','tag 1','tag-1',1,'2012-10-01 00:00:00',1,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL),(2,1,'tag 2','tag 2','tag-2',1,'2012-10-01 00:00:00',1,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL),(3,2,'tag 3','tag 3','tag-3',1,'2012-10-01 00:00:00',1,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL),(4,2,'tag 4','tag 4','tag-4',1,'2012-10-01 00:00:00',1,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL);

/*Table structure for table `default_cms_tipo_grupo_maestros` */

DROP TABLE IF EXISTS `default_cms_tipo_grupo_maestros`;

CREATE TABLE `default_cms_tipo_grupo_maestros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  `descripcion` varchar(45) DEFAULT NULL COMMENT '0 = Video\\n---------------------------------\\n1 = Lista de Reproduccion\\n2 = Coleccion\\n3 = Programa\\n4 = Canal',
  `estado` tinyint(4) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `usuario_registro` int(11) DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `usuario_actualizacion` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `default_cms_tipo_grupo_maestros` */

insert  into `default_cms_tipo_grupo_maestros`(`id`,`nombre`,`descripcion`,`estado`,`fecha_registro`,`usuario_registro`,`fecha_actualizacion`,`usuario_actualizacion`) values (1,'Lista de Reproduccion','Lista de reproduccion',1,'2012-01-01 00:00:00',1,NULL,NULL),(2,'Coleccion','Coleccion',1,'2012-01-01 00:00:00',1,NULL,NULL),(3,'Programa','Programa',1,'2012-01-01 00:00:00',1,NULL,NULL),(4,'Canal','Canal',1,'2012-01-01 00:00:00',1,NULL,NULL);

/*Table structure for table `default_cms_tipo_imagen` */

DROP TABLE IF EXISTS `default_cms_tipo_imagen`;

CREATE TABLE `default_cms_tipo_imagen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL COMMENT 'Small\\nMedium\\nLarge\\nXL',
  `descripcion` varchar(45) DEFAULT NULL COMMENT 'Pendiente- Determinar las dimensiones de las imagenes',
  `fecha_registro` datetime DEFAULT NULL,
  `usuario_registro` int(11) DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `usuario_actualizacion` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `default_cms_tipo_imagen` */

insert  into `default_cms_tipo_imagen`(`id`,`nombre`,`descripcion`,`fecha_registro`,`usuario_registro`,`fecha_actualizacion`,`usuario_actualizacion`) values (1,'pequeña','imagen pequeña','2012-01-01 00:00:00',1,NULL,NULL);

/*Table structure for table `default_cms_tipo_tags` */

DROP TABLE IF EXISTS `default_cms_tipo_tags`;

CREATE TABLE `default_cms_tipo_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL COMMENT 'Tematico\\nPersonaje',
  `descripcion` varchar(45) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `usuario_registro` int(11) DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `usuario_actualizacion` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `default_cms_tipo_tags` */

insert  into `default_cms_tipo_tags`(`id`,`nombre`,`descripcion`,`estado`,`fecha_registro`,`usuario_registro`,`fecha_actualizacion`,`usuario_actualizacion`) values (1,'Tematicos','Tags tematicos',1,'2012-12-01 00:00:00',1,NULL,NULL),(2,'Personajes','Tags personajes',1,'2012-12-01 00:00:00',1,NULL,NULL);

/*Table structure for table `default_cms_tipo_videos` */

DROP TABLE IF EXISTS `default_cms_tipo_videos`;

CREATE TABLE `default_cms_tipo_videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL COMMENT 'Normal\\nPremium',
  `descripcion` mediumtext,
  `estado` tinyint(4) DEFAULT '0' COMMENT '1 = Activo\\n0 = Inactivo',
  `fecha_registro` datetime DEFAULT NULL,
  `usuario_registro` int(11) DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `usuario_actualizacion` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `default_cms_tipo_videos` */

insert  into `default_cms_tipo_videos`(`id`,`nombre`,`descripcion`,`estado`,`fecha_registro`,`usuario_registro`,`fecha_actualizacion`,`usuario_actualizacion`) values (1,'Normal','Normal',1,'2013-02-01 00:00:00',1,'2013-02-01 00:00:00',NULL),(2,'Premium','Premium',1,'2013-02-01 00:00:00',1,'2013-02-01 00:00:00',NULL);

/*Table structure for table `default_cms_usuario_rol_canales` */

DROP TABLE IF EXISTS `default_cms_usuario_rol_canales`;

CREATE TABLE `default_cms_usuario_rol_canales` (
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `canal_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `default_cms_usuario_rol_canales` */

insert  into `default_cms_usuario_rol_canales`(`user_id`,`group_id`,`canal_id`) values (3,4,1),(3,4,2);

/*Table structure for table `default_cms_video_tags` */

DROP TABLE IF EXISTS `default_cms_video_tags`;

CREATE TABLE `default_cms_video_tags` (
  `tags_id` int(11) NOT NULL,
  `videos_id` int(11) NOT NULL,
  `estado` tinyint(4) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `usuario_registro` int(11) DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `usuario_actualizacion` int(11) DEFAULT NULL,
  `estado_migracion_sphinx` tinyint(4) DEFAULT NULL,
  `fecha_migracion_sphinx` datetime DEFAULT NULL,
  `fecha_migracion_actualizacion_sphinx` datetime DEFAULT NULL,
  PRIMARY KEY (`tags_id`,`videos_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `default_cms_video_tags` */

insert  into `default_cms_video_tags`(`tags_id`,`videos_id`,`estado`,`fecha_registro`,`usuario_registro`,`fecha_actualizacion`,`usuario_actualizacion`,`estado_migracion_sphinx`,`fecha_migracion_sphinx`,`fecha_migracion_actualizacion_sphinx`) values (1,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(2,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(3,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL);

/*Table structure for table `default_cms_videos` */

DROP TABLE IF EXISTS `default_cms_videos`;

CREATE TABLE `default_cms_videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID del Video',
  `tipo_videos_id` int(11) NOT NULL COMMENT 'ID del Tipo de Video',
  `categorias_id` int(11) NOT NULL,
  `usuarios_id` int(11) NOT NULL,
  `canales_id` int(11) NOT NULL,
  `fuente` int(11) NOT NULL,
  `nid` int(11) DEFAULT NULL COMMENT 'ID del VIDEO retornado por LIQUID',
  `titulo` varchar(100) DEFAULT NULL COMMENT 'Titulo del Video',
  `alias` varchar(60) DEFAULT NULL COMMENT 'Etiqueta del Portal utilizado para el SEO',
  `descripcion` mediumtext COMMENT 'Descripcion del Video',
  `codigo` varchar(45) DEFAULT NULL COMMENT 'CODIGO para la visualizacion DEL VIDEO retornado por LIQUID',
  `reproducciones` decimal(10,0) DEFAULT NULL COMMENT 'Nro de reproducciones del video en LIQUID',
  `duracion` time DEFAULT NULL,
  `fecha_publicacion` datetime DEFAULT NULL,
  `fecha_transmision` date DEFAULT NULL,
  `horario_transmision` time DEFAULT NULL,
  `ubicacion` varchar(45) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT '0' COMMENT 'Estado de Publicación del Video:0 = codificando, 1 = borrador, 2 = publicado, 3 = eliminado',
  `fecha_registro` datetime DEFAULT NULL COMMENT 'Fecha y hora del registro del Video\\n',
  `usuario_registro` int(11) DEFAULT NULL COMMENT 'ID Usuario que registra el Video\\n',
  `fecha_actualizacion` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la Ultima actualización del registro del Video\\n',
  `usuario_actualizacion` int(11) DEFAULT NULL COMMENT 'ID del Ultimo Usuario que modifica el registro del VIdeo.\\n',
  `nombre_proceso` varchar(45) DEFAULT NULL,
  `estado_migracion` tinyint(4) DEFAULT '0' COMMENT '\\"Status de la migración a MongoDB:\\n0 = Registro Nuevo (por migrar)\\n1 = Proceso (migrando)\\n2 = Migrado\\n\\nAdicionalmente para cuando se modifique un registro, el CMS graba un status 9, y el proceso lo re-tomará para migrarlo al MongoDB.\\n9 = Registro Actualiz /* comment truncated */',
  `fecha_migracion` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la migración por primera vez (registros nuevos)\\n',
  `fecha_migracion_actualizacion` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la migración de la ultima actualización, es decir, cuando un registro se actualiza se graba el status = 9, y el proceso lo re-toma para migrar al MongoDB					',
  `estado_migracion_sphinx_tit` tinyint(4) DEFAULT '0' COMMENT '\\"Status de la migración a Sphinx:\\n0 = Registro Nuevo (por migrar)\\n1 = Proceso (migrando)\\n2 = Migrado\\n\\nAdicionalmente para cuando se modifique un registro, el CMS graba un status 9, y el proceso lo re-tomará para migrarlo al Sphinx.\\n9 = Registro Actualizad /* comment truncated */',
  `fecha_migracion_sphinx_tit` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la migración por primera vez (registros nuevos)',
  `fecha_migracion_actualizacion_sphinx_tit` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la migración de la ultima actualización, es decir, cuando un registro se actualiza se graba el status = 9, y el proceso lo re-toma para migrar al Sphinx	',
  `estado_migracion_sphinx_des` tinyint(4) DEFAULT '0' COMMENT '\\"Status de la migración a Sphinx:\\n0 = Registro Nuevo (por migrar)\\n1 = Proceso (migrando)\\n2 = Migrado\\n\\nAdicionalmente para cuando se modifique un registro, el CMS graba un status 9, y el proceso lo re-tomará para migrarlo al Sphinx.\\n9 = Registro Actualizad /* comment truncated */',
  `fecha_migracion_sphinx_des` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la migración por primera vez (registros nuevos)',
  `fecha_migracion_actualizacion_sphinx_des` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la migración de la ultima actualización, es decir, cuando un registro se actualiza se graba el status = 9, y el proceso lo re-toma para migrar al Sphinx	',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `default_cms_videos` */

insert  into `default_cms_videos`(`id`,`tipo_videos_id`,`categorias_id`,`usuarios_id`,`canales_id`,`fuente`,`nid`,`titulo`,`alias`,`descripcion`,`codigo`,`reproducciones`,`duracion`,`fecha_publicacion`,`fecha_transmision`,`horario_transmision`,`ubicacion`,`estado`,`fecha_registro`,`usuario_registro`,`fecha_actualizacion`,`usuario_actualizacion`,`nombre_proceso`,`estado_migracion`,`fecha_migracion`,`fecha_migracion_actualizacion`,`estado_migracion_sphinx_tit`,`fecha_migracion_sphinx_tit`,`fecha_migracion_actualizacion_sphinx_tit`,`estado_migracion_sphinx_des`,`fecha_migracion_sphinx_des`,`fecha_migracion_actualizacion_sphinx_des`) values (1,1,1,3,1,1,NULL,'Video #1 - Canal 4','video-1-canal-4','Video #1 Canal 4',NULL,NULL,NULL,'2013-02-06 17:30:43','2012-05-25','10:00:00',NULL,2,'2012-05-12 00:00:00',3,NULL,NULL,NULL,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL);

/*Table structure for table `default_comments` */

DROP TABLE IF EXISTS `default_comments`;

CREATE TABLE `default_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_active` int(1) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `website` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `parsed` text COLLATE utf8_unicode_ci NOT NULL,
  `module` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `module_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `created_on` varchar(11) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `ip_address` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `default_comments` */

/*Table structure for table `default_contact_log` */

DROP TABLE IF EXISTS `default_contact_log`;

CREATE TABLE `default_contact_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `sender_agent` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sender_ip` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sender_os` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sent_at` int(11) NOT NULL DEFAULT '0',
  `attachments` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `default_contact_log` */

/*Table structure for table `default_data_field_assignments` */

DROP TABLE IF EXISTS `default_data_field_assignments`;

CREATE TABLE `default_data_field_assignments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sort_order` int(11) NOT NULL,
  `stream_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `is_required` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `is_unique` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `instructions` text COLLATE utf8_unicode_ci,
  `field_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `default_data_field_assignments` */

insert  into `default_data_field_assignments`(`id`,`sort_order`,`stream_id`,`field_id`,`is_required`,`is_unique`,`instructions`,`field_name`) values (1,1,1,1,'yes','no',NULL,NULL),(2,2,1,2,'yes','no',NULL,NULL),(3,3,1,3,'no','no',NULL,NULL),(4,4,1,4,'no','no',NULL,NULL),(5,5,1,5,'no','no',NULL,NULL),(6,6,1,6,'no','no',NULL,NULL),(7,7,1,7,'no','no',NULL,NULL),(8,8,1,8,'no','no',NULL,NULL),(9,9,1,9,'no','no',NULL,NULL),(10,10,1,10,'no','no',NULL,NULL),(11,11,1,11,'no','no',NULL,NULL),(12,12,1,12,'no','no',NULL,NULL),(13,13,1,13,'no','no',NULL,NULL),(14,14,1,14,'no','no',NULL,NULL);

/*Table structure for table `default_data_fields` */

DROP TABLE IF EXISTS `default_data_fields`;

CREATE TABLE `default_data_fields` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `field_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `field_slug` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `field_namespace` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `field_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `field_data` blob,
  `view_options` blob,
  `is_locked` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `default_data_fields` */

insert  into `default_data_fields`(`id`,`field_name`,`field_slug`,`field_namespace`,`field_type`,`field_data`,`view_options`,`is_locked`) values (1,'lang:user_first_name','first_name','users','text','a:1:{s:10:\"max_length\";i:50;}',NULL,'no'),(2,'lang:user_last_name','last_name','users','text','a:1:{s:10:\"max_length\";i:50;}',NULL,'no'),(3,'lang:profile_company','company','users','text','a:1:{s:10:\"max_length\";i:100;}',NULL,'no'),(4,'lang:profile_bio','bio','users','textarea','a:0:{}',NULL,'no'),(5,'lang:user_lang','lang','users','pyro_lang','a:1:{s:12:\"filter_theme\";s:3:\"yes\";}',NULL,'no'),(6,'lang:profile_dob','dob','users','datetime','a:4:{s:8:\"use_time\";s:2:\"no\";s:10:\"start_date\";s:5:\"-100Y\";s:7:\"storage\";s:4:\"unix\";s:10:\"input_type\";s:8:\"dropdown\";}',NULL,'no'),(7,'lang:profile_gender','gender','users','choice','a:2:{s:11:\"choice_data\";s:34:\" : Not Telling\nm : Male\nf : Female\";s:11:\"choice_type\";s:8:\"dropdown\";}',NULL,'no'),(8,'lang:profile_phone','phone','users','text','a:1:{s:10:\"max_length\";i:20;}',NULL,'no'),(9,'lang:profile_mobile','mobile','users','text','a:1:{s:10:\"max_length\";i:20;}',NULL,'no'),(10,'lang:profile_address_line1','address_line1','users','text','a:0:{}',NULL,'no'),(11,'lang:profile_address_line2','address_line2','users','text','a:0:{}',NULL,'no'),(12,'lang:profile_address_line3','address_line3','users','text','a:0:{}',NULL,'no'),(13,'lang:profile_address_postcode','postcode','users','text','a:1:{s:10:\"max_length\";i:20;}',NULL,'no'),(14,'lang:profile_website','website','users','url',NULL,NULL,'no');

/*Table structure for table `default_data_streams` */

DROP TABLE IF EXISTS `default_data_streams`;

CREATE TABLE `default_data_streams` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `stream_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `stream_slug` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `stream_namespace` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stream_prefix` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `about` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `view_options` blob NOT NULL,
  `title_column` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sorting` enum('title','custom') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'title',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `default_data_streams` */

insert  into `default_data_streams`(`id`,`stream_name`,`stream_slug`,`stream_namespace`,`stream_prefix`,`about`,`view_options`,`title_column`,`sorting`) values (1,'lang:user_profile_fields_label','profiles','users',NULL,'Profiles for users module','a:1:{i:0;s:12:\"display_name\";}','display_name','title');

/*Table structure for table `default_email_templates` */

DROP TABLE IF EXISTS `default_email_templates`;

CREATE TABLE `default_email_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `lang` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_default` int(1) NOT NULL DEFAULT '0',
  `module` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug_lang` (`slug`,`lang`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `default_email_templates` */

insert  into `default_email_templates`(`id`,`slug`,`name`,`description`,`subject`,`body`,`lang`,`is_default`,`module`) values (1,'comments','Comment Notification','Email that is sent to admin when someone creates a comment','You have just received a comment from {{ name }}','<h3>You have received a comment from {{ name }}</h3>\n				<p>\n				<strong>IP Address: {{ sender_ip }}</strong><br/>\n				<strong>Operating System: {{ sender_os }}<br/>\n				<strong>User Agent: {{ sender_agent }}</strong>\n				</p>\n				<p>{{ comment }}</p>\n				<p>View Comment: {{ redirect_url }}</p>','en',1,'comments'),(2,'contact','Contact Notification','Template for the contact form','{{ settings:site_name }} :: {{ subject }}','This message was sent via the contact form on with the following details:\n				<hr />\n				IP Address: {{ sender_ip }}\n				OS {{ sender_os }}\n				Agent {{ sender_agent }}\n				<hr />\n				{{ message }}\n\n				{{ name }},\n\n				{{ email }}','en',1,'pages'),(3,'registered','New User Registered','Email sent to the site contact e-mail when a new user registers','{{ settings:site_name }} :: You have just received a registration from {{ name }}','<h3>You have received a registration from {{ name }}</h3>\n				<p><strong>IP Address: {{ sender_ip }}</strong><br/>\n				<strong>Operating System: {{ sender_os }}</strong><br/>\n				<strong>User Agent: {{ sender_agent }}</strong>\n				</p>','en',1,'users'),(4,'activation','Activation Email','The email which contains the activation code that is sent to a new user','{{ settings:site_name }} - Account Activation','<p>Hello {{ user:first_name }},</p>\n				<p>Thank you for registering at {{ settings:site_name }}. Before we can activate your account, please complete the registration process by clicking on the following link:</p>\n				<p><a href=\"{{ url:site }}users/activate/{{ user:id }}/{{ activation_code }}\">{{ url:site }}users/activate/{{ user:id }}/{{ activation_code }}</a></p>\n				<p>&nbsp;</p>\n				<p>In case your email program does not recognize the above link as, please direct your browser to the following URL and enter the activation code:</p>\n				<p><a href=\"{{ url:site }}users/activate\">{{ url:site }}users/activate</a></p>\n				<p><strong>Activation Code:</strong> {{ activation_code }}</p>','en',1,'users'),(5,'forgotten_password','Forgotten Password Email','The email that is sent containing a password reset code','{{ settings:site_name }} - Forgotten Password','<p>Hello {{ user:first_name }},</p>\n				<p>It seems you have requested a password reset. Please click this link to complete the reset: <a href=\"{{ url:site }}users/reset_pass/{{ user:forgotten_password_code }}\">{{ url:site }}users/reset_pass/{{ user:forgotten_password_code }}</a></p>\n				<p>If you did not request a password reset please disregard this message. No further action is necessary.</p>','en',1,'users'),(6,'new_password','New Password Email','After a password is reset this email is sent containing the new password','{{ settings:site_name }} - New Password','<p>Hello {{ user:first_name }},</p>\n				<p>Your new password is: {{ new_password }}</p>\n				<p>After logging in you may change your password by visiting <a href=\"{{ url:site }}edit-profile\">{{ url:site }}edit-profile</a></p>','en',1,'users');

/*Table structure for table `default_file_folders` */

DROP TABLE IF EXISTS `default_file_folders`;

CREATE TABLE `default_file_folders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT '0',
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'local',
  `remote_container` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `date_added` int(11) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `default_file_folders` */

/*Table structure for table `default_files` */

DROP TABLE IF EXISTS `default_files`;

CREATE TABLE `default_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `folder_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '1',
  `type` enum('a','v','d','i','o') COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `extension` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `mimetype` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `width` int(5) DEFAULT NULL,
  `height` int(5) DEFAULT NULL,
  `filesize` int(11) NOT NULL DEFAULT '0',
  `download_count` int(11) NOT NULL DEFAULT '0',
  `date_added` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `default_files` */

/*Table structure for table `default_groups` */

DROP TABLE IF EXISTS `default_groups`;

CREATE TABLE `default_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `default_groups` */

insert  into `default_groups`(`id`,`name`,`description`) values (1,'admin','Administrator'),(2,'user','User'),(3,'administrador-mi-canal','Administrador Mi Canal'),(4,'administrador-canales','Administrador Canales'),(5,'gestor','Gestor');

/*Table structure for table `default_keywords` */

DROP TABLE IF EXISTS `default_keywords`;

CREATE TABLE `default_keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `default_keywords` */

insert  into `default_keywords`(`id`,`name`) values (1,'categoria'),(2,'noticia'),(3,'lima'),(4,'ewewew'),(5,'perú'),(6,'alemania'),(7,'san isidro');

/*Table structure for table `default_keywords_applied` */

DROP TABLE IF EXISTS `default_keywords_applied`;

CREATE TABLE `default_keywords_applied` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` char(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `keyword_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `default_keywords_applied` */

insert  into `default_keywords_applied`(`id`,`hash`,`keyword_id`) values (4,'acf8ff07ba03c03258c724f2cc78c7a6',4),(11,'05780b83b2cf82e6acc83d170d0e4ec5',1),(12,'05780b83b2cf82e6acc83d170d0e4ec5',3),(13,'05780b83b2cf82e6acc83d170d0e4ec5',2);

/*Table structure for table `default_migrations` */

DROP TABLE IF EXISTS `default_migrations`;

CREATE TABLE `default_migrations` (
  `version` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `default_migrations` */

insert  into `default_migrations`(`version`) values (0),(96);

/*Table structure for table `default_modules` */

DROP TABLE IF EXISTS `default_modules`;

CREATE TABLE `default_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `version` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `skip_xss` tinyint(1) NOT NULL,
  `is_frontend` tinyint(1) NOT NULL,
  `is_backend` tinyint(1) NOT NULL,
  `menu` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `installed` tinyint(1) NOT NULL,
  `is_core` tinyint(1) NOT NULL,
  `updated_on` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `enabled` (`enabled`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `default_modules` */

insert  into `default_modules`(`id`,`name`,`slug`,`version`,`type`,`description`,`skip_xss`,`is_frontend`,`is_backend`,`menu`,`enabled`,`installed`,`is_core`,`updated_on`) values (1,'a:15:{s:2:\"en\";s:4:\"Blog\";s:2:\"ar\";s:16:\"المدوّنة\";s:2:\"br\";s:4:\"Blog\";s:2:\"pt\";s:4:\"Blog\";s:2:\"el\";s:18:\"Ιστολόγιο\";s:2:\"he\";s:8:\"בלוג\";s:2:\"id\";s:4:\"Blog\";s:2:\"lt\";s:6:\"Blogas\";s:2:\"pl\";s:4:\"Blog\";s:2:\"ru\";s:8:\"Блог\";s:2:\"zh\";s:6:\"文章\";s:2:\"hu\";s:4:\"Blog\";s:2:\"fi\";s:5:\"Blogi\";s:2:\"th\";s:15:\"บล็อก\";s:2:\"se\";s:5:\"Blogg\";}','blog','2.0',NULL,'a:23:{s:2:\"en\";s:18:\"Post blog entries.\";s:2:\"ar\";s:48:\"أنشر المقالات على مدوّنتك.\";s:2:\"br\";s:30:\"Escrever publicações de blog\";s:2:\"pt\";s:39:\"Escrever e editar publicações no blog\";s:2:\"cs\";s:49:\"Publikujte nové články a příspěvky na blog.\";s:2:\"da\";s:17:\"Skriv blogindlæg\";s:2:\"de\";s:47:\"Veröffentliche neue Artikel und Blog-Einträge\";s:2:\"sl\";s:23:\"Objavite blog prispevke\";s:2:\"fi\";s:28:\"Kirjoita blogi artikkeleita.\";s:2:\"el\";s:93:\"Δημιουργήστε άρθρα και εγγραφές στο ιστολόγιο σας.\";s:2:\"es\";s:54:\"Escribe entradas para los artículos y blog (web log).\";s:2:\"fr\";s:46:\"Envoyez de nouveaux posts et messages de blog.\";s:2:\"he\";s:19:\"ניהול בלוג\";s:2:\"id\";s:15:\"Post entri blog\";s:2:\"it\";s:36:\"Pubblica notizie e post per il blog.\";s:2:\"lt\";s:40:\"Rašykite naujienas bei blog\'o įrašus.\";s:2:\"nl\";s:41:\"Post nieuwsartikelen en blogs op uw site.\";s:2:\"pl\";s:27:\"Dodawaj nowe wpisy na blogu\";s:2:\"ru\";s:49:\"Управление записями блога.\";s:2:\"zh\";s:42:\"發表新聞訊息、部落格等文章。\";s:2:\"th\";s:48:\"โพสต์รายการบล็อก\";s:2:\"hu\";s:32:\"Blog bejegyzések létrehozása.\";s:2:\"se\";s:18:\"Inlägg i bloggen.\";}',1,1,1,'content',1,1,1,1358181248),(2,'a:23:{s:2:\"en\";s:8:\"Comments\";s:2:\"ar\";s:18:\"التعليقات\";s:2:\"br\";s:12:\"Comentários\";s:2:\"pt\";s:12:\"Comentários\";s:2:\"cs\";s:11:\"Komentáře\";s:2:\"da\";s:11:\"Kommentarer\";s:2:\"de\";s:10:\"Kommentare\";s:2:\"el\";s:12:\"Σχόλια\";s:2:\"es\";s:11:\"Comentarios\";s:2:\"fi\";s:9:\"Kommentit\";s:2:\"fr\";s:12:\"Commentaires\";s:2:\"he\";s:12:\"תגובות\";s:2:\"id\";s:8:\"Komentar\";s:2:\"it\";s:8:\"Commenti\";s:2:\"lt\";s:10:\"Komentarai\";s:2:\"nl\";s:8:\"Reacties\";s:2:\"pl\";s:10:\"Komentarze\";s:2:\"ru\";s:22:\"Комментарии\";s:2:\"sl\";s:10:\"Komentarji\";s:2:\"zh\";s:6:\"回應\";s:2:\"hu\";s:16:\"Hozzászólások\";s:2:\"th\";s:33:\"ความคิดเห็น\";s:2:\"se\";s:11:\"Kommentarer\";}','comments','1.0',NULL,'a:23:{s:2:\"en\";s:76:\"Users and guests can write comments for content like blog, pages and photos.\";s:2:\"ar\";s:152:\"يستطيع الأعضاء والزوّار كتابة التعليقات على المُحتوى كالأخبار، والصفحات والصّوَر.\";s:2:\"br\";s:97:\"Usuários e convidados podem escrever comentários para quase tudo com suporte nativo ao captcha.\";s:2:\"pt\";s:100:\"Utilizadores e convidados podem escrever comentários para quase tudo com suporte nativo ao captcha.\";s:2:\"cs\";s:100:\"Uživatelé a hosté mohou psát komentáře k obsahu, např. neovinkám, stránkám a fotografiím.\";s:2:\"da\";s:83:\"Brugere og besøgende kan skrive kommentarer til indhold som blog, sider og fotoer.\";s:2:\"de\";s:65:\"Benutzer und Gäste können für fast alles Kommentare schreiben.\";s:2:\"el\";s:224:\"Οι χρήστες και οι επισκέπτες μπορούν να αφήνουν σχόλια για περιεχόμενο όπως το ιστολόγιο, τις σελίδες και τις φωτογραφίες.\";s:2:\"es\";s:130:\"Los usuarios y visitantes pueden escribir comentarios en casi todo el contenido con el soporte de un sistema de captcha incluído.\";s:2:\"fi\";s:107:\"Käyttäjät ja vieraat voivat kirjoittaa kommentteja eri sisältöihin kuten uutisiin, sivuihin ja kuviin.\";s:2:\"fr\";s:130:\"Les utilisateurs et les invités peuvent écrire des commentaires pour quasiment tout grâce au générateur de captcha intégré.\";s:2:\"he\";s:94:\"משתמשי האתר יכולים לרשום תגובות למאמרים, תמונות וכו\";s:2:\"id\";s:100:\"Pengguna dan pengunjung dapat menuliskan komentaruntuk setiap konten seperti blog, halaman dan foto.\";s:2:\"it\";s:85:\"Utenti e visitatori possono scrivere commenti ai contenuti quali blog, pagine e foto.\";s:2:\"lt\";s:75:\"Vartotojai ir svečiai gali komentuoti jūsų naujienas, puslapius ar foto.\";s:2:\"nl\";s:52:\"Gebruikers en gasten kunnen reageren op bijna alles.\";s:2:\"pl\";s:93:\"Użytkownicy i goście mogą dodawać komentarze z wbudowanym systemem zabezpieczeń captcha.\";s:2:\"ru\";s:187:\"Пользователи и гости могут добавлять комментарии к новостям, информационным страницам и фотографиям.\";s:2:\"sl\";s:89:\"Uporabniki in obiskovalci lahko vnesejo komentarje na vsebino kot je blok, stra ali slike\";s:2:\"zh\";s:75:\"用戶和訪客可以針對新聞、頁面與照片等內容發表回應。\";s:2:\"hu\";s:117:\"A felhasználók és a vendégek hozzászólásokat írhatnak a tartalomhoz (bejegyzésekhez, oldalakhoz, fotókhoz).\";s:2:\"th\";s:240:\"ผู้ใช้งานและผู้เยี่ยมชมสามารถเขียนความคิดเห็นในเนื้อหาของหน้าเว็บบล็อกและภาพถ่าย\";s:2:\"se\";s:98:\"Användare och besökare kan skriva kommentarer till innehåll som blogginlägg, sidor och bilder.\";}',0,0,1,'content',1,1,1,1358181248),(3,'a:23:{s:2:\"en\";s:7:\"Contact\";s:2:\"ar\";s:14:\"الإتصال\";s:2:\"br\";s:7:\"Contato\";s:2:\"pt\";s:8:\"Contacto\";s:2:\"cs\";s:7:\"Kontakt\";s:2:\"da\";s:7:\"Kontakt\";s:2:\"de\";s:7:\"Kontakt\";s:2:\"el\";s:22:\"Επικοινωνία\";s:2:\"es\";s:8:\"Contacto\";s:2:\"fi\";s:13:\"Ota yhteyttä\";s:2:\"fr\";s:7:\"Contact\";s:2:\"he\";s:17:\"יצירת קשר\";s:2:\"id\";s:6:\"Kontak\";s:2:\"it\";s:10:\"Contattaci\";s:2:\"lt\";s:18:\"Kontaktinė formą\";s:2:\"nl\";s:7:\"Contact\";s:2:\"pl\";s:7:\"Kontakt\";s:2:\"ru\";s:27:\"Обратная связь\";s:2:\"sl\";s:7:\"Kontakt\";s:2:\"zh\";s:12:\"聯絡我們\";s:2:\"hu\";s:9:\"Kapcsolat\";s:2:\"th\";s:18:\"ติดต่อ\";s:2:\"se\";s:7:\"Kontakt\";}','contact','0.9',NULL,'a:23:{s:2:\"en\";s:112:\"Adds a form to your site that allows visitors to send emails to you without disclosing an email address to them.\";s:2:\"ar\";s:157:\"إضافة استمارة إلى موقعك تُمكّن الزوّار من مراسلتك دون علمهم بعنوان البريد الإلكتروني.\";s:2:\"br\";s:139:\"Adiciona um formulário para o seu site permitir aos visitantes que enviem e-mails para voce sem divulgar um endereço de e-mail para eles.\";s:2:\"pt\";s:116:\"Adiciona um formulário ao seu site que permite aos visitantes enviarem e-mails sem divulgar um endereço de e-mail.\";s:2:\"cs\";s:149:\"Přidá na web kontaktní formulář pro návštěvníky a uživatele, díky kterému vás mohou kontaktovat i bez znalosti vaší e-mailové adresy.\";s:2:\"da\";s:123:\"Tilføjer en formular på din side som tillader besøgende at sende mails til dig, uden at du skal opgive din email-adresse\";s:2:\"de\";s:119:\"Fügt ein Formular hinzu, welches Besuchern erlaubt Emails zu schreiben, ohne die Kontakt Email-Adresse offen zu legen.\";s:2:\"el\";s:273:\"Προσθέτει μια φόρμα στον ιστότοπό σας που επιτρέπει σε επισκέπτες να σας στέλνουν μηνύμα μέσω email χωρίς να τους αποκαλύπτεται η διεύθυνση του email σας.\";s:2:\"es\";s:156:\"Añade un formulario a tu sitio que permitirá a los visitantes enviarte correos electrónicos a ti sin darles tu dirección de correo directamente a ellos.\";s:2:\"fi\";s:128:\"Luo lomakkeen sivustollesi, josta kävijät voivat lähettää sähköpostia tietämättä vastaanottajan sähköpostiosoitetta.\";s:2:\"fr\";s:122:\"Ajoute un formulaire à votre site qui permet aux visiteurs de vous envoyer un e-mail sans révéler votre adresse e-mail.\";s:2:\"he\";s:155:\"מוסיף תופס יצירת קשר לאתר על מנת לא לחסוף כתובת דואר האלקטרוני של האתר למנועי פרסומות\";s:2:\"id\";s:149:\"Menambahkan formulir ke dalam situs Anda yang memungkinkan pengunjung untuk mengirimkan email kepada Anda tanpa memberikan alamat email kepada mereka\";s:2:\"it\";s:119:\"Aggiunge un modulo al tuo sito che permette ai visitatori di inviarti email senza mostrare loro il tuo indirizzo email.\";s:2:\"lt\";s:124:\"Prideda jūsų puslapyje formą leidžianti lankytojams siūsti jums el. laiškus neatskleidžiant jūsų el. pašto adreso.\";s:2:\"nl\";s:125:\"Voegt een formulier aan de site toe waarmee bezoekers een email kunnen sturen, zonder dat u ze een emailadres hoeft te tonen.\";s:2:\"pl\";s:126:\"Dodaje formularz kontaktowy do Twojej strony, który pozwala użytkownikom wysłanie maila za pomocą formularza kontaktowego.\";s:2:\"ru\";s:234:\"Добавляет форму обратной связи на сайт, через которую посетители могут отправлять вам письма, при этом адрес Email остаётся скрыт.\";s:2:\"sl\";s:113:\"Dodaj obrazec za kontakt da vam lahko obiskovalci pošljejo sporočilo brez da bi jim razkrili vaš email naslov.\";s:2:\"zh\";s:147:\"為您的網站新增「聯絡我們」的功能，對訪客是較為清楚便捷的聯絡方式，也無須您將電子郵件公開在網站上。\";s:2:\"th\";s:316:\"เพิ่มแบบฟอร์มในเว็บไซต์ของคุณ ช่วยให้ผู้เยี่ยมชมสามารถส่งอีเมลถึงคุณโดยไม่ต้องเปิดเผยที่อยู่อีเมลของพวกเขา\";s:2:\"hu\";s:156:\"Létrehozható vele olyan űrlap, amely lehetővé teszi a látogatók számára, hogy e-mailt küldjenek neked úgy, hogy nem feded fel az e-mail címedet.\";s:2:\"se\";s:53:\"Lägger till ett kontaktformulär till din webbplats.\";}',0,0,0,'0',1,1,1,1358181248),(4,'a:22:{s:2:\"en\";s:5:\"Files\";s:2:\"ar\";s:16:\"الملفّات\";s:2:\"br\";s:8:\"Arquivos\";s:2:\"pt\";s:9:\"Ficheiros\";s:2:\"cs\";s:7:\"Soubory\";s:2:\"da\";s:5:\"Filer\";s:2:\"de\";s:7:\"Dateien\";s:2:\"el\";s:12:\"Αρχεία\";s:2:\"es\";s:8:\"Archivos\";s:2:\"fi\";s:9:\"Tiedostot\";s:2:\"fr\";s:8:\"Fichiers\";s:2:\"he\";s:10:\"קבצים\";s:2:\"id\";s:4:\"File\";s:2:\"it\";s:4:\"File\";s:2:\"lt\";s:6:\"Failai\";s:2:\"nl\";s:9:\"Bestanden\";s:2:\"ru\";s:10:\"Файлы\";s:2:\"sl\";s:8:\"Datoteke\";s:2:\"zh\";s:6:\"檔案\";s:2:\"hu\";s:7:\"Fájlok\";s:2:\"th\";s:12:\"ไฟล์\";s:2:\"se\";s:5:\"Filer\";}','files','2.0',NULL,'a:22:{s:2:\"en\";s:40:\"Manages files and folders for your site.\";s:2:\"ar\";s:50:\"إدارة ملفات ومجلّدات موقعك.\";s:2:\"br\";s:53:\"Permite gerenciar facilmente os arquivos de seu site.\";s:2:\"pt\";s:59:\"Permite gerir facilmente os ficheiros e pastas do seu site.\";s:2:\"cs\";s:43:\"Spravujte soubory a složky na vašem webu.\";s:2:\"da\";s:41:\"Administrer filer og mapper for dit site.\";s:2:\"de\";s:35:\"Verwalte Dateien und Verzeichnisse.\";s:2:\"el\";s:100:\"Διαχειρίζεται αρχεία και φακέλους για το ιστότοπό σας.\";s:2:\"es\";s:43:\"Administra archivos y carpetas en tu sitio.\";s:2:\"fi\";s:43:\"Hallitse sivustosi tiedostoja ja kansioita.\";s:2:\"fr\";s:46:\"Gérer les fichiers et dossiers de votre site.\";s:2:\"he\";s:47:\"ניהול תיקיות וקבצים שבאתר\";s:2:\"id\";s:42:\"Mengatur file dan folder dalam situs Anda.\";s:2:\"it\";s:38:\"Gestisci file e cartelle del tuo sito.\";s:2:\"lt\";s:28:\"Katalogų ir bylų valdymas.\";s:2:\"nl\";s:41:\"Beheer bestanden en mappen op uw website.\";s:2:\"ru\";s:78:\"Управление файлами и папками вашего сайта.\";s:2:\"sl\";s:38:\"Uredi datoteke in mape na vaši strani\";s:2:\"zh\";s:33:\"管理網站中的檔案與目錄\";s:2:\"hu\";s:41:\"Fájlok és mappák kezelése az oldalon.\";s:2:\"th\";s:141:\"บริหารจัดการไฟล์และโฟลเดอร์สำหรับเว็บไซต์ของคุณ\";s:2:\"se\";s:45:\"Hanterar filer och mappar för din webbplats.\";}',0,0,1,'content',1,1,1,1358181248),(5,'a:22:{s:2:\"en\";s:6:\"Groups\";s:2:\"ar\";s:18:\"المجموعات\";s:2:\"br\";s:6:\"Grupos\";s:2:\"pt\";s:6:\"Grupos\";s:2:\"cs\";s:7:\"Skupiny\";s:2:\"da\";s:7:\"Grupper\";s:2:\"de\";s:7:\"Gruppen\";s:2:\"el\";s:12:\"Ομάδες\";s:2:\"es\";s:6:\"Grupos\";s:2:\"fi\";s:7:\"Ryhmät\";s:2:\"fr\";s:7:\"Groupes\";s:2:\"he\";s:12:\"קבוצות\";s:2:\"id\";s:4:\"Grup\";s:2:\"it\";s:6:\"Gruppi\";s:2:\"lt\";s:7:\"Grupės\";s:2:\"nl\";s:7:\"Groepen\";s:2:\"ru\";s:12:\"Группы\";s:2:\"sl\";s:7:\"Skupine\";s:2:\"zh\";s:6:\"群組\";s:2:\"hu\";s:9:\"Csoportok\";s:2:\"th\";s:15:\"กลุ่ม\";s:2:\"se\";s:7:\"Grupper\";}','groups','1.0',NULL,'a:22:{s:2:\"en\";s:54:\"Users can be placed into groups to manage permissions.\";s:2:\"ar\";s:100:\"يمكن وضع المستخدمين في مجموعات لتسهيل إدارة صلاحياتهم.\";s:2:\"br\";s:72:\"Usuários podem ser inseridos em grupos para gerenciar suas permissões.\";s:2:\"pt\";s:74:\"Utilizadores podem ser inseridos em grupos para gerir as suas permissões.\";s:2:\"cs\";s:77:\"Uživatelé mohou být rozřazeni do skupin pro lepší správu oprávnění.\";s:2:\"da\";s:49:\"Brugere kan inddeles i grupper for adgangskontrol\";s:2:\"de\";s:85:\"Benutzer können zu Gruppen zusammengefasst werden um diesen Zugriffsrechte zu geben.\";s:2:\"el\";s:168:\"Οι χρήστες μπορούν να τοποθετηθούν σε ομάδες και έτσι να διαχειριστείτε τα δικαιώματά τους.\";s:2:\"es\";s:75:\"Los usuarios podrán ser colocados en grupos para administrar sus permisos.\";s:2:\"fi\";s:84:\"Käyttäjät voidaan liittää ryhmiin, jotta käyttöoikeuksia voidaan hallinnoida.\";s:2:\"fr\";s:82:\"Les utilisateurs peuvent appartenir à des groupes afin de gérer les permissions.\";s:2:\"he\";s:62:\"נותן אפשרות לאסוף משתמשים לקבוצות\";s:2:\"id\";s:68:\"Pengguna dapat dikelompokkan ke dalam grup untuk mengatur perizinan.\";s:2:\"it\";s:69:\"Gli utenti possono essere inseriti in gruppi per gestirne i permessi.\";s:2:\"lt\";s:67:\"Vartotojai gali būti priskirti grupei tam, kad valdyti jų teises.\";s:2:\"nl\";s:73:\"Gebruikers kunnen in groepen geplaatst worden om rechten te kunnen geven.\";s:2:\"ru\";s:134:\"Пользователей можно объединять в группы, для управления правами доступа.\";s:2:\"sl\";s:64:\"Uporabniki so lahko razvrščeni v skupine za urejanje dovoljenj\";s:2:\"zh\";s:45:\"用戶可以依群組分類並管理其權限\";s:2:\"hu\";s:73:\"A felhasználók csoportokba rendezhetőek a jogosultságok kezelésére.\";s:2:\"th\";s:84:\"สามารถวางผู้ใช้ลงในกลุ่มเพื่\";s:2:\"se\";s:76:\"Användare kan delas in i grupper för att hantera roller och behörigheter.\";}',0,0,1,'users',1,1,1,1358181248),(6,'a:15:{s:2:\"en\";s:8:\"Keywords\";s:2:\"ar\";s:21:\"كلمات البحث\";s:2:\"br\";s:14:\"Palavras-chave\";s:2:\"pt\";s:14:\"Palavras-chave\";s:2:\"da\";s:9:\"Nøgleord\";s:2:\"el\";s:27:\"Λέξεις Κλειδιά\";s:2:\"fr\";s:10:\"Mots-Clés\";s:2:\"id\";s:10:\"Kata Kunci\";s:2:\"nl\";s:14:\"Sleutelwoorden\";s:2:\"zh\";s:6:\"鍵詞\";s:2:\"hu\";s:11:\"Kulcsszavak\";s:2:\"fi\";s:10:\"Avainsanat\";s:2:\"sl\";s:15:\"Ključne besede\";s:2:\"th\";s:15:\"คำค้น\";s:2:\"se\";s:9:\"Nyckelord\";}','keywords','1.0',NULL,'a:15:{s:2:\"en\";s:71:\"Maintain a central list of keywords to label and organize your content.\";s:2:\"ar\";s:124:\"أنشئ مجموعة من كلمات البحث التي تستطيع من خلالها وسم وتنظيم المحتوى.\";s:2:\"br\";s:85:\"Mantém uma lista central de palavras-chave para rotular e organizar o seu conteúdo.\";s:2:\"pt\";s:85:\"Mantém uma lista central de palavras-chave para rotular e organizar o seu conteúdo.\";s:2:\"da\";s:72:\"Vedligehold en central liste af nøgleord for at organisere dit indhold.\";s:2:\"el\";s:181:\"Συντηρεί μια κεντρική λίστα από λέξεις κλειδιά για να οργανώνετε μέσω ετικετών το περιεχόμενό σας.\";s:2:\"fr\";s:87:\"Maintenir une liste centralisée de Mots-Clés pour libeller et organiser vos contenus.\";s:2:\"id\";s:71:\"Memantau daftar kata kunci untuk melabeli dan mengorganisasikan konten.\";s:2:\"nl\";s:91:\"Beheer een centrale lijst van sleutelwoorden om uw content te categoriseren en organiseren.\";s:2:\"zh\";s:64:\"集中管理可用於標題與內容的鍵詞(keywords)列表。\";s:2:\"hu\";s:65:\"Ez egy központi kulcsszó lista a cimkékhez és a tartalmakhoz.\";s:2:\"fi\";s:92:\"Hallinnoi keskitettyä listaa avainsanoista merkitäksesi ja järjestelläksesi sisältöä.\";s:2:\"sl\";s:82:\"Vzdržuj centralni seznam ključnih besed za označevanje in ogranizacijo vsebine.\";s:2:\"th\";s:189:\"ศูนย์กลางการปรับปรุงคำค้นในการติดฉลากและจัดระเบียบเนื้อหาของคุณ\";s:2:\"se\";s:61:\"Hantera nyckelord för att organisera webbplatsens innehåll.\";}',0,0,1,'content',1,1,1,1358181248),(7,'a:12:{s:2:\"en\";s:11:\"Maintenance\";s:2:\"pt\";s:12:\"Manutenção\";s:2:\"ar\";s:14:\"الصيانة\";s:2:\"el\";s:18:\"Συντήρηση\";s:2:\"hu\";s:13:\"Karbantartás\";s:2:\"fi\";s:9:\"Ylläpito\";s:2:\"fr\";s:11:\"Maintenance\";s:2:\"id\";s:12:\"Pemeliharaan\";s:2:\"se\";s:10:\"Underhåll\";s:2:\"sl\";s:12:\"Vzdrževanje\";s:2:\"th\";s:39:\"การบำรุงรักษา\";s:2:\"zh\";s:6:\"維護\";}','maintenance','1.0',NULL,'a:12:{s:2:\"en\";s:63:\"Manage the site cache and export information from the database.\";s:2:\"pt\";s:68:\"Gerir o cache do seu site e exportar informações da base de dados.\";s:2:\"ar\";s:81:\"حذف عناصر الذاكرة المخبأة عبر واجهة الإدارة.\";s:2:\"el\";s:142:\"Διαγραφή αντικειμένων προσωρινής αποθήκευσης μέσω της περιοχής διαχείρισης.\";s:2:\"id\";s:60:\"Mengatur cache situs dan mengexport informasi dari database.\";s:2:\"fr\";s:71:\"Gérer le cache du site et exporter les contenus de la base de données\";s:2:\"fi\";s:59:\"Hallinoi sivuston välimuistia ja vie tietoa tietokannasta.\";s:2:\"hu\";s:66:\"Az oldal gyorsítótár kezelése és az adatbázis exportálása.\";s:2:\"se\";s:76:\"Underhåll webbplatsens cache och exportera data från webbplatsens databas.\";s:2:\"sl\";s:69:\"Upravljaj s predpomnilnikom strani (cache) in izvozi podatke iz baze.\";s:2:\"th\";s:150:\"การจัดการแคชเว็บไซต์และข้อมูลการส่งออกจากฐานข้อมูล\";s:2:\"zh\";s:45:\"經由管理介面手動刪除暫存資料。\";}',0,0,1,'utilities',1,1,1,1358181248),(8,'a:23:{s:2:\"en\";s:7:\"Modules\";s:2:\"ar\";s:14:\"الوحدات\";s:2:\"br\";s:8:\"Módulos\";s:2:\"pt\";s:8:\"Módulos\";s:2:\"cs\";s:6:\"Moduly\";s:2:\"da\";s:7:\"Moduler\";s:2:\"de\";s:6:\"Module\";s:2:\"el\";s:16:\"Πρόσθετα\";s:2:\"es\";s:8:\"Módulos\";s:2:\"fi\";s:8:\"Moduulit\";s:2:\"fr\";s:7:\"Modules\";s:2:\"he\";s:14:\"מודולים\";s:2:\"id\";s:5:\"Modul\";s:2:\"it\";s:6:\"Moduli\";s:2:\"lt\";s:8:\"Moduliai\";s:2:\"nl\";s:7:\"Modules\";s:2:\"pl\";s:7:\"Moduły\";s:2:\"ru\";s:12:\"Модули\";s:2:\"sl\";s:6:\"Moduli\";s:2:\"zh\";s:6:\"模組\";s:2:\"hu\";s:7:\"Modulok\";s:2:\"th\";s:15:\"โมดูล\";s:2:\"se\";s:7:\"Moduler\";}','modules','1.0',NULL,'a:23:{s:2:\"en\";s:59:\"Allows admins to see a list of currently installed modules.\";s:2:\"ar\";s:91:\"تُمكّن المُدراء من معاينة جميع الوحدات المُثبّتة.\";s:2:\"br\";s:75:\"Permite aos administradores ver a lista dos módulos instalados atualmente.\";s:2:\"pt\";s:75:\"Permite aos administradores ver a lista dos módulos instalados atualmente.\";s:2:\"cs\";s:68:\"Umožňuje administrátorům vidět seznam nainstalovaných modulů.\";s:2:\"da\";s:63:\"Lader administratorer se en liste over de installerede moduler.\";s:2:\"de\";s:56:\"Zeigt Administratoren alle aktuell installierten Module.\";s:2:\"el\";s:152:\"Επιτρέπει στους διαχειριστές να προβάλουν μια λίστα των εγκατεστημένων πρόσθετων.\";s:2:\"es\";s:71:\"Permite a los administradores ver una lista de los módulos instalados.\";s:2:\"fi\";s:60:\"Listaa järjestelmänvalvojalle käytössä olevat moduulit.\";s:2:\"fr\";s:66:\"Permet aux administrateurs de voir la liste des modules installés\";s:2:\"he\";s:160:\"נותן אופציה למנהל לראות רשימה של המודולים אשר מותקנים כעת באתר או להתקין מודולים נוספים\";s:2:\"id\";s:57:\"Memperlihatkan kepada admin daftar modul yang terinstall.\";s:2:\"it\";s:83:\"Permette agli amministratori di vedere una lista dei moduli attualmente installati.\";s:2:\"lt\";s:75:\"Vartotojai ir svečiai gali komentuoti jūsų naujienas, puslapius ar foto.\";s:2:\"nl\";s:79:\"Stelt admins in staat om een overzicht van geinstalleerde modules te genereren.\";s:2:\"pl\";s:81:\"Umożliwiają administratorowi wgląd do listy obecnie zainstalowanych modułów.\";s:2:\"ru\";s:83:\"Список модулей, которые установлены на сайте.\";s:2:\"sl\";s:65:\"Dovoljuje administratorjem pregled trenutno nameščenih modulov.\";s:2:\"zh\";s:54:\"管理員可以檢視目前已經安裝模組的列表\";s:2:\"hu\";s:79:\"Lehetővé teszi az adminoknak, hogy lássák a telepített modulok listáját.\";s:2:\"th\";s:162:\"ช่วยให้ผู้ดูแลระบบดูรายการของโมดูลที่ติดตั้งในปัจจุบัน\";s:2:\"se\";s:67:\"Gör det möjligt för administratören att se installerade mouler.\";}',0,0,1,'0',1,1,1,1358181248),(9,'a:23:{s:2:\"en\";s:10:\"Navigation\";s:2:\"ar\";s:14:\"الروابط\";s:2:\"br\";s:11:\"Navegação\";s:2:\"pt\";s:11:\"Navegação\";s:2:\"cs\";s:8:\"Navigace\";s:2:\"da\";s:10:\"Navigation\";s:2:\"de\";s:10:\"Navigation\";s:2:\"el\";s:16:\"Πλοήγηση\";s:2:\"es\";s:11:\"Navegación\";s:2:\"fi\";s:10:\"Navigointi\";s:2:\"fr\";s:10:\"Navigation\";s:2:\"he\";s:10:\"ניווט\";s:2:\"id\";s:8:\"Navigasi\";s:2:\"it\";s:11:\"Navigazione\";s:2:\"lt\";s:10:\"Navigacija\";s:2:\"nl\";s:9:\"Navigatie\";s:2:\"pl\";s:9:\"Nawigacja\";s:2:\"ru\";s:18:\"Навигация\";s:2:\"sl\";s:10:\"Navigacija\";s:2:\"zh\";s:12:\"導航選單\";s:2:\"th\";s:36:\"ตัวช่วยนำทาง\";s:2:\"hu\";s:11:\"Navigáció\";s:2:\"se\";s:10:\"Navigation\";}','navigation','1.1',NULL,'a:23:{s:2:\"en\";s:78:\"Manage links on navigation menus and all the navigation groups they belong to.\";s:2:\"ar\";s:85:\"إدارة روابط وقوائم ومجموعات الروابط في الموقع.\";s:2:\"br\";s:91:\"Gerenciar links do menu de navegação e todos os grupos de navegação pertencentes a ele.\";s:2:\"pt\";s:93:\"Gerir todos os grupos dos menus de navegação e os links de navegação pertencentes a eles.\";s:2:\"cs\";s:73:\"Správa odkazů v navigaci a všech souvisejících navigačních skupin.\";s:2:\"da\";s:82:\"Håndtér links på navigationsmenuerne og alle navigationsgrupperne de tilhører.\";s:2:\"de\";s:76:\"Verwalte Links in Navigationsmenüs und alle zugehörigen Navigationsgruppen\";s:2:\"el\";s:207:\"Διαχειριστείτε τους συνδέσμους στα μενού πλοήγησης και όλες τις ομάδες συνδέσμων πλοήγησης στις οποίες ανήκουν.\";s:2:\"es\";s:102:\"Administra links en los menús de navegación y en todos los grupos de navegación al cual pertenecen.\";s:2:\"fi\";s:91:\"Hallitse linkkejä navigointi valikoissa ja kaikkia navigointi ryhmiä, joihin ne kuuluvat.\";s:2:\"fr\";s:97:\"Gérer les liens du menu Navigation et tous les groupes de navigation auxquels ils appartiennent.\";s:2:\"he\";s:73:\"ניהול שלוחות תפריטי ניווט וקבוצות ניווט\";s:2:\"id\";s:73:\"Mengatur tautan pada menu navigasi dan semua pengelompokan grup navigasi.\";s:2:\"it\";s:97:\"Gestisci i collegamenti dei menu di navigazione e tutti i gruppi di navigazione da cui dipendono.\";s:2:\"lt\";s:95:\"Tvarkyk nuorodas navigacijų menių ir visas navigacijų grupes kurioms tos nuorodos priklauso.\";s:2:\"nl\";s:92:\"Beheer koppelingen op de navigatiemenu&apos;s en alle navigatiegroepen waar ze onder vallen.\";s:2:\"pl\";s:95:\"Zarządzaj linkami w menu nawigacji oraz wszystkimi grupami nawigacji do których one należą.\";s:2:\"ru\";s:136:\"Управление ссылками в меню навигации и группах, к которым они принадлежат.\";s:2:\"sl\";s:64:\"Uredi povezave v meniju in vse skupine povezav ki jim pripadajo.\";s:2:\"zh\";s:72:\"管理導航選單中的連結，以及它們所隸屬的導航群組。\";s:2:\"th\";s:108:\"จัดการการเชื่อมโยงนำทางและกลุ่มนำทาง\";s:2:\"hu\";s:100:\"Linkek kezelése a navigációs menükben és a navigációs csoportok kezelése, amikhez tartoznak.\";s:2:\"se\";s:33:\"Hantera länkar och länkgrupper.\";}',0,0,1,'design',1,1,1,1358181248),(10,'a:23:{s:2:\"en\";s:5:\"Pages\";s:2:\"ar\";s:14:\"الصفحات\";s:2:\"br\";s:8:\"Páginas\";s:2:\"pt\";s:8:\"Páginas\";s:2:\"cs\";s:8:\"Stránky\";s:2:\"da\";s:5:\"Sider\";s:2:\"de\";s:6:\"Seiten\";s:2:\"el\";s:14:\"Σελίδες\";s:2:\"es\";s:8:\"Páginas\";s:2:\"fi\";s:5:\"Sivut\";s:2:\"fr\";s:5:\"Pages\";s:2:\"he\";s:8:\"דפים\";s:2:\"id\";s:7:\"Halaman\";s:2:\"it\";s:6:\"Pagine\";s:2:\"lt\";s:9:\"Puslapiai\";s:2:\"nl\";s:13:\"Pagina&apos;s\";s:2:\"pl\";s:6:\"Strony\";s:2:\"ru\";s:16:\"Страницы\";s:2:\"sl\";s:6:\"Strani\";s:2:\"zh\";s:6:\"頁面\";s:2:\"hu\";s:7:\"Oldalak\";s:2:\"th\";s:21:\"หน้าเพจ\";s:2:\"se\";s:5:\"Sidor\";}','pages','2.2.0',NULL,'a:23:{s:2:\"en\";s:55:\"Add custom pages to the site with any content you want.\";s:2:\"ar\";s:99:\"إضافة صفحات مُخصّصة إلى الموقع تحتوي أية مُحتوى تريده.\";s:2:\"br\";s:82:\"Adicionar páginas personalizadas ao site com qualquer conteúdo que você queira.\";s:2:\"pt\";s:86:\"Adicionar páginas personalizadas ao seu site com qualquer conteúdo que você queira.\";s:2:\"cs\";s:74:\"Přidávejte vlastní stránky na web s jakýmkoliv obsahem budete chtít.\";s:2:\"da\";s:71:\"Tilføj brugerdefinerede sider til dit site med det indhold du ønsker.\";s:2:\"de\";s:49:\"Füge eigene Seiten mit anpassbaren Inhalt hinzu.\";s:2:\"el\";s:152:\"Προσθέστε και επεξεργαστείτε σελίδες στον ιστότοπό σας με ό,τι περιεχόμενο θέλετε.\";s:2:\"es\";s:77:\"Agrega páginas customizadas al sitio con cualquier contenido que tu quieras.\";s:2:\"fi\";s:47:\"Lisää mitä tahansa sisältöä sivustollesi.\";s:2:\"fr\";s:89:\"Permet d\'ajouter sur le site des pages personalisées avec le contenu que vous souhaitez.\";s:2:\"he\";s:35:\"ניהול דפי תוכן האתר\";s:2:\"id\";s:75:\"Menambahkan halaman ke dalam situs dengan konten apapun yang Anda perlukan.\";s:2:\"it\";s:73:\"Aggiungi pagine personalizzate al sito con qualsiesi contenuto tu voglia.\";s:2:\"lt\";s:46:\"Pridėkite nuosavus puslapius betkokio turinio\";s:2:\"nl\";s:70:\"Voeg aangepaste pagina&apos;s met willekeurige inhoud aan de site toe.\";s:2:\"pl\";s:53:\"Dodaj własne strony z dowolną treścią do witryny.\";s:2:\"ru\";s:134:\"Управление информационными страницами сайта, с произвольным содержимым.\";s:2:\"sl\";s:44:\"Dodaj stran s kakršno koli vsebino želite.\";s:2:\"zh\";s:39:\"為您的網站新增自定的頁面。\";s:2:\"th\";s:168:\"เพิ่มหน้าเว็บที่กำหนดเองไปยังเว็บไซต์ของคุณตามที่ต้องการ\";s:2:\"hu\";s:67:\"Saját oldalak hozzáadása a weboldalhoz, akármilyen tartalommal.\";s:2:\"se\";s:39:\"Lägg till egna sidor till webbplatsen.\";}',1,1,1,'content',1,1,1,1358181248),(11,'a:23:{s:2:\"en\";s:11:\"Permissions\";s:2:\"ar\";s:18:\"الصلاحيات\";s:2:\"br\";s:11:\"Permissões\";s:2:\"pt\";s:11:\"Permissões\";s:2:\"cs\";s:12:\"Oprávnění\";s:2:\"da\";s:14:\"Adgangskontrol\";s:2:\"de\";s:14:\"Zugriffsrechte\";s:2:\"el\";s:20:\"Δικαιώματα\";s:2:\"es\";s:8:\"Permisos\";s:2:\"fi\";s:16:\"Käyttöoikeudet\";s:2:\"fr\";s:11:\"Permissions\";s:2:\"he\";s:12:\"הרשאות\";s:2:\"id\";s:9:\"Perizinan\";s:2:\"it\";s:8:\"Permessi\";s:2:\"lt\";s:7:\"Teisės\";s:2:\"nl\";s:15:\"Toegangsrechten\";s:2:\"pl\";s:11:\"Uprawnienia\";s:2:\"ru\";s:25:\"Права доступа\";s:2:\"sl\";s:10:\"Dovoljenja\";s:2:\"zh\";s:6:\"權限\";s:2:\"hu\";s:14:\"Jogosultságok\";s:2:\"th\";s:18:\"สิทธิ์\";s:2:\"se\";s:13:\"Behörigheter\";}','permissions','0.6',NULL,'a:23:{s:2:\"en\";s:68:\"Control what type of users can see certain sections within the site.\";s:2:\"ar\";s:127:\"التحكم بإعطاء الصلاحيات للمستخدمين للوصول إلى أقسام الموقع المختلفة.\";s:2:\"br\";s:68:\"Controle quais tipos de usuários podem ver certas seções no site.\";s:2:\"pt\";s:75:\"Controle quais os tipos de utilizadores podem ver certas secções no site.\";s:2:\"cs\";s:93:\"Spravujte oprávnění pro jednotlivé typy uživatelů a ke kterým sekcím mají přístup.\";s:2:\"da\";s:72:\"Kontroller hvilken type brugere der kan se bestemte sektioner på sitet.\";s:2:\"de\";s:70:\"Regelt welche Art von Benutzer welche Sektion in der Seite sehen kann.\";s:2:\"el\";s:180:\"Ελέγξτε τα δικαιώματα χρηστών και ομάδων χρηστών όσο αφορά σε διάφορες λειτουργίες του ιστοτόπου.\";s:2:\"es\";s:81:\"Controla que tipo de usuarios pueden ver secciones específicas dentro del sitio.\";s:2:\"fi\";s:72:\"Hallitse minkä tyyppisiin osioihin käyttäjät pääsevät sivustolla.\";s:2:\"fr\";s:104:\"Permet de définir les autorisations des groupes d\'utilisateurs pour afficher les différentes sections.\";s:2:\"he\";s:75:\"ניהול הרשאות כניסה לאיזורים מסוימים באתר\";s:2:\"id\";s:76:\"Mengontrol tipe pengguna mana yang dapat mengakses suatu bagian dalam situs.\";s:2:\"it\";s:78:\"Controlla che tipo di utenti posssono accedere a determinate sezioni del sito.\";s:2:\"lt\";s:72:\"Kontroliuokite kokio tipo varotojai kokią dalį puslapio gali pasiekti.\";s:2:\"nl\";s:71:\"Bepaal welke typen gebruikers toegang hebben tot gedeeltes van de site.\";s:2:\"pl\";s:79:\"Ustaw, którzy użytkownicy mogą mieć dostęp do odpowiednich sekcji witryny.\";s:2:\"ru\";s:209:\"Управление правами доступа, ограничение доступа определённых групп пользователей к произвольным разделам сайта.\";s:2:\"sl\";s:85:\"Uredite dovoljenja kateri tip uporabnika lahko vidi določena področja vaše strani.\";s:2:\"zh\";s:81:\"用來控制不同類別的用戶，設定其瀏覽特定網站內容的權限。\";s:2:\"hu\";s:129:\"A felhasználók felügyelet alatt tartására, hogy milyen típusú felhasználók, mit láthatnak, mely szakaszain az oldalnak.\";s:2:\"th\";s:117:\"ควบคุมว่าผู้ใช้งานจะเห็นหมวดหมู่ไหนบ้าง\";s:2:\"se\";s:27:\"Hantera gruppbehörigheter.\";}',0,0,1,'users',1,1,1,1358181248),(12,'a:21:{s:2:\"en\";s:9:\"Redirects\";s:2:\"ar\";s:18:\"التوجيهات\";s:2:\"br\";s:17:\"Redirecionamentos\";s:2:\"pt\";s:17:\"Redirecionamentos\";s:2:\"cs\";s:16:\"Přesměrování\";s:2:\"da\";s:13:\"Omadressering\";s:2:\"el\";s:30:\"Ανακατευθύνσεις\";s:2:\"es\";s:13:\"Redirecciones\";s:2:\"fi\";s:18:\"Uudelleenohjaukset\";s:2:\"fr\";s:12:\"Redirections\";s:2:\"he\";s:12:\"הפניות\";s:2:\"id\";s:8:\"Redirect\";s:2:\"it\";s:11:\"Reindirizzi\";s:2:\"lt\";s:14:\"Peradresavimai\";s:2:\"nl\";s:12:\"Verwijzingen\";s:2:\"ru\";s:30:\"Перенаправления\";s:2:\"sl\";s:12:\"Preusmeritve\";s:2:\"zh\";s:6:\"轉址\";s:2:\"hu\";s:17:\"Átirányítások\";s:2:\"th\";s:42:\"เปลี่ยนเส้นทาง\";s:2:\"se\";s:14:\"Omdirigeringar\";}','redirects','1.0',NULL,'a:21:{s:2:\"en\";s:33:\"Redirect from one URL to another.\";s:2:\"ar\";s:47:\"التوجيه من رابط URL إلى آخر.\";s:2:\"br\";s:39:\"Redirecionamento de uma URL para outra.\";s:2:\"pt\";s:40:\"Redirecionamentos de uma URL para outra.\";s:2:\"cs\";s:43:\"Přesměrujte z jedné adresy URL na jinou.\";s:2:\"da\";s:35:\"Omadresser fra en URL til en anden.\";s:2:\"el\";s:81:\"Ανακατευθείνετε μια διεύθυνση URL σε μια άλλη\";s:2:\"es\";s:34:\"Redireccionar desde una URL a otra\";s:2:\"fi\";s:45:\"Uudelleenohjaa käyttäjän paikasta toiseen.\";s:2:\"fr\";s:34:\"Redirection d\'une URL à un autre.\";s:2:\"he\";s:43:\"הפניות מכתובת אחת לאחרת\";s:2:\"id\";s:40:\"Redirect dari satu URL ke URL yang lain.\";s:2:\"it\";s:35:\"Reindirizza da una URL ad un altra.\";s:2:\"lt\";s:56:\"Peradresuokite puslapį iš vieno adreso (URL) į kitą.\";s:2:\"nl\";s:38:\"Verwijs vanaf een URL naar een andere.\";s:2:\"ru\";s:78:\"Перенаправления с одного адреса на другой.\";s:2:\"sl\";s:44:\"Preusmeritev iz enega URL naslova na drugega\";s:2:\"zh\";s:33:\"將網址轉址、重新定向。\";s:2:\"hu\";s:38:\"Egy URL átirányítása egy másikra.\";s:2:\"th\";s:123:\"เปลี่ยนเส้นทางจากที่หนึ่งไปยังอีกที่หนึ่ง\";s:2:\"se\";s:38:\"Omdirigera från en URL till en annan.\";}',0,0,1,'utilities',1,1,1,1358181248),(13,'a:23:{s:2:\"en\";s:8:\"Settings\";s:2:\"ar\";s:18:\"الإعدادات\";s:2:\"br\";s:15:\"Configurações\";s:2:\"pt\";s:15:\"Configurações\";s:2:\"cs\";s:10:\"Nastavení\";s:2:\"da\";s:13:\"Indstillinger\";s:2:\"de\";s:13:\"Einstellungen\";s:2:\"el\";s:18:\"Ρυθμίσεις\";s:2:\"es\";s:15:\"Configuraciones\";s:2:\"fi\";s:9:\"Asetukset\";s:2:\"fr\";s:11:\"Paramètres\";s:2:\"he\";s:12:\"הגדרות\";s:2:\"id\";s:10:\"Pengaturan\";s:2:\"it\";s:12:\"Impostazioni\";s:2:\"lt\";s:10:\"Nustatymai\";s:2:\"nl\";s:12:\"Instellingen\";s:2:\"pl\";s:10:\"Ustawienia\";s:2:\"ru\";s:18:\"Настройки\";s:2:\"sl\";s:10:\"Nastavitve\";s:2:\"zh\";s:12:\"網站設定\";s:2:\"hu\";s:14:\"Beállítások\";s:2:\"th\";s:21:\"ตั้งค่า\";s:2:\"se\";s:14:\"Inställningar\";}','settings','1.0',NULL,'a:23:{s:2:\"en\";s:89:\"Allows administrators to update settings like Site Name, messages and email address, etc.\";s:2:\"ar\";s:161:\"تمكن المدراء من تحديث الإعدادات كإسم الموقع، والرسائل وعناوين البريد الإلكتروني، .. إلخ.\";s:2:\"br\";s:120:\"Permite com que administradores e a equipe consigam trocar as configurações do website incluindo o nome e descrição.\";s:2:\"pt\";s:113:\"Permite com que os administradores consigam alterar as configurações do website incluindo o nome e descrição.\";s:2:\"cs\";s:102:\"Umožňuje administrátorům měnit nastavení webu jako jeho jméno, zprávy a emailovou adresu apod.\";s:2:\"da\";s:90:\"Lader administratorer opdatere indstillinger som sidenavn, beskeder og email adresse, etc.\";s:2:\"de\";s:92:\"Erlaubt es Administratoren die Einstellungen der Seite wie Name und Beschreibung zu ändern.\";s:2:\"el\";s:230:\"Επιτρέπει στους διαχειριστές να τροποποιήσουν ρυθμίσεις όπως το Όνομα του Ιστοτόπου, τα μηνύματα και τις διευθύνσεις email, κ.α.\";s:2:\"es\";s:131:\"Permite a los administradores y al personal configurar los detalles del sitio como el nombre del sitio y la descripción del mismo.\";s:2:\"fi\";s:105:\"Mahdollistaa sivuston asetusten muokkaamisen, kuten sivuston nimen, viestit ja sähköpostiosoitteet yms.\";s:2:\"fr\";s:105:\"Permet aux admistrateurs et au personnel de modifier les paramètres du site : nom du site et description\";s:2:\"he\";s:116:\"ניהול הגדרות שונות של האתר כגון: שם האתר, הודעות, כתובות דואר וכו\";s:2:\"id\";s:112:\"Memungkinkan administrator untuk dapat memperbaharui pengaturan seperti nama situs, pesan dan alamat email, dsb.\";s:2:\"it\";s:109:\"Permette agli amministratori di aggiornare impostazioni quali Nome del Sito, messaggi e indirizzo email, etc.\";s:2:\"lt\";s:104:\"Leidžia administratoriams keisti puslapio vavadinimą, žinutes, administratoriaus el. pašta ir kitą.\";s:2:\"nl\";s:114:\"Maakt het administratoren en medewerkers mogelijk om websiteinstellingen zoals naam en beschrijving te veranderen.\";s:2:\"pl\";s:103:\"Umożliwia administratorom zmianę ustawień strony jak nazwa strony, opis, e-mail administratora, itd.\";s:2:\"ru\";s:135:\"Управление настройками сайта - Имя сайта, сообщения, почтовые адреса и т.п.\";s:2:\"sl\";s:98:\"Dovoljuje administratorjem posodobitev nastavitev kot je Ime strani, sporočil, email naslova itd.\";s:2:\"zh\";s:99:\"網站管理者可更新的重要網站設定。例如：網站名稱、訊息、電子郵件等。\";s:2:\"hu\";s:125:\"Lehetővé teszi az adminok számára a beállítások frissítését, mint a weboldal neve, üzenetek, e-mail címek, stb...\";s:2:\"th\";s:232:\"ให้ผู้ดูแลระบบสามารถปรับปรุงการตั้งค่าเช่นชื่อเว็บไซต์ ข้อความและอีเมล์เป็นต้น\";s:2:\"se\";s:84:\"Administratören kan uppdatera webbplatsens titel, meddelanden och E-postadress etc.\";}',1,0,1,'0',1,1,1,1358181248),(14,'a:18:{s:2:\"en\";s:7:\"Sitemap\";s:2:\"ar\";s:23:\"خريطة الموقع\";s:2:\"br\";s:12:\"Mapa do Site\";s:2:\"pt\";s:12:\"Mapa do Site\";s:2:\"de\";s:7:\"Sitemap\";s:2:\"el\";s:31:\"Χάρτης Ιστότοπου\";s:2:\"es\";s:14:\"Mapa del Sitio\";s:2:\"fi\";s:10:\"Sivukartta\";s:2:\"fr\";s:12:\"Plan du site\";s:2:\"id\";s:10:\"Peta Situs\";s:2:\"it\";s:14:\"Mappa del sito\";s:2:\"lt\";s:16:\"Svetainės medis\";s:2:\"nl\";s:7:\"Sitemap\";s:2:\"ru\";s:21:\"Карта сайта\";s:2:\"zh\";s:12:\"網站地圖\";s:2:\"th\";s:21:\"ไซต์แมพ\";s:2:\"hu\";s:13:\"Oldaltérkép\";s:2:\"se\";s:9:\"Sajtkarta\";}','sitemap','1.2',NULL,'a:19:{s:2:\"en\";s:87:\"The sitemap module creates an index of all pages and an XML sitemap for search engines.\";s:2:\"ar\";s:120:\"وحدة خريطة الموقع تنشئ فهرساً لجميع الصفحات وملف XML لمحركات البحث.\";s:2:\"br\";s:102:\"O módulo de mapa do site cria um índice de todas as páginas e um sitemap XML para motores de busca.\";s:2:\"pt\";s:102:\"O módulo do mapa do site cria um índice de todas as páginas e um sitemap XML para motores de busca.\";s:2:\"da\";s:86:\"Sitemapmodulet opretter et indeks over alle sider og et XML sitemap til søgemaskiner.\";s:2:\"de\";s:92:\"Die Sitemap Modul erstellt einen Index aller Seiten und eine XML-Sitemap für Suchmaschinen.\";s:2:\"el\";s:190:\"Δημιουργεί έναν κατάλογο όλων των σελίδων και έναν χάρτη σελίδων σε μορφή XML για τις μηχανές αναζήτησης.\";s:2:\"es\";s:111:\"El módulo de mapa crea un índice de todas las páginas y un mapa del sitio XML para los motores de búsqueda.\";s:2:\"fi\";s:82:\"sivukartta moduuli luo hakemisto kaikista sivuista ja XML sivukartta hakukoneille.\";s:2:\"fr\";s:106:\"Le module sitemap crée un index de toutes les pages et un plan de site XML pour les moteurs de recherche.\";s:2:\"id\";s:110:\"Modul peta situs ini membuat indeks dari setiap halaman dan sebuah format XML untuk mempermudah mesin pencari.\";s:2:\"it\";s:104:\"Il modulo mappa del sito crea un indice di tutte le pagine e una sitemap in XML per i motori di ricerca.\";s:2:\"lt\";s:86:\"struktūra modulis sukuria visų puslapių ir XML Sitemap paieškos sistemų indeksas.\";s:2:\"nl\";s:89:\"De sitemap module maakt een index van alle pagina\'s en een XML sitemap voor zoekmachines.\";s:2:\"ru\";s:144:\"Карта модуль создает индекс всех страниц и карта сайта XML для поисковых систем.\";s:2:\"zh\";s:84:\"站點地圖模塊創建一個索引的所有網頁和XML網站地圖搜索引擎。\";s:2:\"th\";s:202:\"โมดูลไซต์แมพสามารถสร้างดัชนีของหน้าเว็บทั้งหมดสำหรับเครื่องมือค้นหา.\";s:2:\"hu\";s:94:\"Ez a modul indexeli az összes oldalt és egy XML oldaltéképet generál a keresőmotoroknak.\";s:2:\"se\";s:86:\"Sajtkarta, modulen skapar ett index av alla sidor och en XML-sitemap för sökmotorer.\";}',0,1,0,'content',1,1,1,1358181248),(15,'a:5:{s:2:\"en\";s:12:\"Streams Core\";s:2:\"pt\";s:14:\"Núcleo Fluxos\";s:2:\"fr\";s:10:\"Noyau Flux\";s:2:\"el\";s:23:\"Πυρήνας Ροών\";s:2:\"se\";s:18:\"Streams grundmodul\";}','streams_core','1.0.0',NULL,'a:5:{s:2:\"en\";s:29:\"Core data module for streams.\";s:2:\"pt\";s:37:\"Módulo central de dados para fluxos.\";s:2:\"fr\";s:32:\"Noyau de données pour les Flux.\";s:2:\"el\";s:113:\"Προγραμματιστικός πυρήνας για την λειτουργία ροών δεδομένων.\";s:2:\"se\";s:50:\"Streams grundmodul för enklare hantering av data.\";}',1,0,0,'0',1,1,1,1358181248),(16,'a:19:{s:2:\"en\";s:15:\"Email Templates\";s:2:\"ar\";s:48:\"قوالب الرسائل الإلكترونية\";s:2:\"br\";s:17:\"Modelos de e-mail\";s:2:\"pt\";s:17:\"Modelos de e-mail\";s:2:\"da\";s:16:\"Email skabeloner\";s:2:\"el\";s:22:\"Δυναμικά email\";s:2:\"es\";s:19:\"Plantillas de email\";s:2:\"fr\";s:17:\"Modèles d\'emails\";s:2:\"he\";s:12:\"תבניות\";s:2:\"id\";s:14:\"Template Email\";s:2:\"lt\";s:22:\"El. laiškų šablonai\";s:2:\"nl\";s:15:\"Email sjablonen\";s:2:\"ru\";s:25:\"Шаблоны почты\";s:2:\"sl\";s:14:\"Email predloge\";s:2:\"zh\";s:12:\"郵件範本\";s:2:\"hu\";s:15:\"E-mail sablonok\";s:2:\"fi\";s:25:\"Sähköposti viestipohjat\";s:2:\"th\";s:33:\"แม่แบบอีเมล\";s:2:\"se\";s:12:\"E-postmallar\";}','templates','1.1.0',NULL,'a:19:{s:2:\"en\";s:46:\"Create, edit, and save dynamic email templates\";s:2:\"ar\";s:97:\"أنشئ، عدّل واحفظ قوالب البريد الإلكترني الديناميكية.\";s:2:\"br\";s:51:\"Criar, editar e salvar modelos de e-mail dinâmicos\";s:2:\"pt\";s:51:\"Criar, editar e salvar modelos de e-mail dinâmicos\";s:2:\"da\";s:49:\"Opret, redigér og gem dynamiske emailskabeloner.\";s:2:\"el\";s:108:\"Δημιουργήστε, επεξεργαστείτε και αποθηκεύστε δυναμικά email.\";s:2:\"es\";s:54:\"Crear, editar y guardar plantillas de email dinámicas\";s:2:\"fr\";s:61:\"Créer, éditer et sauver dynamiquement des modèles d\'emails\";s:2:\"he\";s:54:\"ניהול של תבניות דואר אלקטרוני\";s:2:\"id\";s:55:\"Membuat, mengedit, dan menyimpan template email dinamis\";s:2:\"lt\";s:58:\"Kurk, tvarkyk ir saugok dinaminius el. laiškų šablonus.\";s:2:\"nl\";s:49:\"Maak, bewerk, en beheer dynamische emailsjablonen\";s:2:\"ru\";s:127:\"Создавайте, редактируйте и сохраняйте динамические почтовые шаблоны\";s:2:\"sl\";s:52:\"Ustvari, uredi in shrani spremenljive email predloge\";s:2:\"zh\";s:61:\"新增、編輯與儲存可顯示動態資料的 email 範本\";s:2:\"hu\";s:63:\"Csináld, szerkeszd és mentsd el a dinamikus e-mail sablonokat\";s:2:\"fi\";s:66:\"Lisää, muokkaa ja tallenna dynaamisia sähköposti viestipohjia.\";s:2:\"th\";s:129:\"การสร้างแก้ไขและบันทึกแม่แบบอีเมลแบบไดนามิก\";s:2:\"se\";s:49:\"Skapa, redigera och spara dynamiska E-postmallar.\";}',1,0,1,'design',1,1,1,1358181248),(17,'a:23:{s:2:\"en\";s:6:\"Themes\";s:2:\"ar\";s:14:\"السّمات\";s:2:\"br\";s:5:\"Temas\";s:2:\"pt\";s:5:\"Temas\";s:2:\"cs\";s:14:\"Motivy vzhledu\";s:2:\"da\";s:6:\"Temaer\";s:2:\"de\";s:6:\"Themen\";s:2:\"el\";s:31:\"Θέματα Εμφάνισης\";s:2:\"es\";s:5:\"Temas\";s:2:\"fi\";s:6:\"Teemat\";s:2:\"fr\";s:7:\"Thèmes\";s:2:\"he\";s:23:\"ערכות נושאים\";s:2:\"id\";s:4:\"Tema\";s:2:\"it\";s:4:\"Temi\";s:2:\"lt\";s:5:\"Temos\";s:2:\"nl\";s:7:\"Thema\'s\";s:2:\"pl\";s:6:\"Motywy\";s:2:\"ru\";s:8:\"Темы\";s:2:\"sl\";s:8:\"Predloge\";s:2:\"zh\";s:12:\"佈景主題\";s:2:\"hu\";s:8:\"Sablonok\";s:2:\"th\";s:9:\"ธีม\";s:2:\"se\";s:5:\"Teman\";}','themes','1.0',NULL,'a:23:{s:2:\"en\";s:86:\"Allows admins and staff to switch themes, upload new themes, and manage theme options.\";s:2:\"ar\";s:170:\"تمكّن الإدارة وأعضاء الموقع تغيير سِمة الموقع، وتحميل سمات جديدة وإدارتها بطريقة مرئية سلسة.\";s:2:\"br\";s:125:\"Permite aos administradores e membros da equipe fazer upload de novos temas e gerenciá-los através de uma interface visual.\";s:2:\"pt\";s:100:\"Permite aos administradores fazer upload de novos temas e geri-los através de uma interface visual.\";s:2:\"cs\";s:106:\"Umožňuje administrátorům a dalším osobám měnit vzhled webu, nahrávat nové motivy a spravovat je.\";s:2:\"da\";s:108:\"Lader administratore ændre websidens tema, uploade nye temaer og håndtére dem med en mere visual tilgang.\";s:2:\"de\";s:121:\"Ermöglicht es dem Administrator das Seiten Thema auszuwählen, neue Themen hochzulanden oder diese visuell zu verwalten.\";s:2:\"el\";s:222:\"Επιτρέπει στους διαχειριστές να αλλάξουν το θέμα προβολής του ιστοτόπου να ανεβάσουν νέα θέματα και να τα διαχειριστούν.\";s:2:\"es\";s:132:\"Permite a los administradores y miembros del personal cambiar el tema del sitio web, subir nuevos temas y manejar los ya existentes.\";s:2:\"fi\";s:129:\"Mahdollistaa sivuston teeman vaihtamisen, uusien teemojen lataamisen ja niiden hallinnoinnin visuaalisella käyttöliittymällä.\";s:2:\"fr\";s:144:\"Permet aux administrateurs et au personnel de modifier le thème du site, de charger de nouveaux thèmes et de le gérer de façon plus visuelle\";s:2:\"he\";s:63:\"ניהול של ערכות נושאים שונות - עיצוב\";s:2:\"id\";s:104:\"Memungkinkan admin dan staff untuk mengubah tema tampilan, mengupload tema baru, dan mengatur opsi tema.\";s:2:\"it\";s:120:\"Permette ad amministratori e staff di cambiare il tema del sito, carica nuovi temi e gestiscili in um modo più visuale.\";s:2:\"lt\";s:105:\"Leidžiama administratoriams ir personalui keisti puslapio temą, įkraunant naują temą ir valdyti ją.\";s:2:\"nl\";s:153:\"Maakt het voor administratoren en medewerkers mogelijk om het thema van de website te wijzigen, nieuwe thema&apos;s te uploaden en ze visueel te beheren.\";s:2:\"pl\";s:100:\"Umożliwia administratorowi zmianę motywu strony, wgrywanie nowych motywów oraz zarządzanie nimi.\";s:2:\"ru\";s:102:\"Управление темами оформления сайта, загрузка новых тем.\";s:2:\"sl\";s:133:\"Dovoljuje adminom in osebju spremembo izgleda spletne strani, namestitev novega izgleda in urejanja le tega v bolj vizualnem pristopu\";s:2:\"zh\";s:108:\"讓管理者可以更改網站顯示風貌，以視覺化的操作上傳並管理這些網站佈景主題。\";s:2:\"th\";s:219:\"ช่วยให้ผู้ดูแลระบบสามารถอัปโหลดรูปแบบใหม่และการจัดการตัวเลือกชุดรูปแบบได้\";s:2:\"hu\";s:107:\"Az adminok megváltoztathatják az oldal kinézetét, feltölthetnek új kinézeteket és kezelhetik őket.\";s:2:\"se\";s:94:\"Hantera webbplatsens utseende genom teman, ladda upp nya teman och hantera temainställningar.\";}',0,0,1,'design',1,1,1,1358181248),(18,'a:23:{s:2:\"en\";s:5:\"Users\";s:2:\"ar\";s:20:\"المستخدمون\";s:2:\"br\";s:9:\"Usuários\";s:2:\"pt\";s:12:\"Utilizadores\";s:2:\"cs\";s:11:\"Uživatelé\";s:2:\"da\";s:7:\"Brugere\";s:2:\"de\";s:8:\"Benutzer\";s:2:\"el\";s:14:\"Χρήστες\";s:2:\"es\";s:8:\"Usuarios\";s:2:\"fi\";s:12:\"Käyttäjät\";s:2:\"fr\";s:12:\"Utilisateurs\";s:2:\"he\";s:14:\"משתמשים\";s:2:\"id\";s:8:\"Pengguna\";s:2:\"it\";s:6:\"Utenti\";s:2:\"lt\";s:10:\"Vartotojai\";s:2:\"nl\";s:10:\"Gebruikers\";s:2:\"pl\";s:12:\"Użytkownicy\";s:2:\"ru\";s:24:\"Пользователи\";s:2:\"sl\";s:10:\"Uporabniki\";s:2:\"zh\";s:6:\"用戶\";s:2:\"hu\";s:14:\"Felhasználók\";s:2:\"th\";s:27:\"ผู้ใช้งาน\";s:2:\"se\";s:10:\"Användare\";}','users','0.9',NULL,'a:23:{s:2:\"en\";s:81:\"Let users register and log in to the site, and manage them via the control panel.\";s:2:\"ar\";s:133:\"تمكين المستخدمين من التسجيل والدخول إلى الموقع، وإدارتهم من لوحة التحكم.\";s:2:\"br\";s:125:\"Permite com que usuários se registrem e entrem no site e também que eles sejam gerenciáveis apartir do painel de controle.\";s:2:\"pt\";s:125:\"Permite com que os utilizadores se registem e entrem no site e também que eles sejam geriveis apartir do painel de controlo.\";s:2:\"cs\";s:103:\"Umožňuje uživatelům se registrovat a přihlašovat a zároveň jejich správu v Kontrolním panelu.\";s:2:\"da\";s:89:\"Lader brugere registrere sig og logge ind på sitet, og håndtér dem via kontrolpanelet.\";s:2:\"de\";s:108:\"Erlaube Benutzern das Registrieren und Einloggen auf der Seite und verwalte sie über die Admin-Oberfläche.\";s:2:\"el\";s:208:\"Παρέχει λειτουργίες εγγραφής και σύνδεσης στους επισκέπτες. Επίσης από εδώ γίνεται η διαχείριση των λογαριασμών.\";s:2:\"es\";s:138:\"Permite el registro de nuevos usuarios quienes podrán loguearse en el sitio. Estos podrán controlarse desde el panel de administración.\";s:2:\"fi\";s:126:\"Antaa käyttäjien rekisteröityä ja kirjautua sisään sivustolle sekä mahdollistaa niiden muokkaamisen hallintapaneelista.\";s:2:\"fr\";s:112:\"Permet aux utilisateurs de s\'enregistrer et de se connecter au site et de les gérer via le panneau de contrôle\";s:2:\"he\";s:62:\"ניהול משתמשים: רישום, הפעלה ומחיקה\";s:2:\"id\";s:102:\"Memungkinkan pengguna untuk mendaftar dan masuk ke dalam situs, dan mengaturnya melalui control panel.\";s:2:\"it\";s:95:\"Fai iscrivere de entrare nel sito gli utenti, e gestiscili attraverso il pannello di controllo.\";s:2:\"lt\";s:106:\"Leidžia vartotojams registruotis ir prisijungti prie puslapio, ir valdyti juos per administravimo panele.\";s:2:\"nl\";s:88:\"Laat gebruikers registreren en inloggen op de site, en beheer ze via het controlepaneel.\";s:2:\"pl\";s:87:\"Pozwól użytkownikom na logowanie się na stronie i zarządzaj nimi za pomocą panelu.\";s:2:\"ru\";s:155:\"Управление зарегистрированными пользователями, активирование новых пользователей.\";s:2:\"sl\";s:96:\"Dovoli uporabnikom za registracijo in prijavo na strani, urejanje le teh preko nadzorne plošče\";s:2:\"zh\";s:87:\"讓用戶可以註冊並登入網站，並且管理者可在控制台內進行管理。\";s:2:\"th\";s:210:\"ให้ผู้ใช้ลงทะเบียนและเข้าสู่เว็บไซต์และจัดการกับพวกเขาผ่านทางแผงควบคุม\";s:2:\"hu\";s:120:\"Hogy a felhasználók tudjanak az oldalra regisztrálni és belépni, valamint lehessen őket kezelni a vezérlőpulton.\";s:2:\"se\";s:111:\"Låt dina besökare registrera sig och logga in på webbplatsen. Hantera sedan användarna via kontrollpanelen.\";}',0,0,1,'0',1,1,1,1358181248),(19,'a:23:{s:2:\"en\";s:9:\"Variables\";s:2:\"ar\";s:20:\"المتغيّرات\";s:2:\"br\";s:10:\"Variáveis\";s:2:\"pt\";s:10:\"Variáveis\";s:2:\"cs\";s:10:\"Proměnné\";s:2:\"da\";s:8:\"Variable\";s:2:\"de\";s:9:\"Variablen\";s:2:\"el\";s:20:\"Μεταβλητές\";s:2:\"es\";s:9:\"Variables\";s:2:\"fi\";s:9:\"Muuttujat\";s:2:\"fr\";s:9:\"Variables\";s:2:\"he\";s:12:\"משתנים\";s:2:\"id\";s:8:\"Variabel\";s:2:\"it\";s:9:\"Variabili\";s:2:\"lt\";s:10:\"Kintamieji\";s:2:\"nl\";s:10:\"Variabelen\";s:2:\"pl\";s:7:\"Zmienne\";s:2:\"ru\";s:20:\"Переменные\";s:2:\"sl\";s:13:\"Spremenljivke\";s:2:\"zh\";s:12:\"系統變數\";s:2:\"hu\";s:10:\"Változók\";s:2:\"th\";s:18:\"ตัวแปร\";s:2:\"se\";s:9:\"Variabler\";}','variables','0.4',NULL,'a:23:{s:2:\"en\";s:59:\"Manage global variables that can be accessed from anywhere.\";s:2:\"ar\";s:97:\"إدارة المُتغيّرات العامة لاستخدامها في أرجاء الموقع.\";s:2:\"br\";s:61:\"Gerencia as variáveis globais acessíveis de qualquer lugar.\";s:2:\"pt\";s:58:\"Gerir as variáveis globais acessíveis de qualquer lugar.\";s:2:\"cs\";s:56:\"Spravujte globální proměnné přístupné odkudkoliv.\";s:2:\"da\";s:51:\"Håndtér globale variable som kan tilgås overalt.\";s:2:\"de\";s:74:\"Verwaltet globale Variablen, auf die von überall zugegriffen werden kann.\";s:2:\"el\";s:129:\"Διαχείριση μεταβλητών που είναι προσβάσιμες από παντού στον ιστότοπο.\";s:2:\"es\";s:50:\"Manage global variables to access from everywhere.\";s:2:\"fi\";s:66:\"Hallitse globaali muuttujia, joihin pääsee käsiksi mistä vain.\";s:2:\"fr\";s:50:\"Manage global variables to access from everywhere.\";s:2:\"he\";s:96:\"ניהול משתנים גלובליים אשר ניתנים להמרה בכל חלקי האתר\";s:2:\"id\";s:59:\"Mengatur variabel global yang dapat diakses dari mana saja.\";s:2:\"it\";s:58:\"Gestisci le variabili globali per accedervi da ogni parte.\";s:2:\"lt\";s:64:\"Globalių kintamujų tvarkymas kurie yra pasiekiami iš bet kur.\";s:2:\"nl\";s:54:\"Beheer globale variabelen die overal beschikbaar zijn.\";s:2:\"pl\";s:86:\"Zarządzaj globalnymi zmiennymi do których masz dostęp z każdego miejsca aplikacji.\";s:2:\"ru\";s:136:\"Управление глобальными переменными, которые доступны в любом месте сайта.\";s:2:\"sl\";s:53:\"Urejanje globalnih spremenljivk za dostop od kjerkoli\";s:2:\"th\";s:148:\"จัดการตัวแปรทั่วไปโดยที่สามารถเข้าถึงได้จากทุกที่.\";s:2:\"zh\";s:45:\"管理此網站內可存取的全局變數。\";s:2:\"hu\";s:62:\"Globális változók kezelése a hozzáféréshez, bárhonnan.\";s:2:\"se\";s:66:\"Hantera globala variabler som kan avändas över hela webbplatsen.\";}',0,0,1,'content',1,1,1,1358181248),(20,'a:20:{s:2:\"en\";s:7:\"Widgets\";s:2:\"ar\";s:12:\"الودجت\";s:2:\"br\";s:7:\"Widgets\";s:2:\"pt\";s:7:\"Widgets\";s:2:\"cs\";s:7:\"Widgety\";s:2:\"de\";s:7:\"Widgets\";s:2:\"el\";s:7:\"Widgets\";s:2:\"es\";s:7:\"Widgets\";s:2:\"fi\";s:8:\"Widgetit\";s:2:\"fr\";s:7:\"Widgets\";s:2:\"id\";s:6:\"Widget\";s:2:\"it\";s:7:\"Widgets\";s:2:\"lt\";s:11:\"Papildiniai\";s:2:\"nl\";s:7:\"Widgets\";s:2:\"ru\";s:14:\"Виджеты\";s:2:\"sl\";s:9:\"Vtičniki\";s:2:\"zh\";s:9:\"小組件\";s:2:\"hu\";s:9:\"Widget-ek\";s:2:\"th\";s:21:\"วิดเจ็ต\";s:2:\"se\";s:8:\"Widgetar\";}','widgets','1.1',NULL,'a:21:{s:2:\"en\";s:69:\"Manage small sections of self-contained logic in blocks or \"Widgets\".\";s:2:\"ar\";s:138:\"إدارة أقسام صغيرة من البرمجيات في مساحات الموقع أو ما يُسمّى بالـ\"وِدْجِتْ\".\";s:2:\"br\";s:77:\"Gerenciar pequenas seções de conteúdos em bloco conhecidos como \"Widgets\".\";s:2:\"pt\";s:74:\"Gerir pequenas secções de conteúdos em bloco conhecidos como \"Widgets\".\";s:2:\"cs\";s:56:\"Spravujte malé funkční části webu neboli \"Widgety\".\";s:2:\"da\";s:74:\"Håndter små sektioner af selv-opretholdt logik i blokke eller \"Widgets\".\";s:2:\"de\";s:62:\"Verwaltet kleine, eigentständige Bereiche, genannt \"Widgets\".\";s:2:\"el\";s:149:\"Διαχείριση μικρών τμημάτων αυτόνομης προγραμματιστικής λογικής σε πεδία ή \"Widgets\".\";s:2:\"es\";s:75:\"Manejar pequeñas secciones de lógica autocontenida en bloques o \"Widgets\"\";s:2:\"fi\";s:83:\"Hallitse pieniä osioita, jotka sisältävät erillisiä lohkoja tai \"Widgettejä\".\";s:2:\"fr\";s:41:\"Gérer des mini application ou \"Widgets\".\";s:2:\"id\";s:101:\"Mengatur bagian-bagian kecil dari blok-blok yang memuat sesuatu atau dikenal dengan istilah \"Widget\".\";s:2:\"it\";s:70:\"Gestisci piccole sezioni di logica a se stante in blocchi o \"Widgets\".\";s:2:\"lt\";s:43:\"Nedidelių, savarankiškų blokų valdymas.\";s:2:\"nl\";s:75:\"Beheer kleine onderdelen die zelfstandige logica bevatten, ofwel \"Widgets\".\";s:2:\"ru\";s:91:\"Управление небольшими, самостоятельными блоками.\";s:2:\"sl\";s:61:\"Urejanje manjših delov blokov strani ti. Vtičniki (Widgets)\";s:2:\"zh\";s:103:\"可將小段的程式碼透過小組件來管理。即所謂 \"Widgets\"，或稱為小工具、部件。\";s:2:\"hu\";s:56:\"Önálló kis logikai tömbök vagy widget-ek kezelése.\";s:2:\"th\";s:152:\"จัดการส่วนเล็ก ๆ ในรูปแบบของตัวเองในบล็อกหรือวิดเจ็ต\";s:2:\"se\";s:83:\"Hantera små sektioner med egen logik och innehåll på olika delar av webbplatsen.\";}',1,0,1,'content',1,1,1,1358181248),(21,'a:3:{s:2:\"en\";s:7:\"WYSIWYG\";s:2:\"pt\";s:7:\"WYSIWYG\";s:2:\"se\";s:15:\"HTML-redigerare\";}','wysiwyg','1.0',NULL,'a:4:{s:2:\"en\";s:60:\"Provides the WYSIWYG editor for PyroCMS powered by CKEditor.\";s:2:\"pt\";s:61:\"Fornece o editor WYSIWYG para o PyroCMS, powered by CKEditor.\";s:2:\"el\";s:113:\"Παρέχει τον επεξεργαστή WYSIWYG για το PyroCMS, χρησιμοποιεί το CKEDitor.\";s:2:\"se\";s:37:\"Redigeringsmodul för HTML, CKEditor.\";}',0,0,0,'0',1,1,1,1358181248),(22,'a:1:{s:2:\"es\";s:10:\"Hola Mundo\";}','helloworld','1.0',NULL,'a:1:{s:2:\"es\";s:30:\"Este es el módulo Hola Mundo.\";}',0,0,1,'tablas',1,1,1,1359137586),(24,'a:1:{s:2:\"en\";s:6:\"Sample\";}','sample','2.1',NULL,'a:1:{s:2:\"en\";s:32:\"This is a PyroCMS module sample.\";}',0,1,1,'content',0,0,0,1358961667),(25,'a:1:{s:2:\"es\";s:10:\"Tipo video\";}','tipovideo','1.0',NULL,'a:1:{s:2:\"es\";s:30:\"Este es el módulo Tipo video.\";}',0,0,1,'tablas',1,1,0,1358976294),(33,'a:1:{s:2:\"es\";s:7:\"Canales\";}','canal','1.0',NULL,'a:1:{s:2:\"es\";s:23:\"Canales de televisión.\";}',1,0,1,'1',1,1,0,1359416071),(43,'a:1:{s:2:\"es\";s:7:\"Canales\";}','canales','1.0',NULL,'a:1:{s:2:\"es\";s:23:\"Canales de televisión.\";}',1,0,1,'1',1,1,0,1360167455),(44,'a:1:{s:2:\"es\";s:6:\"Videos\";}','videos','1.0',NULL,'a:1:{s:2:\"es\";s:16:\"Carga de videos.\";}',1,0,1,'0',1,1,0,1360185522);

/*Table structure for table `default_navigation_groups` */

DROP TABLE IF EXISTS `default_navigation_groups`;

CREATE TABLE `default_navigation_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `abbrev` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `abbrev` (`abbrev`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `default_navigation_groups` */

insert  into `default_navigation_groups`(`id`,`title`,`abbrev`) values (1,'Header','header'),(2,'Sidebar','sidebar'),(3,'Footer','footer');

/*Table structure for table `default_navigation_links` */

DROP TABLE IF EXISTS `default_navigation_links`;

CREATE TABLE `default_navigation_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `parent` int(11) DEFAULT NULL,
  `link_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'uri',
  `page_id` int(11) DEFAULT NULL,
  `module_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `uri` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `navigation_group_id` int(5) NOT NULL DEFAULT '0',
  `position` int(5) NOT NULL DEFAULT '0',
  `target` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `restricted_to` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `class` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `navigation_group_id` (`navigation_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `default_navigation_links` */

insert  into `default_navigation_links`(`id`,`title`,`parent`,`link_type`,`page_id`,`module_name`,`url`,`uri`,`navigation_group_id`,`position`,`target`,`restricted_to`,`class`) values (1,'Home',NULL,'page',1,'','','',1,1,NULL,NULL,''),(2,'Blog',NULL,'module',NULL,'blog','','',1,2,NULL,NULL,''),(3,'Contact',NULL,'page',3,'','','',1,3,NULL,NULL,'');

/*Table structure for table `default_page_chunks` */

DROP TABLE IF EXISTS `default_page_chunks`;

CREATE TABLE `default_page_chunks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `class` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `page_id` int(11) NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `parsed` text COLLATE utf8_unicode_ci,
  `type` set('html','markdown','wysiwyg-advanced','wysiwyg-simple') COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `default_page_chunks` */

insert  into `default_page_chunks`(`id`,`slug`,`class`,`page_id`,`body`,`parsed`,`type`,`sort`) values (1,'default','',1,'<p>Welcome to our homepage. We have not quite finished setting up our website yet, but please add us to your bookmarks and come back soon.</p>','','wysiwyg-advanced',1),(2,'default','',2,'<p>We cannot find the page you are looking for, please click <a title=\"Home\" href=\"{{ pages:url id=\'1\' }}\">here</a> to go to the homepage.</p>','','html',1),(3,'default','',3,'<p>To contact us please fill out the form below.</p>\n					{{ contact:form name=\"text|required\" email=\"text|required|valid_email\" subject=\"dropdown|Support|Sales|Feedback|Other\" message=\"textarea\" attachment=\"file|zip\" }}\n						<div><label for=\"name\">Name:</label>{{ name }}</div>\n						<div><label for=\"email\">Email:</label>{{ email }}</div>\n						<div><label for=\"subject\">Subject:</label>{{ subject }}</div>\n						<div><label for=\"message\">Message:</label>{{ message }}</div>\n						<div><label for=\"attachment\">Attach  a zip file:</label>{{ attachment }}</div>\n					{{ /contact:form }}','','html',1);

/*Table structure for table `default_page_layouts` */

DROP TABLE IF EXISTS `default_page_layouts`;

CREATE TABLE `default_page_layouts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `css` text COLLATE utf8_unicode_ci,
  `js` text COLLATE utf8_unicode_ci,
  `theme_layout` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default',
  `updated_on` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `default_page_layouts` */

insert  into `default_page_layouts`(`id`,`title`,`body`,`css`,`js`,`theme_layout`,`updated_on`) values (1,'Default','<h2>{{ page:title }}</h2>\n{{ page:body }}','','','default',1358180064);

/*Table structure for table `default_pages` */

DROP TABLE IF EXISTS `default_pages`;

CREATE TABLE `default_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `class` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `uri` text COLLATE utf8_unicode_ci,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `revision_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `layout_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `css` text COLLATE utf8_unicode_ci,
  `js` text COLLATE utf8_unicode_ci,
  `meta_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_keywords` char(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8_unicode_ci,
  `rss_enabled` int(1) NOT NULL DEFAULT '0',
  `comments_enabled` int(1) NOT NULL DEFAULT '0',
  `status` enum('draft','live') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft',
  `created_on` int(11) NOT NULL DEFAULT '0',
  `updated_on` int(11) NOT NULL DEFAULT '0',
  `restricted_to` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_home` int(1) NOT NULL DEFAULT '0',
  `strict_uri` tinyint(1) NOT NULL DEFAULT '1',
  `order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `slug` (`slug`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `default_pages` */

insert  into `default_pages`(`id`,`slug`,`class`,`title`,`uri`,`parent_id`,`revision_id`,`layout_id`,`css`,`js`,`meta_title`,`meta_keywords`,`meta_description`,`rss_enabled`,`comments_enabled`,`status`,`created_on`,`updated_on`,`restricted_to`,`is_home`,`strict_uri`,`order`) values (1,'home','','Home','home',0,'1','1',NULL,NULL,NULL,NULL,NULL,0,0,'live',1358180065,0,'',1,1,0),(2,'404','','Page missing','404',0,'1','1',NULL,NULL,NULL,NULL,NULL,0,0,'live',1358180065,0,'',0,1,1),(3,'contact','','Contact','contact',0,'1','1',NULL,NULL,NULL,NULL,NULL,0,0,'live',1358180065,0,'',0,1,2);

/*Table structure for table `default_permissions` */

DROP TABLE IF EXISTS `default_permissions`;

CREATE TABLE `default_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `module` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `roles` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `default_permissions` */

insert  into `default_permissions`(`id`,`group_id`,`module`,`roles`) values (6,3,'blog','{\"put_live\":\"1\",\"edit_live\":\"1\",\"delete_live\":\"1\"}'),(7,3,'keywords',NULL),(8,3,'users','{\"admin_profile_fields\":\"1\"}'),(31,2,'pages',NULL),(32,2,'users',NULL),(33,1,'files','{\"wysiwyg\":\"1\",\"upload\":\"1\",\"download_file\":\"1\",\"edit_file\":\"1\",\"delete_file\":\"1\",\"create_folder\":\"1\",\"set_location\":\"1\",\"synchronize\":\"1\",\"edit_folder\":\"1\",\"delete_folder\":\"1\"}'),(34,1,'blog','{\"put_live\":\"1\",\"edit_live\":\"1\",\"delete_live\":\"1\"}'),(35,1,'canales','{\"put_live\":\"1\",\"edit_live\":\"1\",\"delete_live\":\"1\"}'),(36,1,'comments',NULL),(37,1,'settings',NULL),(38,1,'groups',NULL),(39,1,'helloworld',NULL),(40,1,'keywords',NULL),(41,1,'maintenance',NULL),(42,1,'modules',NULL),(43,1,'navigation',NULL),(44,1,'permissions',NULL),(45,1,'templates',NULL),(46,1,'pages','{\"put_live\":\"1\",\"edit_live\":\"1\",\"delete_live\":\"1\"}'),(47,1,'redirects',NULL),(48,1,'themes',NULL),(49,1,'tipovideo',NULL),(50,1,'users','{\"admin_profile_fields\":\"1\"}'),(51,1,'variables',NULL),(52,1,'widgets',NULL),(55,4,'canales',NULL),(56,4,'videos',NULL);

/*Table structure for table `default_profiles` */

DROP TABLE IF EXISTS `default_profiles`;

CREATE TABLE `default_profiles` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `ordering_count` int(11) DEFAULT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `display_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `company` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  `bio` text COLLATE utf8_unicode_ci,
  `dob` int(11) DEFAULT NULL,
  `gender` set('m','f','') COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_line1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_line2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_line3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postcode` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated_on` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `default_profiles` */

insert  into `default_profiles`(`id`,`created`,`updated`,`created_by`,`ordering_count`,`user_id`,`display_name`,`first_name`,`last_name`,`company`,`lang`,`bio`,`dob`,`gender`,`phone`,`mobile`,`address_line1`,`address_line2`,`address_line3`,`postcode`,`website`,`updated_on`) values (1,NULL,NULL,NULL,NULL,1,'Gabriela Lopez','Gabriela','Lopez',NULL,'en',NULL,0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1358964564),(2,'2013-01-18 23:08:59',NULL,1,1,2,'Cristian','Cristian','Suazo',NULL,'es',NULL,0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1359060909),(3,'2013-01-18 23:11:19',NULL,1,2,3,'Jessenia','Jessenia','Zambrano',NULL,'es',NULL,0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1359060920),(4,'2013-01-24 20:47:57',NULL,1,3,4,'Carlos De Paz','Carlos','De Paz',NULL,'es',NULL,0,'m',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);

/*Table structure for table `default_redirects` */

DROP TABLE IF EXISTS `default_redirects`;

CREATE TABLE `default_redirects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `to` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(3) NOT NULL DEFAULT '302',
  PRIMARY KEY (`id`),
  KEY `from` (`from`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `default_redirects` */

/*Table structure for table `default_settings` */

DROP TABLE IF EXISTS `default_settings`;

CREATE TABLE `default_settings` (
  `slug` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `type` set('text','textarea','password','select','select-multiple','radio','checkbox') COLLATE utf8_unicode_ci NOT NULL,
  `default` text COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `options` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_required` int(1) NOT NULL,
  `is_gui` int(1) NOT NULL,
  `module` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `order` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`slug`),
  UNIQUE KEY `unique_slug` (`slug`),
  KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `default_settings` */

insert  into `default_settings`(`slug`,`title`,`description`,`type`,`default`,`value`,`options`,`is_required`,`is_gui`,`module`,`order`) values ('activation_email','Activation Email','Send out an e-mail with an activation link when a user signs up. Disable this so that admins must manually activate each account.','radio','1','','1=Enabled|0=Disabled',0,1,'users',961),('addons_upload','Addons Upload Permissions','Keeps mere admins from uploading addons by default','text','0','1','',1,0,'',0),('admin_force_https','Force HTTPS for Control Panel?','Allow only the HTTPS protocol when using the Control Panel?','radio','0','','1=Yes|0=No',1,1,'',0),('admin_theme','Control Panel Theme','Select the theme for the control panel.','','','pyrocms','func:get_themes',1,0,'',0),('akismet_api_key','Akismet API Key','Akismet is a spam-blocker from the WordPress team. It keeps spam under control without forcing users to get past human-checking CAPTCHA forms.','text','','','',0,1,'integration',981),('api_enabled','API Enabled','Allow API access to all modules which have an API controller.','select','0','0','0=Disabled|1=Enabled',0,0,'api',0),('api_user_keys','API User Keys','Allow users to sign up for API keys (if the API is Enabled).','select','0','0','0=Disabled|1=Enabled',0,0,'api',0),('auto_username','Auto Username','Create the username automatically, meaning users can skip making one on registration.','radio','1','','1=Enabled|0=Disabled',0,1,'users',964),('cdn_domain','CDN Domain','CDN domains allow you to offload static content to various edge servers, like Amazon CloudFront or MaxCDN.','text','','','',0,1,'integration',1000),('ckeditor_config','CKEditor Config','You can find a list of valid configuration items in <a target=\"_blank\" href=\"http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.config.html\">CKEditor\'s documentation.</a>','textarea','','{{# this is a wysiwyg-simple editor customized for the blog module (it allows images to be inserted) #}}\n$(\'textarea.blog.wysiwyg-simple\').ckeditor({\n	toolbar: [\n		[\'pyroimages\'],\n		[\'Bold\', \'Italic\', \'-\', \'NumberedList\', \'BulletedList\', \'-\', \'Link\', \'Unlink\']\n	  ],\n	extraPlugins: \'pyroimages\',\n	width: \'99%\',\n	height: 100,\n	dialog_backgroundCoverColor: \'#000\',\n	defaultLanguage: \'{{ helper:config item=\"default_language\" }}\',\n	language: \'{{ global:current_language }}\'\n});\n\n{{# this is the config for all wysiwyg-simple textareas #}}\n$(\'textarea.wysiwyg-simple\').ckeditor({\n	toolbar: [\n		[\'Bold\', \'Italic\', \'-\', \'NumberedList\', \'BulletedList\', \'-\', \'Link\', \'Unlink\']\n	  ],\n	width: \'99%\',\n	height: 100,\n	dialog_backgroundCoverColor: \'#000\',\n	defaultLanguage: \'{{ helper:config item=\"default_language\" }}\',\n	language: \'{{ global:current_language }}\'\n});\n\n{{# and this is the advanced editor #}}\n$(\'textarea.wysiwyg-advanced\').ckeditor({\n	toolbar: [\n		[\'Maximize\'],\n		[\'pyroimages\', \'pyrofiles\'],\n		[\'Cut\',\'Copy\',\'Paste\',\'PasteFromWord\'],\n		[\'Undo\',\'Redo\',\'-\',\'Find\',\'Replace\'],\n		[\'Link\',\'Unlink\'],\n		[\'Table\',\'HorizontalRule\',\'SpecialChar\'],\n		[\'Bold\',\'Italic\',\'StrikeThrough\'],\n		[\'JustifyLeft\',\'JustifyCenter\',\'JustifyRight\',\'JustifyBlock\',\'-\',\'BidiLtr\',\'BidiRtl\'],\n		[\'Format\', \'FontSize\', \'Subscript\',\'Superscript\', \'NumberedList\',\'BulletedList\',\'Outdent\',\'Indent\',\'Blockquote\'],\n		[\'ShowBlocks\', \'RemoveFormat\', \'Source\']\n	],\n	extraPlugins: \'pyroimages,pyrofiles\',\n	width: \'99%\',\n	height: 400,\n	dialog_backgroundCoverColor: \'#000\',\n	removePlugins: \'elementspath\',\n	defaultLanguage: \'{{ helper:config item=\"default_language\" }}\',\n	language: \'{{ global:current_language }}\'\n});','',1,1,'wysiwyg',993),('comment_markdown','Allow Markdown','Do you want to allow visitors to post comments using Markdown?','select','0','0','0=Text Only|1=Allow Markdown',1,1,'comments',965),('comment_order','Comment Order','Sort order in which to display comments.','select','ASC','ASC','ASC=Oldest First|DESC=Newest First',1,1,'comments',966),('contact_email','Contact E-mail','All e-mails from users, guests and the site will go to this e-mail address.','text','glopez@idigital.pe','','',1,1,'email',979),('currency','Currency','The currency symbol for use on products, services, etc.','text','&pound;','','',1,1,'',994),('dashboard_rss','Dashboard RSS Feed','Link to an RSS feed that will be displayed on the dashboard.','text','https://www.pyrocms.com/blog/rss/all.rss','','',0,1,'',990),('dashboard_rss_count','Dashboard RSS Items','How many RSS items would you like to display on the dashboard?','text','5','5','',1,1,'',989),('date_format','Date Format','How should dates be displayed across the website and control panel? Using the <a target=\"_blank\" href=\"http://php.net/manual/en/function.date.php\">date format</a> from PHP - OR - Using the format of <a target=\"_blank\" href=\"http://php.net/manual/en/function.strftime.php\">strings formatted as date</a> from PHP.','text','Y-m-d','','',1,1,'',995),('default_theme','Default Theme','Select the theme you want users to see by default.','','default','default','func:get_themes',1,0,'',0),('enable_comments','Enable Comments','Enable comments.','radio','1','1','1=Enabled|0=Disabled',1,1,'comments',968),('enable_profiles','Enable profiles','Allow users to add and edit profiles.','radio','1','','1=Enabled|0=Disabled',1,1,'users',963),('enable_registration','Enable user registration','Allow users to register in your site.','radio','1','','1=Enabled|0=Disabled',0,1,'users',961),('files_cache','Files Cache','When outputting an image via site.com/files what shall we set the cache expiration for?','select','480','480','0=no-cache|1=1-minute|60=1-hour|180=3-hour|480=8-hour|1440=1-day|43200=30-days',1,1,'files',986),('files_cf_api_key','Rackspace Cloud Files API Key','You also must provide your Cloud Files API Key. You will find it at the same location as your Username in your Rackspace account.','text','','','',0,1,'files',989),('files_cf_username','Rackspace Cloud Files Username','To enable cloud file storage in your Rackspace Cloud Files account please enter your Cloud Files Username. <a href=\"https://manage.rackspacecloud.com/APIAccess.do\">Find your credentials</a>','text','','','',0,1,'files',990),('files_enabled_providers','Enabled File Storage Providers','Which file storage providers do you want to enable? (If you enable a cloud provider you must provide valid auth keys below','checkbox','0','0','amazon-s3=Amazon S3|rackspace-cf=Rackspace Cloud Files',0,1,'files',994),('files_s3_access_key','Amazon S3 Access Key','To enable cloud file storage in your Amazon S3 account provide your Access Key. <a href=\"https://aws-portal.amazon.com/gp/aws/securityCredentials#access_credentials\">Find your credentials</a>','text','','','',0,1,'files',993),('files_s3_geographic_location','Amazon S3 Geographic Location','Either US or EU. If you change this you must also change the S3 URL.','radio','US','US','US=United States|EU=Europe',1,1,'files',991),('files_s3_secret_key','Amazon S3 Secret Key','You also must provide your Amazon S3 Secret Key. You will find it at the same location as your Access Key in your Amazon account.','text','','','',0,1,'files',992),('files_s3_url','Amazon S3 URL','Change this if using one of Amazon\'s EU locations or a custom domain.','text','http://{{ bucket }}.s3.amazonaws.com/','http://{{ bucket }}.s3.amazonaws.com/','',0,1,'files',991),('files_upload_limit','Filesize Limit','Maximum filesize to allow when uploading. Specify the size in MB. Example: 5','text','5','5','',1,1,'files',987),('frontend_enabled','Site Status','Use this option to the user-facing part of the site on or off. Useful when you want to take the site down for maintenance.','radio','1','','1=Open|0=Closed',1,1,'',988),('ga_email','Google Analytic E-mail','E-mail address used for Google Analytics, we need this to show the graph on the dashboard.','text','','','',0,1,'integration',983),('ga_password','Google Analytic Password','This is also needed this to show the graph on the dashboard.','password','','','',0,1,'integration',982),('ga_profile','Google Analytic Profile ID','Profile ID for this website in Google Analytics','text','','','',0,1,'integration',984),('ga_tracking','Google Tracking Code','Enter your Google Analytic Tracking Code to activate Google Analytics view data capturing. E.g: UA-19483569-6','text','','','',0,1,'integration',985),('mail_protocol','Mail Protocol','Select desired email protocol.','select','mail','mail','mail=Mail|sendmail=Sendmail|smtp=SMTP',1,1,'email',977),('mail_sendmail_path','Sendmail Path','Path to server sendmail binary.','text','','','',0,1,'email',972),('mail_smtp_host','SMTP Host Name','The host name of your smtp server.','text','','','',0,1,'email',976),('mail_smtp_pass','SMTP password','SMTP password.','password','','','',0,1,'email',975),('mail_smtp_port','SMTP Port','SMTP port number.','text','','','',0,1,'email',974),('mail_smtp_user','SMTP User Name','SMTP user name.','text','','','',0,1,'email',973),('meta_topic','Meta Topic','Two or three words describing this type of company/website.','text','Content Management','Add your slogan here','',0,1,'',998),('moderate_comments','Moderate Comments','Force comments to be approved before they appear on the site.','radio','1','1','1=Enabled|0=Disabled',1,1,'comments',967),('records_per_page','Records Per Page','How many records should we show per page in the admin section?','select','25','','10=10|25=25|50=50|100=100',1,1,'',992),('registered_email','User Registered Email','Send a notification email to the contact e-mail when someone registers.','radio','1','','1=Enabled|0=Disabled',0,1,'users',962),('require_lastname','Require last names?','For some situations, a last name may not be required. Do you want to force users to enter one or not?','radio','1','','1=Required|0=Optional',1,1,'users',962),('rss_feed_items','Feed item count','How many items should we show in RSS/blog feeds?','select','25','','10=10|25=25|50=50|100=100',1,1,'',991),('server_email','Server E-mail','All e-mails to users will come from this e-mail address.','text','admin@localhost','','',1,1,'email',978),('site_lang','Site Language','The native language of the website, used to choose templates of e-mail notifications, contact form, and other features that should not depend on the language of a user.','select','en','es','func:get_supported_lang',1,1,'',997),('site_name','Site Name','The name of the website for page titles and for use around the site.','text','Un-named Website','Admin - Mi Canal','',1,1,'',1000),('site_public_lang','Public Languages','Which are the languages really supported and offered on the front-end of your website?','checkbox','en','en,es','func:get_supported_lang',1,1,'',996),('site_slogan','Site Slogan','The slogan of the website for page titles and for use around the site','text','','Sí se puede!','',0,1,'',999),('twitter_cache','Cache time','How many minutes should your Tweets be stored?','text','300','','',0,1,'twitter',969),('twitter_feed_count','Feed Count','How many tweets should be returned to the Twitter feed block?','text','5','','',0,1,'twitter',970),('twitter_username','Username','Twitter username.','text','','','',0,1,'twitter',971),('unavailable_message','Unavailable Message','When the site is turned off or there is a major problem, this message will show to users.','textarea','Sorry, this website is currently unavailable.','','',0,1,'',987);

/*Table structure for table `default_theme_options` */

DROP TABLE IF EXISTS `default_theme_options`;

CREATE TABLE `default_theme_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `type` set('text','textarea','password','select','select-multiple','radio','checkbox') COLLATE utf8_unicode_ci NOT NULL,
  `default` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `options` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_required` int(1) NOT NULL,
  `theme` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `default_theme_options` */

insert  into `default_theme_options`(`id`,`slug`,`title`,`description`,`type`,`default`,`value`,`options`,`is_required`,`theme`) values (1,'pyrocms_recent_comments','Recent Comments','Would you like to display recent comments on the dashboard?','radio','yes','yes','yes=Yes|no=No',1,'pyrocms'),(2,'pyrocms_news_feed','News Feed','Would you like to display the news feed on the dashboard?','radio','yes','yes','yes=Yes|no=No',1,'pyrocms'),(3,'pyrocms_quick_links','Quick Links','Would you like to display quick links on the dashboard?','radio','yes','yes','yes=Yes|no=No',1,'pyrocms'),(4,'pyrocms_analytics_graph','Analytics Graph','Would you like to display the graph on the dashboard?','radio','yes','yes','yes=Yes|no=No',1,'pyrocms'),(8,'background','Background','Choose the default background for the theme.','select','fabric','fabric','black=Black|fabric=Fabric|graph=Graph|leather=Leather|noise=Noise|texture=Texture',1,'base'),(9,'slider','Slider','Would you like to display the slider on the homepage?','radio','yes','yes','yes=Yes|no=No',1,'base'),(10,'color','Default Theme Color','This changes things like background color, link colors etc…','select','pink','pink','red=Red|orange=Orange|yellow=Yellow|green=Green|blue=Blue|pink=Pink',1,'base'),(11,'show_breadcrumbs','Do you want to show breadcrumbs?','If selected it shows a string of breadcrumbs at the top of the page.','radio','yes','yes','yes=Yes|no=No',1,'base'),(12,'show_breadcrumbs','Show Breadcrumbs','Would you like to display breadcrumbs?','radio','yes','yes','yes=Yes|no=No',1,'default'),(13,'layout','Layout','Which type of layout shall we use?','select','2 column','2 column','2 column=Two Column|full-width=Full Width|full-width-home=Full Width Home Page',1,'default'),(14,'cufon_enabled','Use Cufon','Would you like to use Cufon for titles?','radio','yes','yes','yes=Yes|no=No',1,'default');

/*Table structure for table `default_users` */

DROP TABLE IF EXISTS `default_users`;

CREATE TABLE `default_users` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `salt` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `group_id` int(11) DEFAULT NULL,
  `ip_address` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` int(1) DEFAULT NULL,
  `activation_code` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_on` int(11) NOT NULL,
  `last_login` int(11) NOT NULL,
  `username` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `forgotten_password_code` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_code` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Registered User Information';

/*Data for the table `default_users` */

insert  into `default_users`(`id`,`email`,`password`,`salt`,`group_id`,`ip_address`,`active`,`activation_code`,`created_on`,`last_login`,`username`,`forgotten_password_code`,`remember_code`) values (1,'glopez@idigital.pe','af3db9ac2af64d73f892e481170f88d0d4e7e385','d1f2b',1,'',1,'',1358180061,1360185129,'admin',NULL,'ce9c6b0d53b3e3380b0837186942ae386728ee4b'),(2,'csuazo@idigital.pe','794c086897ccee7f29c079611a0fcadc37780e90','afc8ab',3,'192.168.1.34',1,NULL,1358550539,1359060941,'cristian',NULL,NULL),(3,'jzambrano@idigital.pe','8561442bbaa114aee9c4f6f9024e65339559dd42','9a9820',4,'192.168.1.34',1,NULL,1358550679,1360189849,'jessenia',NULL,'e098cd0b492072fdd0bd78ad01039f2a497458a7'),(4,'cdepaz@idigital.pe','dc00475fe3449886608277973415af36ec4901fb','8963a3',5,'127.0.0.1',1,NULL,1359060477,1359060477,'cdepaz',NULL,NULL);

/*Table structure for table `default_variables` */

DROP TABLE IF EXISTS `default_variables`;

CREATE TABLE `default_variables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `default_variables` */

/*Table structure for table `default_widget_areas` */

DROP TABLE IF EXISTS `default_widget_areas`;

CREATE TABLE `default_widget_areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `default_widget_areas` */

insert  into `default_widget_areas`(`id`,`slug`,`title`) values (1,'sidebar','Sidebar');

/*Table structure for table `default_widget_instances` */

DROP TABLE IF EXISTS `default_widget_instances`;

CREATE TABLE `default_widget_instances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `widget_id` int(11) DEFAULT NULL,
  `widget_area_id` int(11) DEFAULT NULL,
  `options` text COLLATE utf8_unicode_ci NOT NULL,
  `order` int(10) NOT NULL DEFAULT '0',
  `created_on` int(11) NOT NULL DEFAULT '0',
  `updated_on` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `default_widget_instances` */

/*Table structure for table `default_widgets` */

DROP TABLE IF EXISTS `default_widgets`;

CREATE TABLE `default_widgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `author` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `website` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `version` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `enabled` int(1) NOT NULL DEFAULT '1',
  `order` int(10) NOT NULL DEFAULT '0',
  `updated_on` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `default_widgets` */

insert  into `default_widgets`(`id`,`slug`,`title`,`description`,`author`,`website`,`version`,`enabled`,`order`,`updated_on`) values (1,'google_maps','a:9:{s:2:\"en\";s:11:\"Google Maps\";s:2:\"el\";s:19:\"Χάρτης Google\";s:2:\"nl\";s:11:\"Google Maps\";s:2:\"br\";s:11:\"Google Maps\";s:2:\"pt\";s:11:\"Google Maps\";s:2:\"ru\";s:17:\"Карты Google\";s:2:\"id\";s:11:\"Google Maps\";s:2:\"fi\";s:11:\"Google Maps\";s:2:\"fr\";s:11:\"Google Maps\";}','a:9:{s:2:\"en\";s:32:\"Display Google Maps on your site\";s:2:\"el\";s:78:\"Προβάλετε έναν Χάρτη Google στον ιστότοπό σας\";s:2:\"nl\";s:27:\"Toon Google Maps in uw site\";s:2:\"br\";s:34:\"Mostra mapas do Google no seu site\";s:2:\"pt\";s:34:\"Mostra mapas do Google no seu site\";s:2:\"ru\";s:80:\"Выводит карты Google на страницах вашего сайта\";s:2:\"id\";s:37:\"Menampilkan Google Maps di Situs Anda\";s:2:\"fi\";s:39:\"Näytä Google Maps kartta sivustollasi\";s:2:\"fr\";s:42:\"Publiez un plan Google Maps sur votre site\";}','Gregory Athons','http://www.gregathons.com','1.0',1,1,1358262245),(2,'html','s:4:\"HTML\";','a:9:{s:2:\"en\";s:28:\"Create blocks of custom HTML\";s:2:\"el\";s:80:\"Δημιουργήστε περιοχές με δικό σας κώδικα HTML\";s:2:\"br\";s:41:\"Permite criar blocos de HTML customizados\";s:2:\"pt\";s:41:\"Permite criar blocos de HTML customizados\";s:2:\"nl\";s:30:\"Maak blokken met maatwerk HTML\";s:2:\"ru\";s:83:\"Создание HTML-блоков с произвольным содержимым\";s:2:\"id\";s:24:\"Membuat blok HTML apapun\";s:2:\"fi\";s:32:\"Luo lohkoja omasta HTML koodista\";s:2:\"fr\";s:36:\"Créez des blocs HTML personnalisés\";}','Phil Sturgeon','http://philsturgeon.co.uk/','1.0',1,2,1358262246),(3,'login','a:9:{s:2:\"en\";s:5:\"Login\";s:2:\"el\";s:14:\"Σύνδεση\";s:2:\"nl\";s:5:\"Login\";s:2:\"br\";s:5:\"Login\";s:2:\"pt\";s:5:\"Login\";s:2:\"ru\";s:22:\"Вход на сайт\";s:2:\"id\";s:5:\"Login\";s:2:\"fi\";s:13:\"Kirjautuminen\";s:2:\"fr\";s:9:\"Connexion\";}','a:9:{s:2:\"en\";s:36:\"Display a simple login form anywhere\";s:2:\"el\";s:96:\"Προβάλετε μια απλή φόρμα σύνδεσης χρήστη οπουδήποτε\";s:2:\"br\";s:69:\"Permite colocar um formulário de login em qualquer lugar do seu site\";s:2:\"pt\";s:69:\"Permite colocar um formulário de login em qualquer lugar do seu site\";s:2:\"nl\";s:32:\"Toon overal een simpele loginbox\";s:2:\"ru\";s:72:\"Выводит простую форму для входа на сайт\";s:2:\"id\";s:32:\"Menampilkan form login sederhana\";s:2:\"fi\";s:52:\"Näytä yksinkertainen kirjautumislomake missä vain\";s:2:\"fr\";s:54:\"Affichez un formulaire de connexion où vous souhaitez\";}','Phil Sturgeon','http://philsturgeon.co.uk/','1.0',1,3,1358262246),(4,'navigation','a:9:{s:2:\"en\";s:10:\"Navigation\";s:2:\"el\";s:16:\"Πλοήγηση\";s:2:\"nl\";s:9:\"Navigatie\";s:2:\"br\";s:11:\"Navegação\";s:2:\"pt\";s:11:\"Navegação\";s:2:\"ru\";s:18:\"Навигация\";s:2:\"id\";s:8:\"Navigasi\";s:2:\"fi\";s:10:\"Navigaatio\";s:2:\"fr\";s:10:\"Navigation\";}','a:9:{s:2:\"en\";s:40:\"Display a navigation group with a widget\";s:2:\"el\";s:100:\"Προβάλετε μια ομάδα στοιχείων πλοήγησης στον ιστότοπο\";s:2:\"nl\";s:38:\"Toon een navigatiegroep met een widget\";s:2:\"br\";s:62:\"Exibe um grupo de links de navegação como widget em seu site\";s:2:\"pt\";s:62:\"Exibe um grupo de links de navegação como widget no seu site\";s:2:\"ru\";s:88:\"Отображает навигационную группу внутри виджета\";s:2:\"id\";s:44:\"Menampilkan grup navigasi menggunakan widget\";s:2:\"fi\";s:37:\"Näytä widgetillä navigaatio ryhmä\";s:2:\"fr\";s:47:\"Affichez un groupe de navigation dans un widget\";}','Phil Sturgeon','http://philsturgeon.co.uk/','1.2',1,4,1358262246),(5,'rss_feed','a:9:{s:2:\"en\";s:8:\"RSS Feed\";s:2:\"el\";s:24:\"Τροφοδοσία RSS\";s:2:\"nl\";s:8:\"RSS Feed\";s:2:\"br\";s:8:\"Feed RSS\";s:2:\"pt\";s:8:\"Feed RSS\";s:2:\"ru\";s:31:\"Лента новостей RSS\";s:2:\"id\";s:8:\"RSS Feed\";s:2:\"fi\";s:10:\"RSS Syöte\";s:2:\"fr\";s:8:\"Flux RSS\";}','a:9:{s:2:\"en\";s:41:\"Display parsed RSS feeds on your websites\";s:2:\"el\";s:82:\"Προβάλετε τα περιεχόμενα μιας τροφοδοσίας RSS\";s:2:\"nl\";s:28:\"Toon RSS feeds op uw website\";s:2:\"br\";s:48:\"Interpreta e exibe qualquer feed RSS no seu site\";s:2:\"pt\";s:48:\"Interpreta e exibe qualquer feed RSS no seu site\";s:2:\"ru\";s:94:\"Выводит обработанную ленту новостей на вашем сайте\";s:2:\"id\";s:42:\"Menampilkan kutipan RSS feed di situs Anda\";s:2:\"fi\";s:39:\"Näytä purettu RSS syöte sivustollasi\";s:2:\"fr\";s:39:\"Affichez un flux RSS sur votre site web\";}','Phil Sturgeon','http://philsturgeon.co.uk/','1.2',1,5,1358262246),(6,'social_bookmark','a:9:{s:2:\"en\";s:15:\"Social Bookmark\";s:2:\"el\";s:35:\"Κοινωνική δικτύωση\";s:2:\"nl\";s:19:\"Sociale Bladwijzers\";s:2:\"br\";s:15:\"Social Bookmark\";s:2:\"pt\";s:15:\"Social Bookmark\";s:2:\"ru\";s:37:\"Социальные закладки\";s:2:\"id\";s:15:\"Social Bookmark\";s:2:\"fi\";s:24:\"Sosiaalinen kirjanmerkki\";s:2:\"fr\";s:13:\"Liens sociaux\";}','a:9:{s:2:\"en\";s:47:\"Configurable social bookmark links from AddThis\";s:2:\"el\";s:111:\"Παραμετροποιήσιμα στοιχεία κοινωνικής δικτυώσης από το AddThis\";s:2:\"nl\";s:43:\"Voeg sociale bladwijzers toe vanuit AddThis\";s:2:\"br\";s:87:\"Adiciona links de redes sociais usando o AddThis, podendo fazer algumas configurações\";s:2:\"pt\";s:87:\"Adiciona links de redes sociais usando o AddThis, podendo fazer algumas configurações\";s:2:\"ru\";s:90:\"Конфигурируемые социальные закладки с сайта AddThis\";s:2:\"id\";s:60:\"Tautan social bookmark yang dapat dikonfigurasi dari AddThis\";s:2:\"fi\";s:59:\"Konfiguroitava sosiaalinen kirjanmerkki linkit AddThis:stä\";s:2:\"fr\";s:43:\"Liens sociaux personnalisables avec AddThis\";}','Phil Sturgeon','http://philsturgeon.co.uk/','1.0',1,6,1358262246),(7,'twitter_feed','a:9:{s:2:\"en\";s:12:\"Twitter Feed\";s:2:\"el\";s:14:\"Ροή Twitter\";s:2:\"fr\";s:12:\"Flux Twitter\";s:2:\"nl\";s:11:\"Twitterfeed\";s:2:\"br\";s:15:\"Feed do Twitter\";s:2:\"pt\";s:15:\"Feed do Twitter\";s:2:\"ru\";s:21:\"Лента Twitter\'а\";s:2:\"id\";s:12:\"Twitter Feed\";s:2:\"fi\";s:14:\"Twitter Syöte\";}','a:9:{s:2:\"en\";s:37:\"Display Twitter feeds on your website\";s:2:\"el\";s:69:\"Προβολή των τελευταίων tweets από το Twitter\";s:2:\"fr\";s:49:\"Afficher les flux Twitter sur votre site Internet\";s:2:\"nl\";s:31:\"Toon Twitterfeeds op uw website\";s:2:\"br\";s:64:\"Mostra os últimos tweets de um usuário do Twitter no seu site.\";s:2:\"pt\";s:66:\"Mostra os últimos tweets de um utilizador do Twitter no seu site.\";s:2:\"ru\";s:98:\"Выводит ленту новостей Twitter на страницах вашего сайта\";s:2:\"id\";s:39:\"Menampilkan koleksi Tweet di situs Anda\";s:2:\"fi\";s:35:\"Näytä Twitter syöte sivustollasi\";}','Phil Sturgeon','http://philsturgeon.co.uk/','1.2',1,7,1358262246),(8,'archive','a:7:{s:2:\"en\";s:7:\"Archive\";s:2:\"br\";s:15:\"Arquivo do Blog\";s:2:\"pt\";s:15:\"Arquivo do Blog\";s:2:\"el\";s:33:\"Αρχείο Ιστολογίου\";s:2:\"fr\";s:16:\"Archives du Blog\";s:2:\"ru\";s:10:\"Архив\";s:2:\"id\";s:7:\"Archive\";}','a:7:{s:2:\"en\";s:64:\"Display a list of old months with links to posts in those months\";s:2:\"br\";s:95:\"Mostra uma lista navegação cronológica contendo o índice dos artigos publicados mensalmente\";s:2:\"pt\";s:95:\"Mostra uma lista navegação cronológica contendo o índice dos artigos publicados mensalmente\";s:2:\"el\";s:155:\"Προβάλλει μια λίστα μηνών και συνδέσμους σε αναρτήσεις που έγιναν σε κάθε από αυτούς\";s:2:\"fr\";s:95:\"Permet d\'afficher une liste des mois passés avec des liens vers les posts relatifs à ces mois\";s:2:\"ru\";s:114:\"Выводит список по месяцам со ссылками на записи в этих месяцах\";s:2:\"id\";s:63:\"Menampilkan daftar bulan beserta tautan post di setiap bulannya\";}','Phil Sturgeon','http://philsturgeon.co.uk/','1.0',1,8,1358262246),(9,'blog_categories','a:7:{s:2:\"en\";s:15:\"Blog Categories\";s:2:\"br\";s:18:\"Categorias do Blog\";s:2:\"pt\";s:18:\"Categorias do Blog\";s:2:\"el\";s:41:\"Κατηγορίες Ιστολογίου\";s:2:\"fr\";s:19:\"Catégories du Blog\";s:2:\"ru\";s:29:\"Категории Блога\";s:2:\"id\";s:12:\"Kateori Blog\";}','a:7:{s:2:\"en\";s:30:\"Show a list of blog categories\";s:2:\"br\";s:57:\"Mostra uma lista de navegação com as categorias do Blog\";s:2:\"pt\";s:57:\"Mostra uma lista de navegação com as categorias do Blog\";s:2:\"el\";s:97:\"Προβάλει την λίστα των κατηγοριών του ιστολογίου σας\";s:2:\"fr\";s:49:\"Permet d\'afficher la liste de Catégories du Blog\";s:2:\"ru\";s:57:\"Выводит список категорий блога\";s:2:\"id\";s:35:\"Menampilkan daftar kategori tulisan\";}','Stephen Cozart','http://github.com/clip/','1.0',1,9,1358262246),(10,'latest_posts','a:7:{s:2:\"en\";s:12:\"Latest posts\";s:2:\"br\";s:24:\"Artigos recentes do Blog\";s:2:\"pt\";s:24:\"Artigos recentes do Blog\";s:2:\"el\";s:62:\"Τελευταίες αναρτήσεις ιστολογίου\";s:2:\"fr\";s:17:\"Derniers articles\";s:2:\"ru\";s:31:\"Последние записи\";s:2:\"id\";s:12:\"Post Terbaru\";}','a:7:{s:2:\"en\";s:39:\"Display latest blog posts with a widget\";s:2:\"br\";s:81:\"Mostra uma lista de navegação para abrir os últimos artigos publicados no Blog\";s:2:\"pt\";s:81:\"Mostra uma lista de navegação para abrir os últimos artigos publicados no Blog\";s:2:\"el\";s:103:\"Προβάλει τις πιο πρόσφατες αναρτήσεις στο ιστολόγιό σας\";s:2:\"fr\";s:68:\"Permet d\'afficher la liste des derniers posts du blog dans un Widget\";s:2:\"ru\";s:100:\"Выводит список последних записей блога внутри виджета\";s:2:\"id\";s:51:\"Menampilkan posting blog terbaru menggunakan widget\";}','Erik Berman','http://www.nukleo.fr','1.0',1,10,1358262246);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
