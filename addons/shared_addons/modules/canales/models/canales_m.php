<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroCMS Settings Model
 *
 * Allows for an easy interface for site settings
 *
 * @author		Dan Horrigan <dan@dhorrigan.com>
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Modules\Settings\Models
 */
class Canales_m extends MY_Model {

    protected $_table = 'default_cms_canales';

    /**
     * Sections
     *
     * Gets all the sections (modules) from the settings table.
     *
     * @access	public
     * @return	array
     */
    public function sections() {
        $sections = $this->select('module')
                ->distinct()
                ->where('module != ""')
                ->get_all();

        $result = array();

        foreach ($sections as $section) {
            $result[] = $section->module;
        }

        return $result;
    }

    public function publish($id = 0) {
        return parent::update($id, array('estado' => '1'));
    }

    public function getCanalDropDown($where, $order) {
        $returnValue = array();
        $arrayData = $this->getCanales($where, $order);
        if (count($arrayData) > 0) {
            foreach ($arrayData as $index => $objTipo) {
                $returnValue[$objTipo->id] = $objTipo->nombre;
            }
        }
        $returnValue[0] = lang('videos:select_channel');
        return $returnValue;
    }
    
    public function getCanales($where = array(), $order = NULL) {
        $this->db->select("*");
        $this->db->from($this->_table);
        $this->db->where($where);
        if ($order != NULL) {
            $this->db->order_by($order);
        }
        $resultValue = $this->db->get()->result();
        return $resultValue;
    }
    
    public function save($objBeanCanal){
        $objBeanCanal->id = parent::insert(array(
                'tipo_canales_id' => $objBeanCanal->tipo_canales_id,
                'alias'  => $objBeanCanal->alias,
                'nombre' => $objBeanCanal->nombre,
                'descripcion' => $objBeanCanal->descripcion,
                'apikey' => $objBeanCanal->apikey,
                'playerkey' => $objBeanCanal->playerkey,
                'id_mongo' => $objBeanCanal->id_mongo,
                'cantidad_suscriptores' => $objBeanCanal->cantidad_suscriptores,
                'estado' => $objBeanCanal->estado,
                'fecha_registro' => $objBeanCanal->fecha_registro,
                'usuario_registro' => $objBeanCanal->usuario_registro,
                'fecha_actualizacion' => $objBeanCanal->fecha_actualizacion,
                'usuario_actualizacion' => $objBeanCanal->usuario_actualizacion,
                'estado_migracion' => $objBeanCanal->estado_migracion,
                'fecha_migracion' => $objBeanCanal->fecha_migracion,
                'fecha_migracion_actualizacion' => $objBeanCanal->fecha_migracion_actualizacion
        ));
        return $objBeanCanal;           
    }
  
    public function actualizar($objBeanCanal){
        parent::update($objBeanCanal->id, array("tipo_canales_id"=>$objBeanCanal->tipo_canales_id,
            "alias"=>$objBeanCanal->alias,
            "nombre"=>$objBeanCanal->nombre,
            "descripcion"=>$objBeanCanal->descripcion,
            "apikey"=>$objBeanCanal->apikey,
            "playerkey"=>$objBeanCanal->playerkey,
            "fecha_actualizacion"=>$objBeanCanal->fecha_actualizacion,
            "usuario_actualizacion"=>$objBeanCanal->usuario_actualizacion,
            "estado_migracion"=>$objBeanCanal->estado_migracion,
            "estado_migracion_sphinx"=>$objBeanCanal->estado_migracion_sphinx));
    }

}

/* End of file settings_m.php */