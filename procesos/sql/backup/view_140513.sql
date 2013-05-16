DROP TABLE IF EXISTS default_vw_coleccion;

DROP VIEW IF EXISTS default_vw_coleccion ;
DROP TABLE IF EXISTS default_vw_coleccion ;

 CREATE TABLE  default_vw_coleccion(
 v varchar(1) ,
 id int(11) ,
 nombre varchar(150) ,
 estado bigint(20) ,
 fecha_registro datetime ,
 fecha_transmision datetime ,
 categorias_id int(11) ,
 categoria varchar(45) ,
 gm1 int(33) ,
 gm1_nom varchar(150) ,
 gm2 int(33) ,
 gm2_nom varchar(150) ,
 gm3 int(33) ,
 gm3_nom varchar(150) ,
 tipo_grupo varchar(11) ,
 canales_id int(11) ,
 imagen varchar(150) ,
 procedencia int(11) 
);

/*Table structure for table default_vw_lista */

DROP TABLE IF EXISTS default_vw_lista;

 DROP VIEW IF EXISTS default_vw_lista ;
 DROP TABLE IF EXISTS default_vw_lista ;

 CREATE TABLE  default_vw_lista(
 v varchar(1) ,
 id int(11) ,
 nombre varchar(150) ,
 estado bigint(20) ,
 fecha_registro datetime ,
 fecha_transmision datetime ,
 categorias_id int(11) ,
 categoria varchar(45) ,
 gm1 int(33) ,
 gm1_nom varchar(150) ,
 gm2 int(33) ,
 gm2_nom varchar(150) ,
 gm3 int(33) ,
 gm3_nom varchar(150) ,
 tipo_grupo varchar(11) ,
 canales_id int(11) ,
 imagen varchar(150) ,
 procedencia int(11) 
);

/*Table structure for table default_vw_maestros */

DROP TABLE IF EXISTS default_vw_maestros;

 DROP VIEW IF EXISTS default_vw_maestros ;
 DROP TABLE IF EXISTS default_vw_maestros ;

 CREATE TABLE  default_vw_maestros(
 m varchar(1) ,
 id int(11) ,
 nombre varchar(150) ,
 estado int(5) ,
 fecha_registro datetime ,
 fecha_transmision_inicio datetime ,
 categorias_id int(11) ,
 categoria varchar(45) ,
 gm1 varchar(11) ,
 gm1_nom varchar(150) ,
 gm2 varchar(11) ,
 gm2_nom varchar(150) ,
 gm3 varchar(11) ,
 gm3_nom varchar(150) ,
 tipo_grupo int(11) ,
 canales_id int(11) 
);

/*Table structure for table default_vw_maestros_videos */

DROP TABLE IF EXISTS default_vw_maestros_videos;

 DROP VIEW IF EXISTS default_vw_maestros_videos ;
 DROP TABLE IF EXISTS default_vw_maestros_videos ;

 CREATE TABLE  default_vw_maestros_videos(
 v varchar(1) ,
 id int(11) ,
 nombre varchar(150) ,
 estado bigint(20) ,
 fecha_registro datetime ,
 fecha_transmision datetime ,
 categorias_id int(11) ,
 categoria varchar(45) ,
 gm1 int(33) ,
 gm1_nom varchar(150) ,
 gm2 int(33) ,
 gm2_nom varchar(150) ,
 gm3 int(33) ,
 gm3_nom varchar(150) ,
 tipo_grupo varchar(11) ,
 canales_id int(11) ,
 imagen varchar(150) ,
 procedencia int(11) 
);

/*Table structure for table default_vw_organizar */

DROP TABLE IF EXISTS default_vw_organizar;

 DROP VIEW IF EXISTS default_vw_organizar ;
 DROP TABLE IF EXISTS default_vw_organizar ;

 CREATE TABLE  default_vw_organizar(
 v varchar(1) ,
 id int(11) ,
 nombre varchar(150) ,
 estado bigint(20) ,
 fecha_registro datetime ,
 fecha_transmision datetime ,
 categorias_id int(11) ,
 categoria varchar(45) ,
 gm1 int(33) ,
 gm1_nom varchar(150) ,
 gm2 int(33) ,
 gm2_nom varchar(150) ,
 gm3 int(33) ,
 gm3_nom varchar(150) ,
 tipo_grupo varchar(11) ,
 canales_id int(11) ,
 imagen varchar(150) ,
 procedencia int(11) 
);

/*Table structure for table default_vw_papelera */

DROP TABLE IF EXISTS default_vw_papelera;

 DROP VIEW IF EXISTS default_vw_papelera ;
 DROP TABLE IF EXISTS default_vw_papelera ;

 CREATE TABLE  default_vw_papelera(
 maestros varchar(7) ,
 id int(11) ,
 estado bigint(5) ,
 titulo varchar(150) ,
 tipo_maestro varchar(11) ,
 canales_id int(11) ,
 fecha_registro datetime 
);

/*Table structure for table default_vw_programa */

DROP TABLE IF EXISTS default_vw_programa;

 DROP VIEW IF EXISTS default_vw_programa ;
 DROP TABLE IF EXISTS default_vw_programa ;

 CREATE TABLE  default_vw_programa(
 v varchar(1) ,
 id int(11) ,
 nombre varchar(150) ,
 estado bigint(20) ,
 fecha_registro datetime ,
 fecha_transmision datetime ,
 categorias_id int(11) ,
 categoria varchar(45) ,
 gm1 int(33) ,
 gm1_nom varchar(150) ,
 gm2 int(33) ,
 gm2_nom varchar(150) ,
 gm3 int(33) ,
 gm3_nom varchar(150) ,
 tipo_grupo varchar(11) ,
 canales_id int(11) ,
 imagen varchar(150) ,
 procedencia int(11) 
);

/*Table structure for table default_vw_video */

DROP TABLE IF EXISTS default_vw_video;

 DROP VIEW IF EXISTS default_vw_video ;
 DROP TABLE IF EXISTS default_vw_video ;

 CREATE TABLE  default_vw_video(
 id int(11) ,
 canales_id int(11) ,
 nombre_canal varchar(128) ,
 categorias_id int(11) ,
 nombre_categoria varchar(45) ,
 titulo varchar(150) ,
 fecha_registro datetime ,
 fecha_transmision date ,
 estado tinyint(4) ,
 imagen varchar(150) ,
 primer_padre int(11) ,
 segundo_padre int(11) ,
 tercer_padre int(11) ,
 programa varchar(150) ,
 tematico varchar(341) ,
 personaje varchar(341) ,
 resumen text 
);

