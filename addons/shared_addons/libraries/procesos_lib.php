<?php

set_time_limit(TIME_LIMIT);

class Procesos_lib extends MX_Controller {

    public function __construct() {
        //parent::__construct();
        //$this->database();        
        //$this->load->model('models/proceso_m');

        $this->config->load('sphinx/config');

        $this->load->model('micanal_mp');
        $this->load->model('canal_mp');
        $this->load->model('version_mp');
        $this->load->model('canales_mp');
        $this->load->model('videos_mp');
        $this->load->model('imagenes_mp');
        $this->load->model('secciones_mp');
        $this->load->model('portadas_mp');
        $this->load->model('video_tags_mp');
        $this->load->model('grupo_maestros_mp');
        $this->load->model('tipo_imagenes_mp');

        $this->load->model('sphinx/sphinx_m');

        $this->load->library("Procesos/proceso");
        $this->load->library("Procesos/liquid");
        $this->load->library("Procesos/ffmpeg");
        $this->load->library("Procesos/youtube");
        $this->load->library("Procesos/america");

        $this->load->library("Procesos/log");
        $this->load->library('portadas_lib');
        $this->load->library('sincronizar_lib');

        $this->load->helper('file');
        $this->load->helper('manejo_caracteres');
    }

    public function index() {
        
    }

    /* Corte video  -  INICIO */

    public function curlCorteVideoXId($id_padre, $id_hijo, $inicio, $duracion) {
        Log::erroLog("ini - curlCorteVideo: " . $id_padre . ", hijo " . $id_hijo);
        $ruta = base_url("curlproceso/corteVideoXId/" . $id_padre . "/" . $id_hijo . "/" . $inicio . "/" . $duracion);
        shell_exec("curl " . $ruta . " > /dev/null 2>/dev/null &");
        Log::erroLog("fin - curlCorteVideo: " . $id_padre . ", hijo " . $id_hijo);
    }

    public function corteVideoXId($id_padre, $id_hijo, $inicio, $duracion) {
        Log::erroLog("ini - curlCorteVideo: " . $id_padre . ", hijo " . $id_hijo . ", inicio " . $inicio . ", duracion " . $duracion);

        $result = $this->videos_mp->getVideosxId($id_padre);

        Log::erroLog("ini - curlCorteVideo: " . $id_padre . ", hijo " . $id_hijo . ", inicio " . $inicio . ", duracion " . $duracion);

        if (!empty($id_padre) && !empty($id_hijo) && !empty($inicio) && !empty($duracion)) {
            Log::erroLog("downloadVideo");
            if (Ffmpeg::downloadVideo($result[0]->id, (!empty($result[0]->rutasplitter)) ? $result[0]->rutasplitter : $result[0]->ruta)) {
                Log::erroLog("splitVideo");
                if (Ffmpeg::splitVideo($id_padre, $id_hijo, $inicio, $duracion)) {
                    Log::erroLog("curlProcesoVideosXId");
                    $this->curlProcesoVideosXId($id_hijo);
                }
            }
        } else {
            return FALSE;
        }
    }

    /* Corte video  -  Fin */

    /* Actualizar Visualizaciones Liquid  -  INICIO */

    private function _actualizarVisualizacionAnt() {

        $arrcanales = $this->canales_mp->getCanales();

        foreach ($arrcanales as $value) {

            $arrayViews = Liquid::obtenernumberOfViews($value->apikey);
            foreach ($arrayViews as $value) {
                //print_r($value);
                //$this->videos_mp->setReproducciones($value["id"], $value["numberOfViews"]);
                $this->_curlSetReproducciones($value["id"], $value["numberOfViews"]);
            }
        }
    }

    private function _actualizarVisualizacion() {
        $arrvideos = $this->videos_mp->getVideosActivosPublicadosUlt7Dias();

        foreach ($arrvideos as $value) {
            $cantidad = Liquid::obtenernumberOfViewsXVideo($value->codigo, $value->apikey);
            Log::erroLog("_actualizarVisualizacion: " . $value->id . " - " . $value->id_mongo . " - " . $cantidad);

            if ($cantidad != $value->reproducciones) {
                $this->setReproduccionesVideosXId($value->id, $value->id_mongo, $cantidad);
            }
        }
    }

    /* Actualizar Visualizaciones Liquid  -  FIN */

    private function _curlSetReproducciones($id, $id_mongo, $cant) {
        Log::erroLog("_curlSetReproducciones: " . $id . ", cant: " . $cant);
        $ruta = base_url("curlproceso/setReproduccionesVideosXId/" . $id . "/" . $id_mongo . "/" . $cant);
        shell_exec("curl " . $ruta . " > /dev/null 2>/dev/null &");
    }

    public function setReproduccionesVideosXId($id, $id_mongo, $cant) {
        $this->videos_mp->setReproduccionesVideosXId($id, $cant);
        $this->_setReproduccionesMongoVideosXIdMongo($id_mongo, $cant);
    }

    private function _setReproduccionesMongoVideosXIdMongo($id_mongo, $cant) {
        Log::erroLog("entro a _setReproduccionesMongoVideosXIdMongo");
        $id_mongo = new MongoId($id_mongo);
        $objmongo['reproducciones'] = $cant;
        $this->micanal_mp->setItemCollectionUpdate($objmongo, array('_id' => $id_mongo));
    }

    /* Actualizar Comentarios Valoracion   -  INICIO */

    private function _actualizarComentariosValorizacion() {
        $videos = $this->videos_mp->getVideosActivosPublicadosUlt7Dias();
        foreach ($videos as $value) {
            $id_mongo = new MongoId($value->id_mongo);
            $videomongo = $this->canal_mp->getItemCollection($id_mongo);
            $comentarios = (!isset($videomongo[0]["comentarios"])) ? 0 : $videomongo[0]["comentarios"];
            $valoracion = (!isset($videomongo[0]["valoracion"])) ? 0 : $videomongo[0]["valoracion"];
            $this->videos_mp->setComentariosValorizacion($value->id, $comentarios, $valoracion);
        }
    }

    /* Actualizar Comentarios Valoracion -  FIN */

    /* Actualizar comentarios y valorizaciones de Mysql a Mongo */

    /* Subir Videos - INICIO */

    public function curlProcesoVideosXId($id) {
        Log::erroLog("ini - curlProcesoVideosXId: " . $id);
        $ruta = base_url("curlproceso/procesoVideosXId/" . $id);
        shell_exec("curl " . $ruta . " > /dev/null 2>/dev/null &");
        Log::erroLog("fin - curlProcesoVideosXId: " . $id . " ruta " . $ruta);
    }

    public function procesoVideosXId($id) {
        Log::erroLog("id: " . $id);
        Log::erroLog("entro _convertirVideosXId: " . $id);
        $this->_convertirVideosXId($id);
        Log::erroLog("salio _convertirVideosXId " . $id);
//        $this->_uploadVideosXId($id);
        Log::erroLog("entro a curl upload video: " . $id);
        $this->curlUploadVideosXId($id);
        Log::erroLog("salio de curl upload video " . $id);
    }

    public function continuaProcesoVideos($id) {
        $this->_publishVideosXId($id);
        $this->_obtenerImagesUrlVideosXId($id);
        $this->_generarVideosXId($id);
        Log::erroLog("Finalizo proceso para video: " . $id);
    }

    public function curlUploadVideosXId($id) {
        Log::erroLog("entro a : curlUploadVideosXId " . $id);
        $ruta = base_url("curlproceso/uploadVideosXId/" . $id);
        shell_exec("curl " . $ruta . " > /dev/null 2>/dev/null &");
        Log::erroLog("curlUploadVideosXId ruta - " . $ruta);
    }

    public function uploadVideosXId($id) {
        $this->_uploadVideosXId($id);
    }

    public function verificaVideosLiquidXId($id) {

        Log::erroLog("entro a : verificaVideosLiquidXId " . $id);

        $video = $this->videos_mp->getVideosxIdConKey($id);

        Log::erroLog($video[0]->codigo . " " . $id);
        Log::erroLog($video[0]->estado_liquid . " " . $id);
        Log::erroLog("estado_liquid " . $id . " " . $video[0]->estado_liquid);

        if ($this->config->item('v_e:error') != $video[0]->estado) {

            if (empty($video[0]->codigo)) {
                if ($video[0]->estado_liquid == $this->config->item('v_l:codificado')) {
                    Log::erroLog("el video no se cargo me voy a  curlUploadVideosXId " . $id);
                    sleep(45);
                    $this->curlUploadVideosXId($id);
                } elseif ($video[0]->estado_liquid == $this->config->item('v_l:subiendo') || $video[0]->estado_liquid == $this->config->item('v_l:subido')) {
                    Log::erroLog("no hay datos me voy a curlVerificaVideosLiquidXId " . $id);
                    sleep(30);
                    $this->curlVerificaVideosLiquidXId($id);
                }
            } else {
                Log::erroLog("si hay datos me voy a getVerificarLiquidPostUpload");
                if (Liquid::getVerificarLiquidPostUpload($video[0]->codigo, $video[0]->apikey)) {
                    Log::erroLog("al fin algo continuo el publishd " . $id);
                    $this->continuaProcesoVideos($id);
                } else {
                    sleep(30);
                    Log::erroLog("aun sin nada me curlVerificaVideosLiquidXId " . $id);
                    $this->curlVerificaVideosLiquidXId($id);
                }
            }
        }
    }

