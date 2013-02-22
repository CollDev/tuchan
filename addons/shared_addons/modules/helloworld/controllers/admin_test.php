<?php
class Admin extends Admin_Controller 
{  
     /**
     * The current active section
     *
     * @var string
     */
    //protected $section = 'categories';
 
     public function __construct()
     {  
         parent::__construct();          
         $this->lang->load(array('helloworld'));
     }  
     
     function index() 
     {  
         $this->data->displayMessage = "Test";  
         $this->template->build('test',$this->data);  
     }  
 }  
?>
