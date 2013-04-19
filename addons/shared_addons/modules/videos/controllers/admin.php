<?php

/**
 * @package Videos
 * @name Controller Admin
 * @author Johnny <jhuamani@idigital.pe>
 */
class Admin extends Admin_Controller {

    /**
     * 
     */
    public function __construct() {
        parent::__construct();

        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->model('canales/canales_m');
        $this->load->model('canales/portada_m');
        $this->load->model('canales/secciones_m');
        $this->load->model('canales/detalle_secciones_m');
        $this->load->model('canales/tipo_secciones_m');
        $this->load->model('categoria_m');
        $this->load->model('tipo_video_m');
        $this->load->model('tipo_imagen_m');
        $this->load->model('imagen_m');
        $this->load->model('tipo_maestro_m');
        $this->load->model('grupo_maestro_m');
        $this->load->model('grupo_detalle_m');
        $this->load->model('videos/videos_m');
        $this->load->model('tags_m');
        $this->load->model('video_tags_m');
        $this->load->model('grupo_maestro_tag_m');
        $this->lang->load('videos');
        $this->config->load('videos/uploads');
        $this->load->library('image_lib');
        $this->load->library('imagenes_lib');
    }

    public function index() {
        echo 'este es el index de videos';
    }

    public function renameVideo($objBeanVideo, $name_file) {
        $returnValue = true;
        $path_video_old = FCPATH . 'uploads/videos/' . $name_file;
        $ext = pathinfo($path_video_old, PATHINFO_EXTENSION);
        $path_video_new = FCPATH . 'uploads/videos/' . $objBeanVideo->id . '.' . $this->config->item('videos:extension'); // . $ext;
        rename($path_video_old, $path_video_new);
        return $returnValue;
    }

