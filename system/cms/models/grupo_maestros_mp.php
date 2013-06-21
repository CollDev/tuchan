<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Grupo_Maestros_mp extends CI_Model {

    protected $_table = 'default_cms_grupo_maestros';
    protected $_table_canales = 'default_cms_canales';
    protected $_view_maestro_videos = 'default_vw_maestros_videos';

    function getGrupoMaestroXId($tgm,$id) {
        $query= "SELECT gm.id,gm.nombre,gm.descripcion,gm.alias,gm.categorias_id,gm.estado,gm.estado_migracion,ca.nombre AS 'nombre_ca',ca.alias AS 'alias_ca',ca.id_mongo AS 'idmongo_ca',
                    (SELECT COUNT(id) FROM ".$this->_view_maestro_videos." vmv  WHERE  vmv.gm3 = 1 AND vmv.v='v') AS 'vi'
                        FROM ". $this->_table." gm INNER JOIN ". $this->_table_canales." ca ON gm.canales_id = ca.id
                        WHERE gm.tipo_grupo_maestro_id=".$tgm." AND gm.estado_migracion IN (0,9) AND gm.id =".$id;
        return $this->db->query($query)->result();  
    }
    
    function getMaestroDetalles(){
        $query = "SELECT id,grupo_maestro_id , COUNT(grupo_maestro_padre)  AS 'cant' FROM default_cms_grupo_detalles GROUP BY grupo_maestro_id ORDER BY cant DESC";
        return $this->db->query($query)->result();        
    }
    
    function getMaestroDetallesXId($id){
        $query = "SELECT * FROM default_cms_grupo_detalles WHERE  grupo_maestro_id =".$id;  
        return $this->db->query($query)->result();     
    }
    
    function deleteMaestroDetallesXId($id){
         $query = "delete from default_cms_grupo_detalles WHERE  id =".$id;  
        return $this->db->query($query);     
    }
        
}    
