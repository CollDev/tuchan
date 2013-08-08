<?php
/**
 * Complements cmsapi
 *
 * @author Joe Robles <joe.robles.pdj@gmail.com>
 */
class cmsapi_lib extends MX_Controller {
    
    function __construct() {
        $this->load->library("procesos_lib");
        $this->load->library('session');
        $this->load->model("categoria_mp");
        $this->load->model("grupo_maestros_mp");
        $this->load->model("canal_mp");
        $this->load->model("grupo_detalle_mp");
        $this->load->model("videos/grupo_detalle_m");
        $this->load->model("videos_mp");
        $this->load->model("videos/videos_m");
        $this->load->model("videos/tags_m");
        $this->load->model("videos/video_tags_m");
        $this->load->model("sphinx/sphinx_m");
    }

    public function getProgramasList($canal_id)
    {
        $returnValue = array();
        $arrayData = $this->grupo_maestros_mp->getProgramasList(array('tipo_grupo_maestro_id' => 3, 'canales_id' => $canal_id), 'nombre');
        if (count($arrayData) > 0) {
            foreach ($arrayData as $index => $objTipo) {
                if($objTipo->estado < 2){
                    $returnValue[$objTipo->id] = $objTipo->nombre;
                }
            }
        }
        
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($returnValue);
    }
    
    public function getCanalesList()
    {
        $returnValue = array();
        $arrayData = $this->canal_mp->getCanalesList(array('estado' => 1), 'nombre');
        if (count($arrayData) > 0) {
            foreach ($arrayData as $index => $objTipo) {
                if ($objTipo->tipo_canales_id != 5) {
                    $returnValue[$objTipo->id] = $objTipo->nombre;
                }
            }
        }
        
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($returnValue);
    }
    
    public function getCategoriasList()
    {
        $returnValue = array();
        $arrayData = $this->categoria_mp->getCategoriasList(array('categorias_id' => 0), 'nombre');
        if (count($arrayData) > 0) {
            foreach($arrayData as $index => $objTipo) {
                if ($this->categoria_mp->isParent($objTipo->id)) {
                    $returnValue[$objTipo->nombre] = $this->getChildrenCategorias($objTipo->id);
                } else {
                    $returnValue[$objTipo->id] = $objTipo->nombre;
                }
            }
        }
        
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($returnValue);
    }
    
    private function getChildrenCategorias($category_id){
        $returnValue = array();
        $arrayData = $this->categoria_mp->getCategoriasList(array("categorias_id" => $category_id));
        if (count($arrayData) > 0) {
            foreach($arrayData as $index => $objTipo) {
                    $returnValue[$objTipo->id] = $objTipo->nombre;
            }
        }
        
        return $returnValue;        
    }
    
    public function getColeccionesList($programa_id)
    {
        $arrayCollection = $this->grupo_detalle_mp->getColeccionesList(array("grupo_maestro_padre" => $programa_id));
        //var_dump($arrayCollection);exit;
        $returnValue = array();
        if (count($arrayCollection) > 0) {
            $array_id_maestro = array();
            foreach ($arrayCollection as $index => $objCollection) {
                if ($objCollection->grupo_maestro_id != NULL) {
                    if ($this->isType($objCollection->grupo_maestro_id, 2)) {
                        array_push($array_id_maestro, $objCollection->grupo_maestro_id);
                    }
                }
            }
            if (count($array_id_maestro) > 0) {
                $arrayCollectionMaestro = $this->grupo_maestros_mp->getListCollection($array_id_maestro);
            } else {
                $arrayCollectionMaestro = array();
            }
            if (count($arrayCollectionMaestro) > 0) {
                foreach ($arrayCollectionMaestro as $indice => $objMaestro) {
                    if ($objMaestro->estado < 2) {
                        $returnValue[$objMaestro->id] = $objMaestro->nombre;
                    }
                }
            }
        }

        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($returnValue);
    }
    
    private function isType($grupo_maestro_id, $type) {
        $returnValue = false;
        $objMaestro = $this->grupo_maestros_mp->get_by(array('id' => $grupo_maestro_id));
        if ($objMaestro[0]->tipo_grupo_maestro_id == $type) {
            $returnValue = true;
        }
        
        return $returnValue;
    }
    
