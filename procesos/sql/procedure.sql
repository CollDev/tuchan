 DROP PROCEDURE IF EXISTS  sp_llenartiposeccion6789 ;

DELIMITER $$

 CREATE  PROCEDURE sp_llenartiposeccion6789()
BEGIN
    
	declare xid int;
	declare xportadas_id int;
	declare xtipo_secciones_id int;
	declare xcanales_id INT;
	declare xtipo_portadas_id INT;
	declare xorigen_id int;
	declare cant int;
	declare canttemp int;
	declare idgm int;
	declare xrownum int;
	drop table if exists t_estadistica_reproducciones;
	CREATE TEMPORARY TABLE t_estadistica_reproducciones
	SELECT vid.id AS 'vi_id',ima.id AS 'im_id',categorias_id,vid.canales_id,vid.reproducciones,
  (SELECT gde1.grupo_maestro_padre FROM default_cms_grupo_detalles gde1 WHERE gde1.grupo_maestro_id=(SELECT gde1.grupo_maestro_padre FROM default_cms_grupo_detalles gde1 WHERE gde1.grupo_maestro_id=(SELECT gde2.grupo_maestro_padre FROM default_cms_grupo_detalles gde2 WHERE gde2.video_id=vid.id))) as 'programa_id'
	FROM default_cms_videos vid
	INNER JOIN default_cms_grupo_detalles gde ON gde.video_id = vid.id
	INNER JOIN default_cms_imagenes ima ON ima.videos_id=vid.id
	WHERE  vid.reproducciones!=0     and vid.estado=2
	GROUP BY gde.grupo_maestro_padre
	ORDER BY vid.reproducciones DESC ;
	drop table if exists t_estadistica_valorizacion;
	CREATE TEMPORARY TABLE t_estadistica_valorizacion
	SELECT vid.id AS 'vi_id',ima.id AS 'im_id',categorias_id,vid.canales_id,vid.valorizacion,
(SELECT gde1.grupo_maestro_padre FROM default_cms_grupo_detalles gde1 WHERE gde1.grupo_maestro_id=(SELECT gde1.grupo_maestro_padre FROM default_cms_grupo_detalles gde1 WHERE gde1.grupo_maestro_id=(SELECT gde2.grupo_maestro_padre FROM default_cms_grupo_detalles gde2 WHERE gde2.video_id=vid.id))) as 'programa_id'
	FROM default_cms_videos vid
	INNER JOIN default_cms_grupo_detalles gde ON gde.video_id = vid.id
	INNER JOIN default_cms_imagenes ima ON ima.videos_id=vid.id
	WHERE  vid.valorizacion!=0    and vid.estado=2
	GROUP BY gde.grupo_maestro_padre
	ORDER BY vid.valorizacion DESC ;
	drop table if exists t_estadistica_comentarios;
	CREATE TEMPORARY TABLE t_estadistica_comentarios
	SELECT vid.id AS 'vi_id',ima.id AS 'im_id',categorias_id,vid.canales_id,vid.comentarios,
