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
    
    public function widget($canal_id)
    {
        $this->template
            ->set('canal_id', $canal_id)
            ->set('motor', $this->config->item('motor'))
            ->build('cmsapi/widget');
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
    
    public function search($search,$dateini = "",$datefin = "")
    {
        return $this->cmsapi_lib->search($search,$dateini,$datefin);
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
}