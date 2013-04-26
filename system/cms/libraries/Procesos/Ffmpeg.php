<?php
set_time_limit(TIME_LIMIT);

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ffmpeg {

    function convertVideotoMp4($id_video) {
        try {


            $video_in = PATH_VIDEOS . $id_video . ".vid";
            Log::erroLog("video_in: ".$video_in);

            $video_out = PATH_VIDEOS . $id_video . ".mp4";
            Log::erroLog("video_out: ".$video_in);

            if (!is_readable($video_out)) {
                 Log::erroLog("entro a conversion");
                exec("ffmpeg -i " . $video_in . " " . $video_out . " -loglevel quiet");
            }

            if (is_readable($video_out)) {
                if(is_readable($video_in)){
                     unlink($video_in);
                }               
                return true;
            } else {

                return false;
            }
        } catch (Exception $e) {

            return false;
        }
    }

    function splitVideo($id_padre, $id_hijo, $inicio, $duracion) {
        try {


            $video_in = PATH_VIDEOS . $id_padre . ".mp4";
            $video_out = PATH_VIDEOS . $id_hijo . ".mp4";

            if (!is_readable($video_out)) {
                exec("ffmpeg  -ss " . $inicio . " -t " . $duracion . " -i " . $video_in . " " . $video_out . " -loglevel quiet");
            }

            if (is_readable($video_out)) {
                return true;
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
                $fp = fopen($filePath, "w");

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

                if (is_readable($filePath)) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            }
        } catch (Exception $exc) {
            return FALSE;
        }
    }

}

?>