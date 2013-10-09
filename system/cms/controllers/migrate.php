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


    public function widget($key_canal = false)
    {
        if ($key_canal) {
            $objVideos = $this->migrate_lib->getVideosList($key_canal);

            $this->template
                ->set('videos', $objVideos)
                ->build('migrate/widget');
        } else {
            $this->template
                ->build('cmsapi/error');
        }
    }
    
    public function upload()
    {
        return $this->migrate_lib->upload($_FILES);
    }
    
    public function verificar_estado_video($embed_code)
    {
        return $this->migrate_lib->verificar_estado_video($embed_code);
    }
    
    public function wget()
    {
        return $this->migrate_lib->wget();
    }
    
    public function receive()
    {
        return $this->migrate_lib->receive();
    }
}