<?php
class Admin extends Admin_Controller 
{  
     //protected $section = "items"; // This must match the name in the 'sections' field in details.php
 
     public function __construct()
     {  
         parent::__construct();  
         $this->load->model('canales/canales_m');
     }  
     
     function index() 
     {  
         echo 'este es el index de videos';
     }  
     
     function carga_unitaria($canal_id) 
     {
         // Obtener nombre del canal
         $canal_id = (int) $canal_id;         
         $canal = $this->canales_m->get($canal_id);
         
         $this->template
                    ->title($this->module_details['name'])
                    //->append_js('admin/filter.js')
                    //->set_partial('filters', 'admin/partials/filters')
                    ->set('canal', $canal)
                    ->set('carga_unitaria', 'carga_unitaria');

            $this->input->is_ajax_request()
                    ? $this->template->build('admin/tables/posts')
                    : $this->template->build('admin/carga_unitaria');
     }
     
     function carga_masiva() 
     {
         $this->template
                    ->title($this->module_details['name'])
                    //->append_js('admin/filter.js')
                    //->set_partial('filters', 'admin/partials/filters')
                    //->set('pagination', $pagination)
                    ->set('carga_masiva', 'carga_masiva');

            $this->input->is_ajax_request()
                    ? $this->template->build('admin/tables/posts')
                    : $this->template->build('admin/carga_unitaria');
     }
 }  
?>
