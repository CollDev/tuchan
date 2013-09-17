<?php
/**
 * Complements cmsapi
 *
 * @author Joe Robles <joe.robles.pdj@gmail.com>
 */
class migrate_lib extends MX_Controller {
    
    function __construct()
    {
        $this->load->model("videos/videos_m");
    }

    public function getVideosList()
    {
        return $this->videos_m->getAll();
    }
}