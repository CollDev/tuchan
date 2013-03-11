<?php
<<<<<<< HEAD
define('UPLOAD_IMAGENES_VIDEOS', 'uploads/imagenes/');
define('UPLOAD_VIDEOS', 'uploads/videos/');

$config['videos:imagenes'] = UPLOAD_IMAGENES_VIDEOS;
$config['videos:videos']   = UPLOAD_VIDEOS;
=======
define('UPLOAD_IMAGENES_VIDEOS', './uploads/imagenes/');
define('UPLOAD_VIDEOS', 'uploads/videos/');

$config['videos:post_max_size'] = '5000M';
$config['videos:upload_max_filesize'] = '1000M';
$config['videos:max_execution_time'] = '10000';

$config['videos:imagenes'] = UPLOAD_IMAGENES_VIDEOS;
$config['videos:videos']   = UPLOAD_VIDEOS;

$config['videos:formatos'] = 'mp4|mpg|flv|avi|wmv';
$config['videos:extension'] = 'vid';

//identificadores para los tipos de maestros
$config['videos:programa'] = '3';
$config['videos:coleccion'] = '2';
$config['videos:lista'] = '1';

//estados de los videos en el flujo de upload
$config['status:codificando'] = '0';
$config['status:codificando'] = '0';
$config['liquid:nuevo'] = '0';

//estados de las imagenes
$config['imagen:borrador'] = '0';
$config['imagen:publicado'] = '1';
$config['imagen:eliminado'] = '2';

//tipo de imagenes
$config['imagen:small'] = '1';
$config['imagen:medium'] = '2';
$config['imagen:large'] = '3';
$config['imagen:extralarge'] = '4';

//tamaños minimos de la imagen
$config['imagen:minWidth'] = '260';
$config['imagen:minHeight'] = '140';
$config['imagen:maxSize'] = '5242880';
$config['imagen:formatos'] = 'jpg|png|jpeg';

//tipos de tag
$config['tag:tematicas'] = '1';
$config['tag:personajes'] = '2';

//servidor elemento
$config['server:elemento'] = 'dev.e.micanal.e3.pe';

//protocolos
$config['protocolo:http'] = 'http://';
>>>>>>> 1201ee8a8121b87db20ea4af381bef22058262ef
