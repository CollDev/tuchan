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
class Portada_m extends MY_Model {

    /**
     *  nombre de la tabla
     * @var string 
     */
    protected $_table = 'default_cms_portadas';

    /**
     * listado de categorias en orden ascendente alfabeticamente
     * @param array $where
     * @param string $order
     * @return array
     */
    public function getPortada($where = array(), $order = NULL) {
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
    public function getPortadaDropDown($where = array(), $order = NULL) {
        $returnValue = array();
        $arrayData = $this->getPortada($where, $order);
        if (count($arrayData) > 0) {
            foreach ($arrayData as $index => $objTipo) {
                $returnValue[$objTipo->id] = $objTipo->nombre;
            }
        }
        $returnValue[0] = lang('videos:select_category');
        return $returnValue;
    }
 
    public function save($objBeanPortada){
        $objBeanPortada->id = parent::insert(array(
                'canales_id' => $objBeanPortada->canales_id,
                'nombre' => $objBeanPortada->nombre,
                'descripcion'  => $objBeanPortada->descripcion,
                'tipo_portadas_id' => $objBeanPortada->tipo_portadas_id,
                'origen_id' => $objBeanPortada->origen_id,
                'estado' => $objBeanPortada->estado,
                'fecha_registro' => $objBeanPortada->fecha_registro,
                'usuario_registro' => $objBeanPortada->usuario_registro,
                'fecha_actualizacion' => $objBeanPortada->fecha_actualizacion,
                'usuario_actualizacion' => $objBeanPortada->usuario_actualizacion,
                'id_mongo' => $objBeanPortada->id_mongo,
                'estado_migracion' => $objBeanPortada->estado_migracion,
                'fecha_migracion' => $objBeanPortada->fecha_migracion,
                'fecha_migracion_actualizacion' => $objBeanPortada->fecha_migracion_actualizacion
        ));
        return $objBeanPortada;           
    }
    
    public function ubicar_primero_portada_canal($canal_id){
        $query = "UPDATE " . $this->_table . " SET fecha_registro = '".date("Y-m-d H:i:s")."' WHERE canales_id =" . $canal_id . " AND tipo_portadas_id IN (" . $this->config->item('portada:canal') . ")";
        $result = $this->db->query($query);
        return $result;        
    }
    
    public function update($id, $array){
        
        parent::update($id, $array);
        
        $this->procesos_lib->curlGenerarPortadasMiCanalXId($id);
    }    

}