<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Grupo_Maestros_mp extends CI_Model {

    protected $_table = 'default_cms_grupo_maestros';
    protected $_table_canales = 'default_cms_canales';

    function getProgramasXId($id) {
        $query= "SELECT gm.id,gm.nombre,gm.descripcion,gm.alias,gm.categorias_id,gm.estado,gm.estado_migracion,ca.nombre AS 'nombre_ca',ca.alias AS 'alias_ca',ca.id_mongo AS 'idmongo_ca'
                        FROM ". $this->_table." gm INNER JOIN ". $this->_table_canales." ca ON gm.canales_id = ca.id
                        WHERE gm.tipo_grupo_maestro_id=3 AND gm.estado_migracion IN (0,9) AND gm.id =".$id;
        return $this->db->query($query)->result();  
    }
    
    function getMaestroDetalles(){
        $query = "SELECT grupo_maestro_id , COUNT(grupo_maestro_padre)  AS 'cant' FROM default_cms_grupo_detalles GROUP BY grupo_maestro_id ORDER BY cant DESC";
        return $this->db->query($query)->result();        
    }
    
        
}    