    public function getListasList($coleccion_id)
    {
        $arrayCollection = $this->grupo_detalle_mp->getColeccionesList(array("grupo_maestro_padre" => $coleccion_id));
        $returnValue = array();
        if (count($arrayCollection) > 0) {
            $array_id_maestro = array();
            foreach ($arrayCollection as $index => $objCollection) {
                if ($objCollection->grupo_maestro_id != NULL) {
                    if ($this->isType($objCollection->grupo_maestro_id, 1)) {
                        array_push($array_id_maestro, $objCollection->grupo_maestro_id);
                    }
                }
            }
            if (count($array_id_maestro) > 0) {
                $arrayCollectionMaestro = $this->grupo_maestros_mp->getListCollection($array_id_maestro);
            } else {
                $arrayCollectionMaestro = array();
            }
            if (count($arrayCollectionMaestro) > 0) {
                foreach ($arrayCollectionMaestro as $indice => $objMaestro) {
                    if ($objMaestro->estado < 2) {
                        $returnValue[$objMaestro->id] = $objMaestro->nombre;
                    }
                }
            }
        }
                    
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($returnValue);
    }
    
    public function widget()
    {
        return array();
    }
    
    public function post_upload($post, $files)
    {
        if ($this->input->is_ajax_request()) {
            $ruta_video = str_replace('cmsapi_lib.php', '', __FILE__).'../../../uploads/videos/' . $this->moveUploaded($files);
            $archivo_video = pathinfo($ruta_video);
            $ext = $archivo_video['extension'];
            $size_video = filesize($ruta_video);
            $arrayExt = explode("|", 'mp4|mpg|flv|avi|wmv');
            $return = array();
            if (in_array($ext, $arrayExt)) {
                if ($size_video > 0 && $size_video <= 2147483648) { //10485760=>10MB 2147483648=>2GB
                    if (file_exists($ruta_video) && strlen(trim($archivo_video['basename'])) > 0) {//validamos que exista el archivo

                        $objBeanVideo = new stdClass();
                        $objBeanVideo->id = NULL;
                        $objBeanVideo->tipo_videos_id = $post['int_tipo_video'];
                        $objBeanVideo->categorias_id = $post['categoria'];
                        $objBeanVideo->usuarios_id = 1;
                        $objBeanVideo->canales_id = $post['canal_id'];
                        $objBeanVideo->titulo = $post['titulo'];
                        $objBeanVideo->alias = url_title(strtolower(convert_accented_characters($post['titulo'])));
                        $objBeanVideo->descripcion = $post['descripcion'];
                        $objBeanVideo->fragmento = $post['fragmento'];
                        $objBeanVideo->fecha_publicacion_inicio = date("H:i:s", strtotime($post['fec_pub_ini']));
                        $objBeanVideo->fecha_publicacion_fin = date("H:i:s", strtotime($post['fec_pub_fin']));
                        $objBeanVideo->fecha_transmision = date("Y-m-d H:i:s", strtotime($post['fec_trans']));
                        $objBeanVideo->horario_transmision_inicio = date("H:i:s", strtotime($post['hora_trans_ini']));
                        $objBeanVideo->horario_transmision_fin = date("H:i:s", strtotime($post['hora_trans_fin']));
                        $objBeanVideo->ubicacion = $post['ubicacion'];
                        $objBeanVideo->estado = 0;
                        $objBeanVideo->estado_liquid = 0;
                        $objBeanVideo->fecha_registro = date("Y-m-d H:i:s");
                        $objBeanVideo->usuario_registro = 1;
                        $objBeanVideo->estado_migracion = 0; //estado para mongoDB
                        $objBeanVideo->estado_migracion_sphinx_tit = 0;
                        $objBeanVideo->estado_migracion_sphinx_des = 0;
                        $objBeanVideo->padre = 0;
                        $objBeanVideo->estado_migracion_sphinx = 0;
                        $objBeanVideoSaved = $this->videos_mp->save_video($objBeanVideo);
                        //giardamos los tags de tematica y personajes
                        $this->_saveTagsTematicaPersonajes($objBeanVideoSaved, $post);
                        //guardamos en la tabla grupo detalle
                        $this->_saveVideoMaestroDetalle($objBeanVideoSaved, $post);
                        //cambiar nombre del video por el ID del registro del video 
                        $this->renameVideo($objBeanVideoSaved, $archivo_video['basename']);

                        $return = array(
                            'type' => 'success',
                            'title' => 'Video subido con éxito',
                            'message' => '<a href="'.$this->config->item('motor').'/embed/' . $objBeanVideoSaved->id . '">El archivo subido estará ubicado aquí</a><br>'.$this->config->item('motor').'/embed/' . $objBeanVideoSaved->id,
                            'url' => $this->config->item('motor').'/embed/' . $objBeanVideoSaved->id,
                        );
                    } else {
                        $return = array(
                            'type' => 'danger',
                            'title' => 'Error al subir video.',
                            'message' => 'Por favor vuelva a intentarlo en unos minutos',
                        );
                    }
                } else {
                    $return = array(
                        'type' => 'info',
                        'title' => 'Video muy extenso',
                        'message' => 'El tamaño del video supera el permitido de 2GB.',
                    );
                }
            } else {
                $return = array(
                    'type' => 'info',
                    'title' => 'Formato no permitido',
                    'message' => 'Por favor suba un video: mp4,mpg,flv,avi,wmv',
                );
            }

            header("Content-Type: application/json; charset=utf-8");
            echo json_encode($return);
        }
    }
    