/*Table structure for table vi_estadisticas */

DROP TABLE IF EXISTS vi_estadisticas;

 DROP VIEW IF EXISTS vi_estadisticas ;
 DROP TABLE IF EXISTS vi_estadisticas ;

 CREATE TABLE  vi_estadisticas(
 id int(11) ,
 nombre varchar(150) ,
 ca_id int(11) ,
 cat_id int(11) ,
 pr_id bigint(11) ,
 reproducciones decimal(32,0) ,
 valorizacion decimal(32,0) ,
 comentarios decimal(32,0) 
);

/*Table structure for table vi_estadisticas_comentarios */

DROP TABLE IF EXISTS vi_estadisticas_comentarios;

 DROP VIEW IF EXISTS vi_estadisticas_comentarios ;
 DROP TABLE IF EXISTS vi_estadisticas_comentarios ;

 CREATE TABLE  vi_estadisticas_comentarios(
 id int(11) ,
 nombre varchar(150) ,
 ca_id int(11) ,
 cat_id int(11) ,
 pr_id bigint(11) ,
 comentarios decimal(32,0) 
);

/*Table structure for table vi_estadisticas_reproducciones */

DROP TABLE IF EXISTS vi_estadisticas_reproducciones;

 DROP VIEW IF EXISTS vi_estadisticas_reproducciones ;
 DROP TABLE IF EXISTS vi_estadisticas_reproducciones ;

 CREATE TABLE  vi_estadisticas_reproducciones(
 id int(11) ,
 nombre varchar(150) ,
 ca_id int(11) ,
 cat_id int(11) ,
 pr_id bigint(11) ,
 reproducciones decimal(32,0) 
);

/*Table structure for table vi_estadisticas_valorizacion */

DROP TABLE IF EXISTS vi_estadisticas_valorizacion;

 DROP VIEW IF EXISTS vi_estadisticas_valorizacion ;
 DROP TABLE IF EXISTS vi_estadisticas_valorizacion ;

 CREATE TABLE  vi_estadisticas_valorizacion(
 id int(11) ,
 nombre varchar(150) ,
 ca_id int(11) ,
 cat_id int(11) ,
 pr_id bigint(11) ,
 valorizacion decimal(32,0) 
);

/*View structure for view default_vw_coleccion */

 DROP TABLE IF EXISTS default_vw_coleccion ;
 DROP VIEW IF EXISTS default_vw_coleccion ;

 CREATE ALGORITHM=UNDEFINED  VIEW default_vw_coleccion AS select default_vw_maestros_videos.v AS v,default_vw_maestros_videos.id AS id,default_vw_maestros_videos.nombre AS nombre,default_vw_maestros_videos.estado AS estado,default_vw_maestros_videos.fecha_registro AS fecha_registro,default_vw_maestros_videos.fecha_transmision AS fecha_transmision,default_vw_maestros_videos.categorias_id AS categorias_id,default_vw_maestros_videos.categoria AS categoria,default_vw_maestros_videos.gm1 AS gm1,default_vw_maestros_videos.gm1_nom AS gm1_nom,default_vw_maestros_videos.gm2 AS gm2,default_vw_maestros_videos.gm2_nom AS gm2_nom,default_vw_maestros_videos.gm3 AS gm3,default_vw_maestros_videos.gm3_nom AS gm3_nom,default_vw_maestros_videos.tipo_grupo AS tipo_grupo,default_vw_maestros_videos.canales_id AS canales_id,default_vw_maestros_videos.imagen AS imagen,default_vw_maestros_videos.procedencia AS procedencia from default_vw_maestros_videos where ((isnull(default_vw_maestros_videos.gm1) and (default_vw_maestros_videos.gm2 is not null) and (default_vw_maestros_videos.gm3 is not null) and (default_vw_maestros_videos.tipo_grupo = 0)) or (isnull(default_vw_maestros_videos.gm1) and (default_vw_maestros_videos.gm2 is not null) and (default_vw_maestros_videos.gm3 is not null) and (default_vw_maestros_videos.tipo_grupo = 3)) or (isnull(default_vw_maestros_videos.gm1) and (default_vw_maestros_videos.gm2 is not null) and (default_vw_maestros_videos.gm3 is not null) and (default_vw_maestros_videos.tipo_grupo = 2)) or (isnull(default_vw_maestros_videos.gm1) and (default_vw_maestros_videos.gm2 is not null) and (default_vw_maestros_videos.gm3 is not null) and (default_vw_maestros_videos.tipo_grupo = 1))) order by default_vw_maestros_videos.tipo_grupo desc ;

/*View structure for view default_vw_lista */

 DROP TABLE IF EXISTS default_vw_lista ;
 DROP VIEW IF EXISTS default_vw_lista ;

 CREATE ALGORITHM=UNDEFINED  VIEW default_vw_lista AS select default_vw_maestros_videos.v AS v,default_vw_maestros_videos.id AS id,default_vw_maestros_videos.nombre AS nombre,default_vw_maestros_videos.estado AS estado,default_vw_maestros_videos.fecha_registro AS fecha_registro,default_vw_maestros_videos.fecha_transmision AS fecha_transmision,default_vw_maestros_videos.categorias_id AS categorias_id,default_vw_maestros_videos.categoria AS categoria,default_vw_maestros_videos.gm1 AS gm1,default_vw_maestros_videos.gm1_nom AS gm1_nom,default_vw_maestros_videos.gm2 AS gm2,default_vw_maestros_videos.gm2_nom AS gm2_nom,default_vw_maestros_videos.gm3 AS gm3,default_vw_maestros_videos.gm3_nom AS gm3_nom,default_vw_maestros_videos.tipo_grupo AS tipo_grupo,default_vw_maestros_videos.canales_id AS canales_id,default_vw_maestros_videos.imagen AS imagen,default_vw_maestros_videos.procedencia AS procedencia from default_vw_maestros_videos where (((default_vw_maestros_videos.gm1 is not null) and (default_vw_maestros_videos.gm2 is not null) and (default_vw_maestros_videos.gm3 is not null) and (default_vw_maestros_videos.tipo_grupo = 0)) or ((default_vw_maestros_videos.gm1 is not null) and (default_vw_maestros_videos.gm2 is not null) and (default_vw_maestros_videos.gm3 is not null) and (default_vw_maestros_videos.tipo_grupo = 3)) or ((default_vw_maestros_videos.gm1 is not null) and (default_vw_maestros_videos.gm2 is not null) and (default_vw_maestros_videos.gm3 is not null) and (default_vw_maestros_videos.tipo_grupo = 2)) or ((default_vw_maestros_videos.gm1 is not null) and (default_vw_maestros_videos.gm2 is not null) and (default_vw_maestros_videos.gm3 is not null) and (default_vw_maestros_videos.tipo_grupo = 1))) order by default_vw_maestros_videos.tipo_grupo desc ;

