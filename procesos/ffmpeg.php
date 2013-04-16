<?php

//define('PATH_VIDEOS', $_SERVER["DOCUMENT_ROOT"] . "/adminmicanal/uploads/videos/");

class Ffmpeg {

    function convertVideotoMp4($id_video) {
        try {
            $conexion = new Conexion();

            $video_in = PATH_VIDEOS . $id_video . ".vid";
            echo "qwdqwdwq:" . $video_in . "<br>";

            $video_out = PATH_VIDEOS . $id_video . ".mp4";
            echo $video_out . "<br>";

            //$conexion->updateEstadoVideosLiquid($id_video, 1);

            if (!is_readable($video_out)) {
                exec("ffmpeg -i " . $video_in . " " . $video_out . " -loglevel quiet");
            }

            if (is_readable($video_out)) {
                //$conexion->updateEstadoVideosLiquid($id_video, 2);
                return true;
            } else {
                //$conexion->updateEstadoVideosLiquid($id_video, -1);
                return false;
            }
        } catch (Exception $e) {
            echo "Horror !!!!!" . $e->message;
            return false;
        }
    }

    function splitVideo($id_padre, $id_hijo, $inicio, $duracion) {
        try {
            echo $id_padre."- ". $id_hijo."- ". $inicio."- ". $duracion."\n";

            $video_in = PATH_VIDEOS . $id_padre . ".mp4";
            $video_out = PATH_VIDEOS . $id_hijo . ".mp4";
//           echo $video_in."\n";
//           echo $video_out."\n";
           


            //$conexion->updateEstadoVideosLiquid($id_video, 1);
           var_dump(is_readable($video_out));

            if (!is_readable($video_out)) {
//                echo "entro a procesar";
                exec("ffmpeg  -ss " . $inicio . " -t " . $duracion . " -i " . $video_in . " " . $video_out." -loglevel quiet");
            }

            if (is_readable($video_out)) {
                //$conexion->updateEstadoVideosLiquid($id_video, 2);
                return true;
            } else {
                //$conexion->updateEstadoVideosLiquid($id_video, -1);
                return false;
            }
        } catch (Exception $e) {
            echo "Horror !!!!!" . $e->message;
            return false;
        }
    }

    function downloadVideo($id_video, $ruta) {
        try {
            $filePath = PATH_VIDEOS . $id_video . ".mp4";

            if (is_readable($filePath)) {
                echo "archivo en disco";
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