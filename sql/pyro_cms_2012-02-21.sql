/*
SQLyog Ultimate v10.42 
MySQL - 5.5.29-0ubuntu0.12.04.1 : Database - pyro_admin
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
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

/*Table structure for table `configuracion**` */

DROP TABLE IF EXISTS `configuracion**`;

CREATE TABLE `configuracion**` (
  `configuracion_id` int(11) NOT NULL AUTO_INCREMENT,
  `f_proceso_n_minutos` int(11) DEFAULT NULL,
  ` fecha_registro` datetime DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `accion` int(11) DEFAULT NULL COMMENT '1: proceso n minutos',
  PRIMARY KEY (`configuracion_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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

/*Table structure for table `default_cms_canal_portadas` */

DROP TABLE IF EXISTS `default_cms_canal_portadas`;

CREATE TABLE `default_cms_canal_portadas` (
  `canal_id` int(11) NOT NULL,
  `portada_id` int(11) NOT NULL,
  `estado` tinyint(4) DEFAULT NULL COMMENT '1 = Activo\\n0 = Inactivo',
  `fecha_registro` datetime DEFAULT NULL,
  `usuario_registro` int(11) DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `usuario_actualizacion` int(11) DEFAULT NULL,
  PRIMARY KEY (`canal_id`,`portada_id`),
  KEY `fk_canal_portadas_portada1_idx` (`portada_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `default_cms_canales` */

DROP TABLE IF EXISTS `default_cms_canales`;

CREATE TABLE `default_cms_canales` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID del Canal',
  `tipo_canales_id` int(11) NOT NULL COMMENT 'ID del Tipo de Canal',
  `alias` varchar(128) DEFAULT NULL COMMENT 'Etiqueta del Canal utilizado para el SEO',
  `nombre` varchar(128) DEFAULT NULL COMMENT 'Nombre o Razon Social del Canal',
  `descripcion` varchar(256) DEFAULT NULL COMMENT 'Descripcion del Canal',
  `apikey` varchar(40) DEFAULT NULL COMMENT 'Api que permite diferenciar los videos de cada Canal(Portal dentro de LIQUID)',
  `playerkey` varchar(40) DEFAULT NULL COMMENT 'Api que permite leer los videos de un determinado canal(portal dentro de LIQUID)',
  `id_mongo` varchar(25) DEFAULT NULL,
  `cantidad_suscriptores` int(11) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT '0' COMMENT 'Estado del canal: 0 = Borrador - 1 = Publicado - 2 = Eliminado',
  `fecha_registro` datetime DEFAULT NULL COMMENT 'Fecha y Hora del Registro del Canal',
  `usuario_registro` int(11) DEFAULT NULL COMMENT 'ID Usuario que registra el Canal',
  `fecha_actualizacion` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la Ultima actualización del registro del Canal',
  `usuario_actualizacion` int(11) DEFAULT NULL COMMENT 'ID del Ultimo Usuario que modifica el registro del Canal.',
  `estado_migracion` tinyint(4) DEFAULT '0' COMMENT 'Estado de la migración a MongoDB:\\n0 = Registro Nuevo (por migrar)\\n1 = Proceso (migrando)\\n2 = Migrado\\n\\nAdicionalmente para cuando se modifique un registro, el CMS graba un status 9, y el proceso lo re-tomará para migrarlo al MongoDB.\\n9 = Registro Actualiza /* comment truncated */',
  `fecha_migracion` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la migración por primera vez (registros nuevos)\\n',
  `fecha_migracion_actualizacion` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la migración de la ultima actualización, es decir, cuando un registro se actualiza se graba el status = 9, y el proceso lo re-toma para migrar al MongoDB',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

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
  `estado_migracion` tinyint(4) DEFAULT '0' COMMENT 'Status de la migración a MongoDB:\\n0 = Registro Nuevo (por migrar)\\n1 = Proceso (migrando)\\n2 = Migrado\\n\\nAdicionalmente para cuando se modifique un registro, el CMS graba un status 9, y el proceso lo re-tomará para migrarlo al MongoDB.\\n9 = Registro Actualiza /* comment truncated */',
  `fecha_migracion` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la migración por primera vez (registros nuevos)',
  `fecha_migracion_actualizacion` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la migración de la ultima actualización, es decir, cuando un registro se actualiza se graba el status = 9, y el proceso lo re-toma para migrar al MongoDB',
  `categorias_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Table structure for table `default_cms_comentarios` */

DROP TABLE IF EXISTS `default_cms_comentarios`;

CREATE TABLE `default_cms_comentarios` (
  `videos_id` int(11) NOT NULL,
  `cantidad_comentarios` int(11) DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  PRIMARY KEY (`videos_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `default_cms_detalle_secciones` */

DROP TABLE IF EXISTS `default_cms_detalle_secciones`;

CREATE TABLE `default_cms_detalle_secciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `secciones_id` int(11) NOT NULL,
  `reglas_id` int(11) DEFAULT NULL,
  `videos_id` int(11) DEFAULT NULL,
  `grupo_maestros_id` int(11) DEFAULT NULL,
  `categorias_id` int(11) DEFAULT NULL,
  `tags_id` int(11) DEFAULT NULL,
  `imagenes_id` int(11) DEFAULT NULL,
  `peso` int(11) DEFAULT NULL COMMENT 'Orden de presentacion de los Item en una seccion',
  `descripcion_item` varchar(150) DEFAULT NULL COMMENT 'Descripcion por cada item(descripcion sobre el titulo)',
  `templates_id` int(11) NOT NULL,
  `estado` tinyint(4) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `usuario_registro` int(11) DEFAULT NULL,
  `estado_migracion` tinyint(4) DEFAULT NULL,
  `fecha_migracion` datetime DEFAULT NULL,
  `fecha_migracion_actualizacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_seccion_regla_regla1_idx` (`reglas_id`),
  KEY `fk_seccion_regla_default_cms_videos1_idx` (`videos_id`),
  KEY `fk_seccion_regla_default_cms_grupo_maestros1_idx` (`grupo_maestros_id`),
  KEY `fk_seccion_regla_categoria**1_idx` (`categorias_id`),
  KEY `fk_seccion_regla_default_cms_tags1_idx` (`tags_id`),
  KEY `fk_default_cms_detalle_secciones_template1_idx` (`templates_id`),
  KEY `fk_default_cms_detalle_secciones_default_cms_imagenes1_idx` (`imagenes_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `default_cms_grupo_detalles` */

DROP TABLE IF EXISTS `default_cms_grupo_detalles`;

CREATE TABLE `default_cms_grupo_detalles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grupo_maestro_padre` int(11) NOT NULL,
  `grupo_maestro_id` int(11) DEFAULT NULL,
  `video_id` int(11) DEFAULT NULL,
  `tipo_grupo_maestros_id` int(11) NOT NULL,
  `id_mongo` varchar(25) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `usuario_registro` int(11) DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `usuario_actualizacion` int(11) DEFAULT NULL,
  `estado_migracion` tinyint(4) DEFAULT NULL,
  `fecha_migracion` datetime DEFAULT NULL,
  `fecha_migracion_actualizacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_grupo_detalle_grupo_maestro1_idx` (`grupo_maestro_id`),
  KEY `fk_grupo_detalle_video1_idx` (`video_id`),
  KEY `fk_grupo_detalle_grupo_maestro2_idx` (`grupo_maestro_padre`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

/*Table structure for table `default_cms_grupo_maestro_tags` */

DROP TABLE IF EXISTS `default_cms_grupo_maestro_tags`;

CREATE TABLE `default_cms_grupo_maestro_tags` (
  `grupo_maestros_id` int(11) NOT NULL,
  `tags_id` int(11) NOT NULL,
  `estado` tinyint(4) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `usuario_registro` int(11) DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `usuario_actualizacion` int(11) DEFAULT NULL,
  `estado_migracion_sphinx` tinyint(4) DEFAULT NULL,
  `fecha_migracion_sphinx` datetime DEFAULT NULL,
  `fecha_migracion_actualizacion_sphinx` datetime DEFAULT NULL,
  PRIMARY KEY (`grupo_maestros_id`,`tags_id`),
  KEY `fk_default_cms_grupo_maestro_tags_default_cms_tags1_idx` (`tags_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `default_cms_grupo_maestros` */

DROP TABLE IF EXISTS `default_cms_grupo_maestros`;

CREATE TABLE `default_cms_grupo_maestros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) DEFAULT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  `alias` varchar(150) DEFAULT NULL,
  `tipo_grupo_maestro_id` int(11) NOT NULL,
  `canales_id` int(11) NOT NULL,
  `categorias_id` int(11) DEFAULT NULL,
  `cantidad_suscriptores` int(11) DEFAULT NULL,
  `peso` int(11) DEFAULT NULL COMMENT 'Indica el orden de presentacion del grupo\\n Ejemplo: Colecciones\\n1 = Temporada 1\\n2 = Temporada 2',
  `id_mongo` varchar(25) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `usuario_registro` int(11) DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `usuario_actualizacion` int(11) DEFAULT NULL,
  `estado_migracion` tinyint(4) DEFAULT NULL,
  `fecha_migracion` datetime DEFAULT NULL,
  `fecha_migracion_actualizacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_grupo_maestro_tipo_grupo_maestro1_idx` (`tipo_grupo_maestro_id`),
  KEY `fk_default_cms_grupo_maestros_default_cms_canales1_idx` (`canales_id`),
  KEY `fk_default_cms_grupo_maestros_default_cms_categorias1_idx` (`categorias_id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

/*Table structure for table `default_cms_imagenes` */

DROP TABLE IF EXISTS `default_cms_imagenes`;

CREATE TABLE `default_cms_imagenes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `canales_id` int(11) DEFAULT NULL,
  `grupo_maestros_id` int(11) DEFAULT NULL,
  `videos_id` int(11) DEFAULT NULL,
  `imagen` varchar(150) DEFAULT NULL,
  `tipo_imagen_id` int(11) NOT NULL,
  `estado` tinyint(4) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `usuario_registro` int(11) DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `usuario_actualizacion` int(11) DEFAULT NULL,
  `estado_migracion` tinyint(4) DEFAULT NULL,
  `fecha_migracion` datetime DEFAULT NULL,
  `fecha_migracion_actualizacion` datetime DEFAULT NULL,
  `imagen_padre` int(11) DEFAULT NULL,
  `procedencia` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_default_cms_imagenes_default_cms_videos1_idx` (`videos_id`),
  KEY `fk_default_cms_imagenes_default_cms_grupo_maestros1_idx` (`grupo_maestros_id`),
  KEY `fk_default_cms_imagenes_default_cms_tipo_imagen1_idx` (`tipo_imagen_id`),
  KEY `fk_default_cms_imagenes_default_cms_canales1_idx` (`canales_id`)
) ENGINE=MyISAM AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;

/*Table structure for table `default_cms_portada_secciones` */

DROP TABLE IF EXISTS `default_cms_portada_secciones`;

CREATE TABLE `default_cms_portada_secciones` (
  `portadas_id` int(11) NOT NULL,
  `secciones_id` int(11) NOT NULL,
  `peso` int(11) DEFAULT NULL COMMENT 'Orden de presentación de una Sección dentro de una Portada',
  `estado` tinyint(4) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `usuario_registro` int(11) DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `usuario_actualizacion` int(11) DEFAULT NULL,
  `estado_migracion` tinyint(4) DEFAULT NULL,
  `fecha_migracion` datetime DEFAULT NULL,
  `fecha_migracion_actualizacion` datetime DEFAULT NULL,
  PRIMARY KEY (`portadas_id`,`secciones_id`),
  KEY `fk_portada_seccion_seccion1_idx` (`secciones_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `default_cms_portadas` */

DROP TABLE IF EXISTS `default_cms_portadas`;

CREATE TABLE `default_cms_portadas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `descripcion` varchar(45) DEFAULT NULL COMMENT 'Portada Principal\\nInterna Canal 4\\nInterna Ficha de Video\\nInterna Programa\\n',
  `tipo_portadas_id` int(11) NOT NULL,
  `estado` tinyint(4) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `usuario_registro` int(11) DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `usuario_actualizacion` int(11) DEFAULT NULL,
  `id_mongo` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_portada_tipo_portadas1_idx` (`tipo_portadas_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `default_cms_reglas` */

DROP TABLE IF EXISTS `default_cms_reglas`;

CREATE TABLE `default_cms_reglas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  `descripcion` varchar(45) DEFAULT NULL COMMENT 'Por ejemplo:\\nCantidad de reproducciones\\nCantidad de Comentarios\\nCantidad de like\\nRecientes\\nEtc.',
  `estado` tinyint(4) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `usuario_registro` int(11) DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `usuario_actualizacion` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `default_cms_roles` */

DROP TABLE IF EXISTS `default_cms_roles`;

CREATE TABLE `default_cms_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Rol del usuario',
  `nombre` varchar(45) DEFAULT NULL COMMENT 'Nombre del Rol',
  `descripcion` varchar(45) DEFAULT NULL COMMENT 'Descripcion del Rol',
  `estado` tinyint(4) DEFAULT '0' COMMENT 'Status del Rol:\\n1 = Activo\\n0 = Inactivo',
  `fecha_registro` datetime DEFAULT NULL COMMENT 'Fecha y hora del registro del Rol\\n',
  `usuario_registro` int(11) DEFAULT NULL COMMENT 'ID Usuario que registra el Rol\\n',
  `fecha_actualizacion` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la Ultima actualización del registro del Rol\\n',
  `usuario_actualizacion` int(11) DEFAULT NULL COMMENT 'ID del Ultimo Usuario que modifica el registro del Rol.\\n',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `default_cms_secciones` */

DROP TABLE IF EXISTS `default_cms_secciones`;

CREATE TABLE `default_cms_secciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID de la Seccion del Motor\\n',
  `nombre` varchar(45) DEFAULT NULL COMMENT 'Nombre de la Seccion\\n',
  `descripcion` varchar(45) DEFAULT NULL COMMENT 'Descripcion de la Seccion',
  `tipo` tinyint(4) DEFAULT NULL COMMENT 'Forma en la que se alimenta el contenido de una seccion:\\n0=Automatica\\n1=Manual\\n',
  `id_mongo` varchar(25) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT '0' COMMENT 'Estados de la Sección:\\n1 = Activo\\n0 = Inactivo',
  `fecha_registro` datetime DEFAULT NULL COMMENT 'Fecha y hora del registro de la Seccion',
  `usuario_registro` int(11) DEFAULT NULL COMMENT 'ID Usuario que registra la Seccion',
  `fecha_actualizacion` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la Ultima actualización del registro de la Seccion',
  `usuario_actualizacion` int(11) DEFAULT NULL COMMENT 'ID del Ultimo Usuario que modifica el registro de la Seccion.',
  `estado_migracion` tinyint(4) DEFAULT NULL,
  `fecha_migracion` datetime DEFAULT NULL,
  `fecha_migracion_actualizacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `default_cms_tags` */

DROP TABLE IF EXISTS `default_cms_tags`;

CREATE TABLE `default_cms_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_tags_id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` varchar(50) DEFAULT NULL,
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
  PRIMARY KEY (`id`),
  KEY `fk_tags_tipo_tag1_idx` (`tipo_tags_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

/*Table structure for table `default_cms_templates` */

DROP TABLE IF EXISTS `default_cms_templates`;

CREATE TABLE `default_cms_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `usuario_registro` int(11) DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `usuario_actualizacion` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `default_cms_tipo_canales` */

DROP TABLE IF EXISTS `default_cms_tipo_canales`;

CREATE TABLE `default_cms_tipo_canales` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID del Tipo de Canal',
  `nombre` varchar(45) DEFAULT NULL COMMENT 'Nombre del Tipo de Canal. Por ejemplo:\\n* Canal de TV\\n* Empresa Editora\\n* Emisora Radial\\n* Emisora Internet\\n* Otros',
  `descripcion` varchar(45) DEFAULT NULL COMMENT 'Descripción del Tipo de Canal',
  `estado` tinyint(4) DEFAULT '0' COMMENT 'Estados del Tipo de Canal:\\n1 = Activo\\n0 = Inactivo\\n',
  `fecha_registro` datetime DEFAULT NULL COMMENT 'Fecha y Hora del Registro del Tipo de Canal\\n',
  `usuario_registro` int(11) DEFAULT NULL COMMENT 'ID Usuario que registra el Tipo de Canal',
  `fecha_actualizacion` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la Ultima actualización del registro del Tipo de Canal\\n',
  `usuario_actualizacion` int(11) DEFAULT NULL COMMENT 'ID del Ultimo Usuario que modifica el registro del Tipo de Canal.\\n',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `default_cms_tipo_grupo_maestros` */

DROP TABLE IF EXISTS `default_cms_tipo_grupo_maestros`;

CREATE TABLE `default_cms_tipo_grupo_maestros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` varchar(500) DEFAULT NULL COMMENT '0 = Video\\n---------------------------------\\n1 = Lista de Reproduccion\\n2 = Coleccion\\n3 = Programa\\n4 = Canal',
  `estado` tinyint(4) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `usuario_registro` int(11) DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `usuario_actualizacion` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

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
  `alto` int(11) DEFAULT NULL,
  `ancho` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Table structure for table `default_cms_tipo_portadas` */

DROP TABLE IF EXISTS `default_cms_tipo_portadas`;

CREATE TABLE `default_cms_tipo_portadas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL COMMENT 'principal\\ncategoria\\ntag\\nprograma',
  `descripcion` varchar(45) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `usuario_registro` int(11) DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `usuario_actualizacion` int(11) DEFAULT NULL,
  `id_mongo` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `default_cms_tipo_tags` */

DROP TABLE IF EXISTS `default_cms_tipo_tags`;

CREATE TABLE `default_cms_tipo_tags` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL COMMENT 'Tematico\\nPersonaje',
  `descripcion` varchar(45) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `usuario_registro` int(11) DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `usuario_actualizacion` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `default_cms_tipo_videos` */

DROP TABLE IF EXISTS `default_cms_tipo_videos`;

CREATE TABLE `default_cms_tipo_videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL COMMENT 'Normal\\nPremium',
  `descripcion` varchar(50) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT '0' COMMENT '1 = Activo\\n0 = Inactivo',
  `fecha_registro` datetime DEFAULT NULL,
  `usuario_registro` int(11) DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `usuario_actualizacion` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Table structure for table `default_cms_usuario_group_canales` */

DROP TABLE IF EXISTS `default_cms_usuario_group_canales`;

CREATE TABLE `default_cms_usuario_group_canales` (
  `canal_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `estado` tinyint(4) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `usuario_registro` int(11) DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `usuario_actualizacion` int(11) DEFAULT NULL,
  PRIMARY KEY (`canal_id`,`user_id`,`group_id`),
  KEY `fk_table1_canal1_idx` (`canal_id`),
  KEY `fk_table1_usuario1_idx` (`user_id`),
  KEY `fk_table1_rol1_idx` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `default_cms_usuarios` */

DROP TABLE IF EXISTS `default_cms_usuarios`;

CREATE TABLE `default_cms_usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID de Usuario',
  `rol_id` int(11) NOT NULL COMMENT 'ID del Rol que desempeña un Usuario',
  `nombres` varchar(64) DEFAULT NULL COMMENT 'Nombres del Usuario',
  `apellidos` varchar(64) DEFAULT NULL COMMENT 'Apellidos del Usuario',
  `direccion` varchar(128) DEFAULT NULL COMMENT 'Direccion del usuario',
  `telefono1` varchar(16) DEFAULT NULL COMMENT 'Telefono del usuario',
  `telefono2` varchar(16) DEFAULT NULL COMMENT 'Telefono del usuario',
  `dni` varchar(12) DEFAULT NULL COMMENT 'Nro de documento del Usuario o DNI',
  `email` varchar(128) DEFAULT NULL COMMENT 'e-mail del Usuario',
  `usuario` varchar(10) DEFAULT NULL COMMENT 'Codigo de Identificación del Usuario en el Sistema (CMS)',
  `password` varchar(32) DEFAULT NULL COMMENT 'Clave del Usuario dentro del Sistema CMS',
  `estado` tinyint(4) DEFAULT '0' COMMENT 'Estados del Usuario:\\n1 = Activo\\n0 = Inactivo',
  `fecha_registro` datetime DEFAULT NULL COMMENT 'Fecha y hora del registro del Usuario\\n',
  `usuario_registro` int(11) DEFAULT NULL COMMENT 'ID Usuario que registra al Usuario\\n',
  `fecha_actualizacion` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la Ultima actualización del registro del Usuario\\n',
  `usuario_actualizacion` int(11) DEFAULT NULL COMMENT 'ID del Ultimo Usuario que modifica el registro del Usuario.\\n',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `default_cms_valorizaciones` */

DROP TABLE IF EXISTS `default_cms_valorizaciones`;

CREATE TABLE `default_cms_valorizaciones` (
  `videos_id` int(11) NOT NULL,
  `cantidad_me_gusta` int(11) DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  PRIMARY KEY (`videos_id`),
  KEY `fk_valorizacion_video1_idx` (`videos_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
  PRIMARY KEY (`tags_id`,`videos_id`),
  KEY `fk_tag-video_tag1_idx` (`tags_id`),
  KEY `fk_tag-video_video1_idx` (`videos_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
  `titulo` varchar(150) DEFAULT NULL COMMENT 'Titulo del Video',
  `alias` varchar(150) DEFAULT NULL COMMENT 'Etiqueta del Portal utilizado para el SEO',
  `descripcion` varchar(500) DEFAULT NULL COMMENT 'Descripcion del Video',
  `fragmento` tinyint(4) DEFAULT NULL,
  `codigo` varchar(100) DEFAULT NULL COMMENT 'CODIGO para la visualizacion DEL VIDEO retornado por LIQUID',
  `reproducciones` decimal(10,0) DEFAULT NULL COMMENT 'Nro de reproducciones del video en LIQUID',
  `duracion` time DEFAULT NULL,
  `fecha_publicacion_inicio` datetime DEFAULT NULL,
  `fecha_publicacion_fin` datetime DEFAULT NULL,
  `fecha_transmision` date DEFAULT NULL,
  `horario_transmision_inicio` time DEFAULT NULL,
  `horario_transmision_fin` time DEFAULT NULL,
  `ubicacion` varchar(100) DEFAULT NULL,
  `id_mongo` varchar(25) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT '0' COMMENT 'Estado de Publicación del Video: 0 = codificando - 1 = borrador - 2 = publicado - 3 = eliminado',
  `estado_liquid` tinyint(4) DEFAULT NULL COMMENT 'Estado del proceso de carga del servidor de Mi Canal hacia el repositorio de Liquid\\n0 = Nuevo\\n1 = Codificando\\n2 = Codificado\\n3 = Subiendo\\n4 = Borrador\\n5 = Activo\\n6 = Publicado',
  `fecha_registro` datetime DEFAULT NULL COMMENT 'Fecha y hora del registro del Video\\n',
  `usuario_registro` int(11) DEFAULT NULL COMMENT 'ID Usuario que registra el Video\\n',
  `fecha_actualizacion` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la Ultima actualización del registro del Video\\n',
  `usuario_actualizacion` int(11) DEFAULT NULL COMMENT 'ID del Ultimo Usuario que modifica el registro del VIdeo.\\n',
  `estado_migracion` tinyint(4) DEFAULT '0' COMMENT '\\"Status de la migración a MongoDB:\\n0 = Registro Nuevo (por migrar)\\n1 = Proceso (migrando)\\n2 = Migrado\\n\\nAdicionalmente para cuando se modifique un registro, el CMS graba un status 9, y el proceso lo re-tomará para migrarlo al MongoDB.\\n9 = Registro Actualiz /* comment truncated */',
  `fecha_migracion` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la migración por primera vez (registros nuevos)\\n',
  `fecha_migracion_actualizacion` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la migración de la ultima actualización, es decir, cuando un registro se actualiza se graba el status = 9, y el proceso lo re-toma para migrar al MongoDB					',
  `estado_migracion_sphinx_tit` tinyint(4) DEFAULT '0' COMMENT '\\"Status de la migración a Sphinx:\\n0 = Registro Nuevo (por migrar)\\n1 = Proceso (migrando)\\n2 = Migrado\\n\\nAdicionalmente para cuando se modifique un registro, el CMS graba un status 9, y el proceso lo re-tomará para migrarlo al Sphinx.\\n9 = Registro Actualizad /* comment truncated */',
  `fecha_migracion_sphinx_tit` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la migración por primera vez (registros nuevos)',
  `fecha_migracion_actualizacion_sphinx_tit` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la migración de la ultima actualización, es decir, cuando un registro se actualiza se graba el status = 9, y el proceso lo re-toma para migrar al Sphinx	',
  `estado_migracion_sphinx_des` tinyint(4) DEFAULT '0' COMMENT '\\"Status de la migración a Sphinx:\\n0 = Registro Nuevo (por migrar)\\n1 = Proceso (migrando)\\n2 = Migrado\\n\\nAdicionalmente para cuando se modifique un registro, el CMS graba un status 9, y el proceso lo re-tomará para migrarlo al Sphinx.\\n9 = Registro Actualizad /* comment truncated */',
  `fecha_migracion_sphinx_des` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la migración por primera vez (registros nuevos)',
  `fecha_migracion_actualizacion_sphinx_des` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la migración de la ultima actualización, es decir, cuando un registro se actualiza se graba el status = 9, y el proceso lo re-toma para migrar al Sphinx	',
  `procedencia` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_video_tipo-video1_idx` (`tipo_videos_id`),
  KEY `fk_video_usuario1_idx` (`usuarios_id`),
  KEY `fk_default_cms_videos_default_cms_categorias1_idx` (`categorias_id`),
  KEY `fk_default_cms_videos_default_cms_canales1_idx` (`canales_id`),
  KEY `fk_default_cms_videos_default_cms_canales2_idx` (`fuente`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Table structure for table `default_cms_visitas` */

DROP TABLE IF EXISTS `default_cms_visitas`;

CREATE TABLE `default_cms_visitas` (
  `videos_id` int(11) NOT NULL,
  `cantidad_visitas` decimal(10,0) DEFAULT NULL COMMENT 'Cantidad de visitas a la Ficha del Video',
  `fecha_actualizacion` datetime DEFAULT NULL,
  PRIMARY KEY (`videos_id`),
  KEY `fk_visitas_video1_idx` (`videos_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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

/*Table structure for table `default_groups` */

DROP TABLE IF EXISTS `default_groups`;

CREATE TABLE `default_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Rol del usuario',
  `name` varchar(100) DEFAULT NULL COMMENT 'Nombre del Rol',
  `description` varchar(250) DEFAULT NULL COMMENT 'Descripcion del Rol',
  `estado` tinyint(4) DEFAULT '0' COMMENT 'Status del Rol:\\n1 = Activo\\n0 = Inactivo',
  `fecha_registro` datetime DEFAULT NULL COMMENT 'Fecha y hora del registro del Rol\\n',
  `usuario_registro` int(11) DEFAULT NULL COMMENT 'ID Usuario que registra el Rol\\n',
  `fecha_actualizacion` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la Ultima actualización del registro del Rol\\n',
  `usuario_actualizacion` int(11) DEFAULT NULL COMMENT 'ID del Ultimo Usuario que modifica el registro del Rol.\\n',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Table structure for table `default_keywords` */

DROP TABLE IF EXISTS `default_keywords`;

CREATE TABLE `default_keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `default_keywords_applied` */

DROP TABLE IF EXISTS `default_keywords_applied`;

CREATE TABLE `default_keywords_applied` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` char(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `keyword_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `default_migrations` */

DROP TABLE IF EXISTS `default_migrations`;

CREATE TABLE `default_migrations` (
  `version` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

/*Table structure for table `default_navigation_groups` */

DROP TABLE IF EXISTS `default_navigation_groups`;

CREATE TABLE `default_navigation_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `abbrev` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `abbrev` (`abbrev`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

/*Table structure for table `default_permissions` */

DROP TABLE IF EXISTS `default_permissions`;

CREATE TABLE `default_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `module` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `roles` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

/*Table structure for table `default_users` */

DROP TABLE IF EXISTS `default_users`;

CREATE TABLE `default_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID de Usuario',
  `email` varchar(60) DEFAULT NULL COMMENT 'e-mail del Usuario',
  `password` varchar(100) DEFAULT NULL COMMENT 'Clave del Usuario dentro del Sistema CMS',
  `salt` varchar(6) DEFAULT NULL,
  `group_id` int(11) NOT NULL COMMENT 'ID del Rol que desempeña un Usuario',
  `ip_address` varchar(16) DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `last_login` int(11) DEFAULT NULL,
  `username` varchar(20) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `nombres` varchar(64) DEFAULT NULL COMMENT 'Nombres del Usuario',
  `apellidos` varchar(64) DEFAULT NULL COMMENT 'Apellidos del Usuario',
  `direccion` varchar(128) DEFAULT NULL COMMENT 'Direccion del usuario',
  `telefono1` varchar(16) DEFAULT NULL COMMENT 'Telefono del usuario',
  `telefono2` varchar(16) DEFAULT NULL COMMENT 'Telefono del usuario',
  `dni` varchar(12) DEFAULT NULL COMMENT 'Nro de documento del Usuario o DNI',
  `estado` tinyint(4) DEFAULT '0' COMMENT 'Estados del Usuario: 1 = Activo 0 = Inactivo',
  `fecha_registro` datetime DEFAULT NULL COMMENT 'Fecha y hora del registro del Usuario\\n',
  `usuario_registro` int(11) DEFAULT NULL COMMENT 'ID Usuario que registra al Usuario\\n',
  `fecha_actualizacion` datetime DEFAULT NULL COMMENT 'Fecha y Hora de la Ultima actualización del registro del Usuario\\n',
  `usuario_actualizacion` int(11) DEFAULT NULL COMMENT 'ID del Ultimo Usuario que modifica el registro del Usuario.\\n',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Table structure for table `default_variables` */

DROP TABLE IF EXISTS `default_variables`;

CREATE TABLE `default_variables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `default_widget_areas` */

DROP TABLE IF EXISTS `default_widget_areas`;

CREATE TABLE `default_widget_areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

/*Table structure for table `publicidad**` */

DROP TABLE IF EXISTS `publicidad**`;

CREATE TABLE `publicidad**` (
  `destacados_publicidad_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID de los Videos Destacados de Publicidad',
  `script_publicidad` varchar(500) DEFAULT NULL COMMENT 'Sentencia de javascript que indica un contenido de publicidad que se mostrara en una seccion determinada del Motor.',
  `peso` tinyint(4) DEFAULT NULL COMMENT 'Grado de importancia para la presentación del destacado. Secuencia de presentación.',
  `status` tinyint(4) DEFAULT '0',
  `fecha_registro` datetime DEFAULT NULL,
  `usuario_registro` int(11) DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `usuario_actualizacion` int(11) DEFAULT NULL,
  `status_migracion` tinyint(4) DEFAULT '0',
  `fecha_migracion` datetime DEFAULT NULL,
  `fecha_migracion_actualizacion` datetime DEFAULT NULL,
  PRIMARY KEY (`destacados_publicidad_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;