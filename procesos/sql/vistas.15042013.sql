
DROP TABLE IF EXISTS `default_vw_video`;

 DROP VIEW IF EXISTS `default_vw_video` ;
 DROP TABLE IF EXISTS `default_vw_video` ;

 CREATE TABLE  `default_vw_video`(
 `id` int(11) ,
 `canales_id` int(11) ,
 `nombre_canal` varchar(128) ,
 `categorias_id` int(11) ,
 `nombre_categoria` varchar(45) ,
 `titulo` varchar(150) ,
 `fecha_registro` datetime ,
 `fecha_transmision` date ,
 `estado` tinyint(4) ,
 `imagen` varchar(150) ,
 `procedencia` int(11) ,
 `primer_padre` int(11) ,
 `segundo_padre` int(11) ,
 `tercer_padre` int(11) ,
 `programa` varchar(150) ,
 `tipo_imagen_id` int(11) ,
 `imagen_estado` tinyint(4) ,
 `tematico` varchar(341) ,
 `personaje` varchar(341) ,
 `resumen` text 
);


/*Table structure for table `vi_estadisticas` */

DROP TABLE IF EXISTS `vi_estadisticas`;

 DROP VIEW IF EXISTS `vi_estadisticas` ;
 DROP TABLE IF EXISTS `vi_estadisticas` ;

 CREATE TABLE  `vi_estadisticas`(
 `id` int(11) ,
 `nombre` varchar(150) ,
 `ca_id` int(11) ,
 `cat_id` int(11) ,
 `pr_id` bigint(11) ,
 `reproducciones` decimal(32,0) ,
 `valorizacion` decimal(32,0) ,
 `comentarios` decimal(32,0) 
);

/*Table structure for table `vi_estadisticas_comentarios` */

DROP TABLE IF EXISTS `vi_estadisticas_comentarios`;

 DROP VIEW IF EXISTS `vi_estadisticas_comentarios` ;
 DROP TABLE IF EXISTS `vi_estadisticas_comentarios` ;

 CREATE TABLE  `vi_estadisticas_comentarios`(
 `id` int(11) ,
 `nombre` varchar(150) ,
 `ca_id` int(11) ,
 `cat_id` int(11) ,
 `pr_id` bigint(11) ,
 `comentarios` decimal(32,0) 
);

/*Table structure for table `vi_estadisticas_reproducciones` */

DROP TABLE IF EXISTS `vi_estadisticas_reproducciones`;

 DROP VIEW IF EXISTS `vi_estadisticas_reproducciones` ;
 DROP TABLE IF EXISTS `vi_estadisticas_reproducciones` ;

 CREATE TABLE  `vi_estadisticas_reproducciones`(
 `id` int(11) ,
 `nombre` varchar(150) ,
 `ca_id` int(11) ,
 `cat_id` int(11) ,
 `pr_id` bigint(11) ,
 `reproducciones` decimal(32,0) 
);

/*Table structure for table `vi_estadisticas_valorizacion` */

DROP TABLE IF EXISTS `vi_estadisticas_valorizacion`;

 DROP VIEW IF EXISTS `vi_estadisticas_valorizacion` ;
 DROP TABLE IF EXISTS `vi_estadisticas_valorizacion` ;

 CREATE TABLE  `vi_estadisticas_valorizacion`(
 `id` int(11) ,
 `nombre` varchar(150) ,
 `ca_id` int(11) ,
 `cat_id` int(11) ,
 `pr_id` bigint(11) ,
 `valorizacion` decimal(32,0) 
);

