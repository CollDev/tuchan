<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Accesos module
 *
 * @author MiCanal Dev Team
 */
class Module_Accesos extends Module 
{
    public $version = '1.0';    

    public function info() 
    {
        return array(
            'name' => array(
                'es' => 'Accesos'
            ),
            'description' => array(
                'es' => 'Controla qué usuarios pueden acceder a los canales especificados.'
            ),
            'frontend' => false,
            'backend' => true,
            //'menu' => 'users',
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