    public function curlVerificaVideosLiquidXId($id) {
        Log::erroLog("entro a : curlVerificaVideosLiquidXId" . $id);
        $ruta = base_url("curlproceso/verificaVideosLiquidXId/" . $id);
        shell_exec("curl " . $ruta . " > /dev/null 2>/dev/null &");
    }

    protected function _convertirVideosXId($id) {

        Log::erroLog("_convertirVideosXId: " . $id);

        if (!empty($id)) {
            Log::erroLog("video antes de conversion : " . $id . "  ");
            $this->videos_mp->setEstadosVideos($id, $this->config->item('v_e:codificando'), $this->config->item('v_l:codificando'));
            Log::erroLog("video despues de conversion : " . $id . "  ");
            if (Ffmpeg::convertVideotoMp4($id)) {
                Log::erroLog("video: " . $id . "convertido correctamente ");
                $this->videos_mp->setEstadosVideos($id, $this->config->item('v_e:codificando'), $this->config->item('v_l:codificado'));
            } else {
                Log::erroLog("video: " . $id . "no convertido correctamente ");
                $this->videos_mp->setEstadosVideos($id, $this->config->item('v_e:codificando'), -1);
            }
        }
    }

    protected function _uploadVideosXId($id) {
        Log::erroLog("entro a protected uploadVideosXId: " . $id);
        $resultado = $this->videos_mp->getVideosMp4XId($id);
        if (count($resultado) > 0) {
            foreach ($resultado as $value) {

                $this->videos_mp->setEstadosVideos($value->id, $this->config->item('v_e:codificando'), $this->config->item('v_l:subiendo'));
                $this->curlVerificaVideosLiquidXId($id);
                $retorno = Liquid::uploadVideoLiquid($value->id, $value->apikey);
                Log::erroLog("retorno de upload video: " . $retorno);

                if (!empty($retorno)) {
                    Log::erroLog("entro a : updateMediaVideosXId " . $value->id . "/" . $retorno);
                    $ruta = base_url("curlproceso/updateMediaVideosXId/" . $value->id . "/" . $retorno);
                    shell_exec("curl " . $ruta . " > /dev/null 2>/dev/null &");
                    Log::erroLog("return media " . trim($retorno));
                } else {
                    Log::erroLog("exception de upload  getObtenerMediaXId " . $value->id . "," . $value->apikey);

                    $contador = 0;
                    BUSQUEDAMEDIA:

                    sleep(300);
                    $mediaarray = Liquid::getObtenerMediaXId($value->id, $value->apikey);

                    if ($mediaarray != FALSE) {
                        $mediaxml = new SimpleXMLElement($mediaarray);
                        $mediaarr = json_decode(json_encode($mediaxml), TRUE);

                        $media = Liquid::getObtenerMedia($mediaarr, $value->id);

                        Log::erroLog("Media encontrada: " . $media);

                        if (!empty($media)) {
                            Log::erroLog("entro a : updateMediaVideosXId por error" . $value->id . "/" . $media);
                            $ruta = base_url("curlproceso/updateMediaVideosXId/" . $value->id . "/" . $media);
                            shell_exec("curl " . $ruta . " > /dev/null 2>/dev/null &");
                        } else {

                            if ($contador == 10) {
                                //$this->videos_mp->setEstadosVideos($value->id, $this->config->item('v_e:error'), $this->config->item('v_l:subiendo'));
                                $this->curlUpdateEstadoVideosXId($value->id, $this->config->item('v_e:error'), $this->config->item('v_l:subiendo'));
                            } else {
                                $contador++;
                                goto BUSQUEDAMEDIA;
                            }
                        }
                    } else {
                        //$this->videos_mp->setEstadosVideos($value->id, $this->config->item('v_e:error'), $this->config->item('v_l:subiendo'));
                        $this->curlUpdateEstadoVideosXId($value->id, $this->config->item('v_e:error'), $this->config->item('v_l:subiendo'));
                    }
                }
            }
        }
    }

    public function updateMediaVideosXId($id, $media) {
        $this->_updateMediaVideosXId($id, $media);
    }

    protected function _updateMediaVideosXId($id, $media) {
        if ($media != FALSE) {
            Log::erroLog("es diferente de FALSE media: " . $media);
            $this->videos_mp->setEstadosVideos($id, $this->config->item('v_e:codificando'), $this->config->item('v_l:subido'));
            $this->videos_mp->setMediaVideos($id, $media);
        } else {
            Log::erroLog("es FALSE retorno: " . $media);
            $this->videos_mp->setEstadosVideos($id, 0, 2);
        }
    }

    protected function _publishVideosXId($id) {
        $resultado = $this->videos_mp->getVideosNoPublicadosXId($id);
        //echo print_r($resultado) . "\n";
        if (count($resultado) > 0) {
            foreach ($resultado as $value) {
                
                $value->titulo = limpiar_caracteres($value->titulo);
                $value->descripcion = limpiar_caracteres($value->descripcion);

                $retorno = Liquid::updatePublishedMediaNode($value);
                //var_dump($retorno);
                if ($retorno != FALSE) {
                    $this->videos_mp->setEstadosVideos($value->id, $this->config->item('v_e:borrador'), $this->config->item('v_l:activo'));
                }
            }
        }
    }

    protected function _obtenerImagesUrlVideosXId($id) {
        Log::erroLog("entro a _obtenerImagesUrlVideosXId:  " . $id);

        $resultado = $this->videos_mp->getVideosObtenerDatosXId($id);

        if (count($resultado) > 0) {
            foreach ($resultado as $value) {

                $mediaarr = array();

                ////error_log("dentro de " . $value->id);
                if (empty($value->duracion) || empty($value->ruta) || empty($value->rutasplitter) || $value->imag == 0) {
                    Log::erroLog("Duracion :" . $value->duracion);
                    Log::erroLog("Ruta :" . $value->ruta);
                    Log::erroLog("rutasplitter :" . $value->rutasplitter);
                    $mediaarr = Liquid::obtenerDatosMedia($value);
                }

                if (empty($value->duracion)) {
                    $duracion = Liquid::getDurationLiquid($mediaarr);
                    if (!empty($duracion)) {
                        $duracion = ($duracion / 1000);
                        $this->videos_mp->setDuracionVideos($value->id, $duracion);
                    }
                }

                if (empty($value->ruta)) {
                    $urlvideo = Liquid::getUrlVideoLiquidRawLite($mediaarr);
                    if (!empty($urlvideo)) {
                        $this->videos_mp->setRutaVideos($value->id, $urlvideo);
                    }
                }

                if (empty($value->rutasplitter)) {
                    $urlvideo = Liquid::getUrlVideoLiquidRaw($mediaarr);
                    if (!empty($urlvideo)) {
                        $this->videos_mp->setRutaVideosSplitter($value->id, $urlvideo);
                    }
                }

//                $boolpublished = Liquid::getPublished($mediaarr);
//
//                if ($boolpublished) {
//                    Log::erroLog("Liquid retorna publicado para el video " . $id);
//                } elseif (!$boolpublished) {
//                    Log::erroLog("Liquid retorna no publicado para el video " . $id);
//                    $resultado = $this->videos_mp->getVideosNoPublicadosXId($id);
//                    if (count($resultado) > 0) {
//                        foreach ($resultado as $value) {
//                            Liquid::updatePublishedMediaNode($value);
//                        }
//                    }
//                } elseif ($boolpublished == NULL) {
//                    Log::erroLog("Liquid no retorna published para el video " . $id);
//                }

                if ($value->imag == 0) {
                    
                    $tipo_imagenes = $this->tipo_imagenes_mp->getTipoImagenes();

                    $imagenes = Liquid::getimagenesLiquid($mediaarr,$tipo_imagenes);

                    if (count($imagenes) > 0) {
                        $imagenpadre = NULL;

                        $datos = array();

                        $datos["videos_id"] = $value->id;
                        $datos["procedencia"] = 1;
                        $datos["estado"] = ESTADO_ACTIVO;
                        $datos["fecha_registro"] = date('Y-m-d H:i:s');

                        foreach ($imagenes as $value2) {
                            $datos["imagen"] = $value2["url"];
                            $datos["tipo_imagen_id"] = $value2["tipo_imagen_id"];
                            $datos["imagen_padre"] = $imagenpadre;


                            if ($imagenpadre == NULL) {
                                $imagenpadre = $this->imagenes_mp->setImagenVideos($datos);

                                $this->portadas_lib->agregar_imagen_video_lista($id, $imagenpadre);
                                Log::erroLog("id imagen padre: " . $datos["imagen_padre"]);
                            } else {
                                $video_hijo_id = $this->imagenes_mp->setImagenVideos($datos);

                                $this->portadas_lib->agregar_imagen_video_lista($id, $video_hijo_id);
                            }
                        }
                    }
                }

                if ((!empty($value->ruta) || !empty($urlvideo) || !empty($duracion) ) && ($value->imag != 0 || !empty($datos["imagen"]))) {
                    $this->videos_mp->setEstadosVideos($value->id, $this->config->item('v_e:publicado'), $this->config->item('v_l:publicado'));
                    Log::erroLog(" antes de actualizar_video: " . $id);
                    if(isset($value->postback_url) && ($value->procedencia  == $this->config->item('proce:widget'))){
                      Log::erroLog("Enviando actualizacion de estado a canal de video:  " . $id);
                      $this->envioDatos($value->postback_url,$value->id);
                    }
              
                    $this->curlSincronizarLibVideo($value->id);
                }
            }
        }
    }

