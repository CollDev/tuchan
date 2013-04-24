<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Secciones_mp extends CI_Model {

    protected $_table = 'default_cms_secciones';

    public function getSeccionesXId($id) {
        $query = "SELECT se.id,se.nombre,se.peso,se.templates_id,se.tipo_secciones_id,se.id_mongo AS mongo_se, po.id_mongo AS mongo_po,  se.estado,se.estado_migracion,fu_aliaspa(tipo_portadas_id,origen_id) AS 'alias_pa',po.tipo_portadas_id,po.origen_id
            FROM default_cms_secciones se INNER JOIN default_cms_portadas po ON se.portadas_id=po.id
            WHERE se.id=".$id;
        
        return $this->db->query($query)->result();
    }

}