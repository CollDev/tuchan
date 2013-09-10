<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//if ( ! function_exists('assigned_canales'))
//{
    function assigned_canales() {
        $CI = & get_instance();
        $CI->load->library('assignedcanales_lib');
        
        return $CI->assignedcanales_lib->canales_assigned();
    }
//}