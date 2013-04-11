<?php
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

//estados de los grupo maestros
$config['estado:borrador'] = '0';
$config['estado:publicado'] = '1';
$config['estado:eliminado'] = '2';

//estado de migración
$config['migracion:nuevo'] = '0';
$config['migracion:actualizado'] = '9';

//estados de los videos en el flujo de upload
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
$config['imagen:iso'] = '5';
$config['imagen:logo'] = '6';

//tamaños minimos de la imagen
$config['imagen:minWidth'] = '260';
$config['imagen:minHeight'] = '140';
$config['imagen:widthPortada'] = '1100';
$config['imagen:heightPortada'] = '520';
$config['imagen:maxSize'] = '5242880';
$config['imagen:formatos'] = 'jpg|png|jpeg';

//procedencia
$config['procedencia:liquid'] = '1';

//tipos de tag
$config['tag:tematicas'] = '1';
$config['tag:personajes'] = '2';

//servidor elemento
$config['server:elemento'] = 'dev.e.micanal.e3.pe';

//protocolos
$config['protocolo:http'] = 'http://';

//secciones
$config['seccion:destacado'] = '1';
$config['seccion:programa'] = '2';
$config['seccion:coleccion'] = '3';
$config['seccion:lista'] = '4';
$config['seccion:video'] = '5';
$config['seccion:visto'] = '6';
$config['seccion:comentado'] = '7';
$config['seccion:valorado'] = '8';
$config['seccion:reciente'] = '9';
$config['seccion:perzonalizado'] = '10';

//templates
$config['template:programa'] = '6';

//portadas
$config['portada:canal'] = '5';
$config['portada:programa'] = '4';
$config['portada:tag'] = '3';
$config['portada:categoria'] = '2';
$config['portada:principal'] = '1';

$config['template:destacado'] = '1';
$config['template:destacado_canal'] = '7';

//grupos
$config['grupo:administrador-canales'] = '4';

//tipo de canal
$config['canal:mi_canal'] = '5';