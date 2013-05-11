<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Canales_mp extends CI_Model {

    protected $_table = 'default_cms_canales';
    protected $_table_imagenes = 'default_cms_imagenes';
    protected $_table_videos = 'default_cms_videos';

    public function getCanales() {
        $query = "select * from " . $this->_table;
        return $this->db->query($query)->result();
    }
    
    public function getCanalesXId($id) {
        $query = "SELECT ca.*,(SELECT COUNT(id) FROM ". $this->_table_videos . " vi WHERE vi.estado = 2 AND vi.canales_id =ca.id ) AS 'canal_cv' FROM ". $this->_table . "  ca WHERE ca.id=" . $id;        
        return $this->db->query($query)->result();
    }

}
