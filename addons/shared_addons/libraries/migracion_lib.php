<?php

/**
 * Libreria para la migración de videos ya subidos. 
 * La libreria se encargara de parsear los videos x canal para registrarlo en nuestra Base de datos
 * @author Johnny Huamani <johnny1402@gmail.com>
 * @name Migracion
 * @package Migracion
 * @version 0.1
 */
class Migracion_lib extends MX_Controller {

    /**
     * propiedad que permite almacenar el key del canal
     * @var string 
     */
    private $key = '';

    /**
     * propiedad que almacena la URL de la api al que se solicitará los datos
     * @var string 
     */
    private $url = '';

    /**
     * Definimos el limite de la paginación para iterar
     * @var int 
     */
    private $pagina = 9999;

    /**
     * Variable para definir los campos que necesitamos que nos retorne
     * @var string 
     */
    private $filtro = '';

    /**
     * cadena q se desea buscar
     * @var string 
     */
    private $search = '';

    /**
     * Método para cargar los modelos, librerias y configuraciones
     */
    public function __construct() {
        $this->load->model('canales/canales_m');
        $this->load->model('videos/videos_m');
        $this->load->model('videos/imagen_m');
        $this->load->model('videos/tipo_imagen_m');
        $this->load->model('videos/tags_m');
        $this->load->model('videos/video_tags_m');
        //$this->config->load('videos/uploads');
        $this->url = $this->config->item('migracion:url');
        $this->filtro = $this->config->item('migracion:filtro');
        $this->pagina = $this->config->item('migracion:paginas');
        $this->search = $this->config->item('migracion:tag');
        $this->load->library('procesos_lib');
        $this->load->library('portadas_lib');
        $this->load->library('sincronizar_lib');
        $this->load->library("Procesos/log");
    }

    /**
     * Método para inciar la migración listando canales y llamando otros metodos
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @return boolean
     */
    public function iniciar_migracion_masiva() {
        $returnValue = FALSE;
        $objColeccionCanal = $this->canales_m->get_many_by(array("estado" => $this->config->item('estado:publicado')));
        if (count($objColeccionCanal) > 0) {
            foreach ($objColeccionCanal as $puntero => $objCanal) {
                if (strlen(trim($objCanal->apikey)) > 0) {
                    $this->migrar_canal($objCanal);
                    break;
                }
            }
        }
        return $returnValue;
    }

    /**
     * Metodo para migrar los videos de un solo canal
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param object $objCanal
     */
    public function migrar_canal($objCanal, $extra = array()) {
        //obtenemos el path del api
        $cantidad = 0;
        for ($indice = 0; $indice < $this->pagina; $indice++) {
            if (count($extra) > 0) {
                $ruta_api = $this->generarApi($objCanal, $indice, $extra);
            } else {
                $ruta_api = $this->generarApi($objCanal, $indice);
            }
            $data = @simplexml_load_file($ruta_api);
            //$this->vd($data);
            /// die();
            if ($data) {
                $cantidad+= $this->obtener_objeto_bean_video($data, $objCanal, $extra);
            } else {
                break;
            }
        }
        return $cantidad;
    }

