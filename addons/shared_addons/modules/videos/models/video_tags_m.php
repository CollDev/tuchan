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
class Video_tags_m extends MY_Model {

    /**
     *  nombre de la tabla
     * @var string 
     */
    protected $_table = 'default_cms_video_tags';

    /**
     * listado de categorias en orden ascendente alfabeticamente
     * @param array $where
     * @param string $order
     * @return array
     */
    public function getVideoTags($where = array(), $order = NULL) {
        $this->db->select("*");
        $this->db->from($this->_table);
        $this->db->where($where);
        if ($order != NULL) {
            $this->db->order_by($order);
        }
        $resultValue = $this->db->get()->result();
        return $resultValue;
    }
    
    public function vd($var){
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }

    /**
     * 
     * @param type $where
     * @param type $order
     * @return type
     */
    public function getVideoTagsDropDown($where = array(), $order = NULL) {
        $returnValue = array();
        $arrayData = $this->getVideoTags($where, $order);
        if (count($arrayData) > 0) {
            foreach ($arrayData as $index => $objTipo) {
                $returnValue[$objTipo->id] = $objTipo->nombre;
            }
        }
        $returnValue[0] = lang('videos:select_category');
        return $returnValue;
    }
    
    public function saveVideoTags($objBeanVideoTag){
                 parent::insert(array(
                'tags_id' => $objBeanVideoTag->tags_id,
                'videos_id'  => $objBeanVideoTag->videos_id,
                'estado' => $objBeanVideoTag->estado,
                'fecha_registro' => $objBeanVideoTag->fecha_registro,
                'usuario_registro' => $objBeanVideoTag->usuario_registro,
                'fecha_actualizacion' => $objBeanVideoTag->fecha_actualizacion,
                'usuario_actualizacion' => $objBeanVideoTag->usuario_actualizacion,
                'estado_migracion_sphinx' => $objBeanVideoTag->estado_migracion_sphinx,
                'usuario_actualizacion' => $objBeanVideoTag->usuario_actualizacion,
                'fecha_migracion_actualizacion_sphinx' => $objBeanVideoTag->fecha_migracion_actualizacion_sphinx
        ));
        return true;       
    }
    
    public function existRelacion($tag_id,$video_id){
        $returnValue = false;
        if($tag_id > 0){
            $query="SELECT * FROM ".$this->_table." WHERE tags_id = '".$tag_id."' AND videos_id = '".$video_id."'";
            $result = $this->db->query($query)->result();
            if(count($result)>0){
                $returnValue = true;
            }            
        }
        return $returnValue;        
    }
    
    public function deleteRelationTagVideo($video_id, $tag_id){
        $sql ='DELETE FROM '.$this->_table.' WHERE tags_id = '.$tag_id.' AND videos_id ='.$video_id;
        $this->db->query($sql);
    }


}