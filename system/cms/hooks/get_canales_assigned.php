<?php defined('BASEPATH') OR exit('No direct script access allowed.');

class GetCanalesAssigned extends Admin_Controller
{
    /**
     * Constructor method
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('canales/usuario_group_canales_m');
    }
    
    public function canales_assigned()
    {
        $objCanales_usuario = $this->usuario_group_canales_m->get_canales_activos_by_usuario($this->session->userdata['id']);
        $CI_config =& load_class('Config');
        $CI_config->set_item('objCanales_usuario', $objCanales_usuario);
    }
}