    private function moveUploaded($files)
    {
        $idUniq = uniqid();
        $ext = @end(explode('.', $files['video']['name']));
        $nameVideo = $idUniq . '.' . $ext;
        umask(0);
        move_uploaded_file($files["video"]["tmp_name"], "uploads/videos/" . $nameVideo);
        
        return $nameVideo;
    }
    
    public function _saveTagsTematicaPersonajes($objBeanVideo, $post) {
        $user_id = 1;
        $arrayTagTematicas = explode(",", $post['tematicas']);
        $arraytagPersonajes = explode(",", $post['personajes']);
        if (count($arrayTagTematicas) > 0) {
            foreach ($arrayTagTematicas as $index => $tematica) {
                $tag_id = 0;
                if ($this->tags_m->existTag($tematica, 1)) {
                    $tag_id = $this->tags_m->getIdTag($tematica, 1);
                } else {
                    $objBeanTag = new stdClass();
                    $objBeanTag->id = NULL;
                    $objBeanTag->tipo_tags_id = 1;
                    $objBeanTag->nombre = $tematica;
                    $objBeanTag->descripcion = $tematica;
                    $objBeanTag->alias = url_title(strtolower(convert_accented_characters($tematica)));
                    $objBeanTag->estado = 1;
                    $objBeanTag->fecha_registro = date("Y-m-d H:i:s");
                    $objBeanTag->usuario_registro = $user_id;
                    $objBeanTag->fecha_actualizacion = date("Y-m-d H:i:s");
                    $objBeanTag->usuario_actualizacion = $user_id;
                    $objBeanTag->estado_migracion = 0;
                    $objBeanTag->fecha_migracion = '0000-00-00 00:00:00';
                    $objBeanTag->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                    $objBeanTag->estado_migracion_sphinx = 0;
                    $objBeanTag->fecha_migracion_sphinx = '0000-00-00 00:00:00';
                    $objBeanTag->fecha_migracion_actualizacion_sphinx = '0000-00-00 00:00:00';
                    $objBeanTag = $this->tags_m->saveTag($objBeanTag);
                    $tag_id = $objBeanTag->id;
                }

                //gurdamos la relación de cada tag con su video
                if ($tag_id > 0 && !$this->video_tags_m->existRelacion($tag_id, $objBeanVideo->id)) {
                    $objBeanVideoTag = new stdClass();
                    $objBeanVideoTag->tags_id = $tag_id;
                    $objBeanVideoTag->videos_id = $objBeanVideo->id;
                    $objBeanVideoTag->estado = 1;
                    $objBeanVideoTag->fecha_registro = date("Y-m-d H:i:s");
                    $objBeanVideoTag->usuario_registro = $user_id;
                    $objBeanVideoTag->fecha_actualizacion = date("Y-m-d H:i:s");
                    $objBeanVideoTag->usuario_actualizacion = $user_id;
                    $objBeanVideoTag->estado_migracion_sphinx = 0;
                    $objBeanVideoTag->fecha_migracion_sphinx = '0000-00-00 00:00:00';
                    $objBeanVideoTag->fecha_migracion_actualizacion_sphinx = '0000-00-00 00:00:00';
                    $this->video_tags_m->saveVideoTags($objBeanVideoTag);
                }
            }
            //eliminamos los tags que ya no son necesarios
            $this->_clearOldTags($objBeanVideo, $arrayTagTematicas, 1);
        }

        //guardamos los tag de personajes
        if (count($arraytagPersonajes) > 0) {
            foreach ($arraytagPersonajes as $index => $personaje) {
                $tag_id = 0;
                if ($this->tags_m->existTag($personaje, 2)) {
                    $tag_id = $this->tags_m->getIdTag($personaje, 2);
                } else {
                    $objBeanTag = new stdClass();
                    $objBeanTag->id = NULL;
                    $objBeanTag->tipo_tags_id = 2;
                    $objBeanTag->nombre = $personaje;
                    $objBeanTag->descripcion = $personaje;
                    $objBeanTag->alias = url_title(strtolower(convert_accented_characters($personaje)));
                    $objBeanTag->estado = 1;
                    $objBeanTag->fecha_registro = date("Y-m-d H:i:s");
                    $objBeanTag->usuario_registro = $user_id;
                    $objBeanTag->fecha_actualizacion = date("Y-m-d H:i:s");
                    $objBeanTag->usuario_actualizacion = $user_id;
                    $objBeanTag->estado_migracion = 0;
                    $objBeanTag->fecha_migracion = '0000-00-00 00:00:00';
                    $objBeanTag->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                    $objBeanTag->estado_migracion_sphinx = 0;
                    $objBeanTag->fecha_migracion_sphinx = '0000-00-00 00:00:00';
                    $objBeanTag->fecha_migracion_actualizacion_sphinx = '0000-00-00 00:00:00';
                    $objBeanTag = $this->tags_m->saveTag($objBeanTag);
                    $tag_id = $objBeanTag->id;
                }

                //gurdamos la relación de cada tag con su video
                if ($tag_id > 0 && !$this->video_tags_m->existRelacion($tag_id, $objBeanVideo->id)) {
                    $objBeanVideoTag = new stdClass();
                    $objBeanVideoTag->tags_id = $tag_id;
                    $objBeanVideoTag->videos_id = $objBeanVideo->id;
                    $objBeanVideoTag->estado = 1;
                    $objBeanVideoTag->fecha_registro = date("Y-m-d H:i:s");
                    $objBeanVideoTag->usuario_registro = $user_id;
                    $objBeanVideoTag->fecha_actualizacion = date("Y-m-d H:i:s");
                    $objBeanVideoTag->usuario_actualizacion = $user_id;
                    $objBeanVideoTag->estado_migracion_sphinx = 0;
                    $objBeanVideoTag->fecha_migracion_sphinx = '0000-00-00 00:00:00';
                    $objBeanVideoTag->fecha_migracion_actualizacion_sphinx = '0000-00-00 00:00:00';
                    $this->video_tags_m->saveVideoTags($objBeanVideoTag);
                }
            }

            //eliminamos los tags que ya no son necesarios
            $this->_clearOldTags($objBeanVideo, $arraytagPersonajes, 2);
        }
    }
    
