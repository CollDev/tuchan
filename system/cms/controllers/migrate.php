<?php
/**
 * Serves uploading and search features
 * 
 * @author Joe Robles <joe.robles.pdj@gmail.com>
 */
class migrate extends MX_Controller {

    function __construct()
    {
        $this->load->library("migrate_lib");
    }
    
    public function getLiquidApi()
    {
        return $this->migrate_lib->getLiquidApi();
    }


    public function widget()
    {
        $objVideos = $this->migrate_lib->getVideosList();
        
        $this->template
            ->set('videos', $objVideos)
            ->build('migrate/widget');
    }
    
    public function upload()
    {
        return $this->migrate_lib->upload($_FILES);
    }
    
    public function verificar_estado_video($embed_code)
    {
        return $this->migrate_lib->verificar_estado_video($embed_code);
    }
    
    public function wget($url)
    {
        return $this->migrate_lib->wget($url);
    }
}