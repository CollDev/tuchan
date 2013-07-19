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
    
    public function widget($canal_id)
    {
        if ($this->input->post()) {
            $this->cmsapi_lib->widget($this->input->post(), $_FILES);
        }
        $this->template
                ->set('canal_id', $canal_id)
                ->build('cmsapi/widget');
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
}