    public function _clearOldTags($objBeanVideo, $arraytag, $type_tag) {
        if ($objBeanVideo->id > 0 && count($arraytag) > 0) {
            $collectionTagsByVideo = $this->_getTagsByIdVideo($objBeanVideo->id, $type_tag);
            if (count($collectionTagsByVideo) > 0) {
                foreach ($collectionTagsByVideo as $index => $objTag) {
                    if (!in_array($objTag->nombre, $arraytag)) {
                        $this->video_tags_m->deleteRelationTagVideo($objBeanVideo->id, $objTag->id);
                    }
                }
            }
        }
    }
    
    public function _getTagsByIdVideo($video_id, $type_tag) {
        $returnValue = array();
        $arrayVideoTags = $this->video_tags_m->getVideoTags(array("videos_id" => $video_id));
        if (count($arrayVideoTags) > 0) {
            $arrayIdTags = array();
            foreach ($arrayVideoTags as $index => $objTagVideo) {
                array_push($arrayIdTags, $objTagVideo->tags_id);
            }
            if (count($arrayIdTags) > 0) {
                $returnValue = $this->tags_m->getTagsByIdTagsByType($arrayIdTags, $type_tag);
            }
        }
        return $returnValue;
    }
    
    public function _saveVideoMaestroDetalle($objBeanVideo, $post, $maestro_detalle_id = NULL) {
        $user_id = 1;
        $objBeanMaestroDetalle = new stdClass();
        $objBeanMaestroDetalle->id = $maestro_detalle_id;
        $objBeanMaestroDetalle->video_id = $objBeanVideo->id;
        if ($maestro_detalle_id == NULL) {
            $objBeanMaestroDetalle->id_mongo = NULL;
            $objBeanMaestroDetalle->estado = 0;
            $objBeanMaestroDetalle->fecha_registro = date("Y-m-d H:i:s");
            $objBeanMaestroDetalle->usuario_registro = $user_id;
            $objBeanMaestroDetalle->estado_migracion = 0;
            $objBeanMaestroDetalle->fecha_migracion = '0000-00-00 00:00:00';
            $objBeanMaestroDetalle->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
            $objBeanMaestroDetalle->grupo_maestro_id = NULL;
        }
        $objBeanMaestroDetalle->fecha_actualizacion = date("Y-m-d H:i:s");
        $objBeanMaestroDetalle->usuario_actualizacion = $user_id;
        $isOkToSave = true;
        if ($post['lista'] > 0) {
            $objBeanMaestroDetalle->grupo_maestro_padre = $post['lista'];
            $objBeanMaestroDetalle->tipo_grupo_maestros_id = 1;
        } else {
            if ($post['coleccion'] > 0) {
                $objBeanMaestroDetalle->grupo_maestro_padre = $post['coleccion'];
                $objBeanMaestroDetalle->tipo_grupo_maestros_id = 2;
            } else {
                if ($post['programa'] > 0) {
                    $objBeanMaestroDetalle->grupo_maestro_padre = $post['programa'];
                    $objBeanMaestroDetalle->tipo_grupo_maestros_id = 3;
                } else {
                    $isOkToSave = false;
                }
            }
        }
        if ($isOkToSave) {
            if ($maestro_detalle_id == NULL) {
                $this->grupo_detalle_m->saveMaestroDetalle($objBeanMaestroDetalle);
            } else {
                $this->grupo_detalle_m->updateMaestroDetalle($objBeanMaestroDetalle);
            }
        }
        return $isOkToSave;
    }
    
