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
class Vw_video_m extends MY_Model {

    /**
     *  nombre de la tabla
     * @var string 
     */
    protected $_table = 'vw_video';

    /**
     * listado de categorias en orden ascendente alfabeticamente
     * @param array $where
     * @param string $order
     * @return array
     */
    public function getVideo($where = array(), $order = NULL) {
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
    public function getVideoDropDown($where = array(), $order = NULL) {
        $returnValue = array();
        $arrayData = $this->getVideo($where, $order);
        if (count($arrayData) > 0) {
            foreach ($arrayData as $index => $objTipo) {
                $returnValue[$objTipo->id] = $objTipo->nombre;
            }
        }
        $returnValue[0] = lang('videos:select_category');
        return $returnValue;
    }

}