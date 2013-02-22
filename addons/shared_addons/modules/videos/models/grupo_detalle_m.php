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
class Grupo_detalle_m extends MY_Model {

    /**
     *  nombre de la tabla
     * @var string 
     */
    protected $_table = 'default_cms_grupo_detalles';

    /**
     * listado de categorias en orden ascendente alfabeticamente
     * @param array $where
     * @param string $order
     * @return array
     */
    public function getGrupoDetalle($where = array(), $order = NULL) {
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
    public function getGrupoDetalleDropDown($where = array(), $order = NULL) {
        $returnValue = array();
        $arrayData = $this->getGrupoDetalle($where, $order);
        if (count($arrayData) > 0) {
            foreach ($arrayData as $index => $objTipo) {
                $returnValue[$objTipo->id] = $objTipo->nombre;
            }
        }
        $returnValue[0] = lang('videos:select_category');
        return $returnValue;
    }

    public function saveMaestroDetalle($objBeanMaestroDetalle) {
        $objBeanMaestroDetalle->id = parent::insert(array(
                    'grupo_maestro_padre' => $objBeanMaestroDetalle->grupo_maestro_padre,
                    'grupo_maestro_id' => $objBeanMaestroDetalle->grupo_maestro_id,
                    'tipo_grupo_maestros_id' => $objBeanMaestroDetalle->tipo_grupo_maestros_id,
                    'video_id' => $objBeanMaestroDetalle->video_id,
                    'id_mongo' => $objBeanMaestroDetalle->id_mongo,
                    'estado' => $objBeanMaestroDetalle->estado,
                    'fecha_registro' => $objBeanMaestroDetalle->fecha_registro,
                    'usuario_registro' => $objBeanMaestroDetalle->usuario_registro,
                    'fecha_actualizacion' => $objBeanMaestroDetalle->fecha_actualizacion,
                    'usuario_actualizacion' => $objBeanMaestroDetalle->usuario_actualizacion,
                    'estado_migracion' => $objBeanMaestroDetalle->estado_migracion,
                    'fecha_migracion' => $objBeanMaestroDetalle->fecha_migracion,
                    'fecha_migracion_actualizacion' => $objBeanMaestroDetalle->fecha_migracion_actualizacion
        ));
        return $objBeanMaestroDetalle;
    }
    
    public function updateMaestroDetalle($objBeanMaestroDetalle){
        return parent::update($objBeanMaestroDetalle->id, array(
            'fecha_actualizacion' => $objBeanMaestroDetalle->fecha_actualizacion,
            'usuario_actualizacion' => $objBeanMaestroDetalle->usuario_actualizacion,
            'grupo_maestro_padre' => $objBeanMaestroDetalle->grupo_maestro_padre,
            'tipo_grupo_maestros_id' => $objBeanMaestroDetalle->tipo_grupo_maestros_id
            ));        
    }

}