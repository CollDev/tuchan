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
        $this->load->model('videos/videos_m');
        $this->url = $this->config->item('migracion:url');
        //$this->filtro = $this->config->item('migracion:filtro');
    }

    /**
     * Método para inciar la migración listando canales y llamando otros metodos
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @return boolean
     */
    public function iniciar_migracion_masiva() {
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
                $this->obtener_objeto_bean_video($data, $objCanal);
            }
            break;
        }
    }

    /**
     * Método para iterar la data y retornar objeto bean para guardar en la BD
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param object $data
     * @param object $objCanal
     */
    private function obtener_objeto_bean_video($data, $objCanal) {
        //iteramos el contenido de Media, ahí están la lista de videos e imagenes
        $user_id = (int) $this->session->userdata('user_id');
        $lista_videos = $data->Media;
        $contador = 0;
        foreach ($lista_videos as $puntero => $objVideo) {
            if (property_exists($objVideo, 'files')) {
                $oVideo = $this->videos_m->like('codigo', $objVideo->id, 'none')->get_by(array());
                if (count($oVideo) > 0) {//el video ya se encuentra registrado
                } else { // es un video que no se encuentra en nuestra BD
                    $objFile = $this->obtenerFile($objVideo->files);
                    echo "----------------------#".$contador."#-------------------------------";
                    $this->vd($objVideo->id);
                    $objBeanVideo = new stdClass();
                    $objBeanVideo->id = NULL;
                    $objBeanVideo->tipo_videos_id = $this->config->item('videos:normal');
                    $objBeanVideo->categorias_id = $this->config->item('categoria:modas');
                    $objBeanVideo->usuarios_id = $user_id;
                    $objBeanVideo->canales_id = $objCanal->id;
                    $objBeanVideo->nid = NULL;
                    $objBeanVideo->titulo = '';
                    $objBeanVideo->alias = '';
                    $objBeanVideo->descripcion = '';
                    $objBeanVideo->fragmento = 0;
                    $objBeanVideo->codigo = $objVideo->id;
                    $objBeanVideo->reproducciones = 0;
                    $objBeanVideo->duracion = '';
                    /*$objBeanVideo->fecha_publicacion_inicio                  datetime       YES             (NULL)                   
                    $objBeanVideo->fecha_publicacion_fin                     datetime       YES             (NULL)                   
                    $objBeanVideo->fecha_transmision                         date           YES             (NULL)                   
                    $objBeanVideo->horario_transmision_inicio                time           YES             (NULL)                   
                    $objBeanVideo->horario_transmision_fin                   time           YES             (NULL)                   
                    $objBeanVideo->ubicacion                                 varchar(100)   YES             (NULL)                   
                    $objBeanVideo->id_mongo                                  varchar(25)    YES             (NULL)                   
                    $objBeanVideo->estado                                    tinyint(4)     YES             0                        
                    $objBeanVideo->estado_liquid                             tinyint(4)     YES             0                        
                    $objBeanVideo->fecha_registro                            datetime       YES             (NULL)                   
                    $objBeanVideo->usuario_registro                          int(11)        YES             (NULL)                   
                    $objBeanVideo->fecha_actualizacion                       datetime       YES             (NULL)                   
                    $objBeanVideo->usuario_actualizacion                     int(11)        YES             (NULL)                   
                    $objBeanVideo->estado_migracion                          tinyint(4)     YES             0                        
                    $objBeanVideo->fecha_migracion                           datetime       YES             (NULL)                   
                    $objBeanVideo->fecha_migracion_actualizacion             datetime       YES             (NULL)                   
                    $objBeanVideo->estado_migracion_sphinx_tit               tinyint(4)     YES             0                        
                    $objBeanVideo->fecha_migracion_sphinx_tit                datetime       YES             (NULL)                   
                    $objBeanVideo->fecha_migracion_actualizacion_sphinx_tit  datetime       YES             (NULL)                   
                    $objBeanVideo->estado_migracion_sphinx_des               tinyint(4)     YES             0                        
                    $objBeanVideo->fecha_migracion_sphinx_des                datetime       YES             (NULL)                   
                    $objBeanVideo->fecha_migracion_actualizacion_sphinx_des  datetime       YES             (NULL)                   
                    $objBeanVideo->valorizacion                              int(11)        YES             0                        
                    $objBeanVideo->comentarios                               int(11)        YES             0                        
                    $objBeanVideo->ruta                                      varchar(150)   YES             (NULL)                   
                    $objBeanVideo->padre                                     int(11)        YES             0                        
                    $objBeanVideo->estado_migracion_sphinx                   tinyint(4)     YES             (NULL)                   
                    $objBeanVideo->fecha_migracion_sphinx                    datetime       YES             (NULL)                   
                    $objBeanVideo->fecha_migracion_actualizacion_sphinx      datetime       YES             (NULL)                   
                    $objBeanVideo->procedencia*/
                }
            }
            $contador++;
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
        echo $returnValue;
        return $returnValue;
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
