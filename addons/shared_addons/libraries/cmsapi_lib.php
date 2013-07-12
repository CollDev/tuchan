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
                $returnValue[$objTipo->id] = $objTipo->nombre;
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
    
    public function uploadVideo($post, $files)
    {
        $ruta_video = str_replace('cmsapi_lib.php', '', __FILE__).'../../../uploads/videos/' . $this->moveUploaded($files);
        $archivo_video = pathinfo($ruta_video);
        $ext = $archivo_video['extension'];
        $size_video = filesize($ruta_video);
        $arrayExt = explode("|", 'mp4|mpg|flv|avi|wmv');
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
                    
                    array('message' => $objBeanVideoSaved->id);
                } else {
                    array('message' => 'Error al subir archivo.');
                }
            } else {
                array('message' => 'Archivo supera el tamaño permitido de 2GB.');
            }
        } else {
            array('message' => 'Formato de archivo no permitido: mp4,mpg,flv,avi,wmv');
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
    
    public function search($search,$dateini,$datefin)
    {
        header("Content-Type: application/json; charset=utf-8");
        //echo shell_exec("curl " . "http://micanal.pe/sphinx/videos/1/" . $search);        
        echo $this->sphinx_m->busquedaVideos($search,$dateini,$datefin);        
    }
}