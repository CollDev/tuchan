<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin controller for canales module
 *
 * @author 	MiCanal Dev Team 
 */
class Admin extends Admin_Controller {

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
    public function __construct() {
        parent::__construct();
        $this->load->model('canales_m');
        $this->load->model('tipo_canales_m');
        $this->load->model('imagenes_m');
        $this->load->model('tipo_portada_m');
        $this->load->model('tipo_secciones_m');
        $this->load->model('vw_video_m');
        $this->load->model('vw_maestro_video_m');
        $this->load->model('usuario_grupo_canales_m');
        $this->load->model('videos/videos_m');
        $this->load->model('videos/tipo_imagen_m');
        $this->load->model('videos/imagen_m');
        $this->load->model('videos/categoria_m');
        $this->load->model('videos/video_tags_m');
        $this->load->model('videos/tags_m');
        $this->load->model('templates_m');
        /* $this->load->library('settings');
          $this->load->library('form_validation'); */
        $this->lang->load('canales');
        $this->lang->load('videos/videos');
        $this->load->model('videos/grupo_detalle_m');
        $this->load->model('videos/grupo_maestro_m');
        $this->load->model('videos/tipo_maestro_m');
        $this->load->model('portada_m');
        $this->load->model('canal_portada_m');
        $this->load->model('secciones_m');
        $this->load->model('portada_secciones_m');
        $this->load->model('detalle_secciones_m');
        $this->load->model('papelera_m');
        $this->load->library('imagenes_lib');
        $this->load->library('procesos_lib');
        $this->load->library('portadas_lib');
        $this->config->load('videos/uploads');
    }

    /**
     * Index method, lists all canales
     *
     * @return void
     */
    public function index() {
        //echo "here!!---->".($this->session->userdata['group']);die();
        if ($this->session->userdata['group'] == 'administrador-canales' || $this->session->userdata['group'] == 'admin' || $this->session->userdata['group'] == 'administrador-mi-canal') {
            $user_id = (int) $this->session->userdata('user_id');
            if ($this->session->userdata['group'] != 'admin') {
                $canalesxUsuario = $this->usuario_grupo_canales_m->get_many_by(array("user_id" => $user_id, "estado" => $this->config->item('estado:publicado')));
            } else {
                $canalesxUsuario = array();
            }
            //$this->vd($canalesxUsuario);
            $arrayCanales = array();
            if (count($canalesxUsuario) > 0) {
                foreach ($canalesxUsuario as $in => $objgrupocanal) {
                    array_push($arrayCanales, $objgrupocanal->canal_id);
                }
                if (count($arrayCanales) > 0) {
                    $arrayCanales = array_unique($arrayCanales);
                }
            }
            //$base_where = array();
            if ($this->input->post('f_estado') > 0) {
                if ($this->input->post('f_estado') == '3') {
                    $estado_cambiado = $this->config->item('estado:borrador');
                } else {
                    $estado_cambiado = $this->input->post('f_estado');
                }
                $base_where = array("estado" => $estado_cambiado);
            } else {
                $base_where = array();
            }
            $keyword = '';
            if ($this->input->post('f_keywords'))
                $keyword = $this->input->post('f_keywords');
            // Create pagination links
            if (strlen(trim($keyword)) > 0) {
                if (count($canalesxUsuario) > 0) {
                    $total_rows = $this->canales_m->where_not_in('estado', array($this->config->item('estado:eliminado')))->where_in('id', $arrayCanales)->like('nombre', $keyword)->count_by($base_where);
                } else {
                    $total_rows = $this->canales_m->where_not_in('estado', array($this->config->item('estado:eliminado')))->like('nombre', $keyword)->count_by($base_where);
                }
            } else {
                if (count($canalesxUsuario) > 0) {
                    $total_rows = $this->canales_m->where_not_in('estado', array($this->config->item('estado:eliminado')))->where_in('id', $arrayCanales)->count_by($base_where);
                } else {
                    $total_rows = $this->canales_m->where_not_in('estado', array($this->config->item('estado:eliminado')))->count_by($base_where);
                }
            }
            $pagination = create_pagination('admin/canales/index', $total_rows, 10);

            // Using this data, get the relevant results
            if (strlen(trim($keyword)) > 0) {
                if (count($canalesxUsuario) > 0) {
                    $canales = $this->canales_m->where_not_in('estado', array($this->config->item('estado:eliminado')))->where_in('id', $arrayCanales)->order_by('fecha_registro', 'DESC')->like('nombre', $keyword)->limit($pagination['limit'])->get_many_by($base_where);
                } else {
                    $canales = $this->canales_m->where_not_in('estado', array($this->config->item('estado:eliminado')))->order_by('fecha_registro', 'DESC')->like('nombre', $keyword)->limit($pagination['limit'])->get_many_by($base_where);
                }
            } else {
                if (count($canalesxUsuario) > 0) {
                    $canales = $this->canales_m->where_not_in('estado', array($this->config->item('estado:eliminado')))->where_in('id', $arrayCanales)->order_by('fecha_registro', 'DESC')->limit($pagination['limit'])->get_many_by($base_where);
                } else {
                    $canales = $this->canales_m->where_not_in('estado', array($this->config->item('estado:eliminado')))->order_by('fecha_registro', 'DESC')->limit($pagination['limit'])->get_many_by($base_where);
                }
            }

            //agregamos las imagenes de tipo isotipo para cada canal
            if (count($canales) > 0) {
                foreach ($canales as $puntero => $objCanal) {
                    $objIsotipo = $this->imagen_m->get_by(array("canales_id" => $objCanal->id, "estado" => $this->config->item('estado:publicado'), "tipo_imagen_id" => $this->config->item('imagen:iso')));
                    if (count($objIsotipo) > 0) {
                        $objCanal->imagen_iso = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objIsotipo->imagen;
                    } else {
                        $objCanal->imagen_iso = $this->config->item('url:iso');
                    }
                    $canales[$puntero] = $objCanal;
                }
            }

            $estados = array($this->config->item('estado:publicado') => "Publicado", "3" => "Borrador", $this->config->item('estado:eliminado') => "Eliminado");

            //do we need to unset the layout because the request is ajax?
            $this->input->is_ajax_request() and $this->template->set_layout(FALSE);

            $this->template
                    ->title($this->module_details['name'])
                    ->append_js('admin/filter.js')
                    ->append_js('module::jquery.ddslick.min.js')
                    ->set_partial('filters', 'admin/partials/filters')
                    ->set_partial('canales', 'admin/tables/canales')
                    ->append_js('module::jquery.alerts.js')
                    ->append_css('module::jquery.alerts.css')
                    ->set('pagination', $pagination)
                    ->set('estados', $estados)
                    ->set('canales', $canales);
            $this->input->is_ajax_request() ? $this->template->build('admin/tables/canales') : $this->template->build('admin/index');
        } else {

            redirect('admin#');
        }
    }

    /**
     * Lista de videos del canal seleccionado
     * @param int $canal_id
     */
    public function videos($canal_id = 0) {
        if ($canal_id == 0) {
            $objUsuarioCanal = $this->usuario_grupo_canales_m->get_by(array("user_id" => $this->current_user->id, "estado" => $this->config->item('estado:publicado')));
            $canal_id = $objUsuarioCanal->canal_id;
        }
        $objCanal = $this->canales_m->get($canal_id);
        if (count($objCanal) > 0) {
            if ($this->input->post('f_estado') > 0) {
                if ($this->input->post('f_estado') == '4') {
                    $estado_cambiado = $this->config->item('video:codificando');
                } else {
                    $estado_cambiado = $this->input->post('f_estado');
                }
                $base_where = array("v" => "v", "canales_id" => $canal_id, "estado" => $estado_cambiado);
                $estados_video_listar = array();
            } else {
                $base_where = array("v" => "v", "canales_id" => $canal_id);
                //estados de los videos a listar
                $estados_video_listar = array($this->config->item('video:codificando'), $this->config->item('video:borrador'), $this->config->item('video:publicado'));
            }
            //$programme_id = 0;
            $keyword = '';
            if ($this->input->post('f_keywords'))
                $keyword = $this->input->post('f_keywords');

            if ($this->input->post('f_programa'))
            //$base_where['tercer_padre'] = $this->input->post('f_programa');
                $base_where['gm3'] = $this->input->post('f_programa');

            // Create pagination links
            if (strlen(trim($keyword)) > 0) {
                //$total_rows = $this->vw_video_m->like('titulo', $keyword)->count_by($base_where);
                $total_rows = $this->vw_maestro_video_m->like('nombre', $keyword)->count_by($base_where);
            } else {
                if (count($estados_video_listar) > 0) {
                    $total_rows = $this->vw_maestro_video_m->where_in('estado', $estados_video_listar)->count_by($base_where);
                } else {
                    $total_rows = $this->vw_maestro_video_m->count_by($base_where);
                }
            }
            $pagination = create_pagination('admin/canales/videos/' . $canal_id . '/index/', $total_rows, 10, 6);
            if (strlen(trim($keyword)) > 0) {
                // Using this data, get the relevant results
                $listVideo = $this->vw_maestro_video_m->like('nombre', $keyword)->order_by('fecha_registro', 'DESC')->limit($pagination['limit'])->get_many_by($base_where);
            } else {
                if (count($estados_video_listar) > 0) {
                    $listVideo = $this->vw_maestro_video_m->where_in('estado', $estados_video_listar)->order_by('fecha_registro', 'DESC')->limit($pagination['limit'])->get_many_by($base_where);
                } else {
                    $listVideo = $this->vw_maestro_video_m->order_by('fecha_registro', 'DESC')->limit($pagination['limit'])->get_many_by($base_where);
                }
            }
            //corregimos  el listado para maestros y programas
            // Obtiene datos del canal
            $canal = $this->canales_m->get($canal_id);
            $logo_canal = $this->imagenes_m->getLogo(array('canales_id' => $canal_id,
                'tipo_imagen_id' => TIPO_IMAGEN_ISO, 'estado' => ESTADO_ACTIVO));
            $programas = $this->grupo_maestro_m->getCollectionDropDown(array("tipo_grupo_maestro_id" => $this->config->item('videos:programa'), "canales_id" => $canal_id), 'nombre');
            $estados = array("4" => "Codificando", $this->config->item('video:borrador') => "Borrador", $this->config->item('video:publicado') => "Publicado", $this->config->item('video:eliminado') => "Eliminado");
            // Obtiene la lista de videos según canal seleccionado
            //do we need to unset the layout because the request is ajax?
            $this->input->is_ajax_request() and $this->template->set_layout(FALSE);

            $this->template
                    ->title($this->module_details['name'])
                    ->set('lista_videos', $listVideo)
                    ->append_js('admin/filter.js')
                    ->set_partial('filters', 'admin/partials/filters')
                    ->set_partial('users', 'admin/tables/users')
                    ->set('pagination', $pagination)
                    ->set('canal', $canal)
                    ->set('estados', $estados)
                    ->set('logo_canal', $logo_canal)
                    ->append_js('module::jquery.alerts.js')
                    ->append_css('module::jquery.alerts.css')
                    ->set('programa', $programas);
            $this->input->is_ajax_request() ? $this->template->build('admin/tables/users') : $this->template->build('admin/videos');
        } else {
            $this->template
                    ->title($this->module_details['name']);
            $this->template->build('admin/error');
        }
    }

    private function obtenerImagenVideo($video_id) {
        $objImagen = $this->imagen_m->get_by(array("estado" => $this->config->item('estado:publicado'), "videos_id" => $video_id));
        if (count($objImagen) == 0) {
            $objImagen = new stdClass();
            $objImagen->procedencia = 2;
            $objImagen->imagen = '';
        }
        return $objImagen;
    }

    function liquid_player($video_id, $width = 0, $height = 0) {
        if ($this->input->is_ajax_request()) {
            $objVideo = $this->videos_m->get($video_id);
            $objCanal = $this->canales_m->get($objVideo->canales_id);
            $keyplayer = $objCanal->playerkey;
            $codigo_video = $objVideo->codigo;
            $add = "ad_program=[http://ox-d.sambaads.com/v/1.0/av?auid=129933&amp;tid=3]&";
            $add = "";
            //APIKEYPLAYER;
            $autostart = config_item('liquid_autostart');
            $script = '<script src="http://player.sambatech.com.br/current/samba-player.js?playerWidth=' . $width . '&#038;playerHeight=' . $height . '&#038;ph=' . $keyplayer . '&#038;m=' . $codigo_video . '&#038;' . $add . $autostart . 'amp&#038;skinColor=0x72be44&#038;profileName=sambaPlayer-embed.xml&#038;cb=playerFn"></script>';
            //$script = 'http://player.sambatech.com.br/current/samba-player.js?playerWidth=' . $width . '&#038;playerHeight=' . $height . '&#038;ph=' . $keyplayer . '&#038;m=' . $codigo_video . '&#038;' . $add . $autostart . 'amp&#038;skinColor=0x72be44&#038;profileName=sambaPlayer-embed.xml&#038;cb=playerFn';
            $html = $objVideo->ruta;
            echo $html;
        }
    }

    public function _getTagsByIdVideo($video_id, $type_tag) {
        $returnValue = array();
        $arrayVideoTags = $this->video_tags_m->getVideoTags(array("videos_id" => $video_id));
        if (count($arrayVideoTags) > 0) {
            $arrayIdTags = array();
            foreach ($arrayVideoTags as $index => $objTagVideo) {
                array_push($arrayIdTags, $objTagVideo->tags_id);
            }
            if (count($arrayIdTags) > 0) {
                $returnValue = $this->tags_m->getTagsByIdTagsByType($arrayIdTags, $type_tag);
            }
        }
        if (count($returnValue) > 0) {
            $arrayTag = array();
            foreach ($returnValue as $in => $objTag) {
                array_push($arrayTag, $objTag->nombre);
            }
            $returnValue = implode(',', $arrayTag);
        } else {
            $returnValue = '';
        }
        return $returnValue;
    }

    public function _getNameFuente($canales_id) {
        $objCanal = $this->canales_m->get($canales_id);
        return $objCanal->nombre;
    }

    public function _getNameCategory($categorias_id) {
        $objCategory = $this->categoria_m->get($categorias_id);
        return $objCategory->nombre;
    }

    public function _getUrlImage($objVideo) {
        //$returnValue = BASE_URL . UPLOAD_IMAGENES_VIDEOS . 'no_video.jpg';
        $returnValue = $this->config->item('url:default_imagen') . 'no_video.jpg';
        $objCollectionImagen = $this->imagen_m->get_many_by(array("videos_id" => $objVideo->id, "estado" => "1"));
        if (count($objCollectionImagen) > 0) {
            foreach ($objCollectionImagen as $index => $objImage) {
                if ($objImage->tipo_imagen_id == '1') {
                    if ($objImage->procedencia == '0') {
                        $returnValue = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . BASE_URI . $objImage->imagen;
                    } else {
                        $returnValue = $objImage->imagen;
                    }
                }
            }
        }
        return $returnValue;
    }

    public function _getIdPrograma($video_id) {
        $returnValue = '-1';
        $objGrupoDetalle = $this->grupo_detalle_m->get_by(array("video_id" => $video_id));
        if (count($objGrupoDetalle) > 0) {
            $objPadreDetalleMaestro = $this->getParentTop($objGrupoDetalle->grupo_maestro_padre);
            if ($objPadreDetalleMaestro != NULL) {
                $returnValue = $objPadreDetalleMaestro->id;
            }
            /* $this->vd($objGrupoDetalle);
              $objCollection = $this->grupo_detalle_m->get_by(array("grupo_maestro_id"=>$objGrupoDetalle->grupo_maestro_padre));
              if(count($objCollection)>0){
              $objProgramaDetalle = $this->grupo_detalle_m->get_by(array("grupo_maestro_id"=>$objCollection->grupo_maestro_padre));
              $objPrograma = $this->grupo_maestro_m->get($objProgramaDetalle->grupo_maestro_padre);
              $returnValue = $objPrograma->nombre;
              } */
        }
        return $returnValue;
    }

    public function _getNamePrograma($video_id) {
        $returnValue = '';
        $objGrupoDetalle = $this->grupo_detalle_m->get_by(array("video_id" => $video_id));
        if (count($objGrupoDetalle) > 0) {
            $objPadreDetalleMaestro = $this->getParentTop($objGrupoDetalle->grupo_maestro_padre);
            if ($objPadreDetalleMaestro != NULL) {
                $returnValue = $objPadreDetalleMaestro->nombre;
            }
            /* $this->vd($objGrupoDetalle);
              $objCollection = $this->grupo_detalle_m->get_by(array("grupo_maestro_id"=>$objGrupoDetalle->grupo_maestro_padre));
              if(count($objCollection)>0){
              $objProgramaDetalle = $this->grupo_detalle_m->get_by(array("grupo_maestro_id"=>$objCollection->grupo_maestro_padre));
              $objPrograma = $this->grupo_maestro_m->get($objProgramaDetalle->grupo_maestro_padre);
              $returnValue = $objPrograma->nombre;
              } */
        }
        return $returnValue;
    }

    public function getParentTop($grupo_maestro_padre) {
        $objMaestro = $this->grupo_maestro_m->get($grupo_maestro_padre);
        if (count($objMaestro) > 0) {
            if ($objMaestro->tipo_grupo_maestro_id == $this->config->item('videos:programa')) {
                return $objMaestro;
            } else {
                $objMaestroDetalle = $this->grupo_detalle_m->get_by(array("grupo_maestro_id" => $objMaestro->id));
                return $this->getParentTop($objMaestroDetalle->grupo_maestro_padre);
            }
        } else {
            return NULL;
        }
    }

    public function _getTipoMaestro($video_id) {
        $returnValue = '';
        $objGrupoDetalle = $this->grupo_detalle_m->get_by(array("video_id" => $video_id));
        if (count($objGrupoDetalle) > 0) {
            $objMaestro = $this->grupo_maestro_m->get($objGrupoDetalle->grupo_maestro_padre);
            $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro->tipo_grupo_maestro_id);
            $returnValue = $objTipoMaestro->nombre;
        }
        return $returnValue;
    }

    public function vd($var) {
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }

    /**
     * Edit an existing settings item
     *
     * @return void
     */
    public function edit() {
        if (PYRO_DEMO) {
            $this->session->set_flashdata('notice', lang('global:demo_restrictions'));
            redirect('admin/settings');
        }

        $settings = $this->settings_m->get_many_by(array('is_gui' => 1));
        $settings_stored = array();

        // Create dynamic validation rules
        foreach ($settings as $setting) {
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
        if ($this->form_validation->run()) {
            // Loop through again now we know it worked
            foreach ($settings_stored as $slug => $stored_value) {
                $input_value = $this->input->post($slug, FALSE);

                if (is_array($input_value)) {
                    $input_value = implode(',', $input_value);
                }

                // Dont update if its the same value
                if ($input_value !== $stored_value) {
                    $this->settings->set($slug, $input_value);
                }
            }

            // Fire an event. Yay! We know when settings are updated. 
            Events::trigger('settings_updated', $settings_stored);

            // Success...
            $this->session->set_flashdata('success', $this->lang->line('settings_save_success'));
        } elseif (validation_errors()) {
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
    public function action() {
        switch ($this->input->post('btnAction')) {
            case 'publish':
                $this->publish();
                break;

            case 'delete':
                $this->delete();
                break;

            default:
                redirect('admin/canales');
                break;
        }
    }

    /**
     * Publish canal
     * 
     * @param int $id the ID of the canal post to make public
     * @return void
     */
    public function publish($id = 0) {
        role_or_die('canales', 'put_live');

        // Publish one
        $ids = ($id) ? array($id) : $this->input->post('action_to');

        if (!empty($ids)) {
            // Go through the array of slugs to publish
            $post_titles = array();
            foreach ($ids as $id) {
                // Get the current page so we can grab the id too
                if ($post = $this->canales_m->get($id)) {
                    $this->canales_m->publish($id);

                    // Wipe cache for this model, the content has changed
                    $this->pyrocache->delete('canales_m');
                    $post_titles[] = $post->nombre;
                }
            }
        }

        // Some canales have been published
        if (!empty($post_titles)) {
            // Only publishing one canal
            if (count($post_titles) == 1) {
                $this->session->set_flashdata('success', sprintf($this->lang->line('canales:publish_success'), $post_titles[0]));
            }
            // Publishing multiple canales
            else {
                $this->session->set_flashdata('success', sprintf($this->lang->line('canales:mass_publish_success'), implode('", "', $post_titles)));
            }
        }
        // For some reason, none of them were published
        else {
            $this->session->set_flashdata('notice', $this->lang->line('canales:publish_error'));
        }

        redirect('admin/canales');
    }

    /**
     * Delete blog post
     * 
     * @param int $id the ID of the blog post to delete
     * @return void
     */
    public function delete($id = 0) {
        $this->load->model('comments/comments_m');

        role_or_die('blog', 'delete_live');

        // Delete one
        $ids = ($id) ? array($id) : $this->input->post('action_to');

        // Go through the array of slugs to delete
        if (!empty($ids)) {
            $post_titles = array();
            $deleted_ids = array();
            foreach ($ids as $id) {
                // Get the current page so we can grab the id too
                if ($post = $this->blog_m->get($id)) {
                    if ($this->blog_m->delete($id)) {
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
        if (!empty($post_titles)) {
            // Only deleting one page
            if (count($post_titles) == 1) {
                $this->session->set_flashdata('success', sprintf($this->lang->line('blog:delete_success'), $post_titles[0]));
            }
            // Deleting multiple pages
            else {
                $this->session->set_flashdata('success', sprintf($this->lang->line('blog:mass_delete_success'), implode('", "', $post_titles)));
            }
        }
        // For some reason, none of them were deleted
        else {
            $this->session->set_flashdata('notice', lang('blog:delete_error'));
        }

        redirect('admin/canales');
    }

    /**
     * Sort settings items
     *
     * @return void
     */
    public function ajax_update_order() {
        $slugs = explode(',', $this->input->post('order'));

        $i = 1000;
        foreach ($slugs as $slug) {
            $this->settings_m->update($slug, array(
                'order' => $i--,
            ));
        }
    }

    /**
     * Metodo para crear un formulario de canal y poder crear y/o editarlo
     * @param string $canal
     */
    public function canal($canal = 0) {
        $user_id = (int) $this->session->userdata('user_id');
        if ($canal > 0) {
            $objCanal = $this->canales_m->get($canal);
            if (count($objCanal) > 0) {
                $objBeanCanal = new stdClass();
                $objBeanCanal->id = $objCanal->id;
                $objBeanCanal->tipo_canales_id = $objCanal->tipo_canales_id;
                $objBeanCanal->alias = $objCanal->alias;
                $objBeanCanal->nombre = $objCanal->nombre;
                $objBeanCanal->descripcion = $objCanal->descripcion;
                $objBeanCanal->apikey = $objCanal->apikey;
                $objBeanCanal->playerkey = $objCanal->playerkey;
                $objBeanCanal->id_mongo = $objCanal->id_mongo;
                $objBeanCanal->cantidad_suscriptores = $objCanal->cantidad_suscriptores;
                $objBeanCanal->estado = $objCanal->estado;
                $objBeanCanal->fecha_registro = $objCanal->fecha_registro;
                $objBeanCanal->usuario_registro = $objCanal->usuario_registro;
                $objBeanCanal->fecha_actualizacion = $objCanal->fecha_actualizacion;
                $objBeanCanal->usuario_actualizacion = $objCanal->usuario_actualizacion;
                $objBeanCanal->estado_migracion = $objCanal->estado_migracion;
                $objBeanCanal->fecha_migracion = $objCanal->fecha_migracion;
                $objBeanCanal->fecha_migracion_actualizacion = $objCanal->fecha_migracion_actualizacion;
                $objBeanCanal->imagen_portada = $this->_getImagen($canal, $this->config->item('imagen:extralarge'));
                $objBeanCanal->imagen_logotipo = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $this->_getImagen($canal, $this->config->item('imagen:logo'));
                $objBeanCanal->imagen_isotipo = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $this->_getImagen($canal, $this->config->item('imagen:iso'));
                $listaTipoCanal = $this->tipo_canales_m->getTipoCanalesDropDown(array("estado" => $this->config->item('estado:publicado')), 'nombre');
                $this->template
                        ->title($this->module_details['name'])
                        ->append_js('AjaxUpload.2.0.min.js')
                        ->append_metadata($this->load->view('fragments/wysiwyg', array(), TRUE))
                        ->append_js('module::jquery.ddslick.min.js')
                        ->set('objCanal', $objBeanCanal)
                        ->append_js('module::jquery.alerts.js')
                        ->append_css('module::jquery.alerts.css')
                        ->set('tipo_canales', $listaTipoCanal)
                        ->set('nombre_canal', 'nombre ...');
                $this->template->build('admin/canal');
            } else {
                $listaTipoCanal = $this->tipo_canales_m->getTipoCanalesDropDown(array("estado" => $this->config->item('estado:publicado')), 'nombre');
                $this->template
                        ->title($this->module_details['name']);
                $this->template->build('admin/error');
            }
        } else {
            $objBeanCanal = new stdClass();
            $objBeanCanal->id = 0;
            $objBeanCanal->tipo_canales_id = 0;
            $objBeanCanal->alias = 0;
            $objBeanCanal->nombre = '';
            $objBeanCanal->descripcion = '';
            $objBeanCanal->apikey = '';
            $objBeanCanal->playerkey = '';
            $objBeanCanal->id_mongo = 0;
            $objBeanCanal->cantidad_suscriptores = 0;
            $objBeanCanal->estado = 0;
            $objBeanCanal->fecha_registro = date("Y-m-d H:i:s");
            $objBeanCanal->usuario_registro = $user_id;
            $objBeanCanal->fecha_actualizacion = date("Y-m-d H:i:s");
            $objBeanCanal->usuario_actualizacion = $user_id;
            $objBeanCanal->estado_migracion = 0;
            $objBeanCanal->fecha_migracion = '0000-00-00 00:00:00';
            $objBeanCanal->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
            $objBeanCanal->imagen_portada = '';
            $objBeanCanal->imagen_logotipo = '';
            $objBeanCanal->imagen_isotipo = '';
            $listaTipoCanal = $this->tipo_canales_m->getTipoCanalesDropDown(array("estado" => $this->config->item('estado:publicado')), 'nombre');
            $this->template
                    ->title($this->module_details['name'])
                    ->append_js('AjaxUpload.2.0.min.js')
                    ->append_metadata($this->load->view('fragments/wysiwyg', array(), TRUE))
                    ->append_js('module::jquery.ddslick.min.js')
                    ->set('objCanal', $objBeanCanal)
                    ->append_js('module::jquery.alerts.js')
                    ->append_css('module::jquery.alerts.css')
                    ->set('tipo_canales', $listaTipoCanal)
                    ->set('nombre_canal', 'nombre ...');
            $this->template->build('admin/canal');
        }
    }

    public function _getImagen($canal_id, $tipo) {
        if ($tipo == $this->config->item('imagen:extralarge')) {
            $returnArray = array();
            $arrayImagen = $this->imagen_m->get_many_by(array("tipo_imagen_id" => $this->config->item('imagen:extralarge'), "canales_id" => $canal_id));
            //formato para json
            if (count($arrayImagen) > 0) {
                $arreglo = array();
                foreach ($arrayImagen as $in => $objImg) {
                    $arrayImg['text'] = '';
                    $arrayImg['value'] = $objImg->id;
                    if ($objImg->estado == "0") {
                        $arrayImg['selected'] = false;
                    } else {
                        if ($objImg->estado == "1") {
                            $arrayImg['selected'] = true;
                        }
                    }
                    $arrayImg['description'] = '';
                    $arrayImg['imageSrc'] = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImg->imagen;

                    array_push($arreglo, $arrayImg);
                    unset($arrayImg);
                }
                $returnArray = $arreglo;
            }
            return $returnArray;
        } else {
            $listImg = $this->imagen_m->get_many_by(array("canales_id" => $canal_id, "tipo_imagen_id" => $tipo, "estado" => "1"));
            $returnValue = '';
            if (count($listImg) > 0) {
                foreach ($listImg as $indice => $objImage) {
                    $returnValue = $objImage->imagen;
                }
            }
            return $returnValue;
        }
    }

    /**
     * metodo para registrar un canal x ajax
     * @param int $canal_id
     */
    public function registrar_canal($canal_id = 0) {
        $user_id = (int) $this->session->userdata('user_id');
        if ($canal_id > 0) {
            //$imagen_logotipo_temp = './uploads/temp/' . $this->input->post('imagen_logotipo');
            //$imagen_isotipo_temp = './uploads/temp/' . $this->input->post('imagen_isotipo');
            $imagen_logotipo_temp = $this->config->item('path:temp') . $this->input->post('imagen_logotipo');
            $imagen_isotipo_temp = $this->config->item('path:temp') . $this->input->post('imagen_isotipo');
            //if ($this->input->post('update_logotipo') > 0 && file_exists($imagen_logotipo_temp)) {
            //if ($this->input->post('update_isotipo') > 0 && file_exists($imagen_isotipo_temp)) {
            if (!$this->_existeCanal($this->input->post('nombre'), $canal_id)) {
                $objBeanCanal = new stdClass();
                $objBeanCanal->id = $canal_id;
                $objBeanCanal->tipo_canales_id = $this->input->post('tipo_canal');
                $objBeanCanal->alias = url_title(strtolower(convert_accented_characters($this->input->post('nombre'))));
                $objBeanCanal->nombre = $this->input->post('nombre');
                $objBeanCanal->descripcion = $this->input->post('descripcion_updated');
                $objBeanCanal->apikey = $this->input->post('apikey');
                $objBeanCanal->playerkey = $this->input->post('playerkey');
                $objBeanCanal->fecha_actualizacion = date("Y-m-d H:i:s");
                $objBeanCanal->usuario_actualizacion = $user_id;
                $objBeanCanal->estado_migracion = $this->config->item('migracion:actualizado');
                $objBeanCanal->estado_migracion_sphinx = $this->config->item('sphinx:actualizar');


                $this->canales_m->actualizar($objBeanCanal);

                //guardamos las imagenes
                if ($this->input->post('update_logotipo') > 0 && $this->input->post('update_isotipo') > 0) {
                    $array_images = array($this->config->item('imagen:logo') => $this->input->post('imagen_logotipo'), $this->config->item('imagen:iso') => $this->input->post('imagen_isotipo'));
                    $listLogo = $this->imagen_m->get_many_by(array("canales_id" => $objBeanCanal->id, "tipo_imagen_id" => $this->config->item('imagen:logo')));
                    if (count($listLogo) > 0) {
                        foreach ($listLogo as $j => $objImg) {
                            $this->imagen_m->update($objImg->id, array("estado" => $this->config->item('estado:borrador')));
                        }
                    }
                    $listIso = $this->imagen_m->get_many_by(array("canales_id" => $objBeanCanal->id, "tipo_imagen_id" => $this->config->item('imagen:iso')));
                    if (count($listIso) > 0) {
                        foreach ($listIso as $j => $objIso) {
                            $this->imagen_m->update($objIso->id, array("estado" => $this->config->item('estado:borrador')));
                        }
                    }
                    $arrayImagenSaved = $this->saveImages($array_images, $objBeanCanal->id);
                    $this->_enviarImagenesElemento($arrayImagenSaved);
                } else {
                    if ($this->input->post('update_logotipo') == 0 && $this->input->post('update_isotipo') > 0) {
                        $array_images = array($this->config->item('imagen:iso') => $this->input->post('imagen_isotipo'));
                        $listIso = $this->imagen_m->get_many_by(array("canales_id" => $objBeanCanal->id, "tipo_imagen_id" => $this->config->item('imagen:iso')));
                        if (count($listIso) > 0) {
                            foreach ($listIso as $j => $objIso) {
                                $this->imagen_m->update($objIso->id, array("estado" => $this->config->item('estado:borrador')));
                            }
                        }
                        $arrayImagenSaved = $this->saveImages($array_images, $objBeanCanal->id);
                        $this->_enviarImagenesElemento($arrayImagenSaved);
                    } else {
                        if ($this->input->post('update_logotipo') > 0 && $this->input->post('update_isotipo') == 0) {
                            $array_images = array($this->config->item('imagen:logo') => $this->input->post('imagen_logotipo'));
                            $listLogo = $this->imagen_m->get_many_by(array("canales_id" => $objBeanCanal->id, "tipo_imagen_id" => $this->config->item('imagen:logo')));
                            if (count($listLogo) > 0) {
                                foreach ($listLogo as $j => $objImg) {
                                    $this->imagen_m->update($objImg->id, array("estado" => $this->config->item('estado:borrador')));
                                }
                            }
                            $arrayImagenSaved = $this->saveImages($array_images, $objBeanCanal->id);
                            $this->_enviarImagenesElemento($arrayImagenSaved);
                        }
                    }
                }
                //actualizamos la imagen de portada en el detalle de secciones
                //$this->actualizarPortadaCanal($canal_id);
                $this->procesos_lib->curlGenerarCanalesXId($objBeanCanal->id);
                echo json_encode(array("value" => "0"));
            } else {
                echo json_encode(array("value" => "4")); //ya existe un canal registrado
            }
            /* } else {
              echo json_encode(array("value" => "3")); //no existe el isotipo  en el servidor
              }
              } else {
              echo json_encode(array("value" => "2")); //no existe el logotipo  en el servidor
              } */
        } else {
            $imagen_portada_temp = $this->config->item('path:temp') . $this->input->post('imagen_portada');
            $imagen_logotipo_temp = $this->config->item('path:temp') . $this->input->post('imagen_logotipo');
            $imagen_isotipo_temp = $this->config->item('path:temp') . $this->input->post('imagen_isotipo');
            if (file_exists($imagen_portada_temp)) {
                if (file_exists($imagen_logotipo_temp)) {
                    if (file_exists($imagen_isotipo_temp)) {
                        if (!$this->_existeCanal($this->input->post('nombre'))) {
                            //registramos un nuevo canal
                            $objBeanCanal = new stdClass();
                            $objBeanCanal->id = NULL;
                            $objBeanCanal->tipo_canales_id = $this->input->post('tipo_canal');
                            $objBeanCanal->alias = url_title(strtolower(convert_accented_characters($this->input->post('nombre'))));
                            $objBeanCanal->nombre = $this->input->post('nombre');
                            $objBeanCanal->descripcion = $this->input->post('descripcion_updated');
                            $objBeanCanal->apikey = $this->input->post('apikey');
                            $objBeanCanal->playerkey = $this->input->post('playerkey');
                            $objBeanCanal->id_mongo = NULL;
                            $objBeanCanal->cantidad_suscriptores = '0';
                            $objBeanCanal->estado = $this->config->item('estado:borrador');
                            $objBeanCanal->fecha_registro = date("Y-m-d H:i:s");
                            $objBeanCanal->usuario_registro = $user_id;
                            $objBeanCanal->fecha_actualizacion = date("Y-m-d H:i:s");
                            $objBeanCanal->usuario_actualizacion = $user_id;
                            $objBeanCanal->estado_migracion = '0';
                            $objBeanCanal->fecha_migracion = '0000-00-00 00:00:00';
                            $objBeanCanal->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                            $objBeanCanal->estado_migracion_sphinx = $this->config->item('sphinx:nuevo');
                            $objBeanCanalSaved = $this->canales_m->save($objBeanCanal);
                            $this->procesos_lib->curlGenerarCanalesXId($objBeanCanal->id);

                            //guardamos las imagenes
                            $array_images = array($this->config->item('imagen:extralarge') => $this->input->post('imagen_portada'), $this->config->item('imagen:logo') => $this->input->post('imagen_logotipo'), $this->config->item('imagen:iso') => $this->input->post('imagen_isotipo'));
                            $arrayImagenSaved = $this->saveImages($array_images, $objBeanCanalSaved->id);
                            //ejecutamos el proceso de generación de portada
                            //$this->generarPortadaCanal($objBeanCanalSaved, NULL);
                            $this->generarNuevaPortada($objBeanCanalSaved, NULL, $this->config->item('portada:canal'));
                            //enviamos las imagenes al servidor elemento
                            $this->_enviarImagenesElemento($arrayImagenSaved);
                            //registramos en la tabla de permisos para grupos
                            $this->registrarPermisoGrupo($objBeanCanalSaved->id);

                            echo json_encode(array("value" => "0"));
                        } else {
                            echo json_encode(array("value" => "4")); //ya existe un canal registrado
                        }
                    } else {
                        echo json_encode(array("value" => "3")); //no existe el isotipo  en el servidor
                    }
                } else {
                    echo json_encode(array("value" => "2")); //no existe el logotipo  en el servidor
                }
            } else {
                echo json_encode(array("value" => "1")); //no existe la imagen portada en el servidor
            }
        }
    }

    /**
     * metodo para registrar los permisos de acceso, cuando se crea un nuevo canal
     * @param int $canal_id
     */
    public function registrarPermisoGrupo($canal_id) {
        $user_id = (int) $this->session->userdata('user_id');
        $objBeanUsuarioGrupoCanal = new stdClass();
        $objBeanUsuarioGrupoCanal->canal_id = $canal_id;
        $objBeanUsuarioGrupoCanal->user_id = $user_id;
        $objBeanUsuarioGrupoCanal->group_id = $this->config->item('grupo:administrador-canales');
        $objBeanUsuarioGrupoCanal->estado = $this->config->item('estado:publicado');
        $objBeanUsuarioGrupoCanal->fecha_registro = date("Y-m-d H:i:s");
        $objBeanUsuarioGrupoCanal->usuario_registro = $user_id;
        $objBeanUsuarioGrupoCanal->fecha_actualizacion = date("Y-m-d H:i:s");
        $objBeanUsuarioGrupoCanal->usuario_actualizacion = $user_id;
        $this->usuario_grupo_canales_m->save($objBeanUsuarioGrupoCanal);
    }

    /**
     * metodo para actualizar la imagen de los destacados si es que hubiese cambios
     * @param int $canal_id
     * @return boolean 
     */
    public function actualizarPortadaCanal($canal_id) {
        $returnValue = false;
        $arrayPortada = $this->portada_m->get_many_by(array("tipo_portadas_id" => $this->config->item('portada:canal'), "origen_id" => $canal_id));
        if (count($arrayPortada) > 0) {
            $portada_id = 0;
            foreach ($arrayPortada as $puntero => $objPortada) {
                $portada_id = $objPortada->id;
            }
            if ($portada_id > 0) {
                $arraySecciones = $this->secciones_m->get_many_by(array("portadas_id" => $portada_id, "tipo_secciones_id" => $this->config->item('seccion:destacado')));
                if (count($arraySecciones) > 0) {
                    $seccion_id = 0;
                    foreach ($arraySecciones as $index => $objSeccion) {
                        $seccion_id = $objSeccion->id;
                    }
                    if ($seccion_id > 0) {
                        $arrayDetalleSecciones = $this->detalle_secciones_m->get_many_by(array("secciones_id" => $seccion_id));
                        if (count($arrayDetalleSecciones) > 0) {
                            $imagen_id = 0;
                            foreach ($arrayDetalleSecciones as $indice => $objDetalle) {
                                if ($objDetalle->imagenes_id != NULL) {
                                    $imagen_id = $objDetalle->imagenes_id;
                                    $detalle_seccion_id = $objDetalle->id;
                                }
                            }
                            if ($imagen_id > 0) {
                                //$obtenemos el Objeto de la imagen de portada activa
                                $objImagenPortada = $this->imagen_m->get_by(array("tipo_imagen_id" => $this->config->item('imagen:extralarge'), "estado" => "1", "canales_id" => $canal_id));
                                if (count($objImagenPortada) > 0) {
                                    $this->detalle_secciones_m->update($detalle_seccion_id, array("imagenes_id" => $objImagenPortada->id));
                                }
                            }
                        }
                    }
                }
            }
        }
        return $returnValue;
    }

    public function _enviarImagenesElemento($arrayImagenSaved) {
        if (is_array($arrayImagenSaved)) {
            if (count($arrayImagenSaved) > 0) {
                foreach ($arrayImagenSaved as $index => $objImagen) {
                    $path_image_element = $this->elemento_upload($objImagen->id, $objImagen->imagen);
                    unlink($objImagen->imagen);
                    $array_path = explode("/", $path_image_element);
                    if ($array_path[0] == $this->config->item('server:elemento')) {
                        unset($array_path[0]);
                    }
                    $path_single_element = implode('/', $array_path);
                    if ($objImagen->tipo_imagen_id == $this->config->item('imagen:iso') || $objImagen->tipo_imagen_id == $this->config->item('imagen:logo')) {
                        $this->imagen_m->update($objImagen->id, array("imagen" => $path_single_element, "procedencia" => "0", "estado" => "1"));
                    } else {
                        $this->imagen_m->update($objImagen->id, array("imagen" => $path_single_element, "procedencia" => "0"));
                    }
                }
            }
        }
    }

    /**
     * 
     * @param type $tipo
     * @param type $id
     * @param type $arrayImagenSaved
     */
    public function generarPortadaCanal($objCanal, $objetoMaestro = NULL, $tipo_portada = 5) {
        $user_id = (int) $this->session->userdata('user_id');
        //creamos el objeto
        $objBeanPortada = new stdClass();
        $objBeanPortada->id = NULL;
        $objBeanPortada->canales_id = $objCanal->id;
        if ($objetoMaestro == NULL) {
            $objBeanPortada->nombre = 'Portada ' . $objCanal->nombre; ///PORTADA + nombre del canal
            $objBeanPortada->descripcion = $objCanal->descripcion; //jala del canal
            $objBeanPortada->origen_id = $objCanal->id;
        } else {
            $objBeanPortada->nombre = 'Portada ' . $objetoMaestro->nombre; ///PORTADA + nombre del canal
            $objBeanPortada->descripcion = $objetoMaestro->descripcion; //jala del canal            
            $objBeanPortada->origen_id = $objetoMaestro->id;
        }
        $objBeanPortada->tipo_portadas_id = $tipo_portada; //$this->config->item('portada:canal');
        $objBeanPortada->estado = '0';
        $objBeanPortada->fecha_registro = date("Y-m-d H:i:s");
        $objBeanPortada->usuario_registro = $user_id;
        $objBeanPortada->fecha_actualizacion = date("Y-m-d H:i:s");
        $objBeanPortada->usuario_actualizacion = $user_id;
        $objBeanPortada->id_mongo = '0';
        $objBeanPortada->estado_migracion = 0;
        $objBeanPortada->fecha_migracion = '0000-00-00 00:00:00';
        $objBeanPortada->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
        $objBeanPortadaSaved = $this->portada_m->save($objBeanPortada);

        if ($objetoMaestro == NULL) {
            $arraySecciones = $this->tipo_secciones_m->get_many_by(array());
        } else {
            $arraySecciones = $this->tipo_secciones_m->get_many_by(array());
            foreach ($arraySecciones as $puntero => $oS) {
                if ($oS->id == $this->config->item('seccion:programa')) {
                    unset($arraySecciones[$puntero]);
                }
            }
        }
        $pos = 0;
        //iteramos para crear portadas por cada seccion
        foreach ($arraySecciones as $ind => $objTipoSeccion) {
            if ($objTipoSeccion->id < intval($this->config->item('seccion:perzonalizado'))) {//no se creara secciones personalizadas
                if ($objTipoSeccion->id != intval($this->config->item('seccion:coleccion'))) {//solo secciones que sean difernetes a colecciones
                    $objBeanSeccion = new stdClass();
                    $objBeanSeccion->id = NULL;
                    $objBeanSeccion->nombre = ucwords($objTipoSeccion->nombre); // Destacado + nombre del canal
                    if ($objTipoSeccion->id == $this->config->item('seccion:destacado')) {
                        if ($tipo_portada == $this->config->item('portada:canal')) {
                            $objBeanSeccion->templates_id = $this->config->item('template:destacado_canal');
                        } else {
                            $objBeanSeccion->templates_id = $this->config->item('template:destacado');
                        }
                    } else {
                        if ($objTipoSeccion->id == $this->config->item('seccion:programa')) {
                            $objBeanSeccion->templates_id = '6';
                        } else {
                            if ($objTipoSeccion->id == $this->config->item('seccion:video')) {
                                $objBeanSeccion->templates_id = '5';
                            } else {
                                $objBeanSeccion->templates_id = '3';
                            }
                        }
                    }
                    $objBeanSeccion->descripcion = '';
                    $objBeanSeccion->tipo = '0';
                    $objBeanSeccion->portadas_id = $objBeanPortadaSaved->id;
                    $objBeanSeccion->tipo_secciones_id = $objTipoSeccion->id;
                    $objBeanSeccion->peso = $pos;
                    $objBeanSeccion->id_mongo = '0';
                    $objBeanSeccion->estado = '0';
                    $objBeanSeccion->fecha_registro = date("Y-m-d H:i:s");
                    $objBeanSeccion->usuario_registro = $user_id;
                    $objBeanSeccion->fecha_actualizacion = date("Y-m-d H:i:s");
                    $objBeanSeccion->usuario_actualizacion = $user_id;
                    $objBeanSeccion->estado_migracion = '0';
                    $objBeanSeccion->fecha_migracion = '0000-00-00 00:00:00';
                    $objBeanSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                    $objBeanSeccion->grupo_maestros_id = NULL;
                    $objBeanSeccionSaved = $this->secciones_m->save($objBeanSeccion);

                    /* registramos el detalle de la session, listo todos los programas del canal
                     * obtener la imagen de portada del canal
                     */
                    if ($objTipoSeccion->id == intval($this->config->item('seccion:destacado'))) {//seccion destacado
                        if ($objetoMaestro == NULL) {
                            $objImagen = $this->imagen_m->get_by(array("canales_id" => $objCanal->id, "estado" => "1", "tipo_imagen_id" => $this->config->item('imagen:extralarge')));
                        } else {
                            $objImagen = $this->imagen_m->get_by(array("grupo_maestros_id" => $objetoMaestro->id, "estado" => "1", "tipo_imagen_id" => $this->config->item('imagen:extralarge')));
                        }
                        if (count($objImagen) > 0) {
                            $objBeanDetalleSecciones = new stdClass();
                            $objBeanDetalleSecciones->id = NULL;
                            $objBeanDetalleSecciones->secciones_id = $objBeanSeccionSaved->id;
                            $objBeanDetalleSecciones->reglas_id = NULL;
                            $objBeanDetalleSecciones->videos_id = NULL;
                            $objBeanDetalleSecciones->grupo_maestros_id = NULL;
                            $objBeanDetalleSecciones->categorias_id = NULL;
                            $objBeanDetalleSecciones->tags_id = NULL;
                            $objBeanDetalleSecciones->imagenes_id = $objImagen->id;
                            $objBeanDetalleSecciones->peso = 1;
                            $objBeanDetalleSecciones->descripcion_item = NULL;
                            //$objBeanDetalleSecciones->templates_id = '0';
                            $objBeanDetalleSecciones->estado = 1;
                            $objBeanDetalleSecciones->fecha_registro = date("Y-m-d H:i:s");
                            $objBeanDetalleSecciones->usuario_registro = $user_id;
                            $objBeanDetalleSecciones->estado_migracion = '0';
                            $objBeanDetalleSecciones->fecha_migracion = '0000-00-00 00:00:00';
                            $objBeanDetalleSecciones->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                            $objBeanDetalleSeccionesSaved = $this->detalle_secciones_m->save($objBeanDetalleSecciones);
                            $this->secciones_m->update($objBeanSeccionSaved->id, array("estado" => "1"));
                            //$this->portada_m->update($objBeanPortadaSaved->id, array("estado" => "1"));
                        }
                    } else { //seccion programas
                        $objColeccionGrupoMaestro = $this->obtenerMaestrosParaSecciones($objTipoSeccion->id, $objCanal->id, $objetoMaestro); //$this->grupo_maestro_m->get_many_by(array("tipo_grupo_maestro_id" => "3", "canales_id" => $objCanal->id));
                        if (count($objColeccionGrupoMaestro) > 0) {
                            foreach ($objColeccionGrupoMaestro as $index => $objGrupoMaestro) {
                                if ($this->_obtenerImagenPorMaestro($objGrupoMaestro->id, $this->config->item('imagen:small'), $objTipoSeccion->id, $objCanal->id) > 0) {
                                    $objBeanDetalleSecciones = new stdClass();
                                    $objBeanDetalleSecciones->id = NULL;
                                    $objBeanDetalleSecciones->secciones_id = $objBeanSeccionSaved->id;
                                    $objBeanDetalleSecciones->reglas_id = NULL;
                                    if ($objTipoSeccion->id == $this->config->item('seccion:video')) {
                                        $objBeanDetalleSecciones->videos_id = $objGrupoMaestro->id;
                                        $objBeanDetalleSecciones->grupo_maestros_id = NULL;
                                    } else {
                                        $objBeanDetalleSecciones->videos_id = NULL;
                                        $objBeanDetalleSecciones->grupo_maestros_id = $objGrupoMaestro->id;
                                    }
                                    $objBeanDetalleSecciones->categorias_id = NULL;
                                    $objBeanDetalleSecciones->tags_id = NULL;
                                    $objBeanDetalleSecciones->imagenes_id = $this->_obtenerImagenPorMaestro($objGrupoMaestro->id, $this->config->item('imagen:small'), $objTipoSeccion->id, $objCanal->id); //
                                    $objBeanDetalleSecciones->peso = $index + 2;
                                    $objBeanDetalleSecciones->descripcion_item = NULL;
                                    //$objBeanDetalleSecciones->templates_id = '5';
                                    $objBeanDetalleSecciones->estado = 1;
                                    $objBeanDetalleSecciones->fecha_registro = date("Y-m-d H:i:s");
                                    $objBeanDetalleSecciones->usuario_registro = $user_id;
                                    $objBeanDetalleSecciones->estado_migracion = '0';
                                    $objBeanDetalleSecciones->fecha_migracion = '0000-00-00 00:00:00';
                                    $objBeanDetalleSecciones->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                                    $objBeanDetalleSeccionesSaved = $this->detalle_secciones_m->save($objBeanDetalleSecciones);
                                    $this->portada_m->update($objBeanPortadaSaved->id, array("estado" => "1"));
                                    $this->secciones_m->update($objBeanSeccionSaved->id, array("estado" => "1"));
                                }
                            }
                            //$this->portada_m->update($objBeanPortadaSaved->id, array("estado" => "1"));
                        }
                    }
                } else {//seccion para colecciones
                    $cont_coleccion = $pos;
                    $objColeccionGrupoMaestro = $this->obtenerMaestrosParaSecciones($objTipoSeccion->id, $objCanal->id, $objetoMaestro); //$this->grupo_maestro_m->get_many_by(array("tipo_grupo_maestro_id" => "3", "canales_id" => $objCanal->id));                    
                    if (count($objColeccionGrupoMaestro) > 0) {
                        foreach ($objColeccionGrupoMaestro as $indi => $objMaestroColeccion) {//creamos secciones por cada coleccion que se encuentre
                            $objBeanSeccion = new stdClass();
                            $objBeanSeccion->id = NULL;
                            $objBeanSeccion->nombre = ucwords($objTipoSeccion->nombre); // Destacado + nombre del canal
                            $objBeanSeccion->templates_id = '4';
                            $objBeanSeccion->descripcion = '';
                            $objBeanSeccion->tipo = '0';
                            $objBeanSeccion->portadas_id = $objBeanPortadaSaved->id;
                            $objBeanSeccion->tipo_secciones_id = $objTipoSeccion->id;
                            $objBeanSeccion->peso = $cont_coleccion;
                            $objBeanSeccion->id_mongo = '0';
                            $objBeanSeccion->estado = '0';
                            $objBeanSeccion->fecha_registro = date("Y-m-d H:i:s");
                            $objBeanSeccion->usuario_registro = $user_id;
                            $objBeanSeccion->fecha_actualizacion = date("Y-m-d H:i:s");
                            $objBeanSeccion->usuario_actualizacion = $user_id;
                            $objBeanSeccion->estado_migracion = '0';
                            $objBeanSeccion->fecha_migracion = '0000-00-00 00:00:00';
                            $objBeanSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                            $objBeanSeccion->grupo_maestros_id = NULL;
                            $objBeanSeccionSaved = $this->secciones_m->save($objBeanSeccion);

                            //$objListaGrupoMaestro = $this->obtenerMaestrosParaSecciones($objTipoSeccion->id, $objCanal->id, $objetoMaestro);
                            $objListaGrupoMaestro = array();
                            $lista = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $objMaestroColeccion->id));
                            if (count($lista) > 0) {
                                foreach ($lista as $pun => $objGrupoDetalle) {
                                    if ($objGrupoDetalle->grupo_maestro_id != NULL) {
                                        $objLista = $this->grupo_maestro_m->get($objGrupoDetalle->grupo_maestro_id);
                                        if ($objLista->tipo_grupo_maestro_id == $this->config->item('videos:lista')) {
                                            array_push($objListaGrupoMaestro, $objLista);
                                        }
                                    }
                                }
                            }
                            if (count($objListaGrupoMaestro) > 0) {
                                $exite_item = false;
                                foreach ($objListaGrupoMaestro as $index => $objGrupoMaestroLista) {
                                    if ($this->_obtenerImagenPorMaestro($objGrupoMaestroLista->id, $this->config->item('imagen:small'), $objTipoSeccion->id, $objCanal->id) > 0) {
                                        $objBeanDetalleSecciones = new stdClass();
                                        $objBeanDetalleSecciones->id = NULL;
                                        $objBeanDetalleSecciones->secciones_id = $objBeanSeccionSaved->id;
                                        $objBeanDetalleSecciones->reglas_id = NULL;
                                        $objBeanDetalleSecciones->videos_id = NULL;
                                        $objBeanDetalleSecciones->grupo_maestros_id = $objGrupoMaestroLista->id;
                                        $objBeanDetalleSecciones->categorias_id = NULL;
                                        $objBeanDetalleSecciones->tags_id = NULL;
                                        $objBeanDetalleSecciones->imagenes_id = $this->_obtenerImagenPorMaestro($objGrupoMaestroLista->id, $this->config->item('imagen:small'), $objTipoSeccion->id, $objCanal->id); //
                                        $objBeanDetalleSecciones->peso = $index + 2;
                                        $objBeanDetalleSecciones->descripcion_item = NULL;
                                        //$objBeanDetalleSecciones->templates_id = '5';
                                        $objBeanDetalleSecciones->estado = 1;
                                        $objBeanDetalleSecciones->fecha_registro = date("Y-m-d H:i:s");
                                        $objBeanDetalleSecciones->usuario_registro = $user_id;
                                        $objBeanDetalleSecciones->estado_migracion = '0';
                                        $objBeanDetalleSecciones->fecha_migracion = '0000-00-00 00:00:00';
                                        $objBeanDetalleSecciones->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                                        $objBeanDetalleSeccionesSaved = $this->detalle_secciones_m->save($objBeanDetalleSecciones);
                                        $this->portada_m->update($objBeanPortadaSaved->id, array("estado" => "1"));
                                        $this->secciones_m->update($objBeanSeccionSaved->id, array("estado" => "1"));
                                        $exite_item = true;
                                    }
                                }
                                //registramos la coleccion como item de la seccion coleccion
                                if ($exite_item) {
                                    $objBeanDetalleSecciones = new stdClass();
                                    $objBeanDetalleSecciones->id = NULL;
                                    $objBeanDetalleSecciones->secciones_id = $objBeanSeccionSaved->id;
                                    $objBeanDetalleSecciones->reglas_id = NULL;
                                    $objBeanDetalleSecciones->videos_id = NULL;
                                    $objBeanDetalleSecciones->grupo_maestros_id = $objMaestroColeccion->id;
                                    $objBeanDetalleSecciones->categorias_id = NULL;
                                    $objBeanDetalleSecciones->tags_id = NULL;
                                    $objBeanDetalleSecciones->imagenes_id = $this->_obtenerImagenPorMaestro($objMaestroColeccion->id, $this->config->item('imagen:large'), $objTipoSeccion->id, $objCanal->id); //
                                    $objBeanDetalleSecciones->peso = 0;
                                    $objBeanDetalleSecciones->descripcion_item = NULL;
                                    //$objBeanDetalleSecciones->templates_id = '5';
                                    $objBeanDetalleSecciones->estado = 1;
                                    $objBeanDetalleSecciones->fecha_registro = date("Y-m-d H:i:s");
                                    $objBeanDetalleSecciones->usuario_registro = $user_id;
                                    $objBeanDetalleSecciones->estado_migracion = '0';
                                    $objBeanDetalleSecciones->fecha_migracion = '0000-00-00 00:00:00';
                                    $objBeanDetalleSecciones->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                                    $objBeanDetalleSeccionesSaved = $this->detalle_secciones_m->save($objBeanDetalleSecciones);
                                }
                            }
                            $cont_coleccion++;
                        }
                    } else {//registrar una seccion vacia
                        $objBeanSeccion = new stdClass();
                        $objBeanSeccion->id = NULL;
                        $objBeanSeccion->nombre = ucwords($objTipoSeccion->nombre); // Destacado + nombre del canal
                        $objBeanSeccion->templates_id = '4';
                        $objBeanSeccion->descripcion = '';
                        $objBeanSeccion->tipo = '0';
                        $objBeanSeccion->portadas_id = $objBeanPortadaSaved->id;
                        $objBeanSeccion->tipo_secciones_id = $objTipoSeccion->id;
                        $objBeanSeccion->peso = $pos;
                        $objBeanSeccion->id_mongo = '0';
                        $objBeanSeccion->estado = '0';
                        $objBeanSeccion->fecha_registro = date("Y-m-d H:i:s");
                        $objBeanSeccion->usuario_registro = $user_id;
                        $objBeanSeccion->fecha_actualizacion = date("Y-m-d H:i:s");
                        $objBeanSeccion->usuario_actualizacion = $user_id;
                        $objBeanSeccion->estado_migracion = '0';
                        $objBeanSeccion->fecha_migracion = '0000-00-00 00:00:00';
                        $objBeanSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                        $objBeanSeccion->grupo_maestros_id = NULL;
                        $objBeanSeccionSaved = $this->secciones_m->save($objBeanSeccion);
                    }
                }
            }//FIN del filtro de secciones personalizados
            $pos++;
        }// FIN de la iteracion por SECCIONES
    }

    public function obtenerVideosCanal($canal_id) {
        $returnValue = array();
        $lista_video = $this->videos_m->get_many_by(array("canales_id" => $canal_id));
        if (count($lista_video) > 0) {
            foreach ($lista_video as $index => $objVideo) {
                if ($this->tieneMaestro($objVideo->id)) {
                    unset($lista_video[$index]);
                }
            }
            $returnValue = $lista_video;
        }
        return $returnValue;
    }

    public function tieneMaestro($video_id) {
        $returnValue = false;
        $listaMaestro = $this->grupo_detalle_m->get_many_by(array("video_id" => $video_id));
        if (count($listaMaestro) > 0) {
            $returnValue = true;
        }
        return $returnValue;
    }

    public function obtenerMaestrosParaSecciones($session_tipo_id, $canal_id, $objMaestro = NULL) {
        $returnValue = array();
        if ($session_tipo_id < intval($this->config->item('seccion:visto'))) { //solo videos, listas, colecciones, programas
            if ($objMaestro == NULL) {
                if ($session_tipo_id == intval($this->config->item('seccion:programa'))) {//programa
                    $returnValue = $this->grupo_maestro_m->get_many_by(array("tipo_grupo_maestro_id" => $this->config->item('videos:programa'), "canales_id" => $canal_id));
                } else {
                    if ($session_tipo_id == intval($this->config->item('seccion:video'))) {//video
                        $returnValue = $this->obtenerVideosCanal($canal_id);
                    } else {//coleccion y lista del canal
                        if ($session_tipo_id == intval($this->config->item('seccion:coleccion'))) {
                            $tipo_grupo_maestro = $this->config->item('videos:coleccion');
                        } else {
                            if ($session_tipo_id == intval($this->config->item('seccion:lista'))) {
                                $tipo_grupo_maestro = $this->config->item('videos:lista');
                            }
                        }
                        $returnValue = $this->_getListMasterChannel($this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $tipo_grupo_maestro, 'canales_id' => $canal_id), 'nombre'));
                        unset($returnValue[0]);
                    }
                }
            } else {
                if ($session_tipo_id == intval($this->config->item('seccion:video'))) {
                    $returnValue = $this->obtenerVideosPrograma($objMaestro->id);
                } else {
                    if ($session_tipo_id == intval($this->config->item('seccion:coleccion'))) {
                        $tipo_grupo_maestro = $this->config->item('videos:coleccion');
                    } else {
                        if ($session_tipo_id == intval($this->config->item('seccion:lista'))) {
                            $tipo_grupo_maestro = $this->config->item('videos:lista');
                        }
                    }
                    $returnValue = $this->_obtenerMaestrosPrograma($tipo_grupo_maestro, $objMaestro->id);
                }
            }
        }
        return $returnValue;
    }

    public function _obtenerMaestrosPrograma($tipo_grupo_maestro, $maestro_programa_id) {
        $returnValue = array();
        $coleccionMaestroDetalle = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $maestro_programa_id));
        if (count($coleccionMaestroDetalle) > 0) {
            foreach ($coleccionMaestroDetalle as $index => $objDetalle) {
                if ($objDetalle->grupo_maestro_id != NULL) {
                    $objMaestro = $this->grupo_maestro_m->get($objDetalle->grupo_maestro_id);
                    if (count($objMaestro) > 0) {
                        if ($objMaestro->tipo_grupo_maestro_id == $tipo_grupo_maestro) {
                            array_push($returnValue, $objMaestro);
                        }
                    }
                }
            }
        }
        return $returnValue;
    }

    public function obtenerVideosPrograma($programa_id) {
        $returnValue = array();
        $coleccionMaestroDetalle = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $programa_id, "tipo_grupo_maestros_id" => $this->config->item('videos:programa')));
        if (count($coleccionMaestroDetalle) > 0) {
            foreach ($coleccionMaestroDetalle as $index => $objMaestroDetalle) {
                if ($objMaestroDetalle->grupo_maestro_id == NULL && $objMaestroDetalle->video_id != NULL) {
                    array_push($returnValue, $objMaestroDetalle);
                }
            }
        }
        return $returnValue;
    }

    public function _getListMasterChannel(&$arrayMaestro) {
        //$returnValue[0] = lang('videos:select_list');
        if (count($arrayMaestro) > 0) {
            foreach ($arrayMaestro as $master_id => $name_master) {
                if ($master_id > 0) {
                    if ($this->_isParentOrChild($master_id)) {
                        unset($arrayMaestro[$master_id]);
                    }
                }
            }
        }
        if (count($arrayMaestro) > 0) {
            $arrayObject = array();
            foreach ($arrayMaestro as $id => $value) {
                $objMaestro = $this->grupo_maestro_m->get($id);
                if (count($objMaestro) > 0) {
                    array_push($arrayObject, $objMaestro);
                }
            }
            $arrayMaestro = $arrayObject;
        }
        return $arrayMaestro;
    }

    public function _isParentOrChild($master_id) {
        $returnValue = false;
        $objCollectionMaster = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $master_id, "video_id" => "NULL"));

        if (count($objCollectionMaster) > 0) {
            $returnValue = true;
        } else {
            $objCollectionChild = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_id" => $master_id));
            if (count($objCollectionChild) > 0) {
                $returnValue = true;
            }
        }
        return $returnValue;
    }

    /**
     * mueve y guardas las imagenes para la portada y las registra en la BD
     * @param type $array_images
     * @param type $canal_id
     * @return array
     */
    public function saveImages($array_images, $canal_id) {
        $returnvalue = array();
        $user_id = (int) $this->session->userdata('user_id');
        if (count($array_images) > 0) {
            foreach ($array_images as $index => $image) {
                $path_image = $this->config->item('path:temp') . $image;
                $new_path_image = $this->config->item('path:imagen') . $image;
                umask(0);
                if (copy($path_image, $new_path_image)) {
                    unlink($path_image);
                }
                $objBeanImagen = new stdClass();
                $objBeanImagen->id = NULL;
                $objBeanImagen->canales_id = $canal_id;
                $objBeanImagen->grupo_maestros_id = NULL;
                $objBeanImagen->videos_id = NULL;
                $objBeanImagen->imagen = $new_path_image;
                $objBeanImagen->tipo_imagen_id = $index;
                $objBeanImagen->estado = '1';
                $objBeanImagen->fecha_registro = date("Y-m-d H:i:s");
                $objBeanImagen->usuario_registro = $user_id;
                $objBeanImagen->fecha_actualizacion = date("Y-m-d H:i:s");
                $objBeanImagen->usuario_actualizacion = $user_id;
                $objBeanImagen->estado_migracion = '0';
                $objBeanImagen->fecha_migracion = '0000-00-00 00:00:00';
                $objBeanImagen->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                $objBeanImagen->imagen_padre = NULL;
                $objBeanImagen->procedencia = '0';
                $objBeanImagenSaved = $this->imagen_m->saveImage($objBeanImagen);
                array_push($returnvalue, $objBeanImagenSaved);
            }
        }
        return $returnvalue;
    }

    /**
     * 
     * @param type $fid id  de la imagen local de la BD
     * @param type $file nombre de la imagen [ruta absuluta /var/...]
     * @param type $mensaje 
     * @return string  name, direccion real de la imagen dominio
     */
    public function elemento_upload($fid, $file, $mensaje = '') {
        //$url = "http://dev.e3.pe/index.php/api/v1";
        if (strlen(trim($mensaje)) == 0) {
            $mensaje = $this->config->item('mensaje:elemento');
        }
        $url = $this->config->item('url:elemento');
        $remotedir = $this->elemento_basepath($fid, $this->config->item('server:elemento'));
        $ext = explode('.', $file);
        $infofile = urlencode(file_get_contents($file)); //encode_content_file($file);
        $data = array(
            //'apikey' => '590ee43e919b1f4baa2125a424f03cd160ff8901',
            'apikey' => $this->config->item('apikey:elemento'),
            'name' => $fid . '.' . $ext[count($ext) - 1],
            'content' => $infofile,
            //'ruta' => 'files/' . $remotedir,
            'ruta' => $remotedir,
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, $mensaje);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        curl_close($ch);
        return $data['ruta'] . $data['name'];
    }

    /**
     * 
     * @param type $fid
     * @param type $container
     * @return string
     */
    public function elemento_basepath($fid, $container) {
//    $container = md_elemento_container($ext);
        $filename = str_pad($fid, 8, "0", STR_PAD_LEFT);
        $dir_split_file = preg_split('//', substr($filename, 0, strlen($filename) - 3), -1, PREG_SPLIT_NO_EMPTY);
        $scheme_dir = implode('/', $dir_split_file);
        return $container . '/' . $scheme_dir . '/';
    }

    /**
     * metodo para verificar si el  nombre del canal ya existe
     * @param string $nombre_canal
     * @return boolean
     */
    public function _existeCanal($nombre_canal, $canal_id = 0) {
        $returnValue = false;
        if ($canal_id == 0) {
            $existe = $this->canales_m->like("nombre", $nombre_canal)->count_by(array());
            if ($existe > 0) {
                $returnValue = true;
            }
        } else {
            $lista = $this->canales_m->like("nombre", $nombre_canal)->get_many_by(array());
            if (count($lista) > 0) {
                foreach ($lista as $index => $objCanal) {
                    if (strtolower($nombre_canal) == strtolower($objCanal->nombre) && $objCanal->id != $canal_id) {
                        $returnValue = true;
                        break;
                    }
                }
            }
        }

        return $returnValue;
    }

    /**
     * metodo para subir las imagenes temporales a la carpeta uploads
     * 
     */
    public function subir_imagen($type_image) {
        $fileType = array('image/jpeg', 'image/pjpeg', 'image/png');
        // Bandera para procesar las fotos si pasa el tamaño definido
        $pasaImgSize = false;
        //bandera de error al procesar las fotos
        $respuestaFile = false;
        // nombre por default de las fotos a subir
        $fileName = '';
        // error del lado del servidor
        $mensajeFile = 'ERROR EN EL SCRIPT';
        //array de imagenes cortadas
        $image = '';

        // Obtenemos los datos del archivo
        $tamanio = $_FILES['userfile']['size'];
        $tipo = $_FILES['userfile']['type'];
        $archivo = $_FILES['userfile']['name'];
        // Tamaño de la imagen
        $imageSize = getimagesize($_FILES['userfile']['tmp_name']);

        // Verificamos la extensión del archivo independiente del tipo mime
        $extension = explode('.', $_FILES['userfile']['name']);
        $num = count($extension) - 1;
        // Creamos el nombre del archivo dependiendo la opción
        $imgFile = time() . '.' . $extension[$num];
        //obtenemos las dimenciones de la BD
        switch ($type_image) {
            case 'portada':
                $objTipoImagenUpload = $this->tipo_imagen_m->get($this->config->item('imagen:extralarge'));
                break;
            case 'logo':
                $objTipoImagenUpload = $this->tipo_imagen_m->get($this->config->item('imagen:logo'));
                break;
            case 'iso':
                $objTipoImagenUpload = $this->tipo_imagen_m->get($this->config->item('imagen:iso'));
                break;
        }
        // Verificamos el tamaño válido para las fotos
        if ($imageSize[0] >= $objTipoImagenUpload->ancho && $imageSize[1] >= $objTipoImagenUpload->alto && ($extension[$num] == 'jpg' || $extension[$num] == 'png')) {
            $pasaImgSize = true;
        }
        // Verificamos el status de las dimensiones de la imagen a publicar mediante nuestro jQuery para fotos
        if ($pasaImgSize == true) {
            // Verificamos Tamaño y extensiones
            if (in_array($tipo, $fileType) && $tamanio > 0 && $tamanio <= $this->config->item('imagen:maxSize')) {
                // Intentamos copiar el archivo
                if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
                    umask(0);
                    // Verificamos si se pudo copiar el archivo a nustra carpeta
                    //if (move_uploaded_file($_FILES['userfile']['tmp_name'], UPLOAD_IMAGENES_VIDEOS . '../temp/' . $imgFile)) {
                    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $this->config->item('path:temp') . $imgFile)) {
                        //usamos el crop de imagemagic para crear las 4 imagenes
                        //$arrayTipoImagen = $this->tipo_imagen_m->listType();
                        $width = $imageSize[0];
                        $height = $imageSize[1];
                        if ($width >= $objTipoImagenUpload->ancho && $height >= $objTipoImagenUpload->alto) {
                            //$this->imagenes_lib->loadImage(UPLOAD_IMAGENES_VIDEOS . '../temp/' . $imgFile);
                            $this->imagenes_lib->loadImage($this->config->item('path:temp') . $imgFile);
                            $this->imagenes_lib->crop($objTipoImagenUpload->ancho, $objTipoImagenUpload->alto, 'center');
                            //$this->imagenes_lib->save(UPLOAD_IMAGENES_VIDEOS . '../temp/' . preg_replace("/\\.[^.\\s]{3,4}$/", "", $imgFile) . '_' . $objTipoImagenUpload->ancho . 'x' . $objTipoImagenUpload->alto . '.' . $extension[$num]);
                            $this->imagenes_lib->save($this->config->item('path:temp') . preg_replace("/\\.[^.\\s]{3,4}$/", "", $imgFile) . '_' . $objTipoImagenUpload->ancho . 'x' . $objTipoImagenUpload->alto . '.' . $extension[$num]);
                            //array_push($arrayImagenes, preg_replace("/\\.[^.\\s]{3,4}$/", "", $imgFile) . '_' . $objTipoImagenUpload->ancho . 'x' . $objTipoImagenUpload->alto . '.' . $extension[$num]);
                            $imageCroped = preg_replace("/\\.[^.\\s]{3,4}$/", "", $imgFile) . '_' . $objTipoImagenUpload->ancho . 'x' . $objTipoImagenUpload->alto . '.' . $extension[$num];
                        }
                        //eliminamos el archivo madre
                        if (file_exists($this->config->item('path:temp') . $imgFile)) {
                            unlink($this->config->item('path:temp') . $imgFile);
                        }
                        $respuestaFile = 'done';
                        $fileName = $imgFile;
                        $mensajeFile = $imgFile;
                        $image = $imageCroped;
                    } else {
                        // error del lado del servidor
                        $mensajeFile = 'No se pudo subir el archivo';
                    }
                } else {
                    // error del lado del servidor
                    $mensajeFile = 'No se pudo subir el archivo';
                }
            } else {
                // Error en el tamaño y tipo de imagen
                $mensajeFile = 'Verifique el tamanio y tipo de imagen';
            }
        } else {
            // Error en las dimensiones de la imagen
            $mensajeFile = 'Verificar que la imagen tenga las dimensiones, el ancho:' . $objTipoImagenUpload->ancho . ', la altura: ' . $objTipoImagenUpload->alto;
        }
        $salidaJson = array("respuesta" => $respuestaFile,
            "mensaje" => $mensajeFile,
            "image" => $image,
            "fileNameToDelete" => $fileName);
        echo json_encode($salidaJson);
    }

    /**
     * metodo para disparar las secciones predefinidas
     * @param int $canal_id
     */
    public function dispatch($canal_id) {
        if ($this->input->is_ajax_request()) {
            if ($canal_id > 0 && !$this->existeUnRegistro($canal_id)) {
                $objCanal = $this->canales_m->get($canal_id);
                $this->generarPortadaCanal($objCanal, NULL);
                $this->generarPortadaPrograma($canal_id);
                echo json_encode(array("value" => "1"));
            } else {
                echo json_encode(array("value" => "0"));
            }
        }
    }

    public function _obtenerImagenPorMaestro($maestro_id, $image_type, $seccion, $canal_id) {
        $returnValue = 0;
        if ($seccion == $this->config->item('seccion:video')) {
            $objImagen = $this->imagen_m->get_by(array("canales_id" => $canal_id, "tipo_imagen_id" => $image_type));
        } else {
            $objImagen = $this->imagen_m->get_by(array("grupo_maestros_id" => $maestro_id, "tipo_imagen_id" => $image_type));
        }
        if (count($objImagen) > 0) {
            $returnValue = $objImagen->id;
        }
        return $returnValue;
    }

    /**
     * 
     * @param type $canal_id
     * @return boolean
     */
    public function existeUnRegistro($canal_id) {
        $returnValue = false;
        $objPortadaCanal = $this->portada_m->get_by(array("canales_id" => $canal_id));
        if (count($objPortadaCanal) > 0) {
            $returnValue = true;
        }
        return $returnValue;
    }

    public function generarPortadaPrograma($canal_id) {
        //listamos todos los programas del canal
        $objCanal = $this->canales_m->get($canal_id);
        $coleccionPrograma = $this->grupo_maestro_m->get_many_by(array("canales_id" => $canal_id, "tipo_grupo_maestro_id" => $this->config->item('videos:programa')));
        if (count($coleccionPrograma) > 0) {
            foreach ($coleccionPrograma as $index => $objMaestro) {
                //if($objMaestro->id == '382'){
                $this->generarPortadaCanal($objCanal, $objMaestro, $this->config->item('portada:programa'));
                //}
            }
        }
    }

    public function registrar_portada($canal_id) {
        $imagen = $this->input->post('image');
        $path_image = $this->config->item('path:temp') . $imagen;
        $returnValue = 0;
        if (file_exists($path_image)) {
            //actualizar las imagenes de este tipo a estado borrador
            //$this->imagen_m->update_many(array("canales_id"=>$canal_id, "tipo_imagen_id"=>$this->config->item('imagen:extralarge')), array("estado"=>$this->config->item('estado:borrador')));
            $this->imagen_m->deshabilitar($canal_id, $this->config->item('imagen:extralarge'));
            $user_id = (int) $this->session->userdata('user_id');
            $objBeanImage = new stdClass();
            $objBeanImage->id = NULL;
            $objBeanImage->canales_id = $canal_id; //$canal_id;
            $objBeanImage->grupo_maestros_id = NULL; //$grupo_maestro_id;
            $objBeanImage->videos_id = NULL;
            $objBeanImage->imagen = $path_image;
            $objBeanImage->tipo_imagen_id = $this->config->item('imagen:extralarge');
            $objBeanImage->estado = $this->config->item('estado:publicado');
            $objBeanImage->fecha_registro = date("Y-m-d H:i:s");
            $objBeanImage->usuario_registro = $user_id;
            $objBeanImage->fecha_actualizacion = date("Y-m-d H:i:s");
            $objBeanImage->usuario_actualizacion = $user_id;
            $objBeanImage->estado_migracion = 0;
            $objBeanImage->fecha_migracion = '0000-00-00 00:00:00';
            $objBeanImage->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
            $objBeanImage->imagen_padre = NULL;
            $objBeanImage->procedencia = 0;
            $objBeanImageSaved = $this->imagen_m->saveImage($objBeanImage);

            //deshabilitamos todas las imagenes
            //$listaImagenes = $this->imagen_m->get_many_by(array("canales_id" => $canal_id, "tipo_imagen_id" => $this->config->item('imagen:extralarge')));
            /* if (count($listaImagenes) > 0) {
              foreach ($listaImagenes as $index => $objImagen) {
              if ($objBeanImageSaved->id == $objImagen->id) {
              $this->imagen_m->update($objImagen->id, array("estado" => "1"));
              } else {
              $this->imagen_m->update($objImagen->id, array("estado" => "0"));
              }
              }
              } */
            //subir la imagen a elemento
            $path_image_element = $this->elemento_upload($objBeanImageSaved->id, $objBeanImageSaved->imagen);
            $array_path = explode("/", $path_image_element);
            if ($array_path[0] == $this->config->item('server:elemento')) {
                unset($array_path[0]);
            }
            $path_single_element = implode('/', $array_path);
            $this->imagen_m->update($objBeanImageSaved->id, array("imagen" => $path_single_element));
            //eliminamos la imagen local
            unlink($path_image);
            //echo json_encode(array("error" => "0"));
            $returnValue = 1;
        }
        $arrayImagenes = $this->imagen_m->order_by('estado', 'DESC')->get_many_by(array("canales_id" => $canal_id, "tipo_imagen_id" => $this->config->item('imagen:extralarge')));
        foreach ($arrayImagenes as $indice => $objImg) {
            $objImg->path = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImg->imagen;
            $arrayImagenes[$indice] = $objImg;
        }
        echo json_encode(array('respuesta' => $returnValue, 'imagen_id' => $objBeanImageSaved->id, 'imagenes' => $arrayImagenes));
    }

    public function active_portada($imagen_id, $canal_id) {
        if ($this->input->is_ajax_request()) {
            $listaImagen = $this->imagen_m->get_many_by(array("canales_id" => $canal_id, "tipo_imagen_id" => $this->config->item('imagen:extralarge')));
            if (count($listaImagen) > 0) {
                foreach ($listaImagen as $index => $objImagen) {
                    if ($objImagen->id == $imagen_id) {
                        $this->imagen_m->update($objImagen->id, array("estado" => "1"));
                    } else {
                        $this->imagen_m->update($objImagen->id, array("estado" => "0"));
                    }
                }
            }
            //actualizamos la imagen de portada en el detalle de secciones
            //$this->actualizarPortadaCanal($canal_id);
            echo json_encode(array("respuesta" => "1"));
        }
    }

    public function generar_imagenes_lista() {
        $listas = $this->grupo_maestro_m->get_many_by(array("tipo_grupo_maestro_id" => $this->config->item('videos:lista')));
        if (count($listas) > 0) {
            foreach ($listas as $index => $objMaestroLista) {
                if (!$this->tieneImagen($objMaestroLista->id, $this->config->item('imagen:small'))) {
                    $listaDetalles = $this->grupo_detalle_m->order_by('video_id', 'ASC')->get_many_by(array("grupo_maestro_padre" => $objMaestroLista->id));
                    if (count($listaDetalles) > 0) {
                        foreach ($listaDetalles as $indexDetalle => $objDetalleMaestro) {
                            if ($objDetalleMaestro->video_id != NULL) {
                                $objImagen = $this->imagen_m->get_by(array("videos_id" => $objDetalleMaestro->video_id, "tipo_imagen_id" => $this->config->item('imagen:small'), "estado" => "1"));
                                if (count($objImagen) > 0) {
                                    //registrar imagen para la lista
                                    $objBeanImagen = new stdClass();
                                    $objBeanImagen->id = NULL;
                                    $objBeanImagen->canales_id = NULL;
                                    $objBeanImagen->grupo_maestros_id = $objMaestroLista->id;
                                    $objBeanImagen->videos_id = NULL;
                                    $objBeanImagen->imagen = $objImagen->imagen;
                                    $objBeanImagen->tipo_imagen_id = $this->config->item('imagen:small');
                                    $objBeanImagen->estado = $this->config->item('imagen:publicado');
                                    $objBeanImagen->fecha_registro = $objImagen->fecha_registro;
                                    $objBeanImagen->usuario_registro = $objImagen->usuario_registro;
                                    $objBeanImagen->fecha_actualizacion = $objImagen->fecha_actualizacion;
                                    $objBeanImagen->usuario_actualizacion = $objImagen->usuario_actualizacion;
                                    $objBeanImagen->estado_migracion = $objImagen->estado_migracion;
                                    $objBeanImagen->fecha_migracion = $objImagen->fecha_migracion;
                                    $objBeanImagen->fecha_migracion_actualizacion = $objImagen->fecha_migracion_actualizacion;
                                    $objBeanImagen->imagen_padre = NULL;
                                    $objBeanImagen->procedencia = $objImagen->procedencia;
                                    $objBeanImagenSaved = $this->imagen_m->saveImage($objBeanImagen);
                                    $this->vd($objBeanImagenSaved);
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    private function tieneImagen($lista_id, $type) {
        $returnValue = false;
        $listaImagen = $this->imagen_m->get_many_by(array("grupo_maestros_id" => $lista_id, "tipo_imagen_id" => $type, "estado" => "1"));
        if (count($listaImagen) > 0) {
            $returnValue = true;
        }
        return $returnValue;
    }

    public function generar_imagenes_colecciones() {
        $programas = $this->grupo_maestro_m->get_many_by(array("tipo_grupo_maestro_id" => $this->config->item('videos:programa')));
        if (count($programas) > 0) {
            foreach ($programas as $indexPrograma => $objMaestroPrograma) {
                if ($this->tieneImagen($objMaestroPrograma->id, $this->config->item('imagen:large'))) {
                    $listacoleccion = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $objMaestroPrograma->id));
                    $objImagenPrograma = $this->imagen_m->get_by(array("grupo_maestros_id" => $objMaestroPrograma->id, "tipo_imagen_id" => $this->config->item('imagen:large'), "estado" => "1"));
                    if (count($listacoleccion) > 0) {
                        foreach ($listacoleccion as $indexColeccion => $objDetalle) {
                            if (!$this->tieneImagen($objDetalle->grupo_maestro_id, $this->config->item('imagen:large'))) {
                                //registrar imagen para las colecciones
                                $objBeanImagen = new stdClass();
                                $objBeanImagen->id = NULL;
                                $objBeanImagen->canales_id = NULL;
                                $objBeanImagen->grupo_maestros_id = $objDetalle->grupo_maestro_id;
                                $objBeanImagen->videos_id = NULL;
                                $objBeanImagen->imagen = $objImagenPrograma->imagen;
                                $objBeanImagen->tipo_imagen_id = $this->config->item('imagen:large');
                                $objBeanImagen->estado = $this->config->item('imagen:publicado');
                                $objBeanImagen->fecha_registro = $objImagenPrograma->fecha_registro;
                                $objBeanImagen->usuario_registro = $objImagenPrograma->usuario_registro;
                                $objBeanImagen->fecha_actualizacion = $objImagenPrograma->fecha_actualizacion;
                                $objBeanImagen->usuario_actualizacion = $objImagenPrograma->usuario_actualizacion;
                                $objBeanImagen->estado_migracion = $objImagenPrograma->estado_migracion;
                                $objBeanImagen->fecha_migracion = $objImagenPrograma->fecha_migracion;
                                $objBeanImagen->fecha_migracion_actualizacion = $objImagenPrograma->fecha_migracion_actualizacion;
                                $objBeanImagen->imagen_padre = NULL;
                                $objBeanImagen->procedencia = $objImagenPrograma->procedencia;
                                $objBeanImagenSaved = $this->imagen_m->saveImage($objBeanImagen);
                                $this->vd($objBeanImagenSaved);
                            }
                        }
                    }
                }
            }
        }
    }

    public function portada($canal_id) {
        $objCanal = $this->canales_m->get($canal_id);
        $title = "Portada  del canal " . $objCanal->nombre;
        //parametros de paginacion
        if ($this->input->post('f_estado') > 0) {
            if ($this->input->post('f_estado') == '3') {
                $estado_cambiado = $this->config->item('estado:borrador');
            } else {
                $estado_cambiado = $this->input->post('f_estado');
            }
            $base_where = array("canales_id" => $canal_id, "estado" => $estado_cambiado);
        } else {
            $base_where = array("canales_id" => $canal_id);
        }
        $keyword = '';
        if ($this->input->post('f_keywords'))
            $keyword = $this->input->post('f_keywords');
        // Create pagination links
        if (strlen(trim($keyword)) > 0) {
            $total_rows = $this->portada_m->like('nombre', $keyword)->count_by($base_where);
        } else {
            $total_rows = $this->portada_m->count_by($base_where);
        }
        $pagination = create_pagination('admin/canales/portada/' . $canal_id . '/index', $total_rows, 5, 6);

        //actualizamo la fecha de registro de la portada principal para que siempre sea el primero en mostrarse
        $this->portada_m->ubicar_primero_portada_canal($canal_id);
        // Using this data, get the relevant results
        if (strlen(trim($keyword)) > 0) {
            $coleccionPortada = $this->portada_m->order_by('fecha_registro', 'DESC')->like('nombre', $keyword)->limit($pagination['limit'])->get_many_by($base_where);
        } else {
            $coleccionPortada = $this->portada_m->order_by('fecha_registro', 'DESC')->limit($pagination['limit'])->get_many_by($base_where);
        }

        if (count($coleccionPortada) > 0) {
            foreach ($coleccionPortada as $index => $objPortada) {
                $objPortada->secciones = $this->secciones_m->order_by('peso', 'ASC')->get_many_by(array("portadas_id" => $objPortada->id));
                $coleccionPortada[$index] = $objPortada;
            }
        }
        $tipo_portada = $this->tipo_portada_m->getTipoPortadaDropDown();
        //$tipo_seccion = $this->tipo_secciones_m->getSeccionDropDown();
        $tipo_seccion = array($this->config->item('seccion:programa') => "Programa", $this->config->item('seccion:personalizado') => "Personalizado");
        $templates = $this->templates_m->getTemplateDropDown();
        $estados = array($this->config->item('estado:publicado') => "Publicado", "3" => "Borrador", $this->config->item('estado:eliminado') => "Eliminado");
        //do we need to unset the layout because the request is ajax?
        $this->input->is_ajax_request() and $this->template->set_layout(FALSE);
        $this->template
                ->title($this->module_details['name'])
                ->append_js('admin/filter.js')
                ->append_js('module::jquery.ddslick.min.js')
                ->append_js('module::jquery.tablednd.js')
                ->set_partial('filters', 'admin/partials/filters')
                ->set_partial('portadas', 'admin/tables/portadas')
                ->append_js('module::jquery.alerts.js')
                ->append_css('module::jquery.alerts.css')
                ->set('pagination', $pagination)
                ->set('title', $title)
                ->set('tipo', $tipo_portada)
                ->set('estados', $estados)
                ->set('tipo_seccion', $tipo_seccion)
                ->set('canal_id', $canal_id)
                ->set('objCanal', $objCanal)
                ->set('templates', $templates)
                ->set('portadas', $coleccionPortada);
        $this->input->is_ajax_request() ? $this->template->build('admin/tables/portadas') : $this->template->build('admin/portada');
    }

    /**
     * Método para cargar la vista sección con los detalles de este mismo.
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $canal_id
     * @param int $seccion_id
     */
    public function seccion($canal_id, $seccion_id) {
        //cargamos el objetos seccion
        if ($seccion_id > 0) {
            $objSeccion = $this->secciones_m->get($seccion_id);
        }
        $title = $this->module_details['name'] = 'Sección - ' . $objSeccion->nombre;
        //agregamos el detalle de la seccion como un atributo al objeto
        //$objSeccion->detalle = $this->detalle_secciones_m->order_by('peso', 'ASC')->get_many_by(array("secciones_id" => $objSeccion->id));

        $base_where = array("secciones_id" => $objSeccion->id, "estado" => $this->config->item('estado:publicado'));
        // Create pagination links
        $total_rows = $this->detalle_secciones_m->count_by($base_where);
        //admin/canales/seccion/7/2637
        $pagination = create_pagination('admin/canales/seccion/' . $canal_id . '/' . $objSeccion->id . '/index/', $total_rows, 5, 7);
        $objSeccion->detalle = $this->agregarValores($this->detalle_secciones_m->order_by('peso', 'ASC')->limit($pagination['limit'])->get_many_by($base_where));
        //obtener el último item
        $ultimo = $this->detalle_secciones_m->order_by('peso', 'DESC')->get_by(array("secciones_id" => $seccion_id, "estado" => $this->config->item('estado:publicado')));
        if (count($ultimo) == 0) {
            $objUltimo = new stdClass();
            $objUltimo->id = 0;
            $ultimo = $objUltimo;
        }
        //obtener el primer item
        $primero = $this->detalle_secciones_m->order_by('peso', 'ASC')->get_by(array("secciones_id" => $seccion_id, "estado" => "1"));
        if (count($primero) == 0) {
            $objPrimero = new stdClass();
            $objPrimero->id = 0;
            $primero = $objPrimero;
        }
        //lista de templates
        //$templates = $this->templates_m->getTemplateDropDown();
        //$templates = $this->templates_m->getTemplateDropDown(array("id" => $objSeccion->templates_id));
        $objTemplate = $this->templates_m->get($objSeccion->templates_id);
        $templates = array($objTemplate->id => $objTemplate->nombre);
        //tipo de secciones
        $secciones = $this->tipo_secciones_m->getSeccionDropDown();
        $this->input->is_ajax_request() and $this->template->set_layout(FALSE);
        $this->template
                ->title($this->module_details['name'])
                ->append_js('module::jquery.tablednd.js')
                ->append_js('admin/filter.js')
                ->set_partial('filters', 'admin/partials/filters')
                ->append_js('module::jquery.alerts.js')
                ->append_js('module::smartpaginator.js')
                ->append_css('module::smartpaginator.css')
                ->append_css('module::jquery.alerts.css')
                ->set_partial('secciones', 'admin/tables/secciones')
                ->set('objSeccion', $objSeccion)
                ->set('templates', $templates)
                ->set('tipo_seccion', $secciones)
                ->set('canal_id', $canal_id)
                ->set('pagination', $pagination)
                ->set('ultimo', $ultimo)
                ->set('primer', $primero)
                ->set('title', $title);
        $this->input->is_ajax_request() ? $this->template->build('admin/tables/secciones') : $this->template->build('admin/seccion');
        //}
    }

    public function actualizar_destacado() {
        //actualizar la seccion
        $this->secciones_m->update($this->input->post('seccion_id'), array('nombre' => $this->input->post('nombre'), 'descripcion' => $this->input->post('descripcion')));
        //obtener el ID del detalle de la sección para el update
        $objDetalleSeccion = $this->detalle_secciones_m->get_by(array("secciones_id" => $this->input->post('seccion_id')));
        $this->detalle_secciones_m->update($objDetalleSeccion->id, array("descripcion_item" => $this->input->post('descripcion_portada')));
        echo json_encode(array('value' => '1'));
    }

    /**
     * metodo para obtener los valores de los ID
     * @param array $arrayDetalleSeccion
     * @return array
     */
    public function agregarValores(&$arrayDetalleSeccion) {
        if (count($arrayDetalleSeccion) > 0) {

            foreach ($arrayDetalleSeccion as $index => $objDetalleSeccion) {
                $objImagen = $this->imagen_m->get($objDetalleSeccion->imagenes_id);
                if (count($objImagen) > 0) {
                    if ($objImagen->procedencia == '0') {
                        $objDetalleSeccion->imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagen->imagen;
                    } else {
                        $objDetalleSeccion->imagen = $objImagen->imagen;
                    }
                } else {
                    $objDetalleSeccion->imagen = $this->config->item('url:default_imagen') . 'no_video.jpg';
                }
                //nombre del Item
                $objDetalleSeccion->nombre = '';
                $objDetalleSeccion->tipo = '';
                if ($objDetalleSeccion->grupo_maestros_id != NULL) {
                    $objMaestro = $this->grupo_maestro_m->get($objDetalleSeccion->grupo_maestros_id);
                    if (count($objMaestro) > 0) {
                        $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro->tipo_grupo_maestro_id);
                        $objDetalleSeccion->tipo = $objTipoMaestro->nombre;
                        $objDetalleSeccion->nombre = $objMaestro->nombre;
                    }
                } else {
                    if ($objDetalleSeccion->videos_id != NULL) {
                        $objVideo = $this->videos_m->get($objDetalleSeccion->videos_id);
                        if (count($objVideo) > 0) {
                            $objDetalleSeccion->tipo = 'Video';
                            $objDetalleSeccion->nombre = $objVideo->titulo;
                        }
                    } else {
                        if ($objDetalleSeccion->canales_id != NULL) {
                            $objCanal = $this->canales_m->get($objDetalleSeccion->canales_id);
                            if (count($objCanal) > 0) {
                                $objDetalleSeccion->tipo = 'Canal';
                                $objDetalleSeccion->nombre = $objCanal->nombre;
                            }
                        }
                    }
                }
                $arrayDetalleSeccion[$index] = $objDetalleSeccion;
            }
        }
        return $arrayDetalleSeccion;
    }

    /**
     * ordenamos los items mediando drag and drop
     * @param int $seccion_id
     * @return json respuesta
     */
    public function reordenar($seccion_id) {

        $arrayIndexOrder = $this->input->post('table-1');
        if (count($arrayIndexOrder) > 0) {
            $array_index = array();
            foreach ($arrayIndexOrder as $index => $value) {
                if (strlen(trim($value)) > 0) {
                    $value = explode('_', $value);
                    $array_index[$value[1]] = $value[0];
                }
            }
        }
        //obtenemos la lista original de la BD
        $lista_original = $this->detalle_secciones_m->getListaOriginal($array_index);
        if (count($lista_original) > 0) {
            $array_original = array();
            foreach ($lista_original as $index => $objDetalleSeccion) {
                array_push($array_original, $objDetalleSeccion->peso);
            }
        }

        //actualizamos las nuevas posiciones
        $cont = 0;
        foreach ($array_index as $peso => $detalle_seccion_id) {
            $this->detalle_secciones_m->update($detalle_seccion_id, array("peso" => $array_original[$cont], "estado_migracion" => $this->config->item('migracion:actualizado')));
            $cont++;
        }
        //obtenemos la lista actualizada para mostrarlas en las cajas de texto
        $lista_original2 = $this->detalle_secciones_m->getListaOriginal($array_index);
        if (count($lista_original2) > 0) {
            $array_original2 = array();
            foreach ($lista_original2 as $index2 => $objDetalleSeccion2) {
                $array_original2[$objDetalleSeccion2->id] = $objDetalleSeccion2->peso;
            }
        }
        //obtener el último item
        $ultimo = $this->detalle_secciones_m->order_by('peso', 'DESC')->get_by(array("secciones_id" => $seccion_id, "estado" => "1"));
        //obtener el primer item
        $primero = $this->detalle_secciones_m->order_by('peso', 'ASC')->get_by(array("secciones_id" => $seccion_id, "estado" => "1"));
        echo json_encode(array("value" => "1", "orden" => $array_original2, "ultimo" => $ultimo->id, "primer" => $primero->id));
    }

    /**
     * metodo para llamar al archivo vista_previa.php para la vista previa
     */
    public function vista_previa() {
        $this->template
                ->set_layout('modal', 'admin')
                ->build('admin/vista_previa');
    }

    /**
     * metodo para bajar la posición el registro  de un item en el listado
     * @param int $detalle_seccion_id
     */
    public function bajar($detalle_seccion_id, $indexOrder) {
        if ($this->input->is_ajax_request()) {
            $objDetalleSeccionBajar = $this->detalle_secciones_m->get($detalle_seccion_id);
            $coleccionDetalleSeccion = $this->agregarValores($this->detalle_secciones_m->order_by('peso', 'ASC')->get_many_by(array("secciones_id" => $objDetalleSeccionBajar->secciones_id)));
            if (count($coleccionDetalleSeccion) > 0) {
                $pos_a = 0;
                $pos_b = 0;
                foreach ($coleccionDetalleSeccion as $index => $objDetalle) {
                    if ($objDetalleSeccionBajar->id == $objDetalle->id) {
                        $pos_a = $index;
                        break;
                    }
                }
                $pos_b = $pos_a + 1;
                $objDetalleSeccionASubir = $coleccionDetalleSeccion[$pos_b];
                $objDetalleSeccionBajar = $coleccionDetalleSeccion[$pos_a];
                $this->detalle_secciones_m->update($objDetalleSeccionBajar->id, array("peso" => $objDetalleSeccionASubir->peso));
                $this->detalle_secciones_m->update($objDetalleSeccionASubir->id, array("peso" => $objDetalleSeccionBajar->peso));

                //obtener el último item
                $ultimo = $this->detalle_secciones_m->order_by('peso', 'DESC')->get_by(array("secciones_id" => $objDetalleSeccionBajar->secciones_id));
                //obtener el primer item
                $primero = $this->detalle_secciones_m->order_by('peso', 'ASC')->get_by(array("secciones_id" => $objDetalleSeccionBajar->secciones_id));
                //armamos el json
                $arrayA = array("index" => ($indexOrder), "imagen" => $objDetalleSeccionASubir->imagen, "nombre" => $objDetalleSeccionASubir->nombre, "descripcion" => $objDetalleSeccionASubir->descripcion_item, "tipo" => $objDetalleSeccionASubir->tipo, "peso" => $objDetalleSeccionBajar->peso, "id" => $objDetalleSeccionASubir->id);
                $arrayB = array("index" => ($indexOrder + 1), "imagen" => $objDetalleSeccionBajar->imagen, "nombre" => $objDetalleSeccionBajar->nombre, "descripcion" => $objDetalleSeccionBajar->descripcion_item, "tipo" => $objDetalleSeccionBajar->tipo, "peso" => $objDetalleSeccionASubir->peso, "id" => $objDetalleSeccionBajar->id);
                echo json_encode(array("subir" => $arrayA, "bajar" => $arrayB, "primer" => $primero->id, "ultimo" => $ultimo->id));
            }
        }
    }

    /**
     * Método para subir una posicion a un item de una lista
     * @param type $detalle_seccion_id
     * @param type $indexOrder
     */
    public function subir($detalle_seccion_id, $indexOrder) {
        if ($this->input->is_ajax_request()) {
            $objDetalleSeccionSubir = $this->detalle_secciones_m->get($detalle_seccion_id);
            $coleccionDetalleSeccion = $this->agregarValores($this->detalle_secciones_m->order_by('peso', 'ASC')->get_many_by(array("secciones_id" => $objDetalleSeccionSubir->secciones_id)));
            if (count($coleccionDetalleSeccion) > 0) {
                $pos_a = 0;
                $pos_b = 0;
                foreach ($coleccionDetalleSeccion as $index => $objDetalle) {
                    if ($objDetalleSeccionSubir->id == $objDetalle->id) {
                        $pos_a = $index;
                        break;
                    }
                }
                $pos_b = $pos_a - 1;
                $objDetalleSeccion_a_bajar = $coleccionDetalleSeccion[$pos_b];
                $objDetalleSeccion_a_subir = $coleccionDetalleSeccion[$pos_a];
                $this->detalle_secciones_m->update($objDetalleSeccion_a_subir->id, array("peso" => $objDetalleSeccion_a_bajar->peso));
                $this->detalle_secciones_m->update($objDetalleSeccion_a_bajar->id, array("peso" => $objDetalleSeccion_a_subir->peso));

                //obtener el último item
                $ultimo = $this->detalle_secciones_m->order_by('peso', 'DESC')->get_by(array("secciones_id" => $objDetalleSeccion_a_subir->secciones_id));
                //obtener el primer item
                $primero = $this->detalle_secciones_m->order_by('peso', 'ASC')->get_by(array("secciones_id" => $objDetalleSeccion_a_subir->secciones_id));
                //armamos el json
                $arrayA = array("index" => ($indexOrder), "imagen" => $objDetalleSeccion_a_bajar->imagen, "nombre" => $objDetalleSeccion_a_bajar->nombre, "descripcion" => $objDetalleSeccion_a_bajar->descripcion_item, "tipo" => $objDetalleSeccion_a_bajar->tipo, "peso" => $objDetalleSeccion_a_subir->peso, "id" => $objDetalleSeccion_a_bajar->id);
                $arrayB = array("index" => ($indexOrder - 1), "imagen" => $objDetalleSeccion_a_subir->imagen, "nombre" => $objDetalleSeccion_a_subir->nombre, "descripcion" => $objDetalleSeccion_a_subir->descripcion_item, "tipo" => $objDetalleSeccion_a_subir->tipo, "peso" => $objDetalleSeccion_a_bajar->peso, "id" => $objDetalleSeccion_a_subir->id);
                echo json_encode(array("bajar" => $arrayA, "subir" => $arrayB, "primer" => $primero->id, "ultimo" => $ultimo->id));
            }
        }
    }

    /**
     * metodo para retornar el html del formulario de busqueda
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param string $path
     * @return string
     */
    public function formulario_busqueda($path) {
        if ($this->input->is_ajax_request()) {
            $arrayPath = explode("%2C", $path);
            //eliminamos los inidec vacios
            if (count($arrayPath) > 0) {
                $arrayUrl = array();
                foreach ($arrayPath as $index => $item) {
                    if (strlen(trim($item)) > 0) {
                        array_push($arrayUrl, $item);
                    }
                }
            }
            $html = '';
            if (count($arrayUrl) > 4) {
                //filtramos q tipo de formulario y datos debe cargar
                if ($arrayUrl[0] == 'admin' && $arrayUrl[1] == 'canales' && $arrayUrl[2] == 'seccion') {
                    if (isset($arrayUrl[4])) {
                        $objSeccion = $this->secciones_m->get($arrayUrl[4]);
                        $objPortada = $this->portada_m->get($objSeccion->portadas_id);
                        $objCanal = $this->canales_m->get($arrayUrl[3]);
                        //if ($objSeccion->tipo_secciones_id >= $this->config->item('seccion:destacado') || $objCanal->tipo_canales_id == $this->config->item('canal:mi_canal')) {
                        if ($objPortada->tipo_portadas_id == $this->config->item('portada:canal')) {
                            switch ($objSeccion->tipo_secciones_id):
                                case $this->config->item('seccion:destacado'):
                                    $link = 'buscar_para_destacado(1);';
                                    break;
                                case $this->config->item('seccion:programa'):
                                    $link = 'buscar_para_programa(1);';
                                    break;
                                case $this->config->item('seccion:coleccion'):
                                    $link = 'buscar_para_coleccion(1);';
                                    break;
                                case $this->config->item('seccion:lista'):
                                    $link = 'buscar_para_lista(1);';
                                    break;
                                case $this->config->item('seccion:video'):
                                    $link = 'buscar_para_video(1);';
                                    break;
                                case $this->config->item('seccion:visto'):
                                case $this->config->item('seccion:comentado'):
                                case $this->config->item('seccion:valorado'):
                                case $this->config->item('seccion:reciente'):
                                    $link = 'buscar_para_losmas(1);';
                                    break;
                                default: $link = 'buscar_para_destacado(1);';
                                    break;
                            endswitch;
                        }else {
                            if ($objPortada->tipo_portadas_id == $this->config->item('portada:programa')) {
                                switch ($objSeccion->tipo_secciones_id):
                                    case $this->config->item('seccion:destacado'):
                                        $link = 'buscar_para_destacado_programa(1);';
                                        break;
                                    case $this->config->item('seccion:coleccion'):
                                        $link = 'buscar_para_coleccion_programa(1);';
                                        break;
                                    case $this->config->item('seccion:lista'):
                                        $link = 'buscar_para_lista_programa(1);';
                                        break;
                                    case $this->config->item('seccion:video'):
                                        $link = 'buscar_para_video_programa(1);';
                                        break;
                                    case $this->config->item('seccion:visto'):
                                    case $this->config->item('seccion:comentado'):
                                    case $this->config->item('seccion:valorado'):
                                    case $this->config->item('seccion:reciente'):
                                        $link = 'buscar_para_losmas_programa(1);';
                                        break;
                                    default: $link = 'buscar_para_destacado(1);';
                                        break;
                                endswitch;
                            }else {
                                if ($objPortada->tipo_portadas_id == $this->config->item('portada:principal')) {
                                    switch ($objSeccion->tipo_secciones_id):
                                        case $this->config->item('seccion:destacado'):
                                            $link = 'buscar_para_destacado_micanal(1);';
                                            break;
                                        case $this->config->item('seccion:programa'):
                                            $link = 'buscar_para_programa_micanal(1);';
                                            break;
                                        default: $link = 'buscar_para_micanal(1);';
                                            break;
                                    endswitch;
                                }else {
                                    if ($objPortada->tipo_portadas_id == $this->config->item('portada:categoria')) {
                                        $link = 'buscar_para_destacado_categoria(1);';
                                    }
                                }
                            }
                        }
                        $tipo_buscar = array("0" => "Seleccione el tipo", $this->config->item('videos:programa') => "Programa", $this->config->item('videos:coleccion') => "Coleccion", $this->config->item('videos:lista') => "Lista", $this->config->item('videos:video') => "Video", $this->config->item('videos:canal') => "Canal");
                        $html.='<span class="view_mc">BUSQUEDA</span>                          	  
                         <div class="frm-input">
                            <form name="frmBuscar" id="frmBuscar" action="" method="POST">
                               <div style="float:left;">
                                   <input style="width:300px;" id="txtBuscar" name="txtBuscar" type="text" placeholder="Buscar">   
                               </div>
                               <div style="float:left; padding-top:0px;">
                                    <a href="#" onclick="' . $link . 'return false;" id="s" name="s" class="btn blue">
                                        <span class="st">Buscar</span>
                                    </a>
                               </div>
                               <div style="clear:both;"></div>
                               <div style="float:left;"><select name="tipo_buscar" id="tipo_buscar">';
                        foreach ($tipo_buscar as $puntero => $value) {
                            $html.='<option value="' . $puntero . '">' . $value . '</option>';
                        }
                        $html.='</select></div>
                               <div style="clear:both;"></div>
                               <div id="divResultado" style="background-color:#ffffff; -moz-opacity: 1 !important; opacity: 1 1 !important;"></div>
                               <input type="hidden" id="canal_id" name="canal_id" value="' . $arrayUrl[3] . '" />
                               <input type="hidden" id="seccion_id" name="seccion_id" value="' . $arrayUrl[4] . '" />
                            </form>
                    </div>';
                        //}
                    }
                } else {
                    if ($arrayUrl[0] == 'admin' && $arrayUrl[1] == 'videos' && $arrayUrl[2] == 'grupo_maestro') {
                        if (isset($arrayUrl[3]) && isset($arrayUrl[4])) {
                            $canal_id = $arrayUrl[3];
                            $maestro_id = $arrayUrl[4];
                            if ($maestro_id > 0) {
                                $objMaestro = $this->grupo_maestro_m->get($maestro_id);
                                if ($objMaestro->tipo_grupo_maestro_id == $this->config->item('videos:programa')) {
                                    $funcion = 'listar_para_programa(1)';
                                } else {
                                    if ($objMaestro->tipo_grupo_maestro_id == $this->config->item('videos:coleccion')) {
                                        $funcion = 'listar_para_coleccion(1)';
                                    } else {
                                        if ($objMaestro->tipo_grupo_maestro_id == $this->config->item('videos:lista')) {
                                            $funcion = 'listar_para_lista(1)';
                                        }
                                    }
                                }
                                $objCanal = $this->canales_m->get($canal_id);
                                //if ($objCanal->tipo_canales_id == $this->config->item('canal:mi_canal')) {
                                $html.='<span class="view_mc">BUSQUEDA</span>                          	  
                         <div class="frm-input">
                            <form name="frmBuscar" id="frmBuscar" action="" method="POST">
                               <div style="float:left;">
                                   <input style="width:300px;" id="txtBuscar" name="txtBuscar" type="text" placeholder="Buscar">   
                               </div>
                               <div style="float:left; padding-top:0px;">
                                    <a href="#" onclick="' . $funcion . ';return false;" id="s" name="s" class="btn blue">
                                        <span class="st">Buscar</span>
                                    </a>
                               </div>
                               <div style="clear:both;"></div>
                               <div id="divResultado" style="background-color:#ffffff; -moz-opacity: 1 !important; opacity: 1 1 !important;"></div>
                               <input type="hidden" id="canal_id" name="canal_id" value="' . $canal_id . '" />
                               <input type="hidden" id="maestro_id" name="maestro_id" value="' . $maestro_id . '" />
                            </form>
                    </div>';
                            }
                        }
                    }
                }
            }
            echo $html;
        }
    }

    /**
     * metodo para devolver el grid de resultado de la busqueda por la via secciones
     */
    public function buscar_seccion() {
        if ($this->input->is_ajax_request()) {
            //por ahora no filtraremos, pero solo buscaremos los items correspondientes
            $objSeccion = $this->secciones_m->get($this->input->post('seccion_id'));
            $objPortada = $this->portada_m->get($objSeccion->portadas_id);
            $htmlContenido = '';

            if ($objPortada->tipo_portadas_id == $this->config->item('portada:canal')) {
                if ($objSeccion->tipo_secciones_id == $this->config->item('seccion:programa') || $objSeccion->tipo_secciones_id == $this->config->item('seccion:coleccion') || $objSeccion->tipo_secciones_id == $this->config->item('seccion:lista')) {
                    $objColeccionGrupoMaestro = $this->obtenerMaestrosParaSecciones($objSeccion->tipo_secciones_id, $this->input->post('canal_id'));
                    if (count($objColeccionGrupoMaestro)) {
                        foreach ($objColeccionGrupoMaestro as $puntero => $objMaestro) {
                            if ($objMaestro->tipo_grupo_maestro_id == $this->config->item('videos:coleccion')) {
                                $objImagen = $this->imagen_m->get_by(array("grupo_maestros_id" => $objMaestro->id, "tipo_imagen_id" => $this->config->item('imagen:large'), "estado" => "1"));
                            } else {
                                $objImagen = $this->imagen_m->get_by(array("grupo_maestros_id" => $objMaestro->id, "tipo_imagen_id" => $this->config->item('imagen:small'), "estado" => "1"));
                            }
                            if (count($objImagen) > 0) {
                                if ($objImagen->procedencia == '0') {
                                    $imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagen->imagen;
                                } else {
                                    $imagen = $objImagen->imagen;
                                }
                            } else {
                                //$imagen = UPLOAD_IMAGENES_VIDEOS . 'no_video.jpg';
                                $imagen = $this->config->item('url:default_imagen') . 'no_video.jpg';
                            }
                            $htmlContenido.='<tr>';
                            $htmlContenido.='<td>' . ($puntero + 1) . '</td>';
                            $htmlContenido.='<td><img src="' . $imagen . '" style="width:100px;" title="' . $objMaestro->nombre . '" /></td>';
                            $htmlContenido.='<td>' . $objMaestro->nombre . '</td>';
                            if ($this->existeRegistro($objMaestro->id, $this->input->post('seccion_id'))) {
                                $htmlContenido.='<td><a href="#" id="agregado" name="agregado" class="btn silver" onclick="return false;">Agregado</a></td>';
                            } else {
                                $htmlContenido.='<td><div id="div_' . $objMaestro->id . '"><a href="#" onclick="agregarItemMaestro(' . $objMaestro->id . ', ' . $this->input->post('seccion_id') . '); return false;" id="btnAgregar" name="btnAgregar" class="btn green">Agregar</a></div></td>';
                            }
                            $htmlContenido.='</tr>';
                        }
                    } else {
                        $htmlContenido.='<tr>';
                        $htmlContenido.='<td colspan="4">No se encontro elementos</td>';
                        $htmlContenido.='<tr>';
                    }
                } else { //portadas que no son de tipo canal
                    if ($objSeccion->tipo_secciones_id == $this->config->item('seccion:video')) {
                        $objColeccionVideo = $this->obtenerVideosCanal($this->input->post('canal_id'));
                        if (count($objColeccionVideo)) {
                            foreach ($objColeccionVideo as $puntero => $objVideo) {
                                $objImagen = $this->imagen_m->get_by(array("videos_id" => $objVideo->id, "tipo_imagen_id" => $this->config->item('imagen:small'), "estado" => "1"));
                                if (count($objImagen) > 0) {
                                    if ($objImagen->procedencia == '0') {
                                        $imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagen->imagen;
                                    } else {
                                        $imagen = $objImagen->imagen;
                                    }
                                } else {
                                    //$imagen = UPLOAD_IMAGENES_VIDEOS . 'no_video.jpg';
                                    $imagen = $this->config->item('url:default_imagen') . 'no_video.jpg';
                                }
                                $htmlContenido.='<tr>';
                                $htmlContenido.='<td>' . ($puntero + 1) . '</td>';
                                $htmlContenido.='<td><img src="' . $imagen . '" style="width:100px;" title="' . $objVideo->titulo . '" /></td>';
                                $htmlContenido.='<td>' . $objVideo->titulo . '</td>';
                                if ($this->existeRegistroVideo($objVideo->id, $this->input->post('seccion_id'))) {
                                    $htmlContenido.='<td><a href="#" id="agregado" name="agregado" class="btn silver" onclick="return false;">Agregado</a></td>';
                                } else {
                                    $htmlContenido.='<td><div id="div_' . $objVideo->id . '"><a href="#" onclick="agregarItemVideo(' . $objVideo->id . ', ' . $this->input->post('seccion_id') . '); return false;" id="btnAgregar" name="btnAgregar" class="btn green">Agregar</a></div></td>';
                                }
                                $htmlContenido.='</tr>';
                            }
                        } else {
                            $htmlContenido.='<tr>';
                            $htmlContenido.='<td colspan="4">No se encontro elementos</td>';
                            $htmlContenido.='<tr>';
                        }
                    } else {
                        //busqueda general cuando se lista x el canal, solo se excluirán los de tipo coleccion
                        $keyword = $this->input->post('txtBuscar');
                        $base_where = array("canales_id" => $this->input->post('canal_id'));
                        $cantidad_mostrar = 7;
                        $current_page = 0;
                        if (strlen(trim($keyword)) > 0) {
                            $total_rows = $this->grupo_maestro_m->like('nombre', $keyword)->count_by($base_where);
                            $items = $this->grupo_maestro_m->like('nombre', $keyword)->limit(array($cantidad_mostrar, $current_page))->get_many_by($base_where);
                            $htmlContenido = $this->listaHtml($items);
                            $htmlContenido.='<input type="hidden" id="total" name="total" value="' . $total_rows . '" />';
                            $htmlContenido.='<input type="hidden" id="text_search" name="text_search" value="' . $keyword . '" />';
                            $htmlContenido.='<input type="hidden" id="tipo_portada" name="tipo_portada" value="' . $objPortada->tipo_portadas_id . '" />';
                        } else {
                            $total_rows = $this->grupo_maestro_m->count_by($base_where);
                            $items = $this->grupo_maestro_m->limit(array($cantidad_mostrar, $current_page))->get_many_by($base_where);
                            $htmlContenido = $this->listaHtml($items);
                            //$total = ceil($total_rows/$cantidad_mostrar);
                            $htmlContenido.='<input type="hidden" id="total" name="total" value="' . $total_rows . '" />';
                            $htmlContenido.='<input type="hidden" id="text_search" name="text_search" value="' . $keyword . '" />';
                            $htmlContenido.='<input type="hidden" id="tipo_portada" name="tipo_portada" value="' . $objPortada->tipo_portadas_id . '" />';
                        }
                    }
                }
            } else {
                $objMaestro = $this->grupo_maestro_m->get($objPortada->origen_id);
                if ($objSeccion->tipo_secciones_id == $this->config->item('seccion:coleccion') || $objSeccion->tipo_secciones_id == $this->config->item('seccion:lista')) {
                    $objColeccionGrupoMaestro = $this->obtenerMaestrosParaSecciones($objSeccion->tipo_secciones_id, $this->input->post('canal_id'), $objMaestro);
                    if (count($objColeccionGrupoMaestro)) {
                        foreach ($objColeccionGrupoMaestro as $puntero => $objMaestro) {
                            if ($objMaestro->tipo_grupo_maestro_id == $this->config->item('videos:coleccion')) {
                                $objImagen = $this->imagen_m->get_by(array("grupo_maestros_id" => $objMaestro->id, "tipo_imagen_id" => $this->config->item('imagen:large'), "estado" => "1"));
                            } else {
                                $objImagen = $this->imagen_m->get_by(array("grupo_maestros_id" => $objMaestro->id, "tipo_imagen_id" => $this->config->item('imagen:small'), "estado" => "1"));
                            }
                            if (count($objImagen) > 0) {
                                if ($objImagen->procedencia == '0') {
                                    $imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagen->imagen;
                                } else {
                                    $imagen = $objImagen->imagen;
                                }
                            } else {
                                //$imagen = UPLOAD_IMAGENES_VIDEOS . 'no_video.jpg';
                                $imagen = $this->config->item('url:default_imagen') . 'no_video.jpg';
                            }
                            $htmlContenido.='<tr>';
                            $htmlContenido.='<td>' . ($puntero + 1) . '</td>';
                            $htmlContenido.='<td><img src="' . $imagen . '" style="width:100px;" title="' . $objMaestro->nombre . '" /></td>';
                            $htmlContenido.='<td>' . $objMaestro->nombre . '</td>';
                            if ($this->existeRegistro($objMaestro->id, $this->input->post('seccion_id'))) {
                                $htmlContenido.='<td><a href="#" id="agregado" name="agregado" class="btn silver" onclick="return false;">Agregado</a></td>';
                            } else {
                                $htmlContenido.='<td><div id="div_' . $objMaestro->id . '"><a href="#" onclick="agregarItemMaestro(' . $objMaestro->id . ', ' . $this->input->post('seccion_id') . '); return false;" id="btnAgregar" name="btnAgregar" class="btn green">Agregar</a></div></td>';
                            }
                            $htmlContenido.='</tr>';
                        }
                    } else {
                        $htmlContenido.='<tr>';
                        $htmlContenido.='<td colspan="4">No se encontro elementos</td>';
                        $htmlContenido.='<tr>';
                    }
                } else {
                    //busqueda general cuando se lista x el canal, solo se excluirán los de tipo coleccion
                    $keyword = $this->input->post('txtBuscar');
                    if ($objPortada->tipo_portadas_id == $this->config->item('portada:principal')) {
                        $base_where = array();
                    } else {
                        $base_where = array("canales_id" => $this->input->post('canal_id'));
                    }
                    $cantidad_mostrar = 7;
                    $current_page = 0;
                    if (strlen(trim($keyword)) > 0) {
                        $total_rows = $this->grupo_maestro_m->like('nombre', $keyword)->count_by($base_where);
                        $items = $this->grupo_maestro_m->like('nombre', $keyword)->limit(array($cantidad_mostrar, $current_page))->get_many_by($base_where);
                        $htmlContenido = $this->listaHtml($items);
                        $htmlContenido.='<input type="hidden" id="total" name="total" value="' . $total_rows . '" />';
                        $htmlContenido.='<input type="hidden" id="text_search" name="text_search" value="' . $keyword . '" />';
                        $htmlContenido.='<input type="hidden" id="tipo_portada" name="tipo_portada" value="' . $objPortada->tipo_portadas_id . '" />';
                    } else {
                        $total_rows = $this->grupo_maestro_m->count_by($base_where);
                        $items = $this->grupo_maestro_m->limit(array($cantidad_mostrar, $current_page))->get_many_by($base_where);
                        $htmlContenido = $this->listaHtml($items);
                        //$total = ceil($total_rows/$cantidad_mostrar);
                        $htmlContenido.='<input type="hidden" id="total" name="total" value="' . $total_rows . '" />';
                        $htmlContenido.='<input type="hidden" id="text_search" name="text_search" value="' . $keyword . '" />';
                        $htmlContenido.='<input type="hidden" id="tipo_portada" name="tipo_portada" value="' . $objPortada->tipo_portadas_id . '" />';
                    }
                }
            }
            //creamos la lista para mostrarlo con ajax
            $html = '<table>';
            $html.='<thead>';
            $html.='<tr>';
            $html.='<th>#</th>';
            $html.='<th>imagen</td>';
            $html.='<th>nombre</td>';
            $html.='<th>acciones</td>';
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody id="resultado">';
            $html.=$htmlContenido;
            $html.='</tbody>';
            $html.='</table>';
            $html.='<div id="black" style="margin: auto;"></div>';
            echo $html;
        }
    }

    private function buscar_maestro_video($keyword) {
        
    }

    /**
     * Método para listar en HTML los items devueltos por el paginador de busqueda
     * @param arrayObject $items
     * @return html
     */
    public function listaHtml($items, $indice = 0) {
        $returnValue = '';
        if (count($items) > 0) {
            foreach ($items as $puntero => $objMaestro) {
                //verificamos si es solo video o es de tipo maestro
                if ($objMaestro->tipo_grupo_maestro_id == $this->config->item('videos:coleccion')) {
                    $objImagen = $this->imagen_m->get_by(array("grupo_maestros_id" => $objMaestro->id, "tipo_imagen_id" => $this->config->item('imagen:large'), "estado" => "1"));
                } else {
                    $objImagen = $this->imagen_m->get_by(array("grupo_maestros_id" => $objMaestro->id, "tipo_imagen_id" => $this->config->item('imagen:small'), "estado" => "1"));
                }
                if (count($objImagen) > 0) {
                    if ($objImagen->procedencia == '0') {
                        $imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagen->imagen;
                    } else {
                        $imagen = $objImagen->imagen;
                    }
                } else {
                    //$imagen = UPLOAD_IMAGENES_VIDEOS . 'no_video.jpg';
                    $imagen = $this->config->item('url:default_imagen') . 'no_video.jpg';
                }
                $returnValue.='<tr>';
                $returnValue.='<td>' . ($indice + 1) . '</td>';
                $returnValue.='<td><img src="' . $imagen . '" style="width:100px; height:55px;" title="' . $objMaestro->nombre . '" /></td>';
                $returnValue.='<td>' . $objMaestro->nombre . '</td>';
                if ($this->existeRegistro($objMaestro->id, $this->input->post('seccion_id'))) {
                    $returnValue.='<td><a href="#" id="agregado" name="agregado" class="btn silver" onclick="return false;">Agregado</a></td>';
                } else {
                    $returnValue.='<td><div id="div_' . $objMaestro->id . '"><a href="#" onclick="agregarItem(' . $objMaestro->id . ', ' . $this->input->post('seccion_id') . '); return false;" id="btnAgregar" name="btnAgregar" class="btn green">Agregar</a></div></td>';
                }
                $returnValue.='</tr>';
                $indice++;
            }
        }
        return $returnValue;
    }

    /**
     * metodo para obtener la lista de una pagina
     * @param int $pagina
     */
    public function obtener_lista_paginado($pagina) {
        $keyword = $this->input->post('text_search');
        if ($this->input->post('tipo_portada') == $this->config->item('portada:principal')) {
            $base_where = array();
        } else {
            $base_where = array("canales_id" => $this->input->post('canal_id'));
        }
        $cantidad_mostrar = 7;
        $current_page = ($cantidad_mostrar * $pagina) - $cantidad_mostrar;
        $htmlContenido = '';
        if (strlen(trim($keyword)) > 0) {
            $total_rows = $this->grupo_maestro_m->like('nombre', $keyword)->count_by($base_where);
            $items = $this->grupo_maestro_m->like('nombre', $keyword)->limit(array($cantidad_mostrar, $current_page))->get_many_by($base_where);
            $htmlContenido.= $this->listaHtml($items, $current_page);
            $htmlContenido.='<input type="hidden" id="total" name="total" value="' . $total_rows . '" />';
            $htmlContenido.='<input type="hidden" id="text_search" name="text_search" value="' . $keyword . '" />';
            $htmlContenido.='<input type="hidden" id="tipo_portada" name="tipo_portada" value="' . $this->input->post('tipo_portada') . '" />';
        } else {
            $total_rows = $this->grupo_maestro_m->count_by($base_where);
            $items = $this->grupo_maestro_m->limit(array($cantidad_mostrar, $current_page))->get_many_by($base_where);
            $htmlContenido = $this->listaHtml($items, $current_page);
            //$total = ceil($total_rows/$cantidad_mostrar);
            $htmlContenido.='<input type="hidden" id="total" name="total" value="' . $total_rows . '" />';
            $htmlContenido.='<input type="hidden" id="text_search" name="text_search" value="' . $keyword . '" />';
            $htmlContenido.='<input type="hidden" id="tipo_portada" name="tipo_portada" value="' . $this->input->post('tipo_portada') . '" />';
        }
        echo $htmlContenido;
    }

    /**
     * meotodo para verificar si existe un registro del maestro en el detalle de la seccion
     * @param int $maestro_id
     * @param int $seccion_id
     * @return boolean
     */
    public function existeRegistro($maestro_id, $seccion_id, $estado = 1) {
        $returnValue = false;
        $listaDetalleSeccion = $this->detalle_secciones_m->get_many_by(array("secciones_id" => $seccion_id, "grupo_maestros_id" => $maestro_id, "estado" => $estado));
        if (count($listaDetalleSeccion) > 0) {
            $returnValue = true;
        }
        return $returnValue;
    }

    public function existeRegistroVideo($video_id, $seccion_id, $estado = 1) {
        $returnValue = false;
        $listaDetalleSeccion = $this->detalle_secciones_m->get_many_by(array("secciones_id" => $seccion_id, "videos_id" => $video_id, "estado" => $estado));
        if (count($listaDetalleSeccion) > 0) {
            $returnValue = true;
        }
        return $returnValue;
    }

    public function quitar_detalle_seccion($detalle_seccion_id) {
        if ($this->input->is_ajax_request()) {
            $objDetalleSeccion = $this->detalle_secciones_m->get($detalle_seccion_id);
            $objSeccion = $this->secciones_m->get($objDetalleSeccion->secciones_id);
            $objPortada = $this->portada_m->get($objSeccion->portadas_id);
            if ($objPortada->tipo_portadas_id == $this->config->item('portada:principal') && $objSeccion->tipo_secciones_id == $this->config->item('seccion:programa')) {
                if ($objDetalleSeccion->grupo_maestros_id != NULL) {
                    $objMaestro = $this->grupo_maestro_m->get($objDetalleSeccion->grupo_maestros_id);
                    if ($objMaestro->tipo_grupo_maestro_id == $this->config->item('videos:programa')) {
                        $this->detalle_secciones_m->delete_by("secciones_id", $objDetalleSeccion->secciones_id);
                    } else {
                        $this->detalle_secciones_m->update($detalle_seccion_id, array("estado" => "0"));
                    }
                }
            } else {
                $this->detalle_secciones_m->update($detalle_seccion_id, array("estado" => "0"));
            }
            echo json_encode(array("value" => 1));
        }
    }

    /**
     * metodo para actualizar la informacio de la cabecera de secciones
     */
    public function actualizar_seccion() {
        if ($this->input->is_ajax_request()) {
            $user_id = (int) $this->session->userdata('user_id');
            $this->secciones_m->update($this->input->post('seccion_id'), array('estado_migracion' => $this->config->item('migracion:actualizado'), 'nombre' => $this->input->post('nombre'), 'descripcion' => $this->input->post('descripcion'), 'templates_id' => $this->input->post('template'), 'fecha_actualizacion' => date("Y-m-d H:i:s"), 'usuario_actualizacion' => $user_id));
            echo json_encode(array("value" => "1"));
        }
    }

    /**
     * metodo para validar y agregar items al detalle de una seccion
     */
    public function agregar_item_maestro() {
        if ($this->input->is_ajax_request()) {
            $user_id = (int) $this->session->userdata('user_id');
            $returnValue = 0;
            $detalle_seccion_id = 0;
            $objMaestro = $this->grupo_maestro_m->get($this->input->post('maestro_id'));
            if ($objMaestro->tipo_grupo_maestro_id == $this->config->item('videos:coleccion')) {
                if ($this->coleccionTieneSeccion($this->input->post('maestro_id'), $this->input->post('seccion_id'))) {
                    $returnValue = 4; //la collecion seleccionada tiene una seccion ya registrada.
                } else {
                    //verificamos si la coleccion tiene imagen
                    $objImagen = $this->imagen_m->get_by(array("grupo_maestros_id" => $this->input->post('maestro_id'), "tipo_imagen_id" => $this->config->item('imagen:large')));
                    if (count($objImagen) > 0) {
                        //verificamos que tenga listas
                        $listas = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $objMaestro->id, "tipo_grupo_maestros_id" => $this->config->item('videos:coleccion')));
                        if (count($listas) > 0) {
                            //verificamos que al menos una lista tenga imagen
                            $cont_img = 0;
                            $array_lista = array();
                            foreach ($listas as $puntero => $objGrupoDetalle) {
                                $objMaestroLista = $this->grupo_maestro_m->get($objGrupoDetalle->grupo_maestro_id);
                                $objImagenLista = $this->imagen_m->get_by(array("grupo_maestros_id" => $objMaestroLista->id, "tipo_imagen_id" => $this->config->item('imagen:small')));
                                if (count($objImagenLista) > 0) {
                                    array_push($array_lista, $objMaestroLista);
                                    $cont_img++;
                                }
                            }
                            if ($cont_img > 0) {
                                $cont_detalle_seccion = 1;
                                //limpiamos la tabla  del detalla de la seccion
                                $this->detalle_secciones_m->delete_by('secciones_id', $this->input->post('seccion_id'));
                                //registramos el detalle de la seccion, primero la coleccion como peso 1 y despues las listas
                                $objBeanDetalleSecciones = new stdClass();
                                $objBeanDetalleSecciones->id = NULL;
                                $objBeanDetalleSecciones->secciones_id = $this->input->post('seccion_id');
                                $objBeanDetalleSecciones->reglas_id = NULL;
                                $objBeanDetalleSecciones->videos_id = NULL;
                                $objBeanDetalleSecciones->grupo_maestros_id = $this->input->post('maestro_id');
                                $objBeanDetalleSecciones->categorias_id = NULL;
                                $objBeanDetalleSecciones->tags_id = NULL;
                                $objBeanDetalleSecciones->imagenes_id = $objImagen->id;
                                $objBeanDetalleSecciones->peso = $cont_detalle_seccion;
                                $objBeanDetalleSecciones->descripcion_item = '';
                                //$objBeanDetalleSecciones->templates_id = $this->config->item('template:programa');
                                $objBeanDetalleSecciones->estado = 1;
                                $objBeanDetalleSecciones->fecha_registro = date("Y-m-d H:i:s");
                                $objBeanDetalleSecciones->usuario_registro = $user_id;
                                $objBeanDetalleSecciones->estado_migracion = '0';
                                $objBeanDetalleSecciones->fecha_migracion = '0000-00-00 00:00:00';
                                $objBeanDetalleSecciones->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                                $objBeanDetalleSeccionesSaved = $this->detalle_secciones_m->save($objBeanDetalleSecciones);
                                //seguidamente  registramos las listas
                                foreach ($array_lista as $index => $objLista) {
                                    $objImagenLista = $this->imagen_m->get_by(array("grupo_maestros_id" => $objLista->id, "tipo_imagen_id" => $this->config->item('imagen:small')));
                                    $objBeanDetalleSecciones = new stdClass();
                                    $objBeanDetalleSecciones->id = NULL;
                                    $objBeanDetalleSecciones->secciones_id = $this->input->post('seccion_id');
                                    $objBeanDetalleSecciones->reglas_id = NULL;
                                    $objBeanDetalleSecciones->videos_id = NULL;
                                    $objBeanDetalleSecciones->grupo_maestros_id = $objLista->id;
                                    $objBeanDetalleSecciones->categorias_id = NULL;
                                    $objBeanDetalleSecciones->tags_id = NULL;
                                    $objBeanDetalleSecciones->imagenes_id = $objImagenLista->id;
                                    $objBeanDetalleSecciones->peso = $cont_detalle_seccion + 1;
                                    $objBeanDetalleSecciones->descripcion_item = '';
                                    //$objBeanDetalleSecciones->templates_id = $this->config->item('template:programa');
                                    $objBeanDetalleSecciones->estado = 1;
                                    $objBeanDetalleSecciones->fecha_registro = date("Y-m-d H:i:s");
                                    $objBeanDetalleSecciones->usuario_registro = $user_id;
                                    $objBeanDetalleSecciones->estado_migracion = '0';
                                    $objBeanDetalleSecciones->fecha_migracion = '0000-00-00 00:00:00';
                                    $objBeanDetalleSecciones->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                                    $objBeanDetalleSeccionesSaved = $this->detalle_secciones_m->save($objBeanDetalleSecciones);
                                    $cont_detalle_seccion++;
                                }
                            } else {
                                $returnValue = 7; // la lista de esta coleccion no tienen imagenes
                            }
                        } else {
                            $returnValue = 6; // not tiene listas registradas
                        }
                    } else {
                        $returnValue = 5; // no tiene imagen de tipo large
                    }
                }
            } else {
                if ($this->existeRegistro($this->input->post('maestro_id'), $this->input->post('seccion_id'), 0)) {
                    $objDetalleSeccion = $this->detalle_secciones_m->get_by(array("secciones_id" => $this->input->post('seccion_id'), "grupo_maestros_id" => $this->input->post('maestro_id')));
                    if (count($objDetalleSeccion) > 0) {
                        $this->detalle_secciones_m->update($objDetalleSeccion->id, array("estado" => "1"));
                    } else {//no hay items que agregar 
                        $returnValue = 1;
                    }
                } else { //registrar nuevo item en la tabla detalle secciones
                    $objImagen = $this->imagen_m->get_by(array("grupo_maestros_id" => $this->input->post('maestro_id'), "tipo_imagen_id" => $this->config->item('imagen:small')));
                    if (count($objImagen) > 0) {
                        $objBeanDetalleSecciones = new stdClass();
                        $objBeanDetalleSecciones->id = NULL;
                        $objBeanDetalleSecciones->secciones_id = $this->input->post('seccion_id');
                        $objBeanDetalleSecciones->reglas_id = NULL;
                        $objBeanDetalleSecciones->videos_id = NULL;
                        $objBeanDetalleSecciones->grupo_maestros_id = $this->input->post('maestro_id');
                        $objBeanDetalleSecciones->categorias_id = NULL;
                        $objBeanDetalleSecciones->tags_id = NULL;
                        $objBeanDetalleSecciones->imagenes_id = $objImagen->id;
                        $objBeanDetalleSecciones->peso = $this->obtenerPeso($this->input->post('seccion_id'));
                        $objBeanDetalleSecciones->descripcion_item = '';
                        //$objBeanDetalleSecciones->templates_id = $this->config->item('template:programa');
                        $objBeanDetalleSecciones->estado = 1;
                        $objBeanDetalleSecciones->fecha_registro = date("Y-m-d H:i:s");
                        $objBeanDetalleSecciones->usuario_registro = $user_id;
                        $objBeanDetalleSecciones->estado_migracion = '0';
                        $objBeanDetalleSecciones->fecha_migracion = '0000-00-00 00:00:00';
                        $objBeanDetalleSecciones->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                        $objBeanDetalleSeccionesSaved = $this->detalle_secciones_m->save($objBeanDetalleSecciones);
                        $detalle_seccion_id = $objBeanDetalleSeccionesSaved->id;
                    } else {
                        $returnValue = 2; // no tiene imagen de tipo small
                    }
                }
            }
            echo json_encode(array("error" => $returnValue, "detalle_seccion_id" => $detalle_seccion_id));
        }
    }

    /**
     * Método que retorna si esta coleccion ya tiene un seccion generada
     * @param int $coleccion_id
     * @param int $seccion_id
     * @return boolean
     */
    public function coleccionTieneSeccion($coleccion_id, $seccion_id) {
        $returnValue = false;
        if ($coleccion_id > 0) {
            $listaMaestros = $this->detalle_secciones_m->get_many_by(array("grupo_maestros_id" => $coleccion_id));
            if (count($listaMaestros) > 0) {
                foreach ($listaMaestros as $index => $objDetalleSeccion) {
                    if ($objDetalleSeccion->secciones_id != $seccion_id) {
                        $returnValue = true;
                    }
                }
            }
        }
        return $returnValue;
    }

    /**
     * Método para obtener los primeros pesos disponibles
     * @param int $seccion_id
     * @return int
     */
    public function obtenerPeso($seccion_id) {
        $returnValue = 1;
        $lista_detalles = $this->detalle_secciones_m->order_by('peso', 'ASC')->get_many_by(array("secciones_id" => $seccion_id));
        if (count($lista_detalles) > 0) {
            $peso = 2;
            foreach ($lista_detalles as $puntero => $objDetalle) {
                $this->detalle_secciones_m->update($objDetalle->id, array("peso" => $peso));
                $peso++;
            }
        }
        return $returnValue;
    }

    /**
     * Método para agregar items de tipo video al detalle de una sección
     */
    public function agregar_item_video() {
        if ($this->input->is_ajax_request()) {
            $user_id = (int) $this->session->userdata('user_id');
            $returnValue = 0;
            $detalle_seccion_id = 0;
            $objVideo = $this->videos_m->get($this->input->post('video_id'));
            if ($objVideo->estado != $this->config->item('status:codificando')) {
                if ($this->existeRegistroVideo($this->input->post('video_id'), $this->input->post('seccion_id'), 0)) {
                    $objDetalleSeccion = $this->detalle_secciones_m->get_by(array("secciones_id" => $this->input->post('seccion_id'), "videos_id" => $this->input->post('video_id')));
                    if (count($objDetalleSeccion) > 0) {
                        $this->detalle_secciones_m->update($objDetalleSeccion->id, array("estado" => "1"));
                    } else {//no hay items que agregar 
                        $returnValue = 1;
                    }
                } else {
                    $objImagen = $this->imagen_m->get_by(array("videos_id" => $this->input->post('video_id'), "tipo_imagen_id" => $this->config->item('imagen:small')));
                    if (count($objImagen) > 0) {
                        $objBeanDetalleSecciones = new stdClass();
                        $objBeanDetalleSecciones->id = NULL;
                        $objBeanDetalleSecciones->secciones_id = $this->input->post('seccion_id');
                        $objBeanDetalleSecciones->reglas_id = NULL;
                        $objBeanDetalleSecciones->videos_id = $this->input->post('video_id');
                        $objBeanDetalleSecciones->grupo_maestros_id = NULL;
                        $objBeanDetalleSecciones->categorias_id = NULL;
                        $objBeanDetalleSecciones->tags_id = NULL;
                        $objBeanDetalleSecciones->imagenes_id = $objImagen->id;
                        $objBeanDetalleSecciones->peso = $this->obtenerPeso($this->input->post('seccion_id'));
                        $objBeanDetalleSecciones->descripcion_item = '';
                        //$objBeanDetalleSecciones->templates_id = $this->config->item('template:video');
                        $objBeanDetalleSecciones->estado = 1;
                        $objBeanDetalleSecciones->fecha_registro = date("Y-m-d H:i:s");
                        $objBeanDetalleSecciones->usuario_registro = $user_id;
                        $objBeanDetalleSecciones->estado_migracion = '0';
                        $objBeanDetalleSecciones->fecha_migracion = '0000-00-00 00:00:00';
                        $objBeanDetalleSecciones->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                        $objBeanDetalleSeccionesSaved = $this->detalle_secciones_m->save($objBeanDetalleSecciones);
                        $detalle_seccion_id = $objBeanDetalleSeccionesSaved->id;
                    } else {
                        $returnValue = 2; // no tiene imagen de tipo small
                    }
                }
            } else {
                $returnValue = 3; //el video esta en un estado codificando
            }
            echo json_encode(array("error" => $returnValue, "detalle_seccion_id" => $detalle_seccion_id));
        }
    }

    public function agregar_item() {
        if ($this->input->is_ajax_request()) {
            $user_id = (int) $this->session->userdata('user_id');
            $returnValue = 0;
            $detalle_seccion_id = 0;
            //$objMaestro = $this->grupo_maestro_m->get($this->input->post('maestro_id'));
            $objSeccion = $this->secciones_m->get($this->input->post('seccion_id'));
            //$objPortada = $this->portada_m->get($objSeccion->portadas_id);
            switch ($objSeccion->templates_id) {
                case $this->config->item('template:destacado'):
                case $this->config->item('template:destacado_canal'):
                    if ($this->existeRegistro($this->input->post('maestro_id'), $this->input->post('seccion_id'), 0)) {
                        $objDetalleSeccion = $this->detalle_secciones_m->get_by(array("secciones_id" => $this->input->post('seccion_id'), "grupo_maestros_id" => $this->input->post('maestro_id')));
                        if (count($objDetalleSeccion) > 0) {
                            $objImagen = $this->imagen_m->get_by(array("grupo_maestros_id" => $this->input->post('maestro_id'), "tipo_imagen_id" => $this->config->item('imagen:extralarge')));
                            if (count($objImagen) > 0) {
                                $this->detalle_secciones_m->update($objDetalleSeccion->id, array("estado" => "1", "imagenes_id" => $objImagen->id));
                            } else {
                                $this->detalle_secciones_m->update($objDetalleSeccion->id, array("estado" => "1"));
                            }
                        } else {
                            $returnValue = 1; //no hay items que agregar
                        }
                    } else { //registrar nuevo item en la tabla detalle secciones
                        $objImagen = $this->imagen_m->get_by(array("grupo_maestros_id" => $this->input->post('maestro_id'), "tipo_imagen_id" => $this->config->item('imagen:extralarge')));
                        if (count($objImagen) > 0) {
                            $objBeanDetalleSecciones = new stdClass();
                            $objBeanDetalleSecciones->id = NULL;
                            $objBeanDetalleSecciones->secciones_id = $this->input->post('seccion_id');
                            $objBeanDetalleSecciones->reglas_id = NULL;
                            $objBeanDetalleSecciones->videos_id = NULL;
                            $objBeanDetalleSecciones->grupo_maestros_id = $this->input->post('maestro_id');
                            $objBeanDetalleSecciones->categorias_id = NULL;
                            $objBeanDetalleSecciones->tags_id = NULL;
                            $objBeanDetalleSecciones->imagenes_id = $objImagen->id;
                            $objBeanDetalleSecciones->peso = $this->obtenerPeso($this->input->post('seccion_id'));
                            $objBeanDetalleSecciones->descripcion_item = '';
                            //$objBeanDetalleSecciones->templates_id = $this->config->item('template:programa');
                            $objBeanDetalleSecciones->estado = 1;
                            $objBeanDetalleSecciones->fecha_registro = date("Y-m-d H:i:s");
                            $objBeanDetalleSecciones->usuario_registro = $user_id;
                            $objBeanDetalleSecciones->estado_migracion = '0';
                            $objBeanDetalleSecciones->fecha_migracion = '0000-00-00 00:00:00';
                            $objBeanDetalleSecciones->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                            $objBeanDetalleSeccionesSaved = $this->detalle_secciones_m->save($objBeanDetalleSecciones);
                            $detalle_seccion_id = $objBeanDetalleSeccionesSaved->id;
                        } else {
                            $returnValue = 5; // no tiene imagen extralarge
                        }
                    }
                    break;
            }
            /* if ($objMaestro->tipo_grupo_maestro_id == $this->config->item('videos:coleccion')) {
              $returnValue = 8;
              } else {
              $objSeccion = $this->secciones_m->get($this->input->post('seccion_id'));
              $objPortada = $this->portada_m->get($objSeccion->portadas_id);
              if ($this->existeRegistro($this->input->post('maestro_id'), $this->input->post('seccion_id'), 0)) {
              $objDetalleSeccion = $this->detalle_secciones_m->get_by(array("secciones_id" => $this->input->post('seccion_id'), "grupo_maestros_id" => $this->input->post('maestro_id')));
              if (count($objDetalleSeccion) > 0) {
              if ($objPortada->tipo_portadas_id == $this->config->item('portada:principal')) { //home de micanal
              $objImagen = $this->imagen_m->get_by(array("grupo_maestros_id" => $this->input->post('maestro_id'), "tipo_imagen_id" => $this->config->item('imagen:extralarge')));
              } else {
              if ($objSeccion->templates_id == $this->config->item('template:destacado') || $objSeccion->templates_id == $this->config->item('template:destacado_canal')) {
              $objImagen = $this->imagen_m->get_by(array("grupo_maestros_id" => $this->input->post('maestro_id'), "tipo_imagen_id" => $this->config->item('imagen:extralarge')));
              } else {
              $objImagen = $this->imagen_m->get_by(array("grupo_maestros_id" => $this->input->post('maestro_id'), "tipo_imagen_id" => $this->config->item('imagen:small')));
              }
              }
              if (count($objImagen) > 0) {
              $this->detalle_secciones_m->update($objDetalleSeccion->id, array("estado" => "1", "imagenes_id" => $objImagen->id));
              } else {
              $this->detalle_secciones_m->update($objDetalleSeccion->id, array("estado" => "1"));
              }
              } else {//no hay items que agregar
              $returnValue = 1;
              }
              } else { //registrar nuevo item en la tabla detalle secciones
              if ($objPortada->tipo_portadas_id == $this->config->item('portada:principal')) {
              $objImagen = $this->imagen_m->get_by(array("grupo_maestros_id" => $this->input->post('maestro_id'), "tipo_imagen_id" => $this->config->item('imagen:extralarge')));
              } else {
              if ($objSeccion->templates_id == $this->config->item('template:destacado') || $objSeccion->templates_id == $this->config->item('template:destacado_canal')) {
              $objImagen = $this->imagen_m->get_by(array("grupo_maestros_id" => $this->input->post('maestro_id'), "tipo_imagen_id" => $this->config->item('imagen:extralarge')));
              } else {
              $objImagen = $this->imagen_m->get_by(array("grupo_maestros_id" => $this->input->post('maestro_id'), "tipo_imagen_id" => $this->config->item('imagen:small')));
              }
              }
              if (count($objImagen) > 0) {
              $objBeanDetalleSecciones = new stdClass();
              $objBeanDetalleSecciones->id = NULL;
              $objBeanDetalleSecciones->secciones_id = $this->input->post('seccion_id');
              $objBeanDetalleSecciones->reglas_id = NULL;
              $objBeanDetalleSecciones->videos_id = NULL;
              $objBeanDetalleSecciones->grupo_maestros_id = $this->input->post('maestro_id');
              $objBeanDetalleSecciones->categorias_id = NULL;
              $objBeanDetalleSecciones->tags_id = NULL;
              $objBeanDetalleSecciones->imagenes_id = $objImagen->id;
              $objBeanDetalleSecciones->peso = $this->obtenerPeso($this->input->post('seccion_id'));
              $objBeanDetalleSecciones->descripcion_item = '';
              //$objBeanDetalleSecciones->templates_id = $this->config->item('template:programa');
              $objBeanDetalleSecciones->estado = 1;
              $objBeanDetalleSecciones->fecha_registro = date("Y-m-d H:i:s");
              $objBeanDetalleSecciones->usuario_registro = $user_id;
              $objBeanDetalleSecciones->estado_migracion = '0';
              $objBeanDetalleSecciones->fecha_migracion = '0000-00-00 00:00:00';
              $objBeanDetalleSecciones->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
              $objBeanDetalleSeccionesSaved = $this->detalle_secciones_m->save($objBeanDetalleSecciones);
              $detalle_seccion_id = $objBeanDetalleSeccionesSaved->id;
              } else {
              if ($objSeccion->templates_id == $this->config->item('template:destacado') || $objSeccion->templates_id == $this->config->item('template:destacado_canal')) {
              $returnValue = 5;
              } else {
              $returnValue = 2; // no tiene imagen de tipo small
              }
              }
              }
              } */
            echo json_encode(array("error" => $returnValue, "detalle_seccion_id" => $detalle_seccion_id));
        }
    }

    /**
     * Método que retorna todas las colecciones de un programa
     * @param int $programa_id
     * @return array
     */
    public function obtenerColeccionPrograma($programa_id) {
        $returnValue = array();
        if ($programa_id > 0) {
            $objPrograma = $this->grupo_maestro_m->get($programa_id);
        }
        return $returnValue;
    }

    public function agregar_portada($canal_id) {
        if ($this->input->is_ajax_request()) {
            $user_id = (int) $this->session->userdata('user_id');
            $returnValue = 0;
            $portada_id = 0;
            $resultado = $this->portada_m->like('nombre', trim($this->input->post('nombre')), 'none')->get_many_by(array("canales_id" => $canal_id));
            if (count($resultado) > 0) {
                $returnValue = 1; // existe una portada con el mismo nombre
            } else {
                $objBeanPortada = new stdClass();
                $objBeanPortada->id = NULL;
                $objBeanPortada->canales_id = $canal_id;
                $objBeanPortada->nombre = $this->input->post('nombre');
                $objBeanPortada->descripcion = $this->input->post('descripcion');
                $objBeanPortada->tipo_portadas_id = $this->input->post('tipo');
                $objBeanPortada->origen_id = $canal_id;
                $objBeanPortada->estado = 0;
                $objBeanPortada->fecha_registro = date("Y-m-d H:i:s");
                $objBeanPortada->usuario_registro = $user_id;
                $objBeanPortada->fecha_actualizacion = date("Y-m-d H:i:s");
                $objBeanPortada->usuario_actualizacion = $user_id;
                $objBeanPortada->id_mongo = NULL;
                $objBeanPortada->estado_migracion = 0;
                $objBeanPortada->fecha_migracion = '0000-00-00 00:00:00';
                $objBeanPortada->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                $objBeanPortadaSaved = $this->portada_m->save($objBeanPortada);
                $portada_id = $objBeanPortadaSaved->id;
            }
            echo json_encode(array("error" => $returnValue, "portada_id" => $portada_id));
        }
    }

    public function agregar_seccion($portada_id) {
        if ($this->input->is_ajax_request()) {
            $returnValue = 0;
            $arrayValue = array();
            //verificamos que no exista la seccion ingresada
            if (!$this->existeSeccion($this->input->post('nombre_seccion'), $portada_id)) {
                $user_id = (int) $this->session->userdata('user_id');
                $objBeanSeccion = new stdClass();
                $objBeanSeccion->id = NULL;
                $objBeanSeccion->nombre = $this->input->post('nombre_seccion');
                $objBeanSeccion->descripcion = $this->input->post('descripcion_seccion');
                $objBeanSeccion->tipo = 0;
                $objBeanSeccion->portadas_id = $portada_id;
                //$objBeanSeccion->tipo_secciones_id = $this->config->item('seccion:perzonalizado');
                $objBeanSeccion->tipo_secciones_id = $this->input->post('tipo_seccion');
                $objBeanSeccion->peso = $this->obtenerPesoSeccion($portada_id);
                $objBeanSeccion->id_mongo = NULL;
                $objBeanSeccion->estado = 0;
                $objBeanSeccion->templates_id = $this->input->post('template');
                $objBeanSeccion->fecha_registro = date("Y-m-d H:i:s");
                $objBeanSeccion->usuario_registro = $user_id;
                $objBeanSeccion->fecha_actualizacion = date("Y-m-d H:i:s");
                $objBeanSeccion->usuario_actualizacion = $user_id;
                $objBeanSeccion->estado_migracion = 0;
                $objBeanSeccion->fecha_migracion = '0000-00-00 00:00:00';
                $objBeanSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                $objBeanSeccion->grupo_maestros_id = NULL;
                $objBeanSeccionSaved = $this->secciones_m->save($objBeanSeccion);
                $estado = 'Borrador';
                //$acciones = 'Previsualizar | Publicar | Editar | Eliminar';
                //$acciones = '<a href="/admin/canales/previsualizar_seccion/" target ="_blank" class="modal-large">Previsualizar</a> | <a href="#" onclick="publicar_seccion(' . $objBeanSeccionSaved->id . ', \'seccion\');return false;">Publicar</a> | <a title="Editar" href="admin/canales/seccion/' . $this->input->post('canal_id') . '/' . $objBeanSeccionSaved->id . '">Editar</a> | <a href="#" onclick="eliminar_seccion(' . $objBeanSeccionSaved->id . ', \'seccion\');return false;">Eliminar</a>';
                $acciones = '<a href="/admin/canales/previsualizar_seccion/' . $objBeanSeccionSaved->id . '" target ="_blank" class="mode_preview modal-large">Previsualizar</a>';
                $acciones.= '<a href="#" onclick="publicar_seccion(' . $objBeanSeccionSaved->id . ', \'seccion\');return false;" class="mode_publish">Publicar</a>';
                $acciones.= '<a title="Editar" href="/admin/canales/seccion/' . $this->input->post('canal_id') . '/' . $objBeanSeccionSaved->id . '" class="mode_edit">Editar</a>';
                $acciones.= '<a href="#" onclick="eliminar_seccion(' . $objBeanSeccionSaved->id . ', \'seccion\');return false;" class="mode_delete">Eliminar</a>';
                if ($objBeanSeccionSaved->estado == 1) {
                    $estado = 'Publicado';
                }
                $cantidad = $this->secciones_m->count_by(array("portadas_id" => $portada_id));
                $arrayValue = array("nombre" => $objBeanSeccionSaved->nombre, "descripcion" => $objBeanSeccionSaved->descripcion, "estado" => $estado, "acciones" => $acciones, "indice" => $cantidad);
            } else {
                $returnValue = 1; // la seccion a registrar ya exixte para esta portada
            }
            echo json_encode(array("error" => $returnValue, "value" => $arrayValue, "portada_id" => $portada_id));
        }
    }

    /**
     * Método q verifica si la seccion esta registrada en la portada
     * @param string $nombre_seccion
     * @param int $portada_id
     * @return boolean
     */
    public function existeSeccion($nombre_seccion, $portada_id) {
        $returnValue = false;
        $resultado = $this->secciones_m->like('nombre', trim($nombre_seccion))->get_many_by(array("portadas_id" => $portada_id));
        if (count($resultado) > 0) {
            $returnValue = true;
        }
        return $returnValue;
    }

    public function obtenerPesoSeccion($portada_id) {
        $returnValue = 1;
        $resultado = $this->secciones_m->order_by('peso', 'DESC')->get_by(array("portadas_id" => $portada_id));
        if (count($resultado) > 0) {
            $returnValue = $resultado->peso + 1;
        }
        return $returnValue;
    }

    private function generarNuevaPortada($objCanal, $objMaestro = NULL, $tipo_portada) {
        $user_id = (int) $this->session->userdata('user_id');
        //creamos el registro de portada
        $objBeanPortada = new stdClass();
        $objBeanPortada->id = NULL;
        $objBeanPortada->canales_id = $objCanal->id;
        if ($tipo_portada == $this->config->item('portada:canal')) {
            $objBeanPortada->nombre = 'Portada ' . $objCanal->nombre; ///PORTADA + nombre del canal
            $objBeanPortada->descripcion = $objCanal->descripcion; //jala del canal            
            $objBeanPortada->origen_id = $objCanal->id;
        } else {
            $objBeanPortada->nombre = 'Portada ' . $objMaestro->nombre; ///PORTADA + nombre del canal
            $objBeanPortada->descripcion = $objMaestro->descripcion; //jala del canal            
            $objBeanPortada->origen_id = $objMaestro->id;
        }
        $objBeanPortada->tipo_portadas_id = $tipo_portada; //$this->config->item('portada:canal');
        $objBeanPortada->estado = $this->config->item('estado:borrador');
        $objBeanPortada->fecha_registro = date("Y-m-d H:i:s");
        $objBeanPortada->usuario_registro = $user_id;
        $objBeanPortada->fecha_actualizacion = date("Y-m-d H:i:s");
        $objBeanPortada->usuario_actualizacion = $user_id;
        $objBeanPortada->id_mongo = '0';
        $objBeanPortada->estado_migracion = $this->config->item('migracion:nuevo');
        $objBeanPortada->fecha_migracion = '0000-00-00 00:00:00';
        $objBeanPortada->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
        $objBeanPortadaSaved = $this->portada_m->save($objBeanPortada);
        //listamos los tipos de secciones predefinidas para crearlas
        $arraySecciones = $this->tipo_secciones_m->get_many_by(array());
        if ($tipo_portada != $this->config->item('portada:canal')) {
            if (count($arraySecciones) > 0) {
                foreach ($arraySecciones as $puntero => $oS) {
                    if ($oS->id == $this->config->item('seccion:programa')) {
                        unset($arraySecciones[$puntero]);
                    }
                }
            }
        }
        //iteramos los tipos de seccion para generarlas
        foreach ($arraySecciones as $puntero => $objTipoSeccion) {
            if ($objTipoSeccion->id < intval($this->config->item('seccion:perzonalizado'))) {//no se creara secciones personalizadas
                $objBeanSeccion = new stdClass();
                $objBeanSeccion->id = NULL;
                $objBeanSeccion->nombre = ucwords($objTipoSeccion->nombre); // nombre de la seccion es el nombre del tipo de la seccion
                if ($objTipoSeccion->id == $this->config->item('seccion:destacado')) {
                    //template:destacado_canal
                    if ($tipo_portada == $this->config->item('portada:canal')) {
                        $objBeanSeccion->templates_id = $this->config->item('template:destacado_canal');
                    } else {
                        $objBeanSeccion->templates_id = $this->config->item('template:destacado');
                    }
                } else {
                    if ($objTipoSeccion->id == $this->config->item('seccion:programa')) {
                        $objBeanSeccion->templates_id = '6';
                    } else {
                        if ($objTipoSeccion->id == $this->config->item('seccion:video')) {
                            $objBeanSeccion->templates_id = '5';
                        } else {
                            $objBeanSeccion->templates_id = '3';
                        }
                    }
                }
                $objBeanSeccion->descripcion = '';
                $objBeanSeccion->tipo = '0';
                $objBeanSeccion->portadas_id = $objBeanPortadaSaved->id;
                $objBeanSeccion->tipo_secciones_id = $objTipoSeccion->id;
                $objBeanSeccion->peso = ($puntero + 1);
                $objBeanSeccion->id_mongo = '0';
                $objBeanSeccion->estado = $this->config->item('estado:borrador');
                $objBeanSeccion->fecha_registro = date("Y-m-d H:i:s");
                $objBeanSeccion->usuario_registro = $user_id;
                $objBeanSeccion->fecha_actualizacion = date("Y-m-d H:i:s");
                $objBeanSeccion->usuario_actualizacion = $user_id;
                $objBeanSeccion->estado_migracion = $this->config->item('migracion:nuevo');
                $objBeanSeccion->fecha_migracion = '0000-00-00 00:00:00';
                $objBeanSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                $objBeanSeccion->grupo_maestros_id = NULL;
                $objBeanSeccionSaved = $this->secciones_m->save($objBeanSeccion);

                //verificamos que es de portada de tipo programa para que registre su destacado
                if ($tipo_portada == $this->config->item('portada:programa')) {
                    //en la sección destacado buscar imagen extralarge para registrar detalle seccion
                    if ($objTipoSeccion->id == intval($this->config->item('seccion:destacado'))) {//seccion destacado
                        $objImagen = $this->imagen_m->get_by(array("grupo_maestros_id" => $objMaestro->id, "estado" => "1", "tipo_imagen_id" => $this->config->item('imagen:extralarge')));
                        if (count($objImagen) > 0) {
                            $objBeanDetalleSecciones = new stdClass();
                            $objBeanDetalleSecciones->id = NULL;
                            $objBeanDetalleSecciones->secciones_id = $objBeanSeccionSaved->id;
                            $objBeanDetalleSecciones->reglas_id = NULL;
                            $objBeanDetalleSecciones->videos_id = NULL;
                            $objBeanDetalleSecciones->grupo_maestros_id = $objMaestro->id;
                            $objBeanDetalleSecciones->categorias_id = NULL;
                            $objBeanDetalleSecciones->tags_id = NULL;
                            $objBeanDetalleSecciones->imagenes_id = $objImagen->id;
                            $objBeanDetalleSecciones->peso = 1;
                            $objBeanDetalleSecciones->descripcion_item = NULL;
                            $objBeanDetalleSecciones->estado = $this->config->item('estado:publicado');
                            $objBeanDetalleSecciones->fecha_registro = date("Y-m-d H:i:s");
                            $objBeanDetalleSecciones->usuario_registro = $user_id;
                            $objBeanDetalleSecciones->estado_migracion = $this->config->item('migracion:nuevo');
                            $objBeanDetalleSecciones->fecha_migracion = '0000-00-00 00:00:00';
                            $objBeanDetalleSecciones->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                            $objBeanDetalleSeccionesSaved = $this->detalle_secciones_m->save($objBeanDetalleSecciones);
                            $this->secciones_m->update($objBeanSeccionSaved->id, array("estado" => $this->config->item('estado:publicado')));
                        }
                    }
                }
            }
        }
    }

    /**
     * Método para camiar un canal a un estado eliminado y todo su contenido
     * @author Johny Huamani <johnny1402@gmail.com>
     * @param int $canal_id
     */
    public function eliminar_canal($canal_id) {
        if ($this->input->is_ajax_request()) {
            //estado eliminado al registro del canal
            $this->canales_m->update($canal_id, array("estado_migracion_sphinx" => $this->config->item('sphinx:actualizar'), "estado" => $this->config->item('estado:eliminado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
            //estado eliminado a sus maestros//
            $objColeccionMaestros = $this->grupo_maestro_m->get_many_by(array("canales_id" => $canal_id));
            if (count($objColeccionMaestros) > 0) {
                foreach ($objColeccionMaestros as $puntero => $objMaestro) {
                    $this->grupo_maestro_m->update($objMaestro->id, array("estado" => $this->config->item('estado:eliminado')));
                }
            }
            //$this->grupo_maestro_m->update_by("canales_id", $canal_id, array("estado"=>$this->config->item('estado:borrador')));
            //estado eliminado para sus videos
            //$this->videos_m->update_by('canales_id', $canal_id, array('videos:eliminado'));
            $objColeccionVideo = $this->videos_m->get_many_by(array("canales_id" => $canal_id));
            if (count($objColeccionVideo) > 0) {
                foreach ($objColeccionVideo as $indice => $objVideo) {
                    $this->videos_m->update($objVideo->id, array("estado" => $this->config->item('videos:eliminado')));
                }
            }

            //cambiamos el estado de relacion en la tabla usuario_grupo_canales
            $this->usuario_grupo_canales_m->update_by('canal_id', $canal_id, array('estado' => $this->config->item('estado:borrador')));
            //eliminamos la portada del canal
//            $objPortada = $this->portada_m->get_by(array("tipo_portadas_id" => $this->config->item('portada:canal'), "origen_id" => $canal_id));
//            if (count($objPortada) > 0) {
//                $this->portada_m->update($objPortada->id, array("estado" => $this->config->item('estado:eliminado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
//            }
            $this->procesos_lib->curlGenerarCanalesXId($canal_id);
            echo json_encode(array("value" => "1"));
        }
    }

    public function restablecer_canal($canal_id) {
        if ($this->input->is_ajax_request()) {
            $this->canales_m->update($canal_id, array("estado_migracion_sphinx" => $this->config->item('sphinx:actualizar'), "estado" => $this->config->item('estado:borrador'), "estado_migracion" => $this->config->item('migracion:actualizado')));
            //eliminamos la portada del canal
            $objPortada = $this->portada_m->get_by(array("tipo_portadas_id" => $this->config->item('portada:canal'), "origen_id" => $canal_id));
            if (count($objPortada) > 0) {
                $this->portada_m->update($objPortada->id, array("estado" => $this->config->item('estado:borrador'), "estado_migracion" => $this->config->item('migracion:actualizado')));
            }
            echo json_encode(array("value" => "1"));
        }
    }

    /**
     * Método para publicar un canal a traves de ajax
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param type $canal_id
     * @return json
     */
    public function publicar_canal($canal_id) {
        if ($this->input->is_ajax_request()) {
            //verificamos q al menos un maestro esté publicado para activarlo
            //$lista_maestros_publicados = $this->videos_m->get_many_by(array("canales_id" => $canal_id, "estado" => $this->config->item('video:publicado')));
            $lista_maestros_publicados = $this->vw_maestro_video_m->get_many_by(array("v" => "v", "canales_id" => $canal_id, "estado" => $this->config->item('video:publicado')));
            if (count($lista_maestros_publicados) > 0) {
                if ($this->tiene_destacado_publicado($canal_id, 'canal')) {
                    $this->canales_m->update($canal_id, array("estado_migracion_sphinx" => $this->config->item('sphinx:actualizar'), "estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                    //eliminamos la portada del canal
                    $objPortada = $this->portada_m->get_by(array("tipo_portadas_id" => $this->config->item('portada:canal'), "origen_id" => $canal_id));
                    if (count($objPortada) > 0) {
                        $this->portada_m->update($objPortada->id, array("estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                    }
                    $this->procesos_lib->curlGenerarCanalesXId($canal_id);
                    echo json_encode(array("value" => "1"));
                } else {
                    echo json_encode(array("value" => "2")); //no tiene la sección destacado publicado
                }
            } else {
                echo json_encode(array("value" => "0")); //no tiene maestros activos
            }
        }
    }

    /**
     * Método para identificar si la portada tiene la sección destacado publicado
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $canal_id
     * @param string $tipo
     * @return boolean
     */
    private function tiene_destacado_publicado($canal_id, $tipo) {
        $returnValue = FALSE;
        if ($tipo == 'canal') {
            $objPortadaCanal = $this->portada_m->get_by(array("canales_id" => $canal_id, "origen_id" => $canal_id, "tipo_portadas_id" => $this->config->item('portada:canal'), "estado" => $this->config->item('estado:publicado')));
            if (count($objPortadaCanal) > 0) {
                $returnValue = TRUE;
            }
        }
        return $returnValue;
    }

    /**
     * metodo para llamar al archivo previsualizar_canal.php para la vista previa
     * @author Johnny Huamani <johnny1402@gmail.com>
     */
    public function previsualizar_canal($canal_id) {
        $objCanal = $this->canales_m->get($canal_id);
        $objLogo = $this->imagen_m->get_by(array("canales_id" => $canal_id, "estado" => $this->config->item('estado:publicado'), "tipo_imagen_id" => $this->config->item('imagen:logo')));
        if (count($objLogo) > 0) {
            if ($objLogo->procedencia == 0) {
                $objCanal->logo = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objLogo->imagen;
            } else {
                $objLogo->logo = $objLogo->imagen;
            }
        } else {
            $objCanal->logo = $this->config->item('url:logo');
        }
        $objPortada = $this->obtenerPortadaCanal($canal_id);
        $this->template
                ->set_layout('modal', 'admin')
                ->set('objCanal', $objCanal)
                ->set('objPortada', $objPortada)
                //->append_css('module::custom.css')
                //->append_css('module::mediaquerie.css')
                ->build('admin/previsualizar_canal');
    }

    /**
     * metodo para llamar al archivo previsualizar_portada.php para la vista previa
     * @author Johnny Huamani <johnny1402@gmail.com>
     */
    public function previsualizar_portada($portada_id) {
        $objDestacado = $this->obtener_destacado_programa($portada_id);
        $arrayColeccion = $this->obtener_colecciones_portada_programa($portada_id);
        $lista = $this->obtener_lista_portada_programa($portada_id);
        $video = $this->obtener_video_portada_programa($portada_id);
        $personalizado = $this->obtener_secciones_personalizadas($portada_id);
        $this->template
                ->set_layout('modal', 'admin')
                ->set('objDestacado', $objDestacado)
                ->set('coleccion', $arrayColeccion)
                ->set('lista', $lista)
                ->set('video', $video)
                ->set('personalizado', $personalizado)
                ->build('admin/previsualizar_portada');
    }

    /**
     * Método para obtener las imagenes de las secciones personalizadas
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $portadas_id
     * @return array
     */
    private function obtener_secciones_personalizadas($portada_id) {
        $returnValue = array();
        $tipo_secciones = array('6', '7', '8', '9', '10');
        $objSeccionColeccion = $this->secciones_m->where_in('tipo_secciones_id', $tipo_secciones)->get_many_by(array("estado" => $this->config->item('estado:publicado'), "portadas_id" => $portada_id));
        if (count($objSeccionColeccion) > 0) {
            foreach ($objSeccionColeccion as $puntero => $objSeccion) {
                $detalle_secciones = $this->detalle_secciones_m->get_many_by(array("secciones_id" => $objSeccion->id));
                if (count($detalle_secciones) > 0) {
                    $array_detalle_secciones = array();
                    foreach ($detalle_secciones as $index => $objDetalleSeccion) {
                        //obtenemos la imagen a visualizar
                        $objImagen = $this->imagen_m->get($objDetalleSeccion->imagenes_id);
                        if (count($objImagen) > 0) {
                            if ($objImagen->procedencia == 1) {
                                $imagen = $objImagen->imagen;
                            } else {
                                if ($objImagen->procedencia == 0) {
                                    $imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagen->imagen;
                                } else {
                                    $imagen = $this->config->item('url:default_imagen') . 'no_video.jpg';
                                }
                            }
                        }
                        //haces push a la imagen de esta seccion
                        array_push($array_detalle_secciones, $imagen);
                    }
                }
                //hacemos push al array de retorno
                array_push($returnValue, $array_detalle_secciones);
            }
        }
        return $returnValue;
    }

    /**
     * Método para obtener las imagenes de la seccion video
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $portada_id
     * @return array
     */
    private function obtener_video_portada_programa($portada_id) {
        $returnValue = array();
        $objSeccionLista = $this->secciones_m->get_many_by(array("estado" => $this->config->item('estado:publicado'), "portadas_id" => $portada_id, "tipo_secciones_id" => $this->config->item('seccion:video')));
        if (count($objSeccionLista) > 0) {
            foreach ($objSeccionLista as $puntero => $objSeccion) {
                $detalle_secciones = $this->detalle_secciones_m->get_many_by(array("secciones_id" => $objSeccion->id));
                if (count($detalle_secciones) > 0) {
                    $array_detalle_secciones = array();
                    foreach ($detalle_secciones as $index => $objDetalleSeccion) {
                        //obtenemos la imagen a visualizar
                        $objImagen = $this->imagen_m->get($objDetalleSeccion->imagenes_id);
                        if (count($objImagen) > 0) {
                            if ($objImagen->procedencia == 1) {
                                $imagen = $objImagen->imagen;
                            } else {
                                if ($objImagen->procedencia == 0) {
                                    $imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagen->imagen;
                                } else {
                                    $imagen = $this->config->item('url:default_imagen') . 'no_video.jpg';
                                }
                            }
                        }
                        //haces push a la imagen de esta seccion
                        array_push($array_detalle_secciones, $imagen);
                    }
                }
                //hacemos push al array de retorno
                array_push($returnValue, $array_detalle_secciones);
            }
        }
        return $returnValue;
    }

    /**
     * Método para obtener las imagenes de la seccion lista
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $portada_id
     * @return array
     */
    private function obtener_lista_portada_programa($portada_id) {
        $returnValue = array();
        $objSeccionLista = $this->secciones_m->get_many_by(array("estado" => $this->config->item('estado:publicado'), "portadas_id" => $portada_id, "tipo_secciones_id" => $this->config->item('seccion:lista')));
        if (count($objSeccionLista) > 0) {
            foreach ($objSeccionLista as $puntero => $objSeccion) {
                $detalle_secciones = $this->detalle_secciones_m->get_many_by(array("secciones_id" => $objSeccion->id));
                if (count($detalle_secciones) > 0) {
                    $array_detalle_secciones = array();
                    foreach ($detalle_secciones as $index => $objDetalleSeccion) {
                        //obtenemos la imagen a visualizar
                        $objImagen = $this->imagen_m->get($objDetalleSeccion->imagenes_id);
                        if (count($objImagen) > 0) {
                            if ($objImagen->procedencia == 1) {
                                $imagen = $objImagen->imagen;
                            } else {
                                if ($objImagen->procedencia == 0) {
                                    $imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagen->imagen;
                                } else {
                                    $imagen = $this->config->item('url:default_imagen') . 'no_video.jpg';
                                }
                            }
                        }
                        //haces push a la imagen de esta seccion
                        array_push($array_detalle_secciones, $imagen);
                    }
                }
                //hacemos push al array de retorno
                array_push($returnValue, $array_detalle_secciones);
            }
        }
        return $returnValue;
    }

    /**
     * Método para listar las colecciones de los programas
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $portada_id
     */
    private function obtener_colecciones_portada_programa($portada_id, $secciones_id = 0) {
        $returnValue = array();
        if ($secciones_id > 0) {
            $objSeccionColeccion = $this->secciones_m->get_many_by(array("id" => $secciones_id, "estado" => $this->config->item('estado:publicado'), "portadas_id" => $portada_id, "tipo_secciones_id" => $this->config->item('seccion:coleccion')));
        } else {
            $objSeccionColeccion = $this->secciones_m->get_many_by(array("estado" => $this->config->item('estado:publicado'), "portadas_id" => $portada_id, "tipo_secciones_id" => $this->config->item('seccion:coleccion')));
        }
        if (count($objSeccionColeccion) > 0) {
            foreach ($objSeccionColeccion as $puntero => $objSeccion) {
                $detalle_secciones = $this->detalle_secciones_m->get_many_by(array("secciones_id" => $objSeccion->id));
                if (count($detalle_secciones) > 0) {
                    $array_detalle_secciones = array();
                    foreach ($detalle_secciones as $index => $objDetalleSeccion) {
                        //obtenemos la imagen a visualizar
                        $objImagen = $this->imagen_m->get($objDetalleSeccion->imagenes_id);
                        if (count($objImagen) > 0) {
                            if ($objImagen->procedencia == 1) {
                                $imagen = $objImagen->imagen;
                            } else {
                                if ($objImagen->procedencia == 0) {
                                    $imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagen->imagen;
                                } else {
                                    $imagen = $this->config->item('url:default_imagen') . 'no_video.jpg';
                                }
                            }
                        }
                        //haces push a la imagen de esta seccion
                        array_push($array_detalle_secciones, $imagen);
                    }
                }
                //hacemos push al array de retorno
                array_push($returnValue, $array_detalle_secciones);
            }
        }
        return $returnValue;
    }

    /**
     * Método para obtener el objeto destacado
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $portada_id
     * @return array
     */
    private function obtener_destacado_programa($portada_id) {
        $returnValue = array();
        $objSeccionDestacado = $this->secciones_m->get_by(array("portadas_id" => $portada_id, "tipo_secciones_id" => $this->config->item('seccion:destacado')));
        if (count($objSeccionDestacado) > 0) {
            $detalle_secciones = $this->detalle_secciones_m->get_by(array("secciones_id" => $objSeccionDestacado->id));
            if (count($detalle_secciones) > 0) {
                //obtenemos la imagen a visualizar
                $objImagen = $this->imagen_m->get($detalle_secciones->imagenes_id);
                if (count($objImagen) > 0) {
                    if ($objImagen->procedencia == 1) {
                        $objSeccionDestacado->imagen = $objImagen->imagen;
                    } else {
                        if ($objImagen->procedencia == 0) {
                            $objSeccionDestacado->imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagen->imagen;
                        } else {
                            $objSeccionDestacado->imagen = $this->config->item('url:default_imagen') . 'no_video.jpg';
                        }
                    }
                }
                //obtenemos el titulo y descripcion del item
                if ($detalle_secciones->grupo_maestros_id > 0) {
                    $objMaestro = $this->grupo_maestro_m->get($detalle_secciones->grupo_maestros_id);
                    if (count($objMaestro) > 0) {
                        $objSeccionDestacado->titulo = $objMaestro->nombre;
                    }
                } else {
                    if ($detalle_secciones->videos_id > 0) {
                        $objVideo = $this->videos_m->get($detalle_secciones->videos_id);
                        if (count($objVideo) > 0) {
                            $objSeccionDestacado->titulo = $objVideo->titulo;
                        }
                    } else {
                        if ($detalle_secciones->canales_id > 0) {
                            $objCanal = $this->canales_m->get($detalle_secciones->videos_id);
                            if (count($objCanal) > 0) {
                                $objSeccionDestacado->titulo = $objCanal->titulo;
                            }
                        } else {
                            if ($detalle_secciones->imagenes_id > 0) {
                                $oImagen = $this->imagen_m->get($detalle_secciones->imagenes_id);
                                if (count($oImagen) > 0) {
                                    if ($oImagen->grupo_maestros_id > 0) {
                                        $objMaestro = $this->grupo_maestro_m->get($oImagen->grupo_maestros_id);
                                        if (count($objMaestro) > 0) {
                                            $objSeccionDestacado->titulo = $objMaestro->nombre;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $returnValue = $objSeccionDestacado;
        } else {
            $objDestacadoPortada = new stdClass();
            $objDestacadoPortada->imagen = $this->config->item('url:default_imagen') . 'no_video.jpg';
            $objDestacadoPortada->titulo = '';
            $returnValue = $objDestacadoPortada;
        }
        return $returnValue;
    }

    /**
     * Método para eliminar una portada a traves de ajax
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $portada_id
     * @return json
     */
    public function eliminar_portada($portada_id) {
        if ($this->input->is_ajax_request()) {
            $this->portada_m->update($portada_id, array("estado" => $this->config->item('estado:eliminado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
            echo json_encode(array("value" => "1"));
        }
    }

    public function restablecer_portada($portada_id) {
        if ($this->input->is_ajax_request()) {
            $this->portada_m->update($portada_id, array("estado" => $this->config->item('estado:borrador'), "estado_migracion" => $this->config->item('migracion:actualizado')));
            echo json_encode(array("value" => "1"));
        }
    }

    /**
     * Método para publicar una portada x su ID
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param type $portada_id
     * @return json
     */
    public function publicar_portada($portada_id) {
        if ($this->input->is_ajax_request()) {
            $this->portada_m->update($portada_id, array("estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
            echo json_encode(array("value" => "1"));
        }
    }

    /**
     * Método para mostrar la vista previa de una seccion
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $seccion_id
     */
    public function previsualizar_seccion($seccion_id) {
        $objSeccion = $this->secciones_m->get($seccion_id);
        $objPortada = $this->portada_m->get($objSeccion->portadas_id);
        $objCanal = $this->canales_m->get($objPortada->canales_id);
        $html = '';
        if ($objPortada->tipo_portadas_id == $this->config->item('portada:canal')) {
            switch ($objSeccion->tipo_secciones_id) {
                case $this->config->item('seccion:destacado'):
                    $html.=$this->obtener_html_seccion_portada_canal($objCanal, $objSeccion);
                    break;
                case $this->config->item('seccion:programa'):
                    $html.=$this->obtener_html_programa_portada_canal($seccion_id);
                    break;
                case $this->config->item('seccion:coleccion'):
                    $html.=$this->obtener_html_coleccion_portada_programa($objPortada->id, $seccion_id);
                    break;
                default:
                    $html.=$this->obtener_html_seccion($seccion_id);
                    break;
            }
        } else {
            switch ($objSeccion->tipo_secciones_id) {
                case $this->config->item('seccion:destacado'):
                    $html.=$this->obtener_html_seccion_portada_programa($objPortada->id);
                    break;
                case $this->config->item('seccion:coleccion'):
                    $html.=$this->obtener_html_coleccion_portada_programa($objPortada->id, $seccion_id);
                    break;
                default:
                    $html.=$this->obtener_html_seccion($seccion_id);
                    break;
            }
        }
        $this->template
                ->set_layout('modal', 'admin')
                ->set('html', $html)
                ->build('admin/previsualizar_seccion');
    }

    /**
     * Método para obtener el HTML de una vista previa de una seccion
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $seccion_id
     * @return string
     */
    private function obtener_html_seccion($seccion_id) {
        $html = '';
        $detalle_secciones = $this->detalle_secciones_m->get_many_by(array("secciones_id" => $seccion_id));
        $html.='<div class="sli_item">
                        <!-- ENCABEZADO -->
                        <div class="head_section mc_column mc_columnA mc_mbottom hd_sli">
                            <div class="bkg_col02 hsection">

                                <h4>vista previa de la seccion</h4>           

                                <!--<div class="options_section">
                                    <span class="options_left"></span>
                                    <span class="options_center"><a href="#">+popular del d&#237;a</a></span>
                                    <span class="options_right"></span>-->

                                <div class="controls-slider">
                                    <div class="hidden_links">
                                        <span class="back_link"></span>
                                        <span class="forward_link"></span>
                                    </div>
                                    <a href="#" class="back_linker_small">Siguiente</a>
                                    <a href="#" class="forward_linker_small">Anterior</a>
                                </div>
                            </div>
                        </div>  
                        <div class="mc_column mc_columnA bd_sli">
                            <ul class="sli_ver">';
        if (count($detalle_secciones) > 0) {
            foreach ($detalle_secciones as $indice => $objDetalleSeccion) {
                //obtenemos la imagen a visualizar
                $objImagen = $this->imagen_m->get($objDetalleSeccion->imagenes_id);
                if (count($objImagen) > 0) {
                    if ($objImagen->procedencia == 1) {
                        $objDetalleSeccion->imagen = $objImagen->imagen;
                    } else {
                        if ($objImagen->procedencia == 0) {
                            $objDetalleSeccion->imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagen->imagen;
                        } else {
                            $objDetalleSeccion->imagen = $this->config->item('url:default_imagen') . 'no_video.jpg';
                        }
                    }
                }
                $html.='<li class="str_C">
                                            <div class="mc_column mc_columnD mc_mbottom mc_mright">
                                                <div>
                                                    <a href="#">
                                                        <img src="' . $objDetalleSeccion->imagen . '" alt=""/>
                                                    </a>
                                                </div>
                                            </div>
                                        </li>';
            }
        }
        $html.='</ul>
                        </div>   
                    </div>';
        return $html;
    }

    /**
     * Método para obtener el html de la vista previa de las colecciones
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param object $portada_id
     * @param object $seccion_id
     * @return string
     */
    private function obtener_html_coleccion_portada_programa($portada_id, $seccion_id) {
        $coleccion = $this->obtener_colecciones_portada_programa($portada_id, $seccion_id);
        $html = '';
        if (count($coleccion) > 0) {
            foreach ($coleccion as $puntero => $arrayImagen) {

                $html.= '<div class="sli_item">
                        <!-- ENCABEZADO -->
                        <div class="head_section mc_column mc_columnA mc_mbottom hd_sli">
                            <div class="bkg_col02 hsection">
                                <h4>Temporada</h4>           
                                <div class="controls-slider">
                                    <div class="hidden_links">
                                        <span class="back_link"></span>
                                        <span class="forward_link"></span>
                                    </div>
                                    <a href="#" class="back_linker_small">Siguiente</a>
                                    <a href="#" class="forward_linker_small">Anterior</a>
                                </div>
                            </div>
                        </div>  
                        <div class="mc_column mc_columnA bd_sli">
                            <ul class="sli_ver">';
                if (count($arrayImagen) > 0) {
                    foreach ($arrayImagen as $indice => $imagen) {
                        $html.='<li class="str_C">
                                            <div class="mc_column mc_columnD mc_mbottom mc_mright">
                                                <div>
                                                    <a href="#">
                                                        <img src="' . $imagen . '" alt=""/>
                                                    </a>
                                                </div>
                                            </div>
                                        </li>';
                    }
                }

                $html.='</ul>
                        </div>   
                    </div>';
            }
        }

        return $html;
    }

    /**
     * Método para obtener el html de la vista previa de un programa
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param type $secciones_id
     * @return string
     */
    private function obtener_html_programa_portada_canal($secciones_id) {
        $detalle_secciones = $this->detalle_secciones_m->get_many_by(array("secciones_id" => $secciones_id));
        $html = '';
        $html.='<div class="sli_item_">
                        <div class="head_section mc_column mc_columnA mc_mbottom">
                            <div class="bkg_col02 hsection">
                                <h4>PROGRAMA</h4>               
                            </div>
                        </div>
                        <div class="mc_column mc_columnA head_section mbottom str_E">';
        if (count($detalle_secciones) > 0) {
            foreach ($detalle_secciones as $indice => $objDetalleSeccion) {
                //obtenemos la imagen a visualizar
                $objImagen = $this->imagen_m->get($objDetalleSeccion->imagenes_id);
                if (count($objImagen) > 0) {
                    if ($objImagen->procedencia == 1) {
                        $objDetalleSeccion->imagen = $objImagen->imagen;
                    } else {
                        if ($objImagen->procedencia == 0) {
                            $objDetalleSeccion->imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagen->imagen;
                        } else {
                            $objDetalleSeccion->imagen = $this->config->item('url:default_imagen') . 'no_video.jpg';
                        }
                    }
                }
                $html.='<div class="mc_column mc_columnD mc_mbottom mc_mright">
                                        <a href="#">
                                            <div class="content_section2">
                                                <div class="mc_column layer_content">
                                                    <img src="' . $objDetalleSeccion->imagen . '" alt="">
                                                </div>
                                            </div>
                                        </a>
                                    </div>  ';
            }
        }
        $html.='</div>
                    </div>';

        return $html;
    }

    /**
     * Método para obtener la vista de un destacado de programa
     * Johnny Huamani <johnny1402@gmail.com>
     * @param int $objCanal
     * @return string
     */
    private function obtener_html_seccion_portada_programa($portada_id) {
        $objDestacado = $this->obtener_destacado_programa($portada_id);
        $html = '<div class="mc_column mc_columnA mc_mbottom">
                <div class="flexslider">
                    <ul class="slides">           
                        <li>
                            <div class="content_section3">
                                <div class="layer_content">                        
                                       <img src="' . $objDestacado->imagen . '" title="" alt="">
                                        <div class="mode_fade">
                                            <a href="#">
                                            <div class="layer_info">
                                                <div class="data_info down_place4">
                                                    <span class="span_text2"></span> 
                                                    <h5>' . $objDestacado->titulo . '</h5>
                                                    <span class="span_text2">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore</span>
                                                </div>
                                            </div>
                                            </a> 
                                        </div>                     
                                </div>
                            </div>
                        </li>
                     </ul>
                </div> 
        </div>';
        return $html;
    }

    /**
     * Método para generar la vista de un destacado canal
     * Johnny Huamani <johnny1402@gmail.com>
     * @param object $objCanal
     * @param object $objSeccion
     * @return string
     */
    private function obtener_html_seccion_portada_canal($objCanal, $objSeccion) {
        $objDetalleSeccion = $this->detalle_secciones_m->get_by(array("secciones_id" => $objSeccion->id));
        //obtenemos la imagen a visualizar
        $objImagen = $this->imagen_m->get($objDetalleSeccion->imagenes_id);
        if (count($objImagen) > 0) {
            if ($objImagen->procedencia == 1) {
                $objDetalleSeccion->imagen = $objImagen->imagen;
            } else {
                if ($objImagen->procedencia == 0) {
                    $objDetalleSeccion->imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagen->imagen;
                } else {
                    $objDetalleSeccion->imagen = $this->config->item('url:default_imagen') . 'no_video.jpg';
                }
            }
        }
        $html = '<div class="mc_column mc_columnA ">
            <div class="mc_column mc_columnE mc_mbottom  mc_mright">  
                <div class="mc_colum mc_columnA player_video_main3">
                    <div class="flexslider">
                        <ul class="slides">
                            <li>
                                <div class="content_section3">
                                    <div class="layer_content">
                                        <img src="' . $objDetalleSeccion->imagen . '" title="" alt="">
                                        <div class="mode_fade">
                                            <a href="#">
                                                <div class="layer_info">
                                                    <div class="data_info down_place4">
                                                        <span class="span_text2"></span>
                                                        <h5>
                                                            titulo
                                                        </h5>
                                                        <span class="span_text2"></span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="mode_fade">
                                            <a href="#">
                                                <div class="layer_info">
                                                    <div class="data_info down_place4">
                                                        <span class="span_text2"></span> 
                                                        <h5>titulo</h5>
                                                        <span class="span_text2"></span>
                                                    </div>
                                                </div>

                                            </a> 
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

            <div class="mc_column mc_columnF">
                <div class="mc_column canal_data">
                    <div class="mc_column item_canal_logo">
                        <img src="" title="' . $objCanal->descripcion . '" alt="' . $objCanal->descripcion . '"> 
                    </div>
                    <div class="mc_column item_canal_desc">
                        ' . $objCanal->descripcion . '
                    </div>         
                </div>
                <div class="mc_column canal_info">
                    <div class="mc_column item_canal_text1">
                        <a class="suscript_link3 linker_btn3 tcol08 " href="#"><span class="suscript_left2"></span><span class="suscript_center2  size08 tol00">suscribirse</span><span class="suscript_right2"></span></a>
                        <a class="suscript_link3 linker_btn3 tcol08 "><span class="suscript_number  size08 tol00">125879</span></a>
                    </div>
                    <div class="mc_column item_canal_text2">
                        <span>151 videos</span>
                    </div>
                    <div class="mc_column item_canal_text2">
                        <span>1,443 seguidores</span>
                    </div>
                    <div class="mc_column item_canal_text2">
                        <span>Favoritos</span>
                    </div>        
                </div>
            </div>
        </div>';
        return $html;
    }

    /**
     * Método para eliminar una seccione a traves de su ID
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $seccion_id
     * @return json
     */
    public function eliminar_seccion($seccion_id) {
        if ($this->input->is_ajax_request()) {
            $this->secciones_m->update($seccion_id, array("estado" => $this->config->item('estado:eliminado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
            echo json_encode(array("value" => "1"));
        }
    }

    public function restablecer_seccion($seccion_id) {
        if ($this->input->is_ajax_request()) {
            $this->secciones_m->update($seccion_id, array("estado" => $this->config->item('estado:borrador'), "estado_migracion" => $this->config->item('migracion:actualizado')));
            echo json_encode(array("value" => "1"));
        }
    }

    public function publicar_seccion($seccion_id) {
        if ($this->input->is_ajax_request()) {
            $this->secciones_m->update($seccion_id, array("estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
            echo json_encode(array("value" => "1"));
        }
    }

    /**
     * Método para listar items que correspondan al formulario de busqueda solo para destacados
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $current_page
     * @param int $paginado
     */
    public function buscar_para_destacado($current_page = 1, $paginado = 0) {
        if ($this->input->is_ajax_request()) {
            $array_maestros = array();
            switch ($this->input->post('tipo_buscar')) {
                case $this->config->item('videos:video'):
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        $lista_videos = $this->videos_m->like('titulo', $this->input->post('txtBuscar'))->get_many_by(array('canales_id' => $this->input->post('canal_id')));
                    } else {
                        $lista_videos = $this->videos_m->get_many_by(array('canales_id' => $this->input->post('canal_id')));
                    }
                    if (count($lista_videos) > 0) {
                        foreach ($lista_videos as $index => $objVideo) {
                            $objVideo->es_maestro = 0;
                            $objVideo->tipo = 'Video';
                            array_push($array_maestros, $objVideo);
                        }
                    }
                    break;
                case $this->config->item('videos:canal'):

                    break;
                case $this->config->item('videos:programa'):
                case $this->config->item('videos:coleccion'):
                case $this->config->item('videos:lista'):
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        if ($this->input->post('tipo_buscar')) {
                            $lista_maestros = $this->grupo_maestro_m->where_in('tipo_grupo_maestro_id', $this->input->post('tipo_buscar'))->like('nombre', $this->input->post('txtBuscar'))->get_many_by(array('canales_id' => $this->input->post('canal_id')));
                        } else {
                            $lista_maestros = $this->grupo_maestro_m->like('nombre', $this->input->post('txtBuscar'))->get_many_by(array('canales_id' => $this->input->post('canal_id')));
                        }
                    } else {
                        if ($this->input->post('tipo_buscar')) {
                            $lista_maestros = $this->grupo_maestro_m->where_in('tipo_grupo_maestro_id', $this->input->post('tipo_buscar'))->get_many_by(array('canales_id' => $this->input->post('canal_id')));
                        } else {
                            $lista_maestros = $this->grupo_maestro_m->get_many_by(array('canales_id' => $this->input->post('canal_id')));
                        }
                    }
                    if (count($lista_maestros) > 0) {

                        foreach ($lista_maestros as $puntero => $objMaestro) {
                            if (count($objMaestro) > 0) {
                                $objMaestro->es_maestro = 1;
                                $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro->tipo_grupo_maestro_id);
                                $objMaestro->tipo = $objTipoMaestro->nombre;
                                array_push($array_maestros, $objMaestro);
                            }
                        }
                    }
                    break;
                default:
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        if ($this->input->post('tipo_buscar')) {
                            $lista_maestros = $this->grupo_maestro_m->where_in('tipo_grupo_maestro_id', $this->input->post('tipo_buscar'))->like('nombre', $this->input->post('txtBuscar'))->get_many_by(array('canales_id' => $this->input->post('canal_id')));
                        } else {
                            $lista_maestros = $this->grupo_maestro_m->like('nombre', $this->input->post('txtBuscar'))->get_many_by(array('canales_id' => $this->input->post('canal_id')));
                        }
                    } else {
                        if ($this->input->post('tipo_buscar')) {
                            $lista_maestros = $this->grupo_maestro_m->where_in('tipo_grupo_maestro_id', $this->input->post('tipo_buscar'))->get_many_by(array('canales_id' => $this->input->post('canal_id')));
                        } else {
                            $lista_maestros = $this->grupo_maestro_m->get_many_by(array('canales_id' => $this->input->post('canal_id')));
                        }
                    }
                    if (count($lista_maestros) > 0) {

                        foreach ($lista_maestros as $puntero => $objMaestro) {
                            if (count($objMaestro) > 0) {
                                $objMaestro->es_maestro = 1;
                                $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro->tipo_grupo_maestro_id);
                                $objMaestro->tipo = $objTipoMaestro->nombre;
                                array_push($array_maestros, $objMaestro);
                            }
                        }
                    }
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        $lista_videos = $this->videos_m->like('titulo', $this->input->post('txtBuscar'))->get_many_by(array('canales_id' => $this->input->post('canal_id')));
                    } else {
                        $lista_videos = $this->videos_m->get_many_by(array('canales_id' => $this->input->post('canal_id')));
                    }
                    if (count($lista_videos) > 0) {
                        foreach ($lista_videos as $index => $objVideo) {
                            $objVideo->es_maestro = 0;
                            $objVideo->tipo = 'Video';
                            array_push($array_maestros, $objVideo);
                        }
                    }
                    break;
            }

            $total = count($array_maestros);
            $cantidad_mostrar = 7;
            $current_page = $current_page - 1;
            $numero_paginas = ceil(count($array_maestros) / $cantidad_mostrar);
            $offset = $current_page * $cantidad_mostrar;
            $array_maestros = array_slice($array_maestros, $offset, $cantidad_mostrar);
            if ($paginado == '1') {
                $returnValue = $this->htmlListaMaestro($array_maestros, $this->input->post('seccion_id'), $this->input->post('canal_id'));
            } else {
                $returnValue = '<table>';
                $returnValue.='<thead>';
                $returnValue.='<tr>';
                $returnValue.='<th>#</th>';
                $returnValue.='<th>imagen</td>';
                $returnValue.='<th>nombre</td>';
                $returnValue.='<th>tipo</td>';
                $returnValue.='<th>acciones</td>';
                $returnValue.='</tr>';
                $returnValue.='</thead>';
                $returnValue.='<tbody id="resultado">';
                $returnValue.= $this->htmlListaMaestro($array_maestros, $this->input->post('seccion_id'), $this->input->post('canal_id'));
                $returnValue.='</tbody>';
                $returnValue.='</table>';
                $returnValue.='<input type="hidden" id="total" name="total" value="' . $total . '" />';
                $returnValue.='<input type="hidden" id="cantidad_mostrar" name="cantidad_mostrar" value="' . $cantidad_mostrar . '" />';
                $returnValue.='<div id="black" style="margin: auto;"></div>';
            }
            echo $returnValue;
        }
    }

    private function htmlListaMaestro($arrayMaestro, $seccion_id, $canal_id) {
        $returnValue = '';
        if (count($arrayMaestro) > 0) {
            $indice = 0;
            foreach ($arrayMaestro as $puntero => $objMaestro) {
                if ($objMaestro->es_maestro == '1') {
                    $objImagen = $this->imagen_m->get_by(array("grupo_maestros_id" => $objMaestro->id, "tipo_imagen_id" => $this->config->item('imagen:small'), "estado" => "1"));
                    if (count($objImagen) > 0) {
                        if ($objImagen->procedencia == '0') {
                            $imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagen->imagen;
                        } else {
                            $imagen = $objImagen->imagen;
                        }
                    } else {
                        //$imagen = UPLOAD_IMAGENES_VIDEOS . 'no_video.jpg';
                        $imagen = $this->config->item('url:default_imagen') . 'no_video.jpg';
                    }
                    $returnValue.='<tr>';
                    $returnValue.='<td>' . ($indice + 1) . '</td>';
                    $returnValue.='<td><img src="' . $imagen . '" style="width:100px; height:55px;" title="' . $objMaestro->nombre . '" /></td>';
                    $returnValue.='<td>' . $objMaestro->nombre . '</td>';
                    $returnValue.='<td>' . $objMaestro->tipo . '</td>';
                    if ($this->maestroAgregadoSeccion($objMaestro->id, $seccion_id)) {
                        $returnValue.='<td><a href="#" id="agregado" name="agregado" class="btn silver" onclick="return false;">Agregado</a></td>';
                    } else {
                        $returnValue.='<td><div id="div_' . $objMaestro->id . '"><a href="#" onclick="agregarMaestroASeccion(' . $canal_id . ',' . $objMaestro->id . ', ' . $seccion_id . '); return false;" id="btnAgregar" name="btnAgregar" class="btn green">Agregar</a></div></td>';
                    }
                    $returnValue.='</tr>';
                } else {
                    if ($objMaestro->es_maestro == '2') { //para canales
                        $objImagen = $this->imagen_m->get_by(array("canales_id" => $objMaestro->id, "tipo_imagen_id" => $this->config->item('imagen:logo'), "estado" => "1"));
                        if (count($objImagen) > 0) {
                            if ($objImagen->procedencia == '0') {
                                $imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagen->imagen;
                            } else {
                                $imagen = $objImagen->imagen;
                            }
                        } else {
                            //$imagen = UPLOAD_IMAGENES_VIDEOS . 'no_video.jpg';
                            $imagen = $this->config->item('url:default_imagen') . 'no_video.jpg';
                        }
                        $returnValue.='<tr>';
                        $returnValue.='<td>' . ($indice + 1) . '</td>';
                        $returnValue.='<td><img src="' . $imagen . '" style="width:100px; height:55px;" title="' . $objMaestro->nombre . '" /></td>';
                        $returnValue.='<td>' . $objMaestro->nombre . '</td>';
                        $returnValue.='<td>' . $objMaestro->tipo . '</td>';
                        if ($this->canalAgregadoSeccion($objMaestro->id, $seccion_id)) {
                            $returnValue.='<td><a href="#" id="agregado" name="agregado" class="btn silver" onclick="return false;">Agregado</a></td>';
                        } else {
                            $returnValue.='<td><div id="div_' . $objMaestro->id . '"><a href="#" onclick="agregarCanalASeccion(' . $canal_id . ',' . $objMaestro->id . ', ' . $seccion_id . '); return false;" id="btnAgregar" name="btnAgregar" class="btn green">Agregar</a></div></td>';
                        }
                        $returnValue.='</tr>';
                    } else {
                        $objImagen = $this->imagen_m->get_by(array("videos_id" => $objMaestro->id, "tipo_imagen_id" => $this->config->item('imagen:small'), "estado" => "1"));
                        if (count($objImagen) > 0) {
                            if ($objImagen->procedencia == '0') {
                                $imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagen->imagen;
                            } else {
                                $imagen = $objImagen->imagen;
                            }
                        } else {
                            //$imagen = UPLOAD_IMAGENES_VIDEOS . 'no_video.jpg';
                            $imagen = $this->config->item('url:default_imagen') . 'no_video.jpg';
                        }
                        $returnValue.='<tr>';
                        $returnValue.='<td>' . ($indice + 1) . '</td>';
                        $returnValue.='<td><img src="' . $imagen . '" style="width:100px; height:55px;" title="' . $objMaestro->titulo . '" /></td>';
                        $returnValue.='<td>' . $objMaestro->titulo . '</td>';
                        $returnValue.='<td>' . $objMaestro->tipo . '</td>';
                        if ($this->videoAgregadoSeccion($objMaestro->id, $seccion_id)) {
                            $returnValue.='<td><a href="#" id="agregado" name="agregado" class="btn silver" onclick="return false;">Agregado</a></td>';
                        } else {
                            $returnValue.='<td><div id="div_' . $objMaestro->id . '"><a href="#" onclick="agregarVideoASeccion(' . $canal_id . ',' . $objMaestro->id . ', ' . $seccion_id . '); return false;" id="btnAgregar" name="btnAgregar" class="btn green">Agregar</a></div></td>';
                        }
                        $returnValue.='</tr>';
                    }
                }
                $indice++;
            }
        }
        return $returnValue;
    }

    private function maestroAgregadoSeccion($maestro_id, $seccion_id, $estado = 1) {
        $returnValue = false;
        $listaDetalleSecciones = $this->detalle_secciones_m->get_many_by(array("secciones_id" => $seccion_id, "grupo_maestros_id" => $maestro_id, "estado" => $estado));
        if (count($listaDetalleSecciones) > 0) {
            $returnValue = true;
        }
        return $returnValue;
    }

    private function videoAgregadoSeccion($video_id, $secciones_id, $estado = 1) {
        $returnValue = false;
        $listaDetalleSecciones = $this->detalle_secciones_m->get_many_by(array("videos_id" => $video_id, "secciones_id" => $secciones_id, "estado" => $estado));
        if (count($listaDetalleSecciones) > 0) {
            $returnValue = true;
        }
        return $returnValue;
    }

    private function canalAgregadoSeccion($canal_id, $seccion_id, $estado = 1) {
        $returnValue = false;
        $listaDetalleSecciones = $this->detalle_secciones_m->get_many_by(array("canales_id" => $canal_id, "secciones_id" => $seccion_id, "estado" => $estado));
        if (count($listaDetalleSecciones) > 0) {
            $returnValue = true;
        }
        return $returnValue;
    }

    public function agregarMaestroASeccion($maestro_id, $seccion_id) {
        if ($this->input->is_ajax_request()) {
            $user_id = (int) $this->session->userdata('user_id');
            $returnValue = 1;
            $objSeccion = $this->secciones_m->get($seccion_id);
            $objPortada = $this->portada_m->get($objSeccion->portadas_id);

            if ($objPortada->tipo_portadas_id == $this->config->item('portada:principal')) {

                if ($objSeccion->tipo_secciones_id == $this->config->item('seccion:programa')) {

                    $objImagen = $this->obtenerImagenMaestro($maestro_id, $seccion_id);

                    if (count($objImagen) > 0) {
                        //if ($this->maestroAgregadoSeccion($maestro_id, $seccion_id, 0)) {
                        $this->detalle_secciones_m->delete_by(array("secciones_id" => $seccion_id));
                        $objBeanDetalleSeccion = new stdClass();
                        $objBeanDetalleSeccion->id = NULL;
                        $objBeanDetalleSeccion->secciones_id = $seccion_id;
                        $objBeanDetalleSeccion->videos_id = NULL;
                        $objBeanDetalleSeccion->grupo_maestros_id = $maestro_id;
                        $objBeanDetalleSeccion->canales_id = NULL;
                        $objBeanDetalleSeccion->imagenes_id = $objImagen->id;
                        $objBeanDetalleSeccion->peso = 1;
                        $objBeanDetalleSeccion->descripcion_item = '';
                        $objBeanDetalleSeccion->estado = 1;
                        $objBeanDetalleSeccion->fecha_registro = date("Y-m-d H:i:s");
                        $objBeanDetalleSeccion->usuario_registro = $user_id;
                        $objBeanDetalleSeccion->estado_migracion = 0;
                        $objBeanDetalleSeccion->fecha_migracion = '0000-00-00 00:00:00';
                        $objBeanDetalleSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                        $this->detalle_secciones_m->save($objBeanDetalleSeccion);
                        //llenamos con sus listas de este programa
                        $lista_programa = $this->obtenerListaPrograma($maestro_id);

                        if (count($lista_programa) > 0) {
                            $array_id_lista = array();
                            foreach ($lista_programa as $puntero => $objLista) {
                                array_push($array_id_lista, $objLista->id);
                            }
                            if (count($array_id_lista) > 0) {
                                $array_id_lista = array_unique($array_id_lista);

                                $coleccionMaestros = $this->grupo_maestro_m->where_in('id', $array_id_lista)->order_by('fecha_transmision_inicio', 'DESC')->get_many_by(array());
                                $peso = 2;
                                if (count($coleccionMaestros) > 0) {
                                    foreach ($coleccionMaestros as $indice => $oMaestro) {
                                        $objImagenLista = $this->imagen_m->get_by(array("grupo_maestros_id" => $oMaestro->id, "tipo_imagen_id" => $this->config->item('imagen:small'), "estado" => $this->config->item('imagen:publicado')));
                                        if (count($objImagenLista) > 0) {
                                            $objBeanDetalleSeccion = new stdClass();
                                            $objBeanDetalleSeccion->id = NULL;
                                            $objBeanDetalleSeccion->secciones_id = $seccion_id;
                                            $objBeanDetalleSeccion->videos_id = NULL;
                                            $objBeanDetalleSeccion->grupo_maestros_id = $oMaestro->id;
                                            $objBeanDetalleSeccion->canales_id = NULL;
                                            $objBeanDetalleSeccion->imagenes_id = $objImagenLista->id;
                                            $objBeanDetalleSeccion->peso = $peso;
                                            $objBeanDetalleSeccion->descripcion_item = '';
                                            $objBeanDetalleSeccion->estado = 1;
                                            $objBeanDetalleSeccion->fecha_registro = date("Y-m-d H:i:s");
                                            $objBeanDetalleSeccion->usuario_registro = $user_id;
                                            $objBeanDetalleSeccion->estado_migracion = 0;
                                            $objBeanDetalleSeccion->fecha_migracion = '0000-00-00 00:00:00';
                                            $objBeanDetalleSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                                            $this->detalle_secciones_m->save($objBeanDetalleSeccion);
                                            $peso++;
                                        }
                                    }
                                }
                            }
                        }
                        $returnValue = 0;
                        //}
                    }
                } else {
                    $objImagen = $this->obtenerImagenMaestro($maestro_id, $seccion_id);
                    if (count($objImagen) > 0) {
                        if ($this->maestroAgregadoSeccion($maestro_id, $seccion_id, 0)) {
                            $objDetalleSeccion = $this->detalle_secciones_m->get_by(array("grupo_maestros_id" => $maestro_id, "secciones_id" => $seccion_id));
                            $peso = $this->obtenerPeso($seccion_id);
                            $this->detalle_secciones_m->update($objDetalleSeccion->id, array("imagenes_id" => $objImagen->id, "peso" => $peso, "estado" => "1", "estado_migracion" => $this->config->item('migracion:actualizado')));
                            $returnValue = 0;
                        } else {
                            $objBeanDetalleSeccion = new stdClass();
                            $objBeanDetalleSeccion->id = NULL;
                            $objBeanDetalleSeccion->secciones_id = $seccion_id;
                            $objBeanDetalleSeccion->videos_id = NULL;
                            $objBeanDetalleSeccion->grupo_maestros_id = $maestro_id;
                            $objBeanDetalleSeccion->canales_id = NULL;
                            $objBeanDetalleSeccion->imagenes_id = $objImagen->id;
                            $objBeanDetalleSeccion->peso = $this->obtenerPeso($seccion_id);
                            $objBeanDetalleSeccion->descripcion_item = '';
                            $objBeanDetalleSeccion->estado = 1;
                            $objBeanDetalleSeccion->fecha_registro = date("Y-m-d H:i:s");
                            $objBeanDetalleSeccion->usuario_registro = $user_id;
                            $objBeanDetalleSeccion->estado_migracion = 0;
                            $objBeanDetalleSeccion->fecha_migracion = '0000-00-00 00:00:00';
                            $objBeanDetalleSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                            $this->detalle_secciones_m->save($objBeanDetalleSeccion);
                            $returnValue = 0;
                        }
                    }
                }
            } else {
                $objImagen = $this->obtenerImagenMaestro($maestro_id, $seccion_id);
                if (count($objImagen) > 0) {
                    if ($this->maestroAgregadoSeccion($maestro_id, $seccion_id, 0)) {
                        $objDetalleSeccion = $this->detalle_secciones_m->get_by(array("grupo_maestros_id" => $maestro_id, "secciones_id" => $seccion_id));
                        $peso = $this->obtenerPeso($seccion_id);
                        $this->detalle_secciones_m->update($objDetalleSeccion->id, array("imagenes_id" => $objImagen->id, "peso" => $peso, "estado" => "1", "estado_migracion" => $this->config->item('migracion:actualizado')));
                        $returnValue = 0;
                    } else {
                        $objBeanDetalleSeccion = new stdClass();
                        $objBeanDetalleSeccion->id = NULL;
                        $objBeanDetalleSeccion->secciones_id = $seccion_id;
                        $objBeanDetalleSeccion->videos_id = NULL;
                        $objBeanDetalleSeccion->grupo_maestros_id = $maestro_id;
                        $objBeanDetalleSeccion->canales_id = NULL;
                        $objBeanDetalleSeccion->imagenes_id = $objImagen->id;
                        $objBeanDetalleSeccion->peso = $this->obtenerPeso($seccion_id);
                        $objBeanDetalleSeccion->descripcion_item = '';
                        $objBeanDetalleSeccion->estado = 1;
                        $objBeanDetalleSeccion->fecha_registro = date("Y-m-d H:i:s");
                        $objBeanDetalleSeccion->usuario_registro = $user_id;
                        $objBeanDetalleSeccion->estado_migracion = 0;
                        $objBeanDetalleSeccion->fecha_migracion = '0000-00-00 00:00:00';
                        $objBeanDetalleSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                        $this->detalle_secciones_m->save($objBeanDetalleSeccion);
                        $returnValue = 0;
                    }
                }
            }
            echo json_encode(array("error" => $returnValue, "maestro_id" => $maestro_id));
        }
    }

    private function obtenerImagenMaestro($maestro_id, $seccion_id, $origen = 'maestro') {
        $returnValue = array();
        if ($seccion_id > 0) {
            $objSeccion = $this->secciones_m->get($seccion_id);
            if (count($objSeccion) > 0) {
                switch ($objSeccion->templates_id) {
                    case $this->config->item('template:destacado_canal'):
                        if ($origen == 'canal') {
                            $returnValue = $this->imagen_m->get_by(array("canales_id" => $maestro_id, "tipo_imagen_id" => $this->config->item('imagen:extralarge'), "estado" => "1"));
                        } else {
                            if ($origen == 'video') {
                                $returnValue = $this->imagen_m->get_by(array("videos_id" => $maestro_id, "tipo_imagen_id" => $this->config->item('imagen:extralarge'), "estado" => "1"));
                            } else {
                                $returnValue = $this->imagen_m->get_by(array("grupo_maestros_id" => $maestro_id, "tipo_imagen_id" => $this->config->item('imagen:extralarge'), "estado" => "1"));
                            }
                        }
                        break;
                    case $this->config->item('template:destacado'):
                        if ($origen == 'canal') {
                            $returnValue = $this->imagen_m->get_by(array("canales_id" => $maestro_id, "tipo_imagen_id" => $this->config->item('imagen:extralarge'), "estado" => "1"));
                        } else {
                            if ($origen == 'video') {
                                $returnValue = $this->imagen_m->get_by(array("videos_id" => $maestro_id, "tipo_imagen_id" => $this->config->item('imagen:extralarge'), "estado" => "1"));
                            } else {
                                $returnValue = $this->imagen_m->get_by(array("grupo_maestros_id" => $maestro_id, "tipo_imagen_id" => $this->config->item('imagen:extralarge'), "estado" => "1"));
                            }
                        }
                        break;
                    case $this->config->item('template:5items'):
                        if ($origen == 'canal') {
                            $returnValue = $this->imagen_m->get_by(array("canales_id" => $maestro_id, "tipo_imagen_id" => $this->config->item('imagen:large'), "estado" => "1"));
                        } else {
                            if ($origen == 'video') {
                                $returnValue = $this->imagen_m->get_by(array("videos_id" => $maestro_id, "tipo_imagen_id" => $this->config->item('imagen:large'), "estado" => "1"));
                            } else {
                                $returnValue = $this->imagen_m->get_by(array("grupo_maestros_id" => $maestro_id, "tipo_imagen_id" => $this->config->item('imagen:large'), "estado" => "1"));
                            }
                        }
                        break;
                    case $this->config->item('template:8items'):
                        if ($origen == 'canal') {
                            $returnValue = $this->imagen_m->get_by(array("canales_id" => $maestro_id, "tipo_imagen_id" => $this->config->item('imagen:logo'), "estado" => $this->config->item('estado:publicado')));
                        } else {
                            if ($origen == 'video') {
                                $returnValue = $this->imagen_m->get_by(array("videos_id" => $maestro_id, "tipo_imagen_id" => $this->config->item('imagen:small'), "estado" => $this->config->item('estado:publicado')));
                            } else {
                                $returnValue = $this->imagen_m->get_by(array("grupo_maestros_id" => $maestro_id, "tipo_imagen_id" => $this->config->item('imagen:small'), "estado" => $this->config->item('estado:publicado')));
                            }
                        }
                        break;
                    default:
                        if ($origen == 'canal') {
                            $returnValue = $this->imagen_m->get_by(array("canales_id" => $maestro_id, "tipo_imagen_id" => $this->config->item('imagen:small'), "estado" => "1"));
                        } else {
                            if ($origen == 'video') {
                                $returnValue = $this->imagen_m->get_by(array("videos_id" => $maestro_id, "tipo_imagen_id" => $this->config->item('imagen:small'), "estado" => "1"));
                            } else {
                                $returnValue = $this->imagen_m->get_by(array("grupo_maestros_id" => $maestro_id, "tipo_imagen_id" => $this->config->item('imagen:small'), "estado" => "1"));
                            }
                        }

                        break;
                }
            }
        }
        return $returnValue;
    }

    public function mostrar_lista_detalle_seccion($canal_id, $seccion_id) {
        if ($this->input->is_ajax_request()) {
            $base_where = array("secciones_id" => $seccion_id, "estado" => $this->config->item('estado:publicado'));
            // Create pagination links
            $total_rows = $this->detalle_secciones_m->count_by($base_where);
            //admin/canales/seccion/7/2637
            $pagination = create_pagination('admin/canales/seccion/' . $canal_id . '/' . $seccion_id . '/index/', $total_rows, 5, 7);
            $lista_detalle_seccion = $this->agregarValores($this->detalle_secciones_m->order_by('peso', 'ASC')->limit($pagination['limit'])->get_many_by($base_where));
            //obtener el último item
            $ultimo = $this->detalle_secciones_m->order_by('peso', 'DESC')->get_by(array("secciones_id" => $seccion_id, "estado" => $this->config->item('estado:publicado')));
            if (count($ultimo) == 0) {
                $objUltimo = new stdClass();
                $objUltimo->id = 0;
                $ultimo = $objUltimo;
            }
            //obtener el primer item
            $primero = $this->detalle_secciones_m->order_by('peso', 'ASC')->get_by(array("secciones_id" => $seccion_id, "estado" => "1"));
            if (count($primero) == 0) {
                $objPrimero = new stdClass();
                $objPrimero->id = 0;
                $primero = $objPrimero;
            }
            $html = '    <thead>
        <tr class="nodrag">
            <th>#</th>
            <th>Imagen</th>
            <th>nombre</th>
            <th>Descripcion</th>
            <th>Tipo</th>
            <th>Posición</th>
            <th>Acción</th>
            <th>ID</th>
        </tr>
    </thead>
    <tbody id="contenido">';
            $objSeccion = $this->secciones_m->get($seccion_id);
            $agregar_descripcion = FALSE;
            if ($objSeccion->tipo_secciones_id == $this->config->item('seccion:destacado')) {
                $agregar_descripcion = TRUE;
            }
            if (count($lista_detalle_seccion) > 0) {
                foreach ($lista_detalle_seccion as $index => $objDetalleSeccion):
                    if ($primero->peso == $objDetalleSeccion->peso):
                        $img = '<img onclick="bajar(' . $objDetalleSeccion->id . ');return false;" title="Bajar" src="' . $this->config->item('url:default_imagen') . 'down.png" class="bajar"  />';
                    elseif ($ultimo->peso == $objDetalleSeccion->peso):
                        $img = '<img onclick="subir(' . $objDetalleSeccion->id . ');return false;" title="Subir" src="' . $this->config->item('url:default_imagen') . 'up.png" class="subir"  />';
                    else:
                        $img = '<img onclick="subir(' . $objDetalleSeccion->id . ');return false;" title="Subir" src="' . $this->config->item('url:default_imagen') . 'up.png" class="subir" />' . '<img onclick="bajar(' . $objDetalleSeccion->id . ');return false;" title="Bajar" src="' . $this->config->item('url:default_imagen') . 'down.png"  class="bajar" />';
                    endif;
                    $html.='<tr id="' . $objDetalleSeccion->id . '_' . $objDetalleSeccion->peso . '">';
                    $html.='<td>' . ($index + 1) . '</td>';
                    $html.='<td><img style="width:100px;" src="' . $objDetalleSeccion->imagen . '" /></td>';
                    $html.='<td>' . $objDetalleSeccion->nombre . '</td>';
                    $html.='<td>';
                    if ($agregar_descripcion) {
                        $html.='<div id="descripcion_' . $objDetalleSeccion->id . '">';
                        $html.=$objDetalleSeccion->descripcion_item;
                        $html.='</div>';
                    } else {
                        $html.=$objDetalleSeccion->descripcion_item;
                    }
                    $html.='<td>';
                    $html.='<td>' . $objDetalleSeccion->tipo . '</td>';
                    $html.='<td><div style="float: left;"><input onkeypress="return ordenar_lista_detalle_secciones(event);" type="text" name="peso_' . $objDetalleSeccion->id . '" id="peso_' . $objDetalleSeccion->id . '" size="2" value="' . $objDetalleSeccion->peso . '" /></div><div style="float: left;" id="img_' . $objDetalleSeccion->id . '">' . $img . '</div></td>';
                    $html.='<td>';
                    $html.='<div style="float: left;">';
                    $html.='<a href="#" class="btn red" onclick="quitarDetalleSeccion(' . $objDetalleSeccion->id . ', ' . $canal_id . ', ' . $seccion_id . '); return false;">Quitar</a>';
                    $html.='</div>';
                    if ($agregar_descripcion) {
                        $html.='<div style="float:left;" id="boton_' . $objDetalleSeccion->id . '">';
                        $html.='<a href="#" class="btn blue" onclick="agregar_descripcion(' . $objDetalleSeccion->id . '); return false;">Agregar descripción</a>';
                        $html.='</div>';
                    }
                    $html.='</td>';
                    $html.='<td>' . $objDetalleSeccion->id . '</td>';
                    $html.='</tr>';
                endforeach;
            } else {
                $html.='<tr class="nodrag">
                <td colspan="8">No hay data</td>
            </tr>';
            }
            $this->template
                    ->set('pagination', $pagination);
            $html.='    </tbody>
            <tfoot>
                <tr class="nodrag">
                    <td colspan="7">
                        <div class="inner"  id="paginacion_secciones">';
            if (!empty($pagination['links'])) {

                $html.='<div class="paginate">';
                $html.=$pagination['links'];
                $html.='</div>';
            }
            $html.='</div>
                    </td>
                </tr>
            </tfoot>';
            echo $html;
        }
    }

    /**
     * Método para mostrar en lista los items para esta seccion
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $current_page
     * @param int $paginado
     */
    public function buscar_para_programa($current_page = 1, $paginado = 0) {
        if ($this->input->is_ajax_request()) {
            $array_maestros = array();
            switch ($this->input->post('tipo_buscar')) {
                case $this->config->item('videos:lista'):
                case $this->config->item('videos:coleccion'):
                    break;
                case $this->config->item('videos:programa'):
                    //listamos todos los maestros
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        $lista_maestros = $this->grupo_maestro_m->like('nombre', $this->input->post('txtBuscar'))->get_many_by(array('canales_id' => $this->input->post('canal_id'), "tipo_grupo_maestro_id" => $this->config->item('videos:programa')));
                    } else {
                        $lista_maestros = $this->grupo_maestro_m->get_many_by(array('canales_id' => $this->input->post('canal_id'), "tipo_grupo_maestro_id" => $this->config->item('videos:programa')));
                    }

                    if (count($lista_maestros) > 0) {

                        foreach ($lista_maestros as $puntero => $objMaestro) {
                            if (count($objMaestro) > 0) {
                                $objMaestro->es_maestro = 1;
                                $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro->tipo_grupo_maestro_id);
                                $objMaestro->tipo = $objTipoMaestro->nombre;
                                array_push($array_maestros, $objMaestro);
                            }
                        }
                    }
                    break;
                case $this->config->item('videos:video'):
                    break;
                case $this->config->item('videos:canal'):
                    break;
                default:
                    //listamos todos los maestros
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        $lista_maestros = $this->grupo_maestro_m->like('nombre', $this->input->post('txtBuscar'))->get_many_by(array('canales_id' => $this->input->post('canal_id'), "tipo_grupo_maestro_id" => $this->config->item('videos:programa')));
                    } else {
                        $lista_maestros = $this->grupo_maestro_m->get_many_by(array('canales_id' => $this->input->post('canal_id'), "tipo_grupo_maestro_id" => $this->config->item('videos:programa')));
                    }

                    if (count($lista_maestros) > 0) {

                        foreach ($lista_maestros as $puntero => $objMaestro) {
                            if (count($objMaestro) > 0) {
                                $objMaestro->es_maestro = 1;
                                $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro->tipo_grupo_maestro_id);
                                $objMaestro->tipo = $objTipoMaestro->nombre;
                                array_push($array_maestros, $objMaestro);
                            }
                        }
                    }
                    break;
            }

            $total = count($array_maestros);
            $cantidad_mostrar = 7;
            $current_page = $current_page - 1;
            $numero_paginas = ceil(count($array_maestros) / $cantidad_mostrar);
            $offset = $current_page * $cantidad_mostrar;
            $array_maestros = array_slice($array_maestros, $offset, $cantidad_mostrar);
            if ($paginado == '1') {
                $returnValue = $this->htmlListaMaestro($array_maestros, $this->input->post('seccion_id'), $this->input->post('canal_id'));
            } else {
                $returnValue = '<table>';
                $returnValue.='<thead>';
                $returnValue.='<tr>';
                $returnValue.='<th>#</th>';
                $returnValue.='<th>imagen</td>';
                $returnValue.='<th>nombre</td>';
                $returnValue.='<th>tipo</td>';
                $returnValue.='<th>acciones</td>';
                $returnValue.='</tr>';
                $returnValue.='</thead>';
                $returnValue.='<tbody id="resultado">';
                $returnValue.= $this->htmlListaMaestro($array_maestros, $this->input->post('seccion_id'), $this->input->post('canal_id'));
                $returnValue.='</tbody>';
                $returnValue.='</table>';
                $returnValue.='<input type="hidden" id="total" name="total" value="' . $total . '" />';
                $returnValue.='<input type="hidden" id="cantidad_mostrar" name="cantidad_mostrar" value="' . $cantidad_mostrar . '" />';
                $returnValue.='<div id="black" style="margin: auto;"></div>';
            }
            echo $returnValue;
        }
    }

    /**
     * Método para buscar items para la seccion coleccion
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $current_page
     * @param int $paginado
     */
    public function buscar_para_coleccion($current_page = 1, $paginado = 0) {
        if ($this->input->is_ajax_request()) {
            $array_maestros = array();
            switch ($this->input->post('tipo_buscar')) {
                case $this->config->item('videos:video'):
                    //obtenemos los videos para listarlos
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        $lista_videos = $this->videos_m->like('titulo', $this->input->post('txtBuscar'))->get_many_by(array('canales_id' => $this->input->post('canal_id')));
                    } else {
                        $lista_videos = $this->videos_m->get_many_by(array('canales_id' => $this->input->post('canal_id')));
                    }
                    if (count($lista_videos) > 0) {
                        foreach ($lista_videos as $index => $objVideo) {
                            $objVideo->es_maestro = 0;
                            $objVideo->tipo = 'Video';
                            array_push($array_maestros, $objVideo);
                        }
                    }
                    break;
                case $this->config->item('videos:canal'):

                    break;
                case $this->config->item('videos:programa'):
                case $this->config->item('videos:coleccion'):
                    break;
                case $this->config->item('videos:lista'):
                    //listamos todos los maestros
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        if ($this->input->post('tipo_buscar')) {
                            $lista_maestros = $this->grupo_maestro_m->where_in('tipo_grupo_maestro_id', $this->input->post('tipo_buscar'))->like('nombre', $this->input->post('txtBuscar'))->get_many_by(array('canales_id' => $this->input->post('canal_id'), "tipo_grupo_maestro_id" => $this->config->item('videos:lista')));
                        } else {
                            $lista_maestros = $this->grupo_maestro_m->like('nombre', $this->input->post('txtBuscar'))->get_many_by(array('canales_id' => $this->input->post('canal_id'), "tipo_grupo_maestro_id" => $this->config->item('videos:lista')));
                        }
                    } else {
                        if ($this->input->post('tipo_buscar')) {
                            $lista_maestros = $this->grupo_maestro_m->where_in('tipo_grupo_maestro_id', $this->input->post('tipo_buscar'))->get_many_by(array('canales_id' => $this->input->post('canal_id'), "tipo_grupo_maestro_id" => $this->config->item('videos:lista')));
                        } else {
                            $lista_maestros = $this->grupo_maestro_m->get_many_by(array('canales_id' => $this->input->post('canal_id'), "tipo_grupo_maestro_id" => $this->config->item('videos:lista')));
                        }
                    }

                    if (count($lista_maestros) > 0) {
                        foreach ($lista_maestros as $puntero => $objMaestro) {
                            if (count($objMaestro) > 0) {
                                $objMaestro->es_maestro = 1;
                                $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro->tipo_grupo_maestro_id);
                                $objMaestro->tipo = $objTipoMaestro->nombre;
                                array_push($array_maestros, $objMaestro);
                            }
                        }
                    }
                    break;
                default:
                    //listamos todos los maestros
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        $lista_maestros = $this->grupo_maestro_m->like('nombre', $this->input->post('txtBuscar'))->get_many_by(array('canales_id' => $this->input->post('canal_id'), "tipo_grupo_maestro_id" => $this->config->item('videos:lista')));
                    } else {
                        $lista_maestros = $this->grupo_maestro_m->get_many_by(array('canales_id' => $this->input->post('canal_id'), "tipo_grupo_maestro_id" => $this->config->item('videos:lista')));
                    }

                    if (count($lista_maestros) > 0) {
                        foreach ($lista_maestros as $puntero => $objMaestro) {
                            if (count($objMaestro) > 0) {
                                $objMaestro->es_maestro = 1;
                                $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro->tipo_grupo_maestro_id);
                                $objMaestro->tipo = $objTipoMaestro->nombre;
                                array_push($array_maestros, $objMaestro);
                            }
                        }
                    }
                    //obtenemos los videos para listarlos
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        $lista_videos = $this->videos_m->like('titulo', $this->input->post('txtBuscar'))->get_many_by(array('canales_id' => $this->input->post('canal_id')));
                    } else {
                        $lista_videos = $this->videos_m->get_many_by(array('canales_id' => $this->input->post('canal_id')));
                    }
                    if (count($lista_videos) > 0) {
                        foreach ($lista_videos as $index => $objVideo) {
                            $objVideo->es_maestro = 0;
                            $objVideo->tipo = 'Video';
                            array_push($array_maestros, $objVideo);
                        }
                    }

                    break;
            }

            $total = count($array_maestros);
            $cantidad_mostrar = 7;
            $current_page = $current_page - 1;
            $numero_paginas = ceil(count($array_maestros) / $cantidad_mostrar);
            $offset = $current_page * $cantidad_mostrar;
            $array_maestros = array_slice($array_maestros, $offset, $cantidad_mostrar);
            if ($paginado == '1') {
                $returnValue = $this->htmlListaMaestro($array_maestros, $this->input->post('seccion_id'), $this->input->post('canal_id'));
            } else {
                $returnValue = '<table>';
                $returnValue.='<thead>';
                $returnValue.='<tr>';
                $returnValue.='<th>#</th>';
                $returnValue.='<th>imagen</td>';
                $returnValue.='<th>nombre</td>';
                $returnValue.='<th>tipo</td>';
                $returnValue.='<th>acciones</td>';
                $returnValue.='</tr>';
                $returnValue.='</thead>';
                $returnValue.='<tbody id="resultado">';
                $returnValue.= $this->htmlListaMaestro($array_maestros, $this->input->post('seccion_id'), $this->input->post('canal_id'));
                $returnValue.='</tbody>';
                $returnValue.='</table>';
                $returnValue.='<input type="hidden" id="total" name="total" value="' . $total . '" />';
                $returnValue.='<input type="hidden" id="cantidad_mostrar" name="cantidad_mostrar" value="' . $cantidad_mostrar . '" />';
                $returnValue.='<div id="black" style="margin: auto;"></div>';
            }
            echo $returnValue;
        }
    }

    /**
     * Método para mostrar en lista los items para esta seccion
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $current_page
     * @param int $paginado
     */
    public function buscar_para_lista($current_page = 1, $paginado = 0) {
        if ($this->input->is_ajax_request()) {
            $array_maestros = array();
            switch ($this->input->post('tipo_buscar')) {
                case $this->config->item('videos:canal'):
                    break;
                case $this->config->item('videos:video'):
                    break;
                case $this->config->item('videos:programa'):
                    break;
                case $this->config->item('videos:coleccion'):
                    break;
                case $this->config->item('videos:lista'):
                    //listamos todos los maestros
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        $lista_maestros = $this->grupo_maestro_m->like('nombre', $this->input->post('txtBuscar'))->get_many_by(array('canales_id' => $this->input->post('canal_id'), "tipo_grupo_maestro_id" => $this->config->item('videos:lista')));
                    } else {
                        $lista_maestros = $this->grupo_maestro_m->get_many_by(array('canales_id' => $this->input->post('canal_id'), "tipo_grupo_maestro_id" => $this->config->item('videos:lista')));
                    }

                    if (count($lista_maestros) > 0) {

                        foreach ($lista_maestros as $puntero => $objMaestro) {
                            if (count($objMaestro) > 0) {
                                $objMaestro->es_maestro = 1;
                                $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro->tipo_grupo_maestro_id);
                                $objMaestro->tipo = $objTipoMaestro->nombre;
                                array_push($array_maestros, $objMaestro);
                            }
                        }
                    }
                    break;
                default:
                    //listamos todos los maestros
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        $lista_maestros = $this->grupo_maestro_m->like('nombre', $this->input->post('txtBuscar'))->get_many_by(array('canales_id' => $this->input->post('canal_id'), "tipo_grupo_maestro_id" => $this->config->item('videos:lista')));
                    } else {
                        $lista_maestros = $this->grupo_maestro_m->get_many_by(array('canales_id' => $this->input->post('canal_id'), "tipo_grupo_maestro_id" => $this->config->item('videos:lista')));
                    }

                    if (count($lista_maestros) > 0) {

                        foreach ($lista_maestros as $puntero => $objMaestro) {
                            if (count($objMaestro) > 0) {
                                $objMaestro->es_maestro = 1;
                                $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro->tipo_grupo_maestro_id);
                                $objMaestro->tipo = $objTipoMaestro->nombre;
                                array_push($array_maestros, $objMaestro);
                            }
                        }
                    }
                    break;
            }

            $total = count($array_maestros);
            $cantidad_mostrar = 7;
            $current_page = $current_page - 1;
            $numero_paginas = ceil(count($array_maestros) / $cantidad_mostrar);
            $offset = $current_page * $cantidad_mostrar;
            $array_maestros = array_slice($array_maestros, $offset, $cantidad_mostrar);
            if ($paginado == '1') {
                $returnValue = $this->htmlListaMaestro($array_maestros, $this->input->post('seccion_id'), $this->input->post('canal_id'));
            } else {
                $returnValue = '<table>';
                $returnValue.='<thead>';
                $returnValue.='<tr>';
                $returnValue.='<th>#</th>';
                $returnValue.='<th>imagen</td>';
                $returnValue.='<th>nombre</td>';
                $returnValue.='<th>tipo</td>';
                $returnValue.='<th>acciones</td>';
                $returnValue.='</tr>';
                $returnValue.='</thead>';
                $returnValue.='<tbody id="resultado">';
                $returnValue.= $this->htmlListaMaestro($array_maestros, $this->input->post('seccion_id'), $this->input->post('canal_id'));
                $returnValue.='</tbody>';
                $returnValue.='</table>';
                $returnValue.='<input type="hidden" id="total" name="total" value="' . $total . '" />';
                $returnValue.='<input type="hidden" id="cantidad_mostrar" name="cantidad_mostrar" value="' . $cantidad_mostrar . '" />';
                $returnValue.='<div id="black" style="margin: auto;"></div>';
            }
            echo $returnValue;
        }
    }

    /**
     * Método para mostrar en lista los items para esta seccion
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $current_page
     * @param int $paginado
     */
    public function buscar_para_video($current_page = 1, $paginado = 0) {
        if ($this->input->is_ajax_request()) {
            $array_maestros = array();
            switch ($this->input->post('tipo_buscar')) {
                case $this->config->item('videos:lista'):
                case $this->config->item('videos:coleccion'):
                case $this->config->item('videos:canal'):
                case $this->config->item('videos:programa'):
                    break;
                case $this->config->item('videos:video'):
                    //obtenemos los videos para listarlos
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        $lista_videos = $this->videos_m->like('titulo', $this->input->post('txtBuscar'))->get_many_by(array('canales_id' => $this->input->post('canal_id')));
                    } else {
                        $lista_videos = $this->videos_m->get_many_by(array('canales_id' => $this->input->post('canal_id')));
                    }
                    if (count($lista_videos) > 0) {
                        foreach ($lista_videos as $index => $objVideo) {
                            $objVideo->es_maestro = 0;
                            $objVideo->tipo = 'Video';
                            array_push($array_maestros, $objVideo);
                        }
                    }
                    break;
                default:
                    //obtenemos los videos para listarlos
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        $lista_videos = $this->videos_m->like('titulo', $this->input->post('txtBuscar'))->get_many_by(array('canales_id' => $this->input->post('canal_id')));
                    } else {
                        $lista_videos = $this->videos_m->get_many_by(array('canales_id' => $this->input->post('canal_id')));
                    }
                    if (count($lista_videos) > 0) {
                        foreach ($lista_videos as $index => $objVideo) {
                            $objVideo->es_maestro = 0;
                            $objVideo->tipo = 'Video';
                            array_push($array_maestros, $objVideo);
                        }
                    }
                    break;
            }

            $total = count($array_maestros);
            $cantidad_mostrar = 7;
            $current_page = $current_page - 1;
            $numero_paginas = ceil(count($array_maestros) / $cantidad_mostrar);
            $offset = $current_page * $cantidad_mostrar;
            $array_maestros = array_slice($array_maestros, $offset, $cantidad_mostrar);
            if ($paginado == '1') {
                $returnValue = $this->htmlListaMaestro($array_maestros, $this->input->post('seccion_id'), $this->input->post('canal_id'));
            } else {
                $returnValue = '<table>';
                $returnValue.='<thead>';
                $returnValue.='<tr>';
                $returnValue.='<th>#</th>';
                $returnValue.='<th>imagen</td>';
                $returnValue.='<th>nombre</td>';
                $returnValue.='<th>tipo</td>';
                $returnValue.='<th>acciones</td>';
                $returnValue.='</tr>';
                $returnValue.='</thead>';
                $returnValue.='<tbody id="resultado">';
                $returnValue.= $this->htmlListaMaestro($array_maestros, $this->input->post('seccion_id'), $this->input->post('canal_id'));
                $returnValue.='</tbody>';
                $returnValue.='</table>';
                $returnValue.='<input type="hidden" id="total" name="total" value="' . $total . '" />';
                $returnValue.='<input type="hidden" id="cantidad_mostrar" name="cantidad_mostrar" value="' . $cantidad_mostrar . '" />';
                $returnValue.='<div id="black" style="margin: auto;"></div>';
            }
            echo $returnValue;
        }
    }

    /**
     * Método para listar items para las secciones los más recientes, los más buscados, los + etc
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $current_page
     * @param int $paginado
     */
    public function buscar_para_losmas($current_page = 1, $paginado = 0) {
        if ($this->input->is_ajax_request()) {
            //listamos todos los maestros
            $array_maestros = array();
            /* if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
              $lista_maestros = $this->grupo_maestro_m->like('nombre', $this->input->post('txtBuscar'))->get_many_by(array('canales_id' => $this->input->post('canal_id'), "tipo_grupo_maestro_id" => $this->config->item('videos:lista')));
              } else {
              $lista_maestros = $this->grupo_maestro_m->get_many_by(array('canales_id' => $this->input->post('canal_id'), "tipo_grupo_maestro_id" => $this->config->item('videos:lista')));
              } */

            //if (count($lista_maestros) > 0) {

            /* foreach ($lista_maestros as $puntero => $objMaestro) {
              if (count($objMaestro) > 0) {
              $objMaestro->es_maestro = 1;
              $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro->tipo_grupo_maestro_id);
              $objMaestro->tipo = $objTipoMaestro->nombre;
              array_push($array_maestros, $objMaestro);
              }
              } */
            switch ($this->input->post('tipo_buscar')) {
                case $this->config->item('videos:programa'):
                case $this->config->item('videos:coleccion'):
                case $this->config->item('videos:lista'):
                case $this->config->item('videos:canal'):
                    break;
                case $this->config->item('videos:video'):
                    //obtenemos los videos para listarlos
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        $lista_videos = $this->videos_m->like('titulo', $this->input->post('txtBuscar'))->get_many_by(array('canales_id' => $this->input->post('canal_id')));
                    } else {
                        $lista_videos = $this->videos_m->get_many_by(array('canales_id' => $this->input->post('canal_id')));
                    }
                    if (count($lista_videos) > 0) {
                        foreach ($lista_videos as $index => $objVideo) {
                            $objVideo->es_maestro = 0;
                            $objVideo->tipo = 'Video';
                            array_push($array_maestros, $objVideo);
                        }
                    }
                default:
                    //obtenemos los videos para listarlos
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        $lista_videos = $this->videos_m->like('titulo', $this->input->post('txtBuscar'))->get_many_by(array('canales_id' => $this->input->post('canal_id')));
                    } else {
                        $lista_videos = $this->videos_m->get_many_by(array('canales_id' => $this->input->post('canal_id')));
                    }
                    if (count($lista_videos) > 0) {
                        foreach ($lista_videos as $index => $objVideo) {
                            $objVideo->es_maestro = 0;
                            $objVideo->tipo = 'Video';
                            array_push($array_maestros, $objVideo);
                        }
                    }
                    break;
            }

            //}
            $total = count($array_maestros);
            $cantidad_mostrar = 7;
            $current_page = $current_page - 1;
            $numero_paginas = ceil(count($array_maestros) / $cantidad_mostrar);
            $offset = $current_page * $cantidad_mostrar;
            $array_maestros = array_slice($array_maestros, $offset, $cantidad_mostrar);
            if ($paginado == '1') {
                $returnValue = $this->htmlListaMaestro($array_maestros, $this->input->post('seccion_id'), $this->input->post('canal_id'));
            } else {
                $returnValue = '<table>';
                $returnValue.='<thead>';
                $returnValue.='<tr>';
                $returnValue.='<th>#</th>';
                $returnValue.='<th>imagen</td>';
                $returnValue.='<th>nombre</td>';
                $returnValue.='<th>tipo</td>';
                $returnValue.='<th>acciones</td>';
                $returnValue.='</tr>';
                $returnValue.='</thead>';
                $returnValue.='<tbody id="resultado">';
                $returnValue.= $this->htmlListaMaestro($array_maestros, $this->input->post('seccion_id'), $this->input->post('canal_id'));
                $returnValue.='</tbody>';
                $returnValue.='</table>';
                $returnValue.='<input type="hidden" id="total" name="total" value="' . $total . '" />';
                $returnValue.='<input type="hidden" id="cantidad_mostrar" name="cantidad_mostrar" value="' . $cantidad_mostrar . '" />';
                $returnValue.='<div id="black" style="margin: auto;"></div>';
            }
            echo $returnValue;
        }
    }

    /**
     * Método para listar los items de la sección correspondiente
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $current_page
     * @param int $paginado
     */
    public function buscar_para_destacado_programa($current_page = 1, $paginado = 0) {
        if ($this->input->is_ajax_request()) {
            $objSeccion = $this->secciones_m->get($this->input->post('seccion_id'));
            $objPortada = $this->portada_m->get($objSeccion->portadas_id);
            $programa_id = $objPortada->origen_id;
            $objMaestro = $this->grupo_maestro_m->get($programa_id);
            //listamos todos los maestros
            $array_maestros = array();
            if (count($objMaestro) > 0) {
                $objMaestro->es_maestro = 1;
                $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro->tipo_grupo_maestro_id);
                $objMaestro->tipo = $objTipoMaestro->nombre;
                array_push($array_maestros, $objMaestro);
            }
            $total = count($array_maestros);
            $cantidad_mostrar = 7;
            $current_page = $current_page - 1;
            $numero_paginas = ceil(count($array_maestros) / $cantidad_mostrar);
            $offset = $current_page * $cantidad_mostrar;
            $array_maestros = array_slice($array_maestros, $offset, $cantidad_mostrar);
            if ($paginado == '1') {
                $returnValue = $this->htmlListaMaestro($array_maestros, $this->input->post('seccion_id'), $this->input->post('canal_id'));
            } else {
                $returnValue = '<table>';
                $returnValue.='<thead>';
                $returnValue.='<tr>';
                $returnValue.='<th>#</th>';
                $returnValue.='<th>imagen</td>';
                $returnValue.='<th>nombre</td>';
                $returnValue.='<th>tipo</td>';
                $returnValue.='<th>acciones</td>';
                $returnValue.='</tr>';
                $returnValue.='</thead>';
                $returnValue.='<tbody id="resultado">';
                $returnValue.= $this->htmlListaMaestro($array_maestros, $this->input->post('seccion_id'), $this->input->post('canal_id'));
                $returnValue.='</tbody>';
                $returnValue.='</table>';
                $returnValue.='<input type="hidden" id="total" name="total" value="' . $total . '" />';
                $returnValue.='<input type="hidden" id="cantidad_mostrar" name="cantidad_mostrar" value="' . $cantidad_mostrar . '" />';
                $returnValue.='<div id="black" style="margin: auto;"></div>';
            }
            echo $returnValue;
        }
    }

    /**
     * Método para obtener la lista directas de programas
     * @param int $programa_id
     * @return array
     */
    public function obtenerListaPrograma($programa_id) {
        $returnValue = array();
        $detalle_programa = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $programa_id, "tipo_grupo_maestros_id" => $this->config->item('videos:programa')));
        if (count($detalle_programa) > 0) {
            foreach ($detalle_programa as $puntero => $objDetalle) {
                if ($objDetalle->grupo_maestro_id != NULL) {
                    $detalle_coleccion = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $objDetalle->grupo_maestro_id, "tipo_grupo_maestros_id" => $this->config->item('videos:coleccion')));
                    if (count($detalle_coleccion) > 0) {
                        foreach ($detalle_coleccion as $index => $objDetalleColeccion) {
                            if ($objDetalleColeccion->grupo_maestro_id != NULL) {
                                $lista_lista = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $objDetalleColeccion->grupo_maestro_id, "tipo_grupo_maestros_id" => $this->config->item('videos:lista')));
                                if (count($lista_lista) > 0) {
                                    foreach ($lista_lista as $indice => $objDetalleLista) {
                                        $objLista = $this->grupo_maestro_m->get($objDetalleLista->grupo_maestro_padre);
                                        if (count($objLista) > 0) {
                                            array_push($returnValue, $objLista);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $returnValue;
    }

    public function obtenerListaProgramaDirecta($programa_id) {
        $returnValue = array();
        $detalle_programa = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $programa_id, "tipo_grupo_maestros_id" => $this->config->item('videos:programa')));
        if (count($detalle_programa) > 0) {
            foreach ($detalle_programa as $puntero => $objDetalle) {
                if ($objDetalle->grupo_maestro_id != NULL) {
                    $objMaestro = $this->grupo_maestro_m->get($objDetalle->grupo_maestro_id);
                    if (count($objMaestro) > 0) {
                        if ($objMaestro->tipo_grupo_maestro_id == $this->config->item('videos:lista')) {
                            array_push($returnValue, $objMaestro);
                        }
                    }
                }
            }
        }
        return $returnValue;
    }

    public function buscar_para_coleccion_programa($current_page = 1, $paginado = 0) {
        if ($this->input->is_ajax_request()) {
            $objSeccion = $this->secciones_m->get($this->input->post('seccion_id'));
            $objPortada = $this->portada_m->get($objSeccion->portadas_id);
            $programa_id = $objPortada->origen_id;
            $array_maestros = array();
            $array_id_item = array();
            switch ($this->input->post('tipo_buscar')) {
                case $this->config->item('videos:programa'):
                    break;
                case $this->config->item('videos:coleccion'):
                    break;
                case $this->config->item('videos:lista'):
                    //listamos todas las listas de las colecciones del programa
                    $lista_programa = $this->obtenerListaPrograma($programa_id);
                    if (count($lista_programa) > 0) {
                        foreach ($lista_programa as $puntero => $objMaestro) {
                            if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                                if (stristr($objMaestro->nombre, $this->input->post('txtBuscar'))) {
                                    $objMaestro->es_maestro = 1;
                                    $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro->tipo_grupo_maestro_id);
                                    $objMaestro->tipo = $objTipoMaestro->nombre;
                                    array_push($array_maestros, $objMaestro);
                                    array_push($array_id_item, $objMaestro->id);
                                }
                            } else {
                                $objMaestro->es_maestro = 1;
                                $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro->tipo_grupo_maestro_id);
                                $objMaestro->tipo = $objTipoMaestro->nombre;
                                array_push($array_maestros, $objMaestro);
                                array_push($array_id_item, $objMaestro->id);
                            }
                        }
                    }
                    //listamos las listas diractas al programa
                    $lista_directas_programa = $this->obtenerListaProgramaDirecta($programa_id);
                    if (count($lista_directas_programa) > 0) {
                        foreach ($lista_directas_programa as $indice => $objMaestro2) {
                            if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                                if (stristr($objMaestro2->nombre, $this->input->post('txtBuscar'))) {
                                    $objMaestro2->es_maestro = 1;
                                    $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro2->tipo_grupo_maestro_id);
                                    $objMaestro2->tipo = $objTipoMaestro->nombre;
                                    array_push($array_maestros, $objMaestro2);
                                    array_push($array_id_item, $objMaestro2->id);
                                }
                            } else {
                                $objMaestro2->es_maestro = 1;
                                $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro2->tipo_grupo_maestro_id);
                                $objMaestro2->tipo = $objTipoMaestro->nombre;
                                array_push($array_maestros, $objMaestro2);
                                array_push($array_id_item, $objMaestro2->id);
                            }
                        }
                    }
                    //limpiamos las listas repetidas
                    if (count($array_id_item) > 0) {
                        $array_id_item = array_unique($array_id_item);
                        $array_maestros = array();
                        foreach ($array_id_item as $in => $cod_maestro) {
                            $objMaestro3 = $this->grupo_maestro_m->get($cod_maestro);
                            $objMaestro3->es_maestro = 1;
                            $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro3->tipo_grupo_maestro_id);
                            $objMaestro3->tipo = $objTipoMaestro->nombre;
                            array_push($array_maestros, $objMaestro3);
                        }
                    }
                    break;
                case $this->config->item('videos:video'):
                    //listamos los videos del programa
                    $detalle_videos_programa = $this->obtenerVideosPrograma($programa_id);
                    if (count($detalle_videos_programa) > 0) {
                        foreach ($detalle_videos_programa as $index => $objDetalleMaestro) {
                            $objVideo = $this->videos_m->get($objDetalleMaestro->video_id);
                            if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                                if (stristr($objVideo->titulo, $this->input->post('txtBuscar'))) {
                                    $objVideo->es_maestro = 0;
                                    $objVideo->tipo = 'Video';
                                    array_push($array_maestros, $objVideo);
                                }
                            } else {
                                $objVideo->es_maestro = 0;
                                $objVideo->tipo = 'Video';
                                array_push($array_maestros, $objVideo);
                            }
                        }
                    }
                    break;
                case $this->config->item('videos:canal'):
                    break;
                default:
                    //listamos todas las listas de las colecciones del programa
                    $lista_programa = $this->obtenerListaPrograma($programa_id);
                    if (count($lista_programa) > 0) {
                        foreach ($lista_programa as $puntero => $objMaestro) {
                            if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                                if (stristr($objMaestro->nombre, $this->input->post('txtBuscar'))) {
                                    $objMaestro->es_maestro = 1;
                                    $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro->tipo_grupo_maestro_id);
                                    $objMaestro->tipo = $objTipoMaestro->nombre;
                                    array_push($array_maestros, $objMaestro);
                                    array_push($array_id_item, $objMaestro->id);
                                }
                            } else {
                                $objMaestro->es_maestro = 1;
                                $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro->tipo_grupo_maestro_id);
                                $objMaestro->tipo = $objTipoMaestro->nombre;
                                array_push($array_maestros, $objMaestro);
                                array_push($array_id_item, $objMaestro->id);
                            }
                        }
                    }
                    //listamos las listas diractas al programa
                    $lista_directas_programa = $this->obtenerListaProgramaDirecta($programa_id);
                    if (count($lista_directas_programa) > 0) {
                        foreach ($lista_directas_programa as $indice => $objMaestro2) {
                            if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                                if (stristr($objMaestro2->nombre, $this->input->post('txtBuscar'))) {
                                    $objMaestro2->es_maestro = 1;
                                    $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro2->tipo_grupo_maestro_id);
                                    $objMaestro2->tipo = $objTipoMaestro->nombre;
                                    array_push($array_maestros, $objMaestro2);
                                    array_push($array_id_item, $objMaestro2->id);
                                }
                            } else {
                                $objMaestro2->es_maestro = 1;
                                $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro2->tipo_grupo_maestro_id);
                                $objMaestro2->tipo = $objTipoMaestro->nombre;
                                array_push($array_maestros, $objMaestro2);
                                array_push($array_id_item, $objMaestro2->id);
                            }
                        }
                    }
                    //limpiamos las listas repetidas
                    if (count($array_id_item) > 0) {
                        $array_id_item = array_unique($array_id_item);
                        $array_maestros = array();
                        foreach ($array_id_item as $in => $cod_maestro) {
                            $objMaestro3 = $this->grupo_maestro_m->get($cod_maestro);
                            $objMaestro3->es_maestro = 1;
                            $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro3->tipo_grupo_maestro_id);
                            $objMaestro3->tipo = $objTipoMaestro->nombre;
                            array_push($array_maestros, $objMaestro3);
                        }
                    }
                    //listamos los videos del programa
                    $detalle_videos_programa = $this->obtenerVideosPrograma($programa_id);
                    if (count($detalle_videos_programa) > 0) {
                        foreach ($detalle_videos_programa as $index => $objDetalleMaestro) {
                            $objVideo = $this->videos_m->get($objDetalleMaestro->video_id);
                            if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                                if (stristr($objVideo->titulo, $this->input->post('txtBuscar'))) {
                                    $objVideo->es_maestro = 0;
                                    $objVideo->tipo = 'Video';
                                    array_push($array_maestros, $objVideo);
                                }
                            } else {
                                $objVideo->es_maestro = 0;
                                $objVideo->tipo = 'Video';
                                array_push($array_maestros, $objVideo);
                            }
                        }
                    }
                    break;
            }

            $total = count($array_maestros);
            $cantidad_mostrar = 7;
            $current_page = $current_page - 1;
            $numero_paginas = ceil(count($array_maestros) / $cantidad_mostrar);
            $offset = $current_page * $cantidad_mostrar;
            $array_maestros = array_slice($array_maestros, $offset, $cantidad_mostrar);
            if ($paginado == '1') {
                $returnValue = $this->htmlListaMaestro($array_maestros, $this->input->post('seccion_id'), $this->input->post('canal_id'));
            } else {
                $returnValue = '<table>';
                $returnValue.='<thead>';
                $returnValue.='<tr>';
                $returnValue.='<th>#</th>';
                $returnValue.='<th>imagen</td>';
                $returnValue.='<th>nombre</td>';
                $returnValue.='<th>tipo</td>';
                $returnValue.='<th>acciones</td>';
                $returnValue.='</tr>';
                $returnValue.='</thead>';
                $returnValue.='<tbody id="resultado">';
                $returnValue.= $this->htmlListaMaestro($array_maestros, $this->input->post('seccion_id'), $this->input->post('canal_id'));
                $returnValue.='</tbody>';
                $returnValue.='</table>';
                $returnValue.='<input type="hidden" id="total" name="total" value="' . $total . '" />';
                $returnValue.='<input type="hidden" id="cantidad_mostrar" name="cantidad_mostrar" value="' . $cantidad_mostrar . '" />';
                $returnValue.='<div id="black" style="margin: auto;"></div>';
            }
            echo $returnValue;
        }
    }

    /**
     * Método para mostrar en lista los items para esta seccion
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $current_page
     * @param int $paginado
     */
    public function buscar_para_lista_programa($current_page = 1, $paginado = 0) {
        if ($this->input->is_ajax_request()) {
            $objSeccion = $this->secciones_m->get($this->input->post('seccion_id'));
            $objPortada = $this->portada_m->get($objSeccion->portadas_id);
            $programa_id = $objPortada->origen_id;
            $array_maestros = array();
            $array_id_item = array();
            switch ($this->input->post('tipo_buscar')) {
                case $this->config->item('videos:canal'):
                case $this->config->item('videos:video'):
                case $this->config->item('videos:programa'):
                case $this->config->item('videos:coleccion'):
                    break;
                case $this->config->item('videos:lista'):
                    //listamos las listas diractas al programa
                    $lista_directas_programa = $this->obtenerListaProgramaDirecta($programa_id);
                    if (count($lista_directas_programa) > 0) {
                        foreach ($lista_directas_programa as $indice => $objMaestro2) {
                            if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                                if (stristr($objMaestro2->nombre, $this->input->post('txtBuscar'))) {
                                    $objMaestro2->es_maestro = 1;
                                    $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro2->tipo_grupo_maestro_id);
                                    $objMaestro2->tipo = $objTipoMaestro->nombre;
                                    array_push($array_maestros, $objMaestro2);
                                    array_push($array_id_item, $objMaestro2->id);
                                }
                            } else {
                                $objMaestro2->es_maestro = 1;
                                $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro2->tipo_grupo_maestro_id);
                                $objMaestro2->tipo = $objTipoMaestro->nombre;
                                array_push($array_maestros, $objMaestro2);
                                array_push($array_id_item, $objMaestro2->id);
                            }
                        }
                    }
                    //limpiamos las listas repetidas
                    if (count($array_id_item) > 0) {
                        $array_id_item = array_unique($array_id_item);
                        $array_maestros = array();
                        foreach ($array_id_item as $in => $cod_maestro) {
                            $objMaestro3 = $this->grupo_maestro_m->get($cod_maestro);
                            $objMaestro3->es_maestro = 1;
                            $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro3->tipo_grupo_maestro_id);
                            $objMaestro3->tipo = $objTipoMaestro->nombre;
                            array_push($array_maestros, $objMaestro3);
                        }
                    }
                    break;
                default:
                    //listamos las listas diractas al programa
                    $lista_directas_programa = $this->obtenerListaProgramaDirecta($programa_id);
                    if (count($lista_directas_programa) > 0) {
                        foreach ($lista_directas_programa as $indice => $objMaestro2) {
                            if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                                if (stristr($objMaestro2->nombre, $this->input->post('txtBuscar'))) {
                                    $objMaestro2->es_maestro = 1;
                                    $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro2->tipo_grupo_maestro_id);
                                    $objMaestro2->tipo = $objTipoMaestro->nombre;
                                    array_push($array_maestros, $objMaestro2);
                                    array_push($array_id_item, $objMaestro2->id);
                                }
                            } else {
                                $objMaestro2->es_maestro = 1;
                                $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro2->tipo_grupo_maestro_id);
                                $objMaestro2->tipo = $objTipoMaestro->nombre;
                                array_push($array_maestros, $objMaestro2);
                                array_push($array_id_item, $objMaestro2->id);
                            }
                        }
                    }
                    //limpiamos las listas repetidas
                    if (count($array_id_item) > 0) {
                        $array_id_item = array_unique($array_id_item);
                        $array_maestros = array();
                        foreach ($array_id_item as $in => $cod_maestro) {
                            $objMaestro3 = $this->grupo_maestro_m->get($cod_maestro);
                            $objMaestro3->es_maestro = 1;
                            $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro3->tipo_grupo_maestro_id);
                            $objMaestro3->tipo = $objTipoMaestro->nombre;
                            array_push($array_maestros, $objMaestro3);
                        }
                    }
                    break;
            }

            $total = count($array_maestros);
            $cantidad_mostrar = 7;
            $current_page = $current_page - 1;
            $numero_paginas = ceil(count($array_maestros) / $cantidad_mostrar);
            $offset = $current_page * $cantidad_mostrar;
            $array_maestros = array_slice($array_maestros, $offset, $cantidad_mostrar);
            if ($paginado == '1') {
                $returnValue = $this->htmlListaMaestro($array_maestros, $this->input->post('seccion_id'), $this->input->post('canal_id'));
            } else {
                $returnValue = '<table>';
                $returnValue.='<thead>';
                $returnValue.='<tr>';
                $returnValue.='<th>#</th>';
                $returnValue.='<th>imagen</td>';
                $returnValue.='<th>nombre</td>';
                $returnValue.='<th>tipo</td>';
                $returnValue.='<th>acciones</td>';
                $returnValue.='</tr>';
                $returnValue.='</thead>';
                $returnValue.='<tbody id="resultado">';
                $returnValue.= $this->htmlListaMaestro($array_maestros, $this->input->post('seccion_id'), $this->input->post('canal_id'));
                $returnValue.='</tbody>';
                $returnValue.='</table>';
                $returnValue.='<input type="hidden" id="total" name="total" value="' . $total . '" />';
                $returnValue.='<input type="hidden" id="cantidad_mostrar" name="cantidad_mostrar" value="' . $cantidad_mostrar . '" />';
                $returnValue.='<div id="black" style="margin: auto;"></div>';
            }
            echo $returnValue;
        }
    }

    /**
     * Método para mostrar en lista los items para esta seccion
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $current_page
     * @param int $paginado
     */
    public function buscar_para_video_programa($current_page = 1, $paginado = 0) {
        if ($this->input->is_ajax_request()) {
            $objSeccion = $this->secciones_m->get($this->input->post('seccion_id'));
            $objPortada = $this->portada_m->get($objSeccion->portadas_id);
            $programa_id = $objPortada->origen_id;
            $array_maestros = array();
            switch ($this->input->post('tipo_buscar')) {
                case $this->config->item('videos:lista'):
                case $this->config->item('videos:coleccion'):
                case $this->config->item('videos:canal'):
                case $this->config->item('videos:programa'):
                    break;
                case $this->config->item('videos:video'):
                    //listamos los videos del programa
                    $detalle_videos_programa = $this->obtenerVideosPrograma($programa_id);
                    if (count($detalle_videos_programa) > 0) {
                        foreach ($detalle_videos_programa as $index => $objDetalleMaestro) {
                            $objVideo = $this->videos_m->get($objDetalleMaestro->video_id);
                            if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                                if (stristr($objVideo->titulo, $this->input->post('txtBuscar'))) {
                                    $objVideo->es_maestro = 0;
                                    $objVideo->tipo = 'Video';
                                    array_push($array_maestros, $objVideo);
                                }
                            } else {
                                $objVideo->es_maestro = 0;
                                $objVideo->tipo = 'Video';
                                array_push($array_maestros, $objVideo);
                            }
                        }
                    }
                    break;
                default:
                    //listamos los videos del programa
                    $detalle_videos_programa = $this->obtenerVideosPrograma($programa_id);
                    if (count($detalle_videos_programa) > 0) {
                        foreach ($detalle_videos_programa as $index => $objDetalleMaestro) {
                            $objVideo = $this->videos_m->get($objDetalleMaestro->video_id);
                            if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                                if (stristr($objVideo->titulo, $this->input->post('txtBuscar'))) {
                                    $objVideo->es_maestro = 0;
                                    $objVideo->tipo = 'Video';
                                    array_push($array_maestros, $objVideo);
                                }
                            } else {
                                $objVideo->es_maestro = 0;
                                $objVideo->tipo = 'Video';
                                array_push($array_maestros, $objVideo);
                            }
                        }
                    }
                    break;
            }

            $total = count($array_maestros);
            $cantidad_mostrar = 7;
            $current_page = $current_page - 1;
            $numero_paginas = ceil(count($array_maestros) / $cantidad_mostrar);
            $offset = $current_page * $cantidad_mostrar;
            $array_maestros = array_slice($array_maestros, $offset, $cantidad_mostrar);
            if ($paginado == '1') {
                $returnValue = $this->htmlListaMaestro($array_maestros, $this->input->post('seccion_id'), $this->input->post('canal_id'));
            } else {
                $returnValue = '<table>';
                $returnValue.='<thead>';
                $returnValue.='<tr>';
                $returnValue.='<th>#</th>';
                $returnValue.='<th>imagen</td>';
                $returnValue.='<th>nombre</td>';
                $returnValue.='<th>tipo</td>';
                $returnValue.='<th>acciones</td>';
                $returnValue.='</tr>';
                $returnValue.='</thead>';
                $returnValue.='<tbody id="resultado">';
                $returnValue.= $this->htmlListaMaestro($array_maestros, $this->input->post('seccion_id'), $this->input->post('canal_id'));
                $returnValue.='</tbody>';
                $returnValue.='</table>';
                $returnValue.='<input type="hidden" id="total" name="total" value="' . $total . '" />';
                $returnValue.='<input type="hidden" id="cantidad_mostrar" name="cantidad_mostrar" value="' . $cantidad_mostrar . '" />';
                $returnValue.='<div id="black" style="margin: auto;"></div>';
            }
            echo $returnValue;
        }
    }

    /**
     * Método para listar items de solo videos para este tipo de seccion
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $current_page
     * @param int $paginado
     */
    public function buscar_para_losmas_programa($current_page = 1, $paginado = 0) {
        if ($this->input->is_ajax_request()) {
            $objSeccion = $this->secciones_m->get($this->input->post('seccion_id'));
            $objPortada = $this->portada_m->get($objSeccion->portadas_id);
            $programa_id = $objPortada->origen_id;
            $array_maestros = array();
            $array_id_item = array();
            //listamos todas las listas de las colecciones del programa
            /* $lista_programa = $this->obtenerListaPrograma($programa_id);
              if (count($lista_programa) > 0) {
              foreach ($lista_programa as $puntero => $objMaestro) {
              if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
              if (stristr($objMaestro->nombre, $this->input->post('txtBuscar'))) {
              $objMaestro->es_maestro = 1;
              $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro->tipo_grupo_maestro_id);
              $objMaestro->tipo = $objTipoMaestro->nombre;
              array_push($array_maestros, $objMaestro);
              array_push($array_id_item, $objMaestro->id);
              }
              } else {
              $objMaestro->es_maestro = 1;
              $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro->tipo_grupo_maestro_id);
              $objMaestro->tipo = $objTipoMaestro->nombre;
              array_push($array_maestros, $objMaestro);
              array_push($array_id_item, $objMaestro->id);
              }
              }
              } */
            //listamos las listas diractas al programa
            /* $lista_directas_programa = $this->obtenerListaProgramaDirecta($programa_id);
              if (count($lista_directas_programa) > 0) {
              foreach ($lista_directas_programa as $indice => $objMaestro2) {
              if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
              if (stristr($objMaestro2->nombre, $this->input->post('txtBuscar'))) {
              $objMaestro2->es_maestro = 1;
              $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro2->tipo_grupo_maestro_id);
              $objMaestro2->tipo = $objTipoMaestro->nombre;
              array_push($array_maestros, $objMaestro2);
              array_push($array_id_item, $objMaestro2->id);
              }
              } else {
              $objMaestro2->es_maestro = 1;
              $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro2->tipo_grupo_maestro_id);
              $objMaestro2->tipo = $objTipoMaestro->nombre;
              array_push($array_maestros, $objMaestro2);
              array_push($array_id_item, $objMaestro2->id);
              }
              }
              } */
            //limpiamos las listas repetidas
            /* if (count($array_id_item) > 0) {
              $array_id_item = array_unique($array_id_item);
              $array_maestros = array();
              foreach ($array_id_item as $in => $cod_maestro) {
              $objMaestro3 = $this->grupo_maestro_m->get($cod_maestro);
              $objMaestro3->es_maestro = 1;
              $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro3->tipo_grupo_maestro_id);
              $objMaestro3->tipo = $objTipoMaestro->nombre;
              array_push($array_maestros, $objMaestro3);
              }
              } */
            switch ($this->input->post('tipo_buscar')) {
                case $this->config->item('videos:canal'):
                case $this->config->item('videos:lista'):
                case $this->config->item('videos:programa'):
                case $this->config->item('videos:coleccion'):
                    break;
                case $this->config->item('videos:video'):
                    //listamos los videos del programa
                    $detalle_videos_programa = $this->obtenerVideosPrograma($programa_id);
                    if (count($detalle_videos_programa) > 0) {
                        foreach ($detalle_videos_programa as $index => $objDetalleMaestro) {
                            $objVideo = $this->videos_m->get($objDetalleMaestro->video_id);
                            if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                                if (stristr($objVideo->titulo, $this->input->post('txtBuscar'))) {
                                    $objVideo->es_maestro = 0;
                                    $objVideo->tipo = 'Video';
                                    array_push($array_maestros, $objVideo);
                                }
                            } else {
                                $objVideo->es_maestro = 0;
                                $objVideo->tipo = 'Video';
                                array_push($array_maestros, $objVideo);
                            }
                        }
                    }
                    break;
                default:
                    //listamos los videos del programa
                    $detalle_videos_programa = $this->obtenerVideosPrograma($programa_id);
                    if (count($detalle_videos_programa) > 0) {
                        foreach ($detalle_videos_programa as $index => $objDetalleMaestro) {
                            $objVideo = $this->videos_m->get($objDetalleMaestro->video_id);
                            if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                                if (stristr($objVideo->titulo, $this->input->post('txtBuscar'))) {
                                    $objVideo->es_maestro = 0;
                                    $objVideo->tipo = 'Video';
                                    array_push($array_maestros, $objVideo);
                                }
                            } else {
                                $objVideo->es_maestro = 0;
                                $objVideo->tipo = 'Video';
                                array_push($array_maestros, $objVideo);
                            }
                        }
                    }
                    break;
            }

            $total = count($array_maestros);
            $cantidad_mostrar = 7;
            $current_page = $current_page - 1;
            $numero_paginas = ceil(count($array_maestros) / $cantidad_mostrar);
            $offset = $current_page * $cantidad_mostrar;
            $array_maestros = array_slice($array_maestros, $offset, $cantidad_mostrar);
            if ($paginado == '1') {
                $returnValue = $this->htmlListaMaestro($array_maestros, $this->input->post('seccion_id'), $this->input->post('canal_id'));
            } else {
                $returnValue = '<table>';
                $returnValue.='<thead>';
                $returnValue.='<tr>';
                $returnValue.='<th>#</th>';
                $returnValue.='<th>imagen</td>';
                $returnValue.='<th>nombre</td>';
                $returnValue.='<th>tipo</td>';
                $returnValue.='<th>acciones</td>';
                $returnValue.='</tr>';
                $returnValue.='</thead>';
                $returnValue.='<tbody id="resultado">';
                $returnValue.= $this->htmlListaMaestro($array_maestros, $this->input->post('seccion_id'), $this->input->post('canal_id'));
                $returnValue.='</tbody>';
                $returnValue.='</table>';
                $returnValue.='<input type="hidden" id="total" name="total" value="' . $total . '" />';
                $returnValue.='<input type="hidden" id="cantidad_mostrar" name="cantidad_mostrar" value="' . $cantidad_mostrar . '" />';
                $returnValue.='<div id="black" style="margin: auto;"></div>';
            }
            echo $returnValue;
        }
    }

    /**
     * Método para mostrar en lista los items para esta seccion
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $current_page
     * @param int $paginado
     */
    public function buscar_para_destacado_categoria($current_page = 1, $paginado = 0) {
        if ($this->input->is_ajax_request()) {
            $array_maestros = array();
            switch ($this->input->post('tipo_buscar')) {
                case $this->config->item('videos:programa'):
                case $this->config->item('videos:coleccion'):
                case $this->config->item('videos:lista'):
                    //listamos todos los maestros
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        if ($this->input->post('tipo_buscar')) {
                            $lista_maestros = $this->grupo_maestro_m->where_in('tipo_grupo_maestro_id', $this->input->post('tipo_buscar'))->like('nombre', $this->input->post('txtBuscar'))->get_many_by(array());
                        } else {
                            $lista_maestros = $this->grupo_maestro_m->like('nombre', $this->input->post('txtBuscar'))->get_many_by(array());
                        }
                    } else {
                        if ($this->input->post('tipo_buscar')) {
                            $lista_maestros = $this->grupo_maestro_m->where_in('tipo_grupo_maestro_id', $this->input->post('tipo_buscar'))->get_many_by(array());
                        } else {
                            $lista_maestros = $this->grupo_maestro_m->get_many_by(array());
                        }
                    }
                    if (count($lista_maestros) > 0) {

                        foreach ($lista_maestros as $puntero => $objMaestro) {
                            if (count($objMaestro) > 0) {
                                $objMaestro->es_maestro = 1;
                                $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro->tipo_grupo_maestro_id);
                                $objMaestro->tipo = $objTipoMaestro->nombre;
                                array_push($array_maestros, $objMaestro);
                            }
                        }
                    }
                    break;
                case $this->config->item('videos:canal'):
                    break;
                case $this->config->item('videos:video'):
                    //obtenemos los videos para listarlos
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        $lista_videos = $this->videos_m->like('titulo', $this->input->post('txtBuscar'))->get_many_by(array());
                    } else {
                        $lista_videos = $this->videos_m->get_many_by(array());
                    }
                    if (count($lista_videos) > 0) {
                        foreach ($lista_videos as $index => $objVideo) {
                            $objVideo->es_maestro = 0;
                            $objVideo->tipo = 'Video';
                            array_push($array_maestros, $objVideo);
                        }
                    }
                    break;
                default:
                    //listamos todos los maestros
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        $lista_maestros = $this->grupo_maestro_m->like('nombre', $this->input->post('txtBuscar'))->get_many_by(array());
                    } else {
                        $lista_maestros = $this->grupo_maestro_m->get_many_by(array());
                    }
                    if (count($lista_maestros) > 0) {

                        foreach ($lista_maestros as $puntero => $objMaestro) {
                            if (count($objMaestro) > 0) {
                                $objMaestro->es_maestro = 1;
                                $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro->tipo_grupo_maestro_id);
                                $objMaestro->tipo = $objTipoMaestro->nombre;
                                array_push($array_maestros, $objMaestro);
                            }
                        }
                    }
                    //obtenemos los videos para listarlos
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        $lista_videos = $this->videos_m->like('titulo', $this->input->post('txtBuscar'))->get_many_by(array());
                    } else {
                        $lista_videos = $this->videos_m->get_many_by(array());
                    }
                    if (count($lista_videos) > 0) {
                        foreach ($lista_videos as $index => $objVideo) {
                            $objVideo->es_maestro = 0;
                            $objVideo->tipo = 'Video';
                            array_push($array_maestros, $objVideo);
                        }
                    }

                    break;
            }

            $total = count($array_maestros);
            $cantidad_mostrar = 7;
            $current_page = $current_page - 1;
            $numero_paginas = ceil(count($array_maestros) / $cantidad_mostrar);
            $offset = $current_page * $cantidad_mostrar;
            $array_maestros = array_slice($array_maestros, $offset, $cantidad_mostrar);
            if ($paginado == '1') {
                $returnValue = $this->htmlListaMaestro($array_maestros, $this->input->post('seccion_id'), $this->input->post('canal_id'));
            } else {
                $returnValue = '<table>';
                $returnValue.='<thead>';
                $returnValue.='<tr>';
                $returnValue.='<th>#</th>';
                $returnValue.='<th>imagen</td>';
                $returnValue.='<th>nombre</td>';
                $returnValue.='<th>tipo</td>';
                $returnValue.='<th>acciones</td>';
                $returnValue.='</tr>';
                $returnValue.='</thead>';
                $returnValue.='<tbody id="resultado">';
                $returnValue.= $this->htmlListaMaestro($array_maestros, $this->input->post('seccion_id'), $this->input->post('canal_id'));
                $returnValue.='</tbody>';
                $returnValue.='</table>';
                $returnValue.='<input type="hidden" id="total" name="total" value="' . $total . '" />';
                $returnValue.='<input type="hidden" id="cantidad_mostrar" name="cantidad_mostrar" value="' . $cantidad_mostrar . '" />';
                $returnValue.='<div id="black" style="margin: auto;"></div>';
            }
            echo $returnValue;
        }
    }

    /**
     * Método para mostrar en lista los items para esta seccion
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $current_page
     * @param int $paginado
     */
    public function buscar_para_destacado_micanal($current_page = 1, $paginado = 0) {
        if ($this->input->is_ajax_request()) {
            $array_maestros = array();
            switch ($this->input->post('tipo_buscar')) {
                case $this->config->item('videos:canal'):
                    //obtenemos la lista de los canales, para agregarlos
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        $lista_canales = $this->canales_m->like('nombre', $this->input->post('txtBuscar'))->get_many_by(array("estado" => $this->config->item('estado:publicado')));
                    } else {
                        $lista_canales = $this->canales_m->get_many_by(array("estado" => $this->config->item('estado:publicado')));
                    }
                    if (count($lista_canales) > 0) {
                        foreach ($lista_canales as $ind => $oCanal) {
                            $oCanal->es_maestro = 2; //es canal
                            $oCanal->tipo = 'Canal';
                            array_push($array_maestros, $oCanal);
                        }
                    }
                    break;
                case $this->config->item('videos:video'):
                    //obtenemos los videos para listarlos
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        $lista_videos = $this->videos_m->like('titulo', $this->input->post('txtBuscar'))->get_many_by(array());
                    } else {
                        $lista_videos = $this->videos_m->get_many_by(array());
                    }
                    if (count($lista_videos) > 0) {
                        foreach ($lista_videos as $index => $objVideo) {
                            $objVideo->es_maestro = 0;
                            $objVideo->tipo = 'Video';
                            array_push($array_maestros, $objVideo);
                        }
                    }
                    break;
                case $this->config->item('videos:programa'):
                case $this->config->item('videos:coleccion'):
                case $this->config->item('videos:lista'):
                    //listamos todos los maestros
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        if ($this->input->post('tipo_buscar')) {
                            $lista_maestros = $this->grupo_maestro_m->where_in('tipo_grupo_maestro_id', $this->input->post('tipo_buscar'))->like('nombre', $this->input->post('txtBuscar'))->get_many_by(array());
                        } else {
                            $lista_maestros = $this->grupo_maestro_m->like('nombre', $this->input->post('txtBuscar'))->get_many_by(array());
                        }
                    } else {
                        if ($this->input->post('tipo_buscar')) {
                            $lista_maestros = $this->grupo_maestro_m->where_in('tipo_grupo_maestro_id', $this->input->post('tipo_buscar'))->get_many_by(array());
                        } else {
                            $lista_maestros = $this->grupo_maestro_m->get_many_by(array());
                        }
                    }
                    if (count($lista_maestros) > 0) {

                        foreach ($lista_maestros as $puntero => $objMaestro) {
                            if (count($objMaestro) > 0) {
                                $objMaestro->es_maestro = 1;
                                $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro->tipo_grupo_maestro_id);
                                $objMaestro->tipo = $objTipoMaestro->nombre;
                                array_push($array_maestros, $objMaestro);
                            }
                        }
                    }
                    break;
                default:
                    //obtenemos la lista de los canales, para agregarlos
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        $lista_canales = $this->canales_m->like('nombre', $this->input->post('txtBuscar'))->get_many_by(array("estado" => $this->config->item('estado:publicado')));
                    } else {
                        $lista_canales = $this->canales_m->get_many_by(array("estado" => $this->config->item('estado:publicado')));
                    }
                    if (count($lista_canales) > 0) {
                        foreach ($lista_canales as $ind => $oCanal) {
                            $oCanal->es_maestro = 2; //es canal
                            $oCanal->tipo = 'Canal';
                            array_push($array_maestros, $oCanal);
                        }
                    }
                    //listamos todos los maestros
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        $lista_maestros = $this->grupo_maestro_m->like('nombre', $this->input->post('txtBuscar'))->get_many_by(array());
                    } else {
                        $lista_maestros = $this->grupo_maestro_m->get_many_by(array());
                    }
                    if (count($lista_maestros) > 0) {

                        foreach ($lista_maestros as $puntero => $objMaestro) {
                            if (count($objMaestro) > 0) {
                                $objMaestro->es_maestro = 1;
                                $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro->tipo_grupo_maestro_id);
                                $objMaestro->tipo = $objTipoMaestro->nombre;
                                array_push($array_maestros, $objMaestro);
                            }
                        }
                    }
                    //obtenemos los videos para listarlos
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        $lista_videos = $this->videos_m->like('titulo', $this->input->post('txtBuscar'))->get_many_by(array());
                    } else {
                        $lista_videos = $this->videos_m->get_many_by(array());
                    }
                    if (count($lista_videos) > 0) {
                        foreach ($lista_videos as $index => $objVideo) {
                            $objVideo->es_maestro = 0;
                            $objVideo->tipo = 'Video';
                            array_push($array_maestros, $objVideo);
                        }
                    }
                    break;
            }
            $total = count($array_maestros);
            $cantidad_mostrar = 7;
            $current_page = $current_page - 1;
            $numero_paginas = ceil(count($array_maestros) / $cantidad_mostrar);
            $offset = $current_page * $cantidad_mostrar;
            $array_maestros = array_slice($array_maestros, $offset, $cantidad_mostrar);
            if ($paginado == '1') {
                $returnValue = $this->htmlListaMaestro($array_maestros, $this->input->post('seccion_id'), $this->input->post('canal_id'));
            } else {
                $returnValue = '<table>';
                $returnValue.='<thead>';
                $returnValue.='<tr>';
                $returnValue.='<th>#</th>';
                $returnValue.='<th>imagen</td>';
                $returnValue.='<th>nombre</td>';
                $returnValue.='<th>tipo</td>';
                $returnValue.='<th>acciones</td>';
                $returnValue.='</tr>';
                $returnValue.='</thead>';
                $returnValue.='<tbody id="resultado">';
                $returnValue.= $this->htmlListaMaestro($array_maestros, $this->input->post('seccion_id'), $this->input->post('canal_id'));
                $returnValue.='</tbody>';
                $returnValue.='</table>';
                $returnValue.='<input type="hidden" id="total" name="total" value="' . $total . '" />';
                $returnValue.='<input type="hidden" id="cantidad_mostrar" name="cantidad_mostrar" value="' . $cantidad_mostrar . '" />';
                $returnValue.='<div id="black" style="margin: auto;"></div>';
            }
            echo $returnValue;
        }
    }

    public function agregarCanalASeccion($canal_item, $seccion_id) {
        if ($this->input->is_ajax_request()) {
            $user_id = (int) $this->session->userdata('user_id');
            $objImagen = $this->obtenerImagenMaestro($canal_item, $seccion_id, 'canal');
            $returnValue = 1;
            if (count($objImagen) > 0) {
                if ($this->canalAgregadoSeccion($canal_item, $seccion_id, 0)) {
                    $objDetalleSeccion = $this->detalle_secciones_m->get_by(array("canales_id" => $canal_item, "secciones_id" => $seccion_id));
                    $peso = $this->obtenerPeso($seccion_id);
                    $this->detalle_secciones_m->update($objDetalleSeccion->id, array("imagenes_id" => $objImagen->id, "peso" => $peso, "estado" => "1", "estado_migracion" => $this->config->item('migracion:actualizado')));
                    $returnValue = 0;
                } else {
                    $objBeanDetalleSeccion = new stdClass();
                    $objBeanDetalleSeccion->id = NULL;
                    $objBeanDetalleSeccion->secciones_id = $seccion_id;
                    $objBeanDetalleSeccion->videos_id = NULL;
                    $objBeanDetalleSeccion->grupo_maestros_id = NULL;
                    $objBeanDetalleSeccion->canales_id = $canal_item;
                    $objBeanDetalleSeccion->imagenes_id = $objImagen->id;
                    $objBeanDetalleSeccion->peso = $this->obtenerPeso($seccion_id);
                    $objBeanDetalleSeccion->descripcion_item = '';
                    $objBeanDetalleSeccion->estado = 1;
                    $objBeanDetalleSeccion->fecha_registro = date("Y-m-d H:i:s");
                    $objBeanDetalleSeccion->usuario_registro = $user_id;
                    $objBeanDetalleSeccion->estado_migracion = 0;
                    $objBeanDetalleSeccion->fecha_migracion = '0000-00-00 00:00:00';
                    $objBeanDetalleSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                    $this->detalle_secciones_m->save($objBeanDetalleSeccion);
                    $returnValue = 0;
                }
            }
            echo json_encode(array("error" => $returnValue, "canal_item" => $canal_item));
        }
    }

    public function agregarVideoASeccion($video_id, $seccion_id) {
        if ($this->input->is_ajax_request()) {
            $user_id = (int) $this->session->userdata('user_id');
            $objImagen = $this->obtenerImagenMaestro($video_id, $seccion_id, 'video');
            $returnValue = 1;
            if (count($objImagen) > 0) {
                if ($this->videoAgregadoSeccion($video_id, $seccion_id, 0)) {
                    $objDetalleSeccion = $this->detalle_secciones_m->get_by(array("videos_id" => $video_id, "secciones_id" => $seccion_id));
                    $peso = $this->obtenerPeso($seccion_id);
                    $this->detalle_secciones_m->update($objDetalleSeccion->id, array("peso" => $peso, "estado" => "1", "estado_migracion" => $this->config->item('migracion:actualizado')));
                    $returnValue = 0;
                } else {
                    $objBeanDetalleSeccion = new stdClass();
                    $objBeanDetalleSeccion->id = NULL;
                    $objBeanDetalleSeccion->secciones_id = $seccion_id;
                    $objBeanDetalleSeccion->videos_id = $video_id;
                    $objBeanDetalleSeccion->grupo_maestros_id = NULL;
                    $objBeanDetalleSeccion->canales_id = NULL;
                    $objBeanDetalleSeccion->imagenes_id = $objImagen->id;
                    $objBeanDetalleSeccion->peso = $this->obtenerPeso($seccion_id);
                    $objBeanDetalleSeccion->descripcion_item = '';
                    $objBeanDetalleSeccion->estado = 1;
                    $objBeanDetalleSeccion->fecha_registro = date("Y-m-d H:i:s");
                    $objBeanDetalleSeccion->usuario_registro = $user_id;
                    $objBeanDetalleSeccion->estado_migracion = 0;
                    $objBeanDetalleSeccion->fecha_migracion = '0000-00-00 00:00:00';
                    $objBeanDetalleSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                    $this->detalle_secciones_m->save($objBeanDetalleSeccion);
                    $returnValue = 0;
                }
            }
            echo json_encode(array("error" => $returnValue, "video_id" => $video_id));
        }
    }

    /**
     * Método para mostrar en lista los items para esta seccion
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $current_page
     * @param int $paginado
     */
    public function buscar_para_micanal($current_page = 1, $paginado = 0) {
        if ($this->input->is_ajax_request()) {
            $array_maestros = array();
            switch ($this->input->post('tipo_buscar')) {
                case $this->config->item('videos:programa'):
                case $this->config->item('videos:coleccion'):
                case $this->config->item('videos:lista'):
                case $this->config->item('videos:video'):
                    break;
                case $this->config->item('videos:canal'):
                    //obtenemos la lista de los canales, para agregarlos
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        $lista_canales = $this->canales_m->like('nombre', $this->input->post('txtBuscar'))->get_many_by(array("estado" => $this->config->item('estado:publicado')));
                    } else {
                        $lista_canales = $this->canales_m->get_many_by(array("estado" => $this->config->item('estado:publicado')));
                    }
                    if (count($lista_canales) > 0) {
                        foreach ($lista_canales as $ind => $oCanal) {
                            $oCanal->es_maestro = 2; //es canal
                            $oCanal->tipo = 'Canal';
                            array_push($array_maestros, $oCanal);
                        }
                    }
                    break;
                default:
                    //obtenemos la lista de los canales, para agregarlos
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        $lista_canales = $this->canales_m->like('nombre', $this->input->post('txtBuscar'))->get_many_by(array("estado" => $this->config->item('estado:publicado')));
                    } else {
                        $lista_canales = $this->canales_m->get_many_by(array("estado" => $this->config->item('estado:publicado')));
                    }
                    if (count($lista_canales) > 0) {
                        foreach ($lista_canales as $ind => $oCanal) {
                            $oCanal->es_maestro = 2; //es canal
                            $oCanal->tipo = 'Canal';
                            array_push($array_maestros, $oCanal);
                        }
                    }
                    //listamos todos los maestros
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        $lista_maestros = $this->grupo_maestro_m->like('nombre', $this->input->post('txtBuscar'))->get_many_by(array());
                    } else {
                        $lista_maestros = $this->grupo_maestro_m->get_many_by(array());
                    }
                    if (count($lista_maestros) > 0) {
                        foreach ($lista_maestros as $puntero => $objMaestro) {
                            if (count($objMaestro) > 0) {
                                $objMaestro->es_maestro = 1;
                                $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro->tipo_grupo_maestro_id);
                                $objMaestro->tipo = $objTipoMaestro->nombre;
                                array_push($array_maestros, $objMaestro);
                            }
                        }
                    }
                    //obtenemos los videos para listarlos
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        $lista_videos = $this->videos_m->like('titulo', $this->input->post('txtBuscar'))->get_many_by(array());
                    } else {
                        $lista_videos = $this->videos_m->get_many_by(array());
                    }

                    if (count($lista_videos) > 0) {
                        foreach ($lista_videos as $index => $objVideo) {
                            $objVideo->es_maestro = 0;
                            $objVideo->tipo = 'Video';
                            array_push($array_maestros, $objVideo);
                        }
                    }
                    break;
            }


            $total = count($array_maestros);
            $cantidad_mostrar = 7;
            $current_page = $current_page - 1;
            $numero_paginas = ceil(count($array_maestros) / $cantidad_mostrar);
            $offset = $current_page * $cantidad_mostrar;
            $array_maestros = array_slice($array_maestros, $offset, $cantidad_mostrar);
            if ($paginado == '1') {
                $returnValue = $this->htmlListaMaestro($array_maestros, $this->input->post('seccion_id'), $this->input->post('canal_id'));
            } else {
                $returnValue = '<table>';
                $returnValue.='<thead>';
                $returnValue.='<tr>';
                $returnValue.='<th>#</th>';
                $returnValue.='<th>imagen</td>';
                $returnValue.='<th>nombre</td>';
                $returnValue.='<th>tipo</td>';
                $returnValue.='<th>acciones</td>';
                $returnValue.='</tr>';
                $returnValue.='</thead>';
                $returnValue.='<tbody id="resultado">';
                $returnValue.= $this->htmlListaMaestro($array_maestros, $this->input->post('seccion_id'), $this->input->post('canal_id'));
                $returnValue.='</tbody>';
                $returnValue.='</table>';
                $returnValue.='<input type="hidden" id="total" name="total" value="' . $total . '" />';
                $returnValue.='<input type="hidden" id="cantidad_mostrar" name="cantidad_mostrar" value="' . $cantidad_mostrar . '" />';
                $returnValue.='<div id="black" style="margin: auto;"></div>';
            }
            echo $returnValue;
        }
    }

    /**
     * Método para mostrar en lista los items para esta seccion
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $current_page
     * @param int $paginado
     */
    public function buscar_para_programa_micanal($current_page = 1, $paginado = 0) {
        if ($this->input->is_ajax_request()) {
            $array_maestros = array();
            switch ($this->input->post('tipo_buscar')) {
                case $this->config->item('videos:lista'):
                case $this->config->item('videos:coleccion'):
                case $this->config->item('videos:canal'):
                case $this->config->item('videos:video'):
                    break;
                case $this->config->item('videos:programa'):
                    //listamos todos los maestros
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        $lista_maestros = $this->grupo_maestro_m->like('nombre', $this->input->post('txtBuscar'))->get_many_by(array("tipo_grupo_maestro_id" => $this->config->item('videos:programa')));
                    } else {
                        $lista_maestros = $this->grupo_maestro_m->get_many_by(array("tipo_grupo_maestro_id" => $this->config->item('videos:programa')));
                    }
                    if (count($lista_maestros) > 0) {
                        foreach ($lista_maestros as $puntero => $objMaestro) {
                            if (count($objMaestro) > 0) {
                                $objMaestro->es_maestro = 1;
                                $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro->tipo_grupo_maestro_id);
                                $objMaestro->tipo = $objTipoMaestro->nombre;
                                array_push($array_maestros, $objMaestro);
                            }
                        }
                    }
                    break;
                default:
                    //listamos todos los maestros
                    if (strlen(trim($this->input->post('txtBuscar'))) > 0) {
                        $lista_maestros = $this->grupo_maestro_m->like('nombre', $this->input->post('txtBuscar'))->get_many_by(array("tipo_grupo_maestro_id" => $this->config->item('videos:programa')));
                    } else {
                        $lista_maestros = $this->grupo_maestro_m->get_many_by(array("tipo_grupo_maestro_id" => $this->config->item('videos:programa')));
                    }
                    if (count($lista_maestros) > 0) {
                        foreach ($lista_maestros as $puntero => $objMaestro) {
                            if (count($objMaestro) > 0) {
                                $objMaestro->es_maestro = 1;
                                $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro->tipo_grupo_maestro_id);
                                $objMaestro->tipo = $objTipoMaestro->nombre;
                                array_push($array_maestros, $objMaestro);
                            }
                        }
                    }
                    break;
            }

            $total = count($array_maestros);
            $cantidad_mostrar = 7;
            $current_page = $current_page - 1;
            $numero_paginas = ceil(count($array_maestros) / $cantidad_mostrar);
            $offset = $current_page * $cantidad_mostrar;
            $array_maestros = array_slice($array_maestros, $offset, $cantidad_mostrar);
            if ($paginado == '1') {
                $returnValue = $this->htmlListaMaestro($array_maestros, $this->input->post('seccion_id'), $this->input->post('canal_id'));
            } else {
                $returnValue = '<table>';
                $returnValue.='<thead>';
                $returnValue.='<tr>';
                $returnValue.='<th>#</th>';
                $returnValue.='<th>imagen</td>';
                $returnValue.='<th>nombre</td>';
                $returnValue.='<th>tipo</td>';
                $returnValue.='<th>acciones</td>';
                $returnValue.='</tr>';
                $returnValue.='</thead>';
                $returnValue.='<tbody id="resultado">';
                $returnValue.= $this->htmlListaMaestro($array_maestros, $this->input->post('seccion_id'), $this->input->post('canal_id'));
                $returnValue.='</tbody>';
                $returnValue.='</table>';
                $returnValue.='<input type="hidden" id="total" name="total" value="' . $total . '" />';
                $returnValue.='<input type="hidden" id="cantidad_mostrar" name="cantidad_mostrar" value="' . $cantidad_mostrar . '" />';
                $returnValue.='<div id="black" style="margin: auto;"></div>';
            }
            echo $returnValue;
        }
    }

    /**
     * Método para imprimir en la barra de titulos el canal y el nombre de la interface a la que se accede
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $canal_id
     * @param int $vista
     */
    public function mostrar_titulo($canal_id, $vista) {
        if ($this->input->is_ajax_request()) {
            $vista = str_replace("_", " ", $vista);
            //no backgrouns : .channel_item
            $objImagen = $this->imagen_m->get_by(array("canales_id" => $canal_id, "tipo_imagen_id" => $this->config->item('imagen:iso'), "estado" => $this->config->item('estado:publicado')));
            $objCanal = $this->canales_m->get($canal_id);
            if (count($objImagen) > 0) {
                if ($objImagen->procedencia == '0') {
                    $imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagen->imagen;
                } else {
                    $imagen = $objImagen->imagen;
                }
                $html = '<h2 class="channel_item" background="none" style="padding-left:0px !important;background:none;">';
                $html.='<div class="logo_canal" style="width:40px;display: block;float:left;margin-right: 15px;">';
                $html.='<img width="40" height="40" src="' . $imagen . '">';
                $html.='</div>';
                $html.='<a href="/admin/canales/videos/' . $canal_id . '" float="left"> ' . ucwords($objCanal->nombre) . ' |  </a>';
                $html.='<a>' . ucwords($vista) . '</a>';
                $html.='</h2>';
            } else {
                $html = '<h2 class="channel_item" style="padding-left:50px !important;">';
                $html.='<a href="/admin/canales/videos/' . $canal_id . '" float="left"> ' . ucwords($objCanal->nombre) . ' |  </a>';
                $html.='<a>' . ucwords($vista) . '</a>';
                $html.='</h2>';
            }
            echo $html;
        }
    }

    public function test() {
        //$r = $this->procesos_lib->generarMiCanal();
        if ($this->input->post()) {
            
        } else {
            $this->template
                    ->title($this->module_details['name'])
                    ->set('canales', "d");
            $this->template->build('admin/test');
        }
    }

    /**
     * Método para previsualizar  un video
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $video_id
     * metodo para llamar al archivo vista_previa.php para la vista previa
     */
    public function visualizar_video($video_id) {
        $objVideo = $this->videos_m->get($video_id);
        $this->template
                ->set_layout('modal', 'admin')
                //->set('ruta', $objVideo->ruta)
                ->set('id', $objVideo->id)
                //->append_js('module::flowplayer.min.js')
                //->append_css('module::skin/minimalist.css')                
                ->build('admin/visualizar_video');
    }

    /**
     * Obtenemos el objeto con datos de la portada para la previsualizacion
     * @param int $canal_id
     * @return array $returnValue
     */
    private function obtenerPortadaCanal($canal_id) {
        $user_id = (int) $this->session->userdata('user_id');
        //$returnValue = array();
        $objCanal = $this->canales_m->get($canal_id);
        if ($objCanal->tipo_canales_id == $this->config->item('canal:mi_canal')) {
            $objPortada = $this->portada_m->get_by(array("tipo_portadas_id" => $this->config->item('portada:principal'), "origen_id" => $canal_id));
        } else {
            $objPortada = $this->portada_m->get_by(array("tipo_portadas_id" => $this->config->item('portada:canal'), "origen_id" => $canal_id));
        }
        if (count($objPortada) > 0) {
            $objColeccionSeccion = $this->secciones_m->order_by('peso', 'ASC')->get_many_by(array("portadas_id" => $objPortada->id, "estado" => $this->config->item('estado:publicado')));
            if (count($objColeccionSeccion) > 0) {
                foreach ($objColeccionSeccion as $puntero => $objSeccion) {
                    $detalle_seccion = $this->detalle_secciones_m->order_by('peso', 'ASC')->get_many_by(array("secciones_id" => $objSeccion->id, "estado" => $this->config->item('estado:publicado')));
                    if (count($detalle_seccion) > 0) {
                        foreach ($detalle_seccion as $index => $objDetalleSeccion) {
                            $objImagen = $this->imagen_m->get($objDetalleSeccion->imagenes_id);
                            if (count($objImagen) > 0) {
                                if ($objImagen->procedencia == 0) {
                                    $objDetalleSeccion->imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagen->imagen;
                                } else {
                                    $objDetalleSeccion->imagen = $objImagen->imagen;
                                }
                            } else {
                                $objDetalleSeccion->imagen = $this->config->item('url:portada');
                            }
                            //obtenemos el titulo del item de detalle de seccion
                            $objDetalleSeccion->objMaestro = $this->grupo_maestro_m->get($objDetalleSeccion->grupo_maestros_id);
                            $detalle_seccion[$index] = $objDetalleSeccion;
                        }
                    }
                    $objSeccion->detalle_seccion = $detalle_seccion;
                    $objColeccionSeccion[$puntero] = $objSeccion;
                }
            }
            $objPortada->secciones = $objColeccionSeccion;
            //array_push($returnValue, $objPortada);
        } else {
            //creamos un objeto de tipo portada vacia para que generar error
            $objPortada = new stdClass();
            $objPortada->id = 0;
            $objPortada->canales_id = $canal_id;
            $objPortada->nombre = '';
            $objPortada->descripcion = '';
            $objPortada->tipo_portadas_id = $this->config->item('portada:canal');
            $objPortada->origen_id = $canal_id;
            $objPortada->estado = $this->config->item('estado:borrador');
            $objPortada->fecha_registro = '0000-00-00 00:00:00';
            $objPortada->usuario_registro = $user_id;
            $objPortada->fecha_actualizacion = '0000-00-00 00:00:00';
            $objPortada->usuario_actualizacion = $user_id;
            $objPortada->id_mongo = NULL;
            $objPortada->estado_migracion = $this->config->item('migracion:nuevo');
            $objPortada->fecha_migracion = '0000-00-00 00:00:00';
            $objPortada->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
            $objPortada->secciones = array();
            //array_push($returnValue, $objPortada);
        }
        return $objPortada;
    }

    /**
     * Método para mostrar una vista con ls lista de maestros eliminados
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $canal_id
     */
    public function papelera($canal_id = 0) {
        if ($canal_id > 0) {
            $objCanal = $this->canales_m->get($canal_id);
            //$objColeccionMaestro = $this->grupo_maestro_m->get_many_by(array("canales_id" => $canal_id, "estado" => $this->config->item('estado:eliminado')));

            if ($canal_id == 0) {
                $objUsuarioCanal = $this->usuario_grupo_canales_m->get_by(array("user_id" => $this->current_user->id, "estado" => $this->config->item('estado:publicado')));
                $canal_id = $objUsuarioCanal->canal_id;
            }
            $base_where = array("canales_id" => $canal_id, "estado" => $this->config->item('video:eliminado'));

            //$programme_id = 0;
            $keyword = '';
            if ($this->input->post('f_keywords'))
                $keyword = $this->input->post('f_keywords');

            if ($this->input->post('f_tipo'))
                $base_where['tipo_maestro'] = $this->input->post('f_tipo');

            // Create pagination links
            if (strlen(trim($keyword)) > 0) {
                $total_rows = $this->papelera_m->like('titulo', $keyword)->count_by($base_where);
            } else {
                $total_rows = $this->papelera_m->count_by($base_where);
            }
            $pagination = create_pagination('admin/canales/papelera/' . $canal_id . '/index/', $total_rows, 10, 6);
            if (strlen(trim($keyword)) > 0) {
                // Using this data, get the relevant results
                $objColeccionMaestro = $this->papelera_m->like('titulo', $keyword)->order_by('fecha_registro', 'DESC')->limit($pagination['limit'])->get_many_by($base_where);
            } else {
                $objColeccionMaestro = $this->papelera_m->order_by('fecha_registro', 'DESC')->limit($pagination['limit'])->get_many_by($base_where);
            }

            //obtenemos la imagen
            if (count($objColeccionMaestro) > 0) {
                foreach ($objColeccionMaestro as $puntero => $objMaestro) {
                    if ($objMaestro->maestros == 'maestro') {
                        $objImagen = $this->imagen_m->get_by(array("estado" => $this->config->item('estado:publicado'), "grupo_maestros_id" => $objMaestro->id, "tipo_imagen_id" => $this->config->item('imagen:small')));
                    } else {
                        if ($objMaestro->maestros == 'video') {
                            $objImagen = $this->imagen_m->get_by(array("estado" => $this->config->item('estado:publicado'), "videos_id" => $objMaestro->id, "tipo_imagen_id" => $this->config->item('imagen:small')));
                        } else {
                            $objImagen = $this->imagen_m->get_by(array("estado" => $this->config->item('estado:publicado'), "canales_id" => $objMaestro->id, "tipo_imagen_id" => $this->config->item('imagen:logo')));
                        }
                    }
                    if (count($objImagen) > 0) {
                        if ($objImagen->procedencia == '1') {
                            $objMaestro->imagen = $objImagen->imagen;
                        } else {
                            $objMaestro->imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagen->imagen;
                        }
                    } else {
                        $objMaestro->imagen = $this->config->item('url:default_imagen') . 'no_video.jpg';
                    }
                    $objColeccionMaestro[$puntero] = $objMaestro;
                }
            }
            //los tipos de items
            $tipo_item = array("1" => "Lista de reproducción", "2" => "Colección", "3" => "Programa", "4" => "Video", "5" => "Canal", "6" => "Portada", "7" => "Sección");
            //do we need to unset the layout because the request is ajax?
            $this->input->is_ajax_request() and $this->template->set_layout(FALSE);
            $this->template
                    ->title($this->module_details['name'])
                    ->append_js('admin/filter.js')
                    ->set_partial('filters', 'admin/partials/filters')
                    ->append_js('module::jquery.alerts.js')
                    ->append_css('module::jquery.alerts.css')
                    ->set('maestros', $objColeccionMaestro)
                    ->set('canal', $objCanal)
                    ->set('tipo_item', $tipo_item)
                    ->set_partial('papeleras', 'admin/tables/papeleras')
                    ->append_js('module::jquery.alerts.js')
                    ->append_css('module::jquery.alerts.css')
                    ->set('pagination', $pagination);
            //$this->template->build('admin/papelera');
            $this->input->is_ajax_request() ? $this->template->build('admin/tables/papeleras') : $this->template->build('admin/papelera');
        } else {
            $objUsuarioCanales = $this->usuario_grupo_canales_m->get_many_by(array("user_id" => $this->current_user->id));
            $id_canales = array();
            if (count($objUsuarioCanales) > 0) {
                foreach ($objUsuarioCanales as $index => $value) {
                    array_push($id_canales, $value->canal_id);
                }
            }
            $base_where = array("maestros" => "canal", "estado" => $this->config->item('video:eliminado'));
            $keyword = '';
            if ($this->input->post('f_keywords'))
                $keyword = $this->input->post('f_keywords');
            // Create pagination links
            if (strlen(trim($keyword)) > 0) {
                if (count($id_canales) > 0) {
                    $total_rows = $this->papelera_m->where_in('canales_id', $id_canales)->like('titulo', $keyword)->count_by($base_where);
                } else {
                    $total_rows = 0;
                }
            } else {
                if (count($id_canales) > 0) {
                    $total_rows = $this->papelera_m->where_in('canales_id', $id_canales)->count_by($base_where);
                } else {
                    $total_rows = 0;
                }
            }
            $pagination = create_pagination('admin/canales/papelera/index/', $total_rows, 10, 5);
            if (strlen(trim($keyword)) > 0) {
                if (count($id_canales) > 0) {
                    $objColeccionMaestro = $this->papelera_m->where_in('canales_id', $id_canales)->like('titulo', $keyword)->order_by('fecha_registro', 'DESC')->limit($pagination['limit'])->get_many_by($base_where);
                } else {
                    $objColeccionMaestro = array();
                }
            } else {
                if (count($id_canales) > 0) {
                    $objColeccionMaestro = $this->papelera_m->where_in('canales_id', $id_canales)->order_by('fecha_registro', 'DESC')->limit($pagination['limit'])->get_many_by($base_where);
                } else {
                    $objColeccionMaestro = array();
                }
            }
            //agreagamos las imagenes
            if (count($objColeccionMaestro) > 0) {
                foreach ($objColeccionMaestro as $puntero => $objMaestro) {
                    if ($objMaestro->maestros == 'maestro') {
                        $objImagen = $this->imagen_m->get_by(array("estado" => $this->config->item('estado:publicado'), "canales_id" => $objMaestro->id, "tipo_imagen_id" => $this->config->item('imagen:small')));
                    } else {
                        if ($objMaestro->maestros == 'video') {
                            $objImagen = $this->imagen_m->get_by(array("estado" => $this->config->item('estado:publicado'), "videos_id" => $objMaestro->id, "tipo_imagen_id" => $this->config->item('imagen:small')));
                        } else {
                            $objImagen = $this->imagen_m->get_by(array("estado" => $this->config->item('estado:publicado'), "canales_id" => $objMaestro->id, "tipo_imagen_id" => $this->config->item('imagen:logo')));
                        }
                    }
                    if (count($objImagen) > 0) {
                        if ($objImagen->procedencia == '1') {
                            $objMaestro->imagen = $objImagen->imagen;
                        } else {
                            $objMaestro->imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagen->imagen;
                        }
                    } else {
                        $objMaestro->imagen = $this->config->item('url:default_imagen') . 'no_video.jpg';
                    }
                    $objColeccionMaestro[$puntero] = $objMaestro;
                }
            }
            $this->input->is_ajax_request() and $this->template->set_layout(FALSE);
            $this->template
                    ->title($this->module_details['name'])
                    ->append_js('admin/filter.js')
                    ->set_partial('filters', 'admin/partials/filters')
                    ->append_js('module::jquery.alerts.js')
                    ->append_css('module::jquery.alerts.css')
                    ->set('maestros', $objColeccionMaestro)
                    //->set('canal', $objCanal)
                    ->set_partial('papeleras', 'admin/tables/papeleras')
                    ->append_js('module::jquery.alerts.js')
                    ->append_css('module::jquery.alerts.css')
                    ->set('pagination', $pagination);
            $this->input->is_ajax_request() ? $this->template->build('admin/tables/papeleras') : $this->template->build('admin/papelera_canal');
        }
    }

    /**
     * Método para enviar a estado borrador los items maestros y/o video
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $id
     * @param string $tipo
     */
    public function restaurar($id, $tipo) {
        if ($this->input->is_ajax_request()) {
            if ($tipo == 'maestro') {
                $this->grupo_maestro_m->update($id, array("estado_migracion_sphinx" => $this->config->item('sphinx:actualizar'), "estado_migracion" => $this->config->item('migracion:actualizado'), "estado" => $this->config->item('estado:borrador')));
            } else {
                $this->videos_m->update($id, array("estado_migracion_sphinx" => $this->config->item('sphinx:actualizar'), "estado_migracion" => $this->config->item('migracion:actualizado'), "estado" => $this->config->item('video:borrador')));
            }
            echo json_encode(array("value" => "1"));
        }
    }

    /**
     * Método para eliminar un video, entrada x ajax
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $video_id
     */
    public function eliminar_video($video_id) {
        if ($this->input->is_ajax_request()) {
            $this->videos_m->update($video_id, array("estado_migracion_sphinx" => $this->config->item('sphinx:actualizar'), "estado" => $this->config->item('video:eliminado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
            $this->procesos_lib->curlDesactivarVideosXId($video_id);
            echo json_encode(array("value" => "1"));
        }
    }

    public function eliminar_maestro($maestro_id) {
        if ($this->input->is_ajax_request()) {
            $this->grupo_maestro_m->update($maestro_id, array("estado_migracion_sphinx" => $this->config->item('sphinx:actualizar'), "estado" => $this->config->item('estado:eliminado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
            //$this->procesos_lib->curlDesactivarVideosXId($video_id);
            echo json_encode(array("value" => "1"));
        }
    }

    /**
     * Método para publicar el video
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $video_id
     */
    public function publicar_video($video_id) {
        if ($this->input->is_ajax_request()) {
            $this->videos_m->update($video_id, array("estado_migracion_sphinx" => $this->config->item('sphinx:actualizar'), "estado" => $this->config->item('video:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
            $this->procesos_lib->curlActivarVideosXId($video_id);
            echo json_encode(array("value" => "1"));
        }
    }

    /**
     * Método que se ejecutará desde el navegador para corregir las imagenes
     * @author Johnny Huamani <johnny1402@gmail.com>
     */
    public function corregir_imagenes() {
        $detalle_secciones = $this->detalle_secciones_m->get_many_by(array());
        if (count($detalle_secciones) > 0) {
            foreach ($detalle_secciones as $puntero => $objDetalleSeccion) {
                if ($objDetalleSeccion->grupo_maestros_id > 0) {
                    $objImagen = $this->obtenerImagenMaestro($objDetalleSeccion->grupo_maestros_id, $objDetalleSeccion->secciones_id);
                    if (count($objImagen) > 0) {
                        $this->detalle_secciones_m->update($objDetalleSeccion->id, array("imagenes_id" => $objImagen->id, "estado_migracion" => $this->config->item('migracion:actualizado')));
                    }
                } else {
                    if ($objDetalleSeccion->videos_id > 0) {
                        $objImagen = $this->obtenerImagenMaestro($objDetalleSeccion->videos_id, $objDetalleSeccion->secciones_id, 'video');
                        if (count($objImagen) > 0) {
                            $this->detalle_secciones_m->update($objDetalleSeccion->id, array("imagenes_id" => $objImagen->id, "estado_migracion" => $this->config->item('migracion:actualizado')));
                        }
                    } else {
                        if ($objDetalleSeccion->canales_id > 0) {
                            $objImagen = $this->obtenerImagenMaestro($objDetalleSeccion->canales_id, $objDetalleSeccion->secciones_id, 'canal');
                            if (count($objImagen) > 0) {
                                $this->detalle_secciones_m->update($objDetalleSeccion->id, array("imagenes_id" => $objImagen->id, "estado_migracion" => $this->config->item('migracion:actualizado')));
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Método para obtener la descripcion de una detalle seccion
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $detalle_seccion_id
     */
    public function obtener_descripcion_detalle_seccion($detalle_seccion_id) {
        if ($this->input->is_ajax_request()) {
            $returnValue = '';
            $objDetalleSeccion = $this->detalle_secciones_m->get($detalle_seccion_id);
            if (count($objDetalleSeccion) > 0) {
                $returnValue = $objDetalleSeccion->descripcion_item;
            }
            echo json_encode(array("value" => $returnValue));
        }
    }

    /**
     * Método para guardar el la descripcion de un detalle seccion
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $detalle_seccion_id
     */
    public function guardar_descripcion($detalle_seccion_id) {
        if ($this->input->is_ajax_request()) {
            $this->detalle_secciones_m->update($detalle_seccion_id, array("estado_migracion" => $this->config->item('migracion:actualizado'), "descripcion_item" => $this->input->post('txtDescripcion')));
            echo json_encode(array("value" => "1", "texto" => $this->input->post('txtDescripcion')));
        }
    }

    /**
     * Método para setear las nuevas posiciones del detalle de secciones
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @return json
     */
    public function ordenar_lista_detalle_secciones() {
        if ($this->input->is_ajax_request()) {
            $post = $this->input->post();
            $array_peso = array();
            if (count($post) > 0) {
                foreach ($post as $puntero => $item) {
                    if (!stristr($puntero, 'peso_')) {
                        unset($post[$puntero]);
                    } else {
                        $puntero = substr($puntero, 5);
                        $array_peso[$puntero] = $item;
                    }
                }
            }

            //iteremos los items para las nuevas ubicaciones
            if (count($array_peso) > 0) {
                //iteramos y actualizamos los pesos
                foreach ($array_peso as $detalle_seccion_id => $peso) {
                    $this->detalle_secciones_m->update($detalle_seccion_id, array("peso" => $peso, "estado_migracion" => $this->config->item('migracion:actualizado')));
                }
            }
            echo json_encode(array("error" => "0", "canal_id" => $this->input->post('canal_id'), "seccion_id" => $this->input->post('seccion_id')));
        }
    }

    /**
     * Método para bajar una posición al detalle sección
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $detalle_seccion_id
     */
    public function bajar_detalle_seccion($detalle_seccion_id) {
        if ($this->input->is_ajax_request()) {
            $objDetalleSeccion = $this->detalle_secciones_m->get($detalle_seccion_id);
            if (count($objDetalleSeccion) > 0) {
                //obtenemos el objeto detalle del siguiente peso a cambiarse
                $objDetalleSeccionMayor = $this->agregar_atributos_detalle_seccion($this->detalle_secciones_m->obtener_detalle_seccion_mayor($objDetalleSeccion->secciones_id, $objDetalleSeccion->peso));
                //cambiamos los pesos de ambos
                $this->detalle_secciones_m->update($detalle_seccion_id, array("peso" => $objDetalleSeccionMayor->peso));
                $this->detalle_secciones_m->update($objDetalleSeccionMayor->id, array("peso" => $objDetalleSeccion->peso));
                //echo json_encode(array("error" => "0", "mayor" => $objDetalleSeccionMayor, "menor" => $objDetalleSeccion));
                echo json_encode(array("error" => "0", "seccion_id" => $objDetalleSeccion->secciones_id));
            } else {
                echo json_encode(array("error" => "1"));
            }
        }
    }

    /**
     * Método para subir el item de detalle seccion
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $detalle_seccion_id
     */
    public function subir_detalle_seccion($detalle_seccion_id) {
        if ($this->input->is_ajax_request()) {
            $objDetalleSeccion = $this->detalle_secciones_m->get($detalle_seccion_id);
            if (count($objDetalleSeccion) > 0) {
                //obtenemos el objeto detalle del anterior peso a cambiarse
                $objDetalleSeccionMenor = $this->agregar_atributos_detalle_seccion($this->detalle_secciones_m->obtener_detalle_seccion_menor($objDetalleSeccion->secciones_id, $objDetalleSeccion->peso));
                //cambiamos los pesos de ambos
                $this->detalle_secciones_m->update($detalle_seccion_id, array("peso" => $objDetalleSeccionMenor->peso));
                $this->detalle_secciones_m->update($objDetalleSeccionMenor->id, array("peso" => $objDetalleSeccion->peso));
                echo json_encode(array("error" => "0", "seccion_id" => $objDetalleSeccion->secciones_id));
            } else {
                echo json_encode(array("error" => "1"));
            }
        }
    }

    /**
     * Método para agregar propiedades de imagenes y nombres al detalle seccion
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param object $objDetalleSeccion
     * @return object
     */
    private function agregar_atributos_detalle_seccion(&$objDetalleSeccion) {
        if (is_array($objDetalleSeccion)) {
            $objDetalleSeccion = $objDetalleSeccion[0];
        }
        return $objDetalleSeccion;
    }

    /**
     * Método para eliminar completamente del sistema
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $id
     * @param string $tipo
     */
    public function eliminar_completamente($id, $tipo) {
        if ($this->input->is_ajax_request()) {
            switch ($tipo) {
                case 'maestro':
                    //Elimininamos todos los maestros en detalle secciones
                    //primero verificamos que no tenga ningun video en estado publicado
                    break;
                case 'video':
                    //Eliminamos todo los videos en los detalles secciones
                    $this->detalle_secciones_m->delete_by(array("videos_id" => $id));
                    //Eliminamos los videos que estén en la tabla grupo detalle
                    $this->grupo_detalle_m->delete_by(array("video_id" => $id));
                    //Eliminamos sus imagenes
                    //$this->imagen_m->delete_by(array("videos_id" => $id));
                    //Eliminamos sus tags relacionados
                    $this->video_tags_m->delete_by(array("videos_id" => $id));
                    //eliminamos el video
                    $this->videos_m->delete($id);
                    break;
                case 'canal':
                    break;
                case 'portada':
                    break;
                case 'seccion':
                    break;
            }
        }
    }

    /**
     * Método de tipo vista para cargar datos a la interface de importación de videos
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $canal_id
     */
    public function importacion($canal_id) {
        $objCanal = $this->canales_m->get($canal_id);
        $arrayProgramme = $this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:programa'), 'canales_id' => $canal_id), 'nombre');
        $arrayColeccionVideo = $this->eliminar_maestros_relacionados($this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:coleccion'), 'canales_id' => $canal_id), 'nombre'));
        $arrayList = $this->eliminar_maestros_relacionados($this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:lista'), 'canales_id' => $canal_id), 'nombre'));
        //$this->vd($arrayColeccionVideo);
        $this->template
                ->title($this->module_details['name'])
                ->append_js('module::jquery.alerts.js')
                ->append_css('module::jquery.alerts.css')
                ->set('programa', $arrayProgramme)
                ->set('coleccion', $arrayColeccionVideo)
                ->set('lista', $arrayList)
                ->set('objCanal', $objCanal);
        $this->input->is_ajax_request() ? $this->template->build('admin/tables/canales') : $this->template->build('admin/importacion');
    }

    /**
     * Método para eliminar maestros que sean padres o hijos en la relación
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param array $arrayMaestro
     * @return array
     */
    private function eliminar_maestros_relacionados(&$arrayMaestro) {
        if (count($arrayMaestro) > 0) {
            foreach ($arrayMaestro as $master_id => $name_master) {
                if ($master_id > 0) {
                    if ($this->_isParentOrChild($master_id)) {
                        unset($arrayMaestro[$master_id]);
                    }
                }
            }
        }
        return $arrayMaestro;
    }

    /**
     * Método para editar el título y descripcion de una portada
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $portada_id
     */
    public function editar_portada($portada_id) {
        if ($this->input->is_ajax_request()) {
            $this->portada_m->update($portada_id, array("nombre" => $this->input->post('nombre'), "descripcion" => $this->input->post('descripcion'), "estado_migracion" => $this->config->item('migracion:actualizado')));
            echo json_encode(array("value" => "1", "portada_id" => $portada_id, "nombre" => $this->input->post('nombre'), "descripcion" => $this->input->post('descripcion')));
        }
    }

    /**
     * Método para obtener el nombre y descripcion de una portada
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $portada_id
     */
    public function obtener_portada($portada_id) {
        if ($this->input->is_ajax_request()) {
            $objPortada = $this->portada_m->get($portada_id);
            if (count($objPortada) > 0) {
                echo json_encode(array("value" => "1", "nombre" => $objPortada->nombre, "descripcion" => $objPortada->descripcion));
            } else {
                echo json_encode(array("value" => "0"));
            }
        }
    }

    /**
     * Método para reordenar las secciones de una portada
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param array $dato
     */
    public function ordenarListaSecciones() {
        if ($this->input->is_ajax_request()) {
            $arraySecciones = $this->input->post();
            if (is_array($arraySecciones)) {
                if (count($arraySecciones) > 0) {
                    foreach ($arraySecciones as $puntero => $array_seccion_id) {
                        if (is_array($array_seccion_id)) {
                            if (count($array_seccion_id)) {
                                //obtenemos la lista original de la BD
                                $lista_original = $this->secciones_m->where_in('id', $array_seccion_id)->order_by('peso', 'ASC')->get_many_by(array());
                                if (count($lista_original) > 0) {
                                    $array_original = array();
                                    foreach ($lista_original as $index => $oSeccion) {
                                        if ($oSeccion->peso == NULL) {
                                            array_push($array_original, $index);
                                        } else {
                                            array_push($array_original, $oSeccion->peso);
                                        }
                                    }
                                }
                                //actualizamos las nuevas posiciones
                                $cont = 0;
                                foreach ($array_seccion_id as $peso => $s_id) {
                                    $this->secciones_m->update($s_id, array("peso" => $array_original[$cont], "estado_migracion" => $this->config->item('migracion:actualizado')));
                                    $this->procesos_lib->curlActualizarPesoSeccion($s_id, $array_original[$cont]);
                                    $cont++;
                                }
                            }
                        }
                    }
                }
            }
            echo json_encode(array("value" => "1"));
        }
    }

    /**
     * Método para reenviar los videos en esta error
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $video_id
     */
    public function reenviar_video($video_id) {
        if ($this->input->is_ajax_request()) {
            echo json_encode(array("value" => "1"));
        }
    }

}

/* End of file admin.php */