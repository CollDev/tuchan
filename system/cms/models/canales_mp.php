<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Canales_mp extends CI_Model {

    protected $_table = 'default_cms_canales';

    public function getCanales() {
        $query = "select * from " . $this->_table;
        return $this->db->query($query)->result();
    }

}