/*View structure for view default_vw_maestros */

 DROP TABLE IF EXISTS default_vw_maestros ;
 DROP VIEW IF EXISTS default_vw_maestros ;

 CREATE ALGORITHM=UNDEFINED  VIEW default_vw_maestros AS (select 'm' AS m,gm.id AS id,gm.nombre AS nombre,(gm.estado + 1) AS estado,gm.fecha_registro AS fecha_registro,gm.fecha_transmision_inicio AS fecha_transmision_inicio,gm.categorias_id AS categorias_id,(select default_cms_categorias.nombre from default_cms_categorias where (default_cms_categorias.id = gm.categorias_id)) AS categoria,(case when ((select gd1.tipo_grupo_maestros_id from default_cms_grupo_detalles gd1 where (gd1.grupo_maestro_id = gm.id)) = 1) then (select gd1.grupo_maestro_padre from default_cms_grupo_detalles gd1 where ((gd1.grupo_maestro_id = gm.id) and (gd1.tipo_grupo_maestros_id = 1))) else (select NULL) end) AS gm1,(case when ((select gd1.tipo_grupo_maestros_id from default_cms_grupo_detalles gd1 where (gd1.grupo_maestro_id = gm.id)) = 1) then (select default_cms_grupo_maestros.nombre from default_cms_grupo_maestros where (default_cms_grupo_maestros.id = (select gd1.grupo_maestro_padre from default_cms_grupo_detalles gd1 where ((gd1.grupo_maestro_id = gm.id) and (gd1.tipo_grupo_maestros_id = 1))))) else (select NULL) end) AS gm1_nom,(case when ((select gd1.tipo_grupo_maestros_id from default_cms_grupo_detalles gd1 where (gd1.grupo_maestro_id = gm.id)) = 2) then (select gd1.grupo_maestro_padre from default_cms_grupo_detalles gd1 where ((gd1.grupo_maestro_id = gm.id) and (gd1.tipo_grupo_maestros_id = 2))) when ((select gd2.tipo_grupo_maestros_id from default_cms_grupo_detalles gd2 where (gd2.grupo_maestro_id = (select gd3.grupo_maestro_padre from default_cms_grupo_detalles gd3 where (gd3.grupo_maestro_id = gm.id)))) = 2) then (select gd2.grupo_maestro_padre from default_cms_grupo_detalles gd2 where ((gd2.grupo_maestro_id = (select gd3.grupo_maestro_padre from default_cms_grupo_detalles gd3 where (gd3.grupo_maestro_id = gm.id))) and (gd2.tipo_grupo_maestros_id = 2))) else (select NULL) end) AS gm2,(case when ((select gd1.tipo_grupo_maestros_id from default_cms_grupo_detalles gd1 where (gd1.grupo_maestro_id = gm.id)) = 2) then (select default_cms_grupo_maestros.nombre from default_cms_grupo_maestros where (default_cms_grupo_maestros.id = (select gd1.grupo_maestro_padre from default_cms_grupo_detalles gd1 where ((gd1.grupo_maestro_id = gm.id) and (gd1.tipo_grupo_maestros_id = 2))))) when ((select gd2.tipo_grupo_maestros_id from default_cms_grupo_detalles gd2 where (gd2.grupo_maestro_id = (select gd3.grupo_maestro_padre from default_cms_grupo_detalles gd3 where (gd3.grupo_maestro_id = gm.id)))) = 2) then (select default_cms_grupo_maestros.nombre from default_cms_grupo_maestros where (default_cms_grupo_maestros.id = (select gd2.grupo_maestro_padre from default_cms_grupo_detalles gd2 where ((gd2.grupo_maestro_id = (select gd3.grupo_maestro_padre from default_cms_grupo_detalles gd3 where (gd3.grupo_maestro_id = gm.id))) and (gd2.tipo_grupo_maestros_id = 2))))) else (select NULL) end) AS gm2_nom,(case when ((select gd1.tipo_grupo_maestros_id from default_cms_grupo_detalles gd1 where (gd1.grupo_maestro_id = gm.id)) = 3) then (select gd1.grupo_maestro_padre from default_cms_grupo_detalles gd1 where ((gd1.grupo_maestro_id = gm.id) and (gd1.tipo_grupo_maestros_id = 3))) when ((select gd2.tipo_grupo_maestros_id from default_cms_grupo_detalles gd2 where (gd2.grupo_maestro_id = (select gd3.grupo_maestro_padre from default_cms_grupo_detalles gd3 where (gd3.grupo_maestro_id = gm.id)))) = 3) then (select gd2.grupo_maestro_padre from default_cms_grupo_detalles gd2 where ((gd2.grupo_maestro_id = (select gd3.grupo_maestro_padre from default_cms_grupo_detalles gd3 where (gd3.grupo_maestro_id = gm.id))) and (gd2.tipo_grupo_maestros_id = 3))) when ((select gd4.tipo_grupo_maestros_id from default_cms_grupo_detalles gd4 where (gd4.grupo_maestro_id = (select distinct gd5.grupo_maestro_padre from default_cms_grupo_detalles gd5 where (gd5.grupo_maestro_id = (select gd6.grupo_maestro_padre from default_cms_grupo_detalles gd6 where (gd6.grupo_maestro_id = gm.id)))))) = 3) then (select gd4.grupo_maestro_padre from default_cms_grupo_detalles gd4 where ((gd4.grupo_maestro_id = (select distinct gd5.grupo_maestro_padre from default_cms_grupo_detalles gd5 where (gd5.grupo_maestro_id = (select gd6.grupo_maestro_padre from default_cms_grupo_detalles gd6 where (gd6.grupo_maestro_id = gm.id))))) and (gd4.tipo_grupo_maestros_id = 3))) else (select NULL) end) AS gm3,(case when ((select gd1.tipo_grupo_maestros_id from default_cms_grupo_detalles gd1 where (gd1.grupo_maestro_id = gm.id)) = 3) then (select default_cms_grupo_maestros.nombre from default_cms_grupo_maestros where (default_cms_grupo_maestros.id = (select gd1.grupo_maestro_padre from default_cms_grupo_detalles gd1 where ((gd1.grupo_maestro_id = gm.id) and (gd1.tipo_grupo_maestros_id = 3))))) when ((select gd2.tipo_grupo_maestros_id from default_cms_grupo_detalles gd2 where (gd2.grupo_maestro_id = (select gd3.grupo_maestro_padre from default_cms_grupo_detalles gd3 where (gd3.grupo_maestro_id = gm.id)))) = 3) then (select default_cms_grupo_maestros.nombre from default_cms_grupo_maestros where (default_cms_grupo_maestros.id = (select gd2.grupo_maestro_padre from default_cms_grupo_detalles gd2 where ((gd2.grupo_maestro_id = (select gd3.grupo_maestro_padre from default_cms_grupo_detalles gd3 where (gd3.grupo_maestro_id = gm.id))) and (gd2.tipo_grupo_maestros_id = 3))))) when ((select gd4.tipo_grupo_maestros_id from default_cms_grupo_detalles gd4 where (gd4.grupo_maestro_id = (select distinct gd5.grupo_maestro_padre from default_cms_grupo_detalles gd5 where (gd5.grupo_maestro_id = (select gd6.grupo_maestro_padre from default_cms_grupo_detalles gd6 where (gd6.grupo_maestro_id = gm.id)))))) = 3) then (select default_cms_grupo_maestros.nombre from default_cms_grupo_maestros where (default_cms_grupo_maestros.id = (select gd4.grupo_maestro_padre from default_cms_grupo_detalles gd4 where ((gd4.grupo_maestro_id = (select distinct gd5.grupo_maestro_padre from default_cms_grupo_detalles gd5 where (gd5.grupo_maestro_id = (select gd6.grupo_maestro_padre from default_cms_grupo_detalles gd6 where (gd6.grupo_maestro_id = gm.id))))) and (gd4.tipo_grupo_maestros_id = 3))))) else (select NULL) end) AS gm3_nom,gm.tipo_grupo_maestro_id AS tipo_grupo,gm.canales_id AS canales_id from default_cms_grupo_maestros gm) ;