(SELECT gde1.grupo_maestro_padre FROM default_cms_grupo_detalles gde1 WHERE gde1.grupo_maestro_id=(SELECT gde1.grupo_maestro_padre FROM default_cms_grupo_detalles gde1 WHERE gde1.grupo_maestro_id=(SELECT gde2.grupo_maestro_padre FROM default_cms_grupo_detalles gde2 WHERE gde2.video_id=vid.id))) as 'programa_id'
	FROM default_cms_videos vid
	INNER JOIN default_cms_grupo_detalles gde ON gde.video_id = vid.id
	INNER JOIN default_cms_imagenes ima ON ima.videos_id=vid.id
	WHERE  vid.comentarios!=0    and vid.estado=2
	GROUP BY gde.grupo_maestro_padre
	ORDER BY vid.comentarios DESC ;
	drop table if exists t_estadistica_fecha_transmision;
	CREATE TEMPORARY TABLE t_estadistica_fecha_transmision
	SELECT vid.id AS 'vi_id',ima.id AS 'im_id',categorias_id,vid.canales_id,(SELECT gde1.grupo_maestro_padre FROM default_cms_grupo_detalles gde1
	WHERE gde1.grupo_maestro_id=(SELECT gde1.grupo_maestro_padre FROM default_cms_grupo_detalles gde1
	WHERE gde1.grupo_maestro_id=(SELECT gde2.grupo_maestro_padre FROM default_cms_grupo_detalles gde2 WHERE gde2.video_id=vid.id))) AS 'programa_id',
	fecha_transmision
	FROM default_cms_videos vid
	INNER JOIN default_cms_grupo_detalles gde ON gde.video_id = vid.id
	INNER JOIN default_cms_imagenes ima ON ima.videos_id=vid.id
	WHERE  vid.estado=2 -- vid.reproducciones!=0    
	GROUP BY gde.grupo_maestro_padre
	ORDER BY fecha_transmision DESC 
	LIMIT 200;
	
	DROP TABLE IF EXISTS tempsecciones;
	CREATE TEMPORARY TABLE tempsecciones
 SELECT id,portadas_id,tipo_secciones_id FROM default_cms_secciones WHERE tipo=0 AND tipo_secciones_id IN (6,7,8,9) and estado  =1 ;
SELECT COUNT(*)  INTO cant  FROM tempsecciones ;
 
if cant>0 then
    
    loop_secciones: LOOP    
 
					SELECT id,portadas_id,tipo_secciones_id INTO xid,xportadas_id,xtipo_secciones_id FROM tempsecciones LIMIT 1;							
					IF   0 = cant THEN
						LEAVE loop_secciones;
					END IF ;
							
					SELECT canales_id,tipo_portadas_id,origen_id  
					into xcanales_id,xtipo_portadas_id,xorigen_id
					FROM default_cms_portadas WHERE id=xportadas_id;
					
 
					-- update default_cms_detalle_secciones set estado=0 where secciones_id=xid and estado=1;
					delete from default_cms_detalle_secciones where secciones_id=xid;
					 
					set @rownum=0;
					set canttemp=0;	
	if  xtipo_secciones_id= 6 THEN 
				if xtipo_portadas_id =1 then		
								
   						  insert  into default_cms_detalle_secciones (secciones_id,videos_id,imagenes_id,peso,fecha_registro,estado,estado_migracion)
								SELECT xid,vi_id,im_id,@rownum:=@rownum+1 AS rownum,NOW(),1,0 FROM t_estadistica_reproducciones ter 
								ORDER BY ter.reproducciones DESC 
								LIMIT 50;
	
				elseif xtipo_portadas_id = 2 then
					
								insert  into default_cms_detalle_secciones (secciones_id,videos_id,imagenes_id,peso,fecha_registro,estado,estado_migracion)
								SELECT xid,vi_id,im_id,@rownum:=@rownum+1 AS rownum,NOW(),1,0 FROM t_estadistica_reproducciones ter 
								WHERE  categorias_id=xorigen_id 
								ORDER BY ter.reproducciones DESC 
								LIMIT 50;
				elseif xtipo_portadas_id = 4 then
												
								insert  into default_cms_detalle_secciones (secciones_id,videos_id,imagenes_id,peso,fecha_registro,estado,estado_migracion)
								SELECT xid,vi_id,im_id,@rownum:=@rownum+1 AS rownum,NOW(),1,0 FROM t_estadistica_reproducciones ter 
								WHERE  programa_id=xorigen_id 
								ORDER BY ter.reproducciones DESC 
								LIMIT 50;
				elseif 	xtipo_portadas_id =5 then
								insert  into default_cms_detalle_secciones (secciones_id,videos_id,imagenes_id,peso,fecha_registro,estado,estado_migracion)
								SELECT xid,vi_id,im_id,@rownum:=@rownum+1 AS rownum,NOW(),1,0 FROM t_estadistica_reproducciones ter 
								WHERE  canales_id=xorigen_id 
								ORDER BY ter.reproducciones DESC 
								LIMIT 50;
				end if;	
