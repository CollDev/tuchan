<?php
class Admin extends Admin_Controller 
{  
     //protected $section = "items"; // This must match the name in the 'sections' field in details.php
 
     public function __construct()
     {  
         parent::__construct();  
     }  
     
     function index() 
     {  
         $this->data->displayMessage = "Tipo Video";  
         $this->template->build('display',$this->data);  
     }  
 }  
?>
