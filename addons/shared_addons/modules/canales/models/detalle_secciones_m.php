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
class Detalle_secciones_m extends MY_Model {

    /**
     *  nombre de la tabla
     * @var string 
     */
    protected $_table = 'default_cms_detalle_secciones';
    
    public function vd($var){
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }

    public function save($objBeanSeccion){
        $objBeanSeccion->id = parent::insert(array(
                'secciones_id' => $objBeanSeccion->secciones_id,
                'reglas_id'  => $objBeanSeccion->reglas_id,
                'videos_id' => $objBeanSeccion->videos_id,
                'grupo_maestros_id' => $objBeanSeccion->grupo_maestros_id,
                'categorias_id' => $objBeanSeccion->categorias_id,
                'tags_id' => $objBeanSeccion->tags_id,
                'imagenes_id' => $objBeanSeccion->imagenes_id,
                'peso' => $objBeanSeccion->peso,
                'descripcion_item' => $objBeanSeccion->descripcion_item,
                //'templates_id' => $objBeanSeccion->templates_id,
                'estado' => $objBeanSeccion->estado,
                'fecha_registro' => $objBeanSeccion->fecha_registro,
                'usuario_registro' => $objBeanSeccion->usuario_registro,
                'estado_migracion' => $objBeanSeccion->estado_migracion,
                'fecha_migracion' => $objBeanSeccion->fecha_migracion,
                'fecha_migracion_actualizacion' => $objBeanSeccion->fecha_migracion_actualizacion
        ));
        return $objBeanSeccion;           
    }
    
    public function getListaOriginal($array_index){
        $sql = 'SELECT * FROM '.$this->_table.' WHERE id IN('.implode(',',$array_index).') ORDER BY peso ASC';
        $result = $this->db->query($sql)->result();
        return $result;
    }

}