elseif  xtipo_secciones_id= 7 THEN 
						
			if xtipo_portadas_id =1 then
								insert  into default_cms_detalle_secciones (secciones_id,videos_id,imagenes_id,peso,fecha_registro,estado,estado_migracion)
								SELECT xid,vi_id,im_id,@rownum:=@rownum+1 AS rownum,NOW(),1,0 FROM t_estadistica_comentarios tec
								ORDER BY tec.comentarios DESC 
								LIMIT 50;
				elseif xtipo_portadas_id = 2 then
					
								insert  into default_cms_detalle_secciones (secciones_id,videos_id,imagenes_id,peso,fecha_registro,estado,estado_migracion)
								SELECT xid,vi_id,im_id,@rownum:=@rownum+1 AS rownum,NOW(),1,0 FROM t_estadistica_comentarios tec
								WHERE  categorias_id=xorigen_id 
								ORDER BY tec.comentarios DESC 
								LIMIT 50;
				elseif xtipo_portadas_id = 4 then
					
								insert  into default_cms_detalle_secciones (secciones_id,videos_id,imagenes_id,peso,fecha_registro,estado,estado_migracion)
								SELECT xid,vi_id,im_id,@rownum:=@rownum+1 AS rownum,NOW(),1,0 FROM t_estadistica_comentarios tec 
								WHERE  programa_id=xorigen_id 
								ORDER BY tec.comentarios DESC 
								LIMIT 50;
		
				elseif 	xtipo_portadas_id =5 then
								insert  into default_cms_detalle_secciones (secciones_id,videos_id,imagenes_id,peso,fecha_registro,estado,estado_migracion)
								SELECT xid,vi_id,im_id,@rownum:=@rownum+1 AS rownum,NOW(),1,0 FROM t_estadistica_comentarios tec
								WHERE  canales_id=xorigen_id 
								ORDER BY tec.comentarios DESC 
								LIMIT 50;
				end if;	
elseif  xtipo_secciones_id= 8 THEN 
				if xtipo_portadas_id =1 then
					
								insert  into default_cms_detalle_secciones (secciones_id,videos_id,imagenes_id,peso,fecha_registro,estado,estado_migracion)
								SELECT xid,vi_id,im_id,@rownum:=@rownum+1 AS rownum,NOW(),1,0 FROM t_estadistica_valorizacion tev
								ORDER BY tev.valorizacion DESC 
								LIMIT 50;
				elseif xtipo_portadas_id = 2 then
				
								insert  into default_cms_detalle_secciones (secciones_id,videos_id,imagenes_id,peso,fecha_registro,estado,estado_migracion)
								SELECT xid,vi_id,im_id,@rownum:=@rownum+1 AS rownum,NOW(),1,0 FROM t_estadistica_valorizacion tev
								WHERE categorias_id=xorigen_id 
								ORDER BY tev.valorizacion DESC 
								LIMIT 50;
				elseif xtipo_portadas_id = 4 then
					
								insert  into default_cms_detalle_secciones (secciones_id,videos_id,imagenes_id,peso,fecha_registro,estado,estado_migracion)
								SELECT xid,vi_id,im_id,@rownum:=@rownum+1 AS rownum,NOW(),1,0 FROM t_estadistica_valorizacion tev
								WHERE  programa_id=xorigen_id 
								ORDER BY tev.valorizacion DESC 
								LIMIT 50;
		
				elseif 	xtipo_portadas_id =5 then
								insert  into default_cms_detalle_secciones (secciones_id,videos_id,imagenes_id,peso,fecha_registro,estado,estado_migracion)
								SELECT xid,vi_id,im_id,@rownum:=@rownum+1 AS rownum,NOW(),1,0 FROM t_estadistica_valorizacion tev
								WHERE  canales_id=xorigen_id 
								ORDER BY tev.valorizacion DESC 
								LIMIT 50;
				end if;	
