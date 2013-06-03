<?php

/**
 * Libreria para la ejecución de actualización de portadas y secciones 
 * La libreria se encargara de parsear los maestros,videos y canales y actualizar en las portadas
 * @author Johnny Huamani <johnny1402@gmail.com>
 * @name Portadas
 * @package Portadas
 * @version 0.1
 */
class Portadas_lib extends MX_Controller {

    /**
     * Método para cargar los modelos, librerias y configuraciones
     */
    public function __construct() {
        $this->load->model('canales/canales_m');
        $this->load->model('canales/detalle_secciones_m');
        $this->load->model('canales/secciones_m');
        $this->load->model('canales/tipo_secciones_m');
        $this->load->model('canales/portada_m');
        $this->load->model('videos/videos_m');
        $this->load->model('videos/imagen_m');
        $this->load->model('videos/tipo_imagen_m');
        $this->load->model('videos/grupo_maestro_m');
        $this->load->model('videos/grupo_detalle_m');

        $this->load->library("Procesos/log");
    }

    /**
     * Método para agregar un video a las secciones que corresponde en las portadas
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $video_id
     */
    public function agregar_video($video_id) {
        $objVideo = $this->videos_m->get($video_id);
        if(count($objVideo)>0){
            //verificamos si la portada del canal al que pertenece esta publicada
            //$portadaCanal = 
        }
    }

    /**
     * metodo para debuguear variables con formato
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param undefined $var
     */
    private function vd($var) {
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }

}
