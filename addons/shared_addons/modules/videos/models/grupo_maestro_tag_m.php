<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Modelo categoria
 *
 * metodos para obtener data de la tabla categorias
 *
 * @author		Johnny Huamani <jhuamani@idigital.pe>
 * @author		PyroCMS Dev Team
 * @package		Modules\videos\Models
 */
class Grupo_maestro_tag_m extends MY_Model {

    /**
     *  nombre de la tabla
     * @var string 
     */
    protected $_table = 'default_cms_grupo_maestro_tags';
    
    public function vd($var){
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }
    
    public function existRelacion($tag_id, $maestro_id){
        $returnValue = false;
        if($tag_id > 0){
            $query="SELECT * FROM ".$this->_table." WHERE tags_id = '".$tag_id."' AND grupo_maestros_id = '".$maestro_id."'";
            $result = $this->db->query($query)->result();
            if(count($result)>0){
                $returnValue = true;
            }            
        }
        return $returnValue;        
    }
    
    public function save($objBeanMaestroTag){
                 parent::insert(array(
                'grupo_maestros_id' => $objBeanMaestroTag->grupo_maestros_id,
                'tags_id'  => $objBeanMaestroTag->tags_id,
                'estado' => $objBeanMaestroTag->estado,
                'fecha_registro' => $objBeanMaestroTag->fecha_registro,
                'usuario_registro' => $objBeanMaestroTag->usuario_registro,
                'fecha_actualizacion' => $objBeanMaestroTag->fecha_actualizacion,
                'usuario_actualizacion' => $objBeanMaestroTag->usuario_actualizacion,
                'estado_migracion_sphinx' => $objBeanMaestroTag->estado_migracion_sphinx,
                'usuario_actualizacion' => $objBeanMaestroTag->usuario_actualizacion,
                'fecha_migracion_actualizacion_sphinx' => $objBeanMaestroTag->fecha_migracion_actualizacion_sphinx
        ));
        return true;       
    }
    public function deleteRelationTagVideo($maestro_id, $tag_id){
        $sql ='DELETE FROM '.$this->_table.' WHERE tags_id = '.$tag_id.' AND grupo_maestros_id ='.$maestro_id;
        $this->db->query($sql);
    }    

}