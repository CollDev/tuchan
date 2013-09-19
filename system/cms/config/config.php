<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
|
| URL to your CodeIgniter root. Typically this will be your base URL,
| WITH a trailing slash:
|
|	http://example.com/
|
| If this is not set then CodeIgniter will guess the protocol, domain and
| path to your installation.
|
*/
$config['base_url']	= '';

/*
|--------------------------------------------------------------------------
| Index File
|--------------------------------------------------------------------------
|
| Typically this will be your index.php file, unless you've renamed it to
| something else. If you are using mod_rewrite to remove the page set this
| variable so that it is blank.
|
*/
$config['index_page'] = '';

/*
|--------------------------------------------------------------------------
| URI PROTOCOL
|--------------------------------------------------------------------------
|
| This item determines which server global should be used to retrieve the
| URI string.  The default setting of 'AUTO' works for most servers.
| If your links do not seem to work, try one of the other delicious flavors:
|
| 'AUTO'			Default - auto detects
| 'PATH_INFO'		Uses the PATH_INFO
| 'QUERY_STRING'	Uses the QUERY_STRING
| 'REQUEST_URI'		Uses the REQUEST_URI
| 'ORIG_PATH_INFO'	Uses the ORIG_PATH_INFO
|
*/
$config['uri_protocol']	= 'AUTO';

/*
|--------------------------------------------------------------------------
| URL suffix
|--------------------------------------------------------------------------
|
| This option allows you to add a suffix to all URLs generated by CodeIgniter.
| For more information please see the user guide:
|
| http://codeigniter.com/user_guide/general/urls.html
*/

$config['url_suffix'] = '';

/*
|--------------------------------------------------------------------------
| Default Language
|--------------------------------------------------------------------------
|
| This determines which set of language files should be used. Make sure
| there is an available translation if you intend to use something other
| than english.
|
*/
$config['language']	= "spanish"; // Is overridden in hooks/pick_language.php

/*
|--------------------------------------------------------------------------
| Default Character Set
|--------------------------------------------------------------------------
|
| This determines which character set is used by default in various methods
| that require a character set to be provided.
|
*/
$config['charset'] = 'UTF-8';

/*
|--------------------------------------------------------------------------
| Enable/Disable System Hooks
|--------------------------------------------------------------------------
|
| If you would like to use the 'hooks' feature you must enable it by
| setting this variable to TRUE (boolean).  See the user guide for details.
|
*/
$config['enable_hooks'] = TRUE;


/*
|--------------------------------------------------------------------------
| Class Extension Prefix
|--------------------------------------------------------------------------
|
| This item allows you to set the filename/classname prefix when extending
| native libraries.  For more information please see the user guide:
|
| http://codeigniter.com/user_guide/general/core_classes.html
| http://codeigniter.com/user_guide/general/creating_libraries.html
|
*/
$config['subclass_prefix'] = 'MY_';


/*
|--------------------------------------------------------------------------
| Allowed URL Characters
|--------------------------------------------------------------------------
|
| This lets you specify with a regular expression which characters are permitted
| within your URLs.  When someone tries to submit a URL with disallowed
| characters they will get a warning message.
|
| As a security measure you are STRONGLY encouraged to restrict URLs to
| as few characters as possible.  By default only these are allowed: a-z 0-9~%.:_-
|
| Leave blank to allow all characters -- but only if you are insane.
|
| DO NOT CHANGE THIS UNLESS YOU FULLY UNDERSTAND THE REPERCUSSIONS!!
|
*/
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';


/*
|--------------------------------------------------------------------------
| Enable Query Strings
|--------------------------------------------------------------------------
|
| By default CodeIgniter uses search-engine friendly segment based URLs:
| example.com/who/what/where/
|
| By default CodeIgniter enables access to the $_GET array.  If for some
| reason you would like to disable it, set 'allow_get_array' to FALSE.
|
| You can optionally enable standard query string based URLs:
| example.com?who=me&what=something&where=here
|
| Options are: TRUE or FALSE (boolean)
|
| The other items let you set the query string 'words' that will
| invoke your controllers and its functions:
| example.com/index.php?c=controller&m=function
|
| Please note that some of the helpers won't work as expected when
| this feature is enabled, since CodeIgniter is designed primarily to
| use segment based URLs.
|
*/
$config['allow_get_array']		= TRUE;
$config['enable_query_strings'] = FALSE;
$config['controller_trigger']	= 'c';
$config['function_trigger']		= 'm';
$config['directory_trigger']	= 'd'; // experimental not currently in use

