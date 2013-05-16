
 DROP FUNCTION IF EXISTS fu_aliaspa ;
DELIMITER $$

 CREATE  FUNCTION fu_aliaspa(xtipo_portadas_id integer,xorigen_id integer) RETURNS varchar(200) CHARSET utf8
BEGIN

declare xreturn varchar(100) ;



		if xtipo_portadas_id=1 then

			set xreturn="";



	elseif  xtipo_portadas_id =2  then 

			SELECT 	ca.alias into xreturn  FROM default_cms_categorias ca WHERE  ca.id=xorigen_id;



	elseif  xtipo_portadas_id =3  then 

			SELECT 	ta.alias  into xreturn  FROM default_cms_tags ta WHERE  ta.id=xorigen_id;



	elseif  xtipo_portadas_id =4  then 

				SELECT gm.alias into xreturn FROM default_cms_grupo_maestros gm  INNER JOIN  default_cms_categorias ca ON ca.id=gm.categorias_id   WHERE  gm.id=xorigen_id;



	elseif  xtipo_portadas_id =5  then 

			SELECT 	ca.alias into xreturn  FROM default_cms_canales ca WHERE  ca.id=xorigen_id;



end if;



	RETURN xreturn;

END  $$
DELIMITER ;

/* Function  structure for function  fu_timeahhmmss */

 DROP FUNCTION IF EXISTS fu_timeahhmmss ;
DELIMITER $$

 CREATE  FUNCTION fu_timeahhmmss(xtime time) RETURNS varchar(50) CHARSET utf8
BEGIN
declare itime int;
declare texto varchar(50);
set itime=TIME_TO_SEC(xtime);
	if itime<=59 then
		set texto = concat(TIME_FORMAT(xtime,'%s')," seg");	 
	elseif itime >59  and itime < 3481 then
		set texto = concat(TIME_FORMAT(xtime,'%i:%s')," min");	
	elseif itime >= 3481 then
		set texto = concat(TIME_FORMAT(xtime,'%k:%i:%s')," hor");	
	end if;
	return texto;
END  $$
DELIMITER ;
