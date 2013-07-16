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
class Categoria_m extends MY_Model {

    /**
     *  nombre de la tabla
     * @var string 
     */
    protected $_table = 'default_cms_categorias';

    /**
     * listado de categorias en orden ascendente alfabeticamente
     * @param array $where
     * @param string $order
     * @return array
     */
    public function getCategory($where = array(), $order = NULL) {
        $this->db->select("*");
        $this->db->from($this->_table);
        $this->db->where($where);
        if ($order != NULL) {
            $this->db->order_by($order);
        }
        
        return $this->db->get()->result();
    }
    /**
     * 
     * @param type $where
     * @param type $order
     * @return type
     */
    public function getCategoryDropDown($where = array(), $order = NULL) {
        $returnValue = array();
        $arrayData = $this->getCategory($where, $order);
        if (count($arrayData) > 0) {
            foreach ($arrayData as $index => $objTipo) {
                if($this->isParent($objTipo->id)){
                    $returnValue[$objTipo->nombre] = $this->getChildrenCategories($objTipo->id);
                }else{
                    $returnValue[$objTipo->id] = $objTipo->nombre;
                }
            }
        }

        return $returnValue;
    }
    
    public function getChildrenCategories($category_id){
        $returnValue = array();
        $arrayData = $this->getCategory(array("categorias_id"=>$category_id));
        if (count($arrayData) > 0) {
            foreach ($arrayData as $index => $objTipo) {
                    $returnValue[$objTipo->id] = $objTipo->nombre;
            }
        }
        return $returnValue;        
    }
    
    public function isParent($category_id){
        $returnValue = false;
        $query="SELECT * FROM ".$this->_table." WHERE categorias_id = '".$category_id."'";
        $result = $this->db->query($query)->result();
        if(count($result)>0){
            $returnValue = true;
        }
        
        return $returnValue;         
    }

}