/*View structure for view default_vw_maestros_videos */

 DROP TABLE IF EXISTS default_vw_maestros_videos ;
 DROP VIEW IF EXISTS default_vw_maestros_videos ;

 CREATE ALGORITHM=UNDEFINED  VIEW default_vw_maestros_videos AS select 'v' AS v,vi.id AS id,vi.titulo AS nombre,vi.estado AS estado,vi.fecha_registro AS fecha_registro,vi.fecha_transmision AS fecha_transmision,vi.categorias_id AS categorias_id,(select default_cms_categorias.nombre from default_cms_categorias where (default_cms_categorias.id = vi.categorias_id)) AS categoria,(case when ((select gd1.tipo_grupo_maestros_id from default_cms_grupo_detalles gd1 where (gd1.video_id = vi.id)) = 1) then (select gd1.grupo_maestro_padre from default_cms_grupo_detalles gd1 where ((gd1.video_id = vi.id) and (gd1.tipo_grupo_maestros_id = 1))) else (select NULL) end) AS gm1,(case when ((select gd1.tipo_grupo_maestros_id from default_cms_grupo_detalles gd1 where (gd1.video_id = vi.id)) = 1) then (select default_cms_grupo_maestros.nombre from default_cms_grupo_maestros where (default_cms_grupo_maestros.id = (select gd1.grupo_maestro_padre from default_cms_grupo_detalles gd1 where ((gd1.video_id = vi.id) and (gd1.tipo_grupo_maestros_id = 1))))) else (select NULL) end) AS gm1_nom,(case when ((select gd1.tipo_grupo_maestros_id from default_cms_grupo_detalles gd1 where (gd1.video_id = vi.id)) = 2) then (select gd1.grupo_maestro_padre from default_cms_grupo_detalles gd1 where ((gd1.video_id = vi.id) and (gd1.tipo_grupo_maestros_id = 2))) when ((select gd2.tipo_grupo_maestros_id from default_cms_grupo_detalles gd2 where (gd2.grupo_maestro_id = (select gd3.grupo_maestro_padre from default_cms_grupo_detalles gd3 where (gd3.video_id = vi.id)))) = 2) then (select gd2.grupo_maestro_padre from default_cms_grupo_detalles gd2 where ((gd2.grupo_maestro_id = (select gd3.grupo_maestro_padre from default_cms_grupo_detalles gd3 where (gd3.video_id = vi.id))) and (gd2.tipo_grupo_maestros_id = 2))) else (select NULL) end) AS gm2,(case when ((select gd1.tipo_grupo_maestros_id from default_cms_grupo_detalles gd1 where (gd1.video_id = vi.id)) = 2) then (select default_cms_grupo_maestros.nombre from default_cms_grupo_maestros where (default_cms_grupo_maestros.id = (select gd1.grupo_maestro_padre from default_cms_grupo_detalles gd1 where ((gd1.video_id = vi.id) and (gd1.tipo_grupo_maestros_id = 2))))) when ((select gd2.tipo_grupo_maestros_id from default_cms_grupo_detalles gd2 where (gd2.grupo_maestro_id = (select gd3.grupo_maestro_padre from default_cms_grupo_detalles gd3 where (gd3.video_id = vi.id)))) = 2) then (select default_cms_grupo_maestros.nombre from default_cms_grupo_maestros where (default_cms_grupo_maestros.id = (select gd2.grupo_maestro_padre from default_cms_grupo_detalles gd2 where ((gd2.grupo_maestro_id = (select gd3.grupo_maestro_padre from default_cms_grupo_detalles gd3 where (gd3.video_id = vi.id))) and (gd2.tipo_grupo_maestros_id = 2))))) else (select NULL) end) AS gm2_nom,(case when ((select gd1.tipo_grupo_maestros_id from default_cms_grupo_detalles gd1 where (gd1.video_id = vi.id)) = 3) then (select gd1.grupo_maestro_padre from default_cms_grupo_detalles gd1 where ((gd1.video_id = vi.id) and (gd1.tipo_grupo_maestros_id = 3))) when ((select gd2.tipo_grupo_maestros_id from default_cms_grupo_detalles gd2 where (gd2.grupo_maestro_id = (select gd3.grupo_maestro_padre from default_cms_grupo_detalles gd3 where (gd3.video_id = vi.id)))) = 3) then (select gd2.grupo_maestro_padre from default_cms_grupo_detalles gd2 where ((gd2.grupo_maestro_id = (select gd3.grupo_maestro_padre from default_cms_grupo_detalles gd3 where (gd3.video_id = vi.id))) and (gd2.tipo_grupo_maestros_id = 3))) when ((select gd4.tipo_grupo_maestros_id from default_cms_grupo_detalles gd4 where (gd4.grupo_maestro_id = (select gd5.grupo_maestro_padre from default_cms_grupo_detalles gd5 where (gd5.grupo_maestro_id = (select gd6.grupo_maestro_padre from default_cms_grupo_detalles gd6 where (gd6.video_id = vi.id)))))) = 3) then (select gd4.grupo_maestro_padre from default_cms_grupo_detalles gd4 where ((gd4.grupo_maestro_id = (select gd5.grupo_maestro_padre from default_cms_grupo_detalles gd5 where (gd5.grupo_maestro_id = (select gd6.grupo_maestro_padre from default_cms_grupo_detalles gd6 where (gd6.video_id = vi.id))))) and (gd4.tipo_grupo_maestros_id = 3))) else (select NULL) end) AS gm3,(case when ((select gd1.tipo_grupo_maestros_id from default_cms_grupo_detalles gd1 where (gd1.video_id = vi.id)) = 3) then (select default_cms_grupo_maestros.nombre from default_cms_grupo_maestros where (default_cms_grupo_maestros.id = (select gd1.grupo_maestro_padre from default_cms_grupo_detalles gd1 where ((gd1.video_id = vi.id) and (gd1.tipo_grupo_maestros_id = 3))))) when ((select gd2.tipo_grupo_maestros_id from default_cms_grupo_detalles gd2 where (gd2.grupo_maestro_id = (select gd3.grupo_maestro_padre from default_cms_grupo_detalles gd3 where (gd3.video_id = vi.id)))) = 3) then (select default_cms_grupo_maestros.nombre from default_cms_grupo_maestros where (default_cms_grupo_maestros.id = (select gd2.grupo_maestro_padre from default_cms_grupo_detalles gd2 where ((gd2.grupo_maestro_id = (select gd3.grupo_maestro_padre from default_cms_grupo_detalles gd3 where (gd3.video_id = vi.id))) and (gd2.tipo_grupo_maestros_id = 3))))) when ((select gd4.tipo_grupo_maestros_id from default_cms_grupo_detalles gd4 where (gd4.grupo_maestro_id = (select gd5.grupo_maestro_padre from default_cms_grupo_detalles gd5 where (gd5.grupo_maestro_id = (select gd6.grupo_maestro_padre from default_cms_grupo_detalles gd6 where (gd6.video_id = vi.id)))))) = 3) then (select default_cms_grupo_maestros.nombre from default_cms_grupo_maestros where (default_cms_grupo_maestros.id = (select gd4.grupo_maestro_padre from default_cms_grupo_detalles gd4 where ((gd4.grupo_maestro_id = (select gd5.grupo_maestro_padre from default_cms_grupo_detalles gd5 where (gd5.grupo_maestro_id = (select gd6.grupo_maestro_padre from default_cms_grupo_detalles gd6 where (gd6.video_id = vi.id))))) and (gd4.tipo_grupo_maestros_id = 3))))) else (select NULL) end) AS gm3_nom,'0' AS tipo_grupo,vi.canales_id AS canales_id,(select img.imagen from default_cms_imagenes img where ((img.videos_id = vi.id) and (img.tipo_imagen_id = 1) and (img.estado = 1))) AS imagen,(select img.procedencia from default_cms_imagenes img where ((img.videos_id = vi.id) and (img.tipo_imagen_id = 1) and (img.estado = 1))) AS procedencia from default_cms_videos vi union all select 'm' AS m,gm.id AS id,gm.nombre AS nombre,(gm.estado + 1) AS estado,gm.fecha_registro AS fecha_registro,gm.fecha_transmision_inicio AS fecha_transmision_inicio,gm.categorias_id AS categorias_id,(select default_cms_categorias.nombre from default_cms_categorias where (default_cms_categorias.id = gm.categorias_id)) AS categoria,(case when ((select gd1.tipo_grupo_maestros_id from default_cms_grupo_detalles gd1 where (gd1.grupo_maestro_id = gm.id)) = 1) then (select gd1.grupo_maestro_padre from default_cms_grupo_detalles gd1 where ((gd1.grupo_maestro_id = gm.id) and (gd1.tipo_grupo_maestros_id = 1))) else (select NULL) end) AS gm1,(case when ((select gd1.tipo_grupo_maestros_id from default_cms_grupo_detalles gd1 where (gd1.grupo_maestro_id = gm.id)) = 1) then (select default_cms_grupo_maestros.nombre from default_cms_grupo_maestros where (default_cms_grupo_maestros.id = (select gd1.grupo_maestro_padre from default_cms_grupo_detalles gd1 where ((gd1.grupo_maestro_id = gm.id) and (gd1.tipo_grupo_maestros_id = 1))))) else (select NULL) end) AS gm1_nom,(case when ((select gd1.tipo_grupo_maestros_id from default_cms_grupo_detalles gd1 where (gd1.grupo_maestro_id = gm.id)) = 2) then (select gd1.grupo_maestro_padre from default_cms_grupo_detalles gd1 where ((gd1.grupo_maestro_id = gm.id) and (gd1.tipo_grupo_maestros_id = 2))) when ((select gd2.tipo_grupo_maestros_id from default_cms_grupo_detalles gd2 where (gd2.grupo_maestro_id = (select gd3.grupo_maestro_padre from default_cms_grupo_detalles gd3 where (gd3.grupo_maestro_id = gm.id)))) = 2) then (select gd2.grupo_maestro_padre from default_cms_grupo_detalles gd2 where ((gd2.grupo_maestro_id = (select gd3.grupo_maestro_padre from default_cms_grupo_detalles gd3 where (gd3.grupo_maestro_id = gm.id))) and (gd2.tipo_grupo_maestros_id = 2))) else (select NULL) end) AS gm2,(case when ((select gd1.tipo_grupo_maestros_id from default_cms_grupo_detalles gd1 where (gd1.grupo_maestro_id = gm.id)) = 2) then (select default_cms_grupo_maestros.nombre from default_cms_grupo_maestros where (default_cms_grupo_maestros.id = (select gd1.grupo_maestro_padre from default_cms_grupo_detalles gd1 where ((gd1.grupo_maestro_id = gm.id) and (gd1.tipo_grupo_maestros_id = 2))))) when ((select gd2.tipo_grupo_maestros_id from default_cms_grupo_detalles gd2 where (gd2.grupo_maestro_id = (select gd3.grupo_maestro_padre from default_cms_grupo_detalles gd3 where (gd3.grupo_maestro_id = gm.id)))) = 2) then (select default_cms_grupo_maestros.nombre from default_cms_grupo_maestros where (default_cms_grupo_maestros.id = (select gd2.grupo_maestro_padre from default_cms_grupo_detalles gd2 where ((gd2.grupo_maestro_id = (select gd3.grupo_maestro_padre from default_cms_grupo_detalles gd3 where (gd3.grupo_maestro_id = gm.id))) and (gd2.tipo_grupo_maestros_id = 2))))) else (select NULL) end) AS gm2_nom,(case when ((select gd1.tipo_grupo_maestros_id from default_cms_grupo_detalles gd1 where (gd1.grupo_maestro_id = gm.id)) = 3) then (select gd1.grupo_maestro_padre from default_cms_grupo_detalles gd1 where ((gd1.grupo_maestro_id = gm.id) and (gd1.tipo_grupo_maestros_id = 3))) when ((select gd2.tipo_grupo_maestros_id from default_cms_grupo_detalles gd2 where (gd2.grupo_maestro_id = (select gd3.grupo_maestro_padre from default_cms_grupo_detalles gd3 where (gd3.grupo_maestro_id = gm.id)))) = 3) then (select gd2.grupo_maestro_padre from default_cms_grupo_detalles gd2 where ((gd2.grupo_maestro_id = (select gd3.grupo_maestro_padre from default_cms_grupo_detalles gd3 where (gd3.grupo_maestro_id = gm.id))) and (gd2.tipo_grupo_maestros_id = 3))) when ((select gd4.tipo_grupo_maestros_id from default_cms_grupo_detalles gd4 where (gd4.grupo_maestro_id = (select distinct gd5.grupo_maestro_padre from default_cms_grupo_detalles gd5 where (gd5.grupo_maestro_id = (select gd6.grupo_maestro_padre from default_cms_grupo_detalles gd6 where (gd6.grupo_maestro_id = gm.id)))))) = 3) then (select gd4.grupo_maestro_padre from default_cms_grupo_detalles gd4 where ((gd4.grupo_maestro_id = (select distinct gd5.grupo_maestro_padre from default_cms_grupo_detalles gd5 where (gd5.grupo_maestro_id = (select gd6.grupo_maestro_padre from default_cms_grupo_detalles gd6 where (gd6.grupo_maestro_id = gm.id))))) and (gd4.tipo_grupo_maestros_id = 3))) else (select NULL) end) AS gm3,(case when ((select gd1.tipo_grupo_maestros_id from default_cms_grupo_detalles gd1 where (gd1.grupo_maestro_id = gm.id)) = 3) then (select default_cms_grupo_maestros.nombre from default_cms_grupo_maestros where (default_cms_grupo_maestros.id = (select gd1.grupo_maestro_padre from default_cms_grupo_detalles gd1 where ((gd1.grupo_maestro_id = gm.id) and (gd1.tipo_grupo_maestros_id = 3))))) when ((select gd2.tipo_grupo_maestros_id from default_cms_grupo_detalles gd2 where (gd2.grupo_maestro_id = (select gd3.grupo_maestro_padre from default_cms_grupo_detalles gd3 where (gd3.grupo_maestro_id = gm.id)))) = 3) then (select default_cms_grupo_maestros.nombre from default_cms_grupo_maestros where (default_cms_grupo_maestros.id = (select gd2.grupo_maestro_padre from default_cms_grupo_detalles gd2 where ((gd2.grupo_maestro_id = (select gd3.grupo_maestro_padre from default_cms_grupo_detalles gd3 where (gd3.grupo_maestro_id = gm.id))) and (gd2.tipo_grupo_maestros_id = 3))))) when ((select gd4.tipo_grupo_maestros_id from default_cms_grupo_detalles gd4 where (gd4.grupo_maestro_id = (select distinct gd5.grupo_maestro_padre from default_cms_grupo_detalles gd5 where (gd5.grupo_maestro_id = (select gd6.grupo_maestro_padre from default_cms_grupo_detalles gd6 where (gd6.grupo_maestro_id = gm.id)))))) = 3) then (select default_cms_grupo_maestros.nombre from default_cms_grupo_maestros where (default_cms_grupo_maestros.id = (select gd4.grupo_maestro_padre from default_cms_grupo_detalles gd4 where ((gd4.grupo_maestro_id = (select distinct gd5.grupo_maestro_padre from default_cms_grupo_detalles gd5 where (gd5.grupo_maestro_id = (select gd6.grupo_maestro_padre from default_cms_grupo_detalles gd6 where (gd6.grupo_maestro_id = gm.id))))) and (gd4.tipo_grupo_maestros_id = 3))))) else (select NULL) end) AS gm3_nom,gm.tipo_grupo_maestro_id AS tipo_grupo,gm.canales_id AS canales_id,(select img.imagen from default_cms_imagenes img where ((img.grupo_maestros_id = gm.id) and (img.tipo_imagen_id = 1) and (img.estado = 1))) AS imagen,(select img.procedencia from default_cms_imagenes img where ((img.grupo_maestros_id = gm.id) and (img.tipo_imagen_id = 1) and (img.estado = 1))) AS procedencia from default_cms_grupo_maestros gm ;

