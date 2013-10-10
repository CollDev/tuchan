<?php

set_time_limit(TIME_LIMIT);

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Liquid {

    function postXML($url, $post) {
        POST_XML:
        Log::erroLog("postXML - url: " . $url);

        try {

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));

            Log::erroLog("iniciando envio de postXML ");
            $result = curl_exec($ch);
            Log::erroLog("finalizado envio de postXML ");
            $info = curl_getinfo($ch);

            Log::erroLog("http_code postXML: " . $info['http_code']);
            Log::erroLog("curl_errno: " . curl_errno($ch));

            if (!curl_errno($ch) && $info['http_code'] == '200') {
                curl_close($ch);
                Log::erroLog("paso publishd");
                return $result;
            } elseif ($info['http_code'] == '500') {
                sleep(15);
//                goto POST_XML;
                Log::erroLog("publishd datos genericos");
                return self::updatePublishedMediaGeneral($url);
            } else {
                sleep(5);
                Log::erroLog("no paso publish");
//                goto POST_XML;
            }
        } catch (Exception $exc) {
            return FALSE;
            //echo $exc->getTraceAsString();
        }
    }

    function getXml($url) {
        $i = 0;
        try {
            GET_XML:

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FAILONERROR, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);


            Log::erroLog("url get " . $url);

            $result = curl_exec($ch);
            $info = curl_getinfo($ch);

            if ($info['http_code'] == '200' && $info["content_type"] == 'application/xml') {
                curl_close($ch);
                Log::erroLog(" paso get");
                Log::erroLog(" result : " . $result);
                return $result;
            } else {

                if ($i == 10) {
                    return FALSE;
                } else {
                    $i++;
                    Log::erroLog(" no paso get");
                    sleep(5);
                    goto GET_XML;
                }
            }
        } catch (Exception $exc) {
            return "";
        }
    }

    function updatePublishedMediaGeneral($url) {
        $fecha = date('Y-m-d H:i:s');
        $date = date("Y-m-d\TH:i:sP", strtotime($fecha));

        Log::erroLog(" updatePublishedMediaGeneral  date: " . $date);
        $post = "<Media><title>Titulo</title><description>Descripcion</description><published>TRUE</published><publishDate>" . $date . "</publishDate></Media>";
        //echo $url . "<br>";
        return self::postXML($url, $post);
    }

    function updatePublishedMedia($apikey, $codigo) {
        $url = APIURL . "/medias/" . $codigo . "?key=" . $apikey;

        $post = "<Media><published>TRUE</published></Media>";

        Log::erroLog("url: " . $url);
        return self::postXML($url, $post);
    }

    function updateUnpublishedMedia($apikey, $codigo) {
        $url = APIURL . "/medias/" . $codigo . "?key=" . $apikey;
        $post = "<Media><published>false</published></Media>";
        Log::erroLog("url: " . $url);
        return self::postXML($url, $post);
    }

    function updatePublishedMediaNode($datos) {

        Log::erroLog("description: " . $datos->descripcion);
        Log::erroLog("titulo: " . $datos->titulo);

        $fecha = date('Y-m-d H:i:s');
        $date = date("Y-m-d\TH:i:sP", strtotime($fecha));

        Log::erroLog("$date: " . $date);

        $post = "<Media><published>TRUE</published>";
        $post .= "<description>" . strip_tags($datos->descripcion) . "</description>";
        $post .= "<publishDate>" . $date . "</publishDate>";
        $post .= "<title>" . $datos->titulo . "</title>";
        $post .= "</Media>";

        Log::erroLog("post: " . $post);

        $url = APIURL . "/medias/" . $datos->codigo . "?key=" . $datos->apikey;
        Log::erroLog("url pusblish: " . $url);


        $retorno = self::postXML($url, $post);

        Log::erroLog("retorno: " . $retorno);

        $pos = strpos($retorno, "SUCCESS");
        Log::erroLog("POS: " . $pos);

        if ($pos === false) {
            Log::erroLog("no paso SUCCESS");
            //goto PUBLISHED;
            return FALSE;
        } else {
            Log::erroLog("paso SUCCESS");
            return TRUE;
        }
    }

    function updatePublishedNode($datos) {

        Log::erroLog("description: " . $datos->descripcion);
        Log::erroLog("titulo: " . $datos->titulo);

        $post = "<Media><published>TRUE</published>";
        $post .= "<description>" . strip_tags($datos->descripcion) . "</description>";
        $post .= "<title>" . $datos->titulo . "</title>";
        $post .= "</Media>";

        Log::erroLog("post: " . $post);

        $url = APIURL . "/medias/" . $datos->codigo . "?key=" . $datos->apikey;
        Log::erroLog("url pusblish: " . $url);

        $retorno = self::postXML($url, $post);
        Log::erroLog("retorno: " . $retorno);

        $pos = strpos($retorno, "SUCCESS");
        Log::erroLog("POS: " . $pos);

        if ($pos === false) {
            Log::erroLog("no paso SUCCESS");
            //goto PUBLISHED;
            return FALSE;
        } else {
            Log::erroLog("paso SUCCESS");
            return TRUE;
        }
    }