elseif  xtipo_secciones_id= 9 THEN 
			if xtipo_portadas_id =1 then
					
								insert  into default_cms_detalle_secciones (secciones_id,videos_id,imagenes_id,peso,fecha_registro,estado,estado_migracion)
								SELECT xid,vi_id,im_id,@rownum:=@rownum+1 AS rownum,NOW(),1,0 FROM t_estadistica_fecha_transmision tef
								ORDER BY tef.fecha_transmision DESC 
								LIMIT 50;
				elseif xtipo_portadas_id = 2 then
				
								insert  into default_cms_detalle_secciones (secciones_id,videos_id,imagenes_id,peso,fecha_registro,estado,estado_migracion)
								SELECT xid,vi_id,im_id,@rownum:=@rownum+1 AS rownum,NOW(),1,0 FROM  t_estadistica_fecha_transmision tef
								WHERE categorias_id=xorigen_id 
								ORDER BY tef.fecha_transmision DESC 
								LIMIT 50;
				elseif xtipo_portadas_id = 4 then
					
								insert  into default_cms_detalle_secciones (secciones_id,videos_id,imagenes_id,peso,fecha_registro,estado,estado_migracion)
								SELECT xid,vi_id,im_id,@rownum:=@rownum+1 AS rownum,NOW(),1,0 FROM  t_estadistica_fecha_transmision tef
								WHERE  programa_id=xorigen_id 
								ORDER BY tef.fecha_transmision DESC 
								LIMIT 50;
		
				elseif 	xtipo_portadas_id =5 then
								insert  into default_cms_detalle_secciones (secciones_id,videos_id,imagenes_id,peso,fecha_registro,estado,estado_migracion)
								SELECT xid,vi_id,im_id,@rownum:=@rownum+1 AS rownum,NOW(),1,0 FROM  t_estadistica_fecha_transmision tef
								WHERE  canales_id=xorigen_id 
								ORDER BY tef.fecha_transmision DESC  
								LIMIT 50;
				end if;	
end if;
			 select  count(*) into canttemp from default_cms_detalle_secciones  where secciones_id=xid and estado=1;  
				if  canttemp<> 0 then 
						update default_cms_secciones set estado=1 where id=xid;
					else
						update default_cms_secciones set estado=0 where id=xid;
				end if;
		delete from tempsecciones where id=xid;
		set cant = cant -1;		
     end loop loop_secciones;
	
END IF;    
	
    END  $$
DELIMITER ;

/* Procedure structure for procedure sp_nombresColeccion */

 DROP PROCEDURE IF EXISTS  sp_nombresColeccion ;

DELIMITER $$

 CREATE  PROCEDURE sp_nombresColeccion()
BEGIN
    
	declare xid int;
declare cant int;
	declare xnombre varchar(50);
	drop table if exists t_secciones;
	CREATE TEMPORARY TABLE t_secciones
	SELECT  id,nombre FROM default_cms_secciones WHERE nombre="Coleccion" AND estado=1;
	SELECT COUNT(*) INTO cant  FROM t_secciones ;
 
if cant>0 then
    
    loop_secciones: LOOP    
    
  
					SELECT id iNTO xid  FROM t_secciones LIMIT 1;
						SELECT nombre into xnombre FROM default_cms_grupo_maestros gm  WHERE id = (SELECT grupo_maestros_id FROM default_cms_detalle_secciones ds WHERE secciones_id=xid ORDER BY peso ASC LIMIT 1);
					update default_cms_secciones set nombre = xnombre where id = xid;
							
					IF   0 = cant THEN
						LEAVE loop_secciones;
					END IF ;
					delete from t_secciones where id=xid;
					set cant = cant -1;	
					end loop loop_secciones;
	