/*View structure for view default_vw_organizar */

 DROP TABLE IF EXISTS default_vw_organizar ;
 DROP VIEW IF EXISTS default_vw_organizar ;

 CREATE ALGORITHM=UNDEFINED  VIEW default_vw_organizar AS select default_vw_maestros_videos.v AS v,default_vw_maestros_videos.id AS id,default_vw_maestros_videos.nombre AS nombre,default_vw_maestros_videos.estado AS estado,default_vw_maestros_videos.fecha_registro AS fecha_registro,default_vw_maestros_videos.fecha_transmision AS fecha_transmision,default_vw_maestros_videos.categorias_id AS categorias_id,default_vw_maestros_videos.categoria AS categoria,default_vw_maestros_videos.gm1 AS gm1,default_vw_maestros_videos.gm1_nom AS gm1_nom,default_vw_maestros_videos.gm2 AS gm2,default_vw_maestros_videos.gm2_nom AS gm2_nom,default_vw_maestros_videos.gm3 AS gm3,default_vw_maestros_videos.gm3_nom AS gm3_nom,default_vw_maestros_videos.tipo_grupo AS tipo_grupo,default_vw_maestros_videos.canales_id AS canales_id,default_vw_maestros_videos.imagen AS imagen,default_vw_maestros_videos.procedencia AS procedencia from default_vw_maestros_videos where ((isnull(default_vw_maestros_videos.gm1) and isnull(default_vw_maestros_videos.gm2) and isnull(default_vw_maestros_videos.gm3) and (default_vw_maestros_videos.tipo_grupo = 0)) or (isnull(default_vw_maestros_videos.gm1) and isnull(default_vw_maestros_videos.gm2) and isnull(default_vw_maestros_videos.gm3) and (default_vw_maestros_videos.tipo_grupo = 3)) or (isnull(default_vw_maestros_videos.gm1) and isnull(default_vw_maestros_videos.gm2) and isnull(default_vw_maestros_videos.gm3) and (default_vw_maestros_videos.tipo_grupo = 2)) or (isnull(default_vw_maestros_videos.gm1) and isnull(default_vw_maestros_videos.gm2) and isnull(default_vw_maestros_videos.gm3) and (default_vw_maestros_videos.tipo_grupo = 1))) order by default_vw_maestros_videos.tipo_grupo desc ;