    public function renameVideo($objBeanVideo, $name_file) {
        $returnValue = true;
        $path_video_old = str_replace('cmsapi_lib.php', '', __FILE__).'../../../uploads/videos/' . $name_file;
        $ext = pathinfo($path_video_old, PATHINFO_EXTENSION);
        if ($ext != 'mp4') {
            $path_video_new = str_replace('cmsapi_lib.php', '', __FILE__).'../../../uploads/videos/' . $objBeanVideo->id . '.' . 'vid'; // . $ext;
        } else {
            $path_video_new = str_replace('cmsapi_lib.php', '', __FILE__).'../../../uploads/videos/' . $objBeanVideo->id . '.' . 'mp4'; // . $ext;
            $this->videos_m->update($objBeanVideo->id, array("estado_migracion_sphinx" => 9, "estado_migracion" => 9, "estado_liquid" => 2));
        }
        rename($path_video_old, $path_video_new);
        //lanzamos la libreria para registrar el video en las portadas
        $this->procesos_lib->curlProcesoVideosXId($objBeanVideo->id);

        return $returnValue;
    }
    
    public function search($search)           
    {//,$canales_id = null,$dateini=null,$datefin=null
        header("Content-Type: application/json; charset=utf-8");
        
        $dateini=$this->input->get('fecha_inicio',TRUE) ;
        $datefin=$this->input->get('fecha_fin',TRUE);
        $canales_id=$this->input->get('canal_id',TRUE);
        
        $fechaini = null;
        if ($dateini != null) {
            list($dia1, $mes1, $año1) = explode('-', $dateini);            
            $fechaini = strtotime($año1 . '-' . $mes1 . '-' . $dia1 . ' 00:00:00');
        }
        
        $fechafin = null;
        if ($datefin != null) {
            list($dia2, $mes2, $año2) = explode('-', $datefin);
            $fechafin = strtotime($año2 . '-' . $mes2 . '-' . $dia2 . ' 00:00:00');
        }

        //$palabrabusqueda = urldecode(str_replace("-", " ", $palabrabusqueda));
        
        $parametros = array();
        
//        if ($parametro == ESTADO_ACTIVO) {
            $parametros['estado'] = ESTADO_ACTIVO;
            $parametros["peso_videos"] =
                    array('tags' => $this->config->item('peso_tag:sphinx'),
                        'titulo' => $this->config->item('peso_titulo:sphinx'),
                        'descripcion' => $this->config->item('peso_descripcion:sphinx')
            );
//        }
        
        echo $this->sphinx_m->busquedaVideos($parametros,$search,$fechaini,$fechafin,$canales_id);        
    }
    
