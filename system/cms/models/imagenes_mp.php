<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Imagenes_mp extends CI_Model {

    protected $_table = 'default_cms_imagenes';

    function setImagenVideos($datos) {
        $this->db->insert($this->_table, $datos);
        return $this->db->insert_id();
    }
    
    function getImagenesVideos($id){
        $query = "SELECT alto,ancho,imagen,procedencia FROM " .$this->_table . " im INNER JOIN default_cms_tipo_imagen ti ON im.tipo_imagen_id = ti.id WHERE videos_id=" . $id;
        return $this->db->query($query)->result();             
    }
    
    function getImagenesCanalesXId($id){
        $query = "SELECT REPLACE(nombre,' ','') AS 'nombre',imagen,procedencia FROM default_cms_imagenes  im INNER JOIN default_cms_tipo_imagen ti ON im.tipo_imagen_id = ti.id WHERE im.estado=1 AND  canales_id =". $id;
        return $this->db->query($query)->result();             
    }

}    