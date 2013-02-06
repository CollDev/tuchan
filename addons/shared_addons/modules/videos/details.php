<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Canales module
 *
 * @author MiCanal Dev Team
 */
class Module_Videos extends Module 
{
    public $version = '1.0';    

    public function info() 
    {           
        // Verificar si existe la sesión 'canal_usuario'
        if ($this->session->userdata('canal_usuario') && $this->session->userdata('canal_usuario') != "") {
            
            $opc_canales =  array(
                'name' => array(
                    'es' => 'Videos',
                ),
                'description' => array(
                    'es' => 'Carga de Videos',
                ),
                'frontend' => false,
                'backend' => true,
                'skip_xss' => true,
                'menu' => 'false'
            );
            
        } 
        
        return $opc_canales;

    }

    public function install() 
    {
        return true;
    }

    public function uninstall() 
    {
        return true;
    }

    public function upgrade($old_version) {
        // Your Upgrade Logic  
        return TRUE;
    }

    public function help() {
        // Return a string containing help info  
        // You could include a file and return it here.  
        return "No Help";
    }

}