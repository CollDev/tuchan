<?php
class Module_HelloWorld extends Module 
{  
     public $version = '1.0';  
     
     public function info()
     {  
         return array(  
             'name' => array(  
                 'es' => 'Hola Mundo',  
             ),  
             'description' => array(  
                 'es' => 'Este es el módulo Hola Mundo.',  
             ),  
             'frontend' => FALSE,  
             'backend' => TRUE,  
             //'menu' => 'content',               
             'menu' => 'tablas', // nuevo item menu principal,
             'sections' => array(
                    'posts' => array(
                            'name' => 'global:dashboard',
                            'uri' => 'admin/helloworld',
                            'shortcuts' => array(
                                        array(
                                           'name' => 'helloworld:create_title',
                                           'uri' => 'admin/blog/create',
                                           'class' => 'add'
                                        ),
                             ),
                     ),
                     'test' => array(
                            'name' => 'helloworld:test_label',
                            'uri' => 'admin/helloworld',
                            'shortcuts' => array(
                                        array(
                                            'name' => 'cat_create_title',
                                            'uri' => 'admin/blog/categories/create',
                                            'class' => 'add'
                                        ),
                            ),
                    ),
            ),
             
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
