<?php
class Admin extends Admin_Controller 
{  
     //protected $section = "items"; // This must match the name in the 'sections' field in details.php
 
     public function __construct()
     {  
         parent::__construct();
         
         $this->load->library('form_validation');
         $this->load->helper('form');
         $this->load->helper('url');
         $this->load->model('canales/canales_m');
         $this->load->model('videos/videos_m');
         $this->load->model('tags/tags_m');
         $this->load->model('video_tags/video_tags_m');
     }  
     
     function index() 
     {  
         echo 'este es el index de videos';
     }  
     
     /**
      * Carga unitaria de video
      * @param int $canal_id
      */
     function carga_unitaria($canal_id = 0) 
     {
         $es_post = false;
         
        // Verificar si canal_id tiene un valor > 0
        if (isset($canal_id) && $canal_id > 0) {
            $canal_id = (int) $canal_id;            
        } else {
            $canal_id = $this->input->post('canal_id');
            $es_post = true;
        }
        
        // Obtener nombre del canal según id
        $canal = $this->canales_m->get($canal_id);
        
        if ($es_post) {
            
            // Validación de campos
            $this->form_validation->set_rules('titulo', 'Título', 'required|trim|xss_clean|max_length[150]');
            $this->form_validation->set_rules('editor1', 'Descripción', 'required|trim|xss_clean|max_length[500]');
            $this->form_validation->set_rules('fragmento', 'Fragmento', 'trim|xss_clean');
            $this->form_validation->set_rules('categoria', 'Categoria', 'trim|xss_clean');
            $this->form_validation->set_rules('tipo', 'Tipo', 'trim|xss_clean');
            $this->form_validation->set_rules('tematicas', 'Etiquetas Temáticas', 'required|trim|max_length[250]');
            $this->form_validation->set_rules('personajes', 'Etiquetas Personajes', 'required|trim|max_length[250]');
            $this->form_validation->set_rules('programa', 'Programa', 'xss_clean|trim|max_length[250]');
            $this->form_validation->set_rules('coleccion', 'Colección', 'xss_clean|trim|max_length[250]');
            $this->form_validation->set_rules('lista_rep', 'Lista de Reproducción', 'xss_clean|trim|max_length[250]');
            $this->form_validation->set_rules('fuente', 'Fuente', 'required|trim|xss_clean');
            $this->form_validation->set_rules('fec_pub_ini', 'Fecha Pub. Inicio', 'xss_clean|max_length[16]');
            $this->form_validation->set_rules('fec_pub_fin', 'Fecha Pub. Fin', 'xss_clean|max_length[16]');
            $this->form_validation->set_rules('fec_trans', 'Fecha Transmisión', 'xss_clean|max_length[10]');
            $this->form_validation->set_rules('hora_trans_ini', 'Hora Trans. Inicio', 'xss_clean|max_length[5]');
            $this->form_validation->set_rules('hora_trans_fin', 'Hora Trans. Fin', 'xss_clean|max_length[5]');
            $this->form_validation->set_rules('ubicacion', 'Ubicación', 'trim|xss_clean|max_length[100]');
            $this->form_validation->set_error_delimiters('<br /><span class="error">', '</span>');

            if ($this->form_validation->run() == FALSE) // validation hasn't been passed
            {
                // error en la validación
                //$this->load->view('frm_view');
            } else { 

                // Usuario logueado
                $user_id = (int) $this->session->userdata('user_id');
                
                // build array for the model			
                $video_data = array(
                                'titulo' => set_value('titulo'),
                                'descripcion' => set_value('editor1'),
                                'fragmento' => set_value('fragmento'),
                                'categorias_id' => set_value('categoria'),
                                'usuarios_id' => $user_id,
                                //'tematicas' => set_value('tematicas'),
                                //'personajes' => set_value('personajes'),
                                //'programa' => set_value('programa'),
                                //'coleccion' => set_value('coleccion'),
                                //'lista_rep' => set_value('lista_rep'),
                                'tipo_videos_id' => set_value('tipo'),
                                'fuente' => set_value('fuente'),
                                'fecha_publicacion_inicio' => set_value('fec_pub_ini'),
                                'fecha_publicacion_fin' => set_value('fec_pub_fin'),
                                'fecha_transmision' => set_value('fec_trans'),
                                'hora_transmision_inicio' => set_value('hora_trans_ini'),
                                'hora_transmision_fin' => set_value('hora_trans_fin'),
                                'ubicacion' => set_value('ubicacion'),
                                'canales_id' => $canal_id,
                                'estado' => '0', // Codificando
                                'fecha_registro' => date('Y-m-d H:i:s'),
                                'usuario_registro' => $user_id
                           );
                
                // Grabar los tags con sus respectivos tipos
                $tags_data = array(
                                'tematicas' => set_value('tematicas'),
                                'personajes' => set_value('personajes'),
                );
                
                //print_r($tags_data);exit;
                if ($this->tags_m->insert($tags_data) == TRUE) {
                    // insert tags
                }
                 
                // Debe grabarse también el programa, la colección y la lista de reproducción
                /** TODO **/
                
                // Grabar registro en la tabla
                if ($this->videos_m->insert($video_data) == TRUE) // La data fue grabada con exito
                {
                    
                    //redirect('videos/success');   // or whatever logic needs to occur
                } else {
                    echo 'Ocurrió un error...';
                    // Or whatever error handling is necessary
                }
            }             
        }                
    
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
     
     
     /**
      * Carga masiva de videos
      */
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
                    : $this->template->build('admin/carga_masiva');
     }
 }  
?>
