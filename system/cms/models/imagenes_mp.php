<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Imagenes_mp extends CI_Model {

    protected $_table = 'default_cms_imagenes';

    function setImagenVideos($datos) {
        return $last_id = $this->db->insert($this->_table, $datos);
    }

}