/*
|--------------------------------------------------------------------------
| Error Logging Threshold
|--------------------------------------------------------------------------
|
| If you have enabled error logging, you can set an error threshold to
| determine what gets logged. Threshold options are:
| You can enable error logging by setting a threshold over zero. The
| threshold determines what gets logged. Threshold options are:
|
|	0 = Disables logging, Error logging TURNED OFF
|	1 = Error Messages (including PHP errors)
|	2 = Debug Messages
|	3 = Informational Messages
|	4 = All Messages
|
| You can also pass in a array with threshold levels to show individual error types
|
| 	array(2) = Debug Messages, without Error Messages
|
| For a live site you'll usually only enable Errors (1) to be logged otherwise
| your log files will fill up very fast.
|
*/
$config['log_threshold'] = 1;

/*
|--------------------------------------------------------------------------
| Error Logging Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| system/cms/logs/ folder. Use a full server path with trailing slash.
|
*/
$config['log_path'] = '';

/*
|--------------------------------------------------------------------------
| Date Format for Logs
|--------------------------------------------------------------------------
|
| Each item that is logged has an associated date. You can use PHP date
| codes to set your own date formatting
|
*/
$config['log_date_format'] = 'Y-m-d H:i:s';

/*
|--------------------------------------------------------------------------
| Cache Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| system/cache/ folder.  Use a full server path with trailing slash.
|
*/

// This is set in the pyrocache config so Pro can set the correct SITE_REF
$config['cache_path'] = '';

/*
|--------------------------------------------------------------------------
| Encryption Key
|--------------------------------------------------------------------------
|
| If you use the Encryption class or the Session class you
| MUST set an encryption key.  See the user guide for info.
|
*/
$config['encryption_key'] = "Jiu348^&H%fa";

/*
|--------------------------------------------------------------------------
| Session Variables
|--------------------------------------------------------------------------
|
| 'sess_cookie_name'		= the name you want for the cookie
| 'sess_expiration'			= the number of SECONDS you want the session to last.
|   by default sessions last 7200 seconds (two hours).  Set to zero for no expiration.
| 'sess_expire_on_close'	= Whether to cause the session to expire automatically
|   when the browser window is closed
| 'sess_encrypt_cookie'		= Whether to encrypt the cookie
| 'sess_use_database'		= Whether to save the session data to a database
| 'sess_table_name'			= The name of the session database table
| 'sess_match_ip'			= Whether to match the user's IP address when reading the session data
| 'sess_match_useragent'	= Whether to match the User Agent when reading the session data
| 'sess_time_to_update'		= how many seconds between CI refreshing Session Information
|
*/
$config['sess_cookie_name']		= 'pyrocms' . (ENVIRONMENT !== 'production' ? '_' . ENVIRONMENT : '');
$config['sess_expiration']		= 0;
$config['sess_expire_on_close']	= TRUE;
$config['sess_encrypt_cookie']	= TRUE;
$config['sess_use_database']	= TRUE;
// don't change anything but the 'ci_sessions' part of this. The MSM depends on the 'default_' prefix
$config['sess_table_name']		= 'default_ci_sessions';
$config['sess_match_ip']		= TRUE;
$config['sess_match_useragent']	= TRUE;
$config['sess_time_to_update']	= 300;

/*
|--------------------------------------------------------------------------
| Cookie Related Variables
|--------------------------------------------------------------------------
|
| 'cookie_prefix' = Set a prefix if you need to avoid collisions
| 'cookie_domain' = Set to .your-domain.com for site-wide cookies
| 'cookie_path'   =  Typically will be a forward slash
| 'cookie_secure' =  Cookies will only be set if a secure HTTPS connection exists.
|
*/
// for multi-site logins to work properly we have to set a prefix. We use the subdomain for that or default_ if none exists.
$config['cookie_prefix']	= (substr_count($_SERVER['SERVER_NAME'], '.') > 1) ? substr($_SERVER['SERVER_NAME'], 0, strpos($_SERVER['SERVER_NAME'], '.')) . '_' : 'default_';
$config['cookie_domain']	= ($_SERVER['SERVER_NAME'] == 'localhost') ? '' : $_SERVER['SERVER_NAME'];
$config['cookie_path']		= BASE_URI;
$config['cookie_secure']	= FALSE;

