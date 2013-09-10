<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Canales Events Class
 * 
 * @package     PyroCMS
 * @subpackage  Canales Module
 * @category    events
 * @author      Gaby
 */
class Events_Canales
{
    protected $ci;
    
    public function __construct()
    {
        $this->ci = &get_instance();        
        Events::register('post_admin_login', array($this, 'run'));
    }
    
    // this will be triggered by the Events::trigger('admin_controller') code in Admin_Controller.php
    public function run()
    {
        $data = $this->ci->current_user ? $this->ci->current_user : $this->ci->ion_auth->get_user();
        
        if ($data->user_id > 0) {         

            $this->ci->load->model('canales/usuario_group_canales_m'); 
            $canales_usuario = $this->ci->usuario_group_canales_m->get_canales_by_usuario($data->user_id);

            if (count($canales_usuario) > 0) {
             
                // Obtener todos los canales que pertenecen al usuario
                foreach ($canales_usuario as $canal_usr) {
                    $canal_usuario[] = array(
                        'canal_id' => $canal_usr->canal_id,
                        'canal' => $canal_usr->nombre,
                    );
                }
            }
        }
    }
    
}
/* End of file events.php */