/*View structure for view default_vw_papelera */

 DROP TABLE IF EXISTS default_vw_papelera ;
 DROP VIEW IF EXISTS default_vw_papelera ;

 CREATE ALGORITHM=UNDEFINED  VIEW default_vw_papelera AS select 'maestro' AS maestros,gm.id AS id,(gm.estado + 1) AS estado,gm.nombre AS titulo,gm.tipo_grupo_maestro_id AS tipo_maestro,gm.canales_id AS canales_id,gm.fecha_registro AS fecha_registro from default_cms_grupo_maestros gm union select 'video' AS videos,vi.id AS id,vi.estado AS estado,vi.titulo AS titulo,'4' AS tipo_maestro,vi.canales_id AS canales_id,vi.fecha_registro AS fecha_registro from default_cms_videos vi ;

/*View structure for view default_vw_programa */

 DROP TABLE IF EXISTS default_vw_programa ;
 DROP VIEW IF EXISTS default_vw_programa ;

 CREATE ALGORITHM=UNDEFINED  VIEW default_vw_programa AS select default_vw_maestros_videos.v AS v,default_vw_maestros_videos.id AS id,default_vw_maestros_videos.nombre AS nombre,default_vw_maestros_videos.estado AS estado,default_vw_maestros_videos.fecha_registro AS fecha_registro,default_vw_maestros_videos.fecha_transmision AS fecha_transmision,default_vw_maestros_videos.categorias_id AS categorias_id,default_vw_maestros_videos.categoria AS categoria,default_vw_maestros_videos.gm1 AS gm1,default_vw_maestros_videos.gm1_nom AS gm1_nom,default_vw_maestros_videos.gm2 AS gm2,default_vw_maestros_videos.gm2_nom AS gm2_nom,default_vw_maestros_videos.gm3 AS gm3,default_vw_maestros_videos.gm3_nom AS gm3_nom,default_vw_maestros_videos.tipo_grupo AS tipo_grupo,default_vw_maestros_videos.canales_id AS canales_id,default_vw_maestros_videos.imagen AS imagen,default_vw_maestros_videos.procedencia AS procedencia from default_vw_maestros_videos where ((isnull(default_vw_maestros_videos.gm1) and isnull(default_vw_maestros_videos.gm2) and (default_vw_maestros_videos.gm3 is not null) and (default_vw_maestros_videos.tipo_grupo = 0)) or (isnull(default_vw_maestros_videos.gm1) and isnull(default_vw_maestros_videos.gm2) and (default_vw_maestros_videos.gm3 is not null) and (default_vw_maestros_videos.tipo_grupo = 3)) or (isnull(default_vw_maestros_videos.gm1) and isnull(default_vw_maestros_videos.gm2) and (default_vw_maestros_videos.gm3 is not null) and (default_vw_maestros_videos.tipo_grupo = 2)) or (isnull(default_vw_maestros_videos.gm1) and isnull(default_vw_maestros_videos.gm2) and (default_vw_maestros_videos.gm3 is not null) and (default_vw_maestros_videos.tipo_grupo = 1))) order by default_vw_maestros_videos.tipo_grupo desc ;

