<?php
set_time_limit(TIME_LIMIT);

if (!defined('BASEPATH'))     exit('No direct script access allowed');

class Liquid {

    function postXML($url, $post) {
         ERROR_LIQUID:
            Log::erroLog("postXML - url: " . $url);
            Log::erroLog("postXML - Post : " . print_r($post));
        
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
            
            Log::erroLog("http_code postXML: " .   $info['http_code']);
            Log::erroLog("curl_errno: " .   curl_errno($ch));
            
            if(!curl_errno($ch) && $info['http_code']=='200')
            {
                curl_close($ch);  
                Log::erroLog("paso publishd");
                return $result;
            }elseif ($info['http_code']=='500') {
                Log::erroLog("publishd datos genericos");
                return self::updatePublishedMedia($url);
                
            }else{
                sleep(5);
                Log::erroLog("no paso publish");
                
                goto ERROR_LIQUID;
            }          
        } catch (Exception $exc) {
            return FALSE;
            //echo $exc->getTraceAsString();
        }
    }

    function getXml($url) {
        try {
            ERROR_LIQUID:
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_FAILONERROR,1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);            
            
            
            Log::erroLog("url get " . $url);
            Log::erroLog("get entro error liquid titulo");
            
            $result = curl_exec($ch);
            $info = curl_getinfo($ch);
            
            
            Log::erroLog("http_code: " .   $info['http_code']);
            Log::erroLog("content_type: " .   $info['content_type']);
            Log::erroLog("curl_errno: " .   curl_errno($ch));
            
            if($info['http_code']=='200' &&  $info["content_type"]=='application/xml'){
                curl_close($ch);  
                Log::erroLog(" paso get");
                Log::erroLog(" result : " .  $result);
                return $result;
            }else{
                sleep(5);
                 Log::erroLog(" no paso get");
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

    function updatePublishedMedia($url) {
        $mediaId = trim($mediaId);

        $fecha = date('Y-m-d H:i:s');
        $date = date("Y-m-d\TH:i:sP", strtotime($fecha));

        $post = "<Media><title>Titulo</title><description>Descripcion</description><published>true</published><publishDate>" . $date . "</publishDate></Media>";
        $url = APIURL . "/medias/" . $mediaId . "?key=" . $apiKey;
        //echo $url . "<br>";
        return $this->postXML($url, $post);
    }

    function updatePublishedMediaNode($datos) {
        PUBLISHED:

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
        Log::erroLog("url pusblish: ".$url);
        
        
        $retorno = self::postXML($url, $post);
        
        Log::erroLog("retorno: " . $retorno);
        
        $pos = strpos($retorno, "SUCCESS");
        Log::erroLog("POS: ". $pos);
        
        if ($pos === false) {
            Log::erroLog("no paso SUCCESS");
            goto PUBLISHED;
            return FALSE;
        } else {
            Log::erroLog("paso SUCCESS");
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
            curl_setopt($ch, CURLOPT_TIMEOUT, TIME_LIMIT);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 7);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

            Log::erroLog(PATH_VIDEOS);
            $post = array(
                "file" => "@" . PATH_VIDEOS . $id_video . ".mp4",
                "token" => $apiKey
            );

            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            Log::erroLog("inicio envio de video a liquid ". $id_video);
            $response = curl_exec($ch);
            Log::erroLog("fin envio de video a liquid ". $id_video);
            curl_close($ch);

            $mediaxml = new SimpleXMLElement($response);
            Log::erroLog("mediaxml " . $mediaxml ." ". $id_video);
            $mediaarr = json_decode(json_encode($mediaxml), true);
            Log::erroLog("mediaarr " . $mediaarr ." ". $id_video);
            $media = $mediaarr["media"]["@attributes"]["id"];
            Log::erroLog("media " . $media ." ". $id_video);

            if (!empty($media)) {
                 Log::erroLog("entro a : updateMediaVideosXId ". $id_video."/".$media);
                 $ruta =  base_url("curlproceso/updateMediaVideosXId/".$id_video."/".$media);        
                 shell_exec("curl ".$ruta . " > /dev/null 2>/dev/null &");
                 Log::erroLog("return media " . trim($media));
                return trim($media);
            } else {
                Log::erroLog("return FALSE");
                return FALSE;
            }
        } catch (Exception $exc) {
            Log::erroLog("return FALSE de Exception");
            return FALSE;
        }
    }

    function obtenerDatosMedia($datos) {

        $url = APIURL . "/medias/" . $datos->codigo . "?key=" . $datos->apikey . "&filter=id;thumbs;files;published";
        //echo $url . "<br>";
        
        Log::erroLog("url obtener datos: " . $url);
        
        $response = self::getXml($url);
        Log::erroLog("Response obtener datos" . $response);
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
    
    function getVerificarLiquidPostUpload($media,$apiKey){
     try {
            $url = APIURL . "/medias/" . $media . "?key=" . $apiKey . "&filter=id;thumbs;files;published";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_FAILONERROR,1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);            
                 
            Log::erroLog("entro getVerificarLiquidPostUpload " . $url);
            Log::erroLog("get entro error liquid titulo");
            
            $result = curl_exec($ch);
            $info = curl_getinfo($ch);
            
            
            Log::erroLog("http_code: " .   $info['http_code']);
            Log::erroLog("content_type: " .   $info['content_type']);
            Log::erroLog("curl_errno: " .   curl_errno($ch));
            
            if($info['http_code']=='200' &&  $info["content_type"]=='application/xml'){
                Log::erroLog("return true");
                return TRUE;
            }else{
                Log::erroLog("return false");
                return FALSE;
           
            }          

        } catch (Exception $exc) {
           return FALSE;
        }
    }

}

?>