    public function curlSincronizarLibVideo($id) {
        Log::erroLog("ini - curlSincronizarLibVideo: " . $id);
        $ruta = base_url("curlproceso/sincronizarLibVideo/" . $id);
        shell_exec("curl " . $ruta . " > /dev/null 2>/dev/null &");
    }

    public function sincronizarLibVideo($id) {
        $this->_sincronizarLibVideo($id);
    }

    private function _sincronizarLibVideo($id) {
        $this->sincronizar_lib->agregar_video($id, 'pro');
    }

    /* Subir Videos - INICIO */

    /* MiCanal Mongo - INICIO */

    private function urls_amigables($url) {
        $url = strtolower($url);
        $find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
        $repl = array('a', 'e', 'i', 'o', 'u', 'n');
        $url = str_replace($find, $repl, $url);
        $find = array(' ', '&', '\r\n', '\n', '+');
        $url = str_replace($find, '-', $url);
        $find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
        $repl = array('', '-', '');
        $url = preg_replace($find, $repl, $url);
        return $url;
    }

    public function actualizarPortadasMiCanal() {
        $portadas = $this->portadas_mp->getPortadas();
        foreach ($portadas as $value) {
            $this->_generarPortadasMiCanalXId($value->id);
        }
    }

    public function actualizarPortadasMiCanalXId($id) {
        Log::erroLog("id: " . $id);
        $this->_generarPortadasMiCanalXId($id);
        Log::erroLog("paso: " . $id);
    }

    public function generarPortadasMiCanalXId($id) {
        $this->_generarPortadasMiCanalXId($id);
    }

    public function curlGenerarPortadasMiCanalXId($id) {
        Log::erroLog("ini - curlGenerarCanalesXId: " . $id);
        $ruta = base_url("curlproceso/generarPortadasMiCanalXId/" . $id);
        shell_exec("curl " . $ruta . " > /dev/null 2>/dev/null &");
    }

    private function _generarPortadasMiCanalXId($id) {
        $resquery = $this->portadas_mp->getPortadasXId($id);

        if (count($resquery) > 0) {

            foreach ($resquery as $value) {
                Log::erroLog($value->estado_migracion . "  -  " . $value->estado);

//                if ($value->estado == 1) {

                    $array = array();

                    $array["tipo"] = "portada";
                    $array["nombre"] = ($value->nombre);                    
                    $array["estado"] = ($value->estado == ESTADO_ACTIVO) ? ESTADO_ACTIVO : '0';


                    $resquery2 = $this->micanal_mp->queryMysqlTipoPortadas($value->tipo_portadas_id, $value->origen_id);

                    if (count($resquery2) > 0) {

                        $row2 = $resquery2;

                        if (isset($row2->nombre)) {
                            $array["canal"] = ($row2[0]->nombre);
                        } else {
                            $array["canal"] = "";
                        }

                        $array["tipo_portadas_id"] = $value->tipo_portadas_id;


                        switch ($value->tipo_portadas_id) {
                            case '1':
                                $array["canal"] = ($row2[0]->nombre);
                                $array["alias"] = "";
                                break;

                            case '2':
                                $array["canal"] = ($row2[0]->nombre);
                                $array["alias"] = $row2[0]->alias;
                                break;

                            case '4':
                                $array["id_pro"] = $row2[0]->id;
                                $array["canal"] = ($row2[0]->nombre);
                                $array["alias"] = $row2[0]->alias;
                                $array["categoria"] = $row2[0]->alias_ca;
                                $array["programa"] = ($row2[0]->nombre);
                                $array["descripcion"] = ($row2[0]->descripcion);
                                break;

                            case '5':
                                $array["canal"] = ($row2[0]->nombre);
                                $array["alias"] = $row2[0]->alias;
                                $array["canal_des"] = ($row2[0]->descripcion);
                                $array["canal_cv"] = ($row2[0]->canal_cv);

                                break;
                        }
                    }

                    $objmongo = $array;


//                    if ($value->estado == 1) {
                    if (!($this->micanal_mp->existe_id_mongo($value->id_mongo))) {
                        $id_mongo = $this->micanal_mp->setItemCollection($objmongo);
                        $this->micanal_mp->updateIdMongoPortadas($value->id, $id_mongo);
                        $this->micanal_mp->updateEstadoMigracionPortadas($value->id);
                    } else {//if ($value->estado_migracion == 9) {
                        $id_mongo = new MongoId($value->id_mongo);
                        Log::erroLog("entro a actualizar");
                        $this->micanal_mp->setItemCollectionUpdate($objmongo, array('_id' => $id_mongo));
                        $this->micanal_mp->updateEstadoMigracionPortadasActualizacion($value->id);
                    }

                    $this->_generarSeccionesMiCanalXPortadaXId($id);

//                    }
                    unset($objmongo);
                    unset($array);
//                } elseif ($value->estado == 0 || $value->estado == 2) {
//                    //eliminacion item en coleccion micanal 
//                    if (!empty($value->id_mongo)) {
//                        $id_mongo = new MongoId($value->id_mongo);
//                        $this->micanal_mp->setItemCollectionUpdate(array("estado" => "0"), array('_id' => $id_mongo));
//                        //$this->micanal_mp->SetItemCollectionDelete(array('_id' => $id_mongo));
//                        $this->micanal_mp->updateEstadoMigracionPortadasActualizacion($value->id);
//                    }
//                }
            }
        }
    }

    private function _generarSeccionesMiCanalXPortadaXId($id) {

        $secciones = $this->secciones_mp->getSeccionesXPortadaId($id);
        foreach ($secciones as $value) {
            $this->_generarSeccionesMiCanalXSeccionId($value->id);
        }
    }

    public function actualizarSeccionesXId($id) {
        $this->_generarSeccionesMiCanalXSeccionId($id);
    }

    public function curlGenerarSeccionesMiCanalXSeccionId($id) {
        Log::erroLog("ini - curlGenerarSeccionesMiCanalXSeccionId: " . $id);
        $ruta = base_url("curlproceso/generarSeccionesMiCanalXSeccionId/" . $id);
        shell_exec("curl " . $ruta . " > /dev/null 2>/dev/null &");
    }

    public function generarSeccionesMiCanalXSeccionId($id) {
        Log::erroLog("private curlGenerarSeccionesMiCanalXSeccionId: " . $id);
        $this->_generarSeccionesMiCanalXSeccionId($id);
    }

    private function _generarSeccionesMiCanalXSeccionId($id) {

        $array = array();

        $resquery = $this->secciones_mp->getSeccionesXId($id);
        Log::erroLog("dato entrada    : " . $id . " -- cantidad   : " . count($resquery) . " -- ");

        if (count($resquery) != 0) {

            foreach ($resquery as $value) {

                if ($value->estado == 1) {

                    $array["tipo"] = "seccion";
                    $array["nombre"] = $value->nombre;
                    $array["peso"] = $value->peso;
                    $array["template"] = $value->templates_id;
                    $array["tipo_portadas_id"] = $value->tipo_portadas_id;
                    $array["tipo_secciones_id"] = $value->tipo_secciones_id;

                    $array["padre"] = $value->mongo_po;
                    $array["alias_pa"] = $value->alias_pa;
                    $array["alias_se"] = $this->urls_amigables($value->nombre);
                    $array["estado"] = "1";

                    if ($value->tipo_portadas_id == 5 and $value->tipo_secciones_id == 1) {

                        $datos2 = $this->micanal_mp->queryMysql(5, $value->origen_id);

                        if (count($datos2) == 1) {
                            $array["canal_des"] = $datos2[0]->canal_des;
                            $array["canal_cv"] = $datos2[0]->canal_cv;
                            $array["canal_img"] = "http://" . $this->config->item('server:elemento') . "/" . $datos2[0]->canal_img;
                        }
                    }


                    switch ($value->tipo_secciones_id) {
                        case '2':
                            $array["alias"] = "programa/" . $this->urls_amigables($value->nombre);
                            break;
                        case '6':
                            $array["alias"] = "seccion/" . $this->urls_amigables($value->nombre);
                            break;
                        case '7':
                            $array["alias"] = "seccion/" . $this->urls_amigables($value->nombre);
                            break;
                        case '8':
                            $array["alias"] = "seccion/" . $this->urls_amigables($value->nombre);
                            break;
                        case '9':
                            $array["alias"] = "seccion/" . $this->urls_amigables($value->nombre);
                            break;
                        default:
                            $array["alias"] = "seccion/" . $this->urls_amigables($value->nombre);
                            //$array["alias"] = "#";
                            break;
                    }


                    $array["item"] = array();
                    $objmongo = $array;

                    Log::erroLog("mongo_se: " . $value->mongo_se);
                    Log::erroLog("estado_migracion: " . $value->estado_migracion);

                    $id_mongo = "";


                    if (!($this->micanal_mp->existe_id_mongo($value->mongo_se))) {
                        $id_mongo = $this->micanal_mp->SetItemCollection($objmongo);
                        $this->micanal_mp->updateIdMongoSecciones($value->id, $id_mongo);
                        $this->secciones_mp->updateEstadoMigracionSeccion($value->id);
                    } else {
                        $id_mongo = $value->mongo_se;
                        $mongoid = new MongoId($id_mongo);
                        $this->micanal_mp->SetItemCollectionUpdate($objmongo, array('_id' => $mongoid));
                        $this->secciones_mp->updateEstadoMigracionSeccionActualizacion($value->id);
                    }

                    Log::erroLog("-> id_mongo de seccion " . $id_mongo);
                    $mongoid = new MongoId($id_mongo);

                    $this->_generarDetalleSeccionesMiCanalXSeccionId($value->id, $id_mongo);

                    unset($array);
                    unset($objmongo);
                } elseif ($value->estado == 2) {

                    $id_mongo = new MongoId($value->mongo_se);
                    $this->micanal_mp->setItemCollectionUpdate(array("estado" => "0"), array('_id' => $id_mongo));
                    $this->secciones_mp->updateEstadoMigracionSeccionActualizacion($value->id);
                    $this->_generarDetalleSeccionesMiCanalXSeccionId($value->id, $id_mongo);
                }
            }
        }
    }

