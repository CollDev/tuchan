<?php
/**
 * Serves uploading and search features
 * 
 * @author Joe Robles <joe.robles.pdj@gmail.com>
 */
class cmsApi extends MX_Controller {

    function __construct()
    {
        $this->load->library("cmsapi_lib");
    }
    
    public function widget($key_canal = false)
    {
        if ($key_canal) {
            $objCanal = $this->cmsapi_lib->getCanalByKey($key_canal);
            if ($objCanal != null) {
                $this->template
                    ->set('canal_id', $objCanal->id)
                    ->set('post_url', $objCanal->post_url)
                    ->set('motor', $this->config->item('motor'))
                    ->set('version', WIDGET_VERSION)
                    ->build('cmsapi/widget');
            } else {
                $this->template
                    ->build('cmsapi/error');
            }
        } else {
            $this->template
                ->build('cmsapi/error');
        }
    }

    public function getCanalesList()
    {
        return $this->cmsapi_lib->getCanalesList();
    }

    public function getProgramasList($canal_id)
    {
        return $this->cmsapi_lib->getProgramasList($canal_id);
    }
    
    public function getCategoriasList()
    {
        return $this->cmsapi_lib->getCategoriasList();
    }
    
    public function getColeccionesList($programa_id)
    {
        return $this->cmsapi_lib->getColeccionesList($programa_id);
    }
    
    public function getListasList($coleccion_id)
    {
        return $this->cmsapi_lib->getListasList($coleccion_id);
    }
    
    public function search($search, $dateini = "", $datefin = "")
    {
        return $this->cmsapi_lib->search($search, $dateini, $datefin);
    }
    
    public function corte($video_id)
    {
        return $this->cmsapi_lib->corte($video_id);
    }
    
    public function post_upload()
    {
        return $this->cmsapi_lib->post_upload($this->input->post(), $_FILES);
    }
    
    public function insertCorteVideo($canal_id, $video_id)
    {
        return $this->cmsapi_lib->insertCorteVideo($canal_id, $video_id);
    }
    
    public function verificar_estado_video($video_id)
    {
        return $this->cmsapi_lib->verificar_estado_video($video_id);
    }
    
    public function edit($canal_id, $video_id)
    {
        return $this->cmsapi_lib->editar_video($canal_id, $video_id, $this->input->post());
    }
    
    public function tematicas()
    {
        return $this->cmsapi_lib->tematicas();
    }
    
    public function personajes()
    {
        return $this->cmsapi_lib->personajes();
    }
}