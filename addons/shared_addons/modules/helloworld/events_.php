<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * HelloWorld Events Class
 * 
 * @package     PyroCMS
 * @subpackage  HelloWorld Module
 * @category    events
 * @author      Gaby
 */
class Events_HelloWorld 
{
    protected $ci;
    
    public function __construct()
    {
        $this->ci = &get_instance();
        
        // register the public_controller event when this file is autoloaded
        Events::register('admin_controller', array($this, 'run'));
     }
    
    // this will be triggered by the Events::trigger('admin_controller') code in Admin_Controller.php
    public function run()
    {
        //$this->ci->load->model('sample/sample_m'); 
        //$this->ci->sample_m->get_all();
        
        echo 'El Admin Controller se ha lanzado';
        //exit;        
        
        // you can load a model or etc here if you like using $this->ci->load();
        return 'El Admin Controller se ha lanzado';        
    }
    
}
/* End of file events.php */