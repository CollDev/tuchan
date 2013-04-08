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
class Tags_m extends MY_Model {

    /**
     *  nombre de la tabla
     * @var string 
     */
    protected $_table = 'default_cms_tags';

    /**
     * listado de categorias en orden ascendente alfabeticamente
     * @param array $where
     * @param string $order
     * @return array
     */
    public function getTags($where = array(), $order = NULL) {
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
    public function getTagsDropDown($where = array(), $order = NULL) {
        $returnValue = array();
        $arrayData = $this->getTags($where, $order);
        if (count($arrayData) > 0) {
            foreach ($arrayData as $index => $objTipo) {
                $returnValue[$objTipo->id] = $objTipo->nombre;
            }
        }
        $returnValue[0] = lang('videos:select_category');
        return $returnValue;
    }
    
    public function saveTag($objBeanTag){
        $objBeanTag->id = parent::insert(array(
                'tipo_tags_id' => $objBeanTag->tipo_tags_id,
                'nombre'  => $objBeanTag->nombre,
                'descripcion' => $objBeanTag->descripcion,
                'alias' => $objBeanTag->alias,
                'estado' => $objBeanTag->estado,
                'fecha_registro' => $objBeanTag->fecha_registro,
                'usuario_registro' => $objBeanTag->usuario_registro,
                'fecha_actualizacion' => $objBeanTag->fecha_actualizacion,
                'usuario_actualizacion' => $objBeanTag->usuario_actualizacion,
                'estado_migracion' => $objBeanTag->estado_migracion,
                'fecha_migracion' => $objBeanTag->fecha_migracion,
                'fecha_migracion_actualizacion' => $objBeanTag->fecha_migracion_actualizacion,
                'estado_migracion_sphinx' => $objBeanTag->estado_migracion_sphinx,
                'fecha_migracion_sphinx' => $objBeanTag->fecha_migracion_sphinx,
                'fecha_migracion_actualizacion_sphinx' => $objBeanTag->fecha_migracion_actualizacion_sphinx
        ));
        return $objBeanTag;           
    }
    
    public function existTag($tag,$tag_type){
        $returnValue = false;
        $query="SELECT * FROM ".$this->_table." WHERE tipo_tags_id = '".$tag_type."' AND UPPER(nombre) LIKE '".  strtoupper($tag)."'";
        $result = $this->db->query($query)->result();
        if(count($result)>0){
            $returnValue = true;
        }
        return $returnValue;
    }
    
    public function getIdTag($tag,$tag_type){
        $returnValue = 0;
        $query="SELECT * FROM ".$this->_table." WHERE tipo_tags_id = '".$tag_type."' AND UPPER(nombre) LIKE '".  strtoupper($tag)."'";
        $result = $this->db->query($query)->result();
        if(count($result)>0){
            foreach($result as $index=>$objTag){
                $returnValue = $objTag->id;
            }
        }
        return $returnValue;        
    }
    
    public function getListTags($arrayIdTags, $tag_type){
        $tag_id = implode(",", $arrayIdTags);
        $query="SELECT * FROM ".$this->_table." WHERE tipo_tags_id = '".$tag_type."' AND id IN (".$tag_id.")";
        $result = $this->db->query($query)->result();
        return $result;         
    }
    
    public function getTagsByType($term,$type){
        $query="SELECT nombre as value FROM ".$this->_table." WHERE tipo_tags_id = '".$type."' AND upper(nombre) LIKE '%".$term."%'";
        $result = $this->db->query($query)->result();
        return $result;         
    }
    
    public function getTagsByIdTagsByType($arrayIdTags, $type_tag){
        $tag_id = implode(",", $arrayIdTags);
        $query="SELECT * FROM ".$this->_table." WHERE tipo_tags_id = '".$type_tag."' AND id IN (".$tag_id.")";
        $result = $this->db->query($query)->result();
        return $result;         
    }


}