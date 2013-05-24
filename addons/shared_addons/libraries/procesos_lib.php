<?php

set_time_limit(TIME_LIMIT);

class Procesos_lib extends MX_Controller {

    public function __construct() {
        //parent::__construct();
        //$this->database();        
        //$this->load->model('models/proceso_m');
        $this->load->model('micanal_mp');
        $this->load->model('canal_mp');
        $this->load->model('canales_mp');
        $this->load->model('videos_mp');
        $this->load->model('imagenes_mp');
        $this->load->model('secciones_mp');
        $this->load->model('portadas_mp');
        $this->load->model('video_tags_mp');
        $this->load->model('grupo_maestros_mp');



        $this->load->library("Procesos/proceso");
        $this->load->library("Procesos/liquid");
        $this->load->library("Procesos/ffmpeg");
        $this->load->library("Procesos/log");
        $this->load->library('portadas_lib');
    }

    public function index() {
        //$this->_actualizarComentariosValorizacion();
    }

    /* Corte video  -  INICIO */

    public function curlCorteVideoXId($id_padre, $id_hijo, $inicio, $duracion) {
        Log::erroLog("ini - curlCorteVideo: " . $id_padre . ", hijo " . $id_hijo);
        $ruta = base_url("curlproceso/corteVideoXId/" . $id_padre . "/" . $id_hijo . "/" . $inicio . "/" . $duracion);
        shell_exec("curl " . $ruta . " > /dev/null 2>/dev/null &");
        Log::erroLog("fin - curlCorteVideo: " . $id_padre . ", hijo " . $id_hijo);
    }

    public function corteVideoXId($id_padre, $id_hijo, $inicio, $duracion) {

        $result = $this->videos_mp->getVideosxId($id_padre);


        if (!empty($id_padre) && !empty($id_hijo) && !empty($inicio) && !empty($duracion)) {
            if (Ffmpeg::downloadVideo($result[0]->id, $result[0]->rutasplitter)) {
                if (Ffmpeg::splitVideo($id_padre, $id_hijo, $inicio, $duracion)) {
                    $this->curlProcesoVideosXId($id_hijo);
                }
            }
        } else {
            return FALSE;
        }
    }

    /* Corte video  -  Fin */

    /* Actualizar Visualizaciones Liquid  -  INICIO */