    public function corte($video_id)
    {
        if ($this->input->post()) {
            
        } else if ($this->input->is_ajax_request()) {
            //agregar metodo para alimentar al objeto para la edicion
            $lista = 0;
            $coleccion = 0;
            $programa = 0;
            //verificar que el video tenga registros en la tabla detalles de maestro
            //obtener el objeto maestro para obtener el ID y tipo
            $objGrupoDetalle = $this->grupo_detalle_mp->get_by(array("video_id" => $video_id));
            if (count($objGrupoDetalle) > 0) {
                $lista = $this->getIdMaestro($objGrupoDetalle->grupo_maestro_padre, 1);
                $coleccion = $this->getIdMaestro($objGrupoDetalle->grupo_maestro_padre, 2);
                $programa = $this->getIdMaestro($objGrupoDetalle->grupo_maestro_padre, 3);
            }

            $objVideo = $this->videos_m->get($video_id);
            if (count($objVideo) > 0) {
                $objBeanForm->video_id = $video_id;
                $objBeanForm->titulo = $objVideo->titulo;
                $objBeanForm->video = '';
                $objBeanForm->descripcion = $objVideo->descripcion;
                $objBeanForm->fragmento = $objVideo->fragmento;
                $objBeanForm->categoria = $objVideo->categorias_id;
                $objBeanForm->tematicas = $this->_getTag($video_id, 1);
                $objBeanForm->personajes = $this->_getTag($video_id, 2);
                $objBeanForm->tipo = $objVideo->tipo_videos_id;
                $objBeanForm->programa = $programa;
                $objBeanForm->coleccion = $coleccion;
                $objBeanForm->lista = $lista;
                $objBeanForm->fec_pub_ini = date("d-m-Y H:i:s", strtotime($objVideo->fecha_publicacion_inicio)); //$objVideo->fecha_publicacion_inicio;
                $objBeanForm->fec_pub_fin = date("d-m-Y H:i:s", strtotime($objVideo->fecha_publicacion_fin)); //$objVideo->fecha_publicacion_fin;
                $objBeanForm->fec_trans = date("d-m-Y", strtotime($objVideo->fecha_transmision)); //$objVideo->fecha_transmision;
                $objBeanForm->hora_trans_ini = $objVideo->horario_transmision_inicio;
                $objBeanForm->hora_trans_fin = $objVideo->horario_transmision_fin;
                $objBeanForm->ubicacion = $objVideo->ubicacion;
                $objBeanForm->canal_id = $objVideo->canales_id;
                $objBeanForm->tipo_maestro = '';
                $objBeanForm->keywords = '';
                $objBeanForm->duracion = $objVideo->duracion;
                $objBeanForm->ruta = trim($objVideo->ruta);
                $objBeanForm->progress_key = uniqid();
                $objBeanForm->tiene_imagen = $this->_tieneAvatar($video_id);
                if ($objBeanForm->tiene_imagen) {
                    $objBeanForm->avatar = $this->_getListImagen($video_id);
                } else {
                    $objBeanForm->avatar = array();
                }

                header("Content-Type: application/json; charset=utf-8");
                echo json_encode($objBeanForm);
            } else {
                header("Content-Type: application/json; charset=utf-8");
                echo json_encode(array());
            }
        }
    }
    