    /**
     * Método para iterar la data y retornar objeto bean para guardar en la BD
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param object $data
     * @param object $objCanal
     */
    private function obtener_objeto_bean_video($data, $objCanal, $extra) {
        //iteramos el contenido de Media, ahí están la lista de videos e imagenes
        $user_id = (int) $this->session->userdata('user_id');
        $lista_videos = $data->Media;
        $contador = 0;
        foreach ($lista_videos as $puntero => $objVideo) {
            if (property_exists($objVideo, 'files')) {
                if ($objVideo->published) {
                    $objFile = $this->obtenerFile($objVideo->files);
                    //$oVideo = $this->videos_m->like('codigo', $objFile->id, 'none')->get_by(array());
                    $oVideo = $this->videos_m->like('codigo', $objVideo->id, 'none')->get_by(array());
                    if (count($oVideo) > 0) {//el video ya se encuentra registrado
                    } else { // es un video que no se encuentra en nuestra BD
                        $objBeanVideo = new stdClass();
                        $objBeanVideo->id = NULL;
                        $objBeanVideo->tipo_videos_id = $this->config->item('videos:normal');
                        $objBeanVideo->categorias_id = $this->config->item('categoria:modas');
                        $objBeanVideo->usuarios_id = $user_id;
                        $objBeanVideo->canales_id = $objCanal->id;
                        $objBeanVideo->nid = NULL;
                        $objBeanVideo->titulo = $this->format_title($objVideo->title);
                        $objBeanVideo->alias = '';
                        $objBeanVideo->descripcion = $this->format_descripcion($objVideo->description);
                        $objBeanVideo->fragmento = 0;
                        $objBeanVideo->codigo = $this->generar_codigo($objVideo->id);
                        $objBeanVideo->reproducciones = 0;
                        $objVideoInfo = $objFile->videoInfo;
                        $objBeanVideo->duracion = $this->formatSeconds($objVideoInfo->duration / 1000); //number_format($this->getStamp($objFile->duration), 2);
                        $objBeanVideo->fecha_publicacion_inicio = '0000-00-00 00:00:00';
                        $objBeanVideo->fecha_publicacion_fin = '0000-00-00 00:00:00';
                        $objBeanVideo->fecha_transmision = date("Y-m-d", strtotime($objVideo->postDate));
                        $objBeanVideo->horario_transmision_inicio = '00:00:00';
                        $objBeanVideo->horario_transmision_fin = '00:00:00';
                        $objBeanVideo->ubicacion = '';
                        $objBeanVideo->id_mongo = NULL;

                        $objBeanVideo->estado = 2;
                        $objBeanVideo->estado_liquid = 6;

                        $objBeanVideo->fecha_registro = date("Y-m-d H:i:s", strtotime($objVideo->postDate)); //date("Y-m-d H:i:s");
                        $objBeanVideo->usuario_registro = $user_id;
                        $objBeanVideo->fecha_actualizacion = date("Y-m-d H:i:s", strtotime($objVideo->postDate));
                        $objBeanVideo->usuario_actualizacion = $user_id;
                        $objBeanVideo->estado_migracion = $this->config->item('migracion:nuevo');
                        $objBeanVideo->fecha_migracion = date("Y-m-d H:i:s");
                        $objBeanVideo->fecha_migracion_actualizacion = date("Y-m-d H:i:s");
                        $objBeanVideo->estado_migracion_sphinx_tit = 0;
                        $objBeanVideo->fecha_migracion_sphinx_tit = '0000-00-00 00:00:00';
                        $objBeanVideo->fecha_migracion_actualizacion_sphinx_tit = '0000-00-00 00:00:00';
                        $objBeanVideo->estado_migracion_sphinx_des = NULL;
                        $objBeanVideo->fecha_migracion_sphinx_des = '0000-00-00 00:00:00';
                        $objBeanVideo->fecha_migracion_actualizacion_sphinx_des = '0000-00-00 00:00:00';
                        $objBeanVideo->valorizacion = 0;
                        $objBeanVideo->comentarios = 0;
                        $objBeanVideo->ruta = $this->generar_ruta_video($objVideo, $objVideo->thumbs, $objFile);
                        $objBeanVideo->padre = NULL;
                        $objBeanVideo->estado_migracion_sphinx = 0;
                        $objBeanVideo->fecha_migracion_sphinx = '0000-00-00 00:00:00';
                        $objBeanVideo->fecha_migracion_actualizacion_sphinx = '0000-00-00 00:00:00';
                        $objBeanVideo->procedencia = $this->config->item('procedencia:migracion');
                        $objBeanVideoSaved = $this->videos_m->save($objBeanVideo);
                        //registramos el detalle de grupo maestro
                        $this->registrar_detalle_maestro($objBeanVideoSaved, $extra);
                        //guardamos las imagenes de cada video
                        $this->guardar_imagenes($objBeanVideoSaved->id, $objVideo->thumbs);
                        //guardamos los tags x video
                        $this->registrar_tags($objBeanVideoSaved->id, $objVideo->tags);
                        
                        //registramos en las secciones con la libreria portadas_lib
                        //$this->portadas_lib->agregar_video($objBeanVideoSaved->id);
                        $this->sincronizar_lib->agregar_video($objBeanVideoSaved->id, 'importacion');
                        //disparamos el proceso para la publicacion en el motor
                        $this->procesos_lib->curlActualizarVideosXId($objBeanVideoSaved->id);
                        $contador++;
                    }
                }
            }
            //solo para un item
            //break;
        }
        return $contador;
    }

