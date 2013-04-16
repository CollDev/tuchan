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
class Tipo_secciones_m extends MY_Model {

    /**
     *  nombre de la tabla
     * @var string 
     */
    protected $_table = 'default_cms_tipo_secciones';
    
    public function vd($var){
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }

    public function save($objBeanCanalPortada){
        $objBeanCanalPortada->id = parent::insert(array(
                'canal_id' => $objBeanCanalPortada->canal_id,
                'portada_id'  => $objBeanCanalPortada->portada_id,
                'estado' => $objBeanCanalPortada->estado,
                'fecha_registro' => $objBeanCanalPortada->fecha_registro,
                'usuario_registro' => $objBeanCanalPortada->usuario_registro,
                'fecha_actualizacion' => $objBeanCanalPortada->fecha_actualizacion,
                'usuario_actualizacion' => $objBeanCanalPortada->usuario_actualizacion
        ));
        return $objBeanCanalPortada;           
    }
    
    /**
     * 
     * @param type $where
     * @param type $order
     * @return type
     */
    public function getSeccionDropDown($where = array(), $order = NULL) {
        $returnValue = array();
        $arrayData = $this->getSeccion($where, $order);
        if (count($arrayData) > 0) {
            foreach ($arrayData as $index => $objTipo) {
                $returnValue[$objTipo->id] = $objTipo->nombre;
            }
        }
        //$returnValue[0] = lang('videos:select_category');
        return $returnValue;
    }
    /**
     * listado de categorias en orden ascendente alfabeticamente
     * @param array $where
     * @param string $order
     * @return array
     */
    public function getSeccion($where = array(), $order = NULL) {
        $this->db->select("*");
        $this->db->from($this->_table);
        $this->db->where($where);
        if ($order != NULL) {
            $this->db->order_by($order);
        }
        $resultValue = $this->db->get()->result();
        return $resultValue;
    }    

}