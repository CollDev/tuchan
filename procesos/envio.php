<?php

include_once 'config.php';

class Envio_Videos {

    var $conexionmysql;
    var $claseffmpeg;
    var $claseliquid;

    function __construct() {
        $this->conexionmysql = new Conexion_MySql();
        $this->claseffmpeg = new Ffmpeg();
        $this->claseliquid = new Liquid();
    }

    function UpdateEstadosVideos($id = "", $estado = "", $estado_liquid = "") {
        $query = "update default_cms_videos set estado=" . $estado . ",estado_liquid =" . $estado_liquid . " where id=" . $id;
        echo $query . "\n";
        $this->conexionmysql->setUpdate($query);
    }

    function UpdateMediaVideos($id = "", $media = "") {
        $query = "update default_cms_videos set codigo='" . $media . "' where id=" . $id;
        echo $query . "\n";
        $this->conexionmysql->setUpdate($query);
    }

    function UpdateRutaVideos($id = "", $ruta = "") {
        $query = "update default_cms_videos set ruta='" . $ruta . "' where id=" . $id;
        echo $query . "\n";
        $this->conexionmysql->setUpdate($query);
    }

    function InsertImagenVideos($datos) {

        $query = "insert default_cms_imagenes(videos_id,imagen,tipo_imagen_id,estado,fecha_registro,imagen_padre,procedencia) values ('" . $datos["videos_id"] . "','" . $datos["imagen"] . "','" . $datos["tipo_imagen_id"] . "',1,now()," . $datos["imagen_padre"] . ",1)";
        echo $query;
        return $this->conexionmysql->setQueryInsertReturnId($query);
    }

    function ConvertirVideos() {
        $query = "SELECT vi.id FROM default_cms_videos vi WHERE vi.estado_liquid = 0";


        $resultado = $this->conexionmysql->setQueryRows($query);
        echo print_r($resultado) . "\n";

        if (count($resultado) > 0) {
            foreach ($resultado as $value) {
                $this->UpdateEstadosVideos($value["id"], 0, 1);
                if ($this->claseffmpeg->convertVideotoMp4($value["id"])) {
                    $this->UpdateEstadosVideos($value["id"], 0, 2);
                }
            }
        }
    }

    function UploadVideos() {
        $query = "SELECT vi.id,ca.apikey FROM default_cms_videos vi            
                INNER JOIN default_cms_canales ca ON  vi.canales_id=ca.id
                WHERE vi.estado_liquid=2";

        $resultado = $this->conexionmysql->setQueryRows($query);
        echo print_r($resultado) . "\n";
        if (count($resultado) > 0) {
            foreach ($resultado as $value) {

                $this->UpdateEstadosVideos($value["id"], 0, 3);
                $retorno = $this->claseliquid->uploadVideoLiquid($value["id"], $value["apikey"]);

                if ($retorno != FALSE) {
                    echo "Retorno 1: " . $retorno;
                    $this->UpdateEstadosVideos($value["id"], 0, 4);
                    $this->UpdateMediaVideos($value["id"], $retorno);
                } else {
                    echo "Retorno 2: " . $retorno;
                    $this->UpdateEstadosVideos($value["id"], 0, 2);
                }
            }
        }
    }

    function PublishVideos() {
        $query = "SELECT vi.id,vi.titulo,vi.descripcion,vi.codigo,ca.apikey
                FROM default_cms_videos vi  INNER JOIN default_cms_canales ca ON  vi.canales_id=ca.id
                WHERE vi.estado_liquid=4";
        echo $query . "\n";

        $resultado = $this->conexionmysql->setQueryRows($query);
        echo print_r($resultado) . "\n";
        if (count($resultado) > 0) {
            foreach ($resultado as $value) {
                $retorno = $this->claseliquid->updatePublishedMediaNode($value);

                if ($retorno != FALSE) {
                    $this->UpdateEstadosVideos($value["id"], 0, 5);
                } else {
                    //$this->UpdateEstadosVideos($value["id"], 0, 2);
                }
            }
        }
    }

    function ObtenerImagesUrlVideos() {
        $query = "SELECT vi.id,vi.codigo,vi.ruta,ca.apikey,(select count(im.id) from default_cms_imagenes im  WHERE im.videos_id=vi.id and im.procedencia=1)as 'imag'
                    FROM default_cms_videos vi  
                    INNER  JOIN default_cms_canales ca ON  vi.canales_id=ca.id
                    WHERE vi.estado_liquid=5";

        $resultado = $this->conexionmysql->setQueryRows($query);

        if (count($resultado) > 0) {
            foreach ($resultado as $value) {

                $mediaarr = $this->claseliquid->obtenerDatosMedia($value);

                if (empty($value["ruta"])) {
                    $urlvideo = $this->claseliquid->getUrlVideoLiquidRawLite($mediaarr);
                    if (!empty($urlvideo)) {
                        $this->UpdateRutaVideos($value["id"], $urlvideo);
                    }
                }

                if ($value["imag"] == 0) {

                    $imagenes = $this->claseliquid->getimagenesLiquid($mediaarr);

                    if (count($imagenes) > 0) {
                        print_r($imagenes);
                        $datos = array();
                        $datos["videos_id"] = $value["id"];
                        $datos["imagen_padre"] = "null";

                        foreach ($imagenes as $value2) {
                            $datos["imagen"] = $value2["url"];
                            $datos["tipo_imagen_id"] = $value2["tipo_imagen_id"];
                            $datos["imagen_padre"] = $this->InsertImagenVideos($datos);
                        }
                    }
                }

                if ((!empty($value["ruta"]) || !empty($urlvideo)) && ($value["imag"] != 0 || !empty($datos["imagen"]))) {
                    $this->UpdateEstadosVideos($value["id"], 0, 6);
                }
            }
        }
    }

}

echo 'post_max_size = ' . ini_get('post_max_size') . "\n";
echo 'upload_max_filesize = ' . ini_get('upload_max_filesize') . "\n";
echo 'max_execution_time = ' . ini_get('max_execution_time') . "\n";

$claseEnvioVideos = new Envio_Videos();
$claseEnvioVideos->ConvertirVideos();
$claseEnvioVideos->UploadVideos();
$claseEnvioVideos->PublishVideos();
$claseEnvioVideos->ObtenerImagesUrlVideos();
?>