/*View structure for view default_vw_video */

 DROP TABLE IF EXISTS `default_vw_video` ;
 DROP VIEW IF EXISTS `default_vw_video` ;

 CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `default_vw_video` AS select distinct `v`.`id` AS `id`,`v`.`canales_id` AS `canales_id`,`c`.`nombre` AS `nombre_canal`,`v`.`categorias_id` AS `categorias_id`,`cat`.`nombre` AS `nombre_categoria`,`v`.`titulo` AS `titulo`,`v`.`fecha_registro` AS `fecha_registro`,`v`.`fecha_transmision` AS `fecha_transmision`,`v`.`estado` AS `estado`,`img`.`imagen` AS `imagen`,`img`.`procedencia` AS `procedencia`,`gd`.`grupo_maestro_padre` AS `primer_padre`,`gd2`.`grupo_maestro_padre` AS `segundo_padre`,`gd3`.`grupo_maestro_padre` AS `tercer_padre`,`gm`.`nombre` AS `programa`,`img`.`tipo_imagen_id` AS `tipo_imagen_id`,`img`.`estado` AS `imagen_estado`,(select group_concat(`t`.`nombre` separator ',') from (((`default_cms_videos` `vi` join `default_cms_video_tags` `vt` on((`vt`.`videos_id` = `vi`.`id`))) join `default_cms_tags` `t` on((`vt`.`tags_id` = `t`.`id`))) join `default_cms_tipo_tags` `tt` on((`tt`.`id` = `t`.`tipo_tags_id`))) where ((`tt`.`id` = '1') and (`vi`.`id` = `v`.`id`))) AS `tematico`,(select group_concat(`t`.`nombre` separator ',') from (((`default_cms_videos` `vi` join `default_cms_video_tags` `vt` on((`vt`.`videos_id` = `vi`.`id`))) join `default_cms_tags` `t` on((`vt`.`tags_id` = `t`.`id`))) join `default_cms_tipo_tags` `tt` on((`tt`.`id` = `t`.`tipo_tags_id`))) where ((`tt`.`id` = '2') and (`vi`.`id` = `v`.`id`))) AS `personaje`,concat(`c`.`nombre`,' ',`cat`.`nombre`,' ',`gm`.`nombre`,' ',`gc`.`nombre`,' ',`gl`.`nombre`,' ',`v`.`titulo`) AS `resumen` from (((((((((`default_cms_videos` `v` left join `default_cms_canales` `c` on((`c`.`id` = `v`.`canales_id`))) left join `default_cms_categorias` `cat` on((`cat`.`id` = `v`.`categorias_id`))) left join `default_cms_imagenes` `img` on((`img`.`videos_id` = `v`.`id`))) left join `default_cms_grupo_detalles` `gd` on((`gd`.`video_id` = `v`.`id`))) left join `default_cms_grupo_detalles` `gd2` on((`gd2`.`grupo_maestro_id` = `gd`.`grupo_maestro_padre`))) left join `default_cms_grupo_detalles` `gd3` on((`gd3`.`grupo_maestro_id` = `gd2`.`grupo_maestro_padre`))) left join `default_cms_grupo_maestros` `gm` on((`gm`.`id` = `gd3`.`grupo_maestro_padre`))) left join `default_cms_grupo_maestros` `gc` on((`gc`.`id` = `gd2`.`grupo_maestro_padre`))) left join `default_cms_grupo_maestros` `gl` on((`gl`.`id` = `gd`.`grupo_maestro_padre`))) where (((`img`.`tipo_imagen_id` in (1,6)) or isnull(`img`.`tipo_imagen_id`)) and ((`img`.`estado` = 1) or isnull(`img`.`estado`))) order by `v`.`id` ;

/*View structure for view vi_estadisticas */

 DROP TABLE IF EXISTS `vi_estadisticas` ;
 DROP VIEW IF EXISTS `vi_estadisticas` ;

 CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vi_estadisticas` AS (select `gm`.`id` AS `id`,`gm`.`nombre` AS `nombre`,`ca`.`id` AS `ca_id`,`gm`.`categorias_id` AS `cat_id`,(select `gm3`.`id` from `default_cms_grupo_maestros` `gm3` where (`gm3`.`id` = (select `gd4`.`grupo_maestro_padre` from `default_cms_grupo_detalles` `gd4` where (`gd4`.`grupo_maestro_id` = (select `gd5`.`grupo_maestro_padre` from `default_cms_grupo_detalles` `gd5` where (`gd5`.`grupo_maestro_id` = `gm`.`id`)))))) AS `pr_id`,(select sum(`vi`.`reproducciones`) from `default_cms_videos` `vi` where `vi`.`id` in (select `gd2`.`video_id` from `default_cms_grupo_detalles` `gd2` where (`gd2`.`grupo_maestro_padre` = `gm`.`id`))) AS `reproducciones`,(select sum(`vi`.`valorizacion`) from `default_cms_videos` `vi` where `vi`.`id` in (select `gd2`.`video_id` from `default_cms_grupo_detalles` `gd2` where (`gd2`.`grupo_maestro_padre` = `gm`.`id`))) AS `valorizacion`,(select sum(`vi`.`comentarios`) from `default_cms_videos` `vi` where `vi`.`id` in (select `gd2`.`video_id` from `default_cms_grupo_detalles` `gd2` where (`gd2`.`grupo_maestro_padre` = `gm`.`id`))) AS `comentarios` from ((`default_cms_grupo_maestros` `gm` join `default_cms_grupo_detalles` `gd` on((`gd`.`grupo_maestro_padre` = `gm`.`id`))) join `default_cms_canales` `ca` on((`gm`.`canales_id` = `ca`.`id`))) where (`gm`.`tipo_grupo_maestro_id` = 1) group by `gm`.`id`) ;

/*View structure for view vi_estadisticas_comentarios */

 DROP TABLE IF EXISTS `vi_estadisticas_comentarios` ;
 DROP VIEW IF EXISTS `vi_estadisticas_comentarios` ;

 CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vi_estadisticas_comentarios` AS (select `gm`.`id` AS `id`,`gm`.`nombre` AS `nombre`,`ca`.`id` AS `ca_id`,`gm`.`categorias_id` AS `cat_id`,(select `gm3`.`id` from `default_cms_grupo_maestros` `gm3` where (`gm3`.`id` = (select `gd4`.`grupo_maestro_padre` from `default_cms_grupo_detalles` `gd4` where (`gd4`.`grupo_maestro_id` = (select `gd5`.`grupo_maestro_padre` from `default_cms_grupo_detalles` `gd5` where (`gd5`.`grupo_maestro_id` = `gm`.`id`)))))) AS `pr_id`,(select sum(`vi`.`comentarios`) from `default_cms_videos` `vi` where `vi`.`id` in (select `gd2`.`video_id` from `default_cms_grupo_detalles` `gd2` where (`gd2`.`grupo_maestro_padre` = `gm`.`id`))) AS `comentarios` from ((`default_cms_grupo_maestros` `gm` join `default_cms_grupo_detalles` `gd` on((`gd`.`grupo_maestro_padre` = `gm`.`id`))) join `default_cms_canales` `ca` on((`gm`.`canales_id` = `ca`.`id`))) where (`gm`.`tipo_grupo_maestro_id` = 1) group by `gm`.`id`) ;

