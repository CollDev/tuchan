<?php

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
        $this->load->library("Procesos/proceso");
        $this->load->library("Procesos/liquid");
        $this->load->library("Procesos/ffmpeg");
    }

    public function index() {
        $this->_actualizarComentariosValorizacion();
    }

    /* Corte video  -  INICIO */

    public function corte_Video($id_padre, $id_hijo, $inicio, $duracion) {
        $datos = array();
        $datos["id_padre"] = $id_padre;
        $datos["id_hijo"] = $id_hijo;
        $datos["inicio"] = $inicio;
        $datos["duracion"] = $duracion;
        $result = ci()->videos_mp->getVideosxId($id_padre);
        $datos["ruta"] = $result[0]->ruta;
        Proceso::corte_Video($datos);
    }

    /* Corte video  -  Fin */


    /* Actualizar Visualizaciones Liquid  -  INICIO */

    private function actualizarVisualizacion() {

        $arrcanales = $this->canales_mp->getCanales();

        foreach ($arrcanales as $value) {

            $arrayViews = $this->liquid->obtenernumberOfViews($value->apikey);
            foreach ($arrayViews as $value) {
                //print_r($value);
                $this->videos_mp->setReproducciones($value["id"], $value["numberOfViews"]);
            }
        }
    }

    /* Actualizar Visualizaciones Liquid  -  FIN */

    /* Actualizar Comentarios Valoracion   -  INICIO */

    private function _actualizarComentariosValorizacion() {
        $videos = $this->videos_mp->getVideosActivos();
        foreach ($videos as $value) {
            $id_mongo = new MongoId($value->id_mongo);
            $videomongo = $this->canal_mp->getItemCollection($id_mongo);
            $this->videos_mp->setComentariosValorizacion($value->id, $videomongo[0]["comentarios"], $videomongo[0]["valoracion"]);
        }
    }

    /* Actualizar Comentarios Valoracion -  FIN */



    /* Actualizar comentarios y valorizaciones de Mysql a Mongo */

    /* Subir Videos - INICIO */

    public function procesoVideos() {
        error_log('iniciando - convertir');
        $this->_convertirVideos();
        error_log('iniciando - upload');
        $this->_uploadVideos();
        error_log('iniciando - publicar video');
        $this->_publishVideos();
        error_log('iniciando - obtiene URL');
        $this->_obtenerImagesUrlVideos();
    }

    private function _convertirVideos() {

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

    private function _uploadVideos() {

        $resultado = $this->videos_mp->getVideosMp4();
//        error_log('-------------------lista de videos mp4---------------------');
//        error_log(print_r($resultado, true));
        if (count($resultado) > 0) {
            foreach ($resultado as $value) {

                $this->videos_mp->setEstadosVideos($value->id, 0, 3);
                $retorno = Liquid::uploadVideoLiquid($value->id, $value->apikey);

                if ($retorno != FALSE) {
//                    error_log('----------------------------------------');
//                    error_log(print_r($retorno, true));
                    $this->videos_mp->setEstadosVideos($value->id, 0, 4);
                    $this->videos_mp->setMediaVideos($value->id, $retorno);
                } else {

                    $this->videos_mp->setEstadosVideos($value->id, 0, 2);
                }
            }
        }
    }

    private function _publishVideos() {
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
    
    public function actualizarPortadas(){
        $this->_generarPortadasMiCanal();
    }

    private function _generarPortadasMiCanal() {

        $resquery = $this->micanal_mp->queryMysql(1, "");

        if (count($resquery) > 0) {

            foreach ($resquery as $value) {

                if (($value->estado_migracion == 0 or $value->estado_migracion == 9 ) && $value->estado == 1) {

                    $array = array();
                    
                    $array["tipo"] = "portada";
                    $array["nombre"] = ($value->nombre);
                    $array["estado"]="1";


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
                            $this->micanal_mp->setItemCollectionUpdate(array('_id' => $id_mongo), $objmongo);                            
                            $this->micanal_mp->updateEstadoMigracionPortadasActualizacion($value->id);
                        }
                    }
                    unset($objmongo);
                    unset($array);
                } elseif ($value->estado == 0 || $value->estado == 2) {
                    //eliminacion item en coleccion micanal                    
                    $id_mongo = new MongoId($value->id_mongo);
                    $this->micanal_mp->setItemCollectionUpdate(array('_id' => $id_mongo),array("estado"=>"0"));
                    //$this->micanal_mp->SetItemCollectionDelete(array('_id' => $id_mongo));
                    $this->micanal_mp->updateEstadoMigracionPortadasActualizacion($value->id);
                }
            }
        }
    }

    public function actualizarSecciones(){
        $this->_generarSeccionesMiCanal();
    }
    
    private function _generarSeccionesMiCanal() {

        $array = array();


        $resquery = $this->micanal_mp->queryMysql(2, "");



        //echo count($resquery) . "\n";

        if (count($resquery) != 0) {

            foreach ($resquery as $value) {

                if (($value->estado_migracion == 0 || $value->estado_migracion == 9) && $value->estado == 1) {

                    $array["tipo"] = "seccion";
                    $array["nombre"] = $value->nombre;
                    $array["peso"] = $value->peso;
                    $array["template"] = $value->templates_id;
                    $array["padre"] = $value->mongo_po;

                    $array["alias_pa"] = $value->alias_pa;
                    $array["alias_se"] = $this->urls_amigables($value->nombre);

                    //echo $value->tipo_portadas_id . " - " . $value->tipo_secciones_id;

                    if ($value->tipo_portadas_id == 5 and $value->tipo_secciones_id == 1) {


                        $datos2 = $this->micanal_mp->queryMysql(5, $value->origen_id);

                        if (count($datos2) == 1) {
                            $array["canal_des"] = $datos2[0]->canal_des;
                            $array["canal_cv"] = $datos2[0]->canal_cv;
                            $array["canal_img"] = PATH_ELEMENTOS . $datos2[0]->canal_img;
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

                    if ($value->estado_migracion == 0) {
                        $id_mongo = $this->micanal_mp->SetItemCollection($objmongo);
                        $array["id_mongo"] = $id_mongo;
                        $array["id"] = $value->id;
                        $this->micanal_mp->updateIdMongoSecciones($value->id, $id_mongo);
                        $this->micanal_mp->updateEstadoMigracionPortadas($value->id);
                    } elseif ($value->estado_migracion == 9) {
                        $id_mongo = new MongoId($value->id_mongo);
                        $this->micanal_mp->SetItemCollectionUpdate(array('_id' => $id_mongo), $objmongo);
                        $this->updateEstadoMigracionPortadasActualizacion($value->id);
                    }

                    unset($array);
                    unset($objmongo);
                } elseif ($value->estado == 2) {

                    $id_mongo = new MongoId($value->mongo_se);
                    $this->conexionmongodb->SetItemCollectionDelete(array('_id' => $id_mongo));
                    $this->updateEstadoMigracionActualizacion($value->id);
                }
            }
        }
    }
    
    public function actualizarDetalleSecciones(){
        $this->_generarDetalleSeccionesMiCanal();
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
                        $arrtemp["imagen"] = PATH_ELEMENTOS . $value2->imagen;
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
                            $this->canal_mp->setItemCollectionUpdate(array('_id' => $id_mongo), $objmongo);
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
                    $this->canal_mp->SetItemCollectionDelete(array('_id' => $id_mongo));
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
                            $this->canal_mp->setItemCollectionUpdate(array('_id' => $id_mongo), $objmongo);
                            $this->canal_mp->updateEstadoMigracionGrupoMaestrosActualizacion($value->id);
                        }
                    }
                } elseif ($value->estado == 0 || $value->estado == 2) {
                    //eliminacion item en coleccion micanal                    
                    $id_mongo = new MongoId($value->id_mongo);
                    $this->canal_mp->SetItemCollectionDelete(array('_id' => $id_mongo));
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
                $objmongo['logo'] = PATH_ELEMENTOS . $value->imagen;
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
                    $where = array("_id" => new MongoId($value->id_mongo));
                    $set = $objmongo;
                    $this->canal_mp->setItemCollectionUpdate($set, $where);
                    $this->canal_mp->updateEstadoMigracionVideosActualizacion($value->id);
                    //print_r($set);
                }


                unset($objmongo);
            } elseif ($value->estado = 3) {
                
            }
        }
    }

    private function _generarDetalleVideos() {
        $videos = $this->videos_mp->getVideosActivos();

        foreach ($videos as $value) {
            //print_r($value);
            $where = array("_id" => new MongoId($value->id_mongo));

            if (!empty($value->id)) {
                $imagenes = $this->canal_mp->queryMysqlImagen($value->id);

                foreach ($imagenes as $rowx) {
                    if ($rowx->procedencia == 0) {
                        $arrimagen[$rowx->ancho . "x" . $rowx->alto] = PATH_ELEMENTOS . $rowx->imagen;
                    } else {
                        $arrimagen[$rowx->ancho . "x" . $rowx->alto] = $rowx->imagen;
                    }
                }
                $set = array("imagen" => $arrimagen);
                $this->canal_mp->SetItemCollectionUpdate($set, $where);


                $relacionados = $this->canal_mp->queryMysqlRelated($value->id);


                $arrayrelated = array();

                $i = 0;
                foreach ($relacionados as $codigo) {
                    $arrayrelated[$i] = new MongoId($codigo->id_mongo);
                    $i++;
                }

                //print_r($arrayrelated);

                $set = array("playlist" => $arrayrelated);
                $this->canal_mp->SetItemCollectionUpdate($set, $where);
            }
        }
    }

    /* Canal Mongo - FIN */
}

?>