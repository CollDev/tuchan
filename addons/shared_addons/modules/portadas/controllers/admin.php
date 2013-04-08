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
        $this->load->model('canales/canales_m');
        $this->config->load('videos/uploads');
        $this->load->model('canales/portada_m');
        $this->load->model('canales/secciones_m');
        $this->lang->load('portadas');
    }

    public function index() {
        $this->template
                ->title($this->module_details['name'])
                ->set('portales', 'df');
        $this->input->is_ajax_request() ? $this->template->build('admin/tables/canales') : $this->template->build('admin/index');
    }

    public function canal($canal_id) {
        $objCanal = $this->canales_m->get($canal_id);
        $module_details['name'] = "Portada  del canal " . $objCanal->nombre;
        //parametros de paginacion
        $base_where = array("canales_id" => $canal_id);
        $keyword = '';
        if ($this->input->post('f_keywords'))
            $keyword = $this->input->post('f_keywords');
        // Create pagination links
        if (strlen(trim($keyword)) > 0) {
            $total_rows = $this->portada_m->like('nombre', $keyword)->count_by($base_where);
        } else {
            $total_rows = $this->portada_m->count_by($base_where);
        }
        $pagination = create_pagination('admin/portadas/canal/' . $canal_id . '/index', $total_rows, 10, 6);

        // Using this data, get the relevant results
        if (strlen(trim($keyword)) > 0) {
            $coleccionPortada = $this->portada_m->like('nombre', $keyword)->limit($pagination['limit'])->get_many_by($base_where);
        } else {
            $coleccionPortada = $this->portada_m->limit($pagination['limit'])->get_many_by($base_where);
        }

        if (count($coleccionPortada) > 0) {
            foreach ($coleccionPortada as $index => $objPortada) {
                $objPortada->secciones = $this->secciones_m->get_many_by(array("portadas_id" => $objPortada->id));
                $coleccionPortada[$index] = $objPortada;
            }
        }

        //do we need to unset the layout because the request is ajax?
        $this->input->is_ajax_request() and $this->template->set_layout(FALSE);

        $this->template
                ->title($this->module_details['name'])
                ->append_js('admin/filter.js')
                //->append_js('module::jquery.ddslick.min.js')
                ->set_partial('filters', 'admin/partials/filters')
                ->set_partial('portadas', 'admin/tables/portadas')
                ->set('pagination', $pagination)
                ->set('portadas', $coleccionPortada);
        $this->input->is_ajax_request() ? $this->template->build('admin/tables/portadas') : $this->template->build('admin/canal');
    }

    public function seccion($seccion_id) {
        //cargamos el objetos seccion
        if($seccion_id > 0){
            $objSeccion = $this->secciones_m->get($seccion_id);
        }        
        $title = $this->module_details['name'] = 'Sección - '.$objSeccion->nombre;
        $this->template
                ->title($this->module_details['name'])
                ->append_js('jquery-ui.js')
                //->append_js('admin/jquery.ddslick.min.js')
                //->set_partial('filters', 'admin/partials/filters')
                //->set_partial('portadas', 'admin/tables/portadas')
                //->set('pagination', $pagination)
                ->set('objSeccion', $objSeccion)
                ->set('title', $title);
        $this->template->build('admin/seccion');
    }

    /**
     * retornamos un array object de portadas
     * @param int $canal_id
     * @return array
     */
    public function obtenerPortadas($canal_id) {
        $returnValue = array();
        if ($canal_id > 0) {
            $returnValue = $this->portada_m->get_many_by(array("canales_id" => $canal_id));
        }
        return $returnValue;
    }

    public function vd($var) {
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }

}

/* End of file admin.php */