//    function updateTitleMediaNode($mediaId, $datos, $apiKey) {
//
//        $mediaId = trim($mediaId);
//
//        $tags = '';
//        $date = date("Y-m-d\TH:i:sP", strtotime($datos->fecha));
//        $post = "<Media>" .
//                "<description><![CDATA[" . $datos->legend . "]]</description>" .
//                "<title><![CDATA[{$datos->title}]]</title>" .
//                $tags .
//                "</Media>";
//        $url = APIURL . "/medias/" . $mediaId . "?key=" . $apiKey;
//        //echo $url . "<br>";
//        return $this->postXML($url, $post);
//    }

    function uploadVideoLiquid($id_video, $apiKey) {

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
            curl_setopt($ch, CURLOPT_URL, WEBURL);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: multipart/form-data;"));
            curl_setopt($ch, CURLOPT_AUTOREFERER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, TIME_LIMIT);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 7);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

            Log::erroLog(PATH_VIDEOS);
            $post = array(
                "file" => "@" . PATH_VIDEOS . $id_video . ".mp4",
                "token" => $apiKey
            );

            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            Log::erroLog("inicio envio de video a liquid " . $id_video);
            $response = curl_exec($ch);
            Log::erroLog("fin envio de video a liquid " . $id_video);
            curl_close($ch);

            $mediaxml = new SimpleXMLElement($response);

            $mediaarr = json_decode(json_encode($mediaxml), true);

            $media = $mediaarr["media"]["@attributes"]["id"];
            Log::erroLog("media " . $media . " " . $id_video);

            if (!empty($media)) {
                return trim($media);
            }
        } catch (Exception $exc) {
            Log::erroLog("return FALSE de Exception");
            Log::erroLog("getMessage: " . $exc->getMessage);
            Log::erroLog("getLine: " . $exc->getLine);
            return '';
        }
    }

    function obtenerDatosMedia($datos) {

        $url = APIURL . "/medias/" . $datos->codigo . "?key=" . $datos->apikey . "&filter=id;thumbs;files;published";
        //error_log($url);

        Log::erroLog("url obtener datos: " . $url);

        $response = self::getXml($url);
        Log::erroLog("Response obtener datos" . $response);

        try {
            $mediaxml = new SimpleXMLElement($response);
            $mediaarr = json_decode(json_encode($mediaxml), true);
            return $mediaarr;
        } catch (Exception $exc) {
            return "";
        }
    }

    function obtenerVideosNoPublished($apikey) {


        if (!empty($apikey)) {


            $ini = 0;
            // max valor de $inc = 50
            $inc = 50;
            $flat = 1;

            $arraydatos = array();

            do {
                $url = APIURL . "/medias/?key=" . $apikey . "&filter=id;title&search=published:false&first=" . $ini . "&limit=" . $inc;

                //error_log($url);

                $response = self::getCurl($url);
                if ($response != FALSE) {
                    $mediaxml = new SimpleXMLElement($response);
                    $mediaarr = json_decode(json_encode($mediaxml), true);

                    foreach ($mediaarr["Media"] as $value) {
                        array_push($arraydatos, $value);
                    }

                    $ini = $ini + $inc;
                } else {
                    break;
                }
            } while (true);

            return $arraydatos;
        } else {
            return array();
        }
    }

    function obtenernumberOfViews($apiKey) {
        if (!empty($apiKey)) {
            $ini = 0;
            // max valor de $inc = 50
            $inc = 50;
            $flat = 1;

            $arraydatos = array();

            do {
                $url = APIURL . "/medias/?key=" . $apiKey . "&filter=id;numberOfViews&orderBy=numberOfViews&sort=desc&first=" . $ini . "&limit=" . $inc;
                $response = self::getXml($url);
                $mediaxml = new SimpleXMLElement($response);
                $mediaarr = json_decode(json_encode($mediaxml), true);

                foreach ($mediaarr["Media"] as $value) {
                    if ($value["numberOfViews"] == 0) {
                        break 2;
                    }
                    array_push($arraydatos, $value);
                }

                $ini = $ini + $inc;
            } while (true);

            return $arraydatos;
        } else {
            return array();
        }
    }

    function obtenernumberOfViewsXVideo($media, $apiKey) {
        if (!empty($apiKey) && !empty($media)) {

            $cantidad = 0;

            $url = APIURL . "/medias/" . $media . "?key=" . $apiKey . "&filter=id;numberOfViews";
            $response = self::getXml($url);
            if ($response != FALSE) {
                $mediaxml = new SimpleXMLElement($response);
                $mediaarr = json_decode(json_encode($mediaxml), true);

                $cantidad = $mediaarr["numberOfViews"];
            } else {
                $cantidad = 0;
            }
            return $cantidad;
        } else {
            return 0;
        }
    }

    function getimagenesLiquid($mediaarr = array(), $tipo_imagenes = null) {
//        $arrimg = array(1 => 146, 2 => 197, 3 => 304);
        $result = array();

        if (!empty($mediaarr["thumbs"])) {
            foreach ($mediaarr["thumbs"] as $value) {
                if (isset($value["url"])) {
                    //$retorno = array_search($value["height"], $arrimg);

                    $retorno = self::obtenerTipo($value, $tipo_imagenes);
                    //echo $retorno . "\n";
                    if ($retorno != FALSE) {
                        $value["tipo_imagen_id"] = $retorno;
                        $result = $value;
                    }
                } else {

//                $tmp = Array(); 
//                foreach($value as &$ma) 
//                    $tmp[] = &$ma["height"]; 
//                array_multisort($tmp, $value); 
//                foreach($value as &$ma) 
//                    echo $ma["height"]."<br/>"; 


                    foreach ($value as $value2) {
                        //echo "DAtos: ".array_search($value2["height"], $arrimg)."\n";
                        $retorno = self::obtenerTipo($value2, $tipo_imagenes);


                        //echo $retorno."\n";
                        if ($retorno != FALSE) {
                            unset($tipo_imagenes[$retorno]);
                            $value2["tipo_imagen_id"] = $retorno;
                            //print_r($value2);
                            array_push($result, $value2);
                        }
                    }
                }
            }
        }

        if (count($result) > 0) {
            sort($result);
            print_r($result);
            return $result;
        } else {
            return null;
        }
    }

    function obtenerTipo($thumbs, $tipo_imagenes) {

        $returnValue = FALSE;
        //$ancho_mayor = $thumbs['width'] + $this->config->item('migracion:margen_error_imagen');
        $alto_mayor = $thumbs['height'] + 50;
        //$ancho_menor = $thumbs['width'] - $this->config->item('migracion:margen_error_imagen');
        $alto_menor = $thumbs['height'] - 50;


        foreach ($tipo_imagenes as $value) {
            //$value->ancho <= $ancho_mayor && $value->ancho >= $ancho_menor &&
            if ($value->alto <= $alto_mayor && $value->alto >= $alto_menor) {
                $returnValue = $value->id;
            } else {
                echo "no paso " . $value->id;
            }
        }
        echo $returnValue;

        return $returnValue;
    }

    function getUrlVideoLiquidRawLite($mediaarr = array()) {
        if (!empty($mediaarr["thumbs"])) {
            foreach ($mediaarr["thumbs"] as $value) {

                if (isset($value["url"])) {
                    $urlimg = $value["url"] . "\n";
                    break;
                } else {
                    foreach ($value as $value2) {
                        if (isset($value2["url"])) {
                            $urlimg = $value2["url"] . "\n";
                            break 2;
                        }
                    }
                }
            }
        }

        if (!empty($mediaarr["files"])) {
            foreach ($mediaarr["files"] as $value) {

                // print_r($value);

                if (isset($value["id"])) {
                    //if ($value["output"]["name"] == "_RAW") {
                    $video_filename = $value["fileName"];
                    $video_id = $value["id"];
                    break;
                    //}
                } else {
                    $min = 9999;
                    foreach ($value as $value2) {
                        if ($value2["output"]["name"] != "_RAW") {
                            print_r($value2);
                            
                            if ($value2["videoInfo"]["height"] < $min) {
                                $min = $value2["videoInfo"]["height"];
                                $video_filename = $value2["fileName"];
                                $video_id = $value2["id"];
                            }
                        }
                    }
                }
            }
        }

        if (!empty($urlimg) && !empty($video_filename) && !empty($video_id)) {
            $url = strstr($urlimg, "thumbnail", TRUE) . "video/" . $video_id . "/" . $video_filename . "\n";
        }

        if (!empty($url)) {
            return $url;
        } else {
            return "";
        }
    }

    function getUrlVideoLiquidRaw($mediaarr = array()) {
        if (!empty($mediaarr["thumbs"])) {
            foreach ($mediaarr["thumbs"] as $value) {

                if (isset($value["url"])) {
                    $urlimg = $value["url"] . "\n";
                    break;
                } else {
                    foreach ($value as $value2) {
                        if (isset($value2["url"])) {
                            $urlimg = $value2["url"] . "\n";
                            break 2;
                        }
                    }
                }
            }
        }

        if (!empty($mediaarr["files"])) {
            foreach ($mediaarr["files"] as $value) {

                // print_r($value);

                if (isset($value["id"])) {
                    if ($value["output"]["name"] == "_RAW") {
                        $video_filename = $value["fileName"];
                        $video_id = $value["id"];
                        break;
                    }
                } else {
                    $min = 0;
                    foreach ($value as $value2) {
                        if ($value2["output"]["name"] == "_RAW") {
                            //if ($value2["videoInfo"]["height"] > $min) {                              
                            $min = $value2["videoInfo"]["height"];
                            $video_filename = $value2["fileName"];
                            $video_id = $value2["id"];
                            //} 
                        }
                    }
                }
            }
        }

        if (!empty($urlimg) && !empty($video_filename) && !empty($video_id)) {
            $url = strstr($urlimg, "thumbnail", TRUE) . "video/" . $video_id . "/" . $video_filename . "\n";
        }

        if (!empty($url)) {
            return $url;
        } else {
            return "";
        }
    }

    function getUrlVideoLiquid($mediaId, $apiKey) {
        $url = APIURL . "/medias/" . $mediaId . "?key=" . $apiKey . "&filter=id;thumbs;files";

        $response = $this->getXml($url);
        $mediaxml = new SimpleXMLElement($response);
        $mediaarr = json_decode(json_encode($mediaxml), TRUE);

        return $this->getUrlVideoLiquidRawLite($mediaarr);
    }

    function getDurationLiquid($mediaarr = array()) {
        //error_log("entro duration");
        $duration = 0;

        if (!empty($mediaarr["files"])) {
            foreach ($mediaarr["files"] as $value) {

                if (isset($value["id"])) {

                    if (!empty($value["videoInfo"]["duration"])) {
                        //error_log("duracion 2 ->" . $value["videoInfo"]);
                        $duration = $value["videoInfo"]["duration"];
                        break;
                    }
                } else {

                    foreach ($value as $value2) {

                        if (!empty($value2["videoInfo"]["duration"])) {
                            //error_log("duracion 2->" . $value2["videoInfo"]["duration"]);
                            $duration = $value2["videoInfo"]["duration"];
                            break 2;
                        }
                    }
                }
            }
        }
        return $duration;
    }

    function getPublished($mediaarr = array()) {
        if (!empty($mediaarr["published"])) {
            return (strtoupper($mediaarr["published"]) == 'TRUE') ? TRUE : FALSE;
        } else {
            return NULL;
        }
    }

    function getVerificarLiquidPostUpload($media, $apiKey) {
        try {
            $url = APIURL . "/medias/" . $media . "?key=" . $apiKey . "&filter=id;thumbs;files;published";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FAILONERROR, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);

            Log::erroLog("entro getVerificarLiquidPostUpload " . $url);


            $result = curl_exec($ch);
            $info = curl_getinfo($ch);

            if ($info['http_code'] == '200' && $info["content_type"] == 'application/xml') {
                Log::erroLog("return true");
                return TRUE;
            } else {
                Log::erroLog("return false");
                return FALSE;
            }
        } catch (Exception $exc) {
            return FALSE;
        }
    }

    function getObtenerMediaXId($id, $apikey) {

        $contador = 0;

        $ayer = strtotime("-1 day");
        $mañana = strtotime("+1 day");

        $straye = date("Ymd", $ayer) . "000000<br>";
        $strman = date("Ymd", $mañana) . "235959<br>";

        GETCURL:

        if ($contador == 20) {
            return false;
        }

        $url = APIURL . "/medias/?key=" . $apikey . "&filter=id;postDate;title&search=title:" . $id . ";postDate:%3C" . $strman . ";postDate:%3E" . $straye;
        $retorno = self::getCurl($url);

        if ($retorno != FALSE) {
            return $retorno;
        } else {

            sleep(300);
            $contador++;
            goto GETCURL;
        }
    }

    function getObtenerMedia($mediaarr, $id) {
        $media = '';

        if (count($mediaarr["Media"]) > 0) {
            if (isset($mediaarr["Media"]["id"])) {

                if ($mediaarr["Media"]["title"] == $id) {
                    $media = $mediaarr["Media"]["id"];
                }
            } else {

                foreach ($mediaarr["Media"] as $value) {
                    if ($value["title"] == $id) {
                        $media = $value["id"];
                        break;
                    }
                }
            }
        }
        return $media;
    }

    function getCurl($url) {
        try {

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FAILONERROR, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);

            $result = curl_exec($ch);
            $info = curl_getinfo($ch);

            curl_close($ch);

            Log::erroLog("http_code: " . $info['http_code']);
            Log::erroLog("content_type: " . $info['content_type']);
            //Log::erroLog("curl_errno: " . curl_errno($ch));

            if ($info['http_code'] == '200') {
                return $result;
            } else {
                return FALSE;
            }
        } catch (Exception $exc) {
            return FALSE;
        }
    }

    function obtenerVideosXApiKey($apikey) {
        try {
            if (!empty($apikey)) {
                $ini = 0;
                $inc = 50;
                $flat = 1;

                $arraydatos = array();

                do {
                    //&filter=id;title;;thumbs;
                    $url = APIURL . "/medias/?key=" . $apikey . "&search=published:false&first=" . $ini . "&limit=" . $inc;

                    //error_log($url);

                    $response = self::getCurl($url);
                    if ($response != FALSE) {
                        $mediaxml = new SimpleXMLElement($response);
                        $mediaarr = json_decode(json_encode($mediaxml), true);

                        foreach ($mediaarr["Media"] as $value) {
                            array_push($arraydatos, $value);
                        }

                        $ini = $ini + $inc;
                        //
                        $response = FALSE;
                    } else {
                        break;
                    }
                } while (true);

                return $arraydatos;
            } else {
                return array();
            }
        } catch (Exception $exc) {
            Log::erroLog("Error en obtenerVideosXApiKey");
        }
    }

}

?>