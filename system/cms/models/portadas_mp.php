<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Portadas_mp extends CI_Model {

    protected $_table = 'default_cms_portadas';

    public function getPortadas(){
        $query  = " SELECT * FROM ". $this->_table;
        return $this->db->query($query)->result(); 
    }
    
    
    public function getPortadasXId($id) {
        $query= "SELECT po.id, po.nombre, po.descripcion, po.tipo_portadas_id,IFNULL(po.origen_id,po.canales_id) AS 'origen_id',po.estado,po.id_mongo,po.estado_migracion 
                    FROM ". $this->_table." po WHERE po.id=".$id;
        return $this->db->query($query)->result();  
    }
    
    public function getPortadasMiCanal(){
        $query  = "SELECT id FROM ". $this->_table." WHERE tipo_portadas_id = 1";
        return $this->db->query($query)->result();
    }
    
    public function getExistePortadaXIdMongo($id){
        $query = "select * from " . $this->_table . " where id_mongo = '".$id."'";
        return  $this->db->query($query)->num_rows();
    }
}    