    private function _generarDetalleSeccionesMiCanalXSeccionId($id, $id_mongo) {

        Log::erroLog("-> id_mongo de seccion " . $id_mongo);

        $resquery2 = $this->micanal_mp->queryMysql(4, $id);

        $item = array();

        foreach ($resquery2 as $value2) {

            $arrtemp = array();

            if ($value2->estado == 1) {

                if (!empty($value2->grupo_maestros_id) && empty($value2->videos_id)) {
                    $idtemp = "1," . $value2->grupo_maestros_id;
                } elseif (empty($value2->grupo_maestros_id) && !empty($value2->videos_id)) {
                    $idtemp = "2," . $value2->videos_id;
                } elseif (empty($value2->grupo_maestros_id) && empty($value2->videos_id)) {
                    $idtemp = "3," . $value2->canales_id;
                }

                Log::erroLog("idtemp" . $idtemp);

                $urltemp = "";

                if (!empty($idtemp)) {
                    $resquery3 = $this->micanal_mp->queryProcedure(4, $idtemp);
                    $row3 = $resquery3;

                    $arrtemp["canal"] = $row3[0]->xcanal;
                    $arrtemp["fecha"] = $row3[0]->xfechatransmision;
                    $arrtemp["coleccion"] = $row3[0]->xcoleccion;
                    $arrtemp["programa"] = $row3[0]->xprograma;
                    $arrtemp["lista_reproduccion"] = $row3[0]->xlistareproduccionalias;
                    //$arrtemp["duracion"] = $row3[0]->xduracion;                    
                    $arrtemp["duracion"] = (!empty($row3[0]->xduracion_lr)) ? $row3[0]->xduracion_lr : $row3[0]->xduracion;
                    $arrtemp["categoria"] = $row3[0]->xcategoria;
                    $arrtemp["descripcion"] = (!empty($value2->descripcion_item)) ? strip_tags($value2->descripcion_item) : strip_tags($row3[0]->xdescripcion);
                    $arrtemp["reproducciones"] = $row3[0]->xvi_rep;
                    $arrtemp["comentarios"] = $row3[0]->xvi_com;
                    $arrtemp["valoracion"] = $row3[0]->xvi_val;
                    $arrtemp["peso"] = $value2->peso;
                    $arrtemp["video"] = strip_tags($row3[0]->xvideo);

                    if (!empty($value2->canales_id) && empty($row3[0]->xvideoalias)) {
                        $urltemp = "canal/" . $row3[0]->xcanalalias;
                    } elseif ($value2->tipo_grupo_maestro == 3) {
                        $urltemp = "programa/" . $row3[0]->xprogramaalias;
                    } else {
                        if (!empty($row3[0]->xprogramaalias)) {
                            if ($row3[0]->xfechatransmision == $row3[0]->xlistareproduccionalias) {
                                $urltemp = $row3[0]->xprogramaalias . "/" . $row3[0]->xfechatransmision . "-" . $row3[0]->xvideoalias;
                            } else {
                                $urltemp = $row3[0]->xprogramaalias . "/" . $row3[0]->xlistareproduccionalias . "/" . $row3[0]->xfechatransmision . "-" . $row3[0]->xvideoalias;
                            }
                        } else {
                            $urltemp = "video" . "/" . $row3[0]->xfechatransmision . "-" . $row3[0]->xvideoalias;
                            // //error_log($urltemp. "Paso aqui ");
                        }
                    }

//                   if (!empty($value2->canales_id) && empty($row3[0]->xvideoalias)) {
//                        $urltemp = "canal/" . $row3[0]->xcanalalias;
//                    } elseif ($value2->tipo_secciones_id == 1 && $value2->tipo_portadas_id == 5) {
//                        $urltemp = "programa/" . $row3[0]->xprogramaalias;
//                    } elseif ($value2->tipo_secciones_id == 2 && $value2->tipo_portadas_id == 5) {
//                        $urltemp = "programa/" . $row3[0]->xprogramaalias;
//                    } else {
//                        if (!empty($row3[0]->xprogramaalias)) {
//                            if ($row3[0]->xfechatransmision == $row3[0]->xlistareproduccionalias) {
//                                $urltemp = $row3[0]->xprogramaalias . "/" . $row3[0]->xfechatransmision . "-" . $row3[0]->xvideoalias;
//                            } else {
//                                $urltemp = $row3[0]->xprogramaalias . "/" . $row3[0]->xlistareproduccionalias . "/" . $row3[0]->xfechatransmision . "-" . $row3[0]->xvideoalias;
//                            }
//                        } else {
//                            $urltemp = "video" . "/" . $row3[0]->xfechatransmision . "-" . $row3[0]->xvideoalias;
//                            // //error_log($urltemp. "Paso aqui ");
//                        }
//                    }
                }

                $arrtemp["url"] = $urltemp;

                if ($value2->procedencia == 0) {
                    $arrtemp["imagen"] = "http://" . $this->config->item('server:elemento') . "/" . $value2->imagen;
                } else {
                    $arrtemp["imagen"] = $value2->imagen;
                }

                array_push($item, $arrtemp);
            }



            if ($value2->estado_migracion == 0) {
                $this->micanal_mp->updateEstadoMigracionDetalleSecciones($value2->id);
            } elseif ($value2->estado_migracion == 9) {
                $this->micanal_mp->updateEstadoMigracionDetalleSeccionesActualizacion($value2->id);
            }
        }


        $mongoid = new MongoId($id_mongo);
        ////error_log("mongo_id: " . $mongoid);
        $this->micanal_mp->SetItemCollectionUpdate(array("item" => $item), array('_id' => $mongoid));
    }

    /* MiCanal Mongo - FIN */



    /* Canal Mongo - INICIO */

//    public function generarCanal() {
//        $this->_generarCanales();
//        $this->_generarProgramas();
//        $this->_generarVideos();
//        $this->_generarDetalleVideos();
//    }
//    private function _generarCanales() {
//
//        $resquery = $this->canal_mp->queryMysqlCanal(1, "");
//
//        if (count($resquery) > 0) {
//            //while ($row = $resquery->fetch_object()) {
//
//            foreach ($resquery as $value) {
//                if (($value->estado_migracion == 0 or $value->estado_migracion == 9 ) && $value->estado == 1) {
//
//                    $arrcanal = array();
//
//                    $arrcanal['canal_id'] = $value->id;
//
//                    $objmongo['canal'] = $arrcanal['canal'] = ($value->nombre);
//                    $objmongo['descripcion'] = $arrcanal['descripcion'] = ($value->descripcion);
//                    $objmongo['url'] = $arrcanal['url'] = $value->alias;
//                    //$objmongo['imagen'] = $arrcanal['imagen'] = "canal.jpg";
//                    //$objmongo['logo'] = $arrcanal['logo'] = "logo.jpg";
//                    $objmongo['padre'] = $arrcanal['padre'] = "";
//                    $objmongo['nivel'] = $arrcanal['nivel'] = "0";
//
//                    $arrcanal['apikey'] = $value->apikey;
//                    $arrcanal['playerkey'] = $value->playerkey;
//
//                    if ($value->estado == 1) {
//                        if ($value->estado_migracion == 0) {
//                            $id_mongo = $this->canal_mp->setItemCollection($objmongo);
//                            $this->canal_mp->updateIdMongoCanales($value->id, $id_mongo);
//                            $this->canal_mp->updateEstadoMigracionCanales($value->id);
//                        } elseif ($value->estado_migracion == 9) {
//                            $id_mongo = new MongoId($value->id_mongo);
//                            $this->canal_mp->setItemCollectionUpdate($objmongo, array('_id' => $id_mongo));
//                            $this->canal_mp->updateEstadoMigracionCanalesActualizacion($value->id);
//                        }
//                    }
//
//
////                    $id_mongo = $this->canal_mp->SetItemCollection($objmongo);
////                    $arrcanal['idmongo'] = $id_mongo;
////
////                    $this->canal_mp->updateIdMongoCanales($value->id, $id_mongo);
////                    $this->canal_mp->updateEstadoMigracionCanales($value->id);
//
//                    unset($objmongo);
//
//                    //$this->ListaProgramas($arrcanal);
//                } elseif ($value->estado == 0 || $value->estado == 2) {
//                    //eliminacion item en coleccion micanal                    
//                    $id_mongo = new MongoId($value->id_mongo);
//                    $this->canal_mp->setItemCollectionUpdate(array("estado" => "0"), array('_id' => $id_mongo));
//                    //$this->canal_mp->SetItemCollectionDelete(array('_id' => $id_mongo));
//                    $this->canal_mp->updateEstadoMigracionCanalesActualizacion($value->id);
//                }
//            }
//        }
//    }