    public function _saveVideoMaestroDetalle($objBeanVideo, $post, $maestro_detalle_id = NULL) {
        $user_id = (int) $this->session->userdata('user_id');
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
            $objBeanMaestroDetalle->tipo_grupo_maestros_id = $this->config->item('videos:lista');
        } else {
            if ($post['coleccion'] > 0) {
                $objBeanMaestroDetalle->grupo_maestro_padre = $post['coleccion'];
                $objBeanMaestroDetalle->tipo_grupo_maestros_id = $this->config->item('videos:coleccion');
            } else {
                if ($post['programa'] > 0) {
                    $objBeanMaestroDetalle->grupo_maestro_padre = $post['programa'];
                    $objBeanMaestroDetalle->tipo_grupo_maestros_id = $this->config->item('videos:programa');
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

    public function _saveTagsTematicaPersonajes($objBeanVideo, $post) {

        $user_id = (int) $this->session->userdata('user_id');
        $arrayTagTematicas = explode(",", $post['tematicas']);
        $arraytagPersonajes = explode(",", $post['personajes']);
        if (count($arrayTagTematicas) > 0) {
            foreach ($arrayTagTematicas as $index => $tematica) {
                $tag_id = 0;
                if ($this->tags_m->existTag($tematica, $this->config->item('tag:tematicas'))) {
                    $tag_id = $this->tags_m->getIdTag($tematica, $this->config->item('tag:tematicas'));
                } else {
                    $objBeanTag = new stdClass();
                    $objBeanTag->id = NULL;
                    $objBeanTag->tipo_tags_id = $this->config->item('tag:tematicas');
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
            $this->_clearOldTags($objBeanVideo, $arrayTagTematicas, $this->config->item('tag:tematicas'));
        }

        //guardamos los tag de personajes
        if (count($arraytagPersonajes) > 0) {
            foreach ($arraytagPersonajes as $index => $personaje) {
                $tag_id = 0;
                if ($this->tags_m->existTag($personaje, $this->config->item('tag:personajes'))) {
                    $tag_id = $this->tags_m->getIdTag($personaje, $this->config->item('tag:personajes'));
                } else {
                    $objBeanTag = new stdClass();
                    $objBeanTag->id = NULL;
                    $objBeanTag->tipo_tags_id = $this->config->item('tag:personajes');
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
            $this->_clearOldTags($objBeanVideo, $arraytagPersonajes, $this->config->item('tag:personajes'));
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

    private function _getTagsByIdMaestro($maestro_id, $type_tag) {
        $returnValue = array();
        $arrayMaestroTags = $this->grupo_maestro_tag_m->get_many_by(array("grupo_maestros_id" => $maestro_id));
        if (count($arrayMaestroTags) > 0) {
            $arrayIdTags = array();
            foreach ($arrayMaestroTags as $index => $objTagVideo) {
                array_push($arrayIdTags, $objTagVideo->tags_id);
            }
            if (count($arrayIdTags) > 0) {
                $returnValue = $this->tags_m->getTagsByIdTagsByType($arrayIdTags, $type_tag);
            }
        }
        return $returnValue;
    }

    private function _limpiarAntiguosTag($objBeanMaestro, $arraytag, $type_tag) {
        if ($objBeanMaestro->id > 0 && count($arraytag) > 0) {
            $collectionTagsByVideo = $this->_getTagsByIdMaestro($objBeanMaestro->id, $type_tag);
            if (count($collectionTagsByVideo) > 0) {
                foreach ($collectionTagsByVideo as $index => $objTag) {
                    if (!in_array($objTag->nombre, $arraytag)) {
                        $this->grupo_maestro_tag_m->deleteRelationTagVideo($objBeanMaestro->id, $objTag->id);
                    }
                }
            }
        }
    }

    public function obtenerTagsMaestro($maestro_id, $tipo_tag) {
        $returnValue = '';
        $arrayVideoTags = $this->grupo_maestro_tag_m->get_many_by(array("grupo_maestros_id" => $maestro_id));
        if (count($arrayVideoTags) > 0) {
            $arrayIdTags = array();
            foreach ($arrayVideoTags as $index => $Tags) {
                array_push($arrayIdTags, $Tags->tags_id);
            }

            $arrayTagsString = $this->tags_m->getListTags($arrayIdTags, $tipo_tag);
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

    public function getParentTop($grupo_maestro_padre) {
        $objMaestro = $this->grupo_maestro_m->get($grupo_maestro_padre);
        if (count($objMaestro) > 0) {
            if ($objMaestro->tipo_grupo_maestro_id == $this->config->item('videos:programa')) {
                return $objMaestro;
            } else {
                $objMaestroDetalle = $this->grupo_detalle_m->get_by(array("grupo_maestro_id" => $objMaestro->id));
                return $this->getParentTop($objMaestroDetalle->grupo_maestro_padre);
            }
        } else {
            return NULL;
        }
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

    public function carga_unitaria($canal_id = 0, $video_id = 0) {
        //$this->vd($this->input->post());die();
        $error = false;
        $message = '';
        if ($this->input->post()) {
            umask(0);
            //asign temp name
            $idUniq = uniqid();
            $ext = end(explode('.', $_FILES['video']['name']));
            $arrayExt = explode("|", $this->config->item('videos:formatos'));
            if (in_array($ext, $arrayExt)) {
                if ($_FILES["video"]["size"] > 0 && $_FILES["video"]["size"] <= 2147483648) { //10485760=>10MB 2147483648=>2GB
                    $nameVideo = $idUniq . '.' . $ext;
                    move_uploaded_file($_FILES["video"]["tmp_name"], UPLOAD_VIDEOS . $nameVideo);
                    //validamos que el archivo exista en el servidor
                    $path_video = FCPATH . UPLOAD_VIDEOS . $nameVideo;
                    if (file_exists($path_video) && strlen(trim($nameVideo)) > 0) {//validamos que exista el archivo
                        $user_id = (int) $this->session->userdata('user_id');
                        $objBeanVideo = new stdClass();
                        $objBeanVideo->id = NULL;
                        $objBeanVideo->tipo_videos_id = $this->input->post('tipo');
                        $objBeanVideo->categorias_id = $this->input->post('categoria');
                        $objBeanVideo->usuarios_id = $user_id;
                        $objBeanVideo->canales_id = $this->input->post('canal_id');
                        //$objBeanVideo->fuente = $this->input->post('fuente');
                        //$objBeanVideo->nid ='';
                        $objBeanVideo->titulo = $this->input->post('titulo');
                        $objBeanVideo->alias = url_title(strtolower(convert_accented_characters($this->input->post('titulo'))));
                        $objBeanVideo->descripcion = $this->input->post('descripcion_updated');
                        $objBeanVideo->fragmento = $this->input->post('fragmento');
                        //$objBeanVideo->codigo ='';
                        //$objBeanVideo->reproducciones ='';
                        //$objBeanVideo->duracion ='';
                        $objBeanVideo->fecha_publicacion_inicio = date("H:i:s", strtotime($this->input->post('fec_pub_ini')));
                        $objBeanVideo->fecha_publicacion_fin = date("H:i:s", strtotime($this->input->post('fec_pub_fin')));
                        $objBeanVideo->fecha_transmision = date("Y-m-d H:i:s", strtotime($this->input->post('fec_trans')));
                        $objBeanVideo->horario_transmision_inicio = date("Y-m-d H:i:s", strtotime($this->input->post('hora_trans_ini')));
                        $objBeanVideo->horario_transmision_fin = date("Y-m-d H:i:s", strtotime($this->input->post('hora_trans_fin')));
                        $objBeanVideo->ubicacion = $this->input->post('ubicacion');
                        //$objBeanVideo->id_mongo ='';
                        $objBeanVideo->estado = $this->config->item('status:codificando');
                        $objBeanVideo->estado_liquid = $this->config->item('liquid:nuevo');
                        ;
                        $objBeanVideo->fecha_registro = date("Y-m-d H:i:s");
                        $objBeanVideo->usuario_registro = $user_id;
                        //$objBeanVideo->fecha_actualizacion ='';
                        //$objBeanVideo->usuario_actualizacion ='';
                        $objBeanVideo->estado_migracion = 0; //estado para mongoDB
                        //$objBeanVideo->fecha_migracion ='';
                        //$objBeanVideo->fecha_migracion_actualizacion ='';
                        $objBeanVideo->estado_migracion_sphinx_tit = 0; //
                        //$objBeanVideo->fecha_migracion_sphinx_tit ='';
                        //$objBeanVideo->fecha_migracion_actualizacion_sphinx_tit ='';
                        $objBeanVideo->estado_migracion_sphinx_des = 0;
                        $objBeanVideo->padre = 0;
                        //$objBeanVideo->fecha_migracion_sphinx_des ='';
                        //$objBeanVideo->fecha_migracion_actualizacion_sphinx_des ='';
                        $objBeanVideo = $this->videos_m->save_video($objBeanVideo);
                        //giardamos los tags de tematica y personajes
                        $this->_saveTagsTematicaPersonajes($objBeanVideo, $this->input->post());
                        //guardamos en la tabla grupo detalle
                        $this->_saveVideoMaestroDetalle($objBeanVideo, $this->input->post());
                        //cambiar nombre del video por el ID del registro del video 
                        $this->renameVideo($objBeanVideo, $nameVideo);

                        $this->load->helper('url');
                        redirect('/admin/canales/videos/' . $canal_id, 'refresh');
                    }
                } else {
                    $error = true;
                    $message = lang('videos:size_invalid');
                }
            } else {
                $error = true;
                $message = lang('videos:format_invalid');
            }
        }

        //creamos un objeto vacio que nos servira de recipiente
        $objBeanForm = new stdClass();
        if ($video_id > 0) {
            //agregar metodo para alimentar al objeto para la edicion
            $lista = 0;
            $coleccion = 0;
            $programa = 0;
            // verificamos el que el video tenga registros en la tabla detalles de maestro
            //obtenemos el objeto maestro para obtener el ID y tipo
            $objGrupoDetalle = $this->grupo_detalle_m->get_by(array("video_id" => $video_id));
            if (count($objGrupoDetalle) > 0) {
                $lista = $this->getIdMaestro($objGrupoDetalle->grupo_maestro_padre, $this->config->item('videos:lista'));
                $coleccion = $this->getIdMaestro($objGrupoDetalle->grupo_maestro_padre, $this->config->item('videos:coleccion'));
                $programa = $this->getIdMaestro($objGrupoDetalle->grupo_maestro_padre, $this->config->item('videos:programa'));
                //$objMaestro = $this->grupo_maestro_m->get($objGrupoDetalle->grupo_maestro_padre);
                //$this->vd($objMaestro);
                /* switch ($objMaestro->tipo_grupo_maestro_id) {
                  case $this->config->item('videos:lista'):
                  $lista = $objMaestro->id;
                  if ($lista > 0) {
                  $parentGrupoDetalle = $this->grupo_detalle_m->get_by(array("grupo_maestro_id" => $objMaestro->id));
                  $objParentMaestro = $this->grupo_maestro_m->get($parentGrupoDetalle->grupo_maestro_padre);
                  if ($objParentMaestro->tipo_grupo_maestro_id == $this->config->item('videos:coleccion')) {
                  $coleccion = $objParentMaestro->id;
                  }
                  }
                  //obtenemos el programa si existe
                  if ($coleccion > 0) {
                  $programaGrupoDetalle = $this->grupo_detalle_m->get_by(array("grupo_maestro_id" => $objParentMaestro->id));
                  $objProgramaMaestro = $this->grupo_maestro_m->get($programaGrupoDetalle->grupo_maestro_padre);
                  if ($objProgramaMaestro->tipo_grupo_maestro_id == $this->config->item('videos:programa')) {
                  $programa = $objProgramaMaestro->id;
                  }
                  }
                  break;
                  case $this->config->item('videos:coleccion'):
                  $coleccion = $objMaestro->id;
                  //obtenemos el programa si existe
                  if ($coleccion > 0) {
                  $programaGrupoDetalle = $this->grupo_detalle_m->get_by(array("grupo_maestro_id" => $objMaestro->id));
                  $objProgramaMaestro = $this->grupo_maestro_m->get($programaGrupoDetalle->grupo_maestro_padre);
                  if ($objProgramaMaestro->tipo_grupo_maestro_id == $this->config->item('videos:programa')) {
                  $programa = $objProgramaMaestro->id;
                  }
                  }
                  break;
                  case $this->config->item('videos:programa'):
                  $programa = $objMaestro->id;
                  break;
                  } */
            }

            $objVideo = $this->videos_m->get($video_id);
            $objBeanForm->video_id = $video_id;
            $objBeanForm->titulo = $objVideo->titulo;
            $objBeanForm->video = '';
            $objBeanForm->descripcion = $objVideo->descripcion;
            $objBeanForm->fragmento = $objVideo->fragmento;
            $objBeanForm->categoria = $objVideo->categorias_id;
            $objBeanForm->tematicas = $this->_getTag($video_id, $this->config->item('tag:tematicas'));
            $objBeanForm->personajes = $this->_getTag($video_id, $this->config->item('tag:personajes'));
            $objBeanForm->tipo = $objVideo->tipo_videos_id;
            $objBeanForm->programa = $programa;
            $objBeanForm->coleccion = $coleccion;
            $objBeanForm->lista = $lista;
            //$objBeanForm->fuente = $objVideo->fuente;
            $objBeanForm->fec_pub_ini = date("d-m-Y H:i:s", strtotime($objVideo->fecha_publicacion_inicio)); //$objVideo->fecha_publicacion_inicio;
            $objBeanForm->fec_pub_fin = date("d-m-Y H:i:s", strtotime($objVideo->fecha_publicacion_fin)); //$objVideo->fecha_publicacion_fin;
            $objBeanForm->fec_trans = date("d-m-Y", strtotime($objVideo->fecha_transmision)); //$objVideo->fecha_transmision;
            $objBeanForm->hora_trans_ini = $objVideo->horario_transmision_inicio;
            $objBeanForm->hora_trans_fin = $objVideo->horario_transmision_fin;
            $objBeanForm->ubicacion = $objVideo->ubicacion;
            $objBeanForm->canal_id = $canal_id;
            $objBeanForm->tipo_maestro = '';
            $objBeanForm->keywords = '';
            $objBeanForm->error = $error;
            $objBeanForm->message = $message;
            $objBeanForm->ruta = $objVideo->ruta; /* adicionado */
            $objBeanForm->tiene_imagen = $this->_tieneAvatar($video_id);
            if ($objBeanForm->tiene_imagen) {
                $objBeanForm->avatar = $this->_getListImagen($video_id);
            } else {
                $objBeanForm->avatar = array();
            }
        } else {
            if ($this->input->post()) {
                $objBeanForm->video_id = $video_id;
                $objBeanForm->titulo = $this->input->post('titulo');
                $objBeanForm->video = '';
                $objBeanForm->descripcion = $this->input->post('descripcion');
                $objBeanForm->fragmento = $this->input->post('fragmento');
                $objBeanForm->categoria = $this->input->post('categoria');
                $objBeanForm->tematicas = $this->input->post('tematicas');
                $objBeanForm->personajes = $this->input->post('personajes');
                $objBeanForm->tipo = $this->input->post('tipo');
                $objBeanForm->programa = $this->input->post('programa');
                $objBeanForm->coleccion = $this->input->post('coleccion');
                $objBeanForm->lista = $this->input->post('lista');
                //$objBeanForm->fuente = $this->input->post('fuente');
                $objBeanForm->fec_pub_ini = $this->input->post('fec_pub_ini');
                $objBeanForm->fec_pub_fin = $this->input->post('fec_pub_fin');
                $objBeanForm->fec_trans = $this->input->post('fec_trans');
                $objBeanForm->hora_trans_ini = $this->input->post('hora_trans_ini');
                $objBeanForm->hora_trans_fin = $this->input->post('hora_trans_fin');
                $objBeanForm->ubicacion = $this->input->post('ubicacion');
                $objBeanForm->canal_id = $canal_id;
                $objBeanForm->tipo_maestro = '';
                $objBeanForm->tiene_imagen = false;
                $objBeanForm->avatar = array();
                $objBeanForm->error = $error;
                $objBeanForm->message = $message;
                $objBeanForm->keywords = '';
            } else {
                $objBeanForm->video_id = $video_id;
                $objBeanForm->titulo = '';
                $objBeanForm->video = '';
                $objBeanForm->descripcion = '';
                $objBeanForm->fragmento = '';
                $objBeanForm->categoria = '0';
                $objBeanForm->tematicas = '';
                $objBeanForm->personajes = '';
                $objBeanForm->tipo = '1';
                $objBeanForm->programa = '0';
                $objBeanForm->coleccion = '0';
                $objBeanForm->lista = '0';
                //$objBeanForm->fuente = $canal_id;
                $objBeanForm->fec_pub_ini = '';
                $objBeanForm->fec_pub_fin = '';
                $objBeanForm->fec_trans = '';
                $objBeanForm->hora_trans_ini = '';
                $objBeanForm->hora_trans_fin = '';
                $objBeanForm->ubicacion = '';
                $objBeanForm->canal_id = $canal_id;
                $objBeanForm->tipo_maestro = '';
                $objBeanForm->tiene_imagen = false;
                $objBeanForm->avatar = array();
                $objBeanForm->error = $error;
                $objBeanForm->message = $message;
                $objBeanForm->keywords = '';
            }
        }

        //$this->config->load('videos/uploads');
        // Obtener nombre del canal según id
        $canal = $this->canales_m->get($canal_id);
        $arrayCategory = $this->categoria_m->getCategoryDropDown(array("categorias_id" => "0"), 'nombre');
        $arrayTipo = $this->tipo_video_m->getTipoDropDown(array(), 'nombre');
        //listamos las listas dependientes con datos filtrados
        if ($video_id > 0) {
            $arrayProgramme = $this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:programa'), 'canales_id' => $canal_id), 'nombre');
            if ($programa == 0 && $coleccion == 0 && $lista == 0) {
                $arrayColeccionVideo = $this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:coleccion'), 'canales_id' => $canal_id), 'nombre');
                $arrayList = $this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:lista'), 'canales_id' => $canal_id), 'nombre');
            } else {
                if ($programa > 0) {
                    $arrayColeccionVideo = $this->getChildMaestro($programa, true, $this->config->item('videos:coleccion'));
                    if (count($arrayColeccionVideo) > 0) {
                        if ($coleccion > 0) {
                            $arrayList = $this->getChildMaestro($coleccion, true, $this->config->item('videos:lista'));
                        } else {
                            //$arrayList = array(lang('videos:select_list'));
                            $arrayList = $this->getChildMaestro($programa, true, $this->config->item('videos:lista'));
                        }
                    } else {
                        $arrayColeccionVideo = array(lang('videos:select_list'));
                        $arrayList = $this->getChildMaestro($programa, true, $this->config->item('videos:lista'));
                    }
                } else {
                    $arrayColeccionVideo = $this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:coleccion'), 'canales_id' => $canal_id), 'nombre');
                    $arrayList = $this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:lista'), 'canales_id' => $canal_id), 'nombre');
                }
            }
        } else {
            if ($this->input->post()) {
                $programa = $this->input->post('programa');
                $coleccion = $this->input->post('coleccion');
                $lista = $this->input->post('lista');
                if ($programa == 0 && $coleccion == 0 && $lista == 0) {
                    $arrayProgramme = $this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:programa'), 'canales_id' => $canal_id), 'nombre');
                    $arrayColeccionVideo = $this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:coleccion'), 'canales_id' => $canal_id), 'nombre');
                    $arrayList = $this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:lista'), 'canales_id' => $canal_id), 'nombre');
                } else {
                    $arrayProgramme = $this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:programa'), 'canales_id' => $canal_id), 'nombre');
                    if ($programa > 0) {
                        $arrayColeccionVideo = $this->getChildMaestro($programa, true, $this->config->item('videos:coleccion'));
                        if ($coleccion > 0) {
                            $arrayList = $this->getChildMaestro($coleccion, true, $this->config->item('videos:lista'));
                        } else {
                            $arrayList = array(lang('videos:select_list'));
                        }
                    } else {
                        $arrayColeccionVideo = $this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:coleccion'), 'canales_id' => $canal_id), 'nombre');
                        $arrayList = $this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:lista'), 'canales_id' => $canal_id), 'nombre');
                    }
                }
            } else {
                $arrayProgramme = $this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:programa'), 'canales_id' => $canal_id), 'nombre');
                $arrayColeccionVideo = $this->_getListMasterChannel($this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:coleccion'), 'canales_id' => $canal_id), 'nombre'));
                $arrayList = $this->_getListMasterChannel($this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:lista'), 'canales_id' => $canal_id), 'nombre'));
            }
        }

        $arrayFuente = $this->canales_m->getCanalDropDown(array(), 'nombre');
        $this->template
                ->title($this->module_details['name'])
                ->append_js('AjaxUpload.2.0.min.js')
                //->set_partial('filters', 'admin/partials/filters')
                ->set('canal', $canal)
                ->set('categoria', $arrayCategory)
                ->set('tipo', $arrayTipo)
                ->set('programa', $arrayProgramme)
                ->set('coleccion', $arrayColeccionVideo)
                ->set('lista_rep', $arrayList)
                ->set('fuente', $arrayFuente)
                ->set('objBeanForm', $objBeanForm)
                ->append_metadata($this->load->view('fragments/wysiwyg', array(), TRUE))
                ->append_js('jquery/jquery.tagsinput.js')
                ->append_css('jquery/jquery.tagsinput.css')
                ->append_js('module::jquery.ddslick.min.js')
                //->append_js('cms/module::blog_form.js')
                ->set('carga_unitaria', 'carga_unitaria');

        $this->input->is_ajax_request() ?
                        $this->template->build('admin/tables/posts') : $this->template->build('admin/carga_unitaria');
    }

    public function corte_video($canal_id = 0, $video_id = 0) {
        //$this->vd($this->input->post());die();
        $error = false;
        $message = '';
        if ($this->input->post()) {
            umask(0);
            //asign temp name
            $idUniq = uniqid();
            $ext = end(explode('.', $_FILES['video']['name']));
            $arrayExt = explode("|", $this->config->item('videos:formatos'));
            if (in_array($ext, $arrayExt)) {
                if ($_FILES["video"]["size"] > 0 && $_FILES["video"]["size"] <= 2147483648) { //10485760=>10MB 2147483648=>2GB
                    $nameVideo = $idUniq . '.' . $ext;
                    move_uploaded_file($_FILES["video"]["tmp_name"], UPLOAD_VIDEOS . $nameVideo);
                    //validamos que el archivo exista en el servidor
                    $path_video = FCPATH . UPLOAD_VIDEOS . $nameVideo;
                    if (file_exists($path_video) && strlen(trim($nameVideo)) > 0) {//validamos que exista el archivo
                        $user_id = (int) $this->session->userdata('user_id');
                        $objBeanVideo = new stdClass();
                        $objBeanVideo->id = NULL;
                        $objBeanVideo->tipo_videos_id = $this->input->post('tipo');
                        $objBeanVideo->categorias_id = $this->input->post('categoria');
                        $objBeanVideo->usuarios_id = $user_id;
                        $objBeanVideo->canales_id = $this->input->post('canal_id');
                        //$objBeanVideo->fuente = $this->input->post('fuente');
                        //$objBeanVideo->nid ='';
                        $objBeanVideo->titulo = $this->input->post('titulo');
                        $objBeanVideo->alias = url_title(strtolower(convert_accented_characters($this->input->post('titulo'))));
                        $objBeanVideo->descripcion = $this->input->post('descripcion_updated');
                        $objBeanVideo->fragmento = $this->input->post('fragmento');
                        //$objBeanVideo->codigo ='';
                        //$objBeanVideo->reproducciones ='';
                        //$objBeanVideo->duracion ='';
                        $objBeanVideo->fecha_publicacion_inicio = date("H:i:s", strtotime($this->input->post('fec_pub_ini')));
                        $objBeanVideo->fecha_publicacion_fin = date("H:i:s", strtotime($this->input->post('fec_pub_fin')));
                        $objBeanVideo->fecha_transmision = date("Y-m-d H:i:s", strtotime($this->input->post('fec_trans')));
                        $objBeanVideo->horario_transmision_inicio = date("Y-m-d H:i:s", strtotime($this->input->post('hora_trans_ini')));
                        $objBeanVideo->horario_transmision_fin = date("Y-m-d H:i:s", strtotime($this->input->post('hora_trans_fin')));
                        $objBeanVideo->ubicacion = $this->input->post('ubicacion');
                        //$objBeanVideo->id_mongo ='';
                        $objBeanVideo->estado = $this->config->item('status:codificando');
                        $objBeanVideo->estado_liquid = $this->config->item('liquid:nuevo');
                        ;
                        $objBeanVideo->fecha_registro = date("Y-m-d H:i:s");
                        $objBeanVideo->usuario_registro = $user_id;
                        //$objBeanVideo->fecha_actualizacion ='';
                        //$objBeanVideo->usuario_actualizacion ='';
                        $objBeanVideo->estado_migracion = 0; //estado para mongoDB
                        //$objBeanVideo->fecha_migracion ='';
                        //$objBeanVideo->fecha_migracion_actualizacion ='';
                        $objBeanVideo->estado_migracion_sphinx_tit = 0; //
                        //$objBeanVideo->fecha_migracion_sphinx_tit ='';
                        //$objBeanVideo->fecha_migracion_actualizacion_sphinx_tit ='';
                        $objBeanVideo->estado_migracion_sphinx_des = 0;
                        $objBeanVideo->padre = 0;
                        //$objBeanVideo->fecha_migracion_sphinx_des ='';
                        //$objBeanVideo->fecha_migracion_actualizacion_sphinx_des ='';
                        $objBeanVideo = $this->videos_m->save_video($objBeanVideo);
                        //giardamos los tags de tematica y personajes
                        $this->_saveTagsTematicaPersonajes($objBeanVideo, $this->input->post());
                        //guardamos en la tabla grupo detalle
                        $this->_saveVideoMaestroDetalle($objBeanVideo, $this->input->post());
                        //cambiar nombre del video por el ID del registro del video 
                        $this->renameVideo($objBeanVideo, $nameVideo);

                        $this->load->helper('url');
                        redirect('/admin/canales/videos/' . $canal_id, 'refresh');
                    }
                } else {
                    $error = true;
                    $message = lang('videos:size_invalid');
                }
            } else {
                $error = true;
                $message = lang('videos:format_invalid');
            }
        }

        //creamos un objeto vacio que nos servira de recipiente
        $objBeanForm = new stdClass();
        if ($video_id > 0) {
            //agregar metodo para alimentar al objeto para la edicion
            $lista = 0;
            $coleccion = 0;
            $programa = 0;
            // verificamos el que el video tenga registros en la tabla detalles de maestro
            //obtenemos el objeto maestro para obtener el ID y tipo
            $objGrupoDetalle = $this->grupo_detalle_m->get_by(array("video_id" => $video_id));
            if (count($objGrupoDetalle) > 0) {
                $lista = $this->getIdMaestro($objGrupoDetalle->grupo_maestro_padre, $this->config->item('videos:lista'));
                $coleccion = $this->getIdMaestro($objGrupoDetalle->grupo_maestro_padre, $this->config->item('videos:coleccion'));
                $programa = $this->getIdMaestro($objGrupoDetalle->grupo_maestro_padre, $this->config->item('videos:programa'));
                //$objMaestro = $this->grupo_maestro_m->get($objGrupoDetalle->grupo_maestro_padre);
                //$this->vd($objMaestro);
                /* switch ($objMaestro->tipo_grupo_maestro_id) {
                  case $this->config->item('videos:lista'):
                  $lista = $objMaestro->id;
                  if ($lista > 0) {
                  $parentGrupoDetalle = $this->grupo_detalle_m->get_by(array("grupo_maestro_id" => $objMaestro->id));
                  $objParentMaestro = $this->grupo_maestro_m->get($parentGrupoDetalle->grupo_maestro_padre);
                  if ($objParentMaestro->tipo_grupo_maestro_id == $this->config->item('videos:coleccion')) {
                  $coleccion = $objParentMaestro->id;
                  }
                  }
                  //obtenemos el programa si existe
                  if ($coleccion > 0) {
                  $programaGrupoDetalle = $this->grupo_detalle_m->get_by(array("grupo_maestro_id" => $objParentMaestro->id));
                  $objProgramaMaestro = $this->grupo_maestro_m->get($programaGrupoDetalle->grupo_maestro_padre);
                  if ($objProgramaMaestro->tipo_grupo_maestro_id == $this->config->item('videos:programa')) {
                  $programa = $objProgramaMaestro->id;
                  }
                  }
                  break;
                  case $this->config->item('videos:coleccion'):
                  $coleccion = $objMaestro->id;
                  //obtenemos el programa si existe
                  if ($coleccion > 0) {
                  $programaGrupoDetalle = $this->grupo_detalle_m->get_by(array("grupo_maestro_id" => $objMaestro->id));
                  $objProgramaMaestro = $this->grupo_maestro_m->get($programaGrupoDetalle->grupo_maestro_padre);
                  if ($objProgramaMaestro->tipo_grupo_maestro_id == $this->config->item('videos:programa')) {
                  $programa = $objProgramaMaestro->id;
                  }
                  }
                  break;
                  case $this->config->item('videos:programa'):
                  $programa = $objMaestro->id;
                  break;
                  } */
            }

            $objVideo = $this->videos_m->get($video_id);
            $objBeanForm->video_id = $video_id;
            $objBeanForm->titulo = $objVideo->titulo;
            $objBeanForm->video = '';
            $objBeanForm->descripcion = $objVideo->descripcion;
            $objBeanForm->fragmento = $objVideo->fragmento;
            $objBeanForm->categoria = $objVideo->categorias_id;
            $objBeanForm->tematicas = $this->_getTag($video_id, $this->config->item('tag:tematicas'));
            $objBeanForm->personajes = $this->_getTag($video_id, $this->config->item('tag:personajes'));
            $objBeanForm->tipo = $objVideo->tipo_videos_id;
            $objBeanForm->programa = $programa;
            $objBeanForm->coleccion = $coleccion;
            $objBeanForm->lista = $lista;
            //$objBeanForm->fuente = $objVideo->fuente;
            $objBeanForm->fec_pub_ini = date("d-m-Y H:i:s", strtotime($objVideo->fecha_publicacion_inicio)); //$objVideo->fecha_publicacion_inicio;
            $objBeanForm->fec_pub_fin = date("d-m-Y H:i:s", strtotime($objVideo->fecha_publicacion_fin)); //$objVideo->fecha_publicacion_fin;
            $objBeanForm->fec_trans = date("d-m-Y", strtotime($objVideo->fecha_transmision)); //$objVideo->fecha_transmision;
            $objBeanForm->hora_trans_ini = $objVideo->horario_transmision_inicio;
            $objBeanForm->hora_trans_fin = $objVideo->horario_transmision_fin;
            $objBeanForm->ubicacion = $objVideo->ubicacion;
            $objBeanForm->canal_id = $canal_id;
            $objBeanForm->tipo_maestro = '';
            $objBeanForm->keywords = '';
            $objBeanForm->error = $error;
            $objBeanForm->message = $message;
            $objBeanForm->ruta = $objVideo->ruta; /* adicionado */
            $objBeanForm->tiene_imagen = $this->_tieneAvatar($video_id);
            if ($objBeanForm->tiene_imagen) {
                $objBeanForm->avatar = $this->_getListImagen($video_id);
            } else {
                $objBeanForm->avatar = array();
            }
        } else {
            if ($this->input->post()) {
                $objBeanForm->video_id = $video_id;
                $objBeanForm->titulo = $this->input->post('titulo');
                $objBeanForm->video = '';
                $objBeanForm->descripcion = $this->input->post('descripcion');
                $objBeanForm->fragmento = $this->input->post('fragmento');
                $objBeanForm->categoria = $this->input->post('categoria');
                $objBeanForm->tematicas = $this->input->post('tematicas');
                $objBeanForm->personajes = $this->input->post('personajes');
                $objBeanForm->tipo = $this->input->post('tipo');
                $objBeanForm->programa = $this->input->post('programa');
                $objBeanForm->coleccion = $this->input->post('coleccion');
                $objBeanForm->lista = $this->input->post('lista');
                //$objBeanForm->fuente = $this->input->post('fuente');
                $objBeanForm->fec_pub_ini = $this->input->post('fec_pub_ini');
                $objBeanForm->fec_pub_fin = $this->input->post('fec_pub_fin');
                $objBeanForm->fec_trans = $this->input->post('fec_trans');
                $objBeanForm->hora_trans_ini = $this->input->post('hora_trans_ini');
                $objBeanForm->hora_trans_fin = $this->input->post('hora_trans_fin');
                $objBeanForm->ubicacion = $this->input->post('ubicacion');
                $objBeanForm->canal_id = $canal_id;
                $objBeanForm->tipo_maestro = '';
                $objBeanForm->tiene_imagen = false;
                $objBeanForm->avatar = array();
                $objBeanForm->error = $error;
                $objBeanForm->message = $message;
                $objBeanForm->keywords = '';
            } else {
                $objBeanForm->video_id = $video_id;
                $objBeanForm->titulo = '';
                $objBeanForm->video = '';
                $objBeanForm->descripcion = '';
                $objBeanForm->fragmento = '';
                $objBeanForm->categoria = '0';
                $objBeanForm->tematicas = '';
                $objBeanForm->personajes = '';
                $objBeanForm->tipo = '1';
                $objBeanForm->programa = '0';
                $objBeanForm->coleccion = '0';
                $objBeanForm->lista = '0';
                //$objBeanForm->fuente = $canal_id;
                $objBeanForm->fec_pub_ini = '';
                $objBeanForm->fec_pub_fin = '';
                $objBeanForm->fec_trans = '';
                $objBeanForm->hora_trans_ini = '';
                $objBeanForm->hora_trans_fin = '';
                $objBeanForm->ubicacion = '';
                $objBeanForm->canal_id = $canal_id;
                $objBeanForm->tipo_maestro = '';
                $objBeanForm->tiene_imagen = false;
                $objBeanForm->avatar = array();
                $objBeanForm->error = $error;
                $objBeanForm->message = $message;
                $objBeanForm->keywords = '';
            }
        }

        //$this->config->load('videos/uploads');
        // Obtener nombre del canal según id
        $canal = $this->canales_m->get($canal_id);
        $arrayCategory = $this->categoria_m->getCategoryDropDown(array("categorias_id" => "0"), 'nombre');
        $arrayTipo = $this->tipo_video_m->getTipoDropDown(array(), 'nombre');

        //$arrayTipoMaestro = $this->tipo_maestro_m->getTipoDropDown(array(), 'nombre');
        //listamos las listas dependientes con datos filtrados
        if ($video_id > 0) {
            //$arrayProgramme = $this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:programa'), 'canales_id' => $canal_id), 'nombre');
            //$arrayColeccionVideo = $this->getChild($programa, $coleccion, $lista, $this->config->item('videos:coleccion'), true);
            //$arrayList = $this->getChild($programa, $coleccion, $lista, $this->config->item('videos:lista'),true);
            $arrayProgramme = $this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:programa'), 'canales_id' => $canal_id), 'nombre');
            if ($programa == 0 && $coleccion == 0 && $lista == 0) {
                $arrayColeccionVideo = $this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:coleccion'), 'canales_id' => $canal_id), 'nombre');
                $arrayList = $this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:lista'), 'canales_id' => $canal_id), 'nombre');
            } else {
                if ($programa > 0) {
                    $arrayColeccionVideo = $this->getChildMaestro($programa, true, $this->config->item('videos:coleccion'));
                    if (count($arrayColeccionVideo) > 0) {
                        if ($coleccion > 0) {
                            $arrayList = $this->getChildMaestro($coleccion, true, $this->config->item('videos:lista'));
                        } else {
                            //$arrayList = array(lang('videos:select_list'));
                            $arrayList = $this->getChildMaestro($programa, true, $this->config->item('videos:lista'));
                        }
                    } else {
                        $arrayColeccionVideo = array(lang('videos:select_list'));
                        $arrayList = $this->getChildMaestro($programa, true, $this->config->item('videos:lista'));
                    }
                } else {
                    $arrayColeccionVideo = $this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:coleccion'), 'canales_id' => $canal_id), 'nombre');
                    $arrayList = $this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:lista'), 'canales_id' => $canal_id), 'nombre');
                }
            }
        } else {
            if ($this->input->post()) {
                $programa = $this->input->post('programa');
                $coleccion = $this->input->post('coleccion');
                $lista = $this->input->post('lista');
                if ($programa == 0 && $coleccion == 0 && $lista == 0) {
                    $arrayProgramme = $this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:programa'), 'canales_id' => $canal_id), 'nombre');
                    $arrayColeccionVideo = $this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:coleccion'), 'canales_id' => $canal_id), 'nombre');
                    $arrayList = $this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:lista'), 'canales_id' => $canal_id), 'nombre');
                } else {
                    $arrayProgramme = $this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:programa'), 'canales_id' => $canal_id), 'nombre');
                    if ($programa > 0) {
                        $arrayColeccionVideo = $this->getChildMaestro($programa, true, $this->config->item('videos:coleccion'));
                        if ($coleccion > 0) {
                            $arrayList = $this->getChildMaestro($coleccion, true, $this->config->item('videos:lista'));
                        } else {
                            $arrayList = array(lang('videos:select_list'));
                        }
                    } else {
                        $arrayColeccionVideo = $this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:coleccion'), 'canales_id' => $canal_id), 'nombre');
                        $arrayList = $this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:lista'), 'canales_id' => $canal_id), 'nombre');
                    }
                }
            } else {
                $arrayProgramme = $this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:programa'), 'canales_id' => $canal_id), 'nombre');
                $arrayColeccionVideo = $this->_getListMasterChannel($this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:coleccion'), 'canales_id' => $canal_id), 'nombre'));
                $arrayList = $this->_getListMasterChannel($this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:lista'), 'canales_id' => $canal_id), 'nombre'));
            }
        }

        $arrayFuente = $this->canales_m->getCanalDropDown(array(), 'nombre');
        $this->template
                ->title($this->module_details['name'])
                ->append_js('AjaxUpload.2.0.min.js')
                //->set_partial('filters', 'admin/partials/filters')
                ->set('canal', $canal)
                ->set('categoria', $arrayCategory)
                ->set('tipo', $arrayTipo)
                ->set('programa', $arrayProgramme)
                ->set('coleccion', $arrayColeccionVideo)
                ->set('lista_rep', $arrayList)
                ->set('fuente', $arrayFuente)
                ->set('objBeanForm', $objBeanForm)
                ->append_metadata($this->load->view('fragments/wysiwyg', array(), TRUE))
                ->append_js('jquery/jquery.tagsinput.js')
                ->append_css('jquery/jquery.tagsinput.css')
                ->append_js('module::jquery.ddslick.min.js')
                //->append_js('cms/module::blog_form.js')
                ->set('carga_unitaria', 'carga_unitaria');

        $this->input->is_ajax_request() ?
                        $this->template->build('admin/tables/posts') : $this->template->build('admin/corte_video');
    }

    public function _getListMasterChannel(&$arrayMaestro) {
        //$returnValue[0] = lang('videos:select_list');
        if (count($arrayMaestro) > 0) {
            foreach ($arrayMaestro as $master_id => $name_master) {
                if ($master_id > 0) {
                    if ($this->_isParentOrChild($master_id)) {
                        unset($arrayMaestro[$master_id]);
                    }
                }
            }
        }
        return $arrayMaestro;
    }

    public function _isParentOrChild($master_id) {
        $returnValue = false;
        $objCollectionMaster = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $master_id, "video_id" => "NULL"));

        if (count($objCollectionMaster) > 0) {
            $returnValue = true;
        } else {
            $objCollectionChild = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_id" => $master_id));
            if (count($objCollectionChild) > 0) {
                $returnValue = true;
            }
        }
        return $returnValue;
    }

    public function getChild($programa, $coleccion, $lista, $type, $dropdown = false) {
        
    }

    public function getChildMaestro($parent_maestro, $dropdown = false, $type) {
        $returnValue = array();
        $arrayCollection = $this->grupo_detalle_m->getGrupoDetalle(array("grupo_maestro_padre" => $parent_maestro));
        if (count($arrayCollection) > 0) {
            $array_id_maestro = array();
            foreach ($arrayCollection as $index => $objCollection) {
                if ($objCollection->grupo_maestro_id != NULL) {
                    if ($this->isType($objCollection->grupo_maestro_id, $type)) {
                        array_push($array_id_maestro, $objCollection->grupo_maestro_id);
                    }
                }
            }
            if (count($array_id_maestro) > 0) {
                $arrayCollectionMaestro = $this->grupo_maestro_m->getListCollection($array_id_maestro);
            } else {
                $arrayCollectionMaestro = array();
            }

            if ($dropdown) {
                $returnValue[0] = lang('videos:select_list');
                //$returnValue['error'] = 0;                
                if (count($arrayCollectionMaestro) > 0) {
                    foreach ($arrayCollectionMaestro as $indice => $objMaestro) {
                        $returnValue[$objMaestro->id] = $objMaestro->nombre;
                    }
                }
            } else {
                $returnValue = $arrayCollectionMaestro;
            }
        }
        return $returnValue;
    }

    public function isType($grupo_maestro_id, $type) {
        $returnValue = false;
        $objMaestro = $this->grupo_maestro_m->get($grupo_maestro_id);
        if ($objMaestro->tipo_grupo_maestro_id == $type) {
            $returnValue = true;
        }
        return $returnValue;
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

    public function tematicas() {
        $objCollectionTag = $this->tags_m->getTagsByType($this->input->get('term'), $this->config->item('tag:tematicas'));
        echo json_encode($objCollectionTag);
        /* echo json_encode(
          $this->tags_m->select('nombre value')
          ->like('nombre', $this->input->get('term'))
          ->get_all(array("tipo_tags_id" => $this->config->item('tag:tematicas')))
          ); */
    }

    public function personajes() {
        $objCollectionTag = $this->tags_m->getTagsByType($this->input->get('term'), $this->config->item('tag:personajes'));
        /* echo json_encode(
          $this->tags_m->select('nombre value')
          ->like('nombre', $this->input->get('term'))
          ->get_by(array("tipo_tags_id" => $this->config->item('tag:personajes')))
          ); */
        echo json_encode($objCollectionTag);
    }

    public function _getListImagen($video_id, $json = true) {
        //$this->config->load('videos/uploads');
        $arrayImagenBorrador = $this->imagen_m->getImagen(array("tipo_imagen_id" => $this->config->item('imagen:small'), "videos_id" => $video_id, "estado" => $this->config->item('imagen:borrador')), NULL);
        $arrayImagenPublicado = $this->imagen_m->getImagen(array("tipo_imagen_id" => $this->config->item('imagen:small'), "videos_id" => $video_id, "estado" => $this->config->item('imagen:publicado')), NULL);
        $returnArray = array();
        if (count($arrayImagenBorrador) > 0) {
            foreach ($arrayImagenBorrador as $index => $objImagenBorrador) {
                $objImagenBorrador->path = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagenBorrador->imagen; //.$objImagenBorrador->imagen;
                array_push($returnArray, $objImagenBorrador);
            }
        }
        if (count($arrayImagenPublicado) > 0) {
            foreach ($arrayImagenPublicado as $indice => $objImagenPublicado) {
                $objImagenPublicado->path = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagenPublicado->imagen; //.$objImagenPublicado->imagen;
                array_push($returnArray, $objImagenPublicado);
            }
        }

        if ($json) {
            //formato para json
            if (count($returnArray) > 0) {
                $arreglo = array();
                foreach ($returnArray as $in => $objImg) {
                    $arrayImg['text'] = '';
                    $arrayImg['value'] = $objImg->id;
                    if ($objImg->estado == "0") {
                        $arrayImg['selected'] = false;
                    } else {
                        if ($objImg->estado == "1") {
                            $arrayImg['selected'] = true;
                        }
                    }
                    $arrayImg['description'] = '';
                    $arrayImg['imageSrc'] = $objImg->path;

                    array_push($arreglo, $arrayImg);
                    unset($arrayImg);
                }
                $returnArray = $arreglo;
            }
        }

        return $returnArray;
    }

    public function listarImagenesMaestro($maestro_id, $json = true) {
        $arrayImagenBorrador = $this->imagen_m->get_many_by(array("tipo_imagen_id" => $this->config->item('imagen:small'), "grupo_maestros_id" => $maestro_id, "estado" => $this->config->item('imagen:borrador')));
        $arrayImagenPublicado = $this->imagen_m->get_many_by(array("tipo_imagen_id" => $this->config->item('imagen:small'), "grupo_maestros_id" => $maestro_id, "estado" => $this->config->item('imagen:publicado')));
        $returnArray = array();
        if (count($arrayImagenBorrador) > 0) {
            foreach ($arrayImagenBorrador as $index => $objImagenBorrador) {
                if ($objImagenBorrador->procedencia == $this->config->item('procedencia:liquid')) {
                    $objImagenBorrador->path = $objImagenBorrador->imagen;
                } else {
                    $objImagenBorrador->path = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagenBorrador->imagen; //.$objImagenBorrador->imagen;
                }
                array_push($returnArray, $objImagenBorrador);
            }
        }
        if (count($arrayImagenPublicado) > 0) {
            foreach ($arrayImagenPublicado as $indice => $objImagenPublicado) {
                if ($objImagenPublicado->procedencia == $this->config->item('procedencia:liquid')) {
                    $objImagenPublicado->path = $objImagenPublicado->imagen;
                } else {
                    $objImagenPublicado->path = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagenPublicado->imagen; //.$objImagenPublicado->imagen;
                }
                array_push($returnArray, $objImagenPublicado);
            }
        }

        if ($json) {
            //formato para json
            if (count($returnArray) > 0) {
                $arreglo = array();
                foreach ($returnArray as $in => $objImg) {
                    $arrayImg['text'] = '';
                    $arrayImg['value'] = $objImg->id;
                    if ($objImg->estado == "0") {
                        $arrayImg['selected'] = false;
                    } else {

                        if ($objImg->estado == "1") {
                            $arrayImg['selected'] = true;
                        }
                    }
                    $arrayImg['description'] = '';
                    $arrayImg['imageSrc'] = $objImg->path;

                    array_push($arreglo, $arrayImg);
                    unset($arrayImg);
                }
                $returnArray = $arreglo;
            }
        }

        return $returnArray;
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

    public function _tieneAvatarMaestro($maestro_id) {
        $returnValue = false;
        if ($maestro_id > 0) {
            $arrayReturn = $this->imagen_m->get_many_by(array("grupo_maestros_id" => $maestro_id, "tipo_imagen_id" => $this->config->item('imagen:small')));
            if (count($arrayReturn) > 0) {
                $returnValue = true;
            }
        }
        return $returnValue;
    }

    /**
     * Upload de video al servidor local
     * @param int $video_id
     */
    public function subir_archivo($video_id) {
        //$this->config->load('videos/uploads');

        $config['video_post_max'] = $this->config->item('videos:post_max_size');
        $config['video_tamaño_max'] = $this->config->item('videos:upload_max_filesize');
        $config['video_tiempo_ejecucion_max'] = $this->config->item('videos:max_execution_time');
        $config['video_ruta'] = $this->config->item('videos:videos');
        $config['video_formatos'] = $this->config->item('videos:formatos');

        $this->load->library('upload', $config);

        // Verifica si el archivo fue seleccionado
        if (isset($_FILES['video']['tmp_name'])) {

            // Si el video se ha subido con éxito
            if (is_uploaded_file($_FILES['video']['tmp_name'])) {

                $ext = end(explode('.', $_FILES['video']['name']));

                move_uploaded_file($_FILES["video"]["tmp_name"], $config['video_ruta'] . $video_id . '.' . $ext);
            } else {
                echo 'Error en carga de archivo';
            }
        } else {
            echo 'Error en carga de archivo';
        }
    }

    /**
     *  Formulario para la subida de un grupo de videos
     *  @param int $canal_id Identificador unico de un canal
     */
    function carga_masiva($canal_id) {
        if ($this->input->is_ajax_request()) {
            
        } else {
            $this->template
                    ->title($this->module_details['name'])
                    //->append_js('admin/filter.js')
                    //->set_partial('filters', 'admin/partials/filters')
                    //->set('pagination', $pagination)
                    ->set('canal_id', $canal_id)
                    ->set('carga_masiva', 'carga_masiva');
            $this->template->build('admin/carga_masiva');
        }
    }

    public function vd($var) {
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }

    public function upload() {
        
    }

    public function save_maestro() {
        if ($this->input->is_ajax_request()) {
            //$this->vd($this->input->post());die();
            header('Content-Type: application/x-json; charset=utf-8');
            //$this->config->load('videos/uploads');
            switch ($this->input->post('tipo_maestro')) {
                case 'programa': $tipo_grupo_maestro_id = $this->config->item('videos:programa');
                    $nombre_maestro = $this->input->post('txt_programa');
                    break;
                case 'coleccion': $tipo_grupo_maestro_id = $this->config->item('videos:coleccion');
                    $nombre_maestro = $this->input->post('txt_coleccion');
                    break;
                case 'lista': $tipo_grupo_maestro_id = $this->config->item('videos:lista');
                    $nombre_maestro = $this->input->post('txt_lista');
                    break;
                default:$tipo_grupo_maestro_id = 4;
                    break;
            }
            if ($this->existNameMaestro($nombre_maestro, $tipo_grupo_maestro_id, $this->input->post())) {
                $returnValue = array();
                $returnValue['error'] = 1; // when exists name for master group
            } else {
                $user_id = (int) $this->session->userdata('user_id');
                $objBeanMaestro = new stdClass();
                $objBeanMaestro->id = NULL;
                $objBeanMaestro->nombre = $nombre_maestro;
                $objBeanMaestro->descripcion = $nombre_maestro;
                $objBeanMaestro->alias = $nombre_maestro;
                $objBeanMaestro->tipo_grupo_maestro_id = $tipo_grupo_maestro_id;
                $objBeanMaestro->canales_id = $this->input->post('canal_id');
                $objBeanMaestro->categorias_id = $this->input->post('categoria');
                $objBeanMaestro->cantidad_suscriptores = 0;
                $objBeanMaestro->peso = 1;
                $objBeanMaestro->id_mongo = NULL;
                $objBeanMaestro->estado = 1;
                $objBeanMaestro->fecha_registro = date("Y-m-d H:i:s");
                $objBeanMaestro->usuario_registro = $user_id;
                $objBeanMaestro->fecha_actualizacion = date("Y-m-d H:i:s");
                $objBeanMaestro->usuario_actualizacion = $user_id;
                $objBeanMaestro->estado_migracion = NULL;
                $objBeanMaestro->fecha_migracion = '0000-00-00 00:00:00';
                $objBeanMaestro->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                $objBeanMaestro->comentarios = 0;
                $objBeanMaestro->fecha_transmision_inicio = date("Y-m-d H:i:s");
                $objBeanMaestro->fecha_transmision_fin = date("Y-m-d H:i:s");
                $objBeanMaestroSaved = $this->grupo_maestro_m->save_maestro($objBeanMaestro);
                //guardar en el detalle de maestros en caso de guardarse como hijo
                $this->_saveMaestroDetalle($this->input->post(), $objBeanMaestroSaved);
                //generar su portada si el maestro es de tipo programa
                if ($objBeanMaestroSaved->tipo_grupo_maestro_id == $this->config->item('videos:programa')) {
                    $objCanal = $this->canales_m->get($this->input->post('canal_id'));
                    $this->generarNuevaPortada($objCanal, $objBeanMaestroSaved, $this->config->item('portada:programa'));
                } else {
                    if ($objBeanMaestroSaved->tipo_grupo_maestro_id == $this->config->item('videos:coleccion')) {
                        //generamos la seccion para la coleccion
                        if ($this->input->post('programa') > 0) {
                            //generamos una seccion coleccion para el programa
                            $this->generarSeccionColeccion($this->input->post('programa'), $objBeanMaestroSaved);
                        } else {
                            //generamos  una seccion coleccion para el canal
                            $this->generarSeccionColeccionCanal($this->input->post('canal_id'), $objBeanMaestroSaved);
                        }
                    }
                }
                $returnValue = array();
                $returnValue[$objBeanMaestroSaved->id] = $objBeanMaestroSaved->nombre;
                $returnValue['error'] = 0;
            }
            echo(json_encode($returnValue));
        }
    }

    private function generarSeccionColeccionCanal($canal_id, $objMaestro) {
        $objPortada = $this->portada_m->get_by(array("tipo_portadas_id" => $this->config->item('portada:canal'), "origen_id" => $canal_id));
        if (count($objPortada) > 0) {
            $user_id = (int) $this->session->userdata('user_id');
            $objBeanSeccion = new stdClass();
            $objBeanSeccion->id = NULL;
            $objBeanSeccion->nombre = $objMaestro->nombre;
            $objBeanSeccion->descripcion = $objMaestro->descripcion;
            $objBeanSeccion->tipo = 0;
            $objBeanSeccion->portadas_id = $objPortada->id;
            $objBeanSeccion->tipo_secciones_id = $this->config->item('seccion:coleccion');
            $objBeanSeccion->reglas_id = NULL;
            $objBeanSeccion->categorias_id = NULL;
            $objBeanSeccion->tags_id = NULL;
            $objBeanSeccion->peso = $this->obtenerPesoSeccionPortada($objPortada->id);
            $objBeanSeccion->id_mongo = NULL;
            $objBeanSeccion->estado = $this->config->item('estado:borrador');
            $objBeanSeccion->templates_id = $this->config->item('template:8items');
            $objBeanSeccion->fecha_registro = date("Y-m-d H:i:s");
            $objBeanSeccion->usuario_registro = $user_id;
            $objBeanSeccion->fecha_actualizacion = date("Y-m-d H:i:s");
            $objBeanSeccion->usuario_actualizacion = $user_id;
            $objBeanSeccion->estado_migracion = $this->config->item('migracion:nuevo');
            $objBeanSeccion->fecha_migracion = '0000-00-00 00:00:00';
            $objBeanSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
            $objBeanSeccion->grupo_maestros_id = $objMaestro->id;
            $objBeanSeccionSaved = $this->secciones_m->save($objBeanSeccion);
        }
    }

    public function generarSeccionColeccion($programa_id, $objMaestro) {
        $objPortada = $this->portada_m->get_by(array("tipo_portadas_id" => $this->config->item('portada:programa'), "origen_id" => $programa_id));
        if (count($objPortada) > 0) {
            $user_id = (int) $this->session->userdata('user_id');
            $objBeanSeccion = new stdClass();
            $objBeanSeccion->id = NULL;
            $objBeanSeccion->nombre = $objMaestro->nombre;
            $objBeanSeccion->descripcion = $objMaestro->descripcion;
            $objBeanSeccion->tipo = 0;
            $objBeanSeccion->portadas_id = $objPortada->id;
            $objBeanSeccion->tipo_secciones_id = $this->config->item('seccion:coleccion');
            $objBeanSeccion->reglas_id = NULL;
            $objBeanSeccion->categorias_id = NULL;
            $objBeanSeccion->tags_id = NULL;
            $objBeanSeccion->peso = $this->obtenerPesoSeccionPortada($objPortada->id);
            $objBeanSeccion->id_mongo = NULL;
            $objBeanSeccion->estado = $this->config->item('estado:borrador');
            $objBeanSeccion->templates_id = $this->config->item('template:8items');
            $objBeanSeccion->fecha_registro = date("Y-m-d H:i:s");
            $objBeanSeccion->usuario_registro = $user_id;
            $objBeanSeccion->fecha_actualizacion = date("Y-m-d H:i:s");
            $objBeanSeccion->usuario_actualizacion = $user_id;
            $objBeanSeccion->estado_migracion = $this->config->item('migracion:nuevo');
            $objBeanSeccion->fecha_migracion = '0000-00-00 00:00:00';
            $objBeanSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
            $objBeanSeccion->grupo_maestros_id = $objMaestro->id;
            $objBeanSeccionSaved = $this->secciones_m->save($objBeanSeccion);
        }
    }

    private function obtenerPesoSeccionPortada($portada_id) {
        $peso = 1;
        $secciones = $this->secciones_m->order_by('peso', 'ASC')->get_many_by(array("portadas_id" => $portada_id));
        if (count($secciones) > 0) {
            $nuevo_peso = 2;
            foreach ($secciones as $puntero => $objSeccion) {
                $this->secciones_m->update($objSeccion->id, array("peso" => $nuevo_peso, "estado_migracion" => $this->config->item('migracion:actualizado')));
                $nuevo_peso++;
            }
        }
        return $peso;
    }

    public function existNameMaestro($nombre_maestro, $tipo_grupo_maestro_id, $post) {
        $returnValue = false;
        if ($post['programa'] == 0 && $post['coleccion'] == 0 && $post['lista'] == 0) {
            $returnValue = $this->grupo_maestro_m->existNameMaestro($nombre_maestro, $post['canal_id']);
        } else {
            if ($tipo_grupo_maestro_id == $this->config->item('videos:programa')) {
                if ($post['programa'] > 0) {
                    $returnValue = $this->grupo_maestro_m->existNameMaestro($nombre_maestro, $post['canal_id']);
                }
            }
            if ($tipo_grupo_maestro_id == $this->config->item('videos:coleccion')) {
                if ($post['programa'] > 0) {
                    $returnValue = $this->existCollection($post['programa'], $nombre_maestro);
                } else {
                    $returnValue = $this->grupo_maestro_m->existNameMaestro($nombre_maestro, $post['canal_id']);
                }
            }
            if ($tipo_grupo_maestro_id == $this->config->item('videos:lista')) {
                if ($post['programa'] > 0 && $post['coleccion'] > 0) {
                    $returnValue = $this->existLista($post['programa'], $post['coleccion'], $nombre_maestro);
                } else {
                    if ($post['programa'] > 0 && $post['coleccion'] == 0) {
                        $returnValue = $this->existListaByPrograma($post['programa'], $post['coleccion'], $nombre_maestro);
                    }
                }
            }
        }
        return $returnValue;
    }

    public function existListaByPrograma($programa, $coleccion, $nombre_maestro) {
        $returnValue = false;
        $listCollection = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $programa, "tipo_grupo_maestros_id" => $this->config->item('videos:programa')));
        if (count($listCollection) > 0) {
            $arrayIdMaestroLista = array();
            foreach ($listCollection as $index => $objDetalle) {
                if ($objDetalle->grupo_maestro_id != NULL) {
                    array_push($arrayIdMaestroLista, $objDetalle->grupo_maestro_id);
                }
            }
            if (count($arrayIdMaestroLista) > 0) {
                $objCollectionMaestro = $this->grupo_maestro_m->getListCollection($arrayIdMaestroLista);
                if (count($objCollectionMaestro) > 0) {
                    foreach ($objCollectionMaestro as $indice => $objMaestro) {
                        if (strtolower(trim($objMaestro->nombre)) == $nombre_maestro && $objMaestro->tipo_grupo_maestro_id == $this->config->item('videos:lista')) {
                            $returnValue = true;
                            break;
                        }
                    }
                }
            }
        }
        return $returnValue;
    }

    public function existLista($programa, $coleccion, $nombre_maestro) {
        $returnValue = false;
        $listCollection = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $coleccion, "tipo_grupo_maestros_id" => $this->config->item('videos:coleccion')));
        if (count($listCollection) > 0) {
            $arrayIdMaestroLista = array();
            foreach ($listCollection as $index => $objDetalle) {
                if ($objDetalle->grupo_maestro_id != NULL) {
                    array_push($arrayIdMaestroLista, $objDetalle->grupo_maestro_id);
                }
            }
            if (count($arrayIdMaestroLista) > 0) {
                $objCollectionMaestro = $this->grupo_maestro_m->getListCollection($arrayIdMaestroLista);
                if (count($objCollectionMaestro) > 0) {
                    foreach ($objCollectionMaestro as $indice => $objMaestro) {
                        if (strtolower(trim($objMaestro->nombre)) == $nombre_maestro) {
                            $returnValue = true;
                            break;
                        }
                    }
                }
            }
        }
        return $returnValue;
    }

    public function existCollection($programa, $nombre_maestro) {
        $returnValue = false;
        $listCollection = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $programa, "tipo_grupo_maestros_id" => $this->config->item('videos:programa')));
        if (count($listCollection) > 0) {
            $arrayIdMaestro = array();
            foreach ($listCollection as $index => $objDetalle) {
                if ($objDetalle->grupo_maestro_id != NULL) {
                    array_push($arrayIdMaestro, $objDetalle->grupo_maestro_id);
                }
            }
            if (count($arrayIdMaestro) > 0) {
                $objCollectionMaestro = $this->grupo_maestro_m->getListCollection($arrayIdMaestro);
                if (count($objCollectionMaestro) > 0) {
                    foreach ($objCollectionMaestro as $indice => $objMaestro) {
                        if (strtolower(trim($objMaestro->nombre)) == strtolower(trim($nombre_maestro))) {
                            $returnValue = true;
                            break;
                        }
                    }
                }
            }
        }
        return $returnValue;
    }

    public function _saveMaestroDetalle($post, $objBeanMaestro) {
        $user_id = (int) $this->session->userdata('user_id');
        $objBeanMaestroDetalle = new stdClass();
        $objBeanMaestroDetalle->id = NULL;
        $objBeanMaestroDetalle->video_id = NULL;
        $objBeanMaestroDetalle->id_mongo = NULL;
        $objBeanMaestroDetalle->estado = 1;
        $objBeanMaestroDetalle->fecha_registro = date("Y-m-d H:i:s");
        $objBeanMaestroDetalle->usuario_registro = $user_id;
        $objBeanMaestroDetalle->fecha_actualizacion = date("Y-m-d H:i:s");
        $objBeanMaestroDetalle->usuario_actualizacion = $user_id;
        $objBeanMaestroDetalle->estado_migracion = 0;
        $objBeanMaestroDetalle->fecha_migracion = '0000-00-00 00:00:00';
        $objBeanMaestroDetalle->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
        $objBeanMaestroDetalle->grupo_maestro_id = $objBeanMaestro->id;
        $objBeanMaestroDetalle->tipo_grupo_maestros_id = $this->config->item('videos:programa');
        switch ($post['tipo_maestro']) {
            case 'coleccion':
                if ($post['programa'] > 0) {
                    $objBeanMaestroDetalle->grupo_maestro_padre = $post['programa'];
                    $objBeanMaestroDetalle->tipo_grupo_maestros_id = $this->config->item('videos:programa');
                    $objBeanMaestroDetalle = $this->grupo_detalle_m->saveMaestroDetalle($objBeanMaestroDetalle);
                }
                break;
            case 'lista':
                if ($post['coleccion'] > 0) {
                    $objBeanMaestroDetalle->grupo_maestro_padre = $post['coleccion'];
                    $objBeanMaestroDetalle->tipo_grupo_maestros_id = $this->config->item('videos:coleccion');
                    $objBeanMaestroDetalle = $this->grupo_detalle_m->saveMaestroDetalle($objBeanMaestroDetalle);
                } else {
                    if ($post['programa'] > 0) {
                        $objBeanMaestroDetalle->grupo_maestro_padre = $post['programa'];
                        $objBeanMaestroDetalle->tipo_grupo_maestros_id = $this->config->item('videos:programa');
                        $objBeanMaestroDetalle = $this->grupo_detalle_m->saveMaestroDetalle($objBeanMaestroDetalle);
                    }
                }
                break;
        }
        return $objBeanMaestroDetalle;
    }

    public function generate_coleccion() {
        if ($this->input->is_ajax_request()) {
            //$this->config->load('videos/uploads');
            header('Content-Type: application/x-json; charset=utf-8');
            if ($this->input->post('programa') > 0) {
                $arrayCollection = $this->grupo_detalle_m->getGrupoDetalle(array("grupo_maestro_padre" => $this->input->post('programa')));
                $returnValue = array();
                $returnValue[0] = lang('videos:select_list');
                $returnValue['error'] = 0;
                if (count($arrayCollection) > 0) {
                    $array_id_maestro = array();
                    foreach ($arrayCollection as $index => $objCollection) {
                        if ($objCollection->grupo_maestro_id != NULL) {
                            if ($this->isType($objCollection->grupo_maestro_id, $this->config->item('videos:coleccion'))) {
                                array_push($array_id_maestro, $objCollection->grupo_maestro_id);
                            }
                        }
                    }
                    if (count($array_id_maestro) > 0) {
                        $arrayCollectionMaestro = $this->grupo_maestro_m->getListCollection($array_id_maestro);
                    } else {
                        $arrayCollectionMaestro = array();
                    }
                    if (count($arrayCollectionMaestro) > 0) {
                        foreach ($arrayCollectionMaestro as $indice => $objMaestro) {
                            if ($objMaestro->estado < $this->config->item('estado:eliminado')) {
                                $returnValue[$objMaestro->id] = $objMaestro->nombre;
                            }
                        }
                    } else {
                        $returnValue['error'] = 1; // 1 => no hay datos a mostrar
                    }
                } else {
                    $returnValue['error'] = 1; // 1 => no hay datos a mostrar
                }
            } else {
                $returnValue = $this->_getListMasterChannel($this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:coleccion'), 'canales_id' => $this->input->post('canal_id')), 'nombre'));
            }


            //$this->vd($returnValue);die();
            echo(json_encode($returnValue));
        }
    }

    public function generate_lista() {
        if ($this->input->is_ajax_request()) {
            //$this->config->load('videos/uploads');
            header('Content-Type: application/x-json; charset=utf-8');
            if ($this->input->post('programa') > 0) {
                if ($this->input->post('coleccion') > 0) {
                    $arrayCollection = $this->grupo_detalle_m->getGrupoDetalle(array("grupo_maestro_padre" => $this->input->post('coleccion')));
                    $returnValue = array();
                    $returnValue[0] = lang('videos:select_list');
                    $returnValue['error'] = 0;
                    if (count($arrayCollection) > 0) {
                        $array_id_maestro = array();
                        foreach ($arrayCollection as $index => $objCollection) {
                            if ($objCollection->grupo_maestro_id != NULL) {
                                if ($this->isType($objCollection->grupo_maestro_id, $this->config->item('videos:lista'))) {
                                    array_push($array_id_maestro, $objCollection->grupo_maestro_id);
                                }
                                //array_push($array_id_maestro, $objCollection->grupo_maestro_id);
                            }
                        }
                        if (count($array_id_maestro) > 0) {
                            $arrayCollectionMaestro = $this->grupo_maestro_m->getListCollection($array_id_maestro);
                        } else {
                            $arrayCollectionMaestro = array();
                        }
                        if (count($arrayCollectionMaestro) > 0) {
                            foreach ($arrayCollectionMaestro as $indice => $objMaestro) {
                                if ($objMaestro->estado < $this->config->item('estado:eliminado')) {
                                    $returnValue[$objMaestro->id] = $objMaestro->nombre;
                                }
                            }
                        } else {
                            $returnValue['error'] = 1; // 1 => no hay datos a mostrar
                        }
                    } else {
                        $returnValue['error'] = 1; // 1 => no hay datos a mostrar
                    }
                } else {
                    if ($this->input->post('programa') > 0) {
                        $arrayCollection = $this->grupo_detalle_m->getGrupoDetalle(array("grupo_maestro_padre" => $this->input->post('programa')));
                        $returnValue = array();
                        $returnValue[0] = lang('videos:select_list');
                        $returnValue['error'] = 0;
                        if (count($arrayCollection) > 0) {
                            $array_id_maestro = array();
                            foreach ($arrayCollection as $index => $objCollection) {
                                if ($objCollection->grupo_maestro_id != NULL) {
                                    if ($this->isType($objCollection->grupo_maestro_id, $this->config->item('videos:lista'))) {
                                        array_push($array_id_maestro, $objCollection->grupo_maestro_id);
                                    }
                                    //array_push($array_id_maestro, $objCollection->grupo_maestro_id);
                                }
                            }
                            if (count($array_id_maestro) > 0) {
                                $arrayCollectionMaestro = $this->grupo_maestro_m->getListCollection($array_id_maestro);
                            } else {
                                $arrayCollectionMaestro = array();
                            }
                            if (count($arrayCollectionMaestro) > 0) {
                                foreach ($arrayCollectionMaestro as $indice => $objMaestro) {
                                    $returnValue[$objMaestro->id] = $objMaestro->nombre;
                                }
                            } else {
                                $returnValue['error'] = 1; // 1 => no hay datos a mostrar
                            }
                        } else {
                            $returnValue['error'] = 1; // 1 => no hay datos a mostrar
                        }
                    } else {
                        $returnValue = array();
                        $returnValue[0] = lang('videos:select_list');
                        $returnValue['error'] = 0;
                    }
                }
            } else {
                if ($this->input->post('coleccion') > 0) {
                    $arrayCollection = $this->grupo_detalle_m->getGrupoDetalle(array("grupo_maestro_padre" => $this->input->post('coleccion')));
                    $returnValue = array();
                    $returnValue[0] = lang('videos:select_list');
                    $returnValue['error'] = 0;
                    if (count($arrayCollection) > 0) {
                        $array_id_maestro = array();
                        foreach ($arrayCollection as $index => $objCollection) {
                            if ($objCollection->grupo_maestro_id != NULL) {
                                if ($this->isType($objCollection->grupo_maestro_id, $this->config->item('videos:lista'))) {
                                    array_push($array_id_maestro, $objCollection->grupo_maestro_id);
                                }
                            }
                        }
                        $arrayCollectionMaestro = $this->grupo_maestro_m->getListCollection($array_id_maestro);
                        if (count($arrayCollectionMaestro) > 0) {
                            foreach ($arrayCollectionMaestro as $indice => $objMaestro) {
                                $returnValue[$objMaestro->id] = $objMaestro->nombre;
                            }
                        } else {
                            $returnValue['error'] = 1; // 1 => no hay datos a mostrar
                        }
                    } else {
                        $returnValue['error'] = 1; // 1 => no hay datos a mostrar
                    }
                } else {
                    $returnValue = $this->_getListMasterChannel($this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:lista'), 'canales_id' => $this->input->post('canal_id')), 'nombre'));
                }
            }

            //$this->vd($returnValue);die();
            echo(json_encode($returnValue));
        }
    }

    public function subir_imagen($maestro_id = 0) {
        $directorio = '../temp/';
        if ($maestro_id > 0) {
            $directorio = '';
        }
        $fileType = array('image/jpeg', 'image/pjpeg', 'image/png');

        // Bandera para procesar las fotos si pasa el tamaño definido
        $pasaImgSize = false;
        //bandera de error al procesar las fotos
        $respuestaFile = false;
        // nombre por default de las fotos a subir
        $fileName = '';
        // error del lado del servidor
        $mensajeFile = 'ERROR EN EL SCRIPT';
        //array de imagenes cortadas
        $arrayImagenes = array();

        // Obtenemos los datos del archivo
        $tamanio = $_FILES['userfile']['size'];
        $tipo = $_FILES['userfile']['type'];
        $archivo = $_FILES['userfile']['name'];
        // Tamaño de la imagen
        $imageSize = getimagesize($_FILES['userfile']['tmp_name']);

        // Verificamos la extensión del archivo independiente del tipo mime
        $extension = explode('.', $_FILES['userfile']['name']);
        $num = count($extension) - 1;
        // Creamos el nombre del archivo dependiendo la opción
        $imgFile = time() . '.' . $extension[$num];
        // Verificamos el tamaño válido para las fotos
        if ($imageSize[0] >= $this->config->item('videos:minWidth') && $imageSize[1] >= $this->config->item('videos:minHeight') && ($extension[$num] == 'jpg' || $extension[$num] == 'png')) {
            $pasaImgSize = true;
        }
        // Verificamos el status de las dimensiones de la imagen a publicar mediante nuestro jQuery para fotos
        if ($pasaImgSize == true) {
            // Verificamos Tamaño y extensiones
            if (in_array($tipo, $fileType) && $tamanio > 0 && $tamanio <= $this->config->item('imagen:maxSize')) {
                // Intentamos copiar el archivo
                if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
                    umask(0);
                    // Verificamos si se pudo copiar el archivo a nustra carpeta
                    if (move_uploaded_file($_FILES['userfile']['tmp_name'], UPLOAD_IMAGENES_VIDEOS . $directorio . $imgFile)) {
                        if ($maestro_id > 0) {
                            //usamos el crop de imagemagic para crear las 4 imagenes
                            $arrayTipoImagen = $this->tipo_imagen_m->listType();
                            $width = $imageSize[0];
                            $height = $imageSize[1];
                            if (count($arrayTipoImagen) > 0) {
                                foreach ($arrayTipoImagen as $index => $objTipoImagen) {
                                    if ($width >= $objTipoImagen->ancho && $height >= $objTipoImagen->alto) {
                                        $this->imagenes_lib->loadImage(UPLOAD_IMAGENES_VIDEOS . $directorio . $imgFile);
                                        $this->imagenes_lib->crop($objTipoImagen->ancho, $objTipoImagen->alto, 'center');
                                        $this->imagenes_lib->save(UPLOAD_IMAGENES_VIDEOS . $directorio . preg_replace("/\\.[^.\\s]{3,4}$/", "", $imgFile) . '_' . $objTipoImagen->ancho . 'x' . $objTipoImagen->alto . '.' . $extension[$num]);
                                        array_push($arrayImagenes, preg_replace("/\\.[^.\\s]{3,4}$/", "", $imgFile) . '_' . $objTipoImagen->ancho . 'x' . $objTipoImagen->alto . '.' . $extension[$num]);
                                    }
                                }
                            }
                        }
                        $respuestaFile = 'done';
                        $fileName = $imgFile;
                        $mensajeFile = $imgFile;
                    } else {
                        // error del lado del servidor
                        $mensajeFile = 'No se pudo subir el archivo';
                    }
                } else {
                    // error del lado del servidor
                    $mensajeFile = 'No se pudo subir el archivo';
                }
            } else {
                // Error en el tamaño y tipo de imagen
                $mensajeFile = 'Verifique el tamaño y tipo de imagen';
            }
        } else {
            // Error en las dimensiones de la imagen
            $mensajeFile = 'Verifique las dimensiones de la Imagen';
        }

        $salidaJson = array("respuesta" => $respuestaFile,
            "mensaje" => $mensajeFile,
            "imagenes" => $arrayImagenes,
            "fileName" => $fileName);
        echo json_encode($salidaJson);
    }

    public function _saveParentImage($canal_id, $video_id, $parentImage) {
        $user_id = (int) $this->session->userdata('user_id');
        $img_path = UPLOAD_IMAGENES_VIDEOS . $parentImage;
        $objBeanImage = new stdClass();
        $objBeanImage->id = NULL;
        $objBeanImage->canales_id = $canal_id;
        $objBeanImage->grupo_maestros_id = NULL;
        $objBeanImage->videos_id = $video_id;
        $objBeanImage->imagen = $parentImage;
        $objBeanImage->tipo_imagen_id = $this->_getTypeImage($img_path);
        $objBeanImage->estado = $this->config->item('imagen:borrador');
        $objBeanImage->fecha_registro = date("Y-m-d H:i:s");
        $objBeanImage->usuario_registro = $user_id;
        $objBeanImage->fecha_actualizacion = date("Y-m-d H:i:s");
        $objBeanImage->usuario_actualizacion = $user_id;
        $objBeanImage->estado_migracion = 0;
        $objBeanImage->fecha_migracion = '0000-00-00 00:00:00';
        $objBeanImage->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
        $objBeanImage->imagen_padre = NULL;
        $objBeanImage->procedencia = '0';
        $objBeanImageSaved = $this->imagen_m->saveImage($objBeanImage);
        return $objBeanImageSaved->id;
    }

    public function registrar_imagenes($canal_id, $video_id) {
        if ($this->input->is_ajax_request()) {
            $returnValue = 0;
            $arrayImagenes = $this->input->post('imagenes');
            //eliminamos la imagen original
            if (file_exists(UPLOAD_IMAGENES_VIDEOS . $this->input->post('fileName'))) {
                unlink(UPLOAD_IMAGENES_VIDEOS . $this->input->post('fileName'));
            }
            $parent_id = NULL; //$this->_saveParentImage($canal_id, $video_id,$this->input->post('fileName'));
            if (count($arrayImagenes) > 0) {
                foreach ($arrayImagenes as $index => $nameImage) {
                    $img_path = UPLOAD_IMAGENES_VIDEOS . $nameImage;
                    $ruta_absoluta_imagen = FCPATH . 'uploads/imagenes/' . $nameImage;
                    if (file_exists($img_path)) {
                        $grupo_maestro_id = NULL;
                        $objGrupoDetalle = $this->grupo_detalle_m->get_by(array("video_id" => $video_id));
                        if (count($objGrupoDetalle) > 0) {
                            $grupo_maestro_id = $objGrupoDetalle->grupo_maestro_padre;
                        }
                        $user_id = (int) $this->session->userdata('user_id');
                        $objBeanImage = new stdClass();
                        $objBeanImage->id = NULL;
                        $objBeanImage->canales_id = NULL; //$canal_id;
                        $objBeanImage->grupo_maestros_id = NULL; //$grupo_maestro_id;
                        $objBeanImage->videos_id = $video_id;
                        $objBeanImage->imagen = $nameImage;
                        $objBeanImage->tipo_imagen_id = $this->_getTypeImage($img_path);
                        $objBeanImage->estado = $this->config->item('imagen:borrador');
                        $objBeanImage->fecha_registro = date("Y-m-d H:i:s");
                        $objBeanImage->usuario_registro = $user_id;
                        $objBeanImage->fecha_actualizacion = date("Y-m-d H:i:s");
                        $objBeanImage->usuario_actualizacion = $user_id;
                        $objBeanImage->estado_migracion = 0;
                        $objBeanImage->fecha_migracion = '0000-00-00 00:00:00';
                        $objBeanImage->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                        $objBeanImage->imagen_padre = $parent_id;
                        $objBeanImage->procedencia = '0';
                        $objBeanImage = $this->imagen_m->saveImage($objBeanImage);
                        //enviamos al servidor elemento

                        $path_image_element = $this->elemento_upload($objBeanImage->id, $ruta_absoluta_imagen);
                        $array_path = explode("/", $path_image_element);
                        if ($array_path[0] == $this->config->item('server:elemento')) {
                            unset($array_path[0]);
                        }
                        $path_single_element = implode('/', $array_path);
                        $this->imagen_m->uploadNameImage($objBeanImage->id, $path_single_element);

                        if ($objBeanImage->tipo_imagen_id == $this->config->item('imagen:small')) {
                            $imagen_id_small = $objBeanImage->id;
                            $parent_id = $objBeanImage->id;
                            $nameImage_small = $path_image_element;
                        }

                        //eliminamos la imagen local
                        unlink($img_path);
                    }
                }
                $returnValue = 1;
            }
            $arrayImagenes = $this->_getListImagen($video_id, false);
            echo json_encode(array('respuesta' => $returnValue, 'video_id' => $video_id, 'imagen_id' => $imagen_id_small, 'url' => $this->config->item('protocolo:http') . $nameImage_small, 'imagenes' => $arrayImagenes));
        }
    }

    public function subir_imagenes_maestro() {
        
    }

    public function registrar_imagenes_maestro($maestro_id, $arrayImagenesSubir = array(), $imagen_original = '') {
        //if ($this->input->is_ajax_request()) {
        $returnValue = 0;
        if (count($arrayImagenesSubir) == 0) {
            $arrayImagenes = $this->input->post('imagenes');
        } else {
            $arrayImagenes = $arrayImagenesSubir;
        }
        if (strlen(trim($imagen_original)) == 0) {
            $nombre_imagen_original = $this->input->post('fileName');
            //eliminamos la imagen original
            if (file_exists(UPLOAD_IMAGENES_VIDEOS . $nombre_imagen_original)) {
                unlink(UPLOAD_IMAGENES_VIDEOS . $nombre_imagen_original);
            }
        } else {
            $nombre_imagen_original = $imagen_original;
            //eliminamos la imagen original
            if (file_exists($nombre_imagen_original)) {
                unlink($nombre_imagen_original);
            }
        }
        //error_log(print_r($arrayImagenes, true));die();
        $parent_id = NULL; //$this->_saveParentImage($canal_id, $video_id,$this->input->post('fileName'));
        if (count($arrayImagenes) > 0) {
            foreach ($arrayImagenes as $index => $nameImage) {
                $img_path = UPLOAD_IMAGENES_VIDEOS . $nameImage;
                $ruta_absoluta_imagen = FCPATH . 'uploads/imagenes/' . $nameImage;
                if (file_exists($img_path)) {
                    $user_id = (int) $this->session->userdata('user_id');
                    $objBeanImage = new stdClass();
                    $objBeanImage->id = NULL;
                    $objBeanImage->canales_id = NULL; //$canal_id;
                    $objBeanImage->grupo_maestros_id = $maestro_id;
                    $objBeanImage->videos_id = NULL;
                    $objBeanImage->imagen = $nameImage;
                    $objBeanImage->tipo_imagen_id = $this->_getTypeImage($img_path);
                    if (count($arrayImagenesSubir) == 0) {
                        $objBeanImage->estado = $this->config->item('imagen:borrador');
                    } else {
                        $objBeanImage->estado = $this->config->item('imagen:publicado');
                    }
                    $objBeanImage->fecha_registro = date("Y-m-d H:i:s");
                    $objBeanImage->usuario_registro = $user_id;
                    $objBeanImage->fecha_actualizacion = date("Y-m-d H:i:s");
                    $objBeanImage->usuario_actualizacion = $user_id;
                    $objBeanImage->estado_migracion = 0;
                    $objBeanImage->fecha_migracion = '0000-00-00 00:00:00';
                    $objBeanImage->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                    $objBeanImage->imagen_padre = $parent_id;
                    $objBeanImage->procedencia = '0';
                    $objBeanImage = $this->imagen_m->saveImage($objBeanImage);
                    //enviamos al servidor elemento

                    $path_image_element = $this->elemento_upload($objBeanImage->id, $ruta_absoluta_imagen);
                    $array_path = explode("/", $path_image_element);
                    if ($array_path[0] == $this->config->item('server:elemento')) {
                        unset($array_path[0]);
                    }
                    $path_single_element = implode('/', $array_path);
                    $this->imagen_m->uploadNameImage($objBeanImage->id, $path_single_element);

                    if ($objBeanImage->tipo_imagen_id == $this->config->item('imagen:small')) {
                        $imagen_id_small = $objBeanImage->id;
                        $parent_id = $objBeanImage->id;
                        $nameImage_small = $path_image_element;
                    }

                    //eliminamos la imagen local
                    unlink($img_path);
                }
            }
            $returnValue = 1;
        }
        if (count($arrayImagenesSubir) == 0) {
            $arrayImagenes = $this->listarImagenesMaestro($maestro_id, false);
            echo json_encode(array('respuesta' => $returnValue, 'maestro_id' => $maestro_id, 'imagen_id' => $imagen_id_small, 'url' => $this->config->item('protocolo:http') . $nameImage_small, 'imagenes' => $arrayImagenes));
        }
        //}
    }

    /**
     * 
     * @param type $tipo
     * @param type $id
     * @param type $arrayImagenSaved
     */
    public function generarPortadaCanal($objCanal, $objetoMaestro = NULL, $tipo_portada = 5) {
        $user_id = (int) $this->session->userdata('user_id');
        //creamos el objeto
        $objBeanPortada = new stdClass();
        $objBeanPortada->id = NULL;
        $objBeanPortada->canales_id = $objCanal->id;
        if ($objetoMaestro == NULL) {
            $objBeanPortada->nombre = 'Portada ' . $objCanal->nombre; ///PORTADA + nombre del canal
            $objBeanPortada->descripcion = $objCanal->descripcion; //jala del canal
            $objBeanPortada->origen_id = $objCanal->id;
        } else {
            $objBeanPortada->nombre = 'Portada ' . $objetoMaestro->nombre; ///PORTADA + nombre del canal
            $objBeanPortada->descripcion = $objetoMaestro->descripcion; //jala del canal            
            $objBeanPortada->origen_id = $objetoMaestro->id;
        }
        $objBeanPortada->tipo_portadas_id = $tipo_portada; //$this->config->item('portada:canal');
        $objBeanPortada->estado = '0';
        $objBeanPortada->fecha_registro = date("Y-m-d H:i:s");
        $objBeanPortada->usuario_registro = $user_id;
        $objBeanPortada->fecha_actualizacion = date("Y-m-d H:i:s");
        $objBeanPortada->usuario_actualizacion = $user_id;
        $objBeanPortada->id_mongo = '0';
        $objBeanPortada->estado_migracion = '0';
        $objBeanPortada->fecha_migracion = '0000-00-00 00:00:00';
        $objBeanPortada->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
        $objBeanPortadaSaved = $this->portada_m->save($objBeanPortada);

        if ($objetoMaestro == NULL) {
            $arraySecciones = $this->tipo_secciones_m->get_many_by(array());
        } else {
            $arraySecciones = $this->tipo_secciones_m->get_many_by(array());
            foreach ($arraySecciones as $puntero => $oS) {
                if ($oS->id == $this->config->item('seccion:programa')) {
                    unset($arraySecciones[$puntero]);
                }
            }
        }
        $pos = 0;
        //iteramos para crear portadas por cada seccion
        foreach ($arraySecciones as $ind => $objTipoSeccion) {
            if ($objTipoSeccion->id < intval($this->config->item('seccion:perzonalizado'))) {//no se creara secciones personalizadas
                if ($objTipoSeccion->id != intval($this->config->item('seccion:coleccion'))) {//solo secciones que sean difernetes a colecciones
                    $objBeanSeccion = new stdClass();
                    $objBeanSeccion->id = NULL;
                    $objBeanSeccion->nombre = ucwords($objTipoSeccion->nombre); // nombre de la seccion es el nombre del tipo de la seccion
                    if ($objTipoSeccion->id == $this->config->item('seccion:destacado')) {
                        $objBeanSeccion->templates_id = '1';
                    } else {
                        if ($objTipoSeccion->id == $this->config->item('seccion:programa')) {
                            $objBeanSeccion->templates_id = '6';
                        } else {
                            if ($objTipoSeccion->id == $this->config->item('seccion:video')) {
                                $objBeanSeccion->templates_id = '5';
                            } else {
                                $objBeanSeccion->templates_id = '3';
                            }
                        }
                    }
                    $objBeanSeccion->descripcion = '';
                    $objBeanSeccion->tipo = '0';
                    $objBeanSeccion->portadas_id = $objBeanPortadaSaved->id;
                    $objBeanSeccion->tipo_secciones_id = $objTipoSeccion->id;
                    $objBeanSeccion->peso = $pos;
                    $objBeanSeccion->id_mongo = '0';
                    $objBeanSeccion->estado = '0';
                    $objBeanSeccion->fecha_registro = date("Y-m-d H:i:s");
                    $objBeanSeccion->usuario_registro = $user_id;
                    $objBeanSeccion->fecha_actualizacion = date("Y-m-d H:i:s");
                    $objBeanSeccion->usuario_actualizacion = $user_id;
                    $objBeanSeccion->estado_migracion = '0';
                    $objBeanSeccion->fecha_migracion = '0000-00-00 00:00:00';
                    $objBeanSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                    $objBeanSeccion->grupo_maestros_id = NULL;
                    $objBeanSeccionSaved = $this->secciones_m->save($objBeanSeccion);

                    /* registramos el detalle de la session, listo todos los programas del canal
                     * obtener la imagen de portada del canal
                     */
                    if ($objTipoSeccion->id == intval($this->config->item('seccion:destacado'))) {//seccion destacado
                        if ($objetoMaestro == NULL) {
                            $objImagen = $this->imagen_m->get_by(array("canales_id" => $objCanal->id, "estado" => "1", "tipo_imagen_id" => $this->config->item('imagen:extralarge')));
                        } else {
                            $objImagen = $this->imagen_m->get_by(array("grupo_maestros_id" => $objetoMaestro->id, "estado" => "1", "tipo_imagen_id" => $this->config->item('imagen:extralarge')));
                        }
                        if (count($objImagen) > 0) {
                            $objBeanDetalleSecciones = new stdClass();
                            $objBeanDetalleSecciones->id = NULL;
                            $objBeanDetalleSecciones->secciones_id = $objBeanSeccionSaved->id;
                            $objBeanDetalleSecciones->reglas_id = NULL;
                            $objBeanDetalleSecciones->videos_id = NULL;
                            $objBeanDetalleSecciones->grupo_maestros_id = NULL;
                            $objBeanDetalleSecciones->categorias_id = NULL;
                            $objBeanDetalleSecciones->tags_id = NULL;
                            $objBeanDetalleSecciones->imagenes_id = $objImagen->id;
                            $objBeanDetalleSecciones->peso = 1;
                            $objBeanDetalleSecciones->descripcion_item = NULL;
                            //$objBeanDetalleSecciones->templates_id = '0';
                            $objBeanDetalleSecciones->estado = 1;
                            $objBeanDetalleSecciones->fecha_registro = date("Y-m-d H:i:s");
                            $objBeanDetalleSecciones->usuario_registro = $user_id;
                            $objBeanDetalleSecciones->estado_migracion = '0';
                            $objBeanDetalleSecciones->fecha_migracion = '0000-00-00 00:00:00';
                            $objBeanDetalleSecciones->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                            $objBeanDetalleSeccionesSaved = $this->detalle_secciones_m->save($objBeanDetalleSecciones);
                            $this->secciones_m->update($objBeanSeccionSaved->id, array("estado" => "1"));
                            //$this->portada_m->update($objBeanPortadaSaved->id, array("estado" => "1"));
                        }
                    } else { //seccion programas
                        $objColeccionGrupoMaestro = $this->obtenerMaestrosParaSecciones($objTipoSeccion->id, $objCanal->id, $objetoMaestro); //$this->grupo_maestro_m->get_many_by(array("tipo_grupo_maestro_id" => "3", "canales_id" => $objCanal->id));
                        if (count($objColeccionGrupoMaestro) > 0) {
                            foreach ($objColeccionGrupoMaestro as $index => $objGrupoMaestro) {
                                if ($this->_obtenerImagenPorMaestro($objGrupoMaestro->id, $this->config->item('imagen:small'), $objTipoSeccion->id, $objCanal->id) > 0) {
                                    $objBeanDetalleSecciones = new stdClass();
                                    $objBeanDetalleSecciones->id = NULL;
                                    $objBeanDetalleSecciones->secciones_id = $objBeanSeccionSaved->id;
                                    $objBeanDetalleSecciones->reglas_id = NULL;
                                    if ($objTipoSeccion->id == $this->config->item('seccion:video')) {
                                        $objBeanDetalleSecciones->videos_id = $objGrupoMaestro->id;
                                        $objBeanDetalleSecciones->grupo_maestros_id = NULL;
                                    } else {
                                        $objBeanDetalleSecciones->videos_id = NULL;
                                        $objBeanDetalleSecciones->grupo_maestros_id = $objGrupoMaestro->id;
                                    }
                                    $objBeanDetalleSecciones->categorias_id = NULL;
                                    $objBeanDetalleSecciones->tags_id = NULL;
                                    $objBeanDetalleSecciones->imagenes_id = $this->_obtenerImagenPorMaestro($objGrupoMaestro->id, $this->config->item('imagen:small'), $objTipoSeccion->id, $objCanal->id); //
                                    $objBeanDetalleSecciones->peso = $index + 2;
                                    $objBeanDetalleSecciones->descripcion_item = NULL;
                                    //$objBeanDetalleSecciones->templates_id = '5';
                                    $objBeanDetalleSecciones->estado = 1;
                                    $objBeanDetalleSecciones->fecha_registro = date("Y-m-d H:i:s");
                                    $objBeanDetalleSecciones->usuario_registro = $user_id;
                                    $objBeanDetalleSecciones->estado_migracion = '0';
                                    $objBeanDetalleSecciones->fecha_migracion = '0000-00-00 00:00:00';
                                    $objBeanDetalleSecciones->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                                    $objBeanDetalleSeccionesSaved = $this->detalle_secciones_m->save($objBeanDetalleSecciones);
                                    $this->portada_m->update($objBeanPortadaSaved->id, array("estado" => "1"));
                                    $this->secciones_m->update($objBeanSeccionSaved->id, array("estado" => "1"));
                                }
                            }
                            //$this->portada_m->update($objBeanPortadaSaved->id, array("estado" => "1"));
                        }
                    }
                } else {//seccion para colecciones
                    $cont_coleccion = $pos;
                    $objColeccionGrupoMaestro = $this->obtenerMaestrosParaSecciones($objTipoSeccion->id, $objCanal->id, $objetoMaestro); //$this->grupo_maestro_m->get_many_by(array("tipo_grupo_maestro_id" => "3", "canales_id" => $objCanal->id));                    
                    if (count($objColeccionGrupoMaestro) > 0) {
                        foreach ($objColeccionGrupoMaestro as $indi => $objMaestroColeccion) {//creamos secciones por cada coleccion que se encuentre
                            $objBeanSeccion = new stdClass();
                            $objBeanSeccion->id = NULL;
                            $objBeanSeccion->nombre = ucwords($objMaestroColeccion->nombre); //ucwords($objTipoSeccion->nombre); // Destacado + nombre del canal
                            $objBeanSeccion->templates_id = '4';
                            $objBeanSeccion->descripcion = '';
                            $objBeanSeccion->tipo = '0';
                            $objBeanSeccion->portadas_id = $objBeanPortadaSaved->id;
                            $objBeanSeccion->tipo_secciones_id = $objTipoSeccion->id;
                            $objBeanSeccion->peso = $cont_coleccion;
                            $objBeanSeccion->id_mongo = '0';
                            $objBeanSeccion->estado = '0';
                            $objBeanSeccion->fecha_registro = date("Y-m-d H:i:s");
                            $objBeanSeccion->usuario_registro = $user_id;
                            $objBeanSeccion->fecha_actualizacion = date("Y-m-d H:i:s");
                            $objBeanSeccion->usuario_actualizacion = $user_id;
                            $objBeanSeccion->estado_migracion = '0';
                            $objBeanSeccion->fecha_migracion = '0000-00-00 00:00:00';
                            $objBeanSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                            $objBeanSeccion->grupo_maestros_id = NULL;
                            $objBeanSeccionSaved = $this->secciones_m->save($objBeanSeccion);

                            //$objListaGrupoMaestro = $this->obtenerMaestrosParaSecciones($objTipoSeccion->id, $objCanal->id, $objetoMaestro);
                            $objListaGrupoMaestro = array();
                            $lista = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $objMaestroColeccion->id));
                            if (count($lista) > 0) {
                                foreach ($lista as $pun => $objGrupoDetalle) {
                                    if ($objGrupoDetalle->grupo_maestro_id != NULL) {
                                        $objLista = $this->grupo_maestro_m->get($objGrupoDetalle->grupo_maestro_id);
                                        if ($objLista->tipo_grupo_maestro_id == $this->config->item('videos:lista')) {
                                            array_push($objListaGrupoMaestro, $objLista);
                                        }
                                    }
                                }
                            }
                            if (count($objListaGrupoMaestro) > 0) {
                                $exite_item = false;
                                foreach ($objListaGrupoMaestro as $index => $objGrupoMaestroLista) {
                                    if ($this->_obtenerImagenPorMaestro($objGrupoMaestroLista->id, $this->config->item('imagen:small'), $objTipoSeccion->id, $objCanal->id) > 0) {
                                        $objBeanDetalleSecciones = new stdClass();
                                        $objBeanDetalleSecciones->id = NULL;
                                        $objBeanDetalleSecciones->secciones_id = $objBeanSeccionSaved->id;
                                        $objBeanDetalleSecciones->reglas_id = NULL;
                                        $objBeanDetalleSecciones->videos_id = NULL;
                                        $objBeanDetalleSecciones->grupo_maestros_id = $objGrupoMaestroLista->id;
                                        $objBeanDetalleSecciones->categorias_id = NULL;
                                        $objBeanDetalleSecciones->tags_id = NULL;
                                        $objBeanDetalleSecciones->imagenes_id = $this->_obtenerImagenPorMaestro($objGrupoMaestroLista->id, $this->config->item('imagen:small'), $objTipoSeccion->id, $objCanal->id); //
                                        $objBeanDetalleSecciones->peso = $index + 2;
                                        $objBeanDetalleSecciones->descripcion_item = NULL;
                                        //$objBeanDetalleSecciones->templates_id = '5';
                                        $objBeanDetalleSecciones->estado = 1;
                                        $objBeanDetalleSecciones->fecha_registro = date("Y-m-d H:i:s");
                                        $objBeanDetalleSecciones->usuario_registro = $user_id;
                                        $objBeanDetalleSecciones->estado_migracion = '0';
                                        $objBeanDetalleSecciones->fecha_migracion = '0000-00-00 00:00:00';
                                        $objBeanDetalleSecciones->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                                        $objBeanDetalleSeccionesSaved = $this->detalle_secciones_m->save($objBeanDetalleSecciones);
                                        $this->portada_m->update($objBeanPortadaSaved->id, array("estado" => "1"));
                                        $this->secciones_m->update($objBeanSeccionSaved->id, array("estado" => "1"));
                                        $exite_item = true;
                                    }
                                }
                                //registramos la coleccion como item de la seccion coleccion
                                if ($exite_item) {
                                    $objBeanDetalleSecciones = new stdClass();
                                    $objBeanDetalleSecciones->id = NULL;
                                    $objBeanDetalleSecciones->secciones_id = $objBeanSeccionSaved->id;
                                    $objBeanDetalleSecciones->reglas_id = NULL;
                                    $objBeanDetalleSecciones->videos_id = NULL;
                                    $objBeanDetalleSecciones->grupo_maestros_id = $objMaestroColeccion->id;
                                    $objBeanDetalleSecciones->categorias_id = NULL;
                                    $objBeanDetalleSecciones->tags_id = NULL;
                                    $objBeanDetalleSecciones->imagenes_id = $this->_obtenerImagenPorMaestro($objMaestroColeccion->id, $this->config->item('imagen:large'), $objTipoSeccion->id, $objCanal->id); //
                                    $objBeanDetalleSecciones->peso = 0;
                                    $objBeanDetalleSecciones->descripcion_item = NULL;
                                    //$objBeanDetalleSecciones->templates_id = '5';
                                    $objBeanDetalleSecciones->estado = 1;
                                    $objBeanDetalleSecciones->fecha_registro = date("Y-m-d H:i:s");
                                    $objBeanDetalleSecciones->usuario_registro = $user_id;
                                    $objBeanDetalleSecciones->estado_migracion = '0';
                                    $objBeanDetalleSecciones->fecha_migracion = '0000-00-00 00:00:00';
                                    $objBeanDetalleSecciones->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                                    $objBeanDetalleSeccionesSaved = $this->detalle_secciones_m->save($objBeanDetalleSecciones);
                                }
                            }
                            $cont_coleccion++;
                        }
                    } else {//registrar una seccion vacia
                        $objBeanSeccion = new stdClass();
                        $objBeanSeccion->id = NULL;
                        $objBeanSeccion->nombre = ucwords($objTipoSeccion->nombre); // Destacado + nombre del canal
                        $objBeanSeccion->templates_id = '4';
                        $objBeanSeccion->descripcion = '';
                        $objBeanSeccion->tipo = '0';
                        $objBeanSeccion->portadas_id = $objBeanPortadaSaved->id;
                        $objBeanSeccion->tipo_secciones_id = $objTipoSeccion->id;
                        $objBeanSeccion->peso = $pos;
                        $objBeanSeccion->id_mongo = '0';
                        $objBeanSeccion->estado = '0';
                        $objBeanSeccion->fecha_registro = date("Y-m-d H:i:s");
                        $objBeanSeccion->usuario_registro = $user_id;
                        $objBeanSeccion->fecha_actualizacion = date("Y-m-d H:i:s");
                        $objBeanSeccion->usuario_actualizacion = $user_id;
                        $objBeanSeccion->estado_migracion = '0';
                        $objBeanSeccion->fecha_migracion = '0000-00-00 00:00:00';
                        $objBeanSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                        $objBeanSeccion->grupo_maestros_id = NULL;
                        $objBeanSeccionSaved = $this->secciones_m->save($objBeanSeccion);
                    }
                }
            }//FIN del filtro de secciones personalizados
            $pos++;
        }// FIN de la iteracion por SECCIONES
    }

    public function _getTypeImage($img_path) {
        $returnValue = 1;
        $imageSize = getimagesize($img_path);
        $width = $imageSize[0];
        $height = $imageSize[1];
        $arraytipoImagen = $this->tipo_imagen_m->get_many_by(array("alto" => $height, "ancho" => $width));
        foreach ($arraytipoImagen as $index => $tipoImagen) {
            if ($tipoImagen->id != '6') {
                $returnValue = $tipoImagen->id;
            }
        }
        return $returnValue;
    }

    public function updateVideo($canal_id, $video_id) {
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
                //$objBeanVideo->fuente = $this->input->post('fuente');
                $objBeanVideo->titulo = $this->input->post('titulo');
                $objBeanVideo->alias = url_title(strtolower(convert_accented_characters($this->input->post('titulo')))) . '-' . $video_id;
                $objBeanVideo->descripcion = $this->input->post('descripcion_updated');
                $objBeanVideo->fragmento = $this->input->post('fragmento');
                $objBeanVideo->fecha_publicacion_inicio = date("Y-m-d H:i:s", strtotime($this->input->post('fec_pub_ini')));
                $objBeanVideo->fecha_publicacion_fin = date("Y-m-d H:i:s", strtotime($this->input->post('fec_pub_fin')));
                $objBeanVideo->fecha_transmision = date("Y-m-d H:i:s", strtotime($this->input->post('fec_trans')));
                $objBeanVideo->horario_transmision_inicio = $this->input->post('hora_trans_ini');
                $objBeanVideo->horario_transmision_fin = $this->input->post('hora_trans_fin');
                $objBeanVideo->ubicacion = $this->input->post('ubicacion');
                $objBeanVideo->fecha_actualizacion = date("Y-m-d H:i:s");
                $objBeanVideo->usuario_actualizacion = $user_id;
                $this->videos_m->update_video($objBeanVideo);

                $this->_saveTagsTematicaPersonajes($objBeanVideo, $this->input->post());
                //obtenemos el ID del maestro detalle del video
                $objMaestroDetalle = $this->grupo_detalle_m->get_by(array("video_id" => $objBeanVideo->id));
                $maestro_detalle_id = NULL;
                if (count($objMaestroDetalle) > 0) {
                    //foreach ($objMaestroDetalle as $index => $objDetalle) {
                    $maestro_detalle_id = $objMaestroDetalle->id;
                    //}
                }
                //guardamos en la tabla grupo detalle
                $this->_saveVideoMaestroDetalle($objBeanVideo, $this->input->post(), $maestro_detalle_id);
                echo json_encode(array("value" => '0'));
            }
        }
    }

    public function postDatos($url, $post) {



        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
        $result = curl_exec($ch);
        curl_close($ch);

        //echo "resultadoi:". $result;
        return $result;
    }

    public function insertCorteVideo($canal_id, $video_id) {
//        print_r($this->input->post());
        if (true) {
            if (true) {
                /* if ($this->input->is_ajax_request()) {
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
                  //$objBeanVideo->fuente = $this->input->post('fuente');
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

                  // print_r($objBeanVideo);

                  $objvideotemp = $this->videos_m->save_video($objBeanVideo);
                  //                print_r($objvideotemp);

                  $this->_saveTagsTematicaPersonajes($objBeanVideo, $this->input->post());
                  //obtenemos el ID del maestro detalle del video
                  $objMaestroDetalle = $this->grupo_detalle_m->get_by(array("video_id" => $objBeanVideo->id));
                  $maestro_detalle_id = NULL;
                  if (count($objMaestroDetalle) > 0) {
                  //foreach ($objMaestroDetalle as $index => $objDetalle) {
                  $maestro_detalle_id = $objMaestroDetalle->id;
                  //}
                  }
                  //guardamos en la tabla grupo detalle
                  $this->_saveVideoMaestroDetalle($objBeanVideo, $this->input->post(), $maestro_detalle_id);
                 */

                $id_hijo = '1'; //$objvideotemp->id;
                $inicio = $this->input->post('ini_corte');
                $duracion = $this->input->post('dur_corte');

                $CI = & get_instance();
                echo 'ci: ' . $CI;
                exit;
                Proceso::corte_Video($video_id, $id_hijo, $inicio, $duracion);


                /* $urlpost = base_url("/procesos/cortevideo.php");
                  //$urlpost = "http://localhost/adminmicanal/procesos/cortevideo.php";

                  $post = array(
                  "id_padre" => $video_id,
                  "id_hijo" => $objvideotemp->id,
                  "inicio" => $this->input->post('ini_corte'),
                  "duracion" => $this->input->post('dur_corte')
                  );


                  //$this->postDatos($urlpost, $post); */

                echo json_encode(array("value" => '0'));
            }
        }
    }

    /**
     * 
     * @param type $fid id  de la imagen local de la BD
     * @param type $file nombre de la imagen [ruta absuluta /var/...]
     * @param type $mensaje 
     * @return string  name, direccion real de la imagen dominio
     */
    public function elemento_upload($fid, $file, $mensaje = 'cms.micanal.pe') {
        $url = "http://dev.e3.pe/index.php/api/v1";
        $remotedir = $this->elemento_basepath($fid, $this->config->item('server:elemento'));
        $ext = explode('.', $file);
        $infofile = urlencode(file_get_contents($file)); //encode_content_file($file);
        $data = array(
            'apikey' => '590ee43e919b1f4baa2125a424f03cd160ff8901',
            'name' => $fid . '.' . $ext[1],
            'content' => $infofile,
            //'ruta' => 'files/' . $remotedir,
            'ruta' => $remotedir,
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, $mensaje);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        curl_close($ch);
        return $data['ruta'] . $data['name'];
    }

    public function elemento_basepath($fid, $container = 'dev.e.micanal.e3.pe') {
//    $container = md_elemento_container($ext);
        $filename = str_pad($fid, 8, "0", STR_PAD_LEFT);
        $dir_split_file = preg_split('//', substr($filename, 0, strlen($filename) - 3), -1, PREG_SPLIT_NO_EMPTY);
        $scheme_dir = implode('/', $dir_split_file);
        return $container . '/' . $scheme_dir . '/';
    }

    public function active_imagen($canal_id, $video_id, $imagen_id) {
        $this->imagen_m->desactivarImagenes($imagen_id, $video_id);
        if ($this->imagen_m->tieneHijos($imagen_id)) {
            $coleccionHijos = $this->imagen_m->getImagen(array("imagen_padre" => $imagen_id));
            foreach ($coleccionHijos as $index => $objImg) {
                $objBeanImagen = new stdClass();
                $objBeanImagen->id = $objImg->id;
                $objBeanImagen->estado = $this->config->item('imagen:publicado');
                $this->imagen_m->activarImagen($objBeanImagen);
            }
        }
        $objBeanImagen = new stdClass();
        $objBeanImagen->id = $imagen_id;
        $objBeanImagen->estado = $this->config->item('imagen:publicado');
        $this->imagen_m->activarImagen($objBeanImagen);

        echo json_encode(array("respuesta" => "1"));
    }

    public function verificarVideo($canal_id, $video_id, $post = NULL) {
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
                $returnValue = true;
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

    public function maestro($canal_id) {
        $base_where = array("canales_id" => $canal_id, "tipo_grupo_maestro_id" => $this->config->item('videos:programa'));
        error_log(print_r($this->input->post(), true));
        $keyword = '';
        if ($this->input->post('f_keywords'))
            $keyword = $this->input->post('f_keywords');
        // Create pagination links
        if (strlen(trim($keyword)) > 0) {
            $total_rows = $this->grupo_maestro_m->like('nombre', $keyword)->count_by($base_where);
        } else {
            $total_rows = $this->grupo_maestro_m->count_by($base_where);
        }
        //$total_rows = $this->grupo_maestro_m->count_by($base_where);
        $pagination = create_pagination('admin/videos/maestro/' . $canal_id . '/index', $total_rows, 5, 6, TRUE, 'paginationSinAjax');
        // Using this data, get the relevant results
        if (strlen(trim($keyword)) > 0) {
            $lista_programas = $this->listaProgramacompleto($this->grupo_maestro_m->order_by('fecha_registro', 'DESC')->like('nombre', $keyword)->limit($pagination['limit'])->get_many_by($base_where));
        } else {
            $lista_programas = $this->listaProgramacompleto($this->grupo_maestro_m->order_by('fecha_registro', 'DESC')->limit($pagination['limit'])->get_many_by($base_where));
        }
        //$lista_programas = $this->listaProgramacompleto($this->grupo_maestro_m->order_by('fecha_registro', 'DESC')->limit($pagination['limit'])->get_many_by($base_where));
        $this->input->is_ajax_request() and $this->template->set_layout(FALSE);
        $this->template
                ->title($this->module_details['name'])
                ->append_js('admin/filter.js')
                ->set_partial('filters', 'admin/partials/filters')
                ->append_js('module::jquery.alerts.js')
                ->append_css('module::jquery.alerts.css')
                ->set('pagination', $pagination)
                ->set_partial('maestros', 'admin/tables/maestros')
                ->set('canal_id', $canal_id)
                ->set('lista_programas', $lista_programas);
        $this->input->is_ajax_request() ? $this->template->build('admin/tables/maestros') : $this->template->build('admin/maestro');
    }

    public function listaProgramacompleto($arrayObject) {
        $returnValue = array();
        if (count($arrayObject) > 0) {
            foreach ($arrayObject as $puntero => $objPrograma) {
                $objImagen = $this->imagen_m->get_by(array("grupo_maestros_id" => $objPrograma->id, "estado" => "1", "tipo_imagen_id" => $this->config->item('imagen:small')));
                if (count($objImagen) > 0) {
                    if ($objImagen->procedencia == $this->config->item('procedencia:liquid')) {
                        $imagen = $objImagen->imagen;
                    } else {
                        $imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagen->imagen;
                    }
                } else {
                    $imagen = UPLOAD_IMAGENES_VIDEOS . 'no_video.jpg';
                }
                $objCategoria = $this->categoria_m->get($objPrograma->categorias_id);
                if (count($objCategoria) == 0) {
                    $objCategoria = new stdClass();
                    $objCategoria->nombre = 'Sin categoría';
                }
                $objPrograma->estado_id = $objPrograma->estado;
                $estado = 'Borrador';
                if ($objPrograma->estado == '1') {
                    $estado = 'Publicado';
                } else {
                    if ($objPrograma->estado == '2') {
                        $estado = 'Eliminado';
                    }
                }
                $objPrograma->imagen = $imagen;
                $objPrograma->tipo = 'Programa';
                $objPrograma->coleccion = $this->obtener_coleccion_by_programa($objPrograma->id);
                $objPrograma->cantidad = count($objPrograma->coleccion);
                $objPrograma->categoria = $objCategoria->nombre;
                $objPrograma->estado = $estado;
                $arrayObject[$puntero] = $objPrograma;
            }
            $returnValue = $arrayObject;
        }
        return $returnValue;
    }

    public function obtener_coleccion_by_programa($programa_id) {
        $returnValue = array();
        $grupos_detalles = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $programa_id));
        if (count($grupos_detalles) > 0) {
            foreach ($grupos_detalles as $puntero => $objDetalleGrupo) {
                $objMaestro = $this->grupo_maestro_m->get($objDetalleGrupo->grupo_maestro_id);
                if (count($objMaestro) > 0) {
                    if ($objMaestro->tipo_grupo_maestro_id == $this->config->item('videos:coleccion')) {
                        $objMaestro->lista = $this->obtener_lista_by_coleccion($objMaestro->id);
                        $objImagen = $this->imagen_m->get_by(array("grupo_maestros_id" => $objMaestro->id, "estado" => "1", "tipo_imagen_id" => $this->config->item('imagen:small')));
                        if (count($objImagen) > 0) {
                            if ($objImagen->procedencia == $this->config->item('procedencia:liquid')) {
                                $imagen = $objImagen->imagen;
                            } else {
                                $imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagen->imagen;
                            }
                        } else {
                            $imagen = UPLOAD_IMAGENES_VIDEOS . 'no_video.jpg';
                        }
                        $objCategoria = $this->categoria_m->get($objMaestro->categorias_id);
                        if (count($objCategoria) > 0) {
                            $categoria = $objCategoria->nombre;
                        } else {
                            $categoria = 'Sin categoría';
                        }
                        $objMaestro->estado_id = $objMaestro->estado;
                        $estado = 'Borrador';
                        if ($objMaestro->estado == '1') {
                            $estado = 'Publicado';
                        } else {
                            if ($objMaestro->estado == '2') {
                                $estado = 'Eliminado';
                            }
                        }
                        $objMaestro->imagen = $imagen;
                        $objMaestro->tipo = 'Colección';
                        $objMaestro->cantidad = count($objMaestro->lista);
                        $objMaestro->categoria = $categoria;
                        $objMaestro->estado = $estado;
                        array_push($returnValue, $objMaestro);
                    }
                }
            }
        }
        return $returnValue;
    }

    public function obtener_lista_by_coleccion($coleccion_id) {
        $returnValue = array();
        $listaColeccion = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $coleccion_id, "tipo_grupo_maestros_id" => $this->config->item('videos:coleccion')));
        if (count($listaColeccion) > 0) {
            foreach ($listaColeccion as $index => $objDetalle) {
                $objMaestro = $this->grupo_maestro_m->get($objDetalle->grupo_maestro_id);
                if (count($objMaestro) > 0) {
                    if ($objMaestro->tipo_grupo_maestro_id == $this->config->item('videos:lista')) {
                        $objMaestro->videos = $this->obtener_video_by_lista($objMaestro->id);
                        $objImagen = $this->imagen_m->get_by(array("grupo_maestros_id" => $objMaestro->id, "estado" => "1", "tipo_imagen_id" => $this->config->item('imagen:small')));
                        if (count($objImagen) > 0) {
                            if ($objImagen->procedencia == $this->config->item('procedencia:liquid')) {
                                $imagen = $objImagen->imagen;
                            } else {
                                $imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagen->imagen;
                            }
                        } else {
                            $imagen = UPLOAD_IMAGENES_VIDEOS . 'no_video.jpg';
                        }
                        $objCategoria = $this->categoria_m->get($objMaestro->categorias_id);
                        if (count($objCategoria) > 0) {
                            $categoria = $objCategoria->nombre;
                        } else {
                            $categoria = 'Sin categoría';
                        }
                        $objMaestro->estado_id = $objMaestro->estado;
                        $estado = 'Borrador';
                        if ($objMaestro->estado == '1') {
                            $estado = 'Publicado';
                        } else {
                            if ($objMaestro->estado == '2') {
                                $estado = 'Eliminado';
                            }
                        }
                        $objMaestro->imagen = $imagen;
                        $objMaestro->tipo = 'Lista';
                        $objMaestro->cantidad = count($objMaestro->videos);
                        $objMaestro->categoria = $categoria;
                        $objMaestro->estado = $estado;
                        array_push($returnValue, $objMaestro);
                    }
                }
            }
        }
        return $returnValue;
    }

    public function obtener_video_by_lista($lista_id) {
        $returnValue = array();
        $listaVideo = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $lista_id, "tipo_grupo_maestros_id" => $this->config->item('videos:lista')));
        if (count($listaVideo) > 0) {
            foreach ($listaVideo as $index => $objDetalle) {
                $objVideo = $this->videos_m->get($objDetalle->video_id);
                if (count($objVideo) > 0) {
                    $objImagen = $this->imagen_m->get_by(array("videos_id" => $objVideo->id, "estado" => "1", "tipo_imagen_id" => $this->config->item('imagen:small')));
                    if (count($objImagen) > 0) {
                        if ($objImagen->procedencia == $this->config->item('procedencia:liquid')) {
                            $imagen = $objImagen->imagen;
                        } else {
                            $imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagen->imagen;
                        }
                    } else {
                        $imagen = UPLOAD_IMAGENES_VIDEOS . 'no_video.jpg';
                    }
                    $objCategoria = $this->categoria_m->get($objVideo->categorias_id);
                    if (count($objCategoria) > 0) {
                        $categoria = $objCategoria->nombre;
                    } else {
                        $categoria = 'Sin categoría';
                    }
                    $objVideo->estado_id = $objVideo->estado;
                    $estado = 'Borrador';
                    if ($objVideo->estado == '1') {
                        $estado = 'Publicado';
                    } else {
                        if ($objVideo->estado == '2') {
                            $estado = 'Eliminado';
                        }
                    }
                    $objVideo->imagen = $imagen;
                    $objVideo->tipo = 'Video';
                    $objVideo->cantidad = '-';
                    $objVideo->categoria = $categoria;
                    $objVideo->estado = $estado;
                    array_push($returnValue, $objVideo);
                }
            }
        }
        return $returnValue;
    }

    public function listarSinNivel($lista_programas) {
        $returnValue = array();
        if (count($lista_programas) > 0) {
            
        }
        return $returnValue;
    }

    public function grupo_maestro($canal_id = 0, $maestro_id = 0) {
        $objCanal = $this->canales_m->get($canal_id);
        if ($maestro_id > 0) {
            $objMaestro = $this->grupo_maestro_m->get($maestro_id);
            $objMaestro->tematicas = $this->obtenerTagsMaestro($maestro_id, $this->config->item('tag:tematicas'));
            $objMaestro->personajes = $this->obtenerTagsMaestro($maestro_id, $this->config->item('tag:personajes'));
            $objMaestro->tiene_imagen = $this->_tieneAvatarMaestro($maestro_id);
            if ($objMaestro->tiene_imagen) {
                $objMaestro->avatar = $this->listarImagenesMaestro($maestro_id);
            } else {
                $objMaestro->avatar = array();
            }
            $tipo_maestros = $this->tipo_maestro_m->getTipoDropDown(array("id" => $objMaestro->tipo_grupo_maestro_id), 'id');
        } else {
            $objMaestro = new stdClass();
            $objMaestro->id = NULL;
            $objMaestro->nombre = '';
            $objMaestro->descripcion = '';
            $objMaestro->alias = '';
            $objMaestro->tipo_grupo_maestro_id = 0;
            $objMaestro->canales_id = $canal_id;
            $objMaestro->categorias_id = 0;
            $objMaestro->cantidad_suscriptores = 0;
            $objMaestro->peso = 0;
            $objMaestro->id_mongo = NULL;
            $objMaestro->estado = 0;
            $objMaestro->tematicas = '';
            $objMaestro->personajes = '';
            $objMaestro->tiene_imagen = false;
            $objMaestro->avatar = array();
            $objMaestro->fecha_transmision_inicio = date("Y-m-d H:i:s");
            $objMaestro->fecha_transmision_fin = date("Y-m-d H:i:s");
            $tipo_maestros = $this->tipo_maestro_m->getTipoDropDown(array(), 'id');
        }
        //lista tipo de maestros
        $items = $this->itemsMaestros($maestro_id);
        //categorias
        $categorias = $this->categoria_m->getCategoryDropDown(array());
        //listamos las imagenes
        $lista_imagenes = $this->listaImagenes($maestro_id);

        $this->template
                ->title($this->module_details['name'])
                ->set('objCanal', $objCanal)
                ->append_metadata($this->load->view('fragments/wysiwyg', array(), TRUE))
                ->append_js('jquery/jquery.tagsinput.js')
                ->append_css('jquery/jquery.tagsinput.css')
                ->append_js('module::jquery.ddslick.min.js')
                ->append_js('AjaxUpload.2.0.min.js')
                ->append_js('module::jquery.alerts.js')
                ->append_css('module::jquery.alerts.css')
                ->append_js('module::smartpaginator.js')
                ->append_css('module::fineuploader-3.4.1.css')
                ->append_js('module::jquery.fineuploader-3.4.1.min.js')
                ->append_css('module::smartpaginator.css')
                ->set('tipo_maestros', $tipo_maestros)
                ->set('items', $items)
                ->set_partial('contenidos', 'admin/tables/contenidos')
                ->set_partial('imagenes', 'admin/tables/imagenes')
                ->set('imagenes', $lista_imagenes)
                ->set('categorias', $categorias)
                ->set('objMaestro', $objMaestro);
        $this->input->is_ajax_request() ? $this->template->build('admin/tables/contenidos') : $this->template->build('admin/grupo_maestro');
    }

    public function subir_imagen_grupo($maestro_id, $tipo_imagen_id, $imagen_id) {
        if ($this->input->is_ajax_request()) {
            $user_id = (int) $this->session->userdata('user_id');
            $url_imagen = '';
            $respuesta = 0;
            $cod_imagen_nueva = $imagen_id;
            $pasaImgSize = FALSE;
            // Obtenemos los datos del archivo
            $tamanio = $_FILES['qqfile']['size'];
            $tipo = $_FILES['qqfile']['type'];
            $archivo = $_FILES['qqfile']['name'];
            $fileType = array('image/jpeg', 'image/pjpeg', 'image/png');
            $directorio = '';
            // Tamaño de la imagen
            $imageSize = getimagesize($_FILES['qqfile']['tmp_name']);
            // Verificamos la extensión del archivo independiente del tipo mime
            $extension = explode('.', $_FILES['qqfile']['name']);
            $num = count($extension) - 1;
            // Creamos el nombre del archivo dependiendo la opción
            $imgFile = time() . '.' . $extension[$num];
            // Verificamos el tamaño válido para las fotos
            $objTipoImagen = $this->tipo_imagen_m->get($tipo_imagen_id);
            if ($imageSize[0] >= $objTipoImagen->ancho && $imageSize[1] >= $objTipoImagen->alto && ($extension[$num] == 'jpg' || $extension[$num] == 'png')) {
                $pasaImgSize = TRUE;
            }
            // Verificamos el status de las dimensiones de la imagen a publicar mediante nuestro jQuery para fotos
            if ($pasaImgSize) {
                // Verificamos Tamaño y extensiones
                if (in_array($tipo, $fileType) && $tamanio > 0 && $tamanio <= $this->config->item('imagen:maxSize')) {
                    // Intentamos copiar el archivo
                    if (is_uploaded_file($_FILES['qqfile']['tmp_name'])) {
                        umask(0);
                        // Verificamos si se pudo copiar el archivo a nustra carpeta
                        if (move_uploaded_file($_FILES['qqfile']['tmp_name'], UPLOAD_IMAGENES_VIDEOS . $directorio . $imgFile)) {
                            //usamos el crop de imagemagic para crear las 4 imagenes
                            $this->imagenes_lib->loadImage(UPLOAD_IMAGENES_VIDEOS . $directorio . $imgFile);
                            $this->imagenes_lib->crop($objTipoImagen->ancho, $objTipoImagen->alto, 'center');
                            $this->imagenes_lib->save(UPLOAD_IMAGENES_VIDEOS . $directorio . preg_replace("/\\.[^.\\s]{3,4}$/", "", $imgFile) . '_' . $objTipoImagen->ancho . 'x' . $objTipoImagen->alto . '.' . $extension[$num]);
                            $respuesta = 1;
                            $mensajeFile = 'done';
                            //eliminamos la imagen inicial
                            if (file_exists(UPLOAD_IMAGENES_VIDEOS . $directorio . $imgFile)) {
                                unlink(UPLOAD_IMAGENES_VIDEOS . $directorio . $imgFile);
                            }
                            //obtenemos el nombre de la imagen a enviar a Elemento
                            $imagen_cortada = preg_replace("/\\.[^.\\s]{3,4}$/", "", $imgFile) . '_' . $objTipoImagen->ancho . 'x' . $objTipoImagen->alto . '.' . $extension[$num];
                            if (file_exists(UPLOAD_IMAGENES_VIDEOS . $imagen_cortada)) {
                                if ($imagen_id > 0) {//cambiar imagen
                                    //enviamos a borrador a la imagen a cambiar
                                    $this->imagen_m->update($imagen_id, array("estado" => $this->config->item('estado:borrador'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                                    $this->imagen_m->desabilitarImagenes($maestro_id, $tipo_imagen_id);
                                    //registramos una nueva imagen
                                    $objBeanImagen = new stdClass();
                                    $objBeanImagen->id = NULL;
                                    $objBeanImagen->canales_id = NULL;
                                    $objBeanImagen->grupo_maestros_id = $maestro_id;
                                    $objBeanImagen->videos_id = NULL;
                                    $objBeanImagen->imagen = $imagen_cortada;
                                    $objBeanImagen->tipo_imagen_id = $tipo_imagen_id;
                                    $objBeanImagen->estado = $this->config->item('estado:publicado');
                                    $objBeanImagen->fecha_registro = date("Y-m-d H:i:d");
                                    $objBeanImagen->usuario_registro = $user_id;
                                    $objBeanImagen->fecha_actualizacion = date("Y-m-d H:i:d");
                                    $objBeanImagen->usuario_actualizacion = $user_id;
                                    $objBeanImagen->estado_migracion = $this->config->item('migracion:nuevo');
                                    $objBeanImagen->fecha_migracion = '0000-00-00 00:00:00';
                                    $objBeanImagen->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                                    $objBeanImagen->imagen_padre = 0;
                                    $objBeanImagen->procedencia = $this->config->item('procedencia:elemento');
                                    $objBeanImagen->imagen_anterior = NULL;
                                    $objBeanImagenSaved = $this->imagen_m->saveImage($objBeanImagen);
                                    $cod_imagen_nueva = $objBeanImagen->id;
                                    $url_imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objBeanImagenSaved->imagen;
                                    //enviamos al servidor elemento
                                    $ruta_absoluta_imagen = FCPATH . 'uploads/imagenes/' . $imagen_cortada;
                                    $path_image_element = $this->elemento_upload($objBeanImagenSaved->id, $ruta_absoluta_imagen);
                                    $array_path = explode("/", $path_image_element);
                                    if ($array_path[0] == $this->config->item('server:elemento')) {
                                        unset($array_path[0]);
                                    }
                                    $path_single_element = implode('/', $array_path);
                                    $this->imagen_m->update($objBeanImagenSaved->id, array("imagen" => $path_single_element, "estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                                    $url_imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $path_single_element;
                                    //eliminamos la imagen local
                                    if (file_exists($ruta_absoluta_imagen)) {
                                        unlink($ruta_absoluta_imagen);
                                    }
                                } else {//subir una nueva imagen.
                                    $this->imagen_m->desabilitarImagenes($maestro_id, $tipo_imagen_id);
                                    $objBeanImagen = new stdClass();
                                    $objBeanImagen->id = NULL;
                                    $objBeanImagen->canales_id = NULL;
                                    $objBeanImagen->grupo_maestros_id = $maestro_id;
                                    $objBeanImagen->videos_id = NULL;
                                    $objBeanImagen->imagen = $imagen_cortada;
                                    $objBeanImagen->tipo_imagen_id = $tipo_imagen_id;
                                    $objBeanImagen->estado = $this->config->item('estado:publicado');
                                    $objBeanImagen->fecha_registro = date("Y-m-d H:i:d");
                                    $objBeanImagen->usuario_registro = $user_id;
                                    $objBeanImagen->fecha_actualizacion = date("Y-m-d H:i:d");
                                    $objBeanImagen->usuario_actualizacion = $user_id;
                                    $objBeanImagen->estado_migracion = $this->config->item('migracion:nuevo');
                                    $objBeanImagen->fecha_migracion = '0000-00-00 00:00:00';
                                    $objBeanImagen->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                                    $objBeanImagen->imagen_padre = 0;
                                    $objBeanImagen->procedencia = $this->config->item('procedencia:elemento');
                                    $objBeanImagen->imagen_anterior = NULL;
                                    $objBeanImagenSaved = $this->imagen_m->saveImage($objBeanImagen);
                                    $cod_imagen_nueva = $objBeanImagen->id;
                                    //enviamos al servidor elemento
                                    $ruta_absoluta_imagen = FCPATH . 'uploads/imagenes/' . $imagen_cortada;
                                    $path_image_element = $this->elemento_upload($objBeanImagenSaved->id, $ruta_absoluta_imagen);
                                    $array_path = explode("/", $path_image_element);
                                    if ($array_path[0] == $this->config->item('server:elemento')) {
                                        unset($array_path[0]);
                                    }
                                    $path_single_element = implode('/', $array_path);
                                    $this->imagen_m->update($objBeanImagenSaved->id, array("imagen" => $path_single_element, "estado" => $this->config->item('estado:publicado')));
                                    $url_imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $path_single_element;
                                    //eliminamos la imagen local
                                    if (file_exists($ruta_absoluta_imagen)) {
                                        unlink($ruta_absoluta_imagen);
                                    }
                                }
                            }
                        } else {
                            // error del lado del servidor
                            $mensajeFile = 'No se pudo subir el archivo';
                        }
                    } else {
                        // error del lado del servidor
                        $mensajeFile = 'No se pudo subir el archivo';
                    }
                } else {
                    // Error en el tamaño y tipo de imagen
                    $mensajeFile = 'Verifique el tamanio y tipo de imagen';
                }
            } else {
                // Error en las dimensiones de la imagen
                $mensajeFile = 'Verifique las dimensiones de la Imagen';
            }
            $salidaJson = array("success" => $respuesta,
                "url" => $url_imagen,
                "error" => $mensajeFile,
                "imagen_id" => $cod_imagen_nueva,
                "fileName" => $archivo);
            echo json_encode($salidaJson);
        }
    }

    private function listaImagenes($maestro_id) {
        $returnValue = array();
        $tipo_imagenes = $this->tipo_imagen_m->listType();
        if (count($tipo_imagenes) > 0) {
            foreach ($tipo_imagenes as $puntero => $objTipoImagen) {
                $objImagen = $this->imagen_m->get_by(array("grupo_maestros_id" => $maestro_id, "estado" => $this->config->item('estado:publicado'), "tipo_imagen_id" => $objTipoImagen->id));
                if (count($objImagen) > 0) {
                    $objImagen->tipo_imagen = $objTipoImagen->nombre;
                    $objImagen->tamanio = $objTipoImagen->ancho . 'x' . $objTipoImagen->alto;
                    if ($objImagen->procedencia == $this->config->item('procedencia:liquid')) {
                        $objImagen->imagen = $objImagen->imagen;
                    } else {
                        $objImagen->imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagen->imagen;
                    }
                    $objImagen->existe = 'Si';
                    $objImagen->accion = '<div style="width:120px;" id="fine-uploader-basic_' . $objImagen->tipo_imagen_id . '_' . $objImagen->id . '" class="btn red"><i class="icon-upload icon-white"></i>' . lang('imagen:cambiar_imagen') . '</div>';
                    $objImagen->accion.= '<div class="btn blue" onclick="restaurar_imagen(' . $objTipoImagen->id . ',' . $maestro_id . ');return false;" style="width:120px;" id="restaurar_imagen_' . $objImagen->id . '">' . lang('imagen:restaurar_imagen') . '</button>';
                    $objImagen->progreso = '<div id="messages_' . $objImagen->tipo_imagen_id . '_' . $objImagen->id . '"></div>';
                    array_push($returnValue, $objImagen);
                } else {
                    $oImagen = new stdClass();
                    $oImagen->id = 0;
                    $oImagen->canales_id = NULL;
                    $oImagen->grupo_maestros_id = $maestro_id;
                    $oImagen->videos_id = NULL;
                    $oImagen->imagen = UPLOAD_IMAGENES_VIDEOS . 'no_video.jpg';
                    $oImagen->tipo_imagen_id = $objTipoImagen->id;
                    $oImagen->estado = $this->config->item('estado:publicado');
                    $oImagen->existe = 'No';
                    $oImagen->fecha_registro = '';
                    $oImagen->imagen_padre = 0;
                    $oImagen->procedencia = 0;
                    $oImagen->imagen_anterior = NULL;
                    $oImagen->tipo_imagen = $objTipoImagen->nombre;
                    $oImagen->tamanio = $objTipoImagen->ancho . 'x' . $objTipoImagen->alto;
                    $oImagen->accion = '<div style="width:120px;" id="fine-uploader-basic_' . $oImagen->tipo_imagen_id . '_' . $oImagen->id . '" class="btn red"><i class="icon-upload icon-white"></i>' . lang('imagen:subir_imagen') . '</div>';
                    //$oImagen->accion.= '<button style="width:130px;" id="cambiar_imagen_' . $oImagen->id . '">' . lang('imagen:subir_imagen') . '</button>';
                    $oImagen->accion.= '<div class="btn blue" onclick="restaurar_imagen(' . $objTipoImagen->id . ',' . $maestro_id . ');return false;" style="width:120px;" id="restaurar_imagen_' . $oImagen->id . '">' . lang('imagen:restaurar_imagen') . '</div>';
                    $oImagen->progreso = '<div id="messages_' . $oImagen->tipo_imagen_id . '_' . $oImagen->id . '"></div>';
                    array_push($returnValue, $oImagen);
                }
            }
        }
        /* $lista_imagenes = $this->imagen_m->get_many_by(array("grupo_maestros_id" => $maestro_id));
          if (count($lista_imagenes) > 0) {
          $array_id_imagen = array();
          foreach ($lista_imagenes as $puntero => $objImagen) {
          if ($objImagen->imagen_padre == NULL || $objImagen->imagen_padre == '0') {
          array_push($array_id_imagen, $objImagen->id);
          }
          }
          //obtenemos solo las imagenes padre
          $lista_imagenes_padre = $this->imagen_m->where_in('id', $array_id_imagen)->get_many_by(array());
          if (count($lista_imagenes_padre) > 0) {

          foreach ($lista_imagenes_padre as $index => $objImagenPadre) {
          $lista_tipo_imagenes = $this->tipo_imagen_m->listType();
          if ($objImagenPadre->procedencia == $this->config->item('procedencia:liquid')) {
          $objImagenPadre->imagen = $objImagenPadre->imagen;
          } else {
          $objImagenPadre->imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagenPadre->imagen;
          }
          switch ($objImagenPadre->tipo_imagen_id) {
          case $this->config->item('imagen:small'):
          $objImagenPadre->tipo_imagen = 'Small';
          $oTipoImagen = $this->tipo_imagen_m->get($objImagenPadre->tipo_imagen_id);
          $objImagenPadre->tamanio = $oTipoImagen->ancho . 'x' . $oTipoImagen->alto;
          break;
          case $this->config->item('imagen:medium'):
          $objImagenPadre->tipo_imagen = 'Medium';
          $oTipoImagen = $this->tipo_imagen_m->get($objImagenPadre->tipo_imagen_id);
          $objImagenPadre->tamanio = $oTipoImagen->ancho . 'x' . $oTipoImagen->alto;
          break;
          case $this->config->item('imagen:large'):
          $objImagenPadre->tipo_imagen = 'Large';
          $oTipoImagen = $this->tipo_imagen_m->get($objImagenPadre->tipo_imagen_id);
          $objImagenPadre->tamanio = $oTipoImagen->ancho . 'x' . $oTipoImagen->alto;
          break;
          case $this->config->item('imagen:extralarge'):
          $objImagenPadre->tipo_imagen = 'Extralarge';
          $oTipoImagen = $this->tipo_imagen_m->get($objImagenPadre->tipo_imagen_id);
          $objImagenPadre->tamanio = $oTipoImagen->ancho . 'x' . $oTipoImagen->alto;
          break;
          }
          $objImagenPadre->existe = 'Si';
          $objImagenPadre->accion = '<div style="width:120px;" id="fine-uploader-basic_' . $objImagenPadre->tipo_imagen_id . '_' . $objImagenPadre->id . '" class="btn red"><i class="icon-upload icon-white"></i>' . lang('imagen:cambiar_imagen') . '</div>';
          //$objImagenPadre->accion.= '<button style="width:130px;" id="cambiar_imagen_' . $objImagenPadre->id . '">' . lang('imagen:cambiar_imagen') . '</button>';
          $objImagenPadre->accion.= '<div class="btn blue" style="width:120px;" id="restaurar_imagen_' . $objImagenPadre->id . '">' . lang('imagen:restaurar_imagen') . '</button>';
          $objImagenPadre->progreso = '<div id="messages_' . $objImagenPadre->tipo_imagen_id . '_' . $objImagenPadre->id . '"></div>';
          array_push($returnValue, $objImagenPadre);
          //eliminamos el tipo del padre
          foreach ($lista_tipo_imagenes as $i => $objTipo) {
          if ($objImagenPadre->tipo_imagen_id == $objTipo->id) {
          unset($lista_tipo_imagenes[$i]);
          }
          }
          //obtenemos las imagenes restantes del padre
          foreach ($lista_tipo_imagenes as $i => $objTipoImagen) {
          $oImagen = $this->imagen_m->get_by(array("imagen_padre" => $objImagenPadre->id, "tipo_imagen_id" => $objTipoImagen->id));
          if (count($oImagen) > 0) {
          if ($oImagen->procedencia == $this->config->item('procedencia:liquid')) {
          $oImagen->imagen = $oImagen->imagen;
          } else {
          $oImagen->imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $oImagen->imagen;
          }
          switch ($oImagen->tipo_imagen_id) {
          case $this->config->item('imagen:small'):
          $oImagen->tipo_imagen = 'Small';
          $oTipoImagen = $this->tipo_imagen_m->get($oImagen->tipo_imagen_id);
          $oImagen->tamanio = $oTipoImagen->ancho . 'x' . $oTipoImagen->alto;
          break;
          case $this->config->item('imagen:medium'):
          $oImagen->tipo_imagen = 'Medium';
          $oTipoImagen = $this->tipo_imagen_m->get($oImagen->tipo_imagen_id);
          $oImagen->tamanio = $oTipoImagen->ancho . 'x' . $oTipoImagen->alto;
          break;
          case $this->config->item('imagen:large'):
          $oImagen->tipo_imagen = 'Large';
          $oTipoImagen = $this->tipo_imagen_m->get($oImagen->tipo_imagen_id);
          $oImagen->tamanio = $oTipoImagen->ancho . 'x' . $oTipoImagen->alto;
          break;
          case $this->config->item('imagen:extralarge'):
          $oImagen->tipo_imagen = 'Extralarge';
          $oTipoImagen = $this->tipo_imagen_m->get($oImagen->tipo_imagen_id);
          $oImagen->tamanio = $oTipoImagen->ancho . 'x' . $oTipoImagen->alto;
          break;
          }
          $oImagen->existe = 'Si';
          $oImagen->accion = '<div style="width:120px;" id="fine-uploader-basic_' . $oImagen->tipo_imagen_id . '_' . $oImagen->id . '" class="btn red"><i class="icon-upload icon-white"></i>' . lang('imagen:cambiar_imagen') . '</div>';
          //$oImagen->accion.= '<button style="width:130px;" id="cambiar_imagen_' . $oImagen->id . '">' . lang('imagen:cambiar_imagen') . '</button>';
          $oImagen->accion.= '<div class="btn blue" style="width:120px;" id="restaurar_imagen_' . $oImagen->id . '">' . lang('imagen:restaurar_imagen') . '</button>';
          $oImagen->progreso = '<div id="messages_' . $oImagen->tipo_imagen_id . '_' . $oImagen->id . '"></div>';
          array_push($returnValue, $oImagen);
          } else {
          $oImagen = new stdClass();
          $oImagen->id = 0;
          $oImagen->canales_id = $objImagenPadre->canales_id;
          $oImagen->grupo_maestros_id = $objImagenPadre->grupo_maestros_id;
          $oImagen->videos_id = $objImagenPadre->videos_id;
          $oImagen->imagen = UPLOAD_IMAGENES_VIDEOS . 'no_video.jpg';
          $oImagen->tipo_imagen_id = $objTipoImagen->id;
          $oImagen->estado = $objImagenPadre->estado;
          $oImagen->existe = 'No';
          $oImagen->fecha_registro = $objImagenPadre->fecha_registro;
          $oImagen->usuario_registro = $objImagenPadre->usuario_registro;
          $oImagen->fecha_actualizacion = $objImagenPadre->fecha_actualizacion;
          $oImagen->usuario_actualizacion = $objImagenPadre->usuario_actualizacion;
          $oImagen->estado_migracion = $objImagenPadre->estado_migracion;
          $oImagen->fecha_migracion = $objImagenPadre->fecha_migracion;
          $oImagen->fecha_migracion_actualizacion = $objImagenPadre->fecha_migracion_actualizacion;
          $oImagen->imagen_padre = $objImagenPadre->imagen_padre;
          $oImagen->procedencia = $objImagenPadre->procedencia;
          $oImagen->imagen_anterior = $objImagenPadre->imagen_anterior;
          $oImagen->tipo_imagen = $objTipoImagen->nombre;
          $oImagen->tamanio = $objTipoImagen->ancho . 'x' . $objTipoImagen->alto;
          $oImagen->accion = '<div style="width:120px;" id="fine-uploader-basic_' . $oImagen->tipo_imagen_id . '_' . $oImagen->id . '" class="btn red"><i class="icon-upload icon-white"></i>' . lang('imagen:subir_imagen') . '</div>';
          //$oImagen->accion.= '<button style="width:130px;" id="cambiar_imagen_' . $oImagen->id . '">' . lang('imagen:subir_imagen') . '</button>';
          $oImagen->accion.= '<div class="btn blue" style="width:120px;" id="restaurar_imagen_' . $oImagen->id . '">' . lang('imagen:restaurar_imagen') . '</div>';
          $oImagen->progreso = '<div id="messages_' . $oImagen->tipo_imagen_id . '_' . $oImagen->id . '"></div>';
          array_push($returnValue, $oImagen);
          }
          }
          //listamos los tipos de imagenes para maestros
          }
          }
          } */
        return $returnValue;
    }

    public function active_imagen_maestro($maestro_id, $imagen_id) {
        if ($this->input->is_ajax_request()) {
            $this->imagen_m->desactivarImagenesMaestro($maestro_id);
            if ($this->imagen_m->tieneHijos($imagen_id)) {
                $coleccionHijos = $this->imagen_m->getImagen(array("imagen_padre" => $imagen_id));
                foreach ($coleccionHijos as $index => $objImg) {
                    $objBeanImagen = new stdClass();
                    $objBeanImagen->id = $objImg->id;
                    $objBeanImagen->estado = $this->config->item('imagen:publicado');
                    $this->imagen_m->activarImagen($objBeanImagen);
                }
            }
            $objBeanImagen = new stdClass();
            $objBeanImagen->id = $imagen_id;
            $objBeanImagen->estado = $this->config->item('imagen:publicado');
            $this->imagen_m->activarImagen($objBeanImagen);

            echo json_encode(array("respuesta" => "1"));
        }
    }

    public function itemsMaestros($maestro_id) {
        $returValue = array();
        if ($maestro_id > 0) {

            $lista_detalle = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $maestro_id, "estado" => "1"));
            if (count($lista_detalle) > 0) {
                $arrayId = array();
                $arrayIdVideo = array();
                foreach ($lista_detalle as $puntero => $objDetalle) {
                    if ($objDetalle->grupo_maestro_id != NULL) {
                        //array_push($arrayId, $objDetalle->grupo_maestro_id);
                        $arrayId[$objDetalle->id] = $objDetalle->grupo_maestro_id;
                    } else {
                        //array_push($arrayIdVideo, $objDetalle->video_id);
                        $arrayIdVideo[$objDetalle->id] = $objDetalle->video_id;
                    }
                }
                //verificamos el arrayId
                if (count($arrayId) > 0) {
                    $arrayId = array_unique($arrayId);
                    foreach ($arrayId as $index_maestro => $id_maestro) {
                        $objMaestro = $this->grupo_maestro_m->get($id_maestro);
                        $objContenido = new stdClass();
                        $objContenido->id = $objMaestro->id;
                        $objContenido->imagen = $this->obtenerImagenMaestro($objMaestro->id, $this->config->item('imagen:small'));
                        $objContenido->nombre = $objMaestro->nombre;
                        $objTipoMaestro = $this->tipo_maestro_m->get($objMaestro->tipo_grupo_maestro_id);
                        $objContenido->tipo = $objTipoMaestro->nombre;
                        $objContenido->fecha_registro = $objMaestro->fecha_registro;
                        $objContenido->estado = $objMaestro->estado == '1' ? 'Publicado' : 'Borrador';
                        $objContenido->grupo_detalle_id = $index_maestro;
                        array_push($returValue, $objContenido);
                    }
                }
                //agregamos los videos
                if (count($arrayIdVideo) > 0) {
                    $arrayIdVideo = array_unique($arrayIdVideo);
                    foreach ($arrayIdVideo as $index_video => $video_id) {
                        $objVideo = $this->videos_m->get($video_id);
                        $objContenido = new stdClass();
                        $objContenido->id = $objVideo->id;
                        $objContenido->imagen = $this->obtenerImagenVideo($objVideo->id, $this->config->item('imagen:small'));
                        $objContenido->nombre = $objVideo->titulo;
                        $objContenido->tipo = 'Video';
                        $objContenido->fecha_registro = $objVideo->fecha_registro;
                        $objContenido->estado = $objVideo->estado == '1' ? 'Publicado' : 'Codificando';
                        $objContenido->grupo_detalle_id = $index_video;
                        array_push($returValue, $objContenido);
                    }
                }
            }
        }
        return $returValue;
    }

    public function obtenerImagenMaestro($maestro_id, $tipo = 1) {
        $imagen = UPLOAD_IMAGENES_VIDEOS . 'no_video.jpg';
        $objImagen = $this->imagen_m->get_by(array("grupo_maestros_id" => $maestro_id, "tipo_imagen_id" => $tipo, "estado" => "1"));
        if (count($objImagen) > 0) {
            if ($objImagen->procedencia == '0') {
                $imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagen->imagen;
            } else {
                $imagen = $objImagen->imagen;
            }
        }
        return $imagen;
    }

    public function obtenerImagenVideo($video_id, $tipo) {
        $imagen = UPLOAD_IMAGENES_VIDEOS . 'no_video.jpg';
        $objImagen = $this->imagen_m->get_by(array("videos_id" => $video_id, "tipo_imagen_id" => $tipo, "estado" => "1"));
        if (count($objImagen) > 0) {
            if ($objImagen->procedencia == '0') {
                $imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . $objImagen->imagen;
            } else {
                $imagen = $objImagen->imagen;
            }
        }
        return $imagen;
    }

    public function guardar_maestro() {
        if ($this->input->is_ajax_request()) {
            $returnValue = 0;
            $maestro_id = 0;
            $canal_id = $this->input->post('canal_id');
            $user_id = (int) $this->session->userdata('user_id');
            if ($this->input->post('maestro_id') > 0) {//editare un  maestro
                if ($this->existeNombreMaestro($this->input->post('titulo'), $this->input->post('canal_id'), $this->input->post('maestro_id'))) {
                    $returnValue = 1;
                } else {
                    $objBeanMaestro = new stdClass();
                    $objBeanMaestro->id = $this->input->post('maestro_id');
                    $objBeanMaestro->nombre = $this->input->post('titulo');
                    $objBeanMaestro->descripcion = $this->input->post('descripcion_updated');
                    $objBeanMaestro->alias = url_title(strtolower(convert_accented_characters($this->input->post('titulo'))));
                    $objBeanMaestro->tipo_grupo_maestro_id = $this->input->post('tipo');
                    $objBeanMaestro->canales_id = $this->input->post('canal_id');
                    $objBeanMaestro->fecha_actualizacion = date("Y-m-d H:i:s");
                    $objBeanMaestro->usuario_actualizacion = $user_id;
                    $objBeanMaestro->estado_migracion = $this->config->item('migracion:actualizado');
                    $objBeanMaestro->fecha_transmision_inicio = date("Y-m-d H:i:s", strtotime($this->input->post('fec_pub_ini')));
                    $objBeanMaestro->fecha_transmision_fin = date("Y-m-d H:i:s", strtotime($this->input->post('fec_pub_fin')));
                    $this->grupo_maestro_m->update($objBeanMaestro->id, array("nombre" => $objBeanMaestro->nombre,
                        "descripcion" => $objBeanMaestro->descripcion, "alias" => $objBeanMaestro->alias,
                        "tipo_grupo_maestro_id" => $objBeanMaestro->tipo_grupo_maestro_id, "canales_id" => $objBeanMaestro->canales_id,
                        "fecha_actualizacion" => $objBeanMaestro->fecha_actualizacion, "usuario_actualizacion" => $objBeanMaestro->usuario_actualizacion,
                        "estado_migracion" => $objBeanMaestro->estado_migracion, "fecha_transmision_inicio" => $objBeanMaestro->fecha_transmision_inicio, "fecha_transmision_fin" => $objBeanMaestro->fecha_transmision_fin));
                    $returnValue = 0;
                    $this->guardarTagsMaestro($objBeanMaestro, $this->input->post());
                }
            } else {//registrar un nuevo maestro
                if ($this->existeNombreMaestro($this->input->post('titulo'), $this->input->post('canal_id'), $this->input->post('maestro_id'))) {
                    $returnValue = 1;
                } else {
                    $objBeanMaestro = new stdClass();
                    $objBeanMaestro->id = $this->input->post('maestro_id');
                    $objBeanMaestro->nombre = $this->input->post('titulo');
                    $objBeanMaestro->descripcion = $this->input->post('descripcion_updated');
                    $objBeanMaestro->alias = url_title(strtolower(convert_accented_characters($this->input->post('titulo'))));
                    $objBeanMaestro->tipo_grupo_maestro_id = $this->input->post('tipo');
                    $objBeanMaestro->canales_id = $this->input->post('canal_id');
                    $objBeanMaestro->fecha_actualizacion = date("Y-m-d H:i:s");
                    $objBeanMaestro->usuario_actualizacion = $user_id;
                    $objBeanMaestro->categorias_id = $this->input->post('categoria');
                    $objBeanMaestro->cantidad_suscriptores = 0;
                    $objBeanMaestro->peso = $this->_obtenerPesoMaestro($this->input->post());
                    $objBeanMaestro->id_mongo = NULL;
                    $objBeanMaestro->estado = 0;
                    $objBeanMaestro->fecha_registro = date("Y-m-d H:i:s");
                    $objBeanMaestro->usuario_registro = $user_id;
                    $objBeanMaestro->estado_migracion = 0;
                    $objBeanMaestro->fecha_migracion = '0000-00-00 00:00:00';
                    $objBeanMaestro->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                    $objBeanMaestro->comentarios = 0;
                    $objBeanMaestro->fecha_transmision_inicio = date("Y-m-d H:i:s", strtotime($this->input->post('fec_pub_ini')));
                    $objBeanMaestro->fecha_transmision_fin = date("Y-m-d H:i:s", strtotime($this->input->post('fec_pub_fin')));
                    /* $this->vd($objBeanMaestro);
                      die(); */
                    $objBeanMaestroSaved = $this->grupo_maestro_m->save_maestro($objBeanMaestro);
                    //registramos el detalle maestro
                    $this->registrarDetalleMaestro($objBeanMaestroSaved, $this->input->post());
                    //movemos las imagenes y lo subimos a elemento
                    $direccion_imagen = FCPATH . 'uploads/temp/' . $this->input->post('imagen_maestro');

                    if (file_exists($direccion_imagen)) {
                        // Verificamos la extensión del archivo independiente del tipo mime
                        $extension = explode('.', $this->input->post('imagen_maestro'));
                        $num = count($extension) - 1;
                        $directorio = '';
                        // Creamos el nombre del archivo dependiendo la opción
                        $imgFile = $this->input->post('imagen_maestro');
                        // Tamaño de la imagen
                        $imageSize = getimagesize($direccion_imagen);

                        $arrayTipoImagen = $this->tipo_imagen_m->listType();
                        $width = $imageSize[0];
                        $height = $imageSize[1];
                        if (count($arrayTipoImagen) > 0) {
                            $arrayImagenes = array();
                            foreach ($arrayTipoImagen as $index => $objTipoImagen) {
                                if ($width >= $objTipoImagen->ancho && $height >= $objTipoImagen->alto) {
                                    $this->imagenes_lib->loadImage(UPLOAD_IMAGENES_VIDEOS . '../temp/' . $imgFile);
                                    $this->imagenes_lib->crop($objTipoImagen->ancho, $objTipoImagen->alto, 'center');
                                    $this->imagenes_lib->save(UPLOAD_IMAGENES_VIDEOS . $directorio . preg_replace("/\\.[^.\\s]{3,4}$/", "", $imgFile) . '_' . $objTipoImagen->ancho . 'x' . $objTipoImagen->alto . '.' . $extension[$num]);
                                    array_push($arrayImagenes, preg_replace("/\\.[^.\\s]{3,4}$/", "", $imgFile) . '_' . $objTipoImagen->ancho . 'x' . $objTipoImagen->alto . '.' . $extension[$num]);
                                }
                            }

                            $this->registrar_imagenes_maestro($objBeanMaestroSaved->id, $arrayImagenes, FCPATH . 'uploads/temp/' . $this->input->post('imagen_maestro'));
                            $post = $this->input->post();
                            $post['maestro_id'] = $objBeanMaestroSaved->id;
                            $this->guardarTagsMaestro($objBeanMaestroSaved, $post);
                            $objCanal = $this->canales_m->get($this->input->post('canal_id'));
                            if ($this->input->post('tipo') == $this->config->item('videos:programa')) {
                                $this->generarNuevaPortada($objCanal, $objBeanMaestroSaved, $this->config->item('portada:programa'));
                            } else {
                                if ($this->input->post('tipo') == $this->config->item('videos:coleccion')) {
                                    if ($this->input->post('programa') > 0) {//generamos la seccion coleccion para el programa
                                        $this->generarSeccionColeccion($this->input->post('programa'), $objBeanMaestroSaved);
                                    } else {//generamos la seccion coleccion para el canal
                                        $this->generarSeccionColeccionCanal($this->input->post('canal_id'), $objBeanMaestroSaved);
                                    }
                                }
                            }
                            $maestro_id = $objBeanMaestroSaved->id;
                        }
                        $returnValue = 0;
                    } else {
                        $returnValue = 2; //no se encontró en los temporales
                    }
                }
            }
            echo json_encode(array("value" => $returnValue, "maestro_id" => $maestro_id, "canal_id" => $canal_id));
        }
    }

    private function generarNuevaPortada($objCanal, $objMaestro = NULL, $tipo_portada) {
        $user_id = (int) $this->session->userdata('user_id');
        //creamos el registro de portada
        $objBeanPortada = new stdClass();
        $objBeanPortada->id = NULL;
        $objBeanPortada->canales_id = $objCanal->id;
        if ($tipo_portada == $this->config->item('portada:canal')) {
            $objBeanPortada->nombre = 'Portada ' . $objCanal->nombre; ///PORTADA + nombre del canal
            $objBeanPortada->descripcion = $objCanal->descripcion; //jala del canal            
            $objBeanPortada->origen_id = $objCanal->id;
        } else {
            $objBeanPortada->nombre = 'Portada ' . $objMaestro->nombre; ///PORTADA + nombre del canal
            $objBeanPortada->descripcion = $objMaestro->descripcion; //jala del canal            
            $objBeanPortada->origen_id = $objMaestro->id;
        }
        $objBeanPortada->tipo_portadas_id = $tipo_portada; //$this->config->item('portada:canal');
        $objBeanPortada->estado = '0';
        $objBeanPortada->fecha_registro = date("Y-m-d H:i:s");
        $objBeanPortada->usuario_registro = $user_id;
        $objBeanPortada->fecha_actualizacion = date("Y-m-d H:i:s");
        $objBeanPortada->usuario_actualizacion = $user_id;
        $objBeanPortada->id_mongo = '0';
        $objBeanPortada->estado_migracion = '0';
        $objBeanPortada->fecha_migracion = '0000-00-00 00:00:00';
        $objBeanPortada->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
        $objBeanPortadaSaved = $this->portada_m->save($objBeanPortada);
        //listamos los tipos de secciones predefinidas para crearlas
        $arraySecciones = $this->tipo_secciones_m->get_many_by(array());
        if ($tipo_portada != $this->config->item('portada:canal')) {
            if (count($arraySecciones) > 0) {
                foreach ($arraySecciones as $puntero => $oS) {
                    if ($oS->id == $this->config->item('seccion:programa')) {
                        unset($arraySecciones[$puntero]);
                    }
                }
            }
        }
        //iteramos los tipos de seccion para generarlas
        foreach ($arraySecciones as $puntero => $objTipoSeccion) {
            if ($objTipoSeccion->id < intval($this->config->item('seccion:perzonalizado'))) {//no se creara secciones personalizadas
                $objBeanSeccion = new stdClass();
                $objBeanSeccion->id = NULL;
                $objBeanSeccion->nombre = ucwords($objTipoSeccion->nombre); // nombre de la seccion es el nombre del tipo de la seccion
                if ($objTipoSeccion->id == $this->config->item('seccion:destacado')) {
                    $objBeanSeccion->templates_id = '1';
                } else {
                    if ($objTipoSeccion->id == $this->config->item('seccion:programa')) {
                        $objBeanSeccion->templates_id = '6';
                    } else {
                        if ($objTipoSeccion->id == $this->config->item('seccion:video')) {
                            $objBeanSeccion->templates_id = '5';
                        } else {
                            $objBeanSeccion->templates_id = '3';
                        }
                    }
                }
                $objBeanSeccion->descripcion = '';
                $objBeanSeccion->tipo = '0';
                $objBeanSeccion->portadas_id = $objBeanPortadaSaved->id;
                $objBeanSeccion->tipo_secciones_id = $objTipoSeccion->id;
                $objBeanSeccion->peso = ($puntero + 1);
                $objBeanSeccion->id_mongo = '0';
                $objBeanSeccion->estado = '0';
                $objBeanSeccion->fecha_registro = date("Y-m-d H:i:s");
                $objBeanSeccion->usuario_registro = $user_id;
                $objBeanSeccion->fecha_actualizacion = date("Y-m-d H:i:s");
                $objBeanSeccion->usuario_actualizacion = $user_id;
                $objBeanSeccion->estado_migracion = '0';
                $objBeanSeccion->fecha_migracion = '0000-00-00 00:00:00';
                $objBeanSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                $objBeanSeccion->grupo_maestros_id = NULL;
                $objBeanSeccionSaved = $this->secciones_m->save($objBeanSeccion);

                //verificamos que es de portada de tipo programa para que registre su destacado
                if ($tipo_portada == $this->config->item('portada:programa')) {
                    //en la sección destacado buscar imagen extralarge para registrar detalle seccion
                    if ($objTipoSeccion->id == intval($this->config->item('seccion:destacado'))) {//seccion destacado
                        $objImagen = $this->imagen_m->get_by(array("grupo_maestros_id" => $objMaestro->id, "estado" => "1", "tipo_imagen_id" => $this->config->item('imagen:extralarge')));
                        if (count($objImagen) > 0) {
                            $objBeanDetalleSecciones = new stdClass();
                            $objBeanDetalleSecciones->id = NULL;
                            $objBeanDetalleSecciones->secciones_id = $objBeanSeccionSaved->id;
                            $objBeanDetalleSecciones->reglas_id = NULL;
                            $objBeanDetalleSecciones->videos_id = NULL;
                            $objBeanDetalleSecciones->grupo_maestros_id = $objMaestro->id;
                            $objBeanDetalleSecciones->categorias_id = NULL;
                            $objBeanDetalleSecciones->tags_id = NULL;
                            $objBeanDetalleSecciones->imagenes_id = $objImagen->id;
                            $objBeanDetalleSecciones->peso = 1;
                            $objBeanDetalleSecciones->descripcion_item = NULL;
                            $objBeanDetalleSecciones->estado = 1;
                            $objBeanDetalleSecciones->fecha_registro = date("Y-m-d H:i:s");
                            $objBeanDetalleSecciones->usuario_registro = $user_id;
                            $objBeanDetalleSecciones->estado_migracion = '0';
                            $objBeanDetalleSecciones->fecha_migracion = '0000-00-00 00:00:00';
                            $objBeanDetalleSecciones->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                            $objBeanDetalleSeccionesSaved = $this->detalle_secciones_m->save($objBeanDetalleSecciones);
                            $this->secciones_m->update($objBeanSeccionSaved->id, array("estado" => "1"));
                        }
                    }
                }
            }
        }
    }

    private function registrarDetalleMaestro($objMaestro, $post) {
        $user_id = (int) $this->session->userdata('user_id');
        switch ($post['tipo']) {
            case $this->config->item('videos:coleccion'):
                if ($post['programa'] > 0) {
                    $objBeanGrupoDetalle = new stdClass();
                    $objBeanGrupoDetalle->id = NULL;
                    $objBeanGrupoDetalle->grupo_maestro_padre = $post['programa'];
                    $objBeanGrupoDetalle->grupo_maestro_id = $objMaestro->id;
                    $objBeanGrupoDetalle->video_id = NULL;
                    $objBeanGrupoDetalle->tipo_grupo_maestros_id = $this->config->item('videos:programa');
                    $objBeanGrupoDetalle->id_mongo = NULL;
                    $objBeanGrupoDetalle->estado = 1;
                    $objBeanGrupoDetalle->fecha_registro = date("Y-m-d H:i:s");
                    $objBeanGrupoDetalle->usuario_registro = $user_id;
                    $objBeanGrupoDetalle->fecha_actualizacion = date("Y-m-d H:i:s");
                    $objBeanGrupoDetalle->usuario_actualizacion = $user_id;
                    $objBeanGrupoDetalle->estado_migracion = 0;
                    $objBeanGrupoDetalle->fecha_migracion = '0000-00-00 00:00:00';
                    $objBeanGrupoDetalle->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                    $this->grupo_detalle_m->saveMaestroDetalle($objBeanGrupoDetalle);
                }
                break;
            case $this->config->item('videos:lista'):
                if ($post['programa'] > 0) {
                    if ($post['coleccion'] > 0) {//lista de la coleccion
                        $objBeanGrupoDetalle = new stdClass();
                        $objBeanGrupoDetalle->id = NULL;
                        $objBeanGrupoDetalle->grupo_maestro_padre = $post['coleccion'];
                        $objBeanGrupoDetalle->grupo_maestro_id = $objMaestro->id;
                        $objBeanGrupoDetalle->video_id = NULL;
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
                        $this->grupo_detalle_m->saveMaestroDetalle($objBeanGrupoDetalle);
                    } else {//lista del programa
                        $objBeanGrupoDetalle = new stdClass();
                        $objBeanGrupoDetalle->id = NULL;
                        $objBeanGrupoDetalle->grupo_maestro_padre = $post['programa'];
                        $objBeanGrupoDetalle->grupo_maestro_id = $objMaestro->id;
                        $objBeanGrupoDetalle->video_id = NULL;
                        $objBeanGrupoDetalle->tipo_grupo_maestros_id = $this->config->item('videos:programa');
                        $objBeanGrupoDetalle->id_mongo = NULL;
                        $objBeanGrupoDetalle->estado = 1;
                        $objBeanGrupoDetalle->fecha_registro = date("Y-m-d H:i:s");
                        $objBeanGrupoDetalle->usuario_registro = $user_id;
                        $objBeanGrupoDetalle->fecha_actualizacion = date("Y-m-d H:i:s");
                        $objBeanGrupoDetalle->usuario_actualizacion = $user_id;
                        $objBeanGrupoDetalle->estado_migracion = 0;
                        $objBeanGrupoDetalle->fecha_migracion = '0000-00-00 00:00:00';
                        $objBeanGrupoDetalle->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                        $this->grupo_detalle_m->saveMaestroDetalle($objBeanGrupoDetalle);
                    }
                }
                break;
        }
    }

    private function _obtenerPesoMaestro($post) {
        $returnValue = 0;
        switch ($post['tipo']) {
            case $this->config->item('videos:coleccion'):
                if ($post['programa'] > 0) {
                    $lista_coleccion_programa = $this->coleccion_de_programa($post['programa']);
                    $mayor = 0;
                    if (count($lista_coleccion_programa) > 0) {
                        foreach ($lista_coleccion_programa as $puntero => $objMaestro) {
                            if ($objMaestro->peso > $mayor) {
                                $mayor = $objMaestro->peso;
                            }
                        }
                    }
                    $returnValue = $mayor;
                } else {
                    $coleccion_canal = $this->coleccion_canal($post['canal_id']);
                    if (count($coleccion_canal) > 0) {
                        $mayor = 0;
                        foreach ($coleccion_canal as $indice => $objColeccion) {
                            if ($objColeccion->peso > $mayor) {
                                $mayor = $objColeccion->peso;
                            }
                        }
                    }
                    $returnValue = $mayor;
                }
                break;
            case $this->config->item('videos:lista'):
                if ($post['programa'] > 0) {
                    if ($post['coleccion'] > 0) {
                        $lista_coleccion = $this->lista_coleccion($post['canal_id'], $post['coleccion']);
                        if (count($lista_coleccion) > 0) {
                            $mayor = 0;
                            foreach ($lista_coleccion as $indice => $objColeccion) {
                                if ($objColeccion->peso > $mayor) {
                                    $mayor = $objColeccion->peso;
                                }
                            }
                        }
                        $returnValue = $mayor;
                    } else {//lista para el programa
                        $lista_programa = $this->lista_programa($post['canal_id'], $post['programa']);
                        if (count($lista_programa) > 0) {
                            $mayor = 0;
                            foreach ($lista_programa as $indice => $objLista) {
                                if ($objLista->peso > $mayor) {
                                    $mayor = $objLista->peso;
                                }
                            }
                        }
                        $returnValue = $mayor;
                    }
                } else {
                    $lista = $this->lista_canal($post['canal_id']);
                    if (count($lista) > 0) {
                        $mayor = 0;
                        foreach ($lista as $indice => $objLista) {
                            if ($objLista->peso > $mayor) {
                                $mayor = $objLista->peso;
                            }
                        }
                    }
                    $returnValue = $mayor;
                }
                break;
        }
        return $returnValue + 1;
    }

    private function lista_coleccion($canal_id, $coleccion_id) {
        $returnValue = array();
        $detalles = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $coleccion_id));
        if (count($detalles) > 0) {
            foreach ($detalles as $puntero => $objDetalle) {
                $objMaestro = $this->grupo_maestro_m->get_by(array("canales_id" => $canal_id, "tipo_grupo_maestro_id" => $this->config->item('videos:coleccion')));
                if (count($objMaestro) > 0) {
                    array_push($returnValue, $objMaestro);
                }
            }
        }
        return $returnValue;
    }

    private function lista_programa($canal_id = NULL, $programa_id) {
        $returnValue = array();
        $detalles = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $programa_id));

        if (count($detalles) > 0) {
            foreach ($detalles as $puntero => $objDetalle) {
                if ($canal_id == NULL) {
                    $objMaestro = $this->grupo_maestro_m->get_by(array("id" => $objDetalle->grupo_maestro_id, "tipo_grupo_maestro_id" => $this->config->item('videos:lista')));
                } else {
                    $objMaestro = $this->grupo_maestro_m->get_by(array("id" => $objDetalle->grupo_maestro_id, "canales_id" => $canal_id, "tipo_grupo_maestro_id" => $this->config->item('videos:lista')));
                }
                if (count($objMaestro) > 0) {
                    $objMaestro->es_maestro = 1;
                    array_push($returnValue, $objMaestro);
                }
            }
        }
        //$this->vd($returnValue);
        return $returnValue;
    }

    private function coleccion_canal($canal_id) {
        $returnValue = array();
        $lista_coleccion = $this->grupo_maestro_m->get_many_by(array("canales_id" => $canal_id, "tipo_grupo_maestro_id" => $this->config->item('videos:coleccion')));
        if (count($lista_coleccion) > 0) {
            foreach ($lista_coleccion as $puntero => $objMaestro) {
                if (!$this->_isParentOrChild($objMaestro->id)) {
                    $objMaestro->es_maestro = 1;
                    array_push($returnValue, $objMaestro);
                }
            }
        }
        return $returnValue;
    }

    private function lista_canal($canal_id) {
        $returnValue = array();
        $lista = $this->grupo_maestro_m->get_many_by(array("canales_id" => $canal_id, "tipo_grupo_maestro_id" => $this->config->item('videos:lista')));
        if (count($lista) > 0) {
            foreach ($lista as $puntero => $objMaestro) {
                if (!$this->_isParentOrChild($objMaestro->id)) {
                    $objMaestro->es_maestro = 1;
                    array_push($returnValue, $objMaestro);
                }
            }
        }
        return $returnValue;
    }

    private function guardarTagsMaestro($objBeanMaestro, $post) {
        if ($post['maestro_id'] > 0) {//edicion de tags
            $user_id = (int) $this->session->userdata('user_id');
            $arrayTagTematicas = explode(",", $post['tematicas']);
            $arraytagPersonajes = explode(",", $post['personajes']);
            //error_log(print_r($arrayTagTematicas,true));
            //error_log(print_r($arraytagPersonajes,true));die();
            if (count($arrayTagTematicas) > 0) {
                foreach ($arrayTagTematicas as $index => $tematica) {
                    $tag_id = 0;
                    if ($this->tags_m->existTag($tematica, $this->config->item('tag:tematicas'))) {
                        $tag_id = $this->tags_m->getIdTag($tematica, $this->config->item('tag:tematicas'));
                    } else {
                        $objBeanTag = new stdClass();
                        $objBeanTag->id = NULL;
                        $objBeanTag->tipo_tags_id = $this->config->item('tag:tematicas');
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
                        $objBeanTagSaved = $this->tags_m->saveTag($objBeanTag);
                        $tag_id = $objBeanTagSaved->id;
                    }

                    //gurdamos la relación de cada tag con su video
                    if ($tag_id > 0 && !$this->grupo_maestro_tag_m->existRelacion($tag_id, $objBeanMaestro->id)) {
                        $objBeanMaestroTag = new stdClass();
                        $objBeanMaestroTag->grupo_maestros_id = $objBeanMaestro->id;
                        $objBeanMaestroTag->tags_id = $tag_id;
                        $objBeanMaestroTag->estado = 1;
                        $objBeanMaestroTag->fecha_registro = date("Y-m-d H:i:s");
                        $objBeanMaestroTag->usuario_registro = $user_id;
                        $objBeanMaestroTag->fecha_actualizacion = date("Y-m-d H:i:s");
                        $objBeanMaestroTag->usuario_actualizacion = $user_id;
                        $objBeanMaestroTag->estado_migracion_sphinx = NULL;
                        $objBeanMaestroTag->fecha_migracion_sphinx = '0000-00-00 00:00:00';
                        $objBeanMaestroTag->fecha_migracion_actualizacion_sphinx = '0000-00-00 00:00:00';
                        $this->grupo_maestro_tag_m->save($objBeanMaestroTag);
                    }
                }
                //eliminamos los tags que ya no son necesarios
                $this->_limpiarAntiguosTag($objBeanMaestro, $arrayTagTematicas, $this->config->item('tag:tematicas'));
            }

            //guardamos los tag de personajes
            if (count($arraytagPersonajes) > 0) {
                foreach ($arraytagPersonajes as $index => $personaje) {
                    $tag_id = 0;
                    if ($this->tags_m->existTag($personaje, $this->config->item('tag:personajes'))) {
                        $tag_id = $this->tags_m->getIdTag($personaje, $this->config->item('tag:personajes'));
                    } else {
                        $objBeanTag = new stdClass();
                        $objBeanTag->id = NULL;
                        $objBeanTag->tipo_tags_id = $this->config->item('tag:personajes');
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
                    if ($tag_id > 0 && !$this->grupo_maestro_tag_m->existRelacion($tag_id, $objBeanMaestro->id)) {
                        $objBeanMaestroTag = new stdClass();
                        $objBeanMaestroTag->grupo_maestros_id = $objBeanMaestro->id;
                        $objBeanMaestroTag->tags_id = $tag_id;
                        $objBeanMaestroTag->estado = 1;
                        $objBeanMaestroTag->fecha_registro = date("Y-m-d H:i:s");
                        $objBeanMaestroTag->usuario_registro = $user_id;
                        $objBeanMaestroTag->fecha_actualizacion = date("Y-m-d H:i:s");
                        $objBeanMaestroTag->usuario_actualizacion = $user_id;
                        $objBeanMaestroTag->estado_migracion_sphinx = NULL;
                        $objBeanMaestroTag->fecha_migracion_sphinx = '0000-00-00 00:00:00';
                        $objBeanMaestroTag->fecha_migracion_actualizacion_sphinx = '0000-00-00 00:00:00';
                        $this->grupo_maestro_tag_m->save($objBeanMaestroTag);
                    }
                }

                //eliminamos los tags que ya no son necesarios
                $this->_limpiarAntiguosTag($objBeanMaestro, $arraytagPersonajes, $this->config->item('tag:personajes'));
            }
        }
    }

    public function existeNombreMaestro($nombre_maestro, $canal_id, $maestro_id) {
        $returnValue = false;
        if ($maestro_id > 0) {
            $objMaestro = $this->grupo_maestro_m->get($maestro_id);
            $lista_similares = $this->grupo_maestro_m->like('nombre', $nombre_maestro)->get_many_by(array("canales_id" => $canal_id));
            if (count($lista_similares) > 0) {
                if ($this->tienePadre($objMaestro->id)) {
                    $objPadreMaestro = $this->obtenerMaestroPadre($maestro_id);
                    $hijosPadre = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $objPadreMaestro->id));
                    if (count($hijosPadre) > 0) {
                        foreach ($hijosPadre as $puntero => $objDetalle) {
                            if ($objDetalle->grupo_maestro_id != NULL && $objDetalle->grupo_maestro_id != $maestro_id) {
                                $maestro = $this->grupo_maestro_m->get($objDetalle->grupo_maestro_id);
                                if (trim(strtolower($maestro->nombre)) == trim(strtolower($nombre_maestro))) {
                                    $returnValue = true;
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $lista_similares = $this->grupo_maestro_m->like('nombre', $nombre_maestro)->get_many_by(array("canales_id" => $canal_id));
            if (count($lista_similares) > 0) {
                $returnValue = true;
            }
        }
        return $returnValue;
    }

    private function tienePadre($maestro_id) {
        $returnValue = false;
        $objCollectionMaestro = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_id" => $maestro_id));
        if (count($objCollectionMaestro) > 0) {
            $returnValue = true;
        }
        return $returnValue;
    }

    private function obtenerMaestroPadre($maestro_id) {
        $objDetalle = $this->grupo_detalle_m->get_by(array("grupo_maestro_id" => $maestro_id));
        $objMaestro = $this->grupo_maestro_m->get($objDetalle->grupo_maestro_padre);
        return $objMaestro;
    }

    public function generar_programa() {
        if ($this->input->is_ajax_request()) {
            $html = '';
            switch ($this->input->post('tipo')) {
                case $this->config->item('videos:coleccion'):
                    $lista_programas = $this->grupo_maestro_m->get_many_by(array("canales_id" => $this->input->post('canal_id'), "tipo_grupo_maestro_id" => $this->config->item('videos:programa')));
                    if (count($lista_programas) > 0) {
                        $html.='<label for="tipo">' . lang('videos:programme') . '</label>';
                        $html.='<select name="programa" id="programa" >';
                        $html.='<option value="0">' . lang('videos:select_programme') . '</option>';
                        foreach ($lista_programas as $index => $objPrograma) {
                            $html.='<option value="' . $objPrograma->id . '">' . $objPrograma->nombre . '</option>';
                        }
                        $html.='</select><br /><br />';
                    }
                    break;
                case $this->config->item('videos:lista'):
                    $lista_programas = $this->grupo_maestro_m->get_many_by(array("canales_id" => $this->input->post('canal_id'), "tipo_grupo_maestro_id" => $this->config->item('videos:programa')));
                    if (count($lista_programas) > 0) {
                        $html.='<label for="tipo">' . lang('videos:programme') . '</label>';
                        $html.='<select name="programa" id="programa" onchange="generar_coleccion()" >';
                        $html.='<option value="0">' . lang('videos:select_programme') . '</option>';
                        foreach ($lista_programas as $index => $objPrograma) {
                            $html.='<option value="' . $objPrograma->id . '">' . $objPrograma->nombre . '</option>';
                        }
                        $html.='</select><br /><br /><div id="divColeccion"></div>';
                    }
                    break;
            }
            echo $html;
        }
    }

    private function coleccion_de_programa($programa_id) {
        $lista_detalle = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $programa_id));
        if (count($lista_detalle) > 0) {
            $array_coleccion = array();
            foreach ($lista_detalle as $puntero => $objDetalle) {
                if ($objDetalle->grupo_maestro_id != NULL) {
                    $objColeccion = $this->grupo_maestro_m->get_by(array("id" => $objDetalle->grupo_maestro_id, "tipo_grupo_maestro_id" => $this->config->item('videos:coleccion')));
                    if (count($objColeccion) > 0) {
                        array_push($array_coleccion, $objColeccion);
                    }
                }
            }
        }
        return $lista_detalle;
    }

    public function generar_coleccion() {
        if ($this->input->is_ajax_request()) {
            $html = '';
            $lista_detalle = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $this->input->post('programa')));
            if (count($lista_detalle) > 0) {
                $array_coleccion = array();
                foreach ($lista_detalle as $puntero => $objDetalle) {
                    if ($objDetalle->grupo_maestro_id != NULL) {
                        $objColeccion = $this->grupo_maestro_m->get_by(array("id" => $objDetalle->grupo_maestro_id, "tipo_grupo_maestro_id" => $this->config->item('videos:coleccion')));
                        if (count($objColeccion) > 0) {
                            array_push($array_coleccion, $objColeccion);
                        }
                    }
                }
            }
            if (count($array_coleccion) > 0) {
                $html.='<label for="coleccion">' . lang('videos:collection') . '</label>';
                $html.='<select name="coleccion" id="coleccion" >';
                $html.='<option value="0">' . lang('videos:select_collection') . '</option>';
                foreach ($array_coleccion as $index2 => $objTemporada) {
                    $html.='<option value="' . $objTemporada->id . '">' . $objTemporada->nombre . '</option>';
                }
                $html.='</select><br /><br />';
            }
            echo $html;
        }
    }

    public function log($var) {
        error_log(print_r($var, true));
    }

    public function obtenerMaestrosParaSecciones($session_tipo_id, $canal_id, $objMaestro = NULL) {
        $returnValue = array();
        if ($session_tipo_id < intval($this->config->item('seccion:visto'))) { //solo videos, listas, colecciones, programas
            if ($objMaestro == NULL) {
                if ($session_tipo_id == intval($this->config->item('seccion:programa'))) {//programa
                    $returnValue = $this->grupo_maestro_m->get_many_by(array("tipo_grupo_maestro_id" => $this->config->item('videos:programa'), "canales_id" => $canal_id));
                } else {
                    if ($session_tipo_id == intval($this->config->item('seccion:video'))) {//video
                        $returnValue = $this->obtenerVideosCanal($canal_id);
                    } else {//coleccion y lista del canal
                        if ($session_tipo_id == intval($this->config->item('seccion:coleccion'))) {
                            $tipo_grupo_maestro = $this->config->item('videos:coleccion');
                        } else {
                            if ($session_tipo_id == intval($this->config->item('seccion:lista'))) {
                                $tipo_grupo_maestro = $this->config->item('videos:lista');
                            }
                        }
                        $returnValue = $this->_getListMasterChannel($this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $tipo_grupo_maestro, 'canales_id' => $canal_id), 'nombre'));
                        unset($returnValue[0]);
                    }
                }
            } else {
                if ($session_tipo_id == intval($this->config->item('seccion:video'))) {
                    $returnValue = $this->obtenerVideosPrograma($objMaestro->id);
                } else {
                    if ($session_tipo_id == intval($this->config->item('seccion:coleccion'))) {
                        $tipo_grupo_maestro = $this->config->item('videos:coleccion');
                    } else {
                        if ($session_tipo_id == intval($this->config->item('seccion:lista'))) {
                            $tipo_grupo_maestro = $this->config->item('videos:lista');
                        }
                    }
                    $returnValue = $this->_obtenerMaestrosPrograma($tipo_grupo_maestro, $objMaestro->id);
//                    if($objMaestro != NULL){
//                        error_log('--->'.print_r($returnValue, true));
//                    }                    
                }
            }
        }
        return $returnValue;
    }

