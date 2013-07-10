<?php
/**
 * Serves uploading and search features
 * 
 * @author Joe Robles <joe.robles.pdj@gmail.com>
 */
class cmsApi extends MX_Controller {
    
    function __construct() {
        $this->load->library("cmsapi_lib");
    }
    
    public function upload()
    {
        if ($this->input->post()) {
            $this->cmsapi_lib->uploadVideo($this->input->post(), $_FILES);
        }
        $this->template->build('cmsapi/upload');
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
    
    public function jerarquia($jerarquia)
    {
        return $this->cmsapi_lib->jerarquia($jerarquia);
    }
}