    /**
     * Método para registrar la relación de videos con un maestro
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param object $objBeanVideo
     */
    private function registrar_detalle_maestro($objBeanVideo, $extra) {
        $user_id = (int) $this->session->userdata('user_id');
        $objBeanGrupoDetalle = new stdClass();
        $objBeanGrupoDetalle->id = NULL;
        $objBeanGrupoDetalle->grupo_maestro_padre = $extra['maestro_id'];
        $objBeanGrupoDetalle->grupo_maestro_id = NULL;
        $objBeanGrupoDetalle->video_id = $objBeanVideo->id;
        $objBeanGrupoDetalle->tipo_grupo_maestros_id = $this->config->item('videos:coleccion');
        $objBeanGrupoDetalle->id_mongo = NULL;
        $objBeanGrupoDetalle->estado = 1;
        $objBeanGrupoDetalle->fecha_registro = date("Y-m-d H:i:s");
        $objBeanGrupoDetalle->usuario_registro = $user_id;
        $objBeanGrupoDetalle->fecha_actualizacion = date("Y-m-d H:i:s");
        $objBeanGrupoDetalle->usuario_actualizacion = $user_id;
        $objBeanGrupoDetalle->estado_migracion = 0;
        $objBeanGrupoDetalle->fecha_migracion = '0000-00-00 00:00:00';
        $objBeanGrupoDetalle->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
        $objBeanGrupoDetalleSaved = $this->grupo_detalle_m->saveMaestroDetalle($objBeanGrupoDetalle);
    }

    /**
     * Método para registrar tags x video
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $video_id
     * @param object $objTags
     */
    private function registrar_tags($video_id, $objTags) {
        if (property_exists($objTags, 'tag')) {
            $user_id = (int) $this->session->userdata('user_id');
            //$arrayTag = (array) $objTags->tag;
            $arrayTag = $objTags->tag;
            if (count($arrayTag) > 0) {
                foreach ($arrayTag as $puntero => $tag) {
                    $aTag = $this->tags_m->like('nombre', $tag, 'none')->get_many_by(array("tipo_tags_id" => "1"));
                    if (count($aTag) > 0) {
                        $objTagExistente = $this->tags_m->like('nombre', $tag, 'none')->get_by(array("tipo_tags_id" => "1"));
                        //verificamos si la relacion existe
                        $objVideoTag = $this->video_tags_m->get_by(array("tags_id" => $objTagExistente->id, "videos_id" => $video_id));
                        //Log::erroLog("johnny debug : video tag: " .$objVideoTag->tags_id."=>".$objVideoTag->videos_id);
                        if (count($objVideoTag) == 0) {
                            //registramos la relacion de tag con el video
                            $objBeanVideoTag = new stdClass();
                            $objBeanVideoTag->tags_id = $objTagExistente->id;
                            $objBeanVideoTag->videos_id = $video_id;
                            $objBeanVideoTag->estado = 1;
                            $objBeanVideoTag->fecha_registro = date("Y-m-d H:i:s");
                            $objBeanVideoTag->usuario_registro = $user_id;
                            $objBeanVideoTag->fecha_actualizacion = date("Y-m-d H:i:s");
                            $objBeanVideoTag->usuario_actualizacion = $user_id;
                            $objBeanVideoTag->estado_migracion_sphinx = 0;
                            $objBeanVideoTag->fecha_migracion_sphinx = date("Y-m-d H:i:s");
                            $objBeanVideoTag->fecha_migracion_actualizacion_sphinx = date("Y-m-d H:i:s");
                            $objBeanVideoTagSaved = $this->video_tags_m->saveVideoTags($objBeanVideoTag);
                        }
                    } else {
                        $objBeanTag = new stdClass();
                        $objBeanTag->id = NULL;
                        $objBeanTag->tipo_tags_id = 1;
                        $objBeanTag->nombre = $tag;
                        $objBeanTag->descripcion = $tag;
                        $objBeanTag->alias = $tag;
                        $objBeanTag->estado = 1;
                        $objBeanTag->fecha_registro = date("Y-m-d H:i:s");
                        $objBeanTag->usuario_registro = $user_id;
                        $objBeanTag->fecha_actualizacion = date("Y-m-d H:i:s");
                        $objBeanTag->usuario_actualizacion = $user_id;
                        $objBeanTag->estado_migracion = 0;
                        $objBeanTag->fecha_migracion = '0000-00-00 00:00:00';
                        $objBeanTag->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                        $objBeanTag->estado_migracion_sphinx = 0;
                        $objBeanTag->fecha_migracion_sphinx = date("Y-m-d H:i:s");
                        $objBeanTag->fecha_migracion_actualizacion_sphinx = '0000-00-00 00:00:00';
                        $objBeanTagSaved = $this->tags_m->saveTag($objBeanTag);
                        //registramos la relacion de tag con el video
                        $objBeanVideoTag = new stdClass();
                        $objBeanVideoTag->tags_id = $objBeanTagSaved->id;
                        $objBeanVideoTag->videos_id = $video_id;
                        $objBeanVideoTag->estado = 1;
                        $objBeanVideoTag->fecha_registro = date("Y-m-d H:i:s");
                        $objBeanVideoTag->usuario_registro = $user_id;
                        $objBeanVideoTag->fecha_actualizacion = date("Y-m-d H:i:s");
                        $objBeanVideoTag->usuario_actualizacion = $user_id;
                        $objBeanVideoTag->estado_migracion_sphinx = 0;
                        $objBeanVideoTag->fecha_migracion_sphinx = date("Y-m-d H:i:s");
                        $objBeanVideoTag->fecha_migracion_actualizacion_sphinx = date("Y-m-d H:i:s");
                        $objBeanVideoTagSaved = $this->video_tags_m->saveVideoTags($objBeanVideoTag);
                    }
                }
            }
        }
    }