    public function obtenerVideosCanal($canal_id) {
        $returnValue = array();
        $lista_video = $this->videos_m->get_many_by(array("canales_id" => $canal_id));
        if (count($lista_video) > 0) {
            foreach ($lista_video as $index => $objVideo) {
                if ($this->tieneMaestro($objVideo->id)) {
                    unset($lista_video[$index]);
                } else {
                    $objVideo->es_maestro = 0;
                    $lista_video[$index] = $objVideo;
                }
            }
            $returnValue = $lista_video;
        }
        return $returnValue;
    }

    private function videos_programa($programa_id) {
        $returnValue = array();
        $coleccionMaestroDetalle = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $programa_id, "tipo_grupo_maestros_id" => $this->config->item('videos:programa')));
        if (count($coleccionMaestroDetalle) > 0) {
            foreach ($coleccionMaestroDetalle as $index => $objMaestroDetalle) {
                if ($objMaestroDetalle->grupo_maestro_id == NULL && $objMaestroDetalle->video_id != NULL) {
                    $objVideo = $this->videos_m->get($objMaestroDetalle->video_id);
                    if (count($objVideo) > 0) {
                        $objVideo->es_maestro = 0;
                        array_push($returnValue, $objMaestroDetalle);
                    }
                }
            }
        }
        return $returnValue;
    }

    private function videos_lista($lista_id) {
        $returnValue = array();
        $coleccionMaestroDetalle = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $lista_id, "tipo_grupo_maestros_id" => $this->config->item('videos:lista')));
        if (count($coleccionMaestroDetalle) > 0) {
            foreach ($coleccionMaestroDetalle as $index => $objMaestroDetalle) {
                if ($objMaestroDetalle->grupo_maestro_id == NULL && $objMaestroDetalle->video_id != NULL) {
                    $objVideo = $this->videos_m->get($objMaestroDetalle->video_id);
                    if (count($objVideo) > 0) {
                        $objVideo->es_maestro = 0;
                        array_push($returnValue, $objVideo);
                    }
                }
            }
        }
        return $returnValue;
    }

    public function _obtenerImagenPorMaestro($maestro_id, $image_type, $seccion, $canal_id) {
        $returnValue = 0;
        if ($seccion == $this->config->item('seccion:video')) {
            $objImagen = $this->imagen_m->get_by(array("canales_id" => $canal_id, "tipo_imagen_id" => $image_type));
        } else {
            $objImagen = $this->imagen_m->get_by(array("grupo_maestros_id" => $maestro_id, "tipo_imagen_id" => $image_type));
        }
        if (count($objImagen) > 0) {
            $returnValue = $objImagen->id;
        }
        return $returnValue;
    }

    public function _obtenerMaestrosPrograma($tipo_grupo_maestro, $maestro_programa_id) {
        $returnValue = array();
        $coleccionMaestroDetalle = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $maestro_programa_id));
        if (count($coleccionMaestroDetalle) > 0) {
            foreach ($coleccionMaestroDetalle as $index => $objDetalle) {
                if ($objDetalle->grupo_maestro_id != NULL) {
                    $objMaestro = $this->grupo_maestro_m->get($objDetalle->grupo_maestro_id);
                    if (count($objMaestro) > 0) {
                        if ($objMaestro->tipo_grupo_maestro_id == $tipo_grupo_maestro) {
                            array_push($returnValue, $objMaestro);
                        }
                    }
                }
            }
        }
        return $returnValue;
    }

    public function obtenerVideosPrograma($programa_id) {
        $returnValue = array();
        $coleccionMaestroDetalle = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $programa_id, "tipo_grupo_maestros_id" => $this->config->item('videos:programa')));
        if (count($coleccionMaestroDetalle) > 0) {
            foreach ($coleccionMaestroDetalle as $index => $objMaestro) {
                if ($objMaestro->grupo_maestro_id == NULL && $objMaestro->video_id != NULL) {
                    array_push($returnValue, $objMaestro);
                }
            }
        }
        return $returnValue;
    }

    public function listar_para_programa($current_page = 1, $paginado = 0) {
        if ($this->input->is_ajax_request()) {
            //listar las colecciones, listas y videos paginado con jquery
            //primero listamos las colecciones del programa
            $colecciones = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $this->input->post('maestro_id')));
            if (count($colecciones) > 0) {
                $array_coleccion = array();
                foreach ($colecciones as $puntero => $objDetalleGrupo) {
                    $objColeccion = $this->grupo_maestro_m->get_by(array("id" => $objDetalleGrupo->grupo_maestro_id, "tipo_grupo_maestro_id" => $this->config->item('videos:coleccion')));
                    if (count($objColeccion) > 0) {
                        $objColeccion->es_maestro = 1;
                        array_push($array_coleccion, $objColeccion);
                    }
                }
                //obtenemos las colecciones directamente del canal
                $colecciones_canal = $this->coleccion_canal($this->input->post('canal_id'));
                if (count($colecciones_canal) > 0) {
                    $array_coleccion = array_merge($array_coleccion, $colecciones_canal);
                }
                //obtenemos las listas
                $listas = $this->lista_programa(NULL, $this->input->post('maestro_id'));
                if (count($listas) > 0) {
                    $array_coleccion = array_merge($array_coleccion, $listas);
                }
                //obtenemos listas directamente del canal
                $listas_canal = $this->lista_canal($this->input->post('canal_id'));
                if (count($listas_canal) > 0) {
                    $array_coleccion = array_merge($array_coleccion, $listas_canal);
                }
                //obtenemos los videos del programa
                $videos = $this->videos_programa($this->input->post('maestro_id'));
                if (count($videos) > 0) {
                    $array_coleccion = array_merge($array_coleccion, $videos);
                }
                //obtenemos los videos del canal
                $videos_canal = $this->obtenerVideosCanal($this->input->post('canal_id'));
                if (count($videos_canal) > 0) {
                    $array_coleccion = array_merge($array_coleccion, $videos_canal);
                }
            }
            $total = count($array_coleccion);
            $cantidad_mostrar = 3;
            $current_page = $current_page - 1;
            $numero_paginas = ceil(count($array_coleccion) / $cantidad_mostrar);
            $offset = $current_page * $cantidad_mostrar;
            $array_coleccion = array_slice($array_coleccion, $offset, $cantidad_mostrar);
            if ($paginado == '1') {
                $returnValue = $this->htmlListaMaestro($array_coleccion, $this->input->post('maestro_id'));
            } else {
                $returnValue = '<table>';
                $returnValue.='<thead>';
                $returnValue.='<tr>';
                $returnValue.='<th>#</th>';
                $returnValue.='<th>imagen</td>';
                $returnValue.='<th>nombre</td>';
                $returnValue.='<th>acciones</td>';
                $returnValue.='</tr>';
                $returnValue.='</thead>';
                $returnValue.='<tbody id="resultado">';
                $returnValue.= $this->htmlListaMaestro($array_coleccion, $this->input->post('maestro_id'));
                $returnValue.='</tbody>';
                $returnValue.='</table>';
                $returnValue.='<input type="hidden" id="total" name="total" value="' . $total . '" />';
                $returnValue.='<input type="hidden" id="cantidad_mostrar" name="cantidad_mostrar" value="' . $cantidad_mostrar . '" />';
                $returnValue.='<div id="black" style="margin: auto;"></div>';
            }
            echo $returnValue;
        }
    }

    public function listar_para_coleccion($current_page = 1, $paginado = 0) {
        if ($this->input->is_ajax_request()) {
            //listar las colecciones, listas y videos paginado con jquery
            //primero listamos las colecciones del programa
            $colecciones = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $this->input->post('maestro_id')));
            if (count($colecciones) > 0) {
                $array_coleccion = array();
                foreach ($colecciones as $puntero => $objDetalleGrupo) {
                    $objColeccion = $this->grupo_maestro_m->get_by(array("id" => $objDetalleGrupo->grupo_maestro_id, "tipo_grupo_maestro_id" => $this->config->item('videos:lista')));
                    if (count($objColeccion) > 0) {
                        $objColeccion->es_maestro = 1;
                        array_push($array_coleccion, $objColeccion);
                    }
                }
                //obtenemos listas directamente del canal
                $listas_canal = $this->lista_canal($this->input->post('canal_id'));
                if (count($listas_canal) > 0) {
                    $array_coleccion = array_merge($array_coleccion, $listas_canal);
                }
                //obtenemos los videos del canal
                $videos_canal = $this->obtenerVideosCanal($this->input->post('canal_id'));
                if (count($videos_canal) > 0) {
                    $array_coleccion = array_merge($array_coleccion, $videos_canal);
                }
                //obtenemos los videos directamente relacionado a la coleccion
                $videos_coleccion = $this->obtenerVideosColeccion($this->input->post('maestro_id'));
                if (count($videos_coleccion) > 0) {
                    $array_coleccion = array_merge($array_coleccion, $videos_coleccion);
                }
            }
            $total = count($array_coleccion);
            $cantidad_mostrar = 3;
            $current_page = $current_page - 1;
            $numero_paginas = ceil(count($array_coleccion) / $cantidad_mostrar);
            $offset = $current_page * $cantidad_mostrar;
            $array_coleccion = array_slice($array_coleccion, $offset, $cantidad_mostrar);
            if ($paginado == '1') {
                $returnValue = $this->htmlListaMaestro($array_coleccion, $this->input->post('maestro_id'));
            } else {
                $returnValue = $this->htmlListaMaestro($array_coleccion, $this->input->post('maestro_id'));
                $returnValue = '<table>';
                $returnValue.='<thead>';
                $returnValue.='<tr>';
                $returnValue.='<th>#</th>';
                $returnValue.='<th>imagen</td>';
                $returnValue.='<th>nombre</td>';
                $returnValue.='<th>acciones</td>';
                $returnValue.='</tr>';
                $returnValue.='</thead>';
                $returnValue.='<tbody id="resultado">';
                $returnValue.= $this->htmlListaMaestro($array_coleccion, $this->input->post('maestro_id'));
                $returnValue.='</tbody>';
                $returnValue.='</table>';
                $returnValue.='<input type="hidden" id="total" name="total" value="' . $total . '" />';
                $returnValue.='<input type="hidden" id="cantidad_mostrar" name="cantidad_mostrar" value="' . $cantidad_mostrar . '" />';
                $returnValue.='<div id="black" style="margin: auto;"></div>';
            }
            echo $returnValue;
        }
    }

    public function listar_para_lista($current_page = 1, $paginado = 0) {
        if ($this->input->is_ajax_request()) {
            //listar las colecciones, listas y videos paginado con jquery
            //primero listamos las colecciones del programa
            $colecciones = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $this->input->post('maestro_id')));
            if (count($colecciones) > 0) {
                $array_coleccion = array();
                //obtenemos lista de videos de la lista de reproduccion
                $videos_lista = $this->videos_lista($this->input->post('maestro_id'));
                if (count($videos_lista) > 0) {
                    $array_coleccion = array_merge($array_coleccion, $videos_lista);
                }
                //obtenemos los videos del canal 
                $videos_canal = $this->obtenerVideosCanal($this->input->post('canal_id'));
                if (count($videos_canal) > 0) {
                    $array_coleccion = array_merge($array_coleccion, $videos_canal);
                }
            }
            $total = count($array_coleccion);
            $cantidad_mostrar = 3;
            $current_page = $current_page - 1;
            $numero_paginas = ceil(count($array_coleccion) / $cantidad_mostrar);
            $offset = $current_page * $cantidad_mostrar;
            $array_coleccion = array_slice($array_coleccion, $offset, $cantidad_mostrar);
            if ($paginado == '1') {
                $returnValue = $this->htmlListaMaestro($array_coleccion, $this->input->post('maestro_id'));
            } else {
                $returnValue = $this->htmlListaMaestro($array_coleccion, $this->input->post('maestro_id'));
                $returnValue = '<table>';
                $returnValue.='<thead>';
                $returnValue.='<tr>';
                $returnValue.='<th>#</th>';
                $returnValue.='<th>imagen</td>';
                $returnValue.='<th>nombre</td>';
                $returnValue.='<th>acciones</td>';
                $returnValue.='</tr>';
                $returnValue.='</thead>';
                $returnValue.='<tbody id="resultado">';
                $returnValue.= $this->htmlListaMaestro($array_coleccion, $this->input->post('maestro_id'));
                $returnValue.='</tbody>';
                $returnValue.='</table>';
                $returnValue.='<input type="hidden" id="total" name="total" value="' . $total . '" />';
                $returnValue.='<input type="hidden" id="cantidad_mostrar" name="cantidad_mostrar" value="' . $cantidad_mostrar . '" />';
                $returnValue.='<div id="black" style="margin: auto;"></div>';
            }
            echo $returnValue;
        }
    }

    private function obtenerVideosColeccion($coleccion_id) {
        $returnValue = array();
        $coleccionMaestroDetalle = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $coleccion_id, "tipo_grupo_maestros_id" => $this->config->item('videos:coleccion')));
        if (count($coleccionMaestroDetalle) > 0) {
            foreach ($coleccionMaestroDetalle as $index => $objMaestro) {
                if ($objMaestro->grupo_maestro_id == NULL && $objMaestro->video_id != NULL) {
                    $objVideo = $this->videos_m->get($objMaestro->video_id);
                    if (count($objVideo) > 0) {
                        $objVideo->es_maestro = 0;
                        array_push($returnValue, $objVideo);
                    }
                }
            }
        }
        return $returnValue;
    }

    private function htmlListaMaestro($arrayMaestro, $maestro_id) {
        $returnValue = '';
        if (count($arrayMaestro) > 0) {
            $indice = 0;
            foreach ($arrayMaestro as $puntero => $objMaestro) {
                if ($objMaestro->es_maestro == '1') {
                    $objImagen = $this->imagen_m->get_by(array("grupo_maestros_id" => $objMaestro->id, "tipo_imagen_id" => $this->config->item('imagen:small'), "estado" => "1"));
                    if (count($objImagen) > 0) {
                        if ($objImagen->procedencia == '0') {
                            $imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagen->imagen;
                        } else {
                            $imagen = $objImagen->imagen;
                        }
                    } else {
                        $imagen = UPLOAD_IMAGENES_VIDEOS . 'no_video.jpg';
                    }
                    $returnValue.='<tr>';
                    $returnValue.='<td>' . ($indice + 1) . '</td>';
                    $returnValue.='<td><img src="' . $imagen . '" style="width:100px; height:55px;" title="' . $objMaestro->nombre . '" /></td>';
                    $returnValue.='<td>' . $objMaestro->nombre . '</td>';
                    if ($this->maestroAgregado($objMaestro->id, $maestro_id)) {
                        $returnValue.='<td><a href="#" id="agregado" name="agregado" class="btn silver" onclick="return false;">Agregado</a></td>';
                    } else {
                        $returnValue.='<td><div id="div_' . $objMaestro->id . '"><a href="#" onclick="agregarMaestroAMaestro(' . $objMaestro->id . ', ' . $maestro_id . '); return false;" id="btnAgregar" name="btnAgregar" class="btn green">Agregar</a></div></td>';
                    }
                    $returnValue.='</tr>';
                } else {
                    $objImagen = $this->imagen_m->get_by(array("videos_id" => $objMaestro->id, "tipo_imagen_id" => $this->config->item('imagen:small'), "estado" => "1"));
                    if (count($objImagen) > 0) {
                        if ($objImagen->procedencia == '0') {
                            $imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagen->imagen;
                        } else {
                            $imagen = $objImagen->imagen;
                        }
                    } else {
                        $imagen = UPLOAD_IMAGENES_VIDEOS . 'no_video.jpg';
                    }
                    $returnValue.='<tr>';
                    $returnValue.='<td>' . ($indice + 1) . '</td>';
                    $returnValue.='<td><img src="' . $imagen . '" style="width:100px; height:55px;" title="' . $objMaestro->titulo . '" /></td>';
                    $returnValue.='<td>' . $objMaestro->titulo . '</td>';
                    if ($this->videoAgregado($objMaestro->id, $maestro_id)) {
                        $returnValue.='<td><a href="#" id="agregado" name="agregado" class="btn silver" onclick="return false;">Agregado</a></td>';
                    } else {
                        $returnValue.='<td><div id="div_' . $objMaestro->id . '"><a href="#" onclick="agregarVideoAMaestro(' . $objMaestro->id . ', ' . $maestro_id . '); return false;" id="btnAgregar" name="btnAgregar" class="btn green">Agregar</a></div></td>';
                    }
                    $returnValue.='</tr>';
                }
                $indice++;
            }
        }
        return $returnValue;
    }

    public function agregarMaestroAMaestro($maestro_id, $parent_maestro) {
        if ($this->input->is_ajax_request()) {
            $user_id = (int) $this->session->userdata('user_id');
            $returnValue = 0;
            $detalle_seccion_id = 0;
            if ($this->maestroAgregado($maestro_id, $parent_maestro, 0)) {
                $objDetalleMaestro = $this->grupo_detalle_m->get_by(array("grupo_maestro_padre" => $parent_maestro, "grupo_maestro_id" => $maestro_id));
                $this->grupo_detalle_m->update($objDetalleMaestro->id, array("estado" => "1", "estado_migracion" => $this->config->item('migracion:actualizado')));
            } else {
                $objMaestro = $this->grupo_maestro_m->get($parent_maestro);
                $objBeanDetalleMaestro = new stdClass();
                $objBeanDetalleMaestro->id = NULL;
                $objBeanDetalleMaestro->grupo_maestro_padre = $parent_maestro;
                $objBeanDetalleMaestro->grupo_maestro_id = $maestro_id;
                $objBeanDetalleMaestro->video_id = NULL;
                $objBeanDetalleMaestro->tipo_grupo_maestros_id = $objMaestro->tipo_grupo_maestro_id;
                $objBeanDetalleMaestro->id_mongo = NULL;
                $objBeanDetalleMaestro->estado = 1;
                $objBeanDetalleMaestro->fecha_registro = date("Y-m-d H:i:s");
                $objBeanDetalleMaestro->usuario_registro = $user_id;
                $objBeanDetalleMaestro->fecha_actualizacion = date("Y-m-d H:i:s");
                $objBeanDetalleMaestro->usuario_actualizacion = $user_id;
                $objBeanDetalleMaestro->estado_migracion = 0;
                $objBeanDetalleMaestro->fecha_migracion = '0000-00-00 00:00:00';
                $objBeanDetalleMaestro->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                $objBeanDetalleMaestroSaved = $this->grupo_detalle_m->saveMaestroDetalle($objBeanDetalleMaestro);
                $detalle_seccion_id = $objBeanDetalleMaestroSaved->id;
            }
            //echo json_encode(array("error" => $returnValue, "detalle_id" => $detalle_seccion_id));
            //lista tipo de maestros
            $items = $this->itemsMaestros($parent_maestro);
            $html = '<input type="hidden" name="maestro_agregado" id="maestro_agregado" value="' . $maestro_id . '" />';
            if (count($items) > 0) {
                foreach ($items as $puntero => $objItem) {
                    $html.='<tr>';
                    $html.='<td>' . ($puntero + 1) . '</td>';
                    $html.='<td><img style="width:120px; height: 70px;" src="' . $objItem->imagen . '" /></td>';
                    $html.='<td>' . $objItem->nombre . '</td>';
                    $html.='<td>' . $objItem->tipo . '</td>';
                    $html.='<td>' . $objItem->fecha_registro . '</td>';
                    $html.='<td>' . $objItem->estado . '</td>';
                    $html.='<td><a href="#" onclick="quitarGrupoMaestro(' . $objItem->grupo_detalle_id . ',' . $parent_maestro . ');return false;" class="btn red">Quitar</a></td>';
                    $html.='<td>' . $objItem->grupo_detalle_id . '</td>';
                    $html.='</tr> ';
                }
            }
            echo $html;
        }
    }

    public function agregarVideoAMaestro() {
        if ($this->input->is_ajax_request()) {
            $user_id = (int) $this->session->userdata('user_id');
            if ($this->videoAgregado($this->input->post('video_id'), $this->input->post('maestro_id'), 0)) {
                $objDetalleMaestro = $this->grupo_detalle_m->get_by(array("video_id" => $this->input->post('video_id'), "grupo_maestro_padre" => $this->input->post('maestro_id')));
                $this->grupo_detalle_m->update($objDetalleMaestro->id, array("estado" => "1", "estado_migracion" => $this->config->item('migracion:actualizado')));
            } else {
                $objMaestro = $this->grupo_maestro_m->get($this->input->post('maestro_id'));
                $objBeanDetalleMaestro = new stdClass();
                $objBeanDetalleMaestro->id = NULL;
                $objBeanDetalleMaestro->grupo_maestro_padre = $this->input->post('maestro_id');
                $objBeanDetalleMaestro->grupo_maestro_id = NULL;
                $objBeanDetalleMaestro->video_id = $this->input->post('video_id');
                $objBeanDetalleMaestro->tipo_grupo_maestros_id = $objMaestro->tipo_grupo_maestro_id;
                $objBeanDetalleMaestro->id_mongo = NULL;
                $objBeanDetalleMaestro->estado = 1;
                $objBeanDetalleMaestro->fecha_registro = date("Y-m-d H:i:s");
                $objBeanDetalleMaestro->usuario_registro = $user_id;
                $objBeanDetalleMaestro->fecha_actualizacion = date("Y-m-d H:i:s");
                $objBeanDetalleMaestro->usuario_actualizacion = $user_id;
                $objBeanDetalleMaestro->estado_migracion = 0;
                $objBeanDetalleMaestro->fecha_migracion = '0000-00-00 00:00:00';
                $objBeanDetalleMaestro->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                $this->grupo_detalle_m->saveMaestroDetalle($objBeanDetalleMaestro);
            }
            //lista tipo de maestros
            $items = $this->itemsMaestros($this->input->post('maestro_id'));
            $html = '';
            if (count($items) > 0) {
                foreach ($items as $puntero => $objItem) {
                    $html.='<tr>';
                    $html.='<td>' . ($puntero + 1) . '</td>';
                    $html.='<td><img style="width:120px; height: 70px;" src="' . $objItem->imagen . '" /></td>';
                    $html.='<td>' . $objItem->nombre . '</td>';
                    $html.='<td>' . $objItem->tipo . '</td>';
                    $html.='<td>' . $objItem->fecha_registro . '</td>';
                    $html.='<td>' . $objItem->estado . '</td>';
                    $html.='<td><a href="#" onclick="quitarGrupoMaestro(' . $objItem->grupo_detalle_id . ',' . $this->input->post('maestro_id') . ');return false;" class="btn red">Quitar</a></td>';
                    $html.='<td>' . $objItem->grupo_detalle_id . '</td>';
                    $html.='</tr> ';
                }
            }
            echo $html;
        }
    }

    public function tieneMaestro($video_id) {
        $returnValue = false;
        $listaMaestro = $this->grupo_detalle_m->get_many_by(array("video_id" => $video_id));
        if (count($listaMaestro) > 0) {
            $returnValue = true;
        }
        return $returnValue;
    }

    /**
     * meotodo para verificar si existe un registro del maestro en el detalle de la seccion
     * @param int $maestro_id
     * @param int $seccion_id
     * @return boolean
     */
    public function existeRegistro($maestro_id, $seccion_id, $estado = 1) {
        $returnValue = false;
        $listaDetalleSeccion = $this->detalle_secciones_m->get_many_by(array("secciones_id" => $seccion_id, "grupo_maestros_id" => $maestro_id, "estado" => $estado));
        if (count($listaDetalleSeccion) > 0) {
            $returnValue = true;
        }
        return $returnValue;
    }

    private function maestroAgregado($maestro_id, $parent_maestro, $estado = 1) {
        $returnValue = false;
        $listaDetalleSeccion = $this->grupo_detalle_m->get_many_by(array("grupo_maestro_padre" => $parent_maestro, "grupo_maestro_id" => $maestro_id, "estado" => $estado));
        if (count($listaDetalleSeccion) > 0) {
            $returnValue = true;
        }
        return $returnValue;
    }

    private function videoAgregado($video_id, $maestro_id, $estado = 1) {
        $returnValue = false;
        $listaDetalleSeccion = $this->grupo_detalle_m->get_many_by(array("video_id" => $video_id, "grupo_maestro_padre" => $maestro_id, "estado" => $estado));
        if (count($listaDetalleSeccion) > 0) {
            $returnValue = true;
        }
        return $returnValue;
    }

    public function quitar_grupo_maestro() {
        if ($this->input->is_ajax_request()) {
            $this->grupo_detalle_m->update($this->input->post('grupo_detalle_id'), array("estado" => "0", "estado_migracion" => $this->config->item('migracion:actualizado')));
            //echo json_encode(array("value" => "1"));
            //lista tipo de maestros
            $items = $this->itemsMaestros($this->input->post('parent_maestro'));
            $html = '';
            if (count($items) > 0) {
                foreach ($items as $puntero => $objItem) {
                    $html.='<tr>';
                    $html.='<td>' . ($puntero + 1) . '</td>';
                    $html.='<td><img style="width:120px; height: 70px;" src="' . $objItem->imagen . '" /></td>';
                    $html.='<td>' . $objItem->nombre . '</td>';
                    $html.='<td>' . $objItem->tipo . '</td>';
                    $html.='<td>' . $objItem->fecha_registro . '</td>';
                    $html.='<td>' . $objItem->estado . '</td>';
                    $html.='<td><a href="#" onclick="quitarGrupoMaestro(' . $objItem->grupo_detalle_id . ',' . $this->input->post('parent_maestro') . ');return false;" class="btn red">Quitar</a></td>';
                    $html.='<td>' . $objItem->grupo_detalle_id . '</td>';
                    $html.='</tr> ';
                }
            }
            echo $html;
        }
    }

    public function eliminar_maestro($maestro_id) {
        if ($this->input->is_ajax_request()) {
            $this->grupo_maestro_m->update($maestro_id, array("estado" => $this->config->item('estado:eliminado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
            //eliminamos la portada si es maestro de tipo programa
            $this->cambiarEstadoPortada($maestro_id, $this->config->item('estado:eliminado'));
            echo json_encode(array("value" => "1"));
        }
    }

    public function restablecer_maestro($maestro_id) {
        if ($this->input->is_ajax_request()) {
            $this->grupo_maestro_m->update($maestro_id, array("estado" => $this->config->item('estado:borrador'), "estado_migracion" => $this->config->item('migracion:actualizado')));
            //eliminamos la portada si es maestro de tipo programa
            $this->cambiarEstadoPortada($maestro_id, $this->config->item('estado:borrador'));
            echo json_encode(array("value" => "1"));
        }
    }

    public function publicar_maestro($maestro_id) {
        if ($this->input->is_ajax_request()) {
            $this->grupo_maestro_m->update($maestro_id, array("estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
            //eliminamos la portada si es maestro de tipo programa
            $this->cambiarEstadoPortada($maestro_id, $this->config->item('estado:publicado'));
            echo json_encode(array("value" => "1"));
        }
    }

    public function eliminar_video($video_id) {
        if ($this->input->is_ajax_request()) {
            $this->videos_m->update($video_id, array("estado" => $this->config->item('estado:eliminado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
            //eliminamos la portada si es maestro de tipo programa
            $this->cambiarEstadoPortada($video_id, $this->config->item('estado:eliminado'), 0);
            echo json_encode(array("value" => "1"));
        }
    }

    public function restablecer_video($video_id) {
        if ($this->input->is_ajax_request()) {
            $this->videos_m->update($video_id, array("estado" => $this->config->item('estado:borrador'), "estado_migracion" => $this->config->item('migracion:actualizado')));
            //eliminamos la portada si es maestro de tipo programa
            $this->cambiarEstadoPortada($video_id, $this->config->item('estado:borrador'), 0);
            echo json_encode(array("value" => "1"));
        }
    }

    public function publicar_video($video_id) {
        if ($this->input->is_ajax_request()) {
            $this->videos_m->update($video_id, array("estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
            //eliminamos la portada si es maestro de tipo programa
            $this->cambiarEstadoPortada($video_id, $this->config->item('estado:publicado'), 0);
            echo json_encode(array("value" => "1"));
        }
    }

    private function cambiarEstadoPortada($maestro_id, $estado, $es_maestro = 1) {
        if ($es_maestro == '1') {
            $objMaestro = $this->grupo_maestro_m->get($maestro_id);
            //verificamos que sea un programa
            if ($objMaestro->tipo_grupo_maestro_id == $this->config->item('videos:programa')) {
                //obtenemos la portada del programa
                $objPortadaPrograma = $this->portada_m->get_by(array("tipo_portadas_id" => $this->config->item('portada:programa'), "origen_id" => $maestro_id));
                if (count($objPortadaPrograma) > 0) {
                    $this->portada_m->update($objPortadaPrograma->id, array("estado" => $estado, "estado_migracion" => $this->config->item('migracion:actualizado')));
                }
            }
        }
        if ($es_maestro == '1') {
            //recorrer todos los detalles de las secciones para cambiar de estado en cascada de abajo hacia arriba
            $detalle_secciones = $this->detalle_secciones_m->get_many_by(array("grupo_maestros_id" => $maestro_id));
        } else {
            //recorrer todos los detalles de las secciones para cambiar de estado en cascada de abajo hacia arriba
            $detalle_secciones = $this->detalle_secciones_m->get_many_by(array("videos_id" => $maestro_id));
        }
        if (count($detalle_secciones) > 0) {
            foreach ($detalle_secciones as $puntero => $objDetalleSeccion) {
                $this->detalle_secciones_m->update($objDetalleSeccion->id, array("estado" => $estado, "estado_migracion" => $this->config->item('migracion:actualizado')));
                //verificamos si la sección tiene su detalle en estados no publicados para cambiar su estado
                $items_de_seccion = $this->detalle_secciones_m->get_many_by(array("secciones_id" => $objDetalleSeccion->id));
                $existe_publicado = false;
                if (count($items_de_seccion) > 0) {
                    foreach ($items_de_seccion as $index => $detalleItem) {
                        if ($detalleItem->estado == $this->config->item('estado:publicado')) {
                            $existe_publicado = true;
                        }
                    }
                }
                if (!$existe_publicado) {
                    //cambiamos de estado a la seccion a estado Borrador
                    $this->secciones_m->update($objDetalleSeccion->id, array("estado" => $this->config->item('estado:borrador'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                    //verificamos si la portada de tipo canal tiene las secciones en modo borrador
                    $objSeccion = $this->secciones_m->get($objDetalleSeccion->id);
                    if (count($objSeccion) > 0) {
                        $objPortada = $this->portada_m->get_by(array("id" => $objSeccion->portadas_id));
                        if (count($objPortada) > 0) {
                            $detalle_portada = $this->secciones_m->get_many_by(array("portadas_id" => $objPortada->id));
                            $existe_publicado_seccion = false;
                            if (count($detalle_portada) > 0) {
                                foreach ($detalle_portada as $indice => $objSecciones) {
                                    if ($objSecciones->estado == $this->config->item('estado:publicado')) {
                                        $existe_publicado_seccion = true;
                                    }
                                }
                            }
                            //verificamos el resultado de la variable que indica si existe alguna seccion activa
                            if (!$existe_publicado_seccion) {
                                $this->portada_m->update($objPortada->id, array("estado" => $this->config->item('estado:borrador'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                            }
                        }
                    }
                }
            }
        }
    }

    public function formulario_restaurar_imagen($maestro_id, $tipo_imagen) {
        if ($this->input->is_ajax_request()) {
            $html = '<table><tr>';
            $html.='<th>#</th>';
            $html.='<th>Imagen</th>';
            $html.='<th>Acción</th>';
            $html.='<th>ID</th>';
            $html.='</tr>';
            $imagenes_resturar = $this->imagen_m->get_many_by(array("grupo_maestros_id" => $maestro_id, "tipo_imagen_id" => $tipo_imagen, "estado" => $this->config->item('estado:borrador')));
            if (count($imagenes_resturar) > 0) {
                foreach ($imagenes_resturar as $puntero => $objImagen) {
                    if ($objImagen->procedencia == '1') {
                        $imagen = $objImagen->imagen;
                    } else {
                        $imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagen->imagen;
                    }
                    $html.='<tr>';
                    $html.='<td>' . ($puntero + 1) . '</td>';
                    $html.='<td><img style="width:100px; height:70px;" src="' . $imagen . '" /></td>';
                    $html.='<td><div class="btn blue" onclick="restaurar_imagen_grupo(' . $objImagen->id . ',' . $tipo_imagen . ', ' . $maestro_id . ');return false;">Restaurar</div></td>';
                    $html.='<td>' . $objImagen->id . '</td>';
                    $html.='</tr>';
                }
            } else {
                $html.='<tr>';
                $html.='<td colspan="4">No hay imagenes</td>';
                $html.='</tr>';
            }
            $html.='</table>';
            echo $html;
        }
    }

    public function restaurar_imagen_grupo($imagen_id, $tipo_imagen_id, $maestro_id) {
        if ($this->input->is_ajax_request()) {
            $this->imagen_m->desabilitarImagenes($maestro_id, $tipo_imagen_id);
            $this->imagen_m->update($imagen_id, array("estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
            $objImagen = $this->imagen_m->get($imagen_id);
            if ($objImagen->procedencia == '1') {
                $imagen = $objImagen->imagen;
            } else {
                $imagen = $this->config->item('protocolo:http') . $this->config->item('server:elemento') . '/' . $objImagen->imagen;
            }
            echo json_encode(array("url" => $imagen, "imagen_id" => $imagen_id));
        }
    }

}

