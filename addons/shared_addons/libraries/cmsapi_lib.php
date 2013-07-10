<?php
/**
 * Complements cmsapi
 *
 * @author Joe Robles <joe.robles.pdj@gmail.com>
 */
class cmsapi_lib extends MX_Controller {
    
    function __construct() {
        $this->load->model("categoria_mp");
        $this->load->model("grupo_maestros_mp");
        $this->load->model("canal_mp");
        $this->load->model("grupo_detalle_mp");
        $this->load->model("videos_mp");
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
                    
                    $return = "";
                } else {
                    $return = "Error al subir archivo.";
                }
            } else {
                $return = "Archivo supera el tamaño permitido de 2GB.";
            }
        } else {
            $return = "Formato de archivo no permitido: mp4,mpg,flv,avi,wmv";
        }
        
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode(array('message' => $return));
    }
    
    private function moveUploaded($files)
    {
        $idUniq = uniqid();
        $ext = @end(explode('.', $files['video']['name']));
        $nameVideo = $idUniq . '.' . $ext;
        umask(0);
        move_uploaded_file($files["video"]["tmp_name"], "uploads/videos/" . $nameVideo);
        '<input type="hidden" id="name_file_upload" name="name_file_upload" value="'.$nameVideo.'" />';
        
        return $nameVideo;
    }
}