    /**
     * Método para guardar las imagenes del video
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $video_id
     * @param object $objThumbs
     */
    private function guardar_imagenes($video_id, $objThumbs) {
        $user_id = (int) $this->session->userdata('user_id');
        if (is_object($objThumbs)) {
            if (property_exists($objThumbs, 'thumb')) {
                $arrayThum = $objThumbs->thumb;
                foreach ($arrayThum as $puntero => $objThumbnail) {
                    if ($this->obtenerTipoImagen($objThumbnail) > 0) {
                        $objBeanImagen = new stdClass();
                        $objBeanImagen->id = NULL;
                        $objBeanImagen->canales_id = NULL;
                        $objBeanImagen->grupo_maestros_id = NULL;
                        $objBeanImagen->videos_id = $video_id;
                        $objBeanImagen->imagen = $objThumbnail->url;
                        $objBeanImagen->tipo_imagen_id = $this->obtenerTipoImagen($objThumbnail);
                        $objBeanImagen->estado = 1;
                        $objBeanImagen->fecha_registro = date("Y-m-d H:i:s");
                        $objBeanImagen->usuario_registro = $user_id;
                        $objBeanImagen->fecha_actualizacion = date("Y-m-d H:i:s");
                        $objBeanImagen->usuario_actualizacion = $user_id;
                        $objBeanImagen->estado_migracion = 0;
                        $objBeanImagen->fecha_migracion = '0000-00-00 00:00:00';
                        $objBeanImagen->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                        $objBeanImagen->imagen_padre = NULL;
                        $objBeanImagen->procedencia = 1;
                        $objBeanImagen->imagen_anterior = NULL;
                        //desactivamos las imagenes del mismo tipo
                        $this->imagen_m->desabilitarImagenes($video_id, $objBeanImagen->tipo_imagen_id, 'video');
                        $objBeanImagenSaved = $this->imagen_m->saveImage($objBeanImagen);
                    }
                }
            }
        }
    }

    /**
     * Método para identificar el tipo de imagen por sus dimenciones
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param type $objThumbnail
     * @return int tipo de imagen
     */
    private function obtenerTipoImagen($objThumbnail) {
        $returnValue = 0;
        $tipoImagen = $this->tipo_imagen_m->listType();
        if (count($tipoImagen) > 0) {
            foreach ($tipoImagen as $puntero => $objTipoImage) {
                if ($this->obtenerTipo($objTipoImage, $objThumbnail)) {
                    $returnValue = $objTipoImage->id;
                }
//                if ($objTipoImage->ancho == $objThumbnail->width && $objTipoImage->alto == $objThumbnail->height) {
//                    $returnValue = $objTipoImage->id;
//                }
            }
        }
        return $returnValue;
    }

    /**
     * Método para obtener el tipo con un margen de error
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param object $objTipoImage
     * @param object $objThumbnail
     * @return boolean
     */
    private function obtenerTipo($objTipoImage, $objThumbnail) {
        $returnValue = FALSE;
        $ancho_mayor = $objTipoImage->ancho + $this->config->item('migracion:margen_error_imagen');
        $alto_mayor = $objTipoImage->alto + $this->config->item('migracion:margen_error_imagen');
        $ancho_menor = $objTipoImage->ancho - $this->config->item('migracion:margen_error_imagen');
        $alto_menor = $objTipoImage->alto - $this->config->item('migracion:margen_error_imagen');
        
        if($objThumbnail->width <= $ancho_mayor && $objThumbnail->width >= $ancho_menor && $objThumbnail->height <= $alto_mayor && $objThumbnail->height >= $alto_menor){
            $returnValue = TRUE;
        }
        return $returnValue;
    }