    public function actualizarGrupoMaestros() {
        $this->_actualizarGrupoMaestros();
    }

    private function _actualizarGrupoMaestros() {
        $gm = $this->grupo_maestros_mp->getGrupoMaestro();

        foreach ($gm as $value) {
            $this->generarGrupoMaestrosXId($value->tipo_grupo_maestro_id, $value->id);
        }
    }

    public function curlGenerarGrupoMaestrosXId($tgm, $id) {
        Log::erroLog("ini - curlGenerarGrupoMaestroXId: " . $tgm . "/" . $id);
        $ruta = base_url("curlproceso/generarGrupoMaestroXId/" . $tgm . "/" . $id);
        shell_exec("curl " . $ruta . " > /dev/null 2>/dev/null &");
    }

    public function generarGrupoMaestrosXId($tgm, $id) {
        Log::erroLog("ini - generarGrupoMaestrosXId: " . $tgm . "/" . $id);
        $this->_generarGrupoMaestrosXId($tgm, $id);
    }

    private function _generarGrupoMaestrosXId($tgm, $id) {

        $grupomaestro = $this->grupo_maestros_mp->getGrupoMaestroXId($tgm, $id);

        if (count($grupomaestro) > 0) {

            foreach ($grupomaestro as $value) {

//                if ($value->estado == 1) {

                $imagenes = $this->imagenes_mp->getImagenesGrupoMaestrosXId($id);

                $objmongo = array();

                foreach ($imagenes as $value2) {
                    if ($value2->procedencia == 0) {
                        $objmongo[$value2->nombre] = "http://" . $this->config->item('server:elemento') . "/" . $value2->imagen;
                    } else {
                        $objmongo[$value2->nombre] = $value2->imagen;
                    }
                }

                $objmongo['canal'] = strip_tags($value->nombre_ca);
                $objmongo['nombre'] = strip_tags($value->nombre);
                $objmongo['descripcion'] = strip_tags($value->descripcion);
                $objmongo['url'] = $value->alias;
                $objmongo['estado'] = ($value->estado == ESTADO_ACTIVO) ? $value->estado : '0';
                $objmongo['padre'] = (!empty($value->idmongo_pa)) ? $value->idmongo_pa : $value->idmongo_ca;


                switch ($tgm) {
                    case 3:
                        $objmongo['nivel'] = $this->config->item('nivel:programa');
                        break;
                    case 2:
                        $objmongo['nivel'] = $this->config->item('nivel:coleccion');
                        break;
                    case 1:
                        $objmongo['nivel'] = $this->config->item('nivel:listareproduccion');
                        break;
                }

                $objmongo['cv'] = $value->vi;

                $datovideo = $this->micanal_mp->queryProcedure(4, "1," . $id);

                if (!empty($datovideo[0]->xprogramaalias)) {
                    if ($datovideo[0]->xfechatransmision == $datovideo[0]->xlistareproduccion) {
                        $urltemp = $datovideo[0]->xprogramaalias . "/" . $datovideo[0]->xfechatransmision . "-" . $datovideo[0]->xvideoalias; //  2. micanal.pe/[programa]/[fecha]-[video]-id [ nombre de lista es igual a la fecha de transmisi?n de los videos.                      
                    } else {
                        $urltemp = $datovideo[0]->xprogramaalias . "/" . $datovideo[0]->xlistareproduccionalias . "/" . $datovideo[0]->xfechatransmision . "-" . $datovideo[0]->xvideoalias; //  1. micanal.pe/[programa]/[lista]/[fecha]-[video]-id                        
                    }
                } else {
                    $urltemp = "video" . "/" . $datovideo[0]->xfechatransmision . "-" . $datovideo[0]->xvideoalias;
                }

                $objmongo['link'] = $urltemp;
                $objmongo['canal'] = $datovideo[0]->xcanal;
                $objmongo['programa'] = $datovideo[0]->xprograma;



                if (!($this->canal_mp->existe_id_mongo($value->id_mongo))) {
                    $id_mongo = $this->canal_mp->setItemCollection($objmongo);
                    $this->grupo_maestros_mp->updateIdMongoGrupoMaestros($value->id, $id_mongo);
                    //$this->canal_mp->updateEstadoMigracionCanales($value->id);
                } else {
                    $id_mongo = new MongoId($value->id_mongo);
                    $this->canal_mp->setItemCollectionUpdate($objmongo, array('_id' => $id_mongo));
                    //$this->canal_mp->updateEstadoMigracionCanalesActualizacion($value->id);
                }
                unset($objmongo);

//                } elseif ($value->estado == 0 || $value->estado == 2) {
//                    $id_mongo = new MongoId($value->id_mongo);
//                    $this->canal_mp->setItemCollectionUpdate(array("estado" => "0"), array('_id' => $id_mongo));
////                    $this->canal_mp->updateEstadoMigracionCanalesActualizacion($value->id);
//                }
            }
        }
    }

    public function curlGenerarCanalesXId($id) {
        Log::erroLog("ini - curlGenerarCanalesXId: " . $id);
        $ruta = base_url("curlproceso/generarCanalesXId/" . $id);
        shell_exec("curl " . $ruta . " > /dev/null 2>/dev/null &");
    }

    public function generarCanalesXId($id) {
        $this->_generarCanalesXId($id);
    }

    private function _generarCanalesXId($id) {
        $canal = $this->canales_mp->getCanalesXId($id);

        if (count($canal) > 0) {

            foreach ($canal as $value) {

//                if (($value->estado_migracion == 0 or $value->estado_migracion == 9 ) && $value->estado == 1) {

                $objmongo = array();
                $objmongo['canal'] = strip_tags($value->nombre);
                $objmongo['descripcion'] = strip_tags($value->descripcion);
                $objmongo['url'] = $value->alias;

                $imagenes = $this->imagenes_mp->getImagenesCanalesXId($id);

                foreach ($imagenes as $value2) {
                    if ($value2->procedencia == 0) {
                        $objmongo[$value2->nombre] = "http://" . $this->config->item('server:elemento') . "/" . $value2->imagen;
                    } else {
                        $objmongo[$value2->nombre] = $value2->imagen;
                    }
                }

                $objmongo['estado'] = ($value->estado == ESTADO_ACTIVO) ? $value->estado : '0';
                $objmongo['padre'] = "";
                $objmongo['nivel'] = $this->config->item('nivel:canal');               
                $objmongo['apikey'] = $value->apikey;
                $objmongo['playerkey'] = $value->playerkey;
                $objmongo['cv'] = $value->canal_cv;
                $objmongo['cs'] = $value->canal_cs;


                if (!($this->canal_mp->existe_id_mongo($value->id_mongo))) {
                    $id_mongo = $this->canal_mp->setItemCollection($objmongo);
                    $this->canal_mp->updateIdMongoCanales($value->id, $id_mongo);
                    $this->canal_mp->updateEstadoMigracionCanales($value->id);
                } else {
                    $id_mongo = new MongoId($value->id_mongo);
                    $this->canal_mp->setItemCollectionUpdate($objmongo, array('_id' => $id_mongo));
                    $this->canal_mp->updateEstadoMigracionCanalesActualizacion($value->id);
                }

                unset($objmongo);
//                } elseif ($value->estado == 0 || $value->estado == 2) {
//                    $id_mongo = new MongoId($value->id_mongo);
//                    $this->canal_mp->setItemCollectionUpdate(array("estado" => "0"), array('_id' => $id_mongo));
//                    $this->canal_mp->updateEstadoMigracionCanalesActualizacion($value->id);
//                }
            }
        }
    }

    public function generarProgramasXId($id) {
        $this->_generarProgramasXId($id);
    }

    private function _generarProgramasXId($id) {

        $programa = $this->grupo_maestros_mp->getGrupoMaestroXId(3, $id);

        if (count($programa) > 0) {

            foreach ($programa as $value) {

                if ($value->estado == 1) {

                    $imagenes = $this->imagenes_mp->getImagenesGrupoMaestrosXId($id);

                    $objmongo = array();

                    foreach ($imagenes as $value2) {
                        $objmongo[$value2->nombre] = "http://" . $this->config->item('server:elemento') . "/" . $value2->imagen;
                    }

                    $objmongo['canal'] = strip_tags($value->nombre_ca);
                    $objmongo['nombre'] = strip_tags($value->nombre);
                    $objmongo['descripcion'] = strip_tags($value->descripcion);
                    $objmongo['url'] = $value->alias;
                    $objmongo['estado'] = $value->estado;
                    $objmongo['padre'] = $value->idmongo_ca;
                    $objmongo['nivel'] = "1";
                    $objmongo['cv'] = $value->vi;

                    if (!($this->canal_mp->existe_id_mongo($value->id_mongo))) {
                        $id_mongo = $this->canal_mp->setItemCollection($objmongo);
                        $this->grupo_maestros_mp->updateIdMongoGrupoMaestros($value->id, $id_mongo);
                        $this->grupo_maestros_mp->updateEstadoMigracionGrupoMaestros($value->id);
                    } else {
                        $id_mongo = new MongoId($value->id_mongo);
                        $this->canal_mp->setItemCollectionUpdate($objmongo, array('_id' => $id_mongo));
                        $this->grupo_maestros_mp->updateEstadoMigracionGrupoMaestrosActualizacion($value->id);
                    }


                    unset($objmongo);
                } elseif ($value->estado == 0 || $value->estado == 2) {
                    $id_mongo = new MongoId($value->id_mongo);
                    $this->canal_mp->setItemCollectionUpdate(array("estado" => "0"), array('_id' => $id_mongo));
                    $this->canal_mp->updateEstadoMigracionCanalesActualizacion($value->id);
                }
            }
        }
    }

//    public function generarColeccionesXId($id) {
//        $this->_generarColeccionesXId($id);
//    }
//
//    private function _generarColeccionesXId($id) {
//        
//    }

