<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Accesos Events Class
 * 
 * @package     PyroCMS
 * @subpackage  Accesos Module
 * @category    events
 * @author      Gaby
 */
class Events_Accesos
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
            $canales_usuario = $this->ci->usuario_group_canales_m->get_canales_activos_by_usuario($data->user_id);

            if (count($canales_usuario) > 0) {
             
                // Obtener todos los canales que pertenecen al usuario
                foreach ($canales_usuario as $canal_usr) {
                    $canal_usuario[] = array(
                        'canal_id' => $canal_usr->canal_id,
                        'canal' => $canal_usr->nombre,
                    );
                }

                // Guardar en sesion los id de canales y tipo de usuario
                $this->ci->session->set_userdata("canales_usuario", $canal_usuario);
            }
        }
    }
    
}
/* End of file events.php */