END IF;    
	
    END  $$
DELIMITER ;

/* Procedure structure for procedure sp_obtenerdatos */

 DROP PROCEDURE IF EXISTS  sp_obtenerdatos ;

DELIMITER $$

 CREATE  PROCEDURE sp_obtenerdatos(in xopt int, in xid int)
BEGIN

declare xcategoria varchar(100);

declare xcanal varchar(100);

declare xcanalalias varchar(100);

declare xplayerkey varchar(100);

declare xapikey varchar(100);

declare xprograma varchar(100);

declare xprogramaalias varchar(100);

declare xcoleccion varchar(100);

declare xcoleccionalias varchar(100);

declare xlistareproduccion varchar(100);

declare xlistareproduccionalias varchar(100);

declare xvideo varchar(100);

declare xvideoalias varchar(100);

declare xdescripcion varchar(100);

declare xfechatransmision varchar(20);

declare xduracion varchar(20);

declare xcodigo varchar(50);

DECLARE xco VARCHAR(50);

DECLARE xnco VARCHAR(50);

DECLARE xlr VARCHAR(50);

DECLARE xnlr VARCHAR(50);

DECLARE xvi VARCHAR(50);

declare tint1  int;

declare tint2 int;

declare tint3 int;

declare tint4 int;

declare tint5 int;

declare tint6 int;

declare tint7 int;

declare tint8 int;

declare tvar1 varchar (50); 

declare tvar2 varchar (50); 

declare tvar3 varchar (50); 

declare tvar4 varchar (50); 

declare xvi_rep int ;

declare xvi_com int ;

declare xvi_val int ;

