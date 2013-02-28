<?php

include_once 'liquid.php';
include_once 'util/conn_mysql.php';

class Mantenimiento {

    var $liquid = "";
    var $conexionmysql = "";

    function __construct() {
        $this->liquid = new Liquid();
        $this->conexionmysql = new Conexion();
    }

    function publicarPendientes() {


        $returconsulta = $this->conexionmysql->setConsulta("SELECT id,titulo,descripcion,codigo FROM default_cms_videos where estado_liquid in (4,5)");

        if ($returconsulta) {
            while ($row = $returconsulta->fetch_object()) {
                
                $arrdatos['id'] = $row->id;
                $arrdatos['fecha'] = date('Y-m-d H:i:s');
                $arrdatos['title'] = utf8_encode($row->titulo);
                $arrdatos['legend'] = strip_tags($row->descripcion);
                $arrdatos['codigo'] = $row->codigo;
                
               
               	//$this->liquid->updatePublishedMediaNode($arrdatos['codigo'], $arrdatos);
                
                
                //retorna datos del api
                $retorno = $this->liquid->obtenerDatosMedia($arrdatos['codigo']);

                echo "cantidad: " . count($retorno);
                if (count($retorno) != 0) {
                    //var_dump($arrdatos['id']);
                    $duration = gmdate("H:i:s", ($retorno['files']['file'][0]['videoInfo']['duration']/1000));//date('H:i:s',11);
                    //var_dump($duration);
                    $reproducciones = $retorno['numberOfViews'];
                    $query = "UPDATE default_cms_videos SET reproducciones='" . $reproducciones . "', duracion='" . $duration . "' WHERE id=" . $row->id;
                    //var_dump($query);
                    $this->conexionmysql->setConsulta($query);
                }
                // begin publicar videos
                //echo "published: ".$retorno["published"]."<br>";
                if ($retorno["published"] == "false") {
                    ///echo "entro aki";
                    $this->liquid->updatePublishedMediaNode($arrdatos['codigo'], $arrdatos);
                    //echo "paso aki";
                    $retornopublished = $this->liquid->obtenerDatosMedia($arrdatos['codigo']);
                    //echo "mostrando : ";
                    //print_r($retornopublished);

                    if ($retornopublished["published"] == "true") {
                        //echo "entro en 6";
                        $this->conexionmysql->updateEstadoVideosLiquid($row->id, 6);
                        //echo "FIN";
                    } else {
                        //echo "entro en 5";
                        $this->conexionmysql->updateEstadoVideosLiquid($row->id, 5);
                    }
                } else {

                    $this->conexionmysql->updateEstadoVideosLiquid($row->id, 6);
                }

                // end public videos
                //die();
            }
        }
    }

    function vd($var) {
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }

    function obtenerImagenes() {
        //retorna array de objetos 
        //$returconsulta = $this->conexionmysql->setConsulta("SELECT id,titulo,descripcion,codigo, canales_id FROM default_cms_videos where codigo!='' ");
        $returconsulta = $this->conexionmysql->setConsulta("SELECT DISTINCT v.id, v.codigo, i.`procedencia` 
			FROM default_cms_videos v
			LEFT JOIN default_cms_imagenes i ON i.`videos_id` = v.`id`
			WHERE v.codigo != '' AND (i.procedencia IS NULL OR i.`procedencia` = 0);");
        //iterar las video
        if (count($returconsulta) > 0) {
            foreach ($returconsulta as $index => $arrayVideo) {
                if (strlen(trim($arrayVideo['codigo'])) > 0) {
                    $arrayVideoLiquid = $this->liquid->obtenerImagenesMedia($arrayVideo['codigo']);
                    if (is_array($arrayVideoLiquid)) {
                        if (isset($arrayVideoLiquid['thumbs'])) {
                            //$this->vd($arrayVideoLiquid['thumbs']);
                            $this->saveImage($arrayVideoLiquid['thumbs'], $arrayVideo);
                        }
                    }
                }
            }
        }
    }

    function saveImage($arrayThumbs, $arrayVideo) {
        require_once '../addons/shared_addons/modules/videos/config/uploads.php';
        if (count($arrayThumbs) > 0) {
            $imagen_padre= NULL;
            foreach ($arrayThumbs as $index => $arrayImage) {
                if (is_array($arrayImage)) {
                    if (count($arrayImage) > 0) {
                        if (isset($arrayImage['url'])) {
                            $objBeanImage = new stdClass();
                            $objBeanImage->id = NULL;
                            $objBeanImage->canales_id = 'NULL';
                            $objBeanImage->grupo_maestros_id = 'NULL';
                            $objBeanImage->videos_id = $arrayVideo['id'];
                            $objBeanImage->imagen = $arrayImage['url'];
                            $objBeanImage->tipo_imagen_id = '1';
                            $objBeanImage->estado = '1';
                            $objBeanImage->fecha_registro = date("Y-m-d H:i:s");
                            $objBeanImage->usuario_registro = 'NULL';
                            $objBeanImage->fecha_actualizacion = date("Y-m-d H:i:s");
                            $objBeanImage->usuario_actualizacion = 'NULL';
                            $objBeanImage->estado_migracion = 0;
                            $objBeanImage->fecha_migracion = '0000-00-00 00:00:00';
                            $objBeanImage->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                            $objBeanImage->imagen_padre = 'NULL';
                            $objBeanImage->procedencia = 1;
                            //$objBeanImageSaved = $this->conexionmysql->saveImage($objBeanImage);
                            //$this->vd($objBeanImage);
                            //retorna array de objetos 
                            /* if($arrayVideo['id'] == '1075'){
                              $objBeanImageSaved = $this->conexionmysql->saveImage($objBeanImage);
                              $this->vd($objBeanImageSaved);
                              } */
                        } else {
                            foreach ($arrayImage as $indice => $itemImage) {
                                if (is_array($itemImage)) {
                                    $tipo_imagen = $this->conexionmysql->getTypeImage($itemImage['width']);
                                    if($tipo_imagen == 1){
                                        $imagen_padre = NULL;
                                    }
                                    $objBeanImage = new stdClass();
                                    $objBeanImage->id = NULL;
                                    $objBeanImage->canales_id = 'NULL';
                                    $objBeanImage->grupo_maestros_id = 'NULL';
                                    $objBeanImage->videos_id = $arrayVideo['id'];
                                    $objBeanImage->imagen = $itemImage['url'];
                                    $objBeanImage->tipo_imagen_id = $this->conexionmysql->getTypeImage($itemImage['width']);
                                    $objBeanImage->estado = '1';
                                    $objBeanImage->fecha_registro = date("Y-m-d H:i:s");
                                    $objBeanImage->usuario_registro = 'NULL';
                                    $objBeanImage->fecha_actualizacion = date("Y-m-d H:i:s");
                                    $objBeanImage->usuario_actualizacion = 'NULL';
                                    $objBeanImage->estado_migracion = 0;
                                    $objBeanImage->fecha_migracion = '0000-00-00 00:00:00';
                                    $objBeanImage->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                                    $objBeanImage->imagen_padre = $imagen_padre;
                                    $objBeanImage->procedencia = 1;
                                    //$this->vd($objBeanImage);
                                    if($objBeanImage->tipo_imagen_id >0){
                                        $objBeanImageSaved = $this->conexionmysql->saveImage($objBeanImage);
                                        if($objBeanImageSaved->tipo_imagen_id == 1){
                                            $imagen_padre = $objBeanImageSaved->id;
                                        }
                                    }
                                }
                            }
                        }////
                    }
                }
            }
        }
        echo "OK!";
    }

}

$mantenimiento = new Mantenimiento();
//$mantenimiento->obtenerImagenes();


while (true) {	
	$mantenimiento->publicarPendientes();
	$mantenimiento->obtenerImagenes();
	sleep(30);
}
?>