<?php

class Proceso extends MX_Controller {

    public function __construct() {
        parent::__construct();
        //$this->database();        
        //$this->load->model('models/proceso_m');
        $this->load->model('videos_m');
    }

    public function index() {
        
    }

    public function corte_Video($id_padre = "", $id_hijo = "", $inicio = "", $duracion = "") {

        if (!empty($id_padre) && !empty($id_hijo) && !empty($inicio) && !empty($duracion)) {
            
            $result = ci()->videos_m->getVideosxId($id_padre);
            if (count($result) > 0) {
                
                foreach ($result as $value) {
                    if ($this->_downloadVideo($value->id, $value->ruta)) {

                        if ($this->_splitVideo($id_padre, $id_hijo, $inicio, $duracion)) {
                            
                            echo "OK";
                            return TRUE;
                        }else{
                                                    echo " no paso2";
                        }
                    }
                }
            }
        } else {
            echo "falta datos";
            return FALSE;
        }
    }

    private function _downloadVideo($id_video, $ruta) {
        try {
            $path_video = $_SERVER["DOCUMENT_ROOT"] . "uploads/videos/";

            $filePath = $path_video . $id_video . ".mp4";

            if (is_readable($filePath)) {
                //  echo "archivo en disco";
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

    private function _splitVideo($id_padre, $id_hijo, $inicio, $duracion) {

        try {
            $path_video = $_SERVER["DOCUMENT_ROOT"] . "uploads/videos/";

            $video_in = $path_video . $id_padre . ".mp4";
            $video_out = $path_video . $id_hijo . ".mp4";

            echo $video_in."\n";
            echo $video_out."\n";

            if (!is_readable($video_out)) {
                echo "entro a corte";
                exec("ffmpeg  -ss " . $inicio . " -t " . $duracion . " -i " . $video_in . " " . $video_out . " ");
            }

            if (is_readable($video_out)) {

                return true;
            } else {

                return false;
            }
        } catch (Exception $e) {
            //echo "Horror !!!!!" . $e->message;
            return false;
        }
    }

    public function micanal() {
        $result = ci()->videos_m->queryMysqlMiCanal("1", "");
    }

}

?>