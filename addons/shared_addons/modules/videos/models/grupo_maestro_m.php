<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Modelo categoria
 *
 * metodos para obtener data de la tabla los tipos de maestro
 *
 * @author		Johnny Huamani <jhuamani@idigital.pe>
 * @author		PyroCMS Dev Team
 * @package		Modules\videos\Models
 */
class Grupo_maestro_m extends MY_Model {

    /**
     *  nombre de la tabla
     * @var string 
     */
    protected $_table = 'default_cms_grupo_maestros';

    /**
     * listado de categorias en orden ascendente alfabeticamente
     * @param array $where
     * @param string $order
     * @return array
     */
    public function getCollection($where = array(), $order = NULL) {
        $this->db->select("*");
        $this->db->from($this->_table);
        $this->db->where($where);
        if ($order != NULL) {
            $this->db->order_by($order);
        }
        $resultValue = $this->db->get()->result();
        return $resultValue;
    }

    public function getCollectionDropDown($where, $order) {
        $returnValue = array();
        $returnValue[0] = lang('videos:select_list');
        $arrayData = $this->getCollection($where, $order);
        if (count($arrayData) > 0) {
            foreach ($arrayData as $index => $objTipo) {
                if($objTipo->estado < $this->config->item('estado:eliminado')){
                    $returnValue[$objTipo->id] = $objTipo->nombre;
                }
            }
        }
        
        return $returnValue;
    }

    public function save_maestro($objBeanMaestro) {
        $objBeanMaestro->id = parent::insert(array(
                    'nombre' => $objBeanMaestro->nombre,
                    'descripcion' => $objBeanMaestro->descripcion,
                    'alias' => url_title(strtolower(convert_accented_characters($objBeanMaestro->alias))),
                    'tipo_grupo_maestro_id' => $objBeanMaestro->tipo_grupo_maestro_id,
                    'canales_id' => $objBeanMaestro->canales_id,
                    'categorias_id' => $objBeanMaestro->categorias_id,
                    'cantidad_suscriptores' => $objBeanMaestro->cantidad_suscriptores,
                    'peso' => $objBeanMaestro->peso,
                    'id_mongo' => $objBeanMaestro->id_mongo,
                    'estado' => $objBeanMaestro->estado,
                    'fecha_registro' => $objBeanMaestro->fecha_registro,
                    'usuario_registro' => $objBeanMaestro->usuario_registro,
                    'fecha_actualizacion' => $objBeanMaestro->fecha_actualizacion,
                    'usuario_actualizacion' => $objBeanMaestro->usuario_actualizacion,
                    'estado_migracion' => $objBeanMaestro->estado_migracion,
                    'fecha_migracion' => $objBeanMaestro->fecha_migracion,
                    'fecha_migracion_actualizacion' => $objBeanMaestro->fecha_migracion_actualizacion,
                    'comentarios' => $objBeanMaestro->comentarios,
                    'fecha_transmision_inicio' => $objBeanMaestro->fecha_transmision_inicio,
                    'fecha_transmision_fin' => $objBeanMaestro->fecha_transmision_inicio,
                    'horario_transmision_inicio' => $objBeanMaestro->horario_transmision_inicio,
                    'horario_transmision_fin' => $objBeanMaestro->horario_transmision_inicio,
                    'estado_migracion_sphinx' => $objBeanMaestro->estado_migracion_sphinx,
                    'fecha_migracion_sphinx' => $objBeanMaestro->fecha_migracion_sphinx,
                    'fecha_migracion_actualizacion_sphinx' => $objBeanMaestro->fecha_migracion_actualizacion_sphinx
        ));
        return $objBeanMaestro;
    }
    
    public function existNameMaestro($name, $canal_id){
        $returnValue = false;
        $this->db->select("*");
        $this->db->from($this->_table);
        $this->db->where(array("canales_id"=>$canal_id));
        $objCollection = $this->db->get()->result();
        if(count($objCollection)>0){
            foreach ($objCollection as $index=>$objMaestro){
                if(trim(strtoupper($objMaestro->nombre)) == trim(strtoupper($name))){
                    $returnValue = true;
                    break;
                }
            }
        }
        return $returnValue;
    }
    
    public function getListCollection($array_id_maestro){
        $query="SELECT * FROM ".$this->_table." WHERE id IN (".implode(',',$array_id_maestro).")";
        $result = $this->db->query($query)->result();
        return $result;        
    }
    
    public function vd($var){
        echo"<pre>";
        print_r($var);
        echo"</pre>";
    }
    
    public function update($id, $array){
        parent::update($id, $array);
        //disaramos un proceso de la libreria portadas para actualizar estados de maestros en las portadas y secciones
        $this->portadas_lib->actualizar_maestro($id);
        
        //$this->procesos_lib->actualizarDetalleSecciones();
    }    

}