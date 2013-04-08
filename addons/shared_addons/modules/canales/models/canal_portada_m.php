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
class Canal_portada_m extends MY_Model {

    /**
     *  nombre de la tabla
     * @var string 
     */
    protected $_table = 'default_cms_canal_portadas';
    
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

}