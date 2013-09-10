<?php
/**
 * Complements cmsapi
 *
 * @author Joe Robles <joe.robles.pdj@gmail.com>
 */
class assignedCanales_lib extends MX_Controller {
    
    public function __construct()
    {
        $this->load->model('canales/usuario_group_canales_m');
    }
    
    public function canales_assigned()
    {
        if (isset($this->session->userdata['id'])) {
            return $this->usuario_group_canales_m->get_canales_activos_by_usuario($this->session->userdata['id']);
        }
    }
}