<?php

include 'Ffmpeg.php';
include 'Liquid.php';

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Proceso {


    public function corte_Video($datos) {
        if (count($datos) > 0) {
            if (Ffmpeg::downloadVideo($datos["id_padre"], $datos["ruta"])) {
                if (Ffmpeg::splitVideo($datos["id_padre"], $datos["id_hijo"], $datos["inicio"], $datos["duracion"])) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            }
        } else {
            return FALSE;
        }
    }

//    private function _downloadVideo($id_video, $ruta) {
//        try {
//            $path_video = $_SERVER["DOCUMENT_ROOT"] . "uploads/videos/";
//
//            $filePath = $path_video . $id_video . ".mp4";
//
//            if (is_readable($filePath)) {
//                //  echo "archivo en disco";
//                return TRUE;
//            } else {
//                $fp = fopen($filePath, "w");
//
//                $ch = curl_init();
//                curl_setopt($ch, CURLOPT_URL, $ruta);
//                curl_setopt($ch, CURLOPT_HEADER, false);
//                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
//                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
//                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
//                curl_setopt($ch, CURLOPT_FILE, $fp);
//
//                $result = curl_exec($ch);
//                curl_close($ch);
//                fclose($fp);
//
//                if (is_readable($filePath)) {
//                    return TRUE;
//                } else {
//                    return FALSE;
//                }
//            }
//        } catch (Exception $exc) {
//            return FALSE;
//        }
//    }
//
//    private function _splitVideo($id_padre, $id_hijo, $inicio, $duracion) {
//
//        try {
//            $path_video = $_SERVER["DOCUMENT_ROOT"] . "uploads/videos/";
//
//            $video_in = $path_video . $id_padre . ".mp4";
//            $video_out = $path_video . $id_hijo . ".mp4";
//
//            echo $video_in."\n";
//            echo $video_out."\n";
//
//            if (!is_readable($video_out)) {
//                echo "entro a corte";
//                exec("ffmpeg  -ss " . $inicio . " -t " . $duracion . " -i " . $video_in . " " . $video_out . " ");
//            }
//
//            if (is_readable($video_out)) {
//
//                return true;
//            } else {
//
//                return false;
//            }
//        } catch (Exception $e) {
//            //echo "Horror !!!!!" . $e->message;
//            return false;
//        }
//    }
}

?>