declare xidlr int;



	if xopt=1 then 

	

		SELECT  nombre,alias,descripcion,tipo_grupo_maestro_id,canales_id,categorias_id 	

		into 	tvar1,tvar2,tvar3,tint1,tint2,tint3	

		FROM default_cms_grupo_maestros WHERE id = xid ;

			if tint2 is not null then

				SELECT nombre,alias  into xcanal,xcanalalias FROM default_cms_canales where id=tint2;			

			end if;

			if tint3 is not null then

				SELECT alias  into xcategoria FROM default_cms_categorias  where id=tint3;

			end if;

		if tint1= 3 then 

				set xprograma = tvar1;

				set xprogramaalias	 = tvar2;

			

				-- select fu_ultimovideoprograma(xid) into xvi;

						CALL sp_ultimovideoprograma(xid,xvi); 

		

				select nombre,alias 

				into xlistareproduccion,xlistareproduccionalias

				from default_cms_grupo_maestros where id= (select grupo_maestro_padre from default_cms_grupo_detalles where video_id= xvi);

		elseif  tint1 = 2 then 

				set xcoleccion = tvar1;

				set xcoleccionalias = tvar2;

				SELECT  id,nombre,alias 

				into tint2,xprograma,xprogramaalias

				FROM default_cms_grupo_maestros 

				WHERE id in (select  grupo_maestro_padre  from default_cms_grupo_detalles where grupo_maestro_id=xid);

				

				 -- select fu_ultimovideocoleccion(xid) into xvi;

					CALL sp_ultimovideoprograma(xid,xvi); 





				select nombre,alias 

				into xlistareproduccion,xlistareproduccionalias

				from default_cms_grupo_maestros where id= (select grupo_maestro_padre from default_cms_grupo_detalles where video_id= xvi);

			elseif tint1=1 then 

	

				set xlistareproduccion = tvar1;

				set xlistareproduccionalias = tvar2;

				SELECT  id,nombre,alias

				into tint2, xcoleccion,xcoleccionalias

				FROM default_cms_grupo_maestros 

				WHERE id in (select  grupo_maestro_padre  from default_cms_grupo_detalles where grupo_maestro_id=xid);

				SELECT  id, nombre,alias

				into tint3,xprograma,xprogramaalias

				FROM default_cms_grupo_maestros 

				WHERE id in (select  grupo_maestro_padre  from default_cms_grupo_detalles where grupo_maestro_id=tint2);

				select  count(reproducciones),	count(valorizacion),count(comentarios) into xvi_rep,xvi_com,xvi_val

				from default_cms_videos vi inner join default_cms_grupo_detalles  gd on vi.id=gd.video_id where gd.grupo_maestro_padre=xid;

				select gd.video_id into xvi from default_cms_grupo_detalles gd where gd.grupo_maestro_padre = xid	order by gd.fecha_registro asc limit 1 ;

	

				end if;

					

				

					SELECT id,titulo,alias,descripcion,fecha_transmision,canales_id,codigo,duracion,reproducciones,comentarios,valorizacion

				  into  tint1,xvideo,xvideoalias,xdescripcion,xfechatransmision,tint2,xcodigo,xduracion,xvi_rep,xvi_com,xvi_val

					FROM default_cms_videos where id = xvi;

				 -- select xidlr;

	

	ELSEIF			xopt=2 then



					SELECT id,titulo,alias,descripcion,fecha_transmision,canales_id,codigo,duracion,reproducciones,comentarios,valorizacion

				  into  tint1,xvideo,xvideoalias,xdescripcion,xfechatransmision,tint2,xcodigo,xduracion,xvi_rep,xvi_com,xvi_val

					FROM default_cms_videos 

					WHERE id=xid ;



			if tint2 is not null then

					SELECT nombre,alias,playerkey ,apikey into xcanal,xcanalalias,xplayerkey,xapikey FROM default_cms_canales where id=tint2;			

			end if;

			if tint3 is not null then

					SELECT alias  into xcategoria FROM default_cms_categorias  where id=tint3;			

			end if;



			if  tint1 is not null then 



			SELECT grupo_maestro_padre,grupo_maestro_id,tipo_grupo_maestros_id 

							into tint3,tint4,tint5

							FROM default_cms_grupo_detalles 

							WHERE video_id=xid;

	

							if  tint5= 1 then 

									 SELECT  nombre,alias

									 into xlistareproduccion,xlistareproduccionalias

									 fROM default_cms_grupo_maestros WHERE id = tint3;

							end if;

	

							SELECT grupo_maestro_padre,grupo_maestro_id,tipo_grupo_maestros_id

							into tint3,tint4,tint5

							FROM default_cms_grupo_detalles 

							WHERE grupo_maestro_id=tint3;



						if  tint5= 2 then 

									 SELECT  nombre,alias

									 into xcoleccion,xcoleccionalias

									 fROM default_cms_grupo_maestros 

									WHERE id = tint3;

							end if;

	

							SELECT grupo_maestro_padre,grupo_maestro_id,tipo_grupo_maestros_id

							into tint6,tint7,tint8

							FROM default_cms_grupo_detalles 

							WHERE grupo_maestro_id=tint3;



						if  tint8= 3 then 

									 SELECT  nombre,alias

									 into xprograma,xprogramaalias

									 fROM default_cms_grupo_maestros 

									WHERE id = tint6;

							end if;

																

	end if;



	ELSEIF			xopt=3 then



					SELECT nombre, alias into xcanal,xcanalalias FROM default_cms_canales where id = xid;



end if;

select  xcanal,xcanalalias,xplayerkey,xapikey,xprograma,xprogramaalias,xcoleccion,xcoleccionalias,xlistareproduccion,xlistareproduccionalias,xvideo,xvideoalias,xdescripcion,DATE_FORMAT(xfechatransmision, '%d-%m-%Y') as 'xfechatransmision',xcodigo,fu_timeahhmmss(xduracion)as 'xduracion',xcategoria,xvi_rep,xvi_com,xvi_val;

	

    END  $$
DELIMITER ;