/*
|--------------------------------------------------------------------------
| Global XSS Filtering
|--------------------------------------------------------------------------
|
| Determines whether the XSS filter is always active when GET, POST or
| COOKIE data is encountered
|
*/
$config['global_xss_filtering'] = FALSE;

/*
|--------------------------------------------------------------------------
| Cross Site Request Forgery
|--------------------------------------------------------------------------
| Enables a CSRF cookie token to be set. When set to TRUE, token will be
| checked on a submitted form. If you are accepting user data, it is strongly
| recommended CSRF protection be enabled.
|
| 'csrf_token_name' = The token name
| 'csrf_cookie_name' = The cookie name
| 'csrf_expire' = The number in seconds the token should expire.
| 'csrf_exclude_uris' = Array of URIs which ignore CSRF checks
*/
$config['csrf_protection'] 		= false;//(bool) preg_match('@\/admin(\/.+)?$@', $_SERVER['REQUEST_URI']); // only turn it on for admin panel
$config['csrf_token_name'] 		= 'csrf_hash_name';
$config['csrf_cookie_name'] 	= 'csrf_cookie_name';
$config['csrf_expire'] 			= 7200;
$config['csrf_exclude_uris'] 	= array();

/*
|--------------------------------------------------------------------------
| Output Compression
|--------------------------------------------------------------------------
|
| Enables Gzip output compression for faster page loads.  When enabled,
| the output class will test whether your server supports Gzip.
| Even if it does, however, not all browsers support compression
| so enable only if you are reasonably sure your visitors can handle it.
|
| VERY IMPORTANT:  If you are getting a blank page when compression is enabled it
| means you are prematurely outputting something to your browser. It could
| even be a line of whitespace at the end of one of your scripts.  For
| compression to work, nothing can be sent before the output buffer is called
| by the output class.  Do not 'echo' any values with compression enabled.
|
*/
$config['compress_output'] = FALSE;

/*
|--------------------------------------------------------------------------
| Minify
|--------------------------------------------------------------------------
|
| Removes extra characters (usually unnecessary spaces) from your
| output for faster page load speeds.  Makes your outputted HTML source
| code less readable.
|
*/
$config['minify_output'] = (ENVIRONMENT !== PYRO_DEVELOPMENT); // only do this on

/*
|--------------------------------------------------------------------------
| Master Time Reference
|--------------------------------------------------------------------------
|
| Options are 'local' or 'gmt'.  This pref tells the system whether to use
| your server's local time as the master 'now' reference, or convert it to
| GMT.  See the 'date helper' page of the user guide for information
| regarding date handling.
|
*/
$config['time_reference'] = 'gmt';


/*
|--------------------------------------------------------------------------
| Rewrite PHP Short Tags
|--------------------------------------------------------------------------
|
| If your PHP installation does not have short tag support enabled CI
| can rewrite the tags on-the-fly, enabling you to utilize that syntax
| in your view files.  Options are TRUE or FALSE (boolean)
|
*/
$config['rewrite_short_tags'] = FALSE;


/*
|--------------------------------------------------------------------------
| Reverse Proxy IPs
|--------------------------------------------------------------------------
|
| If your server is behind a reverse proxy, you must whitelist the proxy IP
| addresses from which CodeIgniter should trust the HTTP_X_FORWARDED_FOR
| header in order to properly identify the visitor's IP address.
| Comma-delimited, e.g. '10.0.1.200,10.0.1.201'
|
*/
$config['proxy_ips'] = '';


/*
|--------------------------------------------------------------------------
| Module Locations
|--------------------------------------------------------------------------
|
| Modular Extensions: Where are modules located?
|
*/
$config['modules_locations'] = array(
	APPPATH.'modules/' => '../modules/',
	ADDON_FOLDER.'default/modules/' => '../../../addons/default/modules/',
	SHARED_ADDONPATH.'modules/' => '../../../addons/shared_addons/modules/'
);

            $config['protocolo:http'] = 'http:\/\/';

//servidor elemento
            
$config['server:elemento'] = 'dev.e.micanal.e3.pe';
$config['url:elemento'] = 'http://dev.e3.pe/index.php/api/v1';
$config['apikey:elemento'] = '590ee43e919b1f4baa2125a424f03cd160ff8901';
$config['mensaje:elemento'] = 'cms.micanal.pe';

