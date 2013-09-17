<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function assigned_canales() {
    $CI = & get_instance();
    $CI->load->library('assignedcanales_lib');

    return $CI->assignedcanales_lib->canales_assigned();
}
function todos_canales() {
    $CI = & get_instance();
    $CI->load->library('assignedcanales_lib');

    return $CI->assignedcanales_lib->canales_all();
}