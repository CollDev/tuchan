<?php
include 'Ffprobe.php';;

set_time_limit(TIME_LIMIT);

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ffmpeg {

    function convertVideotoMp4($id_video) {
        try {


            $video_in = PATH_VIDEOS . $id_video . ".vid";
            Log::erroLog("video_in: " . $video_in);

            $video_out = PATH_VIDEOS . $id_video . ".mp4";
            Log::erroLog("video_out: " . $video_out);

            if (!is_readable($video_out)) {
                Log::erroLog("entro a conversion");            
                
                //exec ("ffmpeg -i " . $video_in . " " . $video_out. " -loglevel quiet");
                exec("ffmpeg -i " . $video_in . " " . $video_out. " 1>".PATH_VIDEOS."/".$id_video.".txt 2>&1");
                Log::erroLog("termino conversion");
            }
            
            Log::erroLog($video_out);
            Log::erroLog(mime_content_type($video_out));
                
            if (is_readable($video_out)) {
                if (is_readable($video_in)) {
                    Log::erroLog("borrando archivo origen " . $id_video);
                    unlink($video_in);
                }
                Log::erroLog("retornando true archivo convertido: " . $id_video);
                
                $ruta = base_url("curlproceso/comprobarConvertirVideosXId/" . $id_video);
                shell_exec("curl " . $ruta . " > /dev/null 2>/dev/null &");
                Log::erroLog($ruta);

                Log::erroLog("retornando true archivo convertido: " . $id_video);
                return true;
            } else {
                Log::erroLog("retornando false archivo no convertido: " . $id_video);
                return false;
            }
        } catch (Exception $e) {
            Log::erroLog("excepxion de conversion: " . $id_video);
            return false;
        }
    }

    function splitVideo($id_padre, $id_hijo, $inicio, $duracion) {
        try {

            Log::erroLog("ENTRO DATOS ");

            $video_in = PATH_VIDEOS . $id_padre . ".mp4";
            $video_out = PATH_VIDEOS . $id_hijo . ".mp4";

            Log::erroLog($video_in);
            Log::erroLog($video_out);


            SPLITVIDEO:

            if (is_readable($video_in)) {
                umask(002);
                chmod($video_in, 0444);
            }

            if (!is_readable($video_out)) {
                exec("ffmpeg  -ss " . $inicio . " -t " . $duracion . " -i " . $video_in . " " . $video_out . " -loglevel quiet");
            }

            if (is_readable($video_out)) {
                umask(0);
                chmod($video_in, 0666);
                chmod($video_out, 0666);
                return true;
            } else {
                if (file_exists($video_out)) {
                    unlink($video_out);
                    goto SPLITVIDEO;
                }
            }
        } catch (Exception $e) {
            return false;
        }
    }

    function downloadVideo($id_video, $ruta) {
        try {
            $filePath = PATH_VIDEOS . $id_video . ".mp4";

            if (is_readable($filePath)) {
                return TRUE;
            } else {
                DONWLOADVIDEO:
                Log::erroLog("Inicio descarga de Video id: " . $id_video);
                Log::erroLog("Ruta : " . $ruta);
                $fp = fopen($filePath, "x");

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $ruta);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_FILE, $fp);

                $result = curl_exec($ch);
                curl_close($ch);
                fclose($fp);

                $info = curl_getinfo($ch);


                Log::erroLog("descarga http_code postXML: " . $info['http_code']);
                Log::erroLog("descarga curl_errno: " . curl_errno($ch));

                if (is_readable($filePath)) {
                    Log::erroLog("Termino descarga de Video id: " . $id_video);
                    Log::erroLog("Duracion : " . $info['total_time']);
                    return TRUE;
                } else {
                    Log::erroLog("Error de descarga reintento para video id: " . $id_video);
                    goto DONWLOADVIDEO;
                    //return FALSE;
                }
            }
        } catch (Exception $exc) {
            return FALSE;
        }
    }

}

?>