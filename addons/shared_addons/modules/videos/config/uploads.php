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
$config['extension:mp4'] = 'mp4';

//identificadores para los tipos de maestros
$config['videos:programa'] = '3';
$config['videos:coleccion'] = '2';
$config['videos:lista'] = '1';
$config['videos:video'] = '4';
$config['videos:canal'] = '5';

//tipo de videos
$config['videos:normal'] = '1';
$config['videos:premium'] = '2';

//categorias
$config['categoria:modas'] = '13';

//estados de los grupo maestros
$config['estado:borrador'] = '0';
$config['estado:publicado'] = '1';
$config['estado:eliminado'] = '2';


//estados de los videos
$config['video:codificando'] = '0';
$config['video:borrador'] = '1';
$config['video:publicado'] = '2';
$config['video:eliminado'] = '3';
$config['video:error'] = '4';

//estado de migración
$config['migracion:nuevo'] = '0';
$config['migracion:actualizado'] = '9';
$config['sphinx:actualizar'] = '9';
$config['sphinx:nuevo'] = '0';

//estados de los videos en el flujo de upload
$config['status:codificando'] = '0';
$config['liquid:nuevo'] = '0';
$config['liquid:mp4'] = '2';

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
$config['procedencia:elemento'] = '0';
$config['procedencia:migracion'] = '1';
$config['procedencia:youtube'] = '2';

//tipos de tag
$config['tag:tematicas'] = '1';
$config['tag:personajes'] = '2';

//servidor elemento
//$config['server:elemento'] = 'dev.e.micanal.e3.pe';

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
$config['template:3items'] = '2';
$config['template:4items'] = '3';
$config['template:5items'] = '4';
$config['template:8items'] = '5';
$config['template:nitems'] = '6';
$config['template:destacado_canal'] = '7';
$config['template:8itemsdescripcion'] = '8';

//grupos
$config['grupo:administrador-canales'] = '4';

//tipo de canal
$config['canal:mi_canal'] = '5';
//intervalo de tiempo en segundos para verificar el estado de videos
$config['video:segundos'] = '20000';
$config['video:verificar'] = '0';

//submenus
$config['submenu:carga_unitaria'] = 'Subir video';
$config['submenu:carga_youtube'] = 'Agregar video YouTube';

//Migración
//$config['migracion:no_canal'] = '1';
//$config['migracion:programa'] = '492';
//$config['migracion:coleccion'] = '497';
$config['migracion:programa'] = '13';
$config['migracion:coleccion'] = '14';

//tipo de canal
$config['tipo_canal:micanal'] = '5';