    /**
     * Método para generar el código del video
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param object $objCodigo
     * @return string
     */
    private function generar_codigo($objCodigo) {
        $returnValue = $objCodigo;
        if (is_object($objCodigo)) {
            $arrayCodigo = (array) $objCodigo;
            $returnValue = $arrayCodigo[0];
        }
        return $returnValue;
    }

    /**
     * Método para formatear el tiempo de duracion de un video
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $aul_milisegundos
     * @return int
     */
    private function getStamp($aul_milisegundos) {
        $total = ($aul_milisegundos / 1000);
        $total = ($total / 60);
        return $total;
    }

    /**
     * Método para convertir segundos a formato time
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $seconds
     * @return time
     */
    private function formatSeconds($seconds) {
        $hours = 0;
        $milliseconds = str_replace("0.", '', $seconds - floor($seconds));

        if ($seconds > 3600) {
            $hours = floor($seconds / 3600);
        }
        $seconds = $seconds % 3600;


        return str_pad($hours, 2, '0', STR_PAD_LEFT)
                . gmdate(':i:s', $seconds)
                . ($milliseconds ? ".$milliseconds" : '')
        ;
    }

    /**
     * Método para generar la URL del video
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param object $objThumbs
     * @return string
     */
    private function generar_ruta_video($objVideo, $objThumbs, $objFile) {
        $returnValue = '';
        if (is_object($objThumbs)) {
            if (property_exists($objThumbs, 'thumb')) {
                $arrayThum = $objThumbs->thumb;
                foreach ($arrayThum as $puntero => $objThumbnail) {
                    $url = explode('/', $objThumbnail->url);
                    $cantidad_word = count($url);
                    $url[$cantidad_word - 3] = 'video';
                    $url[$cantidad_word - 2] = $objFile->id;
                    $url[$cantidad_word - 1] = $objFile->fileName;
                    $returnValue = implode('/', $url);
                    break;
                }
            }
        }
        return $returnValue;
    }

    /**
     * Método para obtener el titulo del video
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param object $title
     * @return string
     */
    private function format_title($title) {
        $returValue = $title;
        if (is_object($title)) {
            $title = (array) $title;
            if (count($title) > 0) {
                $returValue = $title[0];
            } else {
                $returValue = '';
            }
        }
        return $returValue;
    }

    /**
     * Método para formatear la descripcion del video
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param object $descripcion
     * @return string
     */
    private function format_descripcion($descripcion) {
        $returValue = $descripcion;
        if (is_object($descripcion)) {
            $descripcion = (array) $descripcion;
            if (count($descripcion) > 0) {
                $returValue = $descripcion[0];
            } else {
                $returValue = '';
            }
        }
        return $returValue;
    }

    /**
     * Método para obtener un objeto file del video correcto
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param arrayObject $objColeccionArchivos
     * @return array
     */
    private function obtenerFile($objColeccionArchivos) {
        $returnValue = array();
        if (property_exists($objColeccionArchivos, 'file')) {
            $arrayFiles = $objColeccionArchivos->file;
            if (count($arrayFiles) > 0) {
                foreach ($arrayFiles as $puntero => $objFile) {
                    if (property_exists($objFile, 'output')) {
                        $objOutput = $objFile->output;
                        if ($objOutput->name == $this->config->item('migracion:output')) {
                            $returnValue = $objFile;
                            break;
                        }
                    }
                }
            }
        }
        return $returnValue;
    }

    /**
     * Método para armar la ruta de la api
     * @param object $objCanal
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @return string
     */
    private function generarApi($objCanal, $pagina = 0, $extra = array()) {
        $returnValue = '';
        $returnValue = $this->url . $objCanal->apikey .'&'.$this->search.$extra['tag'].'&first=' . $pagina . '&' . $this->filtro;
        //$returnValue = $this->url . $objCanal->apikey . '&first=' . $pagina;
        //echo $returnValue;
        return $returnValue;
    }

    /**
     * metodo para debuguear variables con formato
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param undefined $var
     */
    private function vd($var) {
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }

}
