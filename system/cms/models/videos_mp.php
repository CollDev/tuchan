<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Videos_mp extends CI_Model {

    protected $_table = 'default_cms_videos';
    protected $_table_canales = 'default_cms_canales';
    protected $_table_imagenes = 'default_cms_imagenes';

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

    public function getVideosNoPublicados() {
        $query = "SELECT vi.id,vi.titulo,vi.descripcion,vi.codigo,ca.apikey FROM " . $this->_table . " vi 
                INNER JOIN " . $this->_table_canales . " ca ON  vi.canales_id=ca.id
                WHERE vi.estado_liquid=4";

        return $this->db->query($query)->result();
    }

    public function getVideosObtenerDatos() {
        $query = "SELECT vi.id,vi.codigo,vi.ruta,ca.apikey,(select count(im.id) from " . $this->_table_imagenes . " im  WHERE im.videos_id=vi.id and im.procedencia=1) as 'imag'
                    FROM " . $this->_table . " vi  
                    INNER  JOIN " . $this->_table_canales . " ca ON  vi.canales_id=ca.id
                    WHERE vi.estado_liquid=5";

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

}