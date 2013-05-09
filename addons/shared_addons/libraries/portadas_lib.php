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
            //verificamos si el maestro es de tipo programa
            if ($objMaestro->tipo_grupo_maestro_id == $this->config->item('videos:programa')) {
                //$this->actualizar_portada_programa($maestro_id);
            }
        }
    }

    /**
     * Método para actualizar la portada de tipo programa
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $maestro_id
     */
    private function actualizar_portada_programa($maestro_id) {
        
    }

    /**
     * Método para agregar los maestros al detalle de secciones
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $maestro_id
     */
    public function agregar_maestro($maestro_id) {
        if ($maestro_id > 0) {
            $objMaestro = $this->grupo_maestro_m->get($maestro_id);
            if (count($objMaestro) > 0) {
                $user_id = (int) $this->session->userdata('user_id');
                //verificamos si es un programa o coleccion para generar sus portadas
                if ($objMaestro->tipo_grupo_maestro_id == $this->config->item('videos:programa')) {
                    $this->generar_portada_programa($maestro_id);
                } else {
                    if ($objMaestro->tipo_grupo_maestro_id == $this->config->item('videos:coleccion')) {
                        $this->generar_seccion_coleccion($maestro_id);
                    }
                }
                //verificamos si su superior es un programa o un canal
                //si es un canal registramos en la portada del canal
                //si es un programa registramos en la portada programa
                $objMaestroPadre = $this->grupo_detalle_m->get_by(array("grupo_maestro_id" => $maestro_id));
                if (count($objMaestroPadre) > 0) {
                    //rgistramos en la portada del programa
                    $lista_portadas = $this->portada_m->get_many_by(array("origen_id" => $objMaestro->canales_id, "tipo_portadas_id" => $this->config->item('portada:programa')));
                } else {
                    //registramos en la portada del canal
                    $lista_portadas = $this->portada_m->get_many_by(array("canales_id" => $objMaestro->canales_id, "origen_id" => $objMaestro->canales_id, "tipo_portadas_id" => $this->config->item('portada:canal')));
                }
                //$lista_portadas = $this->portada_m->get_many_by(array("canales_id" => $objMaestro->canales_id));
                //$lista_portadas_micanal = $this->portada_m->get_many_by(array("tipo_portadas_id" => $this->config->item('portada:principal')));
                //$lista_portadas = array_merge($lista_portadas_canal, $lista_portadas_micanal);
                if (count($lista_portadas) > 0) {
                    foreach ($lista_portadas as $puntero => $objPortada) {
                        $tipo_seccion = $this->obtener_array_tipo_seccion($objPortada, $objMaestro);
                        if (count($tipo_seccion) > 0) {
                            $secciones_portada = $this->secciones_m->where_in('tipo_secciones_id', $tipo_seccion)->get_many_by(array("portadas_id" => $objPortada->id));
                            if (count($secciones_portada) > 0) {
                                foreach ($secciones_portada as $index => $objSeccion) {
                                    if ($this->obtener_imagen_maestro($objMaestro, $objSeccion) > 0) {
                                        $detalle_seccion = $this->detalle_secciones_m->get_many_by(array("secciones_id" => $objSeccion->id, "grupo_maestros_id" => $objMaestro->id));
                                        if (count($detalle_seccion) == 0) {
                                            //registramos el programa en esta seccion
                                            $objBeanDetalleSeccion = new stdClass();
                                            $objBeanDetalleSeccion->id = NULL;
                                            $objBeanDetalleSeccion->secciones_id = $objSeccion->id;
                                            $objBeanDetalleSeccion->videos_id = NULL;
                                            $objBeanDetalleSeccion->grupo_maestros_id = $objMaestro->id;
                                            $objBeanDetalleSeccion->canales_id = NULL;
                                            $objBeanDetalleSeccion->imagenes_id = $this->obtener_imagen_maestro($objMaestro, $objSeccion);
                                            $objBeanDetalleSeccion->peso = $this->obtenerPesoDetalleSeccion($objSeccion->id);
                                            $objBeanDetalleSeccion->descripcion_item = '';
                                            $objBeanDetalleSeccion->estado = $this->config->item('estado:publicado');
                                            $objBeanDetalleSeccion->fecha_registro = date("Y-m-d H:i:s");
                                            $objBeanDetalleSeccion->usuario_registro = $user_id;
                                            $objBeanDetalleSeccion->estado_migracion = 9;
                                            $objBeanDetalleSeccion->fecha_migracion = '0000-00-00 00:00:00';
                                            $objBeanDetalleSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                                            $objBeanDetalleSeccionSaved = $this->detalle_secciones_m->saveMaestroDetalle($objBeanDetalleSeccion);
                                        }
                                    }
                                }
                            }
                            //
                        }
                    }
                }
                //}
            }
        }
    }

    /**
     * Método para reordenar y obtener el peso del detalle de una seccion
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $seccion_id
     * @return int
     */
    private function obtenerPesoDetalleSeccion($seccion_id) {
        $returnValue = 1;
        $resultado = $this->detalle_secciones_m->order_by('peso', 'DESC')->get_by(array("secciones_id" => $seccion_id));
        if (count($resultado) > 0) {
            $peso = 2;
            foreach ($resultado as $puntero => $objDetalleSeccion) {
                $this->detalle_secciones_m->update($objDetalleSeccion->id, array("peso" => $peso));
                $peso++;
            }
        }
        return $returnValue;
    }

    /**
     * Método para generar una sección de tipo colección si en caso este no exista
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $maestro_id
     */
    private function generar_seccion_coleccion($maestro_id) {
        if ($maestro_id > 0) {
            $user_id = (int) $this->session->userdata('user_id');
            $objMaestro = $this->grupo_maestro_m->get($maestro_id);
            if (count($objMaestro) > 0) {
                //verificamos si su superior es un programa o un canal
                //si es un canal registramos en la portada del canal
                //si es un programa registramos en la portada programa
                $objMaestroPadre = $this->grupo_detalle_m->get_by(array("grupo_maestro_id" => $maestro_id));
                if (count($objMaestroPadre) > 0) {
                    //rgistramos en la portada del programa
                    $objPortada = $this->portada_m->get_by(array("origen_id" => $objMaestro->canales_id, "tipo_portadas_id" => $this->config->item('portada:programa')));
                } else {
                    //registramos en la portada del canal
                    $objPortada = $this->portada_m->get_by(array("canales_id" => $objMaestro->canales_id, "origen_id" => $objMaestro->canales_id, "tipo_portadas_id" => $this->config->item('portada:canal')));
                }
                if (count($objPortada) > 0) {
                    //verificamos que no exista esta seccion de este maestro antes de registrar
                    $objSeccionExistente = $this->secciones_m->get_many_by(array("grupo_maestros_id" => $maestro_id));
                    if (count($objSeccionExistente) == 0) {
                        $objBeanSeccion = new stdClass();
                        $objBeanSeccion->id = NULL;
                        $objBeanSeccion->nombre = $objMaestro->nombre;
                        $objBeanSeccion->descripcion = $objMaestro->descripcion;
                        $objBeanSeccion->tipo = 0;
                        $objBeanSeccion->portadas_id = $objPortada->id;
                        $objBeanSeccion->tipo_secciones_id = $this->config->item('seccion:coleccion');
                        $objBeanSeccion->reglas_id = NULL;
                        $objBeanSeccion->categorias_id = NULL;
                        $objBeanSeccion->tags_id = NULL;
                        $objBeanSeccion->peso = $this->obtenerPesoSeccionPortada($objPortada->id);
                        $objBeanSeccion->id_mongo = NULL;
                        $objBeanSeccion->estado = $this->config->item('estado:borrador');
                        $objBeanSeccion->templates_id = $this->config->item('template:8items');
                        $objBeanSeccion->fecha_registro = date("Y-m-d H:i:s");
                        $objBeanSeccion->usuario_registro = $user_id;
                        $objBeanSeccion->fecha_actualizacion = date("Y-m-d H:i:s");
                        $objBeanSeccion->usuario_actualizacion = $user_id;
                        $objBeanSeccion->estado_migracion = $this->config->item('migracion:nuevo');
                        $objBeanSeccion->fecha_migracion = '0000-00-00 00:00:00';
                        $objBeanSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                        $objBeanSeccion->grupo_maestros_id = $objMaestro->id;
                        $objBeanSeccionSaved = $this->secciones_m->save($objBeanSeccion);
                    }
                }
            }
        }
    }

    /**
     * Método para obtener el peso y reordenar las secciones
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $portada_id
     * @param int $tipo_seccion
     * @return int
     */
    private function obtenerPesoSeccionPortada($portada_id, $tipo_seccion = 1) {
        if ($tipo_seccion == $this->config->item('seccion:coleccion')) {
            $returnPeso = 3;
        } else {
            $returnPeso = 1;
        }
        $secciones = $this->secciones_m->order_by('peso', 'ASC')->get_many_by(array("portadas_id" => $portada_id));
        if (count($secciones) > 0) {
            if ($tipo_seccion == $this->config->item('seccion:coleccion')) {
                $nuevo_peso = 1;
            } else {
                $nuevo_peso = 2;
            }
            foreach ($secciones as $puntero => $objSeccion) {
                if ($tipo_seccion == $this->config->item('seccion:coleccion')) {
                    if ($nuevo_peso == 3) {
                        $nuevo_peso = $nuevo_peso + 1;
                    }
                    $this->secciones_m->update($objSeccion->id, array("peso" => $nuevo_peso, "estado_migracion" => $this->config->item('migracion:actualizado')));
                    if ($nuevo_peso != 3) {
                        $nuevo_peso++;
                    }
                } else {
                    $this->secciones_m->update($objSeccion->id, array("peso" => $nuevo_peso, "estado_migracion" => $this->config->item('migracion:actualizado')));
                    $nuevo_peso++;
                }
            }
        }
        return $returnPeso;
    }

    /**
     * Método para generar la portada de un programa, verificando antes si este no existe
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $maestro_id
     */
    private function generar_portada_programa($maestro_id) {
        if ($maestro_id > 0) {
            $objMaestro = $this->grupo_maestro_m->get($maestro_id);
            $user_id = (int) $this->session->userdata('user_id');
            if (count($objMaestro) > 0) {
                $objPortadaPrograma = $this->portada_m->get_by(array("origen_id" => $maestro_id, "tipo_portadas_id" => $this->config->item('portada:programa')));
                if (count($objPortadaPrograma) == 0) {
                    $objBeanPortada = new stdClass();
                    $objBeanPortada->id = NULL;
                    $objBeanPortada->canales_id = $objMaestro->canales_id;
                    $objBeanPortada->nombre = $objMaestro->nombre;
                    $objBeanPortada->descripcion = $objMaestro->nombre;
                    $objBeanPortada->tipo_portadas_id = $this->config->item('portada:programa');
                    $objBeanPortada->origen_id = $objMaestro->id;
                    $objBeanPortada->estado = $this->config->item('estado:borrador');
                    $objBeanPortada->fecha_registro = date("Y-m-d H:i:s");
                    $objBeanPortada->usuario_registro = $user_id;
                    $objBeanPortada->fecha_actualizacion = date("Y-m-d H:i:s");
                    $objBeanPortada->usuario_actualizacion = $user_id;
                    $objBeanPortada->id_mongo = NULL;
                    $objBeanPortada->estado_migracion = $this->config->item('migracion:actualizado');
                    $objBeanPortada->fecha_migracion = '0000-00-00 00:00:00';
                    $objBeanPortada->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                    $objBeanPortadaSaved = $this->portada_m->save($objBeanPortada);
                    //generamos tipo de secciones
                    $arraySecciones = $this->tipo_secciones_m->get_many_by(array());
                    foreach ($arraySecciones as $puntero => $oS) {
                        if ($oS->id == $this->config->item('seccion:programa')) {
                            unset($arraySecciones[$puntero]);
                        }
                    }
                    //iteremos los tipos de secciones y creamos 
                    if (count($arraySecciones) > 0) {
                        foreach ($arraySecciones as $ind => $objTipoSeccion) {
                            if ($objTipoSeccion->id < intval($this->config->item('seccion:perzonalizado'))) {//no se creara secciones personalizadas
                                if ($objTipoSeccion->id != intval($this->config->item('seccion:programa'))) {
                                    $objBeanSeccion = new stdClass();
                                    $objBeanSeccion->id = NULL;
                                    if ($objTipoSeccion->id == intval($this->config->item('seccion:coleccion'))) {
                                        $objBeanSeccion->grupo_maestros_id = $objMaestro->id;
                                    } else {
                                        $objBeanSeccion->grupo_maestros_id = NULL;
                                    }
                                    $objBeanSeccion->nombre = $objTipoSeccion->nombre;
                                    $objBeanSeccion->descripcion = '';
                                    $objBeanSeccion->tipo = '0';
                                    $objBeanSeccion->portadas_id = $objBeanPortadaSaved->id;
                                    $objBeanSeccion->tipo_secciones_id = $objTipoSeccion->id;
                                    $objBeanSeccion->reglas_id = NULL;
                                    $objBeanSeccion->categorias_id = NULL;
                                    $objBeanSeccion->tags_id = NULL;
                                    $objBeanSeccion->peso = $ind + 1;
                                    $objBeanSeccion->id_mongo = NULL;
                                    $objBeanSeccion->estado = $this->config->item('estado:borrador');
                                    $objBeanSeccion->templates_id = $this->obtener_template($objTipoSeccion->id);
                                    $objBeanSeccion->fecha_registro = date("Y-m-d H:i:s");
                                    $objBeanSeccion->usuario_registro = $user_id;
                                    $objBeanSeccion->fecha_actualizacion = date("Y-m-d H:i:s");
                                    $objBeanSeccion->usuario_actualizacion = $user_id;
                                    $objBeanSeccion->estado_migracion = $this->config->item('migracion:actualizado');
                                    $objBeanSeccion->fecha_migracion = '0000-00-00 00:00:00';
                                    $objBeanSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                                    $objBeanSeccionSaved = $this->secciones_m->save($objBeanSeccion);
                                    //registramos el detalle de sección para el destacado
                                    if ($objTipoSeccion->id == intval($this->config->item('seccion:destacado'))) {//seccion destacado
                                        $objImagen = $this->imagen_m->get_by(array("grupo_maestros_id" => $objMaestro->id, "estado" => "1", "tipo_imagen_id" => $this->config->item('imagen:extralarge')));
                                        if (count($objImagen) > 0) {
                                            $objBeanDetalleSecciones = new stdClass();
                                            $objBeanDetalleSecciones->id = NULL;
                                            $objBeanDetalleSecciones->secciones_id = $objBeanSeccionSaved->id;
                                            $objBeanDetalleSecciones->reglas_id = NULL;
                                            $objBeanDetalleSecciones->videos_id = NULL;
                                            $objBeanDetalleSecciones->canales_id = NULL;
                                            $objBeanDetalleSecciones->grupo_maestros_id = $objMaestro->id;
                                            $objBeanDetalleSecciones->categorias_id = NULL;
                                            $objBeanDetalleSecciones->tags_id = NULL;
                                            $objBeanDetalleSecciones->imagenes_id = $objImagen->id;
                                            $objBeanDetalleSecciones->peso = 1;
                                            $objBeanDetalleSecciones->descripcion_item = NULL;
                                            $objBeanDetalleSecciones->estado = 1;
                                            $objBeanDetalleSecciones->fecha_registro = date("Y-m-d H:i:s");
                                            $objBeanDetalleSecciones->usuario_registro = $user_id;
                                            $objBeanDetalleSecciones->estado_migracion = '0';
                                            $objBeanDetalleSecciones->fecha_migracion = '0000-00-00 00:00:00';
                                            $objBeanDetalleSecciones->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                                            $objBeanDetalleSeccionesSaved = $this->detalle_secciones_m->save($objBeanDetalleSecciones);
                                            $this->secciones_m->update($objBeanSeccionSaved->id, array("estado" => "1"));
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
     * Método para obtener el ID de template según sea la sección
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $tipo_seccion
     * @return int
     */
    private function obtener_template($tipo_seccion) {
        $returnValue = $this->config->item('template:4items');
        if ($tipo_seccion == $this->config->item('seccion:destacado')) {
            $returnValue = $this->config->item('template:destacado');
        } else {
            if ($tipo_seccion == $this->config->item('seccion:programa')) {
                $returnValue = $this->config->item('template:nitems');
            } else {
                if ($tipo_seccion == $this->config->item('seccion:video')) {
                    $returnValue = $this->config->item('template:8items');
                } else {
                    $returnValue = $this->config->item('template:4items');
                }
            }
        }
        return $returnValue;
    }

    /**
     * Método para obtener el ID de imagen por portada y seccion
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param object $objMaestro
     * @param object $objSeccion
     * @return int
     */
    private function obtener_imagen_maestro($objMaestro, $objSeccion) {
        $returnValue = 0;
        switch ($objSeccion->tipo_secciones_id) {
            case $this->config->item('seccion:destacado'):
                $tipo_imagen = $this->config->item('imagen:extralarge');
                break;
            default:
                $tipo_imagen = $this->config->item('imagen:small');
                break;
        }
        $objImagen = $this->imagen_m->get_by(array("estado" => $this->config->item('estado:publicado'), "grupo_maestros_id" => $objMaestro->id, "tipo_imagen_id" => $tipo_imagen));
        if (count($objImagen) > 0) {
            $returnValue = $objImagen->id;
        }
        return $returnValue;
    }

    /**
     * Método para obtener los tipo de secciones por tipo de  maestro a buscar
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param object $objPortada
     * @param object $objMaestro
     * @return array 
     */
    private function obtener_array_tipo_seccion($objPortada, $objMaestro) {
        //recolectamos tipo de secciones para este tipo
        $returnValue = array();
        if ($objMaestro->tipo_grupo_maestro_id == $this->config->item('videos:programa')) {// para maestros de tipo programa
            if ($objPortada->tipo_portadas_id == $this->config->item('portada:principal')) {
                array_push($returnValue, $this->config->item('seccion:destacado'));
                array_push($returnValue, $this->config->item('seccion:programa'));
            } else {
                if ($objPortada->tipo_portadas_id == $this->config->item('portada:canal')) {
                    array_push($returnValue, $this->config->item('seccion:destacado'));
                    array_push($returnValue, $this->config->item('seccion:programa'));
                } else {
                    if ($objPortada->tipo_portadas_id == $this->config->item('portada:programa')) {
                        array_push($returnValue, $this->config->item('seccion:destacado'));
                    }
                }
            }
        } else {
            if ($objMaestro->tipo_grupo_maestro_id == $this->config->item('videos:coleccion')) {
                if ($objPortada->tipo_portadas_id == $this->config->item('portada:principal')) {
                    array_push($returnValue, $this->config->item('seccion:destacado'));
                } else {
                    if ($objPortada->tipo_portadas_id == $this->config->item('portada:canal')) {
                        array_push($returnValue, $this->config->item('seccion:destacado'));
                    }
                }
            } else {
                if ($objMaestro->tipo_grupo_maestro_id == $this->config->item('videos:lista')) {
                    if ($objPortada->tipo_portadas_id == $this->config->item('portada:principal')) {
                        array_push($returnValue, $this->config->item('seccion:destacado'));
                        array_push($returnValue, $this->config->item('seccion:programa'));
                    } else {
                        if ($objPortada->tipo_portadas_id == $this->config->item('portada:canal')) {
                            array_push($returnValue, $this->config->item('seccion:destacado'));
                            array_push($returnValue, $this->config->item('seccion:coleccion'));
                            array_push($returnValue, $this->config->item('seccion:lista'));
                        } else {
                            if ($objPortada->tipo_portadas_id == $this->config->item('portada:programa')) {
                                array_push($returnValue, $this->config->item('seccion:coleccion'));
                                array_push($returnValue, $this->config->item('seccion:lista'));
                            }
                        }
                    }
                }
            }
        }
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
