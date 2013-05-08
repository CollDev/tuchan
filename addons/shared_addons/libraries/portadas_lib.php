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
        $this->load->model('canales/portada_m');
        $this->load->model('videos/videos_m');
        $this->load->model('videos/imagen_m');
        $this->load->model('videos/tipo_imagen_m');
        $this->load->model('videos/grupo_maestro_m');
    }

    /**
     * Método para actualizar videos en las portadas, secciones y detalle secciones
     * Modificaremos los estado del video para que sea compatible con los estados de portada
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $video_id
     */
    public function actualizar_video($video_id) {
        if ($video_id > 0) {
            $objVideo = $this->videos_m->get($video_id);
            //modificamos el estado de video para que sea compatible con los estados de portada
            $objVideo->estado = $objVideo->estado + 1;
            if (count($objVideo) > 0) {
                //listamos todos los detalles secciones que contengan este video
                $arrayDetalleSeciones = $this->detalle_secciones_m->get_many_by(array("videos_id" => $video_id));
                if (count($arrayDetalleSeciones) > 0) {
                    //creamos un array para recolectar los ID de seccion
                    $arrayIdSeccion = array();
                    foreach ($arrayDetalleSeciones as $puntero => $objDetalleSeccion) {
                        array_push($arrayIdSeccion, $objDetalleSeccion->secciones_id);
                        //actualizamos el mismo estado del maestro al detalle de la seccion
                        $this->detalle_secciones_m->update($objDetalleSeccion->id, array("estado" => $objVideo->estado, "estado_migracion" => $this->config->item('migracion:actualizado')));
                    }
                    //actualizamos los estados de la seccion
                    if (count($arrayIdSeccion) > 0) {
                        $arrayIdSeccion = array_unique($arrayIdSeccion);
                        //creamos un array para recolectar los ID de portada
                        $arrayIdPortada = array();
                        foreach ($arrayIdSeccion as $index => $seccion_id) {
                            $objSeccion = $this->secciones_m->get($seccion_id);
                            array_push($arrayIdPortada, $objSeccion->portadas_id);
                            if (count($objSeccion) > 0) {
                                //veremos si podemos publicarlo
                                $arraySeccionPublicado = $this->detalle_secciones_m->get_many_by(array("secciones_id" => $seccion_id, "estado" => $this->config->item('estado:publicado')));
                                if (count($arraySeccionPublicado) > 0) {
                                    $this->secciones_m->update($seccion_id, array("estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                                } else {
                                    //verificamos que tuvo un estado eliminado
                                    if ($objSeccion->estado == $this->config->item('estado:publicado')) {
                                        $this->secciones_m->update($seccion_id, array("estado" => $this->config->item('estado:borrador'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                                    }
                                }
                            }
                        }
                        //verificamos el estado de la portada
                        if (count($arrayIdPortada) > 0) {
                            $arrayIdPortada = array_unique($arrayIdPortada);
                            foreach ($arrayIdPortada as $indice => $portada_id) {
                                $objPortada = $this->portada_m->get($portada_id);
                                if (count($objPortada) > 0) {
                                    //veremos si podemos publicarlo
                                    $arrayPortada = $this->secciones_m->get_many_by(array("portadas_id" => $portada_id, "estado" => $this->config->item('estado:publicado')));
                                    if (count($arrayPortada) > 0) {
                                        $this->portada_m->update($portada_id, array("estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                                    } else {
                                        //verificamos que tuvo un estado eliminado
                                        if ($objPortada->estado == $this->config->item('estado:publicado')) {
                                            $this->portada_m->update($portada_id, array("estado" => $this->config->item('estado:borrador'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Método para actualizar un maestro en las portadas, secciones y detalle seccion
     * tenemos la seguridad de que los estados del maestro con los estados de las portadas y secciones on iguales
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $maestro_id
     */
    public function actualizar_maestro($maestro_id) {
        if ($maestro_id > 0) {
            $objMaestro = $this->grupo_maestro_m->get($maestro_id);
            if (count($objMaestro) > 0) {
                //listamos todos los detalles secciones que contengan este maestro
                $arrayDetalleSeciones = $this->detalle_secciones_m->get_many_by(array("grupo_maestros_id" => $maestro_id));
                if (count($arrayDetalleSeciones) > 0) {
                    //creamos un array para recolectar los ID de seccion
                    $arrayIdSeccion = array();
                    foreach ($arrayDetalleSeciones as $puntero => $objDetalleSeccion) {
                        array_push($arrayIdSeccion, $objDetalleSeccion->secciones_id);
                        //actualizamos el mismo estado del maestro al detalle de la seccion
                        $this->detalle_secciones_m->update($objDetalleSeccion->id, array("estado" => $objMaestro->estado, "estado_migracion" => $this->config->item('migracion:actualizado')));
                    }
                    //actualizamos los estados de la seccion
                    if (count($arrayIdSeccion) > 0) {
                        $arrayIdSeccion = array_unique($arrayIdSeccion);
                        //creamos un array para recolectar los ID de portada
                        $arrayIdPortada = array();
                        foreach ($arrayIdSeccion as $index => $seccion_id) {
                            $objSeccion = $this->secciones_m->get($seccion_id);
                            array_push($arrayIdPortada, $objSeccion->portadas_id);
                            if (count($objSeccion) > 0) {
                                //veremos si podemos publicarlo
                                $arraySeccionPublicado = $this->detalle_secciones_m->get_many_by(array("secciones_id" => $seccion_id, "estado" => $this->config->item('estado:publicado')));
                                if (count($arraySeccionPublicado) > 0) {
                                    $this->secciones_m->update($seccion_id, array("estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                                } else {
                                    //verificamos que tuvo un estado eliminado
                                    if ($objSeccion->estado == $this->config->item('estado:publicado')) {
                                        $this->secciones_m->update($seccion_id, array("estado" => $this->config->item('estado:borrador'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                                    }
                                }
                            }
                        }
                        //verificamos el estado de la portada
                        if (count($arrayIdPortada) > 0) {
                            $arrayIdPortada = array_unique($arrayIdPortada);
                            foreach ($arrayIdPortada as $indice => $portada_id) {
                                $objPortada = $this->portada_m->get($portada_id);
                                if (count($objPortada) > 0) {
                                    //veremos si podemos publicarlo
                                    $arrayPortada = $this->secciones_m->get_many_by(array("portadas_id" => $portada_id, "estado" => $this->config->item('estado:publicado')));
                                    if (count($arrayPortada) > 0) {
                                        $this->portada_m->update($portada_id, array("estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                                    } else {
                                        //verificamos que tuvo un estado eliminado
                                        if ($objPortada->estado == $this->config->item('estado:publicado')) {
                                            $this->portada_m->update($portada_id, array("estado" => $this->config->item('estado:borrador'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
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
