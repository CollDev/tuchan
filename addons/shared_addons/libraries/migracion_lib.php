<?php

/**
 * Libreria para la migración de videos ya subidos. 
 * La libreria se encargara de parsear los videos x canal para registrarlo en nuestra Base de datos
 * @author Johnny Huamani <johnny1402@gmail.com>
 * @name Migracion
 * @package Migracion
 * @version 0.1
 */
class Migracion_lib extends MX_Controller {

    /**
     * propiedad que permite almacenar el key del canal
     * @var string 
     */
    private $key = '';

    /**
     * propiedad que almacena la URL de la api al que se solicitará los datos
     * @var string 
     */
    private $url = '';

    /**
     * Definimos el limite de la paginación para iterar
     * @var int 
     */
    private $pagina = 9999;

    /**
     * Variable para definir los campos que necesitamos que nos retorne
     * @var string 
     */
    private $filtro = '';

    /**
     * Método para cargar los modelos, librerias y configuraciones
     */
    public function __construct() {
        $this->load->model('canales/canales_m');
        $this->url = $this->config->item('migracion:url');
        //$this->filtro = $this->config->item('migracion:filtro');
    }

    /**
     * Método para inciar la migración listando canales y llamando otros metodos
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @return boolean
     */
    public function inicar_migracion_masiva() {
        $returnValue = FALSE;
        $objColeccionCanal = $this->canales_m->get_many_by(array("estado" => $this->config->item('estado:publicado')));
        if (count($objColeccionCanal) > 0) {
            foreach ($objColeccionCanal as $puntero => $objCanal) {
                if (strlen(trim($objCanal->apikey)) > 0) {
                    $this->migrar_canal($objCanal);
                    break;
                }
            }
        }
        return $returnValue;
    }

    /**
     * Metodo para migrar los videos de un solo canal
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param object $objCanal
     */
    public function migrar_canal($objCanal) {
        //obtenemos el path del api
        for ($indice = 0; $indice < $this->pagina; $indice++) {
            $ruta_api = $this->generarApi($objCanal, $indice);
            $data = @simplexml_load_file($ruta_api);
            if ($data) {
                $this->guardar_data($data, $objCanal);
            }
            break;
        }
    }

    /**
     * Método para iterar la data e insertar en la Base de datos
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param object $data
     * @param object $objCanal
     */
    private function guardar_data($data, $objCanal) {

        $lista_videos = $data->Media;
        foreach ($lista_videos as $puntero => $objVideo) {
            if (property_exists($objVideo, 'files')) {
                $objFile = $this->obtenerFile($objVideo->files);
                $this->vd($objFile);
            }
        }
    }

    /**
     * Método para obtener un objeto file del video correcto
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param arrayObject $objColeccionArchivos
     * @return array
     */
    private function obtenerFile($objColeccionArchivos) {
        $returnValue = array();
        if (property_exists($objColeccionArchivos, 'file')) {
            $arrayFiles = $objColeccionArchivos->file;
            if (count($arrayFiles) > 0) {
                foreach ($arrayFiles as $puntero => $objFile) {
                    if (property_exists($objFile, 'output')) {
                        $objOutput = $objFile->output;
                        if ($objOutput->name == $this->config->item('migracion:output')) {
                            $returnValue = $objFile;
                            break;
                        }
                    }
                }
            }
        }
        return $returnValue;
    }

    /**
     * Método para armar la ruta de la api
     * @param object $objCanal
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @return string
     */
    private function generarApi($objCanal, $pagina = 0) {
        $returnValue = '';
        $returnValue = $this->url . $objCanal->apikey . '&first=' . $pagina . '&' . $this->filtro;
        return $returnValue;
    }

    /**
     * metodo para imprimir variables con formato
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param undefined $var
     */
    private function vd($var) {
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }

}