//servidor pre
/*
$config['server:elemento'] = 'pre.e.micanal.e3.pe';
$config['url:elemento'] = 'http://pre.e3.pe/index.php/api/v1';
$config['apikey:elemento'] = '590ee43e919b1f4baa2125a424f03cd160ff8901';
$config['mensaje:elemento'] = 'cms.micanal.pe';
*/

//servidor pro
/*
$config['server:elemento'] = 'e.micanal.e3.pe';
$config['url:elemento'] = 'http://e3.pe/index.php/api/v1';
$config['apikey:elemento'] = '590ee43e919b1f4baa2125a424f03cd160ff8901';
$config['mensaje:elemento'] = 'cms.micanal.pe';
*/
            
//rutas de uploads
$config['path:video'] = FCPATH.'uploads/videos/';
$config['path:imagen'] = FCPATH.'uploads/imagenes/';
$config['path:temp'] = FCPATH.'uploads/temp/';
$config['path:log'] = FCPATH.'uploads/log/';

//url de imagenes predefinidas
$config['url:default_imagen'] = BASE_URL.'uploads/imagenes/';
$config['url:temp'] = BASE_URL.'uploads/temp/';
$config['url:logo'] = BASE_URL.'system/cms/themes/pyrocms/img/image_no_found.png';
$config['url:iso'] = BASE_URL.'system/cms/themes/pyrocms/img/icon_tv4.png';
$config['url:portada'] = BASE_URL.'system/cms/themes/pyrocms/img/imagen_portada_default.jpg';

//variables de la migracion de canales
//$config['migracion:url'] = 'http://fast.api.liquidplatform.com/2.0/medias/?search=tags:LIFWeek&key=';
$config['migracion:url'] = 'http://fast.api.liquidplatform.com/2.0/medias/?key=';
$config['migracion:filtro'] = 'filter=id;files;published;title;thumbs;tags;description;postDate';
$config['migracion:output'] = '_RAW';
$config['migracion:paginas'] = '10';
$config['migracion:tag'] = 'search=tags:';
$config['migracion:margen_error_imagen'] = '70';

$config['fragmento'] = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15);

$config['v_e:codificando'] = '0';
$config['v_e:borrador'] = '1';
$config['v_e:publicado'] = '2';
$config['v_e:eliminado'] = '3';
$config['v_e:error'] = '4'; 

$config['v_l:nuevo'] = '0';
$config['v_l:codificando'] = '1';
$config['v_l:codificado'] = '2';
$config['v_l:subiendo'] = '3';
$config['v_l:subido'] = '4'; 
$config['v_l:activo'] = '5'; 
$config['v_l:publicado'] = '6'; 


/* local */ 
//$config['motor'] = 'http://ci.micanal.deve';

/* DEV */ 
$config['motor'] = 'http://dev.micanal.pe';

/* PRE */ 
//$config['motor'] = 'http://pre.micanal.pe';


// SPHINX - local
/*
 $config['host:sphinx'] = '192.168.1.35';
 $config['port:sphinx'] = 3312;
*/

// SPHINX - DEV

 $config['host:sphinx'] = '10.78.43.81';//'10.85.138.3';
 $config['port:sphinx'] = 3312;

  
 // SPHINX - PRE
 /*
 $config['host:sphinx'] = '10.85.138.3';
 $config['port:sphinx'] = '3312';
 */
 
 // SPHINX - PRO
 /*
 $config['host:sphinx'] = '10.85.138.3';
 $config['port:sphinx'] = '3312';
 */
 
 $config['nivel:canal'] = '0';
 $config['nivel:programa'] = '1';
 $config['nivel:coleccion'] = '2';
 $config['nivel:listareproduccion'] ='3';
 $config['nivel:video'] = '4';
 
 $config['time:delete:video']="-1 week";
 $config['datetime:unpublishDate:video']="2012-01-01T00:00:00-00:00";
 
 
$config['proce:micanal'] = '0';
$config['proce:migracion'] = '1';
$config['proce:youtube'] = '2';
$config['proce:widget'] = '3';

$config['america:cms:url'] = 'http://dev.americatv.multidiario.com/';
$config['america:cms:user'] = 'admin';
$config['america:cms:pass'] = 'eeph0luSh2ou';


/* End of file config.php */
