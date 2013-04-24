<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Liquid {

    function postXML($url, $post) {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
            
            ERROR_LIQUID:
                
            $result = curl_exec($ch);
            $info = curl_getinfo($ch);
            
            if(!curl_errno($ch) && $info['http_code']=='200')
            {
                curl_close($ch);               
                return $result;
            }else{
                sleep(5);
                goto ERROR_LIQUID;
            }          
        } catch (Exception $exc) {
            return FALSE;
            //echo $exc->getTraceAsString();
        }
    }

    function getXml($url) {
        try {
            
            $ch = curl_init($url); 
            
            ERROR_LIQUID:
                 
            $result = curl_exec($ch);
            $info = curl_getinfo($ch);
            
            if(!curl_errno($ch) && $info['http_code']=='200' && $info['']=='application/xml'){
                curl_close($ch);               
                return $result;
            }else{
                sleep(5);
                goto ERROR_LIQUID;
            }          
//            $result = file_get_contents(trim($url));
//            if (!$result) {
//                return "";
//            } else {
//                return $result;
//            }
        } catch (Exception $exc) {
//            return "";
        }
    }

    function updatePublishedMedia($mediaId, $apiKey) {
        $mediaId = trim($mediaId);

        $fecha = date('Y-m-d H:i:s');
        $date = date("Y-m-d\TH:i:sP", strtotime($fecha));

        $post = "<Media><published>true</published><publishDate>" . $date . "</publishDate></Media>";
        $url = APIURL . "/medias/" . $mediaId . "?key=" . $apiKey;
        //echo $url . "<br>";
        return $this->postXML($url, $post);
    }

    function updatePublishedMediaNode($datos) {


        $fecha = date('Y-m-d H:i:s');
        $date = date("Y-m-d\TH:i:sP", strtotime($fecha));

        $post = "<Media><published>true</published>";
        $post .= "<description>" . strip_tags($datos->descripcion) . "</description>";
        $post .= "<highlighted>false</highlighted>";
        $post .= "<publishDate>" . $date . "</publishDate>";
        $post .= "<title>" . $datos->titulo . "</title>";
        $post .= "<channelId>2</channelId>";
        $post .= "</Media>";

        $url = APIURL . "/medias/" . $datos->codigo . "?key=" . $datos->apikey;

        $retorno = self::postXML($url, $post);
        $pos = strpos($retorno, "SUCCESS");
        if ($pos === false) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function updateTitleMediaNode($mediaId, $datos, $apiKey) {

        $mediaId = trim($mediaId);

        $tags = '';
        $date = date("Y-m-d\TH:i:sP", strtotime($datos->fecha));
        $post = "<Media>" .
                "<description>" . $datos->legend . "</description>" .
                "<title>{$datos->title}</title>" .
                $tags .
                "</Media>";
        $url = APIURL . "/medias/" . $mediaId . "?key=" . $apiKey;
        //echo $url . "<br>";
        return $this->postXML($url, $post);
    }

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
            curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 7);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

            error_log(PATH_VIDEOS);
            $post = array(
                "file" => "@" . PATH_VIDEOS . $id_video . ".mp4",
                "token" => $apiKey
            );

            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

            $response = curl_exec($ch);
            curl_close($ch);

            $mediaxml = new SimpleXMLElement($response);
            $mediaarr = json_decode(json_encode($mediaxml), true);
            $media = $mediaarr["media"]["@attributes"]["id"];

            if (!empty($media)) {
                return trim($media);
            } else {
                return FALSE;
            }
        } catch (Exception $exc) {
            return FALSE;
        }
    }

    function obtenerDatosMedia($datos) {

        $url = APIURL . "/medias/" . $datos->codigo . "?key=" . $datos->apikey . "&filter=id;thumbs;files;published";
        //echo $url . "<br>";
        $response = self::getXml($url);
        $mediaxml = new SimpleXMLElement($response);

        $mediaarr = json_decode(json_encode($mediaxml), true);

        return $mediaarr;
    }

    function obtenernumberOfViews($apiKey) {
        $ini = 0;
        // max valor de $inc = 50
        $inc = 50;
        $flat = 1;

        $arraydatos = array();

        do {
            $url = APIURL . "/medias/?key=" . $apiKey . "&filter=id;numberOfViews&orderBy=numberOfViews&sort=desc&first=" . $ini . "&limit=" . $inc;
            $response = $this->getXml($url);
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
    }

    function getimagenesLiquid($mediaarr) {
        $arrimg = array(1 => 146, 2 => 197, 3 => 304);
        $result = array();

        if (count($mediaarr["thumbs"]) > 0) {
            foreach ($mediaarr["thumbs"] as $value) {
                if (isset($value["url"])) {
                    $retorno = array_search($value["height"], $arrimg);
                    //echo $retorno . "\n";
                    if ($retorno != FALSE) {
                        $value["tipo_imagen_id"] = $retorno;
                        $result = $value;
                    }
                } else {

                    foreach ($value as $value2) {
                        //echo "DAtos: ".array_search($value2["height"], $arrimg)."\n";
                        $retorno = array_search($value2["height"], $arrimg);
                        //echo $retorno."\n";
                        if ($retorno != FALSE) {
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
            return $result;
        } else {
            return null;
        }
    }

    function getUrlVideoLiquidRawLite($mediaarr) {
        if (count($mediaarr["thumbs"]) > 0) {
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

        if (count($mediaarr["files"]) > 0) {
            foreach ($mediaarr["files"] as $value) {

                // print_r($value);

                if (isset($value["id"])) {
                    if ($value["output"]["name"] == "_RAW") {
                        $video_filename = $value["fileName"];
                        $video_id = $value["id"];
                        break;
                    }
                } else {
                    foreach ($value as $value2) {
                        if ($value2["output"]["name"] == "_RAW") {
                            $video_filename = $value2["fileName"];
                            $video_id = $value2["id"];
                            break 2;
                        }
                    }
                }
            }
        }

        if (isset($urlimg) && isset($video_filename) && isset($video_id)) {


            $url = strstr($urlimg, "thumbnail", TRUE) . "video/" . $video_id . "/" . $video_filename . "\n";
        }


        if (isset($url)) {
            return $url;
        } else {
            return "";
        }
    }

    function getUrlVideoLiquidRaw($mediaId, $apiKey) {
        $url = APIURL . "/medias/" . $mediaId . "?key=" . $apiKey . "&filter=id;thumbs;files";

        $response = $this->getXml($url);
        $mediaxml = new SimpleXMLElement($response);
        $mediaarr = json_decode(json_encode($mediaxml), TRUE);

        return $this->getUrlVideoLiquidRawLite($mediarr);
    }

}

?>