    private function _actualizarVisualizacion() {

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

    /* Actualizar Visualizaciones Liquid  -  FIN */
    
    private function _curlSetReproducciones($id,$cant){
        Log::erroLog("_curlSetReproducciones: " . $id.", cant: ".$cant);         
        $ruta = base_url("curlproceso/setReproduccionesVideosXId/" . $id."/".$cant);
        shell_exec("curl " . $ruta . " > /dev/null 2>/dev/null &");        
    }
    
    public function setReproduccionesVideosXId($id,$cant){
        $this->videos_mp->setReproduccionesVideosXId($id, $cant);
    }
    
    /* Actualizar Comentarios Valoracion   -  INICIO */

    private function _actualizarComentariosValorizacion() {
        $videos = $this->videos_mp->getVideosActivos();
        foreach ($videos as $value) {
            $id_mongo = new MongoId($value->id_mongo);
            $videomongo = $this->canal_mp->getItemCollection($id_mongo);
            $comentarios = ($videomongo[0]["comentarios"] == "") ? 0 : $videomongo[0]["comentarios"];
            $valoracion = ($videomongo[0]["valoracion"] == "") ? 0 : $videomongo[0]["valoracion"];
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
        $this->_convertirVideosXId($id);
//        $this->_uploadVideosXId($id);
//        Log::erroLog("entro a curl upload video: ". $id);
//        $this->curlUploadVideosXId($id);
//        Log::erroLog("salio de curl upload video ". $id);
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

        if (empty($video[0]->codigo)) {
            if ($video[0]->estado_liquid == 2) {
                Log::erroLog("el video no se cargo me voy a  curlUploadVideosXId " . $id);
                $this->curlUploadVideosXId($id);
            } elseif ($video[0]->estado_liquid == 3 || $video[0]->estado_liquid == 4) {
                Log::erroLog("no hay datos me voy a curlVerificaVideosLiquidXId " . $id);
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

    public function curlVerificaVideosLiquidXId($id) {
        sleep(10);
        Log::erroLog("entro a : curlVerificaVideosLiquidXId" . $id);
        $ruta = base_url("curlproceso/verificaVideosLiquidXId/" . $id);
        shell_exec("curl " . $ruta . " > /dev/null 2>/dev/null &");
    }

    protected function _convertirVideosXId($id) {

        if (!empty($id)) {
            $this->videos_mp->setEstadosVideos($id, 0, 1);
            if (Ffmpeg::convertVideotoMp4($id)) {
                $this->videos_mp->setEstadosVideos($id, 0, 2);
            } else {
                $this->videos_mp->setEstadosVideos($id, 0, -1);
            }
        }
    }

    protected function _uploadVideosXId($id) {

        $resultado = $this->videos_mp->getVideosMp4XId($id);
//        Log::erroLog('-------------------lista de videos mp4---------------------');
//        Log::erroLog(print_r($resultado, true));
        if (count($resultado) > 0) {
            foreach ($resultado as $value) {

                $this->videos_mp->setEstadosVideos($value->id, 0, 3);
                $this->curlVerificaVideosLiquidXId($id);
                $retorno = Liquid::uploadVideoLiquid($value->id, $value->apikey);

                Log::erroLog("retorno de upload video: " . $retorno);
            }
        }
    }

    public function updateMediaVideosXId($id, $media) {
        $this->_updateMediaVideosXId($id, $media);
    }

    protected function _updateMediaVideosXId($id, $media) {
        if ($media != FALSE) {
            Log::erroLog("es diferente de FALSE media: " . $media);
            $this->videos_mp->setEstadosVideos($id, 0, 4);
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

                $retorno = Liquid::updatePublishedMediaNode($value);
                //var_dump($retorno);
                if ($retorno != FALSE) {
                    $this->videos_mp->setEstadosVideos($value->id, 1, 5);
                } else {
                    
                }
            }
        }
    }

    protected function _obtenerImagesUrlVideosXId($id) {


        Log::erroLog("entro a _obtenerImagesUrlVideosXId:  " . $id);

        $resultado = $this->videos_mp->getVideosObtenerDatosXId($id);

        if (count($resultado) > 0) {
            foreach ($resultado as $value) {

                ////error_log("dentro de " . $value->id);

                $mediaarr = Liquid::obtenerDatosMedia($value);


                if (empty($value->duracion)) {
                    $duracion = Liquid::getDurationLiquid($mediaarr);
                    if (!empty($duracion)) {
                        $duracion = ($duracion / 1000);
                        $this->videos_mp->setDuracionVideos($value->id, $duracion);
                    }
                    ////error_log("duracion : " . $duracion);
                }

                if (empty($value->ruta)) {
                    $urlvideo = Liquid::getUrlVideoLiquidRawLite($mediaarr);
                    if (!empty($urlvideo)) {
                        $this->videos_mp->setRutaVideos($value->id, $urlvideo);
                    }
                }

                ////error_log("ruta splitter". $value->rutasplitter);

                if (empty($value->rutasplitter)) {
                    $urlvideo = Liquid::getUrlVideoLiquidRaw($mediaarr);
                    if (!empty($urlvideo)) {
                        $this->videos_mp->setRutaVideosSplitter($value->id, $urlvideo);
                    }
                }


                if ($value->imag == 0) {

                    $imagenes = Liquid::getimagenesLiquid($mediaarr);

                    if (count($imagenes) > 0) {
                        //print_r($imagenes);
                        $imagenpadre = NULL;

                        $datos = array();

                        $datos["videos_id"] = $value->id;
                        $datos["procedencia"] = 1;
                        $datos["estado"] = $value->estado;
                        $datos["fecha_registro"] = date('Y-m-d H:i:s');

                        foreach ($imagenes as $value2) {
                            $datos["imagen"] = $value2["url"];
                            $datos["tipo_imagen_id"] = $value2["tipo_imagen_id"];
                            $datos["imagen_padre"] = $imagenpadre;


                            if ($imagenpadre == NULL) {
                                $imagenpadre = $this->imagenes_mp->setImagenVideos($datos);
                                //registra en las portadas
                               // $this->portadas_lib->actualizar_imagen($imagenpadre);
                                Log::erroLog("id imagen padre: " . $datos["imagen_padre"]);
                            } else {
                                $video_hijo_id = $this->imagenes_mp->setImagenVideos($datos);
                                //registra en las portadas
                                //$this->portadas_lib->actualizar_imagen($video_hijo_id);
                            }
                        }
                    }
                }

                if ((!empty($value->ruta) || !empty($urlvideo) || !empty($duracion) ) && ($value->imag != 0 || !empty($datos["imagen"]))) {
                    $this->videos_mp->setEstadosVideos($value->id, 2, 6);
                }
            }
        }
    }

    public function procesoVideos() {
        $this->_convertirVideos();
        $this->_uploadVideos();
        $this->_publishVideos();
        $this->_obtenerImagesUrlVideos();
    }

    protected function _convertirVideos() {

        $resultado = $this->videos_mp->getVideosNuevos();
        //echo print_r($resultado) . "\n";

        if (count($resultado) > 0) {
            foreach ($resultado as $value) {
                $this->videos_mp->setEstadosVideos($value->id, 0, 1);
                if (Ffmpeg::convertVideotoMp4($value->id)) {
                    $this->videos_mp->setEstadosVideos($value->id, 0, 2);
                } else {
                    $this->videos_mp->setEstadosVideos($value->id, 0, -1);
                }
            }
        }
    }

    protected function _uploadVideos() {

        $resultado = $this->videos_mp->getVideosMp4();
//        Log::erroLog('-------------------lista de videos mp4---------------------');
//        Log::erroLog(print_r($resultado, true));
        if (count($resultado) > 0) {
            foreach ($resultado as $value) {

                $this->videos_mp->setEstadosVideos($value->id, 0, 3);
                $retorno = Liquid::uploadVideoLiquid($value->id, $value->apikey);

                if ($retorno != FALSE) {
//                    Log::erroLog('----------------------------------------');
//                    Log::erroLog(print_r($retorno, true));
                    $this->videos_mp->setEstadosVideos($value->id, 0, 4);
                    $this->videos_mp->setMediaVideos($value->id, $retorno);
                } else {

                    $this->videos_mp->setEstadosVideos($value->id, 0, 2);
                }
            }
        }
    }

    protected function _publishVideos() {
        $resultado = $this->videos_mp->getVideosNoPublicados();
        //echo print_r($resultado) . "\n";
        if (count($resultado) > 0) {
            foreach ($resultado as $value) {
                $retorno = Liquid::updatePublishedMediaNode($value);
                //var_dump($retorno);
                if ($retorno != FALSE) {
                    $this->videos_mp->setEstadosVideos($value->id, 1, 5);
                }
            }
        }
    }

    protected function _obtenerImagesUrlVideos() {

        $resultado = $this->videos_mp->getVideosObtenerDatos();

        if (count($resultado) > 0) {
            foreach ($resultado as $value) {

                $mediaarr = Liquid::obtenerDatosMedia($value);

                if (empty($value->ruta)) {
                    $urlvideo = Liquid::getUrlVideoLiquidRawLite($mediaarr);
                    if (!empty($urlvideo)) {
                        $this->videos_mp->setRutaVideos($value->id, $urlvideo);
                    }
                }

                if ($value->imag == 0) {

                    $imagenes = Liquid::getimagenesLiquid($mediaarr);

                    if (count($imagenes) > 0) {
                        //print_r($imagenes);
                        $datos = array();

                        $datos["videos_id"] = $value->id;
                        $datos["imagen_padre"] = NULL;
                        $datos["procedencia"] = 1;
                        $datos["fecha_registro"] = date('Y-m-d H:i:s');

                        foreach ($imagenes as $value2) {
                            $datos["imagen"] = $value2["url"];
                            $datos["tipo_imagen_id"] = $value2["tipo_imagen_id"];
                            $datos["imagen_padre"] = $this->imagenes_mp->setImagenVideos($datos);
                        }
                    }
                }

                if ((!empty($value->ruta) || !empty($urlvideo)) && ($value->imag != 0 || !empty($datos["imagen"]))) {
                    $this->videos_mp->setEstadosVideos($value->id, 2, 6);
                }
            }
        }
    }

    /* Subir Videos - INICIO */

    /* MiCanal Mongo - INICIO */

    private function urls_amigables($url) {
        $url = strtolower($url);
        $find = array('�', '�', '�', '�', '�', '�');
        $repl = array('a', 'e', 'i', 'o', 'u', 'n');
        $url = str_replace($find, $repl, $url);
        $find = array(' ', '&', '\r\n', '\n', '+');
        $url = str_replace($find, '-', $url);
        $find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
        $repl = array('', '-', '');
        $url = preg_replace($find, $repl, $url);
        return $url;
    }

    public function generarMiCanal() {
        $this->_generarPortadasMiCanal();
        $this->_generarSeccionesMiCanal();
        $this->_generarDetalleSeccionesMiCanal();
    }

//    public function generarPortadasMiCanal() {
//        $this->_generarPortadasMiCanal();
//    }



    private function _generarPortadasMiCanal() {

        $resquery = $this->micanal_mp->queryMysql(1, "");

        if (count($resquery) > 0) {



            foreach ($resquery as $value) {
                Log::erroLog($value->estado_migracion . "  -  " . $value->estado);

                if (($value->estado_migracion == 0 or $value->estado_migracion == 9 ) && $value->estado == 1) {

                    $array = array();

                    $array["tipo"] = "portada";
                    $array["nombre"] = ($value->nombre);
                    $array["estado"] = "1";


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



                    if ($value->estado == 1) {
                        if ($value->estado_migracion == 0) {
                            $id_mongo = $this->micanal_mp->setItemCollection($objmongo);
                            $this->micanal_mp->updateIdMongoPortadas($value->id, $id_mongo);
                            $this->micanal_mp->updateEstadoMigracionPortadas($value->id);
                        } elseif ($value->estado_migracion == 9) {
                            $id_mongo = new MongoId($value->id_mongo);
                            Log::erroLog("entro a actualizar");
                            $this->micanal_mp->setItemCollectionUpdate($objmongo, array('_id' => $id_mongo));
                            $this->micanal_mp->updateEstadoMigracionPortadasActualizacion($value->id);
                        }
                    }
                    unset($objmongo);
                    unset($array);
                } elseif ($value->estado == 0 || $value->estado == 2) {
                    //eliminacion item en coleccion micanal                    
                    $id_mongo = new MongoId($value->id_mongo);
                    $this->micanal_mp->setItemCollectionUpdate(array("estado" => "0"), array('_id' => $id_mongo));
                    //$this->micanal_mp->SetItemCollectionDelete(array('_id' => $id_mongo));
                    $this->micanal_mp->updateEstadoMigracionPortadasActualizacion($value->id);
                }
            }
        }
    }

//    public function generarSeccionesMiCanal() {
//        $this->_generarSeccionesMiCanal();
//    }
//    public function actualizarDetalleSecciones() {
//        $this->_generarDetalleSeccionesMiCanal();
//    }

    private function _generarSeccionesMiCanal() {

        $array = array();


        $resquery = $this->micanal_mp->queryMysql(2, "");

        if (count($resquery) != 0) {

            foreach ($resquery as $value) {

                if (($value->estado_migracion == 0 || $value->estado_migracion == 9) && $value->estado == 1) {

                    $array["tipo"] = "seccion";
                    $array["nombre"] = $value->nombre;
                    $array["peso"] = $value->peso;
                    $array["template"] = $value->templates_id;
                    $array["padre"] = $value->mongo_po;
                    $array["estado"] = "1";

                    $array["alias_pa"] = $value->alias_pa;
                    $array["alias_se"] = $this->urls_amigables($value->nombre);

                    //echo $value->tipo_portadas_id . " - " . $value->tipo_secciones_id;

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
                            $array["alias"] = "#";
                            break;
                    }

                    $item = array();

                    $array["item"] = $item;
                    $objmongo = $array;


                    $id_mongo = "";

                    if ($value->mongo_se == "") {
                        $id_mongo = $this->micanal_mp->SetItemCollection($objmongo);
                        $this->micanal_mp->updateIdMongoSecciones($value->id, $id_mongo);
                        $this->secciones_mp->updateEstadoMigracionSeccion($value->id);
                    } elseif ($value->estado_migracion == 9 || $value->estado_migracion == 2) {
                        $id_mongo = $value->mongo_se;
                        $mongoid = new MongoId($id_mongo);
                        $this->micanal_mp->SetItemCollectionUpdate($objmongo, array('_id' => $mongoid));
                        $this->secciones_mp->updateEstadoMigracionSeccionActualizacion($value->id);
                    }

                    Log::erroLog($id_mongo);
                    $mongoid = new MongoId($id_mongo);

                    $this->_generarDetalleSeccionesMiCanalXSeccionId($value->id, $id_mongo);

//                    if ($value->estado_migracion == 0) {
//                        $id_mongo = $this->micanal_mp->SetItemCollection($objmongo);
//                        $array["id_mongo"] = $id_mongo;
//                        $array["id"] = $value->id;
//                        $this->micanal_mp->updateIdMongoSecciones($value->id, $id_mongo);
//                        $this->micanal_mp->updateEstadoMigracionPortadas($value->id);
//                    } elseif ($value->estado_migracion == 9) {
//                        $id_mongo = new MongoId($value->id_mongo);
//                        $this->micanal_mp->SetItemCollectionUpdate($objmongo, array('_id' => $id_mongo));
//                        $this->updateEstadoMigracionPortadasActualizacion($value->id);
//                    }
//
//
//                    $this->_generarDetalleSeccionesMiCanalXSeccionId($value->id);

                    unset($array);
                    unset($objmongo);
                } elseif ($value->estado == 2) {

                    $id_mongo = new MongoId($value->mongo_se);
                    $this->micanal_mp->setItemCollectionUpdate(array("estado" => "0"), array('_id' => $id_mongo));
                    //$this->conexionmongodb->SetItemCollectionDelete(array('_id' => $id_mongo));
                    $this->updateEstadoMigracionActualizacion($value->id);
                }
            }
        }
    }

    private function _generarDetalleSeccionesMiCanal() {

        $resquery = $this->micanal_mp->queryMysql(3, "");


        foreach ($resquery as $value) {
            //echo "seccion_id" . $value->id . "; mongo:" . $value->id_mongo . "\n";
            $resquery2 = $this->micanal_mp->queryMysql(4, $value->id);


            $item = array();

            foreach ($resquery2 as $value2) {
                $arrtemp = array();

                //echo "\n estado" . $value2->estado . "\n";

                if ($value2->estado == 1) {


                    if ($value2->grupo_maestros_id != "" && $value2->videos_id == "") {
                        $idtemp = "1," . $value2->grupo_maestros_id;
                    } elseif ($value2->grupo_maestros_id == "" && $value2->videos_id != "") {
                        $idtemp = "2," . $value2->videos_id;
                    }



                    $resquery3 = $this->micanal_mp->queryProcedure(4, $idtemp);
                    $row3 = $resquery3;

                    $arrtemp["canal"] = ($row3[0]->xcanal);
                    $arrtemp["fecha"] = $row3[0]->xfechatransmision;
                    $arrtemp["coleccion"] = ($row3[0]->xcoleccion);
                    $arrtemp["programa"] = ($row3[0]->xprograma);
                    $arrtemp["lista_reproduccion"] = ($row3[0]->xlistareproduccionalias);
                    $arrtemp["duracion"] = $row3[0]->xduracion;
                    $arrtemp["categoria"] = $row3[0]->xcategoria;
                    $arrtemp["descripcion"] = (strip_tags($row3[0]->xdescripcion));
                    $arrtemp["reproducciones"] = ($row3[0]->xvi_rep);
                    $arrtemp["comentarios"] = ($row3[0]->xvi_com);
                    $arrtemp["valoracion"] = ($row3[0]->xvi_val);
                    $arrtemp["video"] = ($row3[0]->xvideo);

                    $urltemp = "";
                    if ($value->tipo_secciones_id == 1 && $value->tipo_portadas_id == 5) {
                        $urltemp = "programa/" . $row3[0]->xprogramaalias;
                    } elseif ($value->tipo_secciones_id == 2 && $value->tipo_portadas_id == 5) {
                        $urltemp = "programa/" . $row3[0]->xprogramaalias;
                    } else {

                        if ($row3[0]->xfechatransmision == $row3[0]->xlistareproduccionalias) {
                            $urltemp = $row3[0]->xprogramaalias . "/" . $row3[0]->xfechatransmision . "-" . $row3[0]->xvideoalias;
                        } else {
                            $urltemp = $row3[0]->xprogramaalias . "/" . $row3[0]->xlistareproduccionalias . "/" . $row3[0]->xfechatransmision . "-" . $row3[0]->xvideoalias;
                        }
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
                    //echo "ingresando : " . $value2->id . "\n";
                    $this->micanal_mp->updateEstadoMigracionDetalleSecciones($value2->id);
                } elseif ($value2->estado_migracion == 9 || $value2->estado_migracion == 2) {
                    //echo "actualizando : " . $value2->id . "\n";
                    $this->micanal_mp->updateEstadoMigracionDetalleSeccionesActualizacion($value2->id);
                }
            }

            $where = array("_id" => new MongoId($value->id_mongo));

            $set = array("item" => $item);
            $this->micanal_mp->SetItemCollectionUpdate($set, $where);
        }
    }

    public function actualizarPortadasMiCanal() {
        $portadas = $this->portadas_mp->getPortadas();

        //print_r($portadas);

        foreach ($portadas as $value) {
            $this->_generarPortadasMiCanalXId($value->id);
        }
    }

    public function actualizarPortadasMiCanalXId($id) {
        Log::erroLog("id: " . $id);
        $this->_generarPortadasMiCanalXId($id);
        Log::erroLog("paso: " . $id);
    }
    
    public function generarPortadasMiCanalXId($id){
        $this->_generarPortadasMiCanalXId($id);
    }
    
    public function curlGenerarPortadasMiCanalXId($id){
        Log::erroLog("ini - curlGenerarCanalesXId: " . $id );
        $ruta = base_url("curlproceso/generarPortadasMiCanalXId/" . $id);
        shell_exec("curl " . $ruta . " > /dev/null 2>/dev/null &");                
    }

    private function _generarPortadasMiCanalXId($id) {
        $resquery = $this->portadas_mp->getPortadasXId($id);

        if (count($resquery) > 0) {

            foreach ($resquery as $value) {
                Log::erroLog($value->estado_migracion . "  -  " . $value->estado);

                if ($value->estado == 1) {

                    $array = array();

                    $array["tipo"] = "portada";
                    $array["nombre"] = ($value->nombre);
                    $array["estado"] = "1";


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
                } elseif ($value->estado == 0 || $value->estado == 2) {
                    //eliminacion item en coleccion micanal                    
                    $id_mongo = new MongoId($value->id_mongo);
                    $this->micanal_mp->setItemCollectionUpdate(array("estado" => "0"), array('_id' => $id_mongo));
                    //$this->micanal_mp->SetItemCollectionDelete(array('_id' => $id_mongo));
                    $this->micanal_mp->updateEstadoMigracionPortadasActualizacion($value->id);
                }
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
    
    
    public function curlGenerarSeccionesMiCanalXSeccionId($id){
        Log::erroLog("ini - curlGenerarSeccionesMiCanalXSeccionId: " . $id );
        $ruta = base_url("curlproceso/generarSeccionesMiCanalXSeccionId/" . $id);
        shell_exec("curl " . $ruta . " > /dev/null 2>/dev/null &");  
    }
         
    public function generarSeccionesMiCanalXSeccionId($id) {
          Log::erroLog("private curlGenerarSeccionesMiCanalXSeccionId: " . $id );
        $this->_generarSeccionesMiCanalXSeccionId($id);
    }
    
    private function _generarSeccionesMiCanalXSeccionId($id) {

        $array = array();

        $resquery = $this->secciones_mp->getSeccionesXId($id);
        Log::erroLog("->  dato entrada    : " . $id . " -- ");
        Log::erroLog("->  cantidad   : " . count($resquery) . " -- ");

        if (count($resquery) != 0) {

            foreach ($resquery as $value) {

                if ($value->estado == 1) {

                    $array["tipo"] = "seccion";
                    $array["nombre"] = $value->nombre;
                    $array["peso"] = $value->peso;
                    $array["template"] = $value->templates_id;
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
                            $array["alias"] = "#";
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

                    Log::erroLog("- > id_mongo de seccion : " . $id_mongo);
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

         Log::erroLog(" :D  ".$id." - > id_mongo de seccion : " . $id_mongo);

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

                $urltemp = "";

                if (!empty($idtemp)) {
                    $resquery3 = $this->micanal_mp->queryProcedure(4, $idtemp);
                    $row3 = $resquery3;

                    $arrtemp["canal"] = $row3[0]->xcanal;
                    $arrtemp["fecha"] = $row3[0]->xfechatransmision;
                    $arrtemp["coleccion"] = $row3[0]->xcoleccion;
                    $arrtemp["programa"] = $row3[0]->xprograma;
                    $arrtemp["lista_reproduccion"] = $row3[0]->xlistareproduccionalias;
                    $arrtemp["duracion"] = $row3[0]->xduracion;
                    $arrtemp["categoria"] = $row3[0]->xcategoria;
                    $arrtemp["descripcion"] = (!empty($value2->descripcion_item)) ? strip_tags($value2->descripcion_item) : strip_tags($row3[0]->xdescripcion);
                    $arrtemp["reproducciones"] = $row3[0]->xvi_rep;
                    $arrtemp["comentarios"] = $row3[0]->xvi_com;
                    $arrtemp["valoracion"] = $row3[0]->xvi_val;
                    $arrtemp["peso"] = $value2->peso;
                    $arrtemp["video"] = strip_tags($row3[0]->xvideo);
                    Log::erroLog("video" . $id .",xvideo".$row3[0]->video);

                   if (!empty($value2->canales_id) && empty($row3[0]->xvideoalias)) {
                        $urltemp = "canal/" . $row3[0]->xcanalalias;
                    } elseif ($value2->tipo_secciones_id == 1 && $value2->tipo_portadas_id == 5) {
                        $urltemp = "programa/" . $row3[0]->xprogramaalias;
                    } elseif ($value2->tipo_secciones_id == 2 && $value2->tipo_portadas_id == 5) {
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

    public function generarCanal() {
//        $this->_generarCanales();
//        $this->_generarProgramas();
        $this->_generarVideos();
        $this->_generarDetalleVideos();
    }

    private function _generarCanales() {

        $resquery = $this->canal_mp->queryMysqlCanal(1, "");

        if (count($resquery) > 0) {
            //while ($row = $resquery->fetch_object()) {

            foreach ($resquery as $value) {
                if (($value->estado_migracion == 0 or $value->estado_migracion == 9 ) && $value->estado == 1) {

                    $arrcanal = array();

                    $arrcanal['canal_id'] = $value->id;

                    $objmongo['canal'] = $arrcanal['canal'] = ($value->nombre);
                    $objmongo['descripcion'] = $arrcanal['descripcion'] = ($value->descripcion);
                    $objmongo['url'] = $arrcanal['url'] = $value->alias;
                    //$objmongo['imagen'] = $arrcanal['imagen'] = "canal.jpg";
                    //$objmongo['logo'] = $arrcanal['logo'] = "logo.jpg";
                    $objmongo['padre'] = $arrcanal['padre'] = "";
                    $objmongo['nivel'] = $arrcanal['nivel'] = "0";

                    $arrcanal['apikey'] = $value->apikey;
                    $arrcanal['playerkey'] = $value->playerkey;

                    if ($value->estado == 1) {
                        if ($value->estado_migracion == 0) {
                            $id_mongo = $this->canal_mp->setItemCollection($objmongo);
                            $this->canal_mp->updateIdMongoCanales($value->id, $id_mongo);
                            $this->canal_mp->updateEstadoMigracionCanales($value->id);
                        } elseif ($value->estado_migracion == 9) {
                            $id_mongo = new MongoId($value->id_mongo);
                            $this->canal_mp->setItemCollectionUpdate($objmongo, array('_id' => $id_mongo));
                            $this->canal_mp->updateEstadoMigracionCanalesActualizacion($value->id);
                        }
                    }


//                    $id_mongo = $this->canal_mp->SetItemCollection($objmongo);
//                    $arrcanal['idmongo'] = $id_mongo;
//
//                    $this->canal_mp->updateIdMongoCanales($value->id, $id_mongo);
//                    $this->canal_mp->updateEstadoMigracionCanales($value->id);

                    unset($objmongo);

                    //$this->ListaProgramas($arrcanal);
                } elseif ($value->estado == 0 || $value->estado == 2) {
                    //eliminacion item en coleccion micanal                    
                    $id_mongo = new MongoId($value->id_mongo);
                    $this->canal_mp->setItemCollectionUpdate(array("estado" => "0"), array('_id' => $id_mongo));
                    //$this->canal_mp->SetItemCollectionDelete(array('_id' => $id_mongo));
                    $this->canal_mp->updateEstadoMigracionCanalesActualizacion($value->id);
                }
            }
        }
    }

    private function _generarProgramas() {

        $resquerypro = $this->canal_mp->queryMysqlCanal(2, "");
        //echo "cantidad:  ".count($resquerypro)."\n";

        if (count($resquerypro) > 0) {
            //while ($row = $resquerypro->fetch_object()) {
            foreach ($resquerypro as $value) {

                if (($value->estado_migracion == 0 or $value->estado_migracion == 9 ) && $value->estado == 1) {
                    $arrprogramas = array();

                    $arrprogramas['grupo_maestro_padre'] = $value->id;

                    $objmongo['canal'] = $value->nombre_ca;
                    $objmongo['url'] = $arrprogramas['url'] = ($value->alias);
                    $objmongo['nombre'] = $arrprogramas['nombre'] = ($value->nombre);
                    $objmongo['descripcion'] = $arrprogramas['descripcion'] = ($value->descripcion);

                    //$objmongo['imagen'] = $arrprogramas['imagen'] = "programa.jpg";
                    $objmongo['categoria'] = $value->categorias_id;
                    $objmongo['comentarios'] = 0;

                    $objmongo['padre'] = $arrprogramas['padre'] = $value->idmongo_ca;
                    $objmongo['nivel'] = $arrprogramas['nivel'] = "1";

//                    $idmongo = $this->conexionmongodb->SetItemCollection($objmongo);
//                    $arrprogramas['idmongo'] = $idmongo;
                    //$query = "update default_cms_grupo_maestros set id_mongo='" . $idmongo . "' where id=" . $value["id"];

                    if ($value->estado == 1) {
                        if ($value->estado_migracion == 0) {
                            $id_mongo = $this->canal_mp->setItemCollection($objmongo);
                            $this->canal_mp->updateIdMongoGrupoMaestros($value->id, $id_mongo);
                            $this->canal_mp->updateEstadoMigracionGrupoMaestros($value->id);
                        } elseif ($value->estado_migracion == 9) {
                            $id_mongo = new MongoId($value->id_mongo);
                            $this->canal_mp->setItemCollectionUpdate($objmongo, array('_id' => $id_mongo));
                            $this->canal_mp->updateEstadoMigracionGrupoMaestrosActualizacion($value->id);
                        }
                    }
                } elseif ($value->estado == 0 || $value->estado == 2) {
                    //eliminacion item en coleccion micanal                    
                    $id_mongo = new MongoId($value->id_mongo);
                    //$this->canal_mp->SetItemCollectionDelete(array('_id' => $id_mongo));
                    $this->canal_mp->setItemCollectionUpdate(array("estado" => "0"), array('_id' => $id_mongo));
                    $this->canal_mp->updateEstadoMigracionGrupoMaestrosActualizacion($value->id);
                }


                //$this->conexionmysql->setConsulta($query);
                unset($objmongo);


                //$this->ListaColecciones($arrcanal, $arrprogramas);
            }
        }
    }

    private function _generarVideos() {

        $videosactivos = $this->canal_mp->queryMysqlCanal(5, "");
        //print_r($videosactivos);

        foreach ($videosactivos as $value) {

            if ($value->estado == 2) {
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
//
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
                $objmongo['valoracion'] = $datovideo[0]->xvi_val;
                $objmongo['estado'] = "1";

                if ($datovideo[0]->xfechatransmision == $datovideo[0]->xlistareproduccion) {
                    $urltemp = $datovideo[0]->xprogramaalias . "/" . $datovideo[0]->xfechatransmision . "-" . $datovideo[0]->xvideoalias; //  2. micanal.pe/[programa]/[fecha]-[video]-id [ nombre de lista es igual a la fecha de transmisi?n de los videos.                      
                } else {
                    $urltemp = $datovideo[0]->xprogramaalias . "/" . $datovideo[0]->xlistareproduccionalias . "/" . $datovideo[0]->xfechatransmision . "-" . $datovideo[0]->xvideoalias; //  1. micanal.pe/[programa]/[lista]/[fecha]-[video]-id                        
                }

                $objmongo['url'] = $urltemp;
                //$objmongo['padre'] = $arrlistarepro['idmongo'];
                $objmongo['nivel'] = "4";

                if ($value->estado_migracion == 0) {
                    $mongo_id = $this->canal_mp->setItemCollection($objmongo);
                    $this->canal_mp->updateIdMongoVideos($value->id, $mongo_id);
                    $this->canal_mp->updateEstadoMigracionVideos($value->id);
                } elseif ($value->estado_migracion == 9) {
                    $mongo_id = $value->id_mongo;
                    $MongoId = array("_id" => new MongoId($value->id_mongo));
                    $this->canal_mp->setItemCollectionUpdate($objmongo, $MongoId);
                    $this->canal_mp->updateEstadoMigracionVideosActualizacion($value->id);
                    //print_r($set);
                }

                $this->_generarDetalleVideosXId($value->id, $mongo_id);
                unset($objmongo);
            } else {
                $id_mongo = new MongoId($value->id_mongo);
                $this->canal_mp->setItemCollectionUpdate(array("estado" => "0"), array('_id' => $id_mongo));
            }
        }
    }

    private function _generarDetalleVideos() {
        $videos = $this->videos_mp->getVideosActivos();

        foreach ($videos as $value) {

            $where = array("_id" => new MongoId($value->id_mongo));

            if (!empty($value->id)) {
                $imagenes = $this->imagenes_mp->getImagenesVideos($value->id);

                foreach ($imagenes as $rowx) {
                    if ($rowx->procedencia == 0) {
                        $arrimagen[$rowx->ancho . "x" . $rowx->alto] = "http://" . $this->config->item('server:elemento') . "/" . $rowx->imagen;
                    } else {
                        $arrimagen[$rowx->ancho . "x" . $rowx->alto] = $rowx->imagen;
                    }
                }
                $set = array("imagen" => $arrimagen);
                $this->canal_mp->SetItemCollectionUpdate($set, $where);

                $playlist = $this->videos_mp->getVideosPlaylist($value->id);

                $arrayplaylist = array();

                $i = 0;
                foreach ($playlist as $codigo) {
                    $arrayplaylist[$i] = new MongoId($codigo->id_mongo);
                    $i++;
                }

                $set = array("playlist" => $arrayplaylist);
                $this->canal_mp->SetItemCollectionUpdate($set, $where);
            }
        }
    }

    public function generarGrupoMaestroXId($tgm, $id) {
        ////error_log($tgm,$id);
        switch ($tgm) {
            case 3;
                $this->_generarProgramasXId($id);
                break;
            case 2;
                $this->_generarColeccionXId($id);
                break;
            case 1;
                $this->_generarListaReproduccionXId($id);
                break;
        }
    }

    public function curlGenerarCanalesXId($id){
        Log::erroLog("ini - curlGenerarCanalesXId: " . $id );
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
                ////error_log("estado: " . $value->estado . " >> ". $value->estado_migracion );

                if (($value->estado_migracion == 0 or $value->estado_migracion == 9 ) && $value->estado == 1) {

                    $objmongo = array();
                    $objmongo['canal'] = strip_tags($value->nombre);
                    $objmongo['descripcion'] = strip_tags($value->descripcion);
                    $objmongo['url'] = $value->alias;

//                    
//                    if($value->procedencia == 0){                        
//                        $objmongo['imagen'] = "http://".$this->config->item('server:elemento')."/".$value->imagen;    
//                    }

                    $imagenes = $this->imagenes_mp->getImagenesCanalesXId($id);

                    foreach ($imagenes as $value2) {
                        $objmongo[$value2->nombre] = "http://" . $this->config->item('server:elemento') . "/" . $value2->imagen;
                    }

                    $objmongo['estado'] = $value->estado;
                    $objmongo['padre'] = "";
                    $objmongo['nivel'] = "0";
                    $objmongo['apikey'] = $value->apikey;
                    $objmongo['playerkey'] = $value->playerkey;
                    $objmongo['canal_cv'] = $value->canal_cv;
                    $objmongo['canal_cs'] = $value->canal_cs;

                    //error_log($value->canal_cv);


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
                } elseif ($value->estado == 0 || $value->estado == 2) {
                    $id_mongo = new MongoId($value->id_mongo);
                    $this->canal_mp->setItemCollectionUpdate(array("estado" => "0"), array('_id' => $id_mongo));
                    $this->canal_mp->updateEstadoMigracionCanalesActualizacion($value->id);
                }
            }
        }
    }

//    public function generarProgramasXId($id){
//        $this->_generarProgramasXId($id);
//    }
//    
//    private function _generarProgramasXId($id){
//        
//        ////error_log("id: " . $id);
//        
//        $programa= $this->grupo_maestros_mp->getProgramasXId($id);
//
//        if (count($programa) > 0) {
//
//            foreach ($programa as $value) {
//                ////error_log("estado: " . $value->estado . " >> ". $value->estado_migracion );
//                
//                if (($value->estado_migracion == 0 or $value->estado_migracion == 9 ) && $value->estado == 1) {
//
//                    $objmongo = array();
//                    $objmongo['canal'] =strip_tags($value->nombre_ca);
//                    $objmongo['nombre'] =strip_tags($value->nombre);
//                    $objmongo['descripcion'] =strip_tags($value->descripcion);
//                    $objmongo['url'] = $value->alias;
//                    $objmongo['estado'] = "1";
//                    $objmongo['padre'] = $value->idmongo_ca;
//                    $objmongo['nivel'] = "1";
//                    
//                    if ($value->estado == 1) {
//                        if (empty($value->id_mongo)) {
//                            $id_mongo = $this->canal_mp->setItemCollection($objmongo);
//                            $this->canal_mp->updateIdMongoCanales($value->id, $id_mongo);
//                            $this->canal_mp->updateEstadoMigracionCanales($value->id);
//                        } else {
//                            $id_mongo = new MongoId($value->id_mongo);
//                            $this->canal_mp->setItemCollectionUpdate($objmongo, array('_id' => $id_mongo));
//                            $this->canal_mp->updateEstadoMigracionCanalesActualizacion($value->id);
//                        }
//                        
//                    }
//                    unset($objmongo);
//                    
//                } elseif ($value->estado == 2) {
//                    $id_mongo = new MongoId($value->id_mongo);
//                    $this->canal_mp->setItemCollectionUpdate(array("estado" => "0"), array('_id' => $id_mongo));                    
//                    $this->canal_mp->updateEstadoMigracionCanalesActualizacion($value->id);
//                }
//            }
//        }
//    }
//        
//    public function generarColeccionesXId($id){
//        $this->_generarColeccionesXId($id);
//    }
//    
//    private function _generarColeccionesXId($id){
//        
//        
//    }

    private function _generarVideosXId($id) {

        $video = $this->videos_mp->getVideosxId($id);

        if (count($video) > 0) {
            foreach ($video as $value) {


                if ($value->estado == 1 || $value->estado == 2) {
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
//
                    $objmongo['categoria'] = $datovideo[0]->xcategoria;
                    $objmongo['reproducciones'] = $datovideo[0]->xvi_rep;
                    $objmongo['lista_reproduccion'] = ($datovideo[0]->xlistareproduccion);
                    $objmongo['duracion'] = $datovideo[0]->xduracion;
                    $objmongo['media'] = $datovideo[0]->xcodigo;
//                  $objmongo['media'] = $value->codigo; //retirar
//                
                    $objmongo['comentarios'] = $datovideo[0]->xvi_com;
                    $objmongo['related'] = array();
                    $objmongo['playlist'] = array();
                    $objmongo['clips'] = array();
//                  $objmongo['playerkey'] = $value->playerkey; //retirar                 
                    $objmongo['playerkey'] = $datovideo[0]->xplayerkey;
//                  $objmongo['apikey'] = $value->apikey; //retirar
                    $objmongo['apikey'] = $datovideo[0]->xapikey;

                    $objmongo['valoracion'] = $datovideo[0]->xvi_val;
                    $objmongo['estado'] = ($value->estado == 2) ? "1" : "0";

                    ////error_log($datovideo[0]->xprogramaalias);

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
                    //$objmongo['padre'] = $arrlistarepro['idmongo'];
                    $objmongo['nivel'] = "4";

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
                    
                                 

                    //if ($value->estado_migracion == 0 || $value->estado_migracion == 9) {
                    $this->_generarDetalleVideosXId($value->id, $mongo_id);
                    //}

                    unset($objmongo);
                } else {
                    $id_mongo = new MongoId($value->id_mongo);
                    $this->canal_mp->setItemCollectionUpdate(array("estado" => "0"), array('_id' => $id_mongo));
                }
            }
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

        ////error_log("CASA: ". $id ."=>".$mongo_id);

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
            
            //error_log(count($playlist));
            

            $arrayplaylist = array();

            $i = 0;
            foreach ($playlist as $datos) {
                $arrayplaylist[$i] = new MongoId($datos->id_mongo);
                $i++;
            }
            
            //error_log(count($playlist));
            
            foreach ($playlist as $datos2) {         
                 ////error_log("cantidad: " . count($arrayplaylist). "para :" .$datos2->id_mongo);                 
                 $set = array("playlist" => $arrayplaylist);
                 $tempmongo = array("_id" => new MongoId($datos2->id_mongo));
                 $this->canal_mp->SetItemCollectionUpdate($set,$tempmongo);
            }               


            $tags = $this->video_tags_mp->getTagsVideosXId($id);
            //  print_r($tags);

            $clienteSOAP = new SoapClient($this->config->item('motor') . "/" . EC_CLIENTE_SOAP);

            $parametros = array();

            $parametros["estado"] = array(1);
            $parametros["peso_videos"] = array("titulo" => 15, "descripcion" => 5, "tags" => 80);


            $this->resultado = $clienteSOAP->BusquedaRelacionado(json_encode($parametros), $tags[0]->tags);

            $resultado = json_decode($this->resultado);

            $arrayrelacionados = array();

            $i = 0;
            foreach ($resultado as $value) {
                if ($value != $mongo_id) {
                    $arrayrelacionados[$i] = new MongoId($value);
                    $i++;
                }
            }

            $itemsclips = $this->videos_mp->getVideosClips($id);
            $arrayitemclips = array();

            $i = 0;
            foreach ($itemsclips as $value) {
                $arrayitemclips[$i] = new MongoId($value->id_mongo);
                $i++;
            }

            $set = array("imagen" => $arrimagen,  "clips" => $arrayitemclips, "related" => $arrayrelacionados);
            $this->canal_mp->SetItemCollectionUpdate($set, $MongoId);
            //$this->micanal_mp->SetItemCollectionUpdate(array("item" => $item), array('_id' => $mongoid));
        }
    }


    
    /* Canal Mongo - FIN */

    public function actualizarVideos() {
        $videos = $this->videos_mp->getVideosActivos();
        foreach ($videos as $value) {
            $this->_obtenerImagesUrlVideosXId($value->id);
            $this->_generarVideosXId($value->id);
        }
    }

    public function curlActualizarVideosXId($id){
        Log::erroLog("ini - curlActualizarVideosXId: " . $id );
        $ruta = base_url("curlproceso/actualizarVideosXId/" . $id);
        shell_exec("curl " . $ruta . " > /dev/null 2>/dev/null &");
    }
    
    public function actualizarVideosXId($id) {
        $this->_obtenerImagesUrlVideosXId($id);
        $this->_generarVideosXId($id);
    }

    public function activarVideosXId($id) {
        $this->_activarVideosXId($id);
    }

    private function _activarVideosXId($id) {
        $this->canal_mp->setItemCollectionUpdate(array("estado" => "2"), array('id' => $id));
    }
   
    public function curlActivarVideosXId($id){
        Log::erroLog("ini - curlDesactivarVideosXId: " . $id );
        $ruta = base_url("curlproceso/activarVideosXId/" . $id);
        shell_exec("curl " . $ruta . " > /dev/null 2>/dev/null &");
    }
    
    public function desactivarVideosXId($id) {
        $this->_desactivarVideosXId($id);
    }

    private function _desactivarVideosXId($id) {
        $this->canal_mp->setItemCollectionUpdate(array("estado" => "0"), array('id' => $id));
    }    
    
    public function curlDesactivarVideosXId($id){
        Log::erroLog("ini - curlDesactivarVideosXId: " . $id );
        $ruta = base_url("curlproceso/desactivarVideosXId/" . $id);
        shell_exec("curl " . $ruta . " > /dev/null 2>/dev/null &");
    }
    
    public function actualizarSecciones6789() {
        Log::erroLog("_actualizarVisualizacion");
        //$this->_actualizarVisualizacion();
        Log::erroLog("_actualizarComentariosValorizacion");
        $this->_actualizarComentariosValorizacion();
        Log::erroLog("_actualizarSecciones6789");
        $this->_actualizarSecciones6789();
    }

    private function _actualizarSecciones6789() {
        $this->micanal_mp->queryProcedure(1, "");
        $secciones = $this->secciones_mp->getSeccionesTipo6789();
        foreach ($secciones as $value) {
            $this->_generarSeccionesMiCanalXSeccionId($value->id);
        }
    }

    public function estadosVideos() {
        $videos = $this->videos_mp->getVideos();

        echo "<table border=1><tr><td>id</td><td>estado_liquid</td><td>codigo</td><td>estado</td><td>id_mongo</td><td>fecha_migracion</td><td>fecha_migracion_actualizacion</td></tr>";

        foreach ($videos as $value) {
            echo "<tr><td>" . $value->id . "</td><td>" . $value->estado_liquid . "</td><td>" . $value->codigo . "</td><td>" . $value->estado . "</td><td>" . $value->id_mongo . "</td><td>" . $value->fecha_migracion . "</td><td>" . $value->fecha_migracion_actualizacion . "</td></tr>";
        }
        echo "</table>";
    }

    public function getMaestroDetalles() {
        $videos = $this->grupo_maestros_mp->getMaestroDetalles();

        echo "<table border=1><tr><td>id</td><td>grupo_maestro_id</td><td>cant</td>";

        foreach ($videos as $value) {
            echo "<tr><td>" . $value->id . "</td><td>" . $value->grupo_maestro_id . "</td><td>" . $value->cant . "</td></tr>";
        }
        echo "</table>";
    }

    public function getMaestroDetallesXId($id) {
        $videos = $this->grupo_maestros_mp->getMaestroDetallesXId($id);

        foreach ($videos as $value) {
            echo "<pre>" . print_r($value) . "</pre>";
        }
    }

    public function deleteMaestroDetallesXId($id) {
        $this->grupo_maestros_mp->deleteMaestroDetallesXId($id);
        echo "ok";
    }

    public function datosVideos($id) {
        print_r($this->canal_mp->queryProcedure(4, $id));
    }

    public function showProFun() {
        print_r($this->videos_mp->getShowProcedure());
        print_r($this->videos_mp->getShowFunction());
    }

    public function showLog($date) {
        $ruta = $this->config->item('path:log') . $date . ".txt";

        $file = fopen($ruta, "r") or exit("ERRO AL ABRIR EL ARCHIVO");

        while (!feof($file)) {
            echo fgets($file) . "<br />";
        }
        fclose($file);
    }

}

?>