/* Procedure structure for procedure sp_ultimovideocoleccion */

 DROP PROCEDURE IF EXISTS  sp_ultimovideocoleccion ;

DELIMITER $$

 CREATE  PROCEDURE sp_ultimovideocoleccion(IN xidcoleccion int, INOUT xvi int)
BEGIN

	DECLARE xco VARCHAR(50);

	DECLARE xnco VARCHAR(50);

	DECLARE xlr VARCHAR(50);

	DECLARE xnlr VARCHAR(50);

	-- DECLARE xvi VARCHAR(50);

	DECLARE avi VARCHAR(50)	;

	DECLARE fvi VARCHAR(50)	;

	DECLARE ini_co INT; 

	DECLARE ini_lr INT;

	DECLARE ini_vi INT;

	DECLARE cant_co INT;

	DECLARE cant_lr INT;

	DECLARE cant_vi INT;

	declare contador int;

	SET ini_co=0;

	SET ini_lr=0;

	SET ini_vi=0;

	set contador=0;

	SELECT COUNT(gd.grupo_maestro_id) INTO  cant_lr

	FROM default_cms_grupo_detalles gd INNER JOIN default_cms_grupo_maestros gm ON gd.grupo_maestro_id=gm.id

	WHERE gd.tipo_grupo_maestros_id=2 AND gd.grupo_maestro_padre=xidcoleccion ;

	lo_lre	:LOOP

												

	if  contador=cant_lr  then 

			LEAVE lo_lre;		

