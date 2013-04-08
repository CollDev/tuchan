<?php
class Admin extends Admin_Controller 
{  
     /**
     * The current active section
     *
     * @var string
     */
    protected $section = 'posts';
 
     public function __construct()
     {  
         parent::__construct();          
         $this->lang->load(array('helloworld'));
     }  
     
     function index() 
     {  
         $this->data->displayMessage = "Hello World";  
         $this->template->build('display',$this->data);  
     }               
 }  
?>
