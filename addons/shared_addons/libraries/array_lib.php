<?php

class Array_lib extends MX_Controller {

    function array_unique_multi($datos = array()) {
        $id = array();
        $return = array();

        foreach ($datos as $dato) {
            if (!in_array($dato['id'], $id)) {
                array_push($id, $dato['id']);
                array_push($return, $dato);
            }
        }
        return $return;
    }

}
