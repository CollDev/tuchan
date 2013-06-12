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
    
    public function getVideosActivosPublicados(){
        $query = " SELECT vi.*,ca.apikey,ca.playerkey FROM default_cms_videos vi INNER JOIN default_cms_canales ca ON vi.canales_id =  ca.id WHERE vi.estado = 2 and vi.estado_liquid=6";
        return $this->db->query($query)->result();
    }

    public function getVideosxId($id) {
        $query = "SELECT vi.ruta,vi.id,vi.id_mongo,vi.estado_migracion,vi.estado, (SELECT GROUP_CONCAT(ta.nombre)
                    FROM default_cms_video_tags vt INNER JOIN default_cms_tags ta ON vt.tags_id = ta.id  
                    WHERE vt.videos_id=vi.id) AS 'etiquetas',
                    ( SELECT  imagen FROM default_cms_imagenes im WHERE im.tipo_imagen_id=5 AND canales_id=vi.canales_id and im.estado=1 ) AS 'imagen'				
                    FROM default_cms_videos vi WHERE vi.id =" . $id;
        return $this->db->query($query)->result();
    }
    
    public function getVideosxCodigo($codigo) {
        
        $query ="SELECT vi.id,vi.titulo,vi.descripcion,vi.codigo,ca.apikey FROM " . $this->_table . " vi 
                INNER JOIN " . $this->_table_canales . " ca ON  vi.canales_id=ca.id
                WHERE  vi.codigo='" . $codigo."'";
        
        return $this->db->query($query)->result();
    }    

    public function getVideosxIdConKey($id) {
        $query = "SELECT vi.*,ca.apikey,ca.playerkey FROM default_cms_videos vi INNER JOIN default_cms_canales ca ON vi.canales_id =  ca.id WHERE vi.id=" . $id;
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
        $query = "SELECT vi.id,vi.estado,vi.codigo,vi.ruta,vi.rutasplitter,vi.duracion,ca.apikey,(select count(im.id) from " . $this->_table_imagenes . " im  WHERE im.videos_id=vi.id and im.procedencia=1) as 'imag'
                    FROM " . $this->_table . " vi  
                    INNER  JOIN " . $this->_table_canales . " ca ON  vi.canales_id=ca.id
                    WHERE  vi.id=" . $id; //vi.estado_liquid=5 and

        return $this->db->query($query)->result();
    }
    
    public function getVideosMasVistosXId($cant){
        $query  ="select id,id_mongo from " . $this->_table . " WHERE estado=2 ORDER BY reproducciones DESC LIMIT ".$cant;        
        return $this->db->query($query)->result();
    }
    
    public function getVideoPadreXIdHijo($id){
        
        $query = "SELECT id,id_mongo FROM " . $this->_table . " WHERE id =  (SELECT padre FROM " . $this->_table . " WHERE id = ".$id.")";
        return $this->db->query($query)->result();
    }

    public function setReproduccionesVideosXId($id, $cant) {
        $query = "update " . $this->_table . " set reproducciones='" . $cant . "' where id='" . $id . "'";
        return $this->db->query($query);
    }

    public function setEstadosVideos($id = "", $estado = "", $estado_liquid = "") {
        $query = "update " . $this->_table . " set estado=" . $estado . ",estado_liquid =" . $estado_liquid . " where id=" . $id;
        $this->db->query($query);
        Log::erroLog("query setEstadosVideos  " . $query);
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
        $query = "update " . $this->_table . " set duracion= SEC_TO_TIME(" . $duracion . ") where id=" . $id;
        $this->db->query($query);
        Log::erroLog("setDuracionVideos  " . $query);
    }

    function setComentariosValorizacion($id, $comentarios, $valorizacion) {
        $query = "update " . $this->_table . " set comentarios= '" . $comentarios . "', valorizacion='" . $valorizacion . "' where id=" . $id;

        $this->db->query($query);
    }

    function getVideosPlaylist($id) {
        $query = "SELECT vi.id,vi.id_mongo FROM " . $this->_table_grupo_detalles . " gd INNER JOIN " . $this->_table_videos . " vi ON gd.video_id = vi.id 
            WHERE gd.grupo_maestro_padre = (SELECT gd2.grupo_maestro_padre FROM " . $this->_table_grupo_detalles . " gd2 WHERE video_id=" . $id . ") AND vi.id_mongo IS NOT NULL
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
    
     
}