<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Modelo categoria
 *
 * metodos para obtener data de la tabla categorias
 *
 * @author Joe Robles <joe.robles.pdj@gmail.com>
 */
class Categoria_mp extends MY_Model {

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
    public function getCategoriasList($where = array(), $order = NULL) {
        $this->db->select("*");
        $this->db->from($this->_table);
        $this->db->where($where);
        if ($order != NULL) {
            $this->db->order_by($order);
        }
        
        return $this->db->get()->result();
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