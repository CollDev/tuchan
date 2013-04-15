<?php
class Module_TipoVideo extends Module 
{  
     public $version = '1.0';  
     
     public function info()
     {  
         return array(  
             'name' => array(  
                 'es' => 'Tipo video',  
             ),  
             'description' => array(  
                 'es' => 'Este es el módulo Tipo video.',  
             ),  
             'frontend' => FALSE,  
             'backend' => TRUE,  
             'menu' => 'tablas', // nuevo item menu principal
             
         );  
     }  
     
     public function install()    
     {  
         return true;  
     }  
     
     public function uninstall()
     {  
         return true;  
     }  
     
     public function upgrade($old_version)
     {  
         // Your Upgrade Logic  
         return TRUE;  
     }  
     
     public function help()
     {  
         // Return a string containing help info  
         // You could include a file and return it here.  
         return "No Help";  
     }  
 }
?>
