<?php

class Admin extends Admin_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->model('canales/canales_m');
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
        $path_video_new = FCPATH . 'uploads/videos/' . $objBeanVideo->id . '.'.$this->config->item('videos:extension');// . $ext;
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
        }
    }
    
    public function getParentTop($grupo_maestro_padre){
        $objMaestro = $this->grupo_maestro_m->get($grupo_maestro_padre);
        if(count($objMaestro)>0){
            if($objMaestro->tipo_grupo_maestro_id == $this->config->item('videos:programa')){
                return $objMaestro;
            }else{
                $objMaestroDetalle = $this->grupo_detalle_m->get_by(array("grupo_maestro_id"=>$objMaestro->id));
                return $this->getParentTop($objMaestroDetalle->grupo_maestro_padre);
            }
        }else{
            return NULL;
        }
    }
    
    public function getIdMaestro($grupo_maestro_padre, $type){
        $objMaestro = $this->grupo_maestro_m->get($grupo_maestro_padre);
        if(count($objMaestro)>0){
            if($objMaestro->tipo_grupo_maestro_id == $type){
                return $objMaestro->id;
            }else{
                $objMaestroDetalle = $this->grupo_detalle_m->get_by(array("grupo_maestro_id"=>$objMaestro->id));
                if(count($objMaestroDetalle)>0){
                    return $this->getIdMaestro($objMaestroDetalle->grupo_maestro_padre, $type);
                }else{
                    return 0;
                }
            }
        }else{
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
                /*switch ($objMaestro->tipo_grupo_maestro_id) {
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
                }*/
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
            $objBeanForm->fec_pub_ini = date("d-m-Y H:i:s", strtotime($objVideo->fecha_publicacion_inicio));//$objVideo->fecha_publicacion_inicio;
            $objBeanForm->fec_pub_fin = date("d-m-Y H:i:s", strtotime($objVideo->fecha_publicacion_fin));//$objVideo->fecha_publicacion_fin;
            $objBeanForm->fec_trans = date("d-m-Y", strtotime($objVideo->fecha_transmision));//$objVideo->fecha_transmision;
            $objBeanForm->hora_trans_ini = $objVideo->horario_transmision_inicio;
            $objBeanForm->hora_trans_fin = $objVideo->horario_transmision_fin;
            $objBeanForm->ubicacion = $objVideo->ubicacion;
            $objBeanForm->canal_id = $canal_id;
            $objBeanForm->tipo_maestro = '';
            $objBeanForm->keywords = '';
            $objBeanForm->error = $error;
            $objBeanForm->message = $message;
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
        $arrayCategory = $this->categoria_m->getCategoryDropDown(array("categorias_id"=>"0"), 'nombre');
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
                    if(count($arrayColeccionVideo)>0){
                        if ($coleccion > 0) {
                            $arrayList = $this->getChildMaestro($coleccion, true, $this->config->item('videos:lista'));
                        } else {
                            //$arrayList = array(lang('videos:select_list'));
                            $arrayList = $this->getChildMaestro($programa, true, $this->config->item('videos:lista'));
                        }                        
                    }else{
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
                $arrayColeccionVideo = $this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:coleccion'), 'canales_id' => $canal_id), 'nombre');
                $arrayList = $this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:lista'), 'canales_id' => $canal_id), 'nombre');
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
    
    public function getChild($programa, $coleccion, $lista, $type, $dropdown = false){
        
    }
    
    public function getChildMaestro($parent_maestro, $dropdown = false, $type) {
        $returnValue = array();
        $arrayCollection = $this->grupo_detalle_m->getGrupoDetalle(array("grupo_maestro_padre" => $parent_maestro));
        if (count($arrayCollection) > 0) {
            $array_id_maestro = array();
            foreach ($arrayCollection as $index => $objCollection) {
                if ($objCollection->grupo_maestro_id != NULL) {
                    if($this->isType($objCollection->grupo_maestro_id, $type)){
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
    
    public function isType($grupo_maestro_id, $type){
        $returnValue = false;
        $objMaestro = $this->grupo_maestro_m->get($grupo_maestro_id);
        if($objMaestro->tipo_grupo_maestro_id == $type){
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
        $objCollectionTag = $this->tags_m->getTagsByType($this->input->get('term'),$this->config->item('tag:tematicas'));
        echo json_encode($objCollectionTag);
        /*echo json_encode(
                $this->tags_m->select('nombre value')
                        ->like('nombre', $this->input->get('term'))
                        ->get_all(array("tipo_tags_id" => $this->config->item('tag:tematicas')))
        );*/
    }

    public function personajes() {
        $objCollectionTag = $this->tags_m->getTagsByType($this->input->get('term'),$this->config->item('tag:personajes'));
        /*echo json_encode(
                $this->tags_m->select('nombre value')
                        ->like('nombre', $this->input->get('term'))
                        ->get_by(array("tipo_tags_id" => $this->config->item('tag:personajes')))
        );*/
        echo json_encode($objCollectionTag);
    }

    public function _getListImagen($video_id, $json= true) {
        //$this->config->load('videos/uploads');
        $arrayImagenBorrador = $this->imagen_m->getImagen(array("tipo_imagen_id" => $this->config->item('imagen:small'), "videos_id" => $video_id, "estado" => $this->config->item('imagen:borrador')), NULL);
        $arrayImagenPublicado = $this->imagen_m->getImagen(array("tipo_imagen_id" => $this->config->item('imagen:small'), "videos_id" => $video_id, "estado" => $this->config->item('imagen:publicado')), NULL);
        $returnArray = array();
        if (count($arrayImagenBorrador) > 0) {
            foreach ($arrayImagenBorrador as $index => $objImagenBorrador) {
                $objImagenBorrador->path = $this->config->item('protocolo:http').$this->config->item('server:elemento').'/'. $objImagenBorrador->imagen; //.$objImagenBorrador->imagen;
                array_push($returnArray, $objImagenBorrador);
            }
        }
        if (count($arrayImagenPublicado) > 0) {
            foreach ($arrayImagenPublicado as $indice => $objImagenPublicado) {
                $objImagenPublicado->path = $this->config->item('protocolo:http').$this->config->item('server:elemento').'/'. $objImagenPublicado->imagen; //.$objImagenPublicado->imagen;
                array_push($returnArray, $objImagenPublicado);
            }
        }
        
        if($json){
            //formato para json
            if(count($returnArray)>0){
                $arreglo = array();
                foreach($returnArray as $in=>$objImg){
                    $arrayImg['text'] = '';
                    $arrayImg['value'] = $objImg->id;
                    if($objImg->estado == "0"){
                        $arrayImg['selected'] = false;
                    }else{
                        if($objImg->estado == "1"){
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
            if (false/* $this->grupo_maestro_m->existNameMaestro($nombre_maestro, $this->input->post('canal_id')) */) {
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
                $objBeanMaestro->categorias_id = NULL;//$this->input->post('categoria');
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
                $objBeanMaestro = $this->grupo_maestro_m->save_maestro($objBeanMaestro);
                //guardar en el detalle de maestros en caso de guardarse como hijo
                $this->_saveMaestroDetalle($this->input->post(), $objBeanMaestro);
                $returnValue = array();
                $returnValue[$objBeanMaestro->id] = $objBeanMaestro->nombre;
                $returnValue['error'] = 0;
            }
            echo(json_encode($returnValue));
        }
    }

    public function _saveMaestroDetalle($post, $objBeanMaestro) {
        $user_id = (int) $this->session->userdata('user_id');
        $objBeanMaestroDetalle = new stdClass();
        $objBeanMaestroDetalle->id = NULL;
        $objBeanMaestroDetalle->video_id = NULL;
        $objBeanMaestroDetalle->id_mongo = NULL;
        $objBeanMaestroDetalle->estado = 0;
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
                            if($this->isType($objCollection->grupo_maestro_id, $this->config->item('videos:coleccion'))){
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
                            $returnValue[$objMaestro->id] = $objMaestro->nombre;
                        }
                    } else {
                        $returnValue['error'] = 1; // 1 => no hay datos a mostrar
                    }
                } else {
                    $returnValue['error'] = 1; // 1 => no hay datos a mostrar
                }
            } else {
                $returnValue = $this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:coleccion'), 'canales_id' => $this->input->post('canal_id')), 'nombre');
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
                                if($this->isType($objCollection->grupo_maestro_id, $this->config->item('videos:lista'))){
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
                    if ($this->input->post('programa') > 0) {
                        $arrayCollection = $this->grupo_detalle_m->getGrupoDetalle(array("grupo_maestro_padre" => $this->input->post('programa')));
                        $returnValue = array();
                        $returnValue[0] = lang('videos:select_list');
                        $returnValue['error'] = 0;
                        if (count($arrayCollection) > 0) {
                            $array_id_maestro = array();
                            foreach ($arrayCollection as $index => $objCollection) {
                                if ($objCollection->grupo_maestro_id != NULL) {
                                    if($this->isType($objCollection->grupo_maestro_id, $this->config->item('videos:lista'))){
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
                    }else{
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
                                if($this->isType($objCollection->grupo_maestro_id, $this->config->item('videos:lista'))){
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
                    $returnValue = $this->grupo_maestro_m->getCollectionDropDown(array('tipo_grupo_maestro_id' => $this->config->item('videos:lista'), 'canales_id' => $this->input->post('canal_id')), 'nombre');
                }
            }

            //$this->vd($returnValue);die();
            echo(json_encode($returnValue));
        }
    }

    public function subir_imagen() {
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
                    if (move_uploaded_file($_FILES['userfile']['tmp_name'], UPLOAD_IMAGENES_VIDEOS . $imgFile)) {
                        //usamos el crop de imagemagic para crear las 4 imagenes
                        $arrayTipoImagen = $this->tipo_imagen_m->listType();
                        $width = $imageSize[0];
                        $height = $imageSize[1];
                        if (count($arrayTipoImagen) > 0) {
                            foreach ($arrayTipoImagen as $index => $objTipoImagen) {
                                if ($width >= $objTipoImagen->ancho && $height >= $objTipoImagen->alto) {
                                    $this->imagenes_lib->loadImage(UPLOAD_IMAGENES_VIDEOS . $imgFile);
                                    $this->imagenes_lib->crop($objTipoImagen->ancho, $objTipoImagen->alto, 'center');
                                    $this->imagenes_lib->save(UPLOAD_IMAGENES_VIDEOS . preg_replace("/\\.[^.\\s]{3,4}$/", "", $imgFile) . '_' . $objTipoImagen->ancho . 'x' . $objTipoImagen->alto . '.' . $extension[$num]);
                                    array_push($arrayImagenes, preg_replace("/\\.[^.\\s]{3,4}$/", "", $imgFile) . '_' . $objTipoImagen->ancho . 'x' . $objTipoImagen->alto . '.' . $extension[$num]);
                                }
                            }
                        }
                        //eliminamos el archivo madre
                        /*if (file_exists(UPLOAD_IMAGENES_VIDEOS . $imgFile)) {
                            unlink(UPLOAD_IMAGENES_VIDEOS . $imgFile);
                        }*/
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
    
    public function _saveParentImage($canal_id, $video_id,$parentImage){
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
        $objBeanImage = $this->imagen_m->saveImage($objBeanImage);
        return $objBeanImage->id;
    }

    public function registrar_imagenes($canal_id, $video_id) {
        if ($this->input->is_ajax_request()) {
            $returnValue = 0;
            $arrayImagenes = $this->input->post('imagenes');
            //eliminamos la imagen original
            if(file_exists(UPLOAD_IMAGENES_VIDEOS.$this->input->post('fileName'))){
                unlink(UPLOAD_IMAGENES_VIDEOS.$this->input->post('fileName'));
            }
            $parent_id = NULL;//$this->_saveParentImage($canal_id, $video_id,$this->input->post('fileName'));
            if (count($arrayImagenes) > 0) {
                foreach ($arrayImagenes as $index => $nameImage) {
                    $img_path = UPLOAD_IMAGENES_VIDEOS . $nameImage;
                    $ruta_absoluta_imagen = FCPATH.'uploads/imagenes/'.$nameImage;
                    if (file_exists($img_path)) {
                        $grupo_maestro_id = NULL;
                        $objGrupoDetalle = $this->grupo_detalle_m->get_by(array("video_id" => $video_id));
                        if(count($objGrupoDetalle)>0){
                            $grupo_maestro_id = $objGrupoDetalle->grupo_maestro_padre;
                        }
                        $user_id = (int) $this->session->userdata('user_id');
                        $objBeanImage = new stdClass();
                        $objBeanImage->id = NULL;
                        $objBeanImage->canales_id = NULL;//$canal_id;
                        $objBeanImage->grupo_maestros_id = NULL;//$grupo_maestro_id;
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
                        $objBeanImage = $this->imagen_m->saveImage($objBeanImage);
                        //enviamos al servidor elemento

                        $path_image_element = $this->elemento_upload($objBeanImage->id, $ruta_absoluta_imagen);
                        $array_path = explode("/", $path_image_element);
                        if($array_path[0] == $this->config->item('server:elemento')){
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
            echo json_encode(array('respuesta' => $returnValue, 'video_id' => $video_id, 'imagen_id' => $imagen_id_small, 'url' => $this->config->item('protocolo:http').$nameImage_small, 'imagenes'=>$arrayImagenes));
        }
    }

    public function _getTypeImage($img_path) {
        $imageSize = getimagesize($img_path);
        $width = $imageSize[0];
        $height = $imageSize[1];
        $tipoImagen = $this->tipo_imagen_m->get_by(array("alto" => $height, "ancho" => $width));
        return $tipoImagen->id;
    }

    public function updateVideo($canal_id, $video_id) {
        if ($this->input->is_ajax_request()) {
            $user_id = (int) $this->session->userdata('user_id');
            $objBeanVideo = new stdClass();
            $objBeanVideo->id = $video_id;
            $objBeanVideo->tipo_videos_id = $this->input->post('tipo');
            $objBeanVideo->categorias_id = $this->input->post('categoria');
            $objBeanVideo->usuarios_id = $user_id;
            $objBeanVideo->canales_id = $this->input->post('canal_id');
            //$objBeanVideo->fuente = $this->input->post('fuente');
            $objBeanVideo->titulo = $this->input->post('titulo');
            $objBeanVideo->alias = url_title(strtolower(convert_accented_characters($this->input->post('titulo'))));
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
            echo json_encode(array("value" => '1'));
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
    
    public function active_imagen($canal_id, $video_id, $imagen_id){
        $this->imagen_m->desactivarImagenes($imagen_id,$video_id);
        if($this->imagen_m->tieneHijos($imagen_id)){
            $coleccionHijos = $this->imagen_m->getImagen(array("imagen_padre"=>$imagen_id));
            foreach($coleccionHijos as $index=> $objImg){
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
        
        echo json_encode(array("respuesta"=>"1"));
    }
    
    public function verificarVideo($canal_id, $video_id){
        $returnValue = false;
        /*$video_id = $this->input->post('video_id');*/
        $id_type = 0;
        if($this->input->post('lista') > 0 ){
            $id_type = $this->input->post('lista');
        }else{
            if($this->input->post('coleccion') > 0){
                $id_type = $this->input->post('coleccion');
            }else{
                if($this->input->post('programa') > 0){
                    $id_type = $this->input->post('programa');
                }
            }
        }
        if( $this->input->post('lista') == 0 && $this->input->post('coleccion') == 0 && $this->input->post('programa') == 0){
            if($this->videos_m->existVideo($this->input->post('titulo'), $canal_id, $video_id)){
                $returnValue = true;
            }
        }else{
            if($id_type > 0){
                $objCollectionDetalle = $this->grupo_detalle_m->getGrupoDetalle(array("grupo_maestro_padre"=>$id_type));
                if(count($objCollectionDetalle)>0){
                    foreach ($objCollectionDetalle as $index=>$objDetalle){
                        if($objDetalle->video_id != NULL){
                            if($this->videos_m->existVideo($this->input->post('titulo'), $canal_id, $objDetalle->video_id, $video_id, $id_type)){
                                $returnValue = true;
                                break;
                            }
                        }
                    }
                }else{
                    $returnValue = false;
                }
            }else{
                 $returnValue = false;
            }
        }
        if($returnValue){
            echo json_encode(array("errorValue"=>"1"));
        }else{
            echo json_encode(array("errorValue"=>"0"));
        }
    }


}