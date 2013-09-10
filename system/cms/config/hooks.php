<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

// PRE CONTROLLER HOOKS
$hook['pre_controller'][] = array(
    'function' => 'pick_language',
    'filename' => 'pick_language.php',
    'filepath' => 'hooks'
);
$hook['pre_controller'][] = array(
    'class'    => 'GetCanalesAssigned',
    'function' => 'canales_assigned',
    'filename' => 'get_canales_assigned.php',
    'filepath' => 'hooks',
    'params'   => array('beer', 'wine', 'snacks')
);

# PERFORM-TWEAK: Disable this to make your system slightly quicker
$hook['pre_controller'][] = array(
	'function' => 'check_installed',
	'filename' => 'check_installed.php',
	'filepath' => 'hooks'
);

/* End of file hooks.php */