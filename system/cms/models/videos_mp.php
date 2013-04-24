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

    public function getVideos(){
         $query = "select * from " . $this->_table. " order by id desc limit 10";
          return $this->db->query($query)->result();
    }
        
    public function getVideosActivos() {
        $query = "select id,id_mongo from " . $this->_table . " where estado=2";
        return $this->db->query($query)->result();
    }

    public function getVideosxId($id) {
        $query = "select * from " . $this->_table . " where id=" . $id;
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
                WHERE vi.estado_liquid=2 and vi.id=".$id;

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
                WHERE vi.estado_liquid=4 and vi.id=".$id;

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
        $query = "SELECT vi.id,vi.codigo,vi.ruta,ca.apikey,(select count(im.id) from " . $this->_table_imagenes . " im  WHERE im.videos_id=vi.id and im.procedencia=1) as 'imag'
                    FROM " . $this->_table . " vi  
                    INNER  JOIN " . $this->_table_canales . " ca ON  vi.canales_id=ca.id
                    WHERE vi.estado_liquid=5 and vi.id=".$id;

        return $this->db->query($query)->result();
    }
    
    public function setReproducciones($id, $cant) {
        $query = "update " . $this->_table . " set reproducciones=" . $cant . " where codigo='" . $id . "'";
        return $this->db->query($query);
    }

    public function setEstadosVideos($id = "", $estado = "", $estado_liquid = "") {
        $query = "update " . $this->_table . " set estado=" . $estado . ",estado_liquid =" . $estado_liquid . " where id=" . $id;
        //echo $query . "\n";
        $this->db->query($query);
    }

    function setMediaVideos($id = "", $media = "") {
        $query = "update " . $this->_table . " set codigo='" . $media . "' where id=" . $id;
        //echo $query . "\n";
        $this->db->query($query);
    }

    function setRutaVideos($id = "", $ruta = "") {
        $query = "update " . $this->_table . " set ruta='" . $ruta . "' where id=" . $id;
        //echo $query . "\n";
        $this->db->query($query);
    }

    function setComentariosValorizacion($id, $comentarios, $valorizacion) {
        $query = "update " . $this->_table . " set comentarios= '" . $comentarios . "', valorizacion=" . $valorizacion . " where id=" . $id;
        
        $this->db->query($query);
    }
    
    function getVideosPlaylist($id){
        $query = "SELECT vi.id_mongo FROM " . $this->_table_grupo_detalles . " gd INNER JOIN " . $this->_table_videos . " vi ON gd.video_id = vi.id 
            WHERE gd.grupo_maestro_padre = (SELECT gd2.grupo_maestro_padre FROM " . $this->_table_grupo_detalles . " gd2 WHERE video_id=" . $id . ") 
            ORDER BY vi.fecha_registro DESC";

        return $this->db->query($query)->result();
    }
    
    function getVideosClips($id){
        
        $query = "SELECT id_mongo FROM default_cms_videos WHERE padre = ".$id;
        return $this->db->query($query)->result();
        
    }

}