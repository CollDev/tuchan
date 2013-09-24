<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Videos_mp extends CI_Model {

    protected $_table = 'default_cms_videos';
    protected $_table_canales = 'default_cms_canales';
    protected $_table_grupo_maestros = 'default_cms_grupo_maestros';
    protected $_table_videos = 'default_cms_videos';
    protected $_table_categorias = 'default_cms_categorias';
    protected $_table_grupo_detalles = 'default_cms_grupo_detalles';
    protected $_table_portadas = 'default_cms_portadas';
    protected $_table_secciones = 'default_cms_secciones';
    protected $_table_detalle_secciones = 'default_cms_detalle_secciones';
    protected $_table_imagenes = 'default_cms_imagenes';
    protected $_table_tags = 'default_cms_tags';

    public function getVideos() {
        $query = "select * from " . $this->_table . " order by id desc limit 100";
        return $this->db->query($query)->result();
    }

    public function getVideosActivos() {
        $query = "select id,id_mongo from " . $this->_table . " where codigo is not null and estado_liquid = 6";
        return $this->db->query($query)->result();
    }

    public function getVideosActivosPublicados() {
        $query = " SELECT vi.*,ca.apikey,ca.playerkey FROM default_cms_videos vi INNER JOIN default_cms_canales ca ON vi.canales_id =  ca.id WHERE vi.estado = 2 and vi.estado_liquid=6";
        return $this->db->query($query)->result();
    }

    public function getVideosActivosPublicadosUlt7Dias() {
        $query = "SELECT vi.*,ca.apikey,ca.playerkey
                  FROM default_cms_videos vi INNER JOIN default_cms_canales ca ON vi.canales_id =  ca.id WHERE vi.estado = 2 AND vi.estado_liquid=6
                  AND (DATE_ADD(CONCAT(vi.fecha_transmision,' ',vi.horario_transmision_inicio) ,INTERVAL ca.ibope HOUR)) >  (DATE_SUB(NOW(),INTERVAL 7 DAY)) 
                  AND (DATE_ADD(CONCAT(vi.fecha_transmision,' ',vi.horario_transmision_inicio) ,INTERVAL ca.ibope HOUR)) <= NOW();";
        return $this->db->query($query)->result();
    }

    public function getVideosxId($id) {
        $query = "SELECT vi.ruta,vi.id,vi.id_mongo,vi.estado_migracion,vi.estado,vi.fragmento, (SELECT GROUP_CONCAT(ta.nombre)
                    FROM default_cms_video_tags vt INNER JOIN default_cms_tags ta ON vt.tags_id = ta.id  
                    WHERE vt.videos_id=vi.id) AS 'etiquetas',vi.procedencia,     
                    ( SELECT  imagen FROM default_cms_imagenes im WHERE im.tipo_imagen_id=5 AND canales_id=vi.canales_id AND im.estado=1 ) AS 'imagen'
                    ,IF((DATE_ADD(CONCAT(fecha_transmision,' ',horario_transmision_inicio) , INTERVAL ibope HOUR)<NOW()),1,0) AS 'est_tra',                    
                    (SELECT gm2.id_mongo FROM default_cms_grupo_maestros gm2 INNER JOIN default_cms_grupo_detalles gd2 ON gm2.id = gd2.grupo_maestro_padre WHERE 
                        gd2.video_id = vi.id) AS 'idmongo_pa' 
                    FROM default_cms_videos vi  INNER JOIN default_cms_canales ca ON ca.id = vi.canales_id
                    WHERE vi.id =" . $id;
        return $this->db->query($query)->result();
    }

    public function getVideosxCodigo($codigo) {

        $query = "SELECT vi.id,vi.titulo,vi.descripcion,vi.codigo,ca.apikey FROM " . $this->_table . " vi 
                INNER JOIN " . $this->_table_canales . " ca ON  vi.canales_id=ca.id
                WHERE  vi.codigo='" . $codigo . "'";

        return $this->db->query($query)->result();
    }

    public function getVideosxIdConKey($id) {
        $query = "SELECT vi.*,ca.id as 'canal_id' , ca.apikey,ca.playerkey FROM default_cms_videos vi INNER JOIN default_cms_canales ca ON vi.canales_id =  ca.id WHERE vi.id=" . $id;
        return $this->db->query($query)->result();
    }

    public function getVideosXIdDatos($id) {
        $query = "SELECT ca.id AS 'canal_id',ca.id_mongo AS 'id_mongo_ca',
                    CASE 
		WHEN (SELECT gd1.tipo_grupo_maestros_id FROM default_cms_grupo_detalles gd1 WHERE gd1.video_id = vi.id)  =3 THEN 
			(SELECT gd1.grupo_maestro_padre  FROM default_cms_grupo_detalles gd1 WHERE gd1.video_id = vi.id AND gd1.tipo_grupo_maestros_id =3)
	
		WHEN (SELECT gd2.tipo_grupo_maestros_id FROM default_cms_grupo_detalles gd2 WHERE gd2.grupo_maestro_id = (SELECT gd3.grupo_maestro_padre FROM default_cms_grupo_detalles gd3 WHERE gd3.video_id = vi.id  ) )  =3 THEN 
			(SELECT gd2.grupo_maestro_padre FROM default_cms_grupo_detalles gd2 WHERE gd2.grupo_maestro_id = (SELECT gd3.grupo_maestro_padre FROM default_cms_grupo_detalles gd3 WHERE gd3.video_id = vi.id  )   AND  gd2.tipo_grupo_maestros_id =3	)
			
		WHEN (SELECT gd4.tipo_grupo_maestros_id FROM default_cms_grupo_detalles gd4 WHERE gd4.grupo_maestro_id = (SELECT gd5.grupo_maestro_padre FROM default_cms_grupo_detalles gd5 WHERE gd5.grupo_maestro_id = (SELECT gd6.grupo_maestro_padre FROM default_cms_grupo_detalles gd6 WHERE gd6.video_id = vi.id )))=3 THEN 
			(SELECT gd4.grupo_maestro_padre FROM default_cms_grupo_detalles gd4 WHERE gd4.grupo_maestro_id = (SELECT gd5.grupo_maestro_padre FROM default_cms_grupo_detalles gd5 WHERE gd5.grupo_maestro_id = (SELECT gd6.grupo_maestro_padre FROM default_cms_grupo_detalles gd6 WHERE gd6.video_id = vi.id )) AND  gd4.tipo_grupo_maestros_id =3	)
		ELSE(SELECT NULL) 
                    END AS 'gm_id'                                                  
                FROM default_cms_videos vi  INNER JOIN default_cms_canales ca ON ca.id =  vi.canales_id WHERE vi.id =" . $id;
        return $this->db->query($query)->result();
    }

    public function getVideosNuevos() {
        $query = "select * from " . $this->_table . " where estado_liquid=0";
        return $this->db->query($query)->result();
    }

    public function getVideosMp4() {
        $query = "SELECT vi.id,ca.apikey FROM " . $this->_table . " vi 
                INNER JOIN " . $this->_table_canales . " ca ON  vi.canales_id=ca.id
                WHERE vi.estado_liquid=2";

        return $this->db->query($query)->result();
    }

    public function getVideosMp4XId($id) {
        $query = "SELECT vi.id,ca.apikey FROM " . $this->_table . " vi 
                INNER JOIN " . $this->_table_canales . " ca ON  vi.canales_id=ca.id
                WHERE vi.estado_liquid=2 and vi.id=" . $id;

        return $this->db->query($query)->result();
    }

    public function getVideosNoPublicados() {
        $query = "SELECT vi.id,vi.titulo,vi.descripcion,vi.codigo,ca.apikey FROM " . $this->_table . " vi 
                INNER JOIN " . $this->_table_canales . " ca ON  vi.canales_id=ca.id
                WHERE vi.estado_liquid=4";

        return $this->db->query($query)->result();
    }

    public function getVideosNoPublicadosXId($id) {
        $query = "SELECT vi.id,vi.titulo,vi.descripcion,vi.codigo,ca.apikey FROM " . $this->_table . " vi 
                INNER JOIN " . $this->_table_canales . " ca ON  vi.canales_id=ca.id
                WHERE vi.estado_liquid=4 and vi.id=" . $id;

        return $this->db->query($query)->result();
    }

    public function getVideosObtenerDatos() {
        $query = "SELECT vi.id,vi.codigo,vi.ruta,ca.apikey,(select count(im.id) from " . $this->_table_imagenes . " im  WHERE im.videos_id=vi.id and im.procedencia=1) as 'imag'
                    FROM " . $this->_table . " vi  
                    INNER  JOIN " . $this->_table_canales . " ca ON  vi.canales_id=ca.id
                    WHERE vi.estado_liquid=5";

        return $this->db->query($query)->result();
    }

    public function getVideosObtenerDatosXId($id) {
        $query = "SELECT vi.id,vi.estado,vi.codigo,vi.ruta,vi.rutasplitter,vi.duracion,vi.procedencia,ca.id as 'canal_id' , ca.apikey,ca.postback_url , (select count(im.id) from " . $this->_table_imagenes . " im  WHERE im.videos_id=vi.id and im.procedencia=1) as 'imag'
                    FROM " . $this->_table . " vi  
                    INNER  JOIN " . $this->_table_canales . " ca ON  vi.canales_id=ca.id
                    WHERE  vi.id=" . $id;

        return $this->db->query($query)->result();
    }

    public function getVideosMasVistosXId($cant) {
        $query = "select id,id_mongo from " . $this->_table . " WHERE estado=2 ORDER BY reproducciones DESC LIMIT " . $cant;
        return $this->db->query($query)->result();
    }

    public function getVideoPadreXIdHijo($id) {

        $query = "SELECT id,id_mongo FROM " . $this->_table . " WHERE id =  (SELECT padre FROM " . $this->_table . " WHERE id = " . $id . ")";
        return $this->db->query($query)->result();
    }

    public function setReproduccionesVideosXId($id, $cant) {
        $query = "update " . $this->_table . " set reproducciones='" . $cant . "' where id='" . $id . "'";
        return $this->db->query($query);
    }

    public function setEstadosVideos($id = "", $estado = "", $estado_liquid = "") {
        $query = "update " . $this->_table . " set estado=" . $estado . ",estado_liquid =" . $estado_liquid . " where id=" . $id ;        
        if($estado_liquid >0 && $estado != 4){
            $query = $query . " and estado_liquid = " . ($estado_liquid - 1);  
        }        
        $this->db->query($query);    
        Log::erroLog("query setEstadosVideos  " . $query);
        return  $this->db->affected_rows();
    }

    function setMediaVideos($id, $media) {
        $query = "update " . $this->_table . " set codigo='" . $media . "' where id=" . $id;
        $this->db->query($query);
        Log::erroLog("query setEstadosVideos  " . $query);
    }

    function setRutaVideos($id = "", $ruta = "") {
        $query = "update " . $this->_table . " set ruta='" . $ruta . "' where id=" . $id;
        $this->db->query($query);
        Log::erroLog("setRutaVideos  " . $query);
    }

    function setRutaVideosSplitter($id = "", $ruta = "") {
        $query = "update " . $this->_table . " set rutasplitter='" . $ruta . "' where id=" . $id;
        $this->db->query($query);
        Log::erroLog("setRutaVideos  " . $query);
    }

    function setDuracionVideos($id = "", $duracion = "") {
        $query = "update " . $this->_table . " set duracion = SEC_TO_TIME(" . $duracion . ") where id = " . $id;
        $this->db->query($query);
        Log::erroLog("setDuracionVideos  " . $query);
    }

    function setComentariosValorizacion($id, $comentarios, $valorizacion) {
        $query = "update " . $this->_table . " set comentarios = ?, valorizacion = '" . $valorizacion . "' where id=" . $id;

        $this->db->query($query, array($comentarios));
    }

    function getVideosPlaylist($id) {
        $query = "SELECT vi.id,vi.id_mongo FROM " . $this->_table_grupo_detalles . " gd INNER JOIN " . $this->_table_videos . " vi ON gd.video_id = vi.id 
            WHERE gd.grupo_maestro_padre = (SELECT gd2.grupo_maestro_padre FROM " . $this->_table_grupo_detalles . " gd2 WHERE tipo_grupo_maestros_id = 1 and  video_id=" . $id . ") AND vi.id_mongo IS NOT NULL
            ORDER BY vi.fragmento,vi.fecha_registro DESC";

        return $this->db->query($query)->result();
    }

    function getVideosClips($id) {

        $query = "SELECT id_mongo FROM default_cms_videos WHERE padre = " . $id;
        return $this->db->query($query)->result();
    }

    function getShowProcedure() {

        $query = "SHOW PROCEDURE STATUS";
        return $this->db->query($query)->result();
    }

    function getShowFunction() {

        $query = "SHOW FUNCTION STATUS";
        return $this->db->query($query)->result();
    }

    public function getExisteVideosXIdMongo($id) {
        $query = "select * from " . $this->_table . " where id_mongo = '" . $id . "'";
        return $this->db->query($query)->num_rows();
    }

    public function getTransmisionMenorIbope() {
        $query = "SELECT vi.id,vi.id_mongo FROM " . $this->_table_canales . " ca INNER JOIN " . $this->_table_videos . " vi ON ca.id = vi.canales_id 
            WHERE ( DATE_ADD(CONCAT(fecha_transmision,' ',horario_transmision_inicio), INTERVAL ca.ibope HOUR )<NOW()) AND vi.id_mongo IS NULL AND vi.estado = '2'";
        return $this->db->query($query)->result();
    }

    public function insertVideo($objBeanVideo) {
        $query = "INSERT INTO " . $this->_table . " 
            (
                `tipo_videos_id`,
                `categorias_id`,
                `usuarios_id`,
                `canales_id`,
                `titulo`,
                `alias`,
                `descripcion`,
                `fragmento`,
                `fecha_publicacion_inicio`,
                `fecha_publicacion_fin`, ".
//                `fecha_transmision`,
//                `horario_transmision_inicio`,
//                `horario_transmision_fin`,
                "`ubicacion`,
                `estado`,
                `estado_liquid`,
                `fecha_registro`,
                `usuario_registro`,
                `estado_migracion`,
                `estado_migracion_sphinx_tit`,
                `estado_migracion_sphinx_des`,
                `padre`,
                `estado_migracion_sphinx`,
                `procedencia`
            ) VALUES (
                '" . $objBeanVideo->tipo_videos_id . "',
                '" . $objBeanVideo->categorias_id . "',
                '" . $objBeanVideo->usuarios_id . "',
                '" . $objBeanVideo->canales_id . "',
                ?,
                '" . $objBeanVideo->alias . "',
                ?,
                '" . $objBeanVideo->fragmento . "',
                '" . $objBeanVideo->fecha_publicacion_inicio . "',
                '" . $objBeanVideo->fecha_publicacion_fin . "', " .
//                '" . $objBeanVideo->fecha_transmision . "',
//                '" . $objBeanVideo->horario_transmision_inicio . "',
//                '" . $objBeanVideo->horario_transmision_fin . "',
                "'" . $objBeanVideo->ubicacion . "',
                '" . $objBeanVideo->estado . "',
                '" . $objBeanVideo->estado_liquid . "',
                '" . $objBeanVideo->fecha_registro . "',
                '" . $objBeanVideo->usuario_registro . "',
                '" . $objBeanVideo->estado_migracion . "',
                '" . $objBeanVideo->estado_migracion_sphinx_tit . "',
                '" . $objBeanVideo->estado_migracion_sphinx_des . "',
                '" . $objBeanVideo->padre . "',
                '" . $objBeanVideo->estado_migracion_sphinx . "',
                '" . $objBeanVideo->procedencia . "'
            );";

        $this->db->query($query, array($objBeanVideo->titulo, $objBeanVideo->descripcion));
        return $this->db->insert_id();
    }

    public function updateVideo($id, $data) {
        $key = array_keys($data);
        $value = array_values($data);
        $query = "UPDATE " . $this->_table . " SET
                `" . $key[0] . "` = ? 
                WHERE `id` = '" . $id . "';";

        $this->db->query($query, $value[0]);
    }

    public function save_video($objBeanVideo) {
        $objBeanVideo->id = $this->insertVideo($objBeanVideo);
        $objBeanVideo->alias = $objBeanVideo->alias . '-' . $objBeanVideo->id;
        $this->updateVideo($objBeanVideo->id, array('alias' => $objBeanVideo->alias));

        return $objBeanVideo;
    }
}