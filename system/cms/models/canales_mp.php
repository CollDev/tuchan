<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Canales_mp extends CI_Model {

    protected $_table = 'default_cms_canales';
    protected $_table_imagenes = 'default_cms_imagenes';

    public function getCanales() {
        $query = "select * from " . $this->_table;
        return $this->db->query($query)->result();
    }
    
    public function getCanalesXId($id) {
        $query = "SELECT ca.*,im.imagen,im.procedencia FROM ". $this->_table . " ca 
            INNER JOIN ". $this->_table_imagenes . " im ON ca.id=im.canales_id 
            AND im.tipo_imagen_id=5 and im.estado = 1 and ca.id=".$id;
        return $this->db->query($query)->result();
    }

}
