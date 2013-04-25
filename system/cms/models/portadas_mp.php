<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Portadas_mp extends CI_Model {

    protected $_table = 'default_cms_portadas';

    function getPortadasXId($id) {
        $query= "SELECT po.id, po.nombre, po.descripcion, po.tipo_portadas_id,IFNULL(po.origen_id,po.canales_id) AS 'origen_id',po.estado,po.id_mongo,po.estado_migracion 
                    FROM default_cms_portadas po WHERE po.id=".$id;
        return $this->db->query($query)->result();  
    }
    

}    
