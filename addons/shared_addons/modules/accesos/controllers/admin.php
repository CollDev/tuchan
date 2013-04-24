<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin controller for canales module
 *
 * @author 	MiCanal Dev Team 
 */
class Admin extends Admin_Controller 
{
    
    /**
     * Constructor method
     * 
     * @return void
     */
    public function __construct() 
    {
        parent::__construct();
        
        $this->load->model('canales/canales_m');        
        $this->load->model('canales/usuario_group_canales_m');        
        $this->lang->load('canales/canales');
        $this->lang->load('accesos');        
    }

    /**
     * Index method, lists all canales
     *
     * @return void
     */
    public function index($id = 0) 
    {               
        if ($this->session->userdata['group'] == 'admin') {
            
            // Get the user's data
            if ( ! ($member = $this->ion_auth->get_user($id)))
            {
                    $this->session->set_flashdata('error', lang('user_edit_user_not_found_error'));
                    redirect('admin/users');
            }
            
            // Reglas de validacion
            $this->form_validation->set_rules('action_to[]', 'Acceso', 'required');
            $this->form_validation->set_rules('default[]', 'Predeterminado', 'required');            

            // Lista de canales
            $canales = $this->canales_m->getCanales();   
                        
            // Datos del usuario seleccionado
            $data_usuario = $this->ion_auth->get_user($id);
            $usuario =  $data_usuario->display_name;

            // Verificar si usuario ya tiene canales asignados
            $canales_asignados = $this->usuario_group_canales_m->get_canales_by_usuario($id);
            $canalesx =  json_decode(json_encode($canales_asignados), true);
            
            if ($this->form_validation->run() === TRUE)
            {                                            
                if (TRUE) {
                    
                    // Recibe datos del formulario
                    $post_data['canal_id'] = $this->input->post('action_to');
                    $predeterminado = $this->input->post('default');
                    $predeterminado = $predeterminado[0];
                    
                    // Convierte canalesx a un array simple                    
                    $i=0;
                    $arraytemp = array();                    
                    foreach ($canalesx as $canal) {
                        $arraytemp[$i] = $canal['canal_id'];
                        $i++;
                    }
                    
                    // Cuando se quitan canales
                    $quitar = array_diff($arraytemp, $post_data['canal_id']);                    

                    if (count($quitar) > 0) {
                        
                        // Actualizar accesos                        
                        $params['estado'] = 0;
                        $params['fecha_actualizacion'] = date("Y-m-d H:i:s");
                        $params['usuario_actualizacion'] = $this->current_user->id;
                        foreach ($quitar as $canal_id) {
                            $this->usuario_group_canales_m->update($canal_id, $id, $params);
                        }                                                
                    }
                                        
                    // Accesos                        
                    $params['user_id']  = $data_usuario->user_id;
                    $params['group_id'] = $data_usuario->group_id;
                    $params['estado'] = 1;
                    $params['fecha_registro'] = date("Y-m-d H:i:s");
                    $params['usuario_registro'] = $this->current_user->id;
                    $params['predeterminado'] = 0;

                    $activos=array();
                    $i = 0;
                    foreach ($canalesx as $value) {
                        if($value["estado"] == 1){
                            $activos[$i]=$value["canal_id"];    
                            $i++;        
                        }    
                    }
                    
                    // Cuando se agregan nuevos canales y activan canales asignados
                    $seleccionados = array_diff($post_data['canal_id'],$activos); 

                    foreach ($seleccionados as $value) {
                        if(array_search($value, $arraytemp) === FALSE){
                            $params['canal_id'] =$value;        
                            $this->usuario_group_canales_m->insert($params);        
                        } else {
                            $this->usuario_group_canales_m->update($value, $id, $params);            
                        }
                    }
                    
                    // Grabar canal predeterminado
                    $params = array(); 
                    $params['predeterminado'] = $predeterminado;
                    $this->usuario_group_canales_m->update_predeterminado($params, $predeterminado, $id);
                    
                    $this->session->set_flashdata('msg_success', 'La asignación de canales fue realizada con éxito.');
                    redirect('admin/accesos/index/' . $data_usuario->user_id);                    
                }
            }           

            // Do we need to unset the layout because the request is ajax?
            $this->input->is_ajax_request() and $this->template->set_layout(FALSE);

            $this->template
                    ->title($this->module_details['name'])
                    ->set_partial('canales', 'admin/tables/accesos')
                    ->append_js('module::jquery.alerts.js')
                    ->append_css('module::jquery.alerts.css')
                    ->set('usuario_id', $data_usuario->user_id)
                    ->set('usuario', $usuario)
                    ->set('canales_asignados', $canales_asignados)            
                    ->set('canales', $canales);
            
            $this->input->is_ajax_request() ? $this->template->build('admin/tables/accesos') : $this->template->build('admin/index');            
            
        } else {

            redirect('admin#');
        }
    }


}

/* End of file admin.php */