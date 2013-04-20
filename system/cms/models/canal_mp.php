<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Canal_mp extends CI_Model {

    private $_tabla = 'canal';
    protected $_table_canales = 'default_cms_canales';
    protected $_table_grupo_maestros = 'default_cms_grupo_maestros';
    protected $_table_videos = 'default_cms_videos';
    protected $_table_grupo_detalles = 'default_cms_grupo_detalles';

    function queryMysqlCanal($option, $id = "") {

        switch ($option) {
            case '1':
                $query = "SELECT id,alias,nombre,descripcion,id_mongo,estado,estado_migracion  FROM default_cms_canales WHERE estado_migracion IN (0,9)";
                break;
            case '2':
                $query = "SELECT gm.id,gm.nombre,gm.descripcion,gm.alias,gm.categorias_id,gm.estado,gm.estado_migracion,ca.nombre AS 'nombre_ca',ca.alias AS 'alias_ca',ca.id_mongo AS 'idmongo_ca'
                        FROM default_cms_grupo_maestros gm INNER JOIN default_cms_canales ca ON gm.canales_id = ca.id
                        WHERE gm.tipo_grupo_maestro_id=3 AND gm.estado_migracion IN (0,9)";
                break;
            case '3':
                $query = "SELECT id,nombre,descripcion,alias,categorias_id FROM default_cms_grupo_maestros  WHERE id IN (SELECT grupo_maestro_id FROM default_cms_grupo_detalles WHERE grupo_maestro_padre=" . $id . " AND tipo_grupo_maestros_id=3) AND tipo_grupo_maestro_id=2 ";
                break;
            case '4':
                $query = "SELECT id,nombre,descripcion,alias,categorias_id FROM default_cms_grupo_maestros  WHERE id IN (SELECT grupo_maestro_id FROM default_cms_grupo_detalles  WHERE grupo_maestro_padre=" . $id . " AND tipo_grupo_maestros_id=2) AND tipo_grupo_maestro_id=1";
                break;
//            case '5':
//                $query = "SELECT vi.id,vi.titulo,vi.alias,vi.descripcion,vi.categorias_id,ca.nombre,vi.codigo,vi.fecha_transmision,vi.fragmento,vi.codigo,vi.reproducciones,fu_timeahhmmss(vi.duracion) as 'duracion',vi.canales_id,vi.valorizacion,vi.comentarios             FROM default_cms_videos vi   INNER JOIN default_cms_categorias ca ON vi.categorias_id=ca.id   WHERE vi.id IN ( SELECT video_id FROM default_cms_grupo_detalles WHERE grupo_maestro_padre=" . $id . " AND tipo_grupo_maestros_id=1 )  ORDER BY fragmento ASC";
//                break;
//            
            case '5':
                $query = "SELECT vi.id,vi.id_mongo,vi.estado_migracion,vi.estado, (SELECT GROUP_CONCAT(ta.nombre)
                    FROM default_cms_video_tags vt INNER JOIN default_cms_tags ta ON vt.tags_id = ta.id  
                    WHERE vt.videos_id=vi.id) AS 'etiquetas',
                    ( SELECT  imagen FROM default_cms_imagenes WHERE tipo_imagen_id=5 AND canales_id=vi.canales_id) AS 'imagen'				
                    FROM default_cms_videos vi WHERE vi.estado_migracion IN (0,9) AND vi.estado IN (2,3)  AND vi.estado_liquid=6";
                break;
        }

        return $this->db->query($query)->result();
    }

    function queryProcedure($option, $id) {
        switch ($option) {
            case '4':
                $query = "";
                $query = "call sp_obtenerdatos(2," . $id . ")";
                //echo $query."<br>";                
                break;
        }

        $objresult = $this->db->query($query);
        mysqli_next_result($this->db->conn_id);
        return $objresult->result();
    }

    function queryMysqlRelated($id) {
        $query = "SELECT vi.id_mongo FROM " . $this->_table_grupo_detalles . " gd INNER JOIN " . $this->_table_videos . " vi ON gd.video_id = vi.id 
            WHERE gd.grupo_maestro_padre = (SELECT gd2.grupo_maestro_padre FROM " . $this->_table_grupo_detalles . " gd2 WHERE video_id=" . $id . ") 
            ORDER BY vi.fecha_registro DESC";

        return $this->db->query($query)->result();
    }

    function queryMysqlImagen($id) {
        $query = "SELECT alto,ancho,imagen,procedencia FROM default_cms_imagenes im INNER JOIN default_cms_tipo_imagen ti ON im.tipo_imagen_id = ti.id WHERE videos_id=" . $id;
        return $this->db->query($query)->result();
    }

    function updateIdMongoCanales($id, $id_mongo) {
        $query = "update " . $this->_table_canales . " set id_mongo='" . $id_mongo . "' where id=" . $id;
        $this->db->query($query);
    }

    function updateEstadoMigracionCanales($id) {
        $query = "update " . $this->_table_canales . " set estado_migracion=2,fecha_migracion=now() where id=" . $id;
        
        $this->db->query($query);
    }

    function updateEstadoMigracionCanalesActualizacion($id) {
        $query = "update " . $this->_table_canales . " set estado_migracion=2,fecha_migracion_actualizacion=now() where id=" . $id;
        
        $this->db->query($query);
    }

    function updateIdMongoGrupoMaestros($id, $id_mongo) {
        $query = "update " . $this->_table_grupo_maestros . " set id_mongo='" . $id_mongo . "' where id=" . $id;
        $this->db->query($query);
    }

    function updateEstadoMigracionGrupoMaestros($id) {
        $query = "update " . $this->_table_grupo_maestros . " set estado_migracion=2,fecha_migracion=now() where id=" . $id;
        
        $this->db->query($query);
    }

    function updateEstadoMigracionGrupoMaestrosActualizacion($id) {
        $query = "update " . $this->_table_grupo_maestros . " set estado_migracion=2,fecha_migracion_actualizacion=now() where id=" . $id;
        
        $this->db->query($query);
    }

    function updateIdMongoVideos($id, $id_mongo) {
        $query = "update " . $this->_table_videos . " set id_mongo='" . $id_mongo . "' where id=" . $id;
        $this->db->query($query);
    }

    function updateEstadoMigracionVideos($id) {
        $query = "update " . $this->_table_videos . " set estado_migracion=2,fecha_migracion=now() where id=" . $id;
        
        $this->db->query($query);
    }

    function updateEstadoMigracionVideosActualizacion($id) {
        $query = "update " . $this->_table_videos . " set estado_migracion=2,fecha_migracion_actualizacion=now() where id=" . $id;
        
        $this->db->query($query);
    }

    public function setItemCollection($objmongo) {
        return $this->mongo_db->insert($this->_tabla, $objmongo);
    }

    public function setItemCollectionUpdate($set, $where) {
        $this->mongo_db->where($where)->set($set)->update($this->_tabla);
    }

    public function setItemCollectionDelete($objmongo) {
        $this->mongo_db->delete($this->_tabla, $objmongo, array());
    }

    public function getItemCollection($id_mongo) {
        return $this->mongo_db->where(array('_id' => $id_mongo))->get($this->_tabla);
    }

}