    public function _getTag($video_id, $tag_type) {
        $returnValue = '';
        $arrayVideoTags = $this->video_tags_m->getVideoTags(array("videos_id" => $video_id));
        if (count($arrayVideoTags) > 0) {
            $arrayIdTags = array();
            foreach ($arrayVideoTags as $index => $Tags) {
                array_push($arrayIdTags, $Tags->tags_id);
            }

            $arrayTagsString = $this->tags_m->getListTags($arrayIdTags, $tag_type);
            $arrayTag = array();
            if (count($arrayTagsString) > 0) {
                foreach ($arrayTagsString as $indice => $tag) {
                    array_push($arrayTag, $tag->nombre);
                }
            }
            $returnValue = implode(",", $arrayTag);
        }

        return $returnValue;
    }
    
    public function _tieneAvatar($video_id) {
        $returnValue = false;
        //$this->config->load('videos/uploads');
        if ($video_id > 0) {
            $arrayReturn = $this->imagen_m->getImagen(array("videos_id" => $video_id, "tipo_imagen_id" => $this->config->item('imagen:small')), NULL);
            if (count($arrayReturn) > 0) {
                $returnValue = true;
            }
        }
        return $returnValue;
    }
    
    public function getIdMaestro($grupo_maestro_padre, $type) {
        $objMaestro = $this->grupo_maestro_m->get($grupo_maestro_padre);
        if (count($objMaestro) > 0) {
            if ($objMaestro->tipo_grupo_maestro_id == $type) {
                return $objMaestro->id;
            } else {
                $objMaestroDetalle = $this->grupo_detalle_m->get_by(array("grupo_maestro_id" => $objMaestro->id));
                if (count($objMaestroDetalle) > 0) {
                    return $this->getIdMaestro($objMaestroDetalle->grupo_maestro_padre, $type);
                } else {
                    return 0;
                }
            }
        } else {
            return 0;
        }
    }
    
    public function verificarVideo($canal_id, $video_id, $post)
    {
        $returnValue = false;
        $fromView = false;
        if ($post == NULL) {
            $post = $this->input->post();
            $fromView = true;
        }
        $id_type = 0;
        if ($post['lista'] > 0) {
            $id_type = $post['lista'];
        } else {
            if ($post['coleccion'] > 0) {
                $id_type = $post['coleccion'];
            } else {
                if ($post['programa'] > 0) {
                    $id_type = $post['programa'];
                }
            }
        }
                
        if ($id_type == 0) {
            if ($this->videos_m->existVideo($post['titulo'], $canal_id, $video_id)) {
                $objVideo2 = $this->videos_m->like('titulo', $post['titulo'], 'none')->get_by(array());
                if (count($objVideo2) > 0) {
                    if ($objVideo2->id != $video_id) {
                        $returnValue = true;
                    }
                }
            }
        } else {
            if ($id_type > 0) {
                $objCollectionDetalle = $this->grupo_detalle_m->getGrupoDetalle(array("grupo_maestro_padre" => $id_type));
                if (count($objCollectionDetalle) > 0) {
                    foreach ($objCollectionDetalle as $index => $objDetalle) {
                        if ($objDetalle->video_id != NULL) {
                            if ($this->videos_m->existVideo($post['titulo'], $canal_id, $objDetalle->video_id, $video_id, $id_type)) {
                                $returnValue = true;
                                break;
                            }
                        }
                    }
                } else {
                    $returnValue = false;
                }
            } else {
                $returnValue = false;
            }
        }
        if ($fromView) {
            if ($returnValue) {
                echo json_encode(array("errorValue" => "1"));
            } else {
                echo json_encode(array("errorValue" => "0"));
            }
        } else {
            return $returnValue;
        }
    }
    