else

			set contador = contador	  + 1;

	end if;

								

							set @query = concat(' SELECT 	gm.alias,gd.grupo_maestro_id 	INTO 	@xlr,@xnlr  	FROM default_cms_grupo_detalles gd INNER JOIN default_cms_grupo_maestros gm ON gd.grupo_maestro_id=gm.id

							WHERE gd.tipo_grupo_maestros_id=2 AND gd.grupo_maestro_padre=',xidcoleccion,' ORDER BY gd.fecha_registro DESC LIMIT ',ini_lr,',1');



								PREPARE statement1 FROM @query;

								EXECUTE statement1;

								set xlr  = @xlr;					

								set xnlr = @xnlr;



	SET ini_lr=ini_lr+1;	



	IF xnlr IS NULL THEN 													

				ITERATE lo_lre; 

	ELSE	

		SELECT  COUNT(video_id) INTO cant_vi

						FROM default_cms_grupo_detalles gd  WHERE grupo_maestro_padre=xnlr AND gd.tipo_grupo_maestros_id=1;

						IF  cant_vi=0 THEN

								ITERATE lo_lre; 

						ELSE

								-- SELECT  video_id INTO xvi FROM default_cms_grupo_detalles gd  WHERE grupo_maestro_padre=xnlr AND gd.tipo_grupo_maestros_id=1 ORDER BY gd.fecha_registro asc LIMIT ini_vi,1;

								set @query2 = concat('SELECT  video_id INTO @xvi FROM default_cms_grupo_detalles gd  WHERE grupo_maestro_padre=',xnlr,'  AND gd.tipo_grupo_maestros_id=1 ORDER BY gd.fecha_registro asc LIMIT ',ini_vi,',1');

								PREPARE statement1 FROM @query2;

								EXECUTE statement1;

								set xvi  = @xvi;					



					END IF;

END IF;									

	END LOOP lo_lre;

END  $$
DELIMITER ;

/* Procedure structure for procedure sp_ultimovideoprograma */

 DROP PROCEDURE IF EXISTS  sp_ultimovideoprograma ;

DELIMITER $$

 CREATE  PROCEDURE sp_ultimovideoprograma(IN xidprograma INT,INOUT xvi INT)
BEGIN

DECLARE xco VARCHAR(50);

	DECLARE xnco VARCHAR(50);

	DECLARE xlr VARCHAR(50);

	DECLARE xnlr VARCHAR(50);

	-- DECLARE xvi VARCHAR(50);

	DECLARE avi VARCHAR(50)	;

	DECLARE fvi VARCHAR(50)	;

	DECLARE ini_co INT; 

	DECLARE ini_lr INT;

	DECLARE ini_vi INT;

	DECLARE cant_co INT;

	DECLARE cant_lr INT;

	DECLARE cant_vi INT;

	SET ini_co=0;

	SET ini_lr=0;

	SET ini_vi=0;



	SELECT COUNT(gd.grupo_maestro_id) INTO  cant_co

	FROM default_cms_grupo_detalles gd INNER JOIN default_cms_grupo_maestros gm ON gd.grupo_maestro_id=gm.id

	WHERE gd.tipo_grupo_maestros_id=3 AND gd.grupo_maestro_padre=xidprograma;

lo_col: LOOP

						IF  ini_co >= cant_co THEN

			LEAVE lo_col;			

	END IF ;



								set @query = concat('SELECT  gm.alias,gd.grupo_maestro_id INTO @xco,@xnco 

								FROM default_cms_grupo_detalles gd INNER JOIN default_cms_grupo_maestros gm ON gd.grupo_maestro_id=gm.id

								WHERE gd.tipo_grupo_maestros_id=3 AND gd.grupo_maestro_padre=',xidprograma,' ORDER BY gd.fecha_registro DESC LIMIT  ',ini_co,' ,1;');



								PREPARE statement1 FROM @query;

								EXECUTE statement1;

								set xco  = @xco;					

								set xnco  = @xnco ;





	 SET ini_co=ini_co+1;

				IF xnco IS NULL THEN 													

							ITERATE lo_col; 

				ELSE

	SELECT COUNT(gd.grupo_maestro_id) INTO  cant_lr

	FROM default_cms_grupo_detalles gd INNER JOIN default_cms_grupo_maestros gm ON gd.grupo_maestro_id=gm.id

	WHERE gd.tipo_grupo_maestros_id=2 AND gd.grupo_maestro_padre=xnco ;

	lo_lre	:LOOP

												IF cant_lr=0	 THEN 

														ITERATE lo_col;	

	END IF;

	IF  ini_lr >= cant_lr THEN

				ITERATE lo_col;			

	END IF ;	





								set @query = concat('SELECT 	gm.alias,gd.grupo_maestro_id  INTO @xlr,@xnlr

								FROM default_cms_grupo_detalles gd INNER JOIN default_cms_grupo_maestros gm ON gd.grupo_maestro_id=gm.id

								WHERE gd.tipo_grupo_maestros_id=2 AND gd.grupo_maestro_padre=',xnco,' ORDER BY gd.fecha_registro DESC LIMIT ',ini_lr,',1;');



								PREPARE statement1 FROM @query;

								EXECUTE statement1;

								set xlr  = @xlr;					

								set xnlr = @xnlr;



	SET ini_lr=ini_lr+1;	

	IF xnlr IS NULL THEN 													

				ITERATE lo_lre; 

	ELSE	

	SELECT  COUNT(video_id) INTO cant_vi

					FROM default_cms_grupo_detalles gd  WHERE grupo_maestro_padre=xnlr AND gd.tipo_grupo_maestros_id=1;

			IF  cant_vi=0 THEN

					ITERATE lo_lre; 

	ELSE

								set @query = concat('SELECT  video_id INTO 	@xvi 	FROM default_cms_grupo_detalles gd  WHERE grupo_maestro_padre=',xnlr,' AND gd.tipo_grupo_maestros_id=1 ORDER BY gd.fecha_registro ASC LIMIT ',ini_vi,',1');

								PREPARE statement1 FROM @query;

								EXECUTE statement1;

								set xvi= @xvi;													



	END IF;

END IF;																														

	END LOOP lo_lre;

		END IF;	

 END LOOP lo_col;

END  $$
DELIMITER ;