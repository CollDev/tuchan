<?php defined('BASEPATH') OR exit('No direct script access allowed');
if (!function_exists('array_unique_multi')) {

    function array_unique_multi($datos = array()) {
        $id = array();
        $return = array();
                        
        foreach ($datos as $key =>  $dato) {
            if(!in_array($dato['id'],$id)){
                array_push($id,$dato['id']);
                array_push($return, $dato);
            }
        }        
        return $return;
    }

}
// Fin limpiar_caracteres_especiales