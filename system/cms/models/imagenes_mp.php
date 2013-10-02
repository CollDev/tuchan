<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Imagenes_mp extends CI_Model {

    protected $_table = 'default_cms_imagenes';
    protected $_cms_tipo_imagen = 'default_cms_tipo_imagen';

    function setImagenVideos($datos) {
        $this->db->insert($this->_table, $datos);
        return $this->db->insert_id();
    }
    
    function getImagenesVideos($id) {
        $this->db
                ->select('alto,ancho,imagen,procedencia')
                ->from($this->_table . " as im")
                ->join($this->_cms_tipo_imagen . ' as ti', 'im.tipo_imagen_id = ti.id', 'inner')
                ->where(array('videos_id' => $id));

        $query = $this->db->get();
        return $query->result();
    }
    
    function getImagenesCanalesXId($id){
        $query = "SELECT REPLACE(nombre,' ','') AS 'nombre',imagen,procedencia FROM default_cms_imagenes  im INNER JOIN default_cms_tipo_imagen ti ON im.tipo_imagen_id = ti.id WHERE im.estado=1 AND  canales_id =". $id;
        return $this->db->query($query)->result();             
    }

    function getImagenesGrupoMaestrosXId($id){
        $query = "SELECT REPLACE(nombre,' ','') AS 'nombre',imagen,procedencia FROM default_cms_imagenes  im INNER JOIN default_cms_tipo_imagen ti ON im.tipo_imagen_id = ti.id WHERE im.estado=1 AND  grupo_maestros_id =". $id;
        return $this->db->query($query)->result();             
    }    
    
}    