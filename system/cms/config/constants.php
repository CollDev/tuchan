<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | File and Directory Modes
  |--------------------------------------------------------------------------
  |
  | These prefs are used when checking and setting modes when working
  | with the file system.  The defaults are fine on servers with proper
  | security, but you may wish (or even need) to change the values in
  | certain environments (Apache running a separate process for each
  | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
  | always be used to set the mode correctly.
  |
 */
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

define('IS_SECURE', (string) (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on'));

/*
  |--------------------------------------------------------------------------
  | Docment root folders
  |--------------------------------------------------------------------------
  |
  | These constants use existing location information to work out web root, etc.
  |
 */

// Base URL (keeps this crazy sh*t out of the config.php
if (isset($_SERVER['HTTP_HOST'])) {
    $base_url = (IS_SECURE ? 'https' : 'http')
            . '://' . $_SERVER['HTTP_HOST']
            . str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

    // Base URI (It's different to base URL!)
    $base_uri = parse_url($base_url, PHP_URL_PATH);
    if (substr($base_uri, 0, 1) != '/')
        $base_uri = '/' . $base_uri;
    if (substr($base_uri, -1, 1) != '/')
        $base_uri .= '/';
}

else {
    $base_url = 'http://localhost/';
    $base_uri = '/';
}

// Define these values to be used later on
define('BASE_URL', $base_url);
define('BASE_URI', $base_uri);
define('APPPATH_URI', BASE_URI . APPPATH);
if (isset($_SERVER['DOCUMENT_ROOT'])) {
    define('PATH_VIDEOS', $_SERVER['DOCUMENT_ROOT'] . "/uploads/videos/");
    define('PATH_SQL',$_SERVER['DOCUMENT_ROOT'] . "/procesos/sql/");
}


// We dont need these variables any more
unset($base_uri, $base_url);

/*
  |--------------------------------------------------------------------------
  | PyroCMS Version
  |--------------------------------------------------------------------------
  |
  | Which version of PyroCMS is currently running?
  |
 */

define('CMS_VERSION', '2.1.5');

/*
  |--------------------------------------------------------------------------
  | PyroCMS Edition
  |--------------------------------------------------------------------------
  |
  | Community or Professional?
  |
 */

define('CMS_EDITION', 'Community');

/*
  |--------------------------------------------------------------------------
  | PyroCMS Release Date
  |--------------------------------------------------------------------------
  |
  | When was the current version of PyroCMS released?
  |
 */

define('CMS_DATE', '11/01/2012');
define('TIPO_IMAGEN_ISO', '5');
define('TIPO_IMAGEN_LOGO', '6');
define('ESTADO_ACTIVO', '1');
define('ESTADO_PUBLICADO', '2');
define("PATH_ELEMENTOS", "http://dev.e.micanal.e3.pe/");
define('APIURL', 'http://api.liquidplatform.com/2.0');
define('WEBURL', 'http://webtv.liquidplatform.com/2.0/uploadMedia');

//define('MOTOR', 'http://dev.micanal.pe/');


// Buscador
define('EC_CLIENTE_SOAP', 'busca/busqueda.wsdl');

define('TIME_LIMIT',3500);
/*
|--------------------------------------------------------------------------
| Custom constants
|--------------------------------------------------------------------------
*/



/* End of file constants.php */