    private function _generarVideosXId($id) {

        Log::erroLog("_generarVideosXId: " . $id);

        $video = $this->videos_mp->getVideosxId($id);

        if (count($video) > 0) {
            foreach ($video as $value) {
                
                $estado  = ($value->procedencia == $this->config->item('proce:widget'))?ESTADO_ACTIVO:$value->est_tra;

                if (($value->estado == 1 || $value->estado == 2) && $estado == ESTADO_ACTIVO) {
                    $datovideo = $this->canal_mp->queryProcedure(4, $value->id);
                    $objmongo['id'] = $value->id;
                    $objmongo['canal'] = ($datovideo[0]->xcanal);
                    $objmongo['canal_alias'] = $datovideo[0]->xcanalalias;
                    $objmongo['programa'] = ($datovideo[0]->xprograma);
                    $objmongo['programa_alias'] = $datovideo[0]->xprogramaalias;
                    $objmongo['fecha'] = date("d-m-Y", strtotime($datovideo[0]->xfechatransmision));
                    $objmongo['etiquetas'] = explode(",", $value->etiquetas);
                    $objmongo['logo'] = "http://" . $this->config->item('server:elemento') . "/" . $value->imagen;
                    $objmongo['nombre'] = $datovideo[0]->xvideo;
                    $objmongo['descripcion'] = (strip_tags($datovideo[0]->xdescripcion));
                    $objmongo['imagen'] = array();
                    $objmongo['categoria'] = $datovideo[0]->xcategoria;
                    $objmongo['reproducciones'] = $datovideo[0]->xvi_rep;
                    $objmongo['lista_reproduccion'] = ($datovideo[0]->xlistareproduccion);
                    $objmongo['duracion'] = $datovideo[0]->xduracion;
                    $objmongo['media'] = $datovideo[0]->xcodigo;
                    $objmongo['comentarios'] = $datovideo[0]->xvi_com;
                    $objmongo['related'] = array();
                    $objmongo['playlist'] = array();
                    $objmongo['clips'] = array();
                    $objmongo['playerkey'] = $datovideo[0]->xplayerkey;
                    $objmongo['apikey'] = $datovideo[0]->xapikey;
                    $objmongo['fragmento'] = (int) ($value->fragmento);
                    $objmongo['valoracion'] = $datovideo[0]->xvi_val;
                    $objmongo['publicidad'] = "0";
                    $objmongo['estado'] = ($value->estado == 2) ? "1" : "0";

                    if (!empty($datovideo[0]->xprogramaalias)) {
                        if ($datovideo[0]->xfechatransmision == $datovideo[0]->xlistareproduccion) {
                            $urltemp = $datovideo[0]->xprogramaalias . "/" . $datovideo[0]->xfechatransmision . "-" . $datovideo[0]->xvideoalias; //  2. micanal.pe/[programa]/[fecha]-[video]-id [ nombre de lista es igual a la fecha de transmisi?n de los videos.                      
                        } else {
                            $urltemp = $datovideo[0]->xprogramaalias . "/" . $datovideo[0]->xlistareproduccionalias . "/" . $datovideo[0]->xfechatransmision . "-" . $datovideo[0]->xvideoalias; //  1. micanal.pe/[programa]/[lista]/[fecha]-[video]-id                        
                        }
                    } else {
                        $urltemp = "video" . "/" . $datovideo[0]->xfechatransmision . "-" . $datovideo[0]->xvideoalias;
                    }

                    $objmongo['url'] = $urltemp;
                    $objmongo['padre'] = $value->idmongo_pa;
                    $objmongo['nivel'] = $this->config->item('nivel:video');

                    if (!($this->canal_mp->existe_id_mongo($value->id_mongo))) {
                        echo "entro ";
                        $mongo_id = $this->canal_mp->setItemCollection($objmongo);
                        $this->canal_mp->updateIdMongoVideos($value->id, $mongo_id);
                        $this->canal_mp->updateEstadoMigracionVideos($value->id);
                    } else { //if ($value->estado_migracion == 9)
                        $mongo_id = $value->id_mongo;
                        $MongoId = array("_id" => new MongoId($value->id_mongo));
                        $this->canal_mp->setItemCollectionUpdate($objmongo, $MongoId);
                        $this->canal_mp->updateEstadoMigracionVideosActualizacion($value->id);
                    }

                    $this->_generarDetalleVideosXId($value->id, $mongo_id);

                    unset($objmongo);
                } else {

                    if (!empty($value->id_mongo)) {
                        $id_mongo = new MongoId($value->id_mongo);
                        $this->canal_mp->setItemCollectionUpdate(array("estado" => "0"), array('_id' => $id_mongo));
                    }
                }
            }

            Log::erroLog("actualizar_video: " . $id);
            $this->_actualizarCantidadVideosXVideosId($id);
            //$this->curlActualizarCantidadVideosXVideosId($id);
        } else {
            if ($this->canal_mp->existe_id($id)) {
                $this->canal_mp->setItemCollectionDelete($id);
            }
        }
    }

    public function generarDetalleVideosXId($id, $mongo_id) {
        $this->_generarDetalleVideosXId($id, $mongo_id);
    }

    private function _generarDetalleVideosXId($id, $mongo_id) {

        $MongoId = array("_id" => new MongoId($mongo_id));

        if (!empty($id)) {

            $arrimagen = array();

            $imagenes = $this->imagenes_mp->getImagenesVideos($id);

            foreach ($imagenes as $rowx) {
                if ($rowx->procedencia == 0) {
                    $arrimagen[$rowx->ancho . "x" . $rowx->alto] = "http://" . $this->config->item('server:elemento') . "/" . $rowx->imagen;
                } else {
                    $arrimagen[$rowx->ancho . "x" . $rowx->alto] = $rowx->imagen;
                }
            }

            $playlist = $this->videos_mp->getVideosPlaylist($id);

            $arrayplaylist = array();

            $i = 0;

            foreach ($playlist as $datos) {
                if (!empty($datos->id_mongo)) {
                    $arrayplaylist[$i] = new MongoId($datos->id_mongo);
                    $i++;
                }
            }

            foreach ($playlist as $datos2) {
                if (!empty($datos2->id_mongo)) {
                    $set = array("playlist" => $arrayplaylist);
                    $tempmongo = array("_id" => new MongoId($datos2->id_mongo));
                    $this->canal_mp->SetItemCollectionUpdate($set, $tempmongo);
                }
            }

            $tags = $this->video_tags_mp->getTagsVideosXId($id);

            Log::erroLog("relacionados tags: " . $tags[0]->tags);

//            $url = $this->config->item('motor') . "/sphinx/relacionados/nada/" . str_replace(" ", "-", $tags[0]->tags);
//
//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL, $url);
//            curl_setopt($ch, CURLOPT_FAILONERROR, 1);
//            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
//
//            $result = curl_exec($ch);
//            $info = curl_getinfo($ch);
//
//
//            if ($info['http_code'] == '200') {
//                $resultado = json_decode($result);
//            } else {
//                $resultado = array();
//            }

            $parametros = array();

            $parametros['estado'] = ESTADO_ACTIVO;
            $parametros["peso_videos"] =
                    array('tags' => $this->config->item('peso_tag:sphinx'),
                        'titulo' => $this->config->item('peso_titulo:sphinx'),
                        'descripcion' => $this->config->item('peso_descripcion:sphinx')
            );

            $resultado = $this->sphinx_m->busquedaRelacionado($parametros, $tags[0]->tags);

            $arrayrelacionados = array();

            $i = 0;

            foreach ($resultado as $value) {
                if (!empty($value)) {
                    if ($value != $mongo_id) {
                        $arrayrelacionados[$i] = new MongoId($value);
                        $i++;
                    }
                }
            }

            Log::erroLog("relacionados cantidad: " . count($arrayrelacionados));


            $itemsclips = $this->videos_mp->getVideosClips($id);
            $arrayitemclips = array();

            $i = 0;
            foreach ($itemsclips as $value) {
                if (!empty($value->id_mongo)) {
                    $arrayitemclips[$i] = new MongoId($value->id_mongo);
                    $i++;
                }
            }

            $padreclips = $this->videos_mp->getVideoPadreXIdHijo($id);

            foreach ($padreclips as $value) {
                self::_generarDetalleVideosXId($value->id, $value->id_mongo);
            }

            $set = array("imagen" => $arrimagen, "clips" => $arrayitemclips, "related" => $arrayrelacionados);
            $this->canal_mp->SetItemCollectionUpdate($set, $MongoId);
            Log::erroLog("despues de actualizar_video: " . $id);
            //$this->micanal_mp->SetItemCollectionUpdate(array("item" => $item), array('_id' => $mongoid));
        }
    }

