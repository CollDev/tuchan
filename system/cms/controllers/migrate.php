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
    
    public function widget()
    {
        $objVideos = $this->migrate_lib->getVideosList();
        
        $this->template
            ->set('videos', $objVideos)
            ->build('migrate/widget');
    }
}