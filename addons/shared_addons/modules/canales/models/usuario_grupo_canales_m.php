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
class Usuario_grupo_canales_m extends MY_Model {

    /**
     *  nombre de la tabla
     * @var string 
     */
    protected $_table = 'default_cms_usuario_group_canales';
    
    public function vd($var){
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }

    public function save($objBeanUsuariogrupoCanal){
         parent::insert(array(
                'canal_id' => $objBeanUsuariogrupoCanal->canal_id,
                'user_id'  => $objBeanUsuariogrupoCanal->user_id,
                'group_id' => $objBeanUsuariogrupoCanal->group_id,
                'estado' => $objBeanUsuariogrupoCanal->estado,
                'fecha_registro' => $objBeanUsuariogrupoCanal->fecha_registro,
                'usuario_registro' => $objBeanUsuariogrupoCanal->usuario_registro,
                'fecha_actualizacion' => $objBeanUsuariogrupoCanal->fecha_actualizacion,
                'usuario_actualizacion' => $objBeanUsuariogrupoCanal->usuario_actualizacion
        ));
        return true;           
    }

}