    /* Canal Mongo - FIN */

    public function actualizarVideos() {
        $videos = $this->videos_mp->getVideosActivos();

        foreach ($videos as $value) {
            $this->actualizarVideosXId($value->id);
//            $this->curlActualizarVideosXId($value->id);
        }
    }
    
    public function actualizarPadreVideos(){
        $videos = $this->videos_mp->getVideosActivos();

        foreach ($videos as $value) {
            $this->_actualizarPadreVideos($value->id);
//            $this->curlActualizarVideosXId($value->id);
        }
         echo "OK";     
    }
    
    private function _actualizarPadreVideos($id) {
        Log::erroLog("_actualizarPadreVideos: " . $id);

        $video = $this->videos_mp->getVideosxId($id);

        if (count($video) > 0) {
            foreach ($video as $value) {
                    $objmongo['padre'] = $value->idmongo_pa;                 
                    if (($this->canal_mp->existe_id_mongo($value->id_mongo))) {                        
                        $MongoId = array("_id" => new MongoId($value->id_mongo));
                        $this->canal_mp->setItemCollectionUpdate($objmongo, $MongoId);
                        $this->canal_mp->updateEstadoMigracionVideosActualizacion($value->id);
                    }
            }
        }                
    }

    public function curlActualizarVideosXId($id) {
        Log::erroLog("ini - curlActualizarVideosXId: " . $id);
        $ruta = base_url("curlproceso/actualizarVideosXId/" . $id);
        shell_exec("curl " . $ruta . " > /dev/null 2>/dev/null &");
    }

    public function actualizarVideosXId($id) {
        $this->_obtenerImagesUrlVideosXId($id);
        $this->_generarVideosXId($id);
    }

    public function activarVideosXId($id) {
        $this->_activarVideosXId($id);
        $this->_actualizarCantidadVideosXVideosId($id);
    }

    private function _activarVideosXId($id) {
        $this->canal_mp->setItemCollectionUpdate(array("estado" => "2"), array('id' => $id));
    }

    public function curlActivarVideosXId($id) {
        Log::erroLog("ini - _activarVideosXId: " . $id);
        $ruta = base_url("curlproceso/activarVideosXId/" . $id);
        shell_exec("curl " . $ruta . " > /dev/null 2>/dev/null &");
    }

    public function desactivarVideosXId($id) {
        $this->_desactivarVideosXId($id);
        $this->_actualizarCantidadVideosXVideosId($id);
    }

    private function _desactivarVideosXId($id) {
        $this->canal_mp->setItemCollectionUpdate(array("estado" => "0"), array('id' => $id));
    }

    public function curlDesactivarVideosXId($id) {
        Log::erroLog("ini - curlDesactivarVideosXId: " . $id);
        $ruta = base_url("curlproceso/desactivarVideosXId/" . $id);
        shell_exec("curl " . $ruta . " > /dev/null 2>/dev/null &");
    }

    public function actualizarSecciones6789() {
        Log::erroLog("_activarSecciones6789");
        $this->_activarSecciones6789();

        Log::erroLog("_actualizarVisualizacion");
        $this->_actualizarVisualizacion();

        Log::erroLog("_actualizarComentariosValorizacion");
        $this->_actualizarComentariosValorizacion();

        Log::erroLog("_actualizarSecciones6789");
        $this->_actualizarSecciones6789();

        Log::erroLog("_publicidadVideosMasVistos");
        $this->_publicidadVideosMasVistos();
    }

    private function _activarSecciones6789() {
        $this->secciones_mp->updateSeccionesTipo6789();
    }

    private function _actualizarSecciones6789() {
        $this->micanal_mp->queryProcedure(1, "");
        $secciones = $this->secciones_mp->getSeccionesTipo6789();
        foreach ($secciones as $value) {
            $this->_generarSeccionesMiCanalXSeccionId($value->id);
        }
    }

    private function _publicidadVideosMasVistos() {

        $this->canal_mp->setItemsCollectionUpdate(array('publicidad' => "0"));

        $videos = $this->videos_mp->getVideosMasVistosXId(50);

        foreach ($videos as $value) {
            $id_mongo = new MongoId($value->id_mongo);
            $this->canal_mp->setItemCollectionUpdate(array('publicidad' => "1"), array('_id' => $id_mongo));
        }
    }

    public function curlActualizarPesoSeccion($id, $peso) {
        Log::erroLog("ini - curlActualizarPesoSeccion: " . $id . " - " . $peso);
        $ruta = base_url("curlproceso/actualizarPesoSeccion/" . $id . "/" . $peso);
        shell_exec("curl " . $ruta . " > /dev/null 2>/dev/null &");
    }

    public function actualizarPesoSeccion($id, $peso) {
        $this->_actualizarPesoSeccion($id, $peso);
    }

    private function _actualizarPesoSeccion($id, $peso) {
        $seccion = $this->secciones_mp->getSeccionesXId($id);
        $id_mongo = new MongoId($seccion[0]->mongo_se);
        $this->micanal_mp->setItemCollectionUpdate(array('peso' => $peso), array('_id' => $id_mongo));
    }

    public function estadosVideos() {
        $videos = $this->videos_mp->getVideos();

        echo "<table border=1><tr><td>id</td><td>estado_liquid</td><td>codigo</td><td>estado</td><td>id_mongo</td><td>fecha_migracion</td><td>fecha_migracion_actualizacion</td><td>reproducciones</td><td>procedencia</td></tr>";

        foreach ($videos as $value) {
            echo "<tr><td>" . $value->id . "</td><td>" . $value->estado_liquid . "</td><td>" . $value->codigo . "</td><td>" . $value->estado . "</td><td>" . $value->id_mongo . "</td><td>" . $value->fecha_migracion . "</td><td>" . $value->fecha_migracion_actualizacion . "</td><td>" . $value->reproducciones . "</td><td>" . $value->procedencia . "</td></tr>";
        }
        echo "</table>";
    }

    public function publicarPendientes() {
        $this->_publicarPendientes();
    }

    private function _publicarPendientes() {

        $apikeyCanales = $this->canales_mp->getCanalesDistinctApiKey();

        foreach ($apikeyCanales as $value) {
            $listavideos = Liquid::obtenerVideosNoPublished($value->apikey);

            foreach ($listavideos as $value) {
                $videos = $this->videos_mp->getVideosxCodigo($value["id"]);
                if(isset($videos[0])) {
                    if (!empty($videos[0]->codigo) && !empty($videos[0]->apikey)) {
                        Liquid::updatePublishedNode($videos[0]);
                    }
                }
            }
        }
    }

    public function videoYoutube($id, $url = "") {

        $limpiarurl = array(
            "http://www.youtube.com/watch?",
            "https://www.youtube.com/watch?"
        );

        $str = str_replace($limpiarurl, "", $url, $count);

        parse_str($str, $temp);

        if (empty($temp["v"])) {
            $limpiarurllite = array(
                "http://youtu.be/",
                "https://youtu.be/"
            );

            $str = str_replace($limpiarurllite, "", $url, $count);
            $temp["v"] = $str;
        }

        if (!empty($temp["v"])) {
            $this->curlObtenerVideoYoutube($id, $temp["v"]);
        }
    }

    public function curlObtenerVideoYoutube($id, $v) {
        Log::erroLog("ini - obtenerVideoYoutube: " . $v);
        $ruta = base_url("curlproceso/obtenerVideoYoutube/" . $id . "/" . $v);
        shell_exec("curl " . $ruta . " > /dev/null 2>/dev/null &");
        Log::erroLog("fin - obtenerVideoYoutube: " . $v);
    }

    public function obtenerVideoYoutube($id, $v) {
        $this->_obtenerVideoYoutube($id, $v);
    }

    private function _obtenerVideoYoutube($id, $v) {
        $data = Youtube::obtenerVideo($v);
        
        Log::erroLog("data video $id " .$data);

        if (!empty($data)) {

            $this->videos_mp->setEstadosVideos($id, $this->config->item('v_e:codificando'), $this->config->item('v_l:codificando'));
            $retorno = Youtube::descargaVideo($id, $data);
            
            if ($retorno) {
                $retorno = $this->curlUpdateEstadoVideosXId($id, $this->config->item('v_e:codificando'), $this->config->item('v_l:codificado'));                
                if (trim($retorno) === "OK") {
                    $this->curlUploadVideosXId($id);
                }
            } else {
                $this->curlUpdateEstadoVideosXId($id, $this->config->item('v_e:error'),$this->config->item('v_l:nuevo'));
            }
        }else{
            Log::erroLog("data video $id  error de video " );
            $this->curlUpdateEstadoVideosXId($id, $this->config->item('v_e:error'),$this->config->item('v_l:nuevo'));
            Log::erroLog("data video $id  error de video " );
        }
    }

    public function curlUpdateEstadoVideosXId($id, $ev, $el) {
        Log::erroLog("ini - updateEstadoVideosXId: " . $id . "/" . $ev . "/" . $el);
        $ruta = base_url("curlproceso/updateEstadoVideosXId/" . $id . "/" . $ev . "/" . $el);
        $retorno = shell_exec("curl " . $ruta);
        Log::erroLog("retorno de updateEstadoVideosXId: " . $retorno);
        return $retorno;
    }