/*View structure for view default_vw_video */

 DROP TABLE IF EXISTS default_vw_video ;
 DROP VIEW IF EXISTS default_vw_video ;

 CREATE ALGORITHM=UNDEFINED  VIEW default_vw_video AS select distinct v.id AS id,v.canales_id AS canales_id,c.nombre AS nombre_canal,v.categorias_id AS categorias_id,cat.nombre AS nombre_categoria,v.titulo AS titulo,v.fecha_registro AS fecha_registro,v.fecha_transmision AS fecha_transmision,v.estado AS estado,img.imagen AS imagen,gd.grupo_maestro_padre AS primer_padre,gd2.grupo_maestro_padre AS segundo_padre,gd3.grupo_maestro_padre AS tercer_padre,gm.nombre AS programa,(select group_concat(t.nombre separator ',') from (((default_cms_videos vi join default_cms_video_tags vt on((vt.videos_id = vi.id))) join default_cms_tags t on((vt.tags_id = t.id))) join default_cms_tipo_tags tt on((tt.id = t.tipo_tags_id))) where ((tt.id = '1') and (vi.id = v.id))) AS tematico,(select group_concat(t.nombre separator ',') from (((default_cms_videos vi join default_cms_video_tags vt on((vt.videos_id = vi.id))) join default_cms_tags t on((vt.tags_id = t.id))) join default_cms_tipo_tags tt on((tt.id = t.tipo_tags_id))) where ((tt.id = '2') and (vi.id = v.id))) AS personaje,concat(c.nombre,' ',cat.nombre,' ',gm.nombre,' ',gc.nombre,' ',gl.nombre,' ',v.titulo) AS resumen from (((((((((default_cms_videos v left join default_cms_canales c on((c.id = v.canales_id))) left join default_cms_categorias cat on((cat.id = v.categorias_id))) left join default_cms_imagenes img on((img.videos_id = v.id))) left join default_cms_grupo_detalles gd on((gd.video_id = v.id))) left join default_cms_grupo_detalles gd2 on((gd2.grupo_maestro_id = gd.grupo_maestro_padre))) left join default_cms_grupo_detalles gd3 on((gd3.grupo_maestro_id = gd2.grupo_maestro_padre))) left join default_cms_grupo_maestros gm on((gm.id = gd3.grupo_maestro_padre))) left join default_cms_grupo_maestros gc on((gc.id = gd2.grupo_maestro_padre))) left join default_cms_grupo_maestros gl on((gl.id = gd.grupo_maestro_padre))) where (((img.tipo_imagen_id in (1,6)) or isnull(img.tipo_imagen_id)) and ((img.estado = 1) or isnull(img.estado))) order by v.id ;

