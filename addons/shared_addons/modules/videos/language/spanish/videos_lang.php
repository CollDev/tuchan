<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$lang['videos:posts_title'] = 'Videos';

// labels - cabeceras listado
$lang['videos:imagen_label'] = 'Imagen';
$lang['videos:titulo_label'] = 'Título';
$lang['videos:categoria_label'] = 'Categoría';
$lang['videos:descripcion_label'] = 'Descripción';
$lang['videos:tipo_label'] = 'Tipo';
$lang['videos:programa_label'] = 'Programa';
$lang['videos:coleccion_label'] = 'Colección';
$lang['videos:lista_reprod_label'] = 'Lista Reproducción';
$lang['videos:fragmento_label'] = 'Fragmento';
$lang['videos:fuente_label'] = 'Fuente';
$lang['videos:etiquetas_tematicas_label'] = 'Etiquetas temáticas';
$lang['videos:etiquetas_personajes_label'] = 'Etiquetas personajes';
$lang['videos:duracion'] = 'Duración';
$lang['videos:fecha_subida_label'] = 'Fecha subida';
$lang['videos:fecha_publicacion_label'] = 'Fecha publicación';
$lang['videos:fecha_publicacion_inicio_label'] = 'Fecha publicación inicio';
$lang['videos:fecha_publicacion_fin_label'] = 'Fecha publicación fin';
$lang['videos:fecha_transmision_label'] = 'Fecha transmisión';
$lang['videos:horario_transmision_inicio_label'] = 'Horario trans. inicio';
$lang['videos:horario_transmision_fin_label'] = 'Horario trans. fin';
$lang['videos:ubicacion_label'] = 'Ubicación';
$lang['videos:tamanio_label'] = 'Tamaño';
$lang['videos:inicio'] = 'Inicio';
$lang['videos:fin'] = 'Fin';
$lang['videos:horaio_transmision'] = 'Horario de transmisión';
$lang['videos:avatar'] = 'Imagen';
$lang['videos:require_images'] = 'Es necesario subir una imagen';

// Labels lista de clips
$lang['videos:clips_label'] = 'Clips Relacionados';

// estados
$lang['videos:0_estado'] = 'Codificando';
$lang['videos:1_estado'] = 'Borrador';
$lang['videos:2_estado'] = 'Publicado';
$lang['videos:3_estado'] = 'Eliminado';
$lang['videos:4_estado'] = 'Error';

// estados maestros
$lang['estado:0_estado'] = 'Borrador';
$lang['estado:1_estado'] = 'Publicado';
$lang['estado:2_estado'] = 'Eliminado';

// maestros
$lang['maestro:1_maestro'] = 'Lista de reproducción';
$lang['maestro:2_maestro'] = 'Colección';
$lang['maestro:3_maestro'] = 'Programa';
$lang['maestro:4_maestro'] = 'Video';
$lang['maestro:5_maestro'] = 'Canal';

//tipo maestro
$lang['tipo:1_maestro'] = 'Lista de reproducción';
$lang['tipo:2_maestro'] = 'Colección';
$lang['tipo:3_maestro'] = 'Programa';
$lang['tipo:0_maestro'] = 'Video';

// mensajes
$lang['videos:publish_success'] = 'El video "%s" ha sido publicado.';
$lang['videos:mass_publish_success'] = 'Los videos "%s" han sido publicados.';
$lang['videos:publish_error'] = 'El video no pudo ser publicado.';
$lang['videos:delete_success'] = 'El video "%s" ha sido eliminado.';
$lang['videos:mass_delete_success'] = 'Los videos "%s" han sido eliminados.';
$lang['videos:delete_error'] = 'Los videos no fueron eliminados.';

//envios masivos
$lang['videos:no_items'] = 'No hay items.';
$lang['videos:title_bulk_load'] = 'Carga masiva';
$lang['videos:quitar'] = 'Quitar';
$lang['videos:title'] = 'Título';
$lang['videos:category'] = 'Categoría';
$lang['videos:programme'] = 'Programa';
$lang['videos:collection'] = 'Colección';
$lang['videos:list_player'] = 'Lista de reproducción';
$lang['videos:label_tematicas'] = 'Etiquetas temáticas';
$lang['videos:label_personajes'] = 'Etiquetas personajes';
$lang['videos:progress'] = 'Progreso';
$lang['videos:action'] = 'Acción';
$lang['videos:video'] = 'Video';
$lang['videos:youtube_url'] = 'Dirección YouTube';
$lang['videos:description'] = 'Description';
$lang['videos:source'] = 'Fuentes';
$lang['videos:add'] = 'Añadir';


//envios unitario
$lang['videos:select_programme'] = 'Seleccione programa.';
$lang['videos:select_fragment'] = 'Seleccione fragmento.';
$lang['videos:select_type_video'] = 'Seleccione tipo video.';
$lang['videos:select_category'] = 'Seleccione categoría.';
$lang['videos:missing_programme'] = 'Ingrese el tipo de programa.';
$lang['videos:add_programme'] = 'Se agregó el nuevo programa satisfactoriamente.';
$lang['videos:select_collection'] = 'Seleccione colección.';
$lang['videos:select_list'] = 'Seleccione lista.';
$lang['videos:missing_select_programme'] = 'Es necesario seleccionar un programa.';
$lang['videos:missing_collection'] = 'Ingrese un nombre de una colección.';
$lang['videos:added_collection'] = 'Se agregó la nueva colección satisfactoriamente.';
$lang['videos:missing_category'] = 'Es necesario seleccionar una categoría.';
$lang['videos:exist_name'] = 'El nombre del grupo maestro ya existe par este canal.';
$lang['videos:select_channel'] = 'Seleccione canal.';
$lang['videos:add_video_success'] = 'El video se registro en forma satisfactoria. En unos momentos será redirigido a la lista de videos.';
$lang['videos:not_found_video'] = 'El archivo no se pudo subir.';
$lang['videos:format_invalid'] = 'El formato del archivo no es el correcto.';
$lang['videos:size_invalid'] = 'El tamaño del archivo no es el correcto.';
$lang['videos:edit_video_success'] = 'Los cambios se guardaron satisfactoriamente';

//validaciones formulario envios unitario
$lang['videos:require_title'] = 'Ingrese un título';
$lang['videos:require_video'] = 'Examine un video para subir al servidor.';
$lang['videos:require_category'] = 'Seleccione una categoría.';
$lang['videos:require_tematicas'] = 'Ingrese las temáticas.';
$lang['videos:require_personajes'] = 'Ingrese personajes.';
$lang['videos:require_type'] = 'Seleccione el tipo de video.';
$lang['videos:require_source'] = 'Seleccione la fuente del video.';
$lang['videos:require_description'] = 'Ingrese la descripción del video.';
$lang['videos:fragment_exist'] = 'Ya existe un video con estos datos.';
$lang['videos:require_inicio'] = 'Ingrese una fecha de inicio';
$lang['videos:require_fin'] = 'Ingrese una fecha de fin';
//maestros
        $lang['maestros:organizar_videos'] = 'Organizar videos';
$lang['maestros:registrar_destacado'] = 'Registrar en la sección destacado del canal.';
$lang['maestros:maestro_existe'] = 'El nombre maestro ya existe.';
$lang['coleccion:temporada'] = 'Temporada 1';

//imagenes
$lang['imagen:cambiar_imagen'] = 'Cambiar imagen';
$lang['imagen:subir_imagen'] = 'Subir imagen';
$lang['imagen:restaurar_imagen'] = 'Restaurar';
/* End of file videos_lang.php */