    public function updateEstadoVideosXId($id, $ev, $el) {
        $this->_updateEstadoVideosXId($id, $ev, $el);
    }

    private function _updateEstadoVideosXId($id, $ev, $el) {
        $this->videos_mp->setEstadosVideos($id, $ev, $el);
        echo "OK";
    }

    public function curlActualizarCantidadVideosXVideosId($id) {
        Log::erroLog("ini - curlActualizarCantidadVideosXVideosId: " . $id);
        $ruta = base_url("curlproceso/actualizarCantidadVideosXVideosId/" . $id);
        shell_exec("curl " . $ruta . " > /dev/null 2>/dev/null &");
    }

    public function actualizarCantidadVideosXVideosId($id) {
        $this->_actualizarCantidadVideosXVideosId($id);
    }

    private function _actualizarCantidadVideosXVideosId($id) {
        $video = $this->videos_mp->getVideosXIdDatos($id);

        foreach ($video as $value) {
            $this->_actualizarCantidadVideosXCanalId($value->canal_id);

            if (!empty($value->gm_id)) {
                $this->_actualizarCantidadVideosXGmId(3, $value->gm_id);
            }
        }
    }

    private function _actualizarCantidadVideosXGmId($tgm, $id) {
        $gm = $this->grupo_maestros_mp->getCantidadVideosXMaestroId($tgm, $id);

        foreach ($gm as $value) {
            Log::erroLog("id: " . $id);
            Log::erroLog("cv: " . $value->cv);
            Log::erroLog("id_mongo: " . $value->id_mongo);
            $objmongo = array();
            $objmongo['cv'] = $value->cv;
            $id_mongo = new MongoId($value->id_mongo);
            $this->canal_mp->setItemCollectionUpdate($objmongo, array('_id' => $id_mongo));
            //$this->canal_mp->updateEstadoMigracionCanalesActualizacion($value->id);
        }
    }

    private function _actualizarCantidadVideosXCanalId($id) {

        $canal = $this->canales_mp->getCantVideosXCanalId($id);

        if (count($canal) > 0) {
            $objmongo = array();
            $objmongo['cv'] = $canal[0]->cv;
            $id_mongo = new MongoId($canal[0]->id_mongo);
            $this->canal_mp->setItemCollectionUpdate($objmongo, array('_id' => $id_mongo));
            $this->canal_mp->updateEstadoMigracionCanalesActualizacion($canal[0]->id);
        }
    }

//    public function getMaestroDetalles() {
//        $videos = $this->grupo_maestros_mp->getMaestroDetalles();
//
//        echo "<table border=1><tr><td>id</td><td>grupo_maestro_id</td><td>cant</td>";
//
//        foreach ($videos as $value) {
//            echo "<tr><td>" . $value->id . "</td><td>" . $value->grupo_maestro_id . "</td><td>" . $value->cant . "</td></tr>";
//        }
//        echo "</table>";
//    }
//
//    public function getMaestroDetallesXId($id) {
//        $videos = $this->grupo_maestros_mp->getMaestroDetallesXId($id);
//
//        foreach ($videos as $value) {
//            echo "<pre>" . print_r($value) . "</pre>";
//        }
//    }
//
//    public function deleteMaestroDetallesXId($id) {
//        $this->grupo_maestros_mp->deleteMaestroDetallesXId($id);
//        echo "ok";
//    }

    public function datosVideos($id) {
        print_r($this->canal_mp->queryProcedure(4, $id));
    }

    public function showProFun() {
        print_r($this->videos_mp->getShowProcedure());
        print_r($this->videos_mp->getShowFunction());
    }

    public function showLog($date) {
        $ruta = $this->config->item('path:log') . $date . ".txt";

        $file = fopen($ruta, "r") or exit("ERROR AL ABRIR EL ARCHIVO");

        while (!feof($file)) {
            echo fgets($file) . "<br />";
        }
        fclose($file);
    }
    
    public function showXml($date) {
        $ruta = $this->config->item('path:log') . $date . "_xml.txt";

        $file = fopen($ruta, "r") or exit("ERROR AL ABRIR EL ARCHIVO");

        while (!feof($file)) {
            echo fgets($file) . "<br />";
        }
        fclose($file);
    }

    public function publishedVideosXId($id) {
        $video = $this->videos_mp->getVideosxIdConKey($id);

        if (count($video) > 0 && !empty($video[0]->codigo)) {
            print_r(Liquid::updatePublishedMedia($video[0]->apikey, $video[0]->codigo));
        }
    }

    public function unpublishedVideosXId($id) {
        $video = $this->videos_mp->getVideosxIdConKey($id);

        if (count($video) > 0 && !empty($video[0]->codigo)) {
            print_r(Liquid::updateUnpublishedMedia($video[0]->apikey, $video[0]->codigo));
        }
    }

    public function limpiarMongo() {

        $canal = $this->canal_mp->getCanal();

        foreach ($canal as $value) {

            $id_mongo = new MongoId($value['_id']);

            switch ($value['nivel']) {
                case 0:
                    $ret = $this->canales_mp->getExisteCanalXIdMongo($value['_id']);
                    if ($ret == 0) {
                        $this->canal_mp->setItemCollectionDeleteXIdMongo($id_mongo);
                    }
                    break;
                case 1:
                    $ret = $this->grupo_maestros_mp->getExisteGrupoMaestroXIdMongo($value['_id']);
                    if ($ret == 0) {
                        $this->canal_mp->setItemCollectionDeleteXIdMongo($id_mongo);
                    }
                    break;
                case 2:
                    $ret = $this->grupo_maestros_mp->getExisteGrupoMaestroXIdMongo($value['_id']);
                    if ($ret == 0) {
                        $this->canal_mp->setItemCollectionDeleteXIdMongo($id_mongo);
                    }
                    break;
                case 3:
                    $ret = $this->grupo_maestros_mp->getExisteGrupoMaestroXIdMongo($value['_id']);
                    if ($ret == 0) {
                        $this->canal_mp->setItemCollectionDeleteXIdMongo($id_mongo);
                    }
                    break;
                case 4:
                    $ret = $this->videos_mp->getExisteVideosXIdMongo($value['_id']);
                    if ($ret == 0) {
                        $this->canal_mp->setItemCollectionDeleteXIdMongo($id_mongo);
                    }
                    break;

                default:
                    break;
            }
        }


        $micanal = $this->micanal_mp->getMiCanal();

        foreach ($micanal as $value) {
            //print_r($value);

            $id_mongo = new MongoId($value['_id']);

            switch ($value['tipo']) {
                case 'portada':
                    $ret = $this->portadas_mp->getExistePortadaXIdMongo($value['_id']);
                    if ($ret == 0) {
                        if (isset($value['alias'])) {
                            if ($value['alias'] != "") {
                                $this->micanal_mp->setItemCollectionDeleteXIdMongo($id_mongo);
                            }
                        }
                    }
                    break;
                case 'seccion':
                    $ret = $this->secciones_mp->getExisteSeccionesXIdMongo($value['_id']);
                    if ($ret == 0) {
                        $this->micanal_mp->setItemCollectionDeleteXIdMongo($id_mongo);
                    }
                    break;
                default:
                    break;
            }
        }
    }

    public function publicarPorIbope() {
        $videos = $this->videos_mp->getTransmisionMenorIbope();
        foreach ($videos as $video) {
            $this->curlGenerarVideosXId($video->id);
        }
    }

    public function curlGenerarVideosXId($id) {
        Log::erroLog("ini - generarVideosXId: " . $id);
        $ruta = base_url("curlproceso/generarVideosXId/" . $id);
        shell_exec("curl " . $ruta . " > /dev/null 2>/dev/null &");
        Log::erroLog("fin - generarVideosXId");
    }

    public function generarVideosXId($id) {
        $this->_generarVideosXId($id);
    }

    public function limpiarUploadVideo() {
        $this->_limpiarUploadVideo();
    }

    private function _limpiarUploadVideo() {
        $files = get_dir_file_info($this->config->item('path:video'));
        $timesemanaant = strtotime($this->config->item('time:delete:video'));
        
        Log::erroLog("timesemana: " . $timesemanaant );
        Log::erroLog("cantidad de archivos: " . count($files));

        foreach ($files as $file) {
            $octper = octal_permissions(fileperms($file["server_path"]));
             Log::erroLog( $file['name'] . " > permiso: " . $octper);

            if ($timesemanaant > $file['date'] && ($file["name"] != "index.html") && ($octper == "666")) {
                umask(0);
                unlink($file["server_path"]);
                Log::erroLog("Eliminando archivo de video " . $file['name'] . ", subido el " . date('Y-m-d H:i:s', $file['date']));
            }
        }
    }

    public function actualizarVersion($tipo, $version) {
        $this->version_mp->set_version($tipo, $version);
    }
    
    public function postbackliquid($xml){
        
         if (!empty($xml)) {
            $mediaxml = new SimpleXMLElement($xml);
            $mediaarr = json_decode(json_encode($mediaxml), true);
            LOG::xmlLog($mediaarr);
        }
        
    }
    
    public function envioDatos($urlpostback,$id){
        $url = $this->config->item('america:cms:url');
        $user = $this->config->item('america:cms:user');
        $pass = $this->config->item('america:cms:pass');
                
        America::envioDatos($url,$urlpostback,$user,$pass,$id);
    }
}