/*View structure for view vi_estadisticas */

 DROP TABLE IF EXISTS vi_estadisticas ;
 DROP VIEW IF EXISTS vi_estadisticas ;

 CREATE ALGORITHM=UNDEFINED  VIEW vi_estadisticas AS (select gm.id AS id,gm.nombre AS nombre,ca.id AS ca_id,gm.categorias_id AS cat_id,(select gm3.id from default_cms_grupo_maestros gm3 where (gm3.id = (select gd4.grupo_maestro_padre from default_cms_grupo_detalles gd4 where (gd4.grupo_maestro_id = (select gd5.grupo_maestro_padre from default_cms_grupo_detalles gd5 where (gd5.grupo_maestro_id = gm.id)))))) AS pr_id,(select sum(vi.reproducciones) from default_cms_videos vi where vi.id in (select gd2.video_id from default_cms_grupo_detalles gd2 where (gd2.grupo_maestro_padre = gm.id))) AS reproducciones,(select sum(vi.valorizacion) from default_cms_videos vi where vi.id in (select gd2.video_id from default_cms_grupo_detalles gd2 where (gd2.grupo_maestro_padre = gm.id))) AS valorizacion,(select sum(vi.comentarios) from default_cms_videos vi where vi.id in (select gd2.video_id from default_cms_grupo_detalles gd2 where (gd2.grupo_maestro_padre = gm.id))) AS comentarios from ((default_cms_grupo_maestros gm join default_cms_grupo_detalles gd on((gd.grupo_maestro_padre = gm.id))) join default_cms_canales ca on((gm.canales_id = ca.id))) where (gm.tipo_grupo_maestro_id = 1) group by gm.id) ;

/*View structure for view vi_estadisticas_comentarios */

 DROP TABLE IF EXISTS vi_estadisticas_comentarios ;
 DROP VIEW IF EXISTS vi_estadisticas_comentarios ;

 CREATE ALGORITHM=UNDEFINED  VIEW vi_estadisticas_comentarios AS (select gm.id AS id,gm.nombre AS nombre,ca.id AS ca_id,gm.categorias_id AS cat_id,(select gm3.id from default_cms_grupo_maestros gm3 where (gm3.id = (select gd4.grupo_maestro_padre from default_cms_grupo_detalles gd4 where (gd4.grupo_maestro_id = (select gd5.grupo_maestro_padre from default_cms_grupo_detalles gd5 where (gd5.grupo_maestro_id = gm.id)))))) AS pr_id,(select sum(vi.comentarios) from default_cms_videos vi where vi.id in (select gd2.video_id from default_cms_grupo_detalles gd2 where (gd2.grupo_maestro_padre = gm.id))) AS comentarios from ((default_cms_grupo_maestros gm join default_cms_grupo_detalles gd on((gd.grupo_maestro_padre = gm.id))) join default_cms_canales ca on((gm.canales_id = ca.id))) where (gm.tipo_grupo_maestro_id = 1) group by gm.id) ;

/*View structure for view vi_estadisticas_reproducciones */

 DROP TABLE IF EXISTS vi_estadisticas_reproducciones ;
 DROP VIEW IF EXISTS vi_estadisticas_reproducciones ;

 CREATE ALGORITHM=UNDEFINED  VIEW vi_estadisticas_reproducciones AS (select gm.id AS id,gm.nombre AS nombre,ca.id AS ca_id,gm.categorias_id AS cat_id,(select gm3.id from default_cms_grupo_maestros gm3 where (gm3.id = (select gd4.grupo_maestro_padre from default_cms_grupo_detalles gd4 where (gd4.grupo_maestro_id = (select gd5.grupo_maestro_padre from default_cms_grupo_detalles gd5 where (gd5.grupo_maestro_id = gm.id)))))) AS pr_id,(select sum(vi.reproducciones) from default_cms_videos vi where vi.id in (select gd2.video_id from default_cms_grupo_detalles gd2 where (gd2.grupo_maestro_padre = gm.id))) AS reproducciones from ((default_cms_grupo_maestros gm join default_cms_grupo_detalles gd on((gd.grupo_maestro_padre = gm.id))) join default_cms_canales ca on((gm.canales_id = ca.id))) where (gm.tipo_grupo_maestro_id = 1) group by gm.id) ;

/*View structure for view vi_estadisticas_valorizacion */

 DROP TABLE IF EXISTS vi_estadisticas_valorizacion ;
 DROP VIEW IF EXISTS vi_estadisticas_valorizacion ;

 CREATE ALGORITHM=UNDEFINED  VIEW vi_estadisticas_valorizacion AS (select gm.id AS id,gm.nombre AS nombre,ca.id AS ca_id,gm.categorias_id AS cat_id,(select gm3.id from default_cms_grupo_maestros gm3 where (gm3.id = (select gd4.grupo_maestro_padre from default_cms_grupo_detalles gd4 where (gd4.grupo_maestro_id = (select gd5.grupo_maestro_padre from default_cms_grupo_detalles gd5 where (gd5.grupo_maestro_id = gm.id)))))) AS pr_id,(select sum(vi.valorizacion) from default_cms_videos vi where vi.id in (select gd2.video_id from default_cms_grupo_detalles gd2 where (gd2.grupo_maestro_padre = gm.id))) AS valorizacion from ((default_cms_grupo_maestros gm join default_cms_grupo_detalles gd on((gd.grupo_maestro_padre = gm.id))) join default_cms_canales ca on((gm.canales_id = ca.id))) where (gm.tipo_grupo_maestro_id = 1) group by gm.id) ;

