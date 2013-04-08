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
class Portada_secciones_m extends MY_Model {

    /**
     *  nombre de la tabla
     * @var string 
     */
    protected $_table = 'default_cms_portada_secciones';
    
    public function vd($var){
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }

    public function save($objBeanSeccion){
        $objBeanSeccion->id = parent::insert(array(
                'portadas_id' => $objBeanSeccion->portadas_id,
                'secciones_id'  => $objBeanSeccion->secciones_id,
                'peso' => $objBeanSeccion->peso,
                'estado' => $objBeanSeccion->estado,
                'fecha_registro' => $objBeanSeccion->fecha_registro,
                'usuario_registro' => $objBeanSeccion->usuario_registro,
                'fecha_actualizacion' => $objBeanSeccion->fecha_actualizacion,
                'usuario_actualizacion' => $objBeanSeccion->usuario_actualizacion,
                'estado_migracion' => $objBeanSeccion->estado_migracion,
                'fecha_migracion' => $objBeanSeccion->fecha_migracion,
                'fecha_migracion_actualizacion' => $objBeanSeccion->fecha_migracion_actualizacion
        ));
        return $objBeanSeccion;           
    }

}