/*View structure for view vi_estadisticas_reproducciones */

 DROP TABLE IF EXISTS `vi_estadisticas_reproducciones` ;
 DROP VIEW IF EXISTS `vi_estadisticas_reproducciones` ;

 CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vi_estadisticas_reproducciones` AS (select `gm`.`id` AS `id`,`gm`.`nombre` AS `nombre`,`ca`.`id` AS `ca_id`,`gm`.`categorias_id` AS `cat_id`,(select `gm3`.`id` from `default_cms_grupo_maestros` `gm3` where (`gm3`.`id` = (select `gd4`.`grupo_maestro_padre` from `default_cms_grupo_detalles` `gd4` where (`gd4`.`grupo_maestro_id` = (select `gd5`.`grupo_maestro_padre` from `default_cms_grupo_detalles` `gd5` where (`gd5`.`grupo_maestro_id` = `gm`.`id`)))))) AS `pr_id`,(select sum(`vi`.`reproducciones`) from `default_cms_videos` `vi` where `vi`.`id` in (select `gd2`.`video_id` from `default_cms_grupo_detalles` `gd2` where (`gd2`.`grupo_maestro_padre` = `gm`.`id`))) AS `reproducciones` from ((`default_cms_grupo_maestros` `gm` join `default_cms_grupo_detalles` `gd` on((`gd`.`grupo_maestro_padre` = `gm`.`id`))) join `default_cms_canales` `ca` on((`gm`.`canales_id` = `ca`.`id`))) where (`gm`.`tipo_grupo_maestro_id` = 1) group by `gm`.`id`) ;

/*View structure for view vi_estadisticas_valorizacion */

 DROP TABLE IF EXISTS `vi_estadisticas_valorizacion` ;
 DROP VIEW IF EXISTS `vi_estadisticas_valorizacion` ;

 CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vi_estadisticas_valorizacion` AS (select `gm`.`id` AS `id`,`gm`.`nombre` AS `nombre`,`ca`.`id` AS `ca_id`,`gm`.`categorias_id` AS `cat_id`,(select `gm3`.`id` from `default_cms_grupo_maestros` `gm3` where (`gm3`.`id` = (select `gd4`.`grupo_maestro_padre` from `default_cms_grupo_detalles` `gd4` where (`gd4`.`grupo_maestro_id` = (select `gd5`.`grupo_maestro_padre` from `default_cms_grupo_detalles` `gd5` where (`gd5`.`grupo_maestro_id` = `gm`.`id`)))))) AS `pr_id`,(select sum(`vi`.`valorizacion`) from `default_cms_videos` `vi` where `vi`.`id` in (select `gd2`.`video_id` from `default_cms_grupo_detalles` `gd2` where (`gd2`.`grupo_maestro_padre` = `gm`.`id`))) AS `valorizacion` from ((`default_cms_grupo_maestros` `gm` join `default_cms_grupo_detalles` `gd` on((`gd`.`grupo_maestro_padre` = `gm`.`id`))) join `default_cms_canales` `ca` on((`gm`.`canales_id` = `ca`.`id`))) where (`gm`.`tipo_grupo_maestro_id` = 1) group by `gm`.`id`) ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE ;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS ;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS ;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES ;
