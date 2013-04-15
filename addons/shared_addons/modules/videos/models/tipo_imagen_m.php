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
class Tipo_imagen_m extends MY_Model {

    /**
     *  nombre de la tabla
     * @var string 
     */
    protected $_table = 'default_cms_tipo_imagen';

    /**
     * listado de categorias en orden ascendente alfabeticamente
     * @param array $where
     * @param string $order
     * @return array
     */
    public function getTipoImagen($where = array(), $order = NULL) {
        $this->db->select("*");
        $this->db->from($this->_table);
        $this->db->where($where);
        if ($order != NULL) {
            $this->db->order_by($order);
        }
        $resultValue = $this->db->get()->result();
        return $resultValue;
    }
    
    public function listType(){
        $this->db->select("*");
        $this->db->from($this->_table);
        $this->db->where("id < 5");
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
    public function getTipoImagenDropDown($where = array(), $order = NULL) {
        $returnValue = array();
        $arrayData = $this->getTipoImagen($where, $order);
        if (count($arrayData) > 0) {
            foreach ($arrayData as $index => $objTipo) {
                $returnValue[$objTipo->id] = $objTipo->nombre;
            }
        }
        $returnValue[0] = lang('videos:select_category');
        return $returnValue;
    }

}