    public function insertCorteVideo($canal_id, $video_id)
    {
        if ($this->input->is_ajax_request()) {
            if ($this->verificarVideo($canal_id, $video_id, $this->input->post())) {
                echo json_encode(array("value" => '1'));
            } else {
                $user_id = (int) $this->session->userdata('user_id');
                $objBeanVideo = new stdClass();
                $objBeanVideo->id = $video_id;
                $objBeanVideo->tipo_videos_id = $this->input->post('tipo');
                $objBeanVideo->categorias_id = $this->input->post('categoria');
                $objBeanVideo->usuarios_id = $user_id;
                $objBeanVideo->canales_id = $this->input->post('canal_id');
                $objBeanVideo->titulo = $this->input->post('titulo');
                $objBeanVideo->alias = url_title(strtolower(convert_accented_characters($this->input->post('titulo')))) . '-' . $video_id;
                $objBeanVideo->descripcion = $this->input->post('descripcion_updated');
                $objBeanVideo->fragmento = 0;
                $objBeanVideo->fecha_publicacion_inicio = date("Y-m-d H:i:s", strtotime($this->input->post('fec_pub_ini')));
                $objBeanVideo->fecha_publicacion_fin = date("Y-m-d H:i:s", strtotime($this->input->post('fec_pub_fin')));
                $objBeanVideo->fecha_transmision = date("Y-m-d H:i:s", strtotime($this->input->post('fec_trans')));
                $objBeanVideo->horario_transmision_inicio = $this->input->post('hora_trans_ini');
                $objBeanVideo->horario_transmision_fin = $this->input->post('hora_trans_fin');
                $objBeanVideo->ubicacion = $this->input->post('ubicacion');
                $objBeanVideo->fecha_actualizacion = date("Y-m-d H:i:s");
                $objBeanVideo->usuario_actualizacion = $user_id;

                $objBeanVideo->estado_liquid = 2;
                $objBeanVideo->fecha_registro = date("Y-m-d H:i:s");
                $objBeanVideo->usuario_registro = $user_id;
                $objBeanVideo->estado_migracion = 0;
                $objBeanVideo->estado_migracion_sphinx_tit = 0;
                $objBeanVideo->estado_migracion_sphinx_des = 0;

                $objBeanVideo->estado = 0;
                $objBeanVideo->padre = $video_id;
                $objBeanVideo->estado_migracion_sphinx = $this->config->item('sphinx:nuevo');
                $objBeanVideo->procedencia = $this->config->item('procedencia:micanal');

                $objvideotemp = $this->videos_m->save_video($objBeanVideo);

                $this->_saveTagsTematicaPersonajes($objBeanVideo, $this->input->post());
                //obtenemos el ID del maestro detalle del video
                $objMaestroDetalle = $this->grupo_detalle_m->get_by(array("video_id" => $objBeanVideo->id));
                $maestro_detalle_id = NULL;
                if (count($objMaestroDetalle) > 0) {
                    $maestro_detalle_id = $objMaestroDetalle->id;
                }
                //guardamos en la tabla grupo detalle
                $this->_saveVideoMaestroDetalle($objBeanVideo, $this->input->post(), $maestro_detalle_id);

                Log::erroLog("admin antes  curlCorteVideoXId");
                $this->procesos_lib->curlCorteVideoXId($video_id, $objvideotemp->id, $this->input->post('ini_corte'), $this->input->post('dur_corte'));
                Log::erroLog("admin despues curlCorteVideoXId");
                echo json_encode(array("value" => '0', 'video_id' => $objvideotemp->id));
                //echo json_encode(array($video_id, $objvideotemp->id, $this->input->post('ini_corte'), $this->input->post('dur_corte')));
            }
        }
    }
    
    public function getCanalIdByKey($key_canal)
    {
        $objCanal = $this->canales_m->get_by(array("key_canal" => $key_canal));
        
        return $objCanal->id;
    }
}