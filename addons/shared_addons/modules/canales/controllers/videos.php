<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin controller for canales module
 *
 * @author 	MiCanal Dev Team 
 */
class Videos extends Admin_Controller 
{

	/**
	 * Validation array
	 * 
	 * @var array
	 */
	private $validation_rules = array();

	/**
	 * Constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->load->model('canales_m');
		$this->load->model('videos/videos_m');
		/*$this->load->library('settings');
		$this->load->library('form_validation');*/
		$this->lang->load('canales');
		$this->lang->load('videos/videos');
                $this->load->model('videos/grupo_detalle_m');
                $this->load->model('videos/grupo_maestro_m');
                $this->load->model('videos/tipo_maestro_m');                
	}

	/**
	 * Index method, lists all canales
	 *
	 * @return void
	 */
	public function index()
	{
            //set the base/default where clause
            //$base_where = array('status' => '1');
            $base_where = array();

            //add post values to base_where if f_module is posted
            //if ($this->input->post('f_category')) 	$base_where['category'] = $this->input->post('f_category');
            //if ($this->input->post('f_status')) 	$base_where['status'] 	= $this->input->post('f_status');
            //if ($this->input->post('f_keywords')) 	$base_where['keywords'] = $this->input->post('f_keywords');

            // Create pagination links
            $total_rows = $this->canales_m->count_by($base_where);
            $pagination = create_pagination('admin/canales/index', $total_rows);

            // Using this data, get the relevant results
            $canales = $this->canales_m->limit($pagination['limit'])->get_many_by($base_where);

            //do we need to unset the layout because the request is ajax?
            //$this->input->is_ajax_request() and $this->template->set_layout(FALSE);

            $this->template
                    ->title($this->module_details['name'])
                    //->append_js('admin/filter.js')
                    //->set_partial('filters', 'admin/partials/filters')
                    ->set('pagination', $pagination)
                    ->set('canales', $canales);

            $this->input->is_ajax_request()
                    ? $this->template->build('admin/tables/posts')
                    : $this->template->build('admin/index');
	}
        
        /**
         * Lista de videos del canal seleccionado
         * @param int $canal_id
        */
        public function videos($canal_id)
        {
            // Configuracion de imagenes de videos
            $this->config->load('videos/imagenes');
            
            // Obtiene datos del canal
            $canal = $this->canales_m->get($canal_id);            
  
            // Obtiene la lista de videos según canal seleccionado
            $lista_videos = $this->videos_m->get_by_canal($canal_id);
            if(count($lista_videos)>0){
                foreach($lista_videos as $index=>$objVideo){
                    $objVideo->maestro = $this->_getTipoMaestro($objVideo->id);
                    $lista_videos[$index] = $objVideo;
                }
            }   
            
            $this->template
                    ->title($this->module_details['name'])
                    ->set('lista_videos', $lista_videos)
                    ->set('canal', $canal);
            
            $this->input->is_ajax_request()
                    ? $this->template->build('admin/index')
                    : $this->template->build('admin/videos');
        }
        
        public function _getTipoMaestro($video_id){
            $returnValue = '';
            $objGrupoDetalle = $this->grupo_detalle_m->get_by(array("video_id"=>$video_id));
            if(count($objGrupoDetalle)>0){
                $objMaestro= $this->grupo_maestro_m->get($objGrupoDetalle->grupo_maestro_padre);
                $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro->tipo_grupo_maestro_id);
                $returnValue = $objTipoMaestro->nombre;
            }
            return $returnValue;
        }
        
        public function vd($var){
            echo "<pre>";
            print_r($var);
            echo "</pre>";
        }
                
	/**
	 * Edit an existing settings item
	 *
	 * @return void
	 */
	public function edit()
	{
		if (PYRO_DEMO)
		{
			$this->session->set_flashdata('notice', lang('global:demo_restrictions'));
			redirect('admin/settings');
		}
		
		$settings = $this->settings_m->get_many_by(array('is_gui'=>1));
		$settings_stored = array();

		// Create dynamic validation rules
		foreach ($settings as $setting)
		{
			$this->validation_rules[] = array(
				'field' => $setting->slug . (in_array($setting->type, array('select-multiple', 'checkbox')) ? '[]' : ''),
				'label' => 'lang:settings_' . $setting->slug,
				'rules' => 'trim' . ($setting->is_required ? '|required' : '') . ($setting->type !== 'textarea' ? '|max_length[255]' : '')
			);

			$settings_stored[$setting->slug] = $setting->value;
		}

		// Set the validation rules
		$this->form_validation->set_rules($this->validation_rules);

		// Got valid data?
		if ($this->form_validation->run())
		{
			// Loop through again now we know it worked
			foreach ($settings_stored as $slug => $stored_value)
			{
				$input_value = $this->input->post($slug, FALSE);

				if (is_array($input_value))
				{
					$input_value = implode(',', $input_value);
				}

				// Dont update if its the same value
				if ($input_value !== $stored_value)
				{
					$this->settings->set($slug, $input_value);
				}
			}
			
			// Fire an event. Yay! We know when settings are updated. 
			Events::trigger('settings_updated', $settings_stored);

			// Success...
			$this->session->set_flashdata('success', $this->lang->line('settings_save_success'));
		}
		elseif (validation_errors())
		{
			$this->session->set_flashdata('error', validation_errors());
		}

		// Redirect user back to index page or the module/section settings they are editing
		redirect('admin/settings');
	}
        
        
	/**
	 * Helper method to determine what to do with selected items from form post
	 * 
	 * @return void
	 */
	public function action()
	{                       
            switch ($this->input->post('btnAction'))
            {
                case 'publish':
                                $this->publish();
                                break;

                case 'delete':
                                $this->delete();
                                break;

                default:
                        redirect('canales/videos');
                        break;
            }
	}
        
        /**
	 * Publish video
	 * 
	 * @param int $id the ID of the canal post to make public
	 * @return void
	 */
	public function publish($id = 0) 
        {
            role_or_die('videos', 'put_live');

            // Publish one
            $ids = ($id) ? array($id) : $this->input->post('action_to');

            if (!empty($ids)) {
                
                // Go through the array of slugs to publish
                $post_titles = array();
                
                foreach ($ids as $id) {
                    
                    // Get the current page so we can grab the id too
                    if ($post = $this->videos_m->get($id)) {
                        $this->videos_m->publish($id);

                        // Wipe cache for this model, the content has changed
                        $this->pyrocache->delete('videos_m');
                        $post_titles[] = $post->titulo;
                    }
                }
            }

            // Some canales have been published
            if (!empty($post_titles)) {
                // Only publishing one canal
                if (count($post_titles) == 1) {
                    $this->session->set_flashdata('success', sprintf($this->lang->line('videos:publish_success'), $post_titles[0]));
                }
                // Publishing multiple canales
                else {
                    $this->session->set_flashdata('success', sprintf($this->lang->line('videos:mass_publish_success'), implode('", "', $post_titles)));
                }
            }
            // For some reason, none of them were published
            else {
                $this->session->set_flashdata('notice', $this->lang->line('videos:publish_error'));
            }

            redirect('admin/canales/videos/' . $id);
        }

	/**
	 * Delete blog post
	 * 
	 * @param int $id the ID of the blog post to delete
	 * @return void
	 */
	public function delete($id = 0)
	{
		$this->load->model('comments/comments_m');

		role_or_die('videos', 'delete_live');

		// Delete one
		$ids = ($id) ? array($id) : $this->input->post('action_to');

		// Go through the array of slugs to delete
		if ( ! empty($ids))
		{
			$post_titles = array();
			$deleted_ids = array();
			foreach ($ids as $id)
			{
				// Get the current page so we can grab the id too
				if ($post = $this->videos_m->get($id))
				{
					if ($this->videos_m->delete($id))
					{
						$this->comments_m->where('module', 'blog')->delete_by('module_id', $id);

						// Wipe cache for this model, the content has changed
						$this->pyrocache->delete('blog_m');
						$post_titles[] = $post->title;
						$deleted_ids[] = $id;
					}
				}
			}
			
			// Fire an event. We've deleted one or more blog posts.
			Events::trigger('post_deleted', $deleted_ids);
		}

		// Some pages have been deleted
		if ( ! empty($post_titles))
		{
			// Only deleting one page
			if (count($post_titles) == 1)
			{
				$this->session->set_flashdata('success', sprintf($this->lang->line('blog:delete_success'), $post_titles[0]));
			}
			// Deleting multiple pages
			else
			{
				$this->session->set_flashdata('success', sprintf($this->lang->line('blog:mass_delete_success'), implode('", "', $post_titles)));
			}
		}
		// For some reason, none of them were deleted
		else
		{
			$this->session->set_flashdata('notice', lang('blog:delete_error'));
		}

		redirect('admin/canales');
	}

	/**
	 * Sort settings items
	 *
	 * @return void
	 */
	public function ajax_update_order()
	{
		$slugs = explode(',', $this->input->post('order'));

		$i = 1000;
		foreach ($slugs as $slug)
		{
			$this->settings_m->update($slug, array(
				'order' => $i--,
			));
		}
	}
}

/* End of file admin.php */