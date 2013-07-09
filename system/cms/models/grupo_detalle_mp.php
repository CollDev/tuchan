<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modelo grupo detalle
 *
 * @author Joe Robles <joe.robles.pdj@gmail.com>
 */
class Grupo_detalle_mp extends MY_Model {

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
    public function getColeccionesList($where = array(), $order = NULL) {
        $this->db->select("*");
        $this->db->from($this->_table);
        $this->db->where($where);
        if ($order != NULL) {
            $this->db->order_by($order);
        }
        
        return $this->db->get()->result();
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
    
    public function existVideo($titulo, $canal_id,$maestro_id, $type){
        $returnValue = false;
        $query="SELECT * FROM ".$this->_table." WHERE upper(titulo) like '".  strtoupper($title)."' AND canales_id =".$canal_id;
        $result = $this->db->query($query)->result();
        if(count($result)>0){
            $returnValue = true;
        }
        
        return $returnValue;        
    }
}