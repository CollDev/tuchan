<?php

/**
 * Libreria para la ejecución de actualización de portadas y secciones 
 * La libreria se encargara de parsear los maestros,videos y canales y actualizar en las portadas
 * @author Johnny Huamani <johnny1402@gmail.com>
 * @name Portadas
 * @package Portadas
 * @version 0.1
 */
class Sincronizar_lib extends MX_Controller {

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
        $this->load->model('videos/vw_maestro_video_m');
        $this->load->library("Procesos/log");
    }

    /**
     * Método para publicar videos y activar maestros, portadas y secciones.
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $video_id
     * @param string $ref
     */
    private function publicar_video_canal($objVistaVideo, $ref) {
        //cargamos el config si el origen de llamada es diferente al cms
        if ($ref != 'cms' && $ref != 'importacion') {
            //$this->config->load('videos/uploads');
            $user_id = 1; //usuario administrador
        } else {
            $user_id = (int) $this->session->userdata('user_id');
        }
        //varificamos que el video tenga imagenes
        $objImagen = $this->imagen_m->get_by(array("videos_id" => $objVistaVideo->id, "tipo_imagen_id" => $this->config->item('imagen:small'), "estado" => $this->config->item('estado:publicado')));
        if (count($objImagen) > 0) {
            //obtenemos el objeto del canal
            $objCanal = $this->canales_m->get($objVistaVideo->canales_id);
            if (count($objCanal) > 0) {
                //obtenemos la portada del canal
                $objPortadaCanal = $this->portada_m->get_by(array("origen_id" => $objCanal->id, "canales_id" => $objCanal->id, "tipo_portadas_id" => $this->config->item('portada:canal')));
                if (count($objPortadaCanal) > 0) {
                    //validamos que la portada este publicada
                    //if ($objPortadaCanal->estado == $this->config->item('estado:publicado')) {
                        //obtenemos la sección video de la portada canal
                        $objSeccionVideo = $this->secciones_m->get_by(array("portadas_id" => $objPortadaCanal->id, "tipo_secciones_id" => $this->config->item('seccion:video')));
                        if (count($objSeccionVideo) > 0) {
                            //verificamos que no exista el video registrado en sus detallles
                            $video_detalle_seccion = $this->detalle_secciones_m->get_by(array("secciones_id" => $objSeccionVideo->id, "videos_id" => $objVistaVideo->id));
                            if (count($video_detalle_seccion) > 0) {
                                $this->detalle_secciones_m->update($video_detalle_seccion->id, array("estado_migracion" => $this->config->item('migracion:actualizado'), "imagenes_id" => $objImagen->id, "estado" => $this->config->item('estado:publicado')));
                                //actualizamos la seccion video de la portada
                                $this->secciones_m->update($video_detalle_seccion->secciones_id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                            } else {
                                //insertamos un registro en el detalle de la seccion videos
                                $objBeanDetalleSeccion = new stdClass();
                                $objBeanDetalleSeccion->id = NULL;
                                $objBeanDetalleSeccion->secciones_id = $objSeccionVideo->id;
                                $objBeanDetalleSeccion->videos_id = $objVistaVideo->id;
                                $objBeanDetalleSeccion->grupo_maestros_id = NULL;
                                $objBeanDetalleSeccion->canales_id = NULL;
                                $objBeanDetalleSeccion->imagenes_id = $objImagen->id;
                                $objBeanDetalleSeccion->peso = $this->obtenerPeso($objSeccionVideo->id);
                                $objBeanDetalleSeccion->descripcion_item = '';
                                $objBeanDetalleSeccion->estado = $this->config->item('estado:publicado');
                                $objBeanDetalleSeccion->fecha_registro = date("Y-m-d H:i:s");
                                $objBeanDetalleSeccion->usuario_registro = $user_id;
                                $objBeanDetalleSeccion->estado_migracion = $this->config->item('migracion:nuevo');
                                $objBeanDetalleSeccion->fecha_migracion = '0000-00-00 00:00:00';
                                $objBeanDetalleSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                                $this->detalle_secciones_m->save($objBeanDetalleSeccion);
                                //actualizamos la seccion
                                $this->secciones_m->update($objSeccionVideo->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                            }
                        }
                    //}
                }
            }
        }
    }

    /**
     * Método para publicar todos los maestros relacionados al video
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $video_id
     */
    public function agregar_video($video_id, $ref = 'cms') {
        Log::erroLog("ini - agregar_video: video_id: " . $video_id . ", ref: " . $ref . ' - ' . date("Y-m-d H:i:s"));
        //cargamos el config si el origen de llamada es diferente al cms
        if ($ref != 'cms' && $ref != 'importacion') {
            Log::erroLog("$ref != 'cms' && $ref != 'importacion' true");
            $this->config->load('videos/uploads');
            $user_id = 1; //usuario administrador
        } else {
            Log::erroLog("$ref != 'cms' && $ref != 'importacion' false");
            $user_id = (int) $this->session->userdata('user_id');
        }
        $objVideo = $this->videos_m->get($video_id);
        if (count($objVideo) > 0) {
            Log::erroLog("count objVideo > 0");
            //verificamo si el canal al que pertenece esta publicado
            //verificamos si el origen es del CMS para solo listar el canal sin importar el estado
            if($ref == 'cms'){
                Log::erroLog("ref == cms true");
                $objCanal = $this->canales_m->get_by(array("id" => $objVideo->canales_id));
            } else {
                Log::erroLog("ref == cms false");
                //validamos que el origen no es del cms y obtenemos el canal en modo publicado
                $objCanal = $this->canales_m->get_by(array("id" => $objVideo->canales_id, "estado" => $this->config->item('estado:publicado')));
            }
            if (count($objCanal) > 0) {
                Log::erroLog("objCanal > 0");
                //verificamos que tenga imagenes en la tabla imagenes
                $imagenes_video = $this->imagen_m->get_many_by(array("videos_id" => $video_id, "estado" => $this->config->item('estado:publicado')));
                if (count($imagenes_video) > 0) {
                    Log::erroLog("imagenes_video > 0");
                    //verificamos que el video esté en un estado publicado
                    if ($objVideo->estado == $this->config->item('video:publicado')) {
                        Log::erroLog("objVideo estado publicado");
                        //obtenemos su lista
                        $objVistaVideo = $this->vw_maestro_video_m->get_by(array("v" => "v", "id" => $video_id));
                        if (count($objVistaVideo) > 0) {
                            Log::erroLog("objVistaVideo > 0");
                            //verificamos que el video sea directo al canal
                            if ($objVistaVideo->gm1 == NULL && $objVistaVideo->gm2 == NULL && $objVistaVideo->gm3 == NULL) {
                                Log::erroLog("publicar_video_canal");
                                //agregamos el video a la sección video del canal
                                $this->publicar_video_canal($objVistaVideo, $ref);
                            } else {
                                if ($objVistaVideo->gm1 != NULL && $objVistaVideo->gm2 == NULL && $objVistaVideo->gm3 == NULL) {
                                    Log::erroLog("publicar_lista_canal");
                                    //identificamos que es un video de una lista, cuya lista es directa al canal
                                    $this->publicar_lista_canal($objVistaVideo, $ref);
                                } else {
                                    if ($objVistaVideo->gm1 == NULL && $objVistaVideo->gm2 != NULL && $objVistaVideo->gm3 == NULL) {
                                        Log::erroLog("publicar_coleccion_canal");
                                        //identificamos que es un video directa a una coleccion que es directa al canal
                                        $this->publicar_coleccion_canal($objVistaVideo, $ref);
                                    } else {
                                        if ($objVistaVideo->gm1 == NULL && $objVistaVideo->gm2 == NULL && $objVistaVideo->gm3 != NULL) {
                                            Log::erroLog("publicar_programa_canal");
                                            //identificamos que el video pertenece a un programa del canal
                                            $this->publicar_programa_canal($objVistaVideo, $ref);
                                        } else {
                                            if ($objVistaVideo->gm1 != NULL && $objVistaVideo->gm2 != NULL && $objVistaVideo->gm3 == NULL) {
                                                Log::erroLog("publicar_lista_coleccion_canal");
                                                //identificamos que el video pertenece a una lista-coleccion-canal
                                                $this->publicar_lista_coleccion_canal($objVistaVideo, $ref);
                                            } else {
                                                //identificamos que es de tipo video-lista-programa-canal
                                                if ($objVistaVideo->gm1 != NULL && $objVistaVideo->gm2 == NULL && $objVistaVideo->gm3 != NULL) {
                                                    Log::erroLog("publicar_video_lista_programa_canal");
                                                    $this->publicar_video_lista_programa_canal($objVistaVideo, $ref);
                                                } else {
                                                    if ($objVistaVideo->gm1 == NULL && $objVistaVideo->gm2 != NULL && $objVistaVideo->gm3 != NULL) {
                                                        Log::erroLog("publicar_video_coleccion_programa_canal");
                                                        //identificamos que es de tipo video-coleccion-programa_canal
                                                        $this->publicar_video_coleccion_programa_canal($objVistaVideo, $ref);
                                                    } else {
                                                        Log::erroLog("publicar_video_lista_coleccion_programa_canal");
                                                        $this->publicar_video_lista_coleccion_programa_canal($objVistaVideo, $ref);
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
            }
        }
        Log::erroLog("fin - agregar_video: video_id: " . $video_id . ", ref: " . $ref . ' - ' . date("Y-m-d H:i:s"));
    }

    /**
     * Método para activar la lista y registrar en la seccion lista de la portada del canal
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param object $objVistaVideo
     * @param string $ref
     */
    private function publicar_lista_canal($objVistaVideo, $ref) {
        //cargamos el config si el origen de llamada es diferente al cms
        if ($ref != 'cms' && $ref != 'importacion') {
            //$this->config->load('videos/uploads');
            $user_id = 1; //usuario administrador
        } else {
            $user_id = (int) $this->session->userdata('user_id');
        }
        //varificamos que el video tenga imagenes
        $objImagen = $this->imagen_m->get_by(array("videos_id" => $objVistaVideo->id, "tipo_imagen_id" => $this->config->item('imagen:small'), "estado" => $this->config->item('estado:publicado')));
        //verificar que la lista también tenga imagen
        $objImagenLista = $this->imagen_m->get_by(array("grupo_maestros_id" => $objVistaVideo->gm1, "tipo_imagen_id" => $this->config->item('imagen:small'), "estado" => $this->config->item('estado:publicado')));
        //preguntamos si ambos tienen imagen
        if (count($objImagenLista) > 0 && count($objImagen) > 0) {
            //activamos el maestro lista
            $this->grupo_maestro_m->update($objVistaVideo->gm1, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado'), "estado_migracion_sphinx" => $this->config->item('sphinx:actualizar')));
            //obtenemos la portada del canal
            $objPortadaCanal = $this->portada_m->get_by(array("origen_id" => $objVistaVideo->canales_id, "canales_id" => $objVistaVideo->canales_id, "tipo_portadas_id" => $this->config->item('portada:canal')));
            if (count($objPortadaCanal) > 0) {
                //validamos que la portada este publicada
                //if ($objPortadaCanal->estado == $this->config->item('estado:publicado')) {
                    //obtenemos la seccion lista de la portada canal
                    $objSeccionLista = $this->secciones_m->get_by(array("portadas_id" => $objPortadaCanal->id, "tipo_secciones_id" => $this->config->item('seccion:lista')));
                    if (count($objSeccionLista) > 0) {
                        //verificamos que no exista la lista registrado en sus detallles
                        $lista_detalle_seccion = $this->detalle_secciones_m->get_by(array("secciones_id" => $objSeccionLista->id, "grupo_maestros_id" => $objVistaVideo->gm1));
                        if (count($lista_detalle_seccion) > 0) {
                            $this->detalle_secciones_m->update($lista_detalle_seccion->id, array("estado_migracion" => $this->config->item('migracion:actualizado'), "imagenes_id" => $objImagenLista->id, "estado" => $this->config->item('estado:publicado')));
                            //actualizamos la seccion video de la portada
                            $this->secciones_m->update($lista_detalle_seccion->secciones_id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                        } else {
                            //insertamos un registro en el detalle de la seccion videos
                            $objBeanDetalleSeccion = new stdClass();
                            $objBeanDetalleSeccion->id = NULL;
                            $objBeanDetalleSeccion->secciones_id = $objSeccionLista->id;
                            $objBeanDetalleSeccion->videos_id = NULL;
                            $objBeanDetalleSeccion->grupo_maestros_id = $objVistaVideo->gm1;
                            $objBeanDetalleSeccion->canales_id = NULL;
                            $objBeanDetalleSeccion->imagenes_id = $objImagenLista->id;
                            $objBeanDetalleSeccion->peso = $this->obtenerPeso($objSeccionLista->id);
                            $objBeanDetalleSeccion->descripcion_item = '';
                            $objBeanDetalleSeccion->estado = $this->config->item('estado:publicado');
                            $objBeanDetalleSeccion->fecha_registro = date("Y-m-d H:i:s");
                            $objBeanDetalleSeccion->usuario_registro = $user_id;
                            $objBeanDetalleSeccion->estado_migracion = $this->config->item('migracion:nuevo');
                            $objBeanDetalleSeccion->fecha_migracion = '0000-00-00 00:00:00';
                            $objBeanDetalleSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                            $this->detalle_secciones_m->save($objBeanDetalleSeccion);
                            //actualizamos la seccion
                            $this->secciones_m->update($objSeccionLista->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                        }
                    }
                //}
            }
        }
    }

    /**
     * Método para publicar una coleccion directa del canal
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param object $objVistaVideo
     * @param string $ref
     */
    private function publicar_coleccion_canal($objVistaVideo, $ref) {
        //cargamos el config si el origen de llamada es diferente al cms
        if ($ref != 'cms' && $ref != 'importacion') {
            //$this->config->load('videos/uploads');
            $user_id = 1; //usuario administrador
        } else {
            $user_id = (int) $this->session->userdata('user_id');
        }
        //varificamos que el video tenga imagenes
        $objImagen = $this->imagen_m->get_by(array("videos_id" => $objVistaVideo->id, "tipo_imagen_id" => $this->config->item('imagen:small'), "estado" => $this->config->item('estado:publicado')));
        //no verificamos si el maestro coleccion tiene imagen, xq en la seccion coleccion irá el video
        if (count($objImagen) > 0) {
            //activamos el maestro coleccion apesar que no tiene imagen
            $this->grupo_maestro_m->update($objVistaVideo->gm2, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado'), "estado_migracion_sphinx" => $this->config->item('sphinx:actualizar')));
            //obtenemos la portada del canal
            $objPortadaCanal = $this->portada_m->get_by(array("origen_id" => $objVistaVideo->canales_id, "canales_id" => $objVistaVideo->canales_id, "tipo_portadas_id" => $this->config->item('portada:canal')));
            if (count($objPortadaCanal) > 0) {
                //validamos que la portada este publicada
                //if ($objPortadaCanal->estado == $this->config->item('estado:publicado')) {
                    //obtenemos la seccion coleccion del maestro de la portada canal
                    $objSeccionColeccion = $this->secciones_m->get_by(array("portadas_id" => $objPortadaCanal->id, "tipo_secciones_id" => $this->config->item('seccion:coleccion'), "grupo_maestros_id" => $objVistaVideo->gm2));
                    if (count($objSeccionColeccion) > 0) {
                        //verificamos que no exista el video registrado en sus detallles
                        $coleccion_detalle_seccion = $this->detalle_secciones_m->get_by(array("secciones_id" => $objSeccionColeccion->id, "videos_id" => $objVistaVideo->id));
                        if (count($coleccion_detalle_seccion) > 0) {
                            $this->detalle_secciones_m->update($coleccion_detalle_seccion->id, array("estado_migracion" => $this->config->item('migracion:actualizado'), "imagenes_id" => $objImagen->id, "estado" => $this->config->item('estado:publicado')));
                            //actualizamos la seccion video de la portada
                            $this->secciones_m->update($coleccion_detalle_seccion->secciones_id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                        } else {
                            //insertamos un registro en el detalle de la seccion videos
                            $objBeanDetalleSeccion = new stdClass();
                            $objBeanDetalleSeccion->id = NULL;
                            $objBeanDetalleSeccion->secciones_id = $objSeccionColeccion->id;
                            $objBeanDetalleSeccion->videos_id = NULL;
                            $objBeanDetalleSeccion->grupo_maestros_id = $objVistaVideo->gm1;
                            $objBeanDetalleSeccion->canales_id = NULL;
                            $objBeanDetalleSeccion->imagenes_id = $objImagen->id;
                            $objBeanDetalleSeccion->peso = $this->obtenerPeso($objSeccionColeccion->id);
                            $objBeanDetalleSeccion->descripcion_item = '';
                            $objBeanDetalleSeccion->estado = $this->config->item('estado:publicado');
                            $objBeanDetalleSeccion->fecha_registro = date("Y-m-d H:i:s");
                            $objBeanDetalleSeccion->usuario_registro = $user_id;
                            $objBeanDetalleSeccion->estado_migracion = $this->config->item('migracion:nuevo');
                            $objBeanDetalleSeccion->fecha_migracion = '0000-00-00 00:00:00';
                            $objBeanDetalleSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                            $this->detalle_secciones_m->save($objBeanDetalleSeccion);
                            //actualizamos la seccion
                            $this->secciones_m->update($objSeccionColeccion->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                        }
                    }
                //}
            }
        }
    }

    /**
     * Método para publicar un video directo al programa
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param object $objVistaVideo
     * @param string $ref
     */
    private function publicar_programa_canal($objVistaVideo, $ref) {
        //cargamos el config si el origen de llamada es diferente al cms
        if ($ref != 'cms' && $ref != 'importacion') {
            //$this->config->load('videos/uploads');
            $user_id = 1; //usuario administrador
        } else {
            $user_id = (int) $this->session->userdata('user_id');
        }
        $objImagenXLPrograma = $this->imagen_m->where_in('tipo_imagen_id', array($this->config->item('imagen:extralarge')))->get_by(array("estado" => $this->config->item('estado:publicado'), "grupo_maestros_id" => $objVistaVideo->gm3));
        $objImagenSPrograma = $this->imagen_m->where_in('tipo_imagen_id', array($this->config->item('imagen:small')))->get_by(array("estado" => $this->config->item('estado:publicado'), "grupo_maestros_id" => $objVistaVideo->gm3));
        //verificamos si tiene portada del programa
        $objPortadaPrograma = $this->portada_m->get_by(array("origen_id" => $objVistaVideo->gm3, "tipo_portadas_id" => $this->config->item('portada:programa')));
        if (count($objPortadaPrograma) > 0) {
            //validamos que esté en estado publicado
            if ($objPortadaPrograma->estado != $this->config->item('estado:publicado')) {
                //preguntamos si el programa cuenta con una imagen XL o S para estar apto a publicar
                if (count($objImagenXLPrograma) > 0 && count($objImagenSPrograma) > 0) {
                    //verificamos si el programa está registrado en el destacado de la portada programa
                    $objSeccionDestacadoPrograma = $this->secciones_m->get_by(array("portadas_id" => $objPortadaPrograma->id, "tipo_secciones_id" => $this->config->item('seccion:destacado')));
                    if (count($objSeccionDestacadoPrograma) > 0) {
                        //actualizamos el destacado del programa
                        $detalle_seccion = $this->detalle_secciones_m->get_by(array("secciones_id" => $objSeccionDestacadoPrograma->id, "grupo_maestros_id" => $objVistaVideo->gm3));
                        if (count($detalle_seccion) > 0) {
                            //actualizamos el detalle seccion destacado
                            $this->detalle_secciones_m->update($detalle_seccion->id, array("imagenes_id" => $objImagenXLPrograma->id, "estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                            //activamos la sección destacado
                            $this->secciones_m->update($objSeccionDestacadoPrograma->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                        } else {
                            //registramos el detalle seccion de la seccion destacado
                            $objBeanDetalleSeccion = new stdClass();
                            $objBeanDetalleSeccion->id = NULL;
                            $objBeanDetalleSeccion->secciones_id = $objSeccionDestacadoPrograma->id;
                            $objBeanDetalleSeccion->videos_id = NULL;
                            $objBeanDetalleSeccion->grupo_maestros_id = $objVistaVideo->gm3;
                            $objBeanDetalleSeccion->canales_id = NULL;
                            $objBeanDetalleSeccion->imagenes_id = $objImagenXLPrograma->id;
                            $objBeanDetalleSeccion->peso = $this->obtenerPeso($objSeccionDestacadoPrograma->id);
                            $objBeanDetalleSeccion->descripcion_item = '';
                            $objBeanDetalleSeccion->estado = $this->config->item('estado:publicado');
                            $objBeanDetalleSeccion->fecha_registro = date("Y-m-d H:i:s");
                            $objBeanDetalleSeccion->usuario_registro = $user_id;
                            $objBeanDetalleSeccion->estado_migracion = $this->config->item('migracion:nuevo');
                            $objBeanDetalleSeccion->fecha_migracion = '0000-00-00 00:00:00';
                            $objBeanDetalleSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                            $this->detalle_secciones_m->save($objBeanDetalleSeccion);
                            //actualizamos la seccion
                            $this->secciones_m->update($objSeccionDestacadoPrograma->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                        }
                    }
                }
            }
            if (count($objImagenXLPrograma) > 0 && count($objImagenSPrograma) > 0) {
                //verificamos la imagen del video
                $objImagenVideo = $this->imagen_m->get_by(array("videos_id" => $objVistaVideo->id, "tipo_imagen_id" => $this->config->item('imagen:small'), "estado" => $this->config->item('estado:publicado')));
                if (count($objImagenVideo) > 0) {
                    //registramos la sección videos de la portada programa
                    //obtenemos el objecto seccion video de la portada programa
                    $objSeccionVideoPrograma = $this->secciones_m->get_by(array("portadas_id" => $objPortadaPrograma->id, "tipo_secciones_id" => $this->config->item('seccion:video')));
                    if (count($objSeccionVideoPrograma) > 0) {
                        //obtenemos la seccion video de la portada programa
                        $detalle_seccion = $this->detalle_secciones_m->get_by(array("secciones_id" => $objSeccionVideoPrograma->id, "videos_id" => $objVistaVideo->id));
                        if (count($detalle_seccion) > 0) {
                            //actualizamos el detalle sección
                            $this->detalle_secciones_m->update($detalle_seccion->id, array("imagenes_id" => $objImagenVideo->id, "estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                            //activamos la sección destacado
                            $this->secciones_m->update($objSeccionVideoPrograma->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                        } else {
                            //registramos el video en esta sección
                            $objBeanDetalleSeccion = new stdClass();
                            $objBeanDetalleSeccion->id = NULL;
                            $objBeanDetalleSeccion->secciones_id = $objSeccionVideoPrograma->id;
                            $objBeanDetalleSeccion->videos_id = $objVistaVideo->id;
                            $objBeanDetalleSeccion->grupo_maestros_id = NULL;
                            $objBeanDetalleSeccion->canales_id = NULL;
                            $objBeanDetalleSeccion->imagenes_id = $objImagenVideo->id;
                            $objBeanDetalleSeccion->peso = $this->obtenerPeso($objSeccionVideoPrograma->id);
                            $objBeanDetalleSeccion->descripcion_item = '';
                            $objBeanDetalleSeccion->estado = $this->config->item('estado:publicado');
                            $objBeanDetalleSeccion->fecha_registro = date("Y-m-d H:i:s");
                            $objBeanDetalleSeccion->usuario_registro = $user_id;
                            $objBeanDetalleSeccion->estado_migracion = $this->config->item('migracion:nuevo');
                            $objBeanDetalleSeccion->fecha_migracion = '0000-00-00 00:00:00';
                            $objBeanDetalleSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                            $this->detalle_secciones_m->save($objBeanDetalleSeccion);
                            //actualizamos la seccion
                            $this->secciones_m->update($objSeccionVideoPrograma->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                        }
                    }
                }
                //publicamos el maestro programa
                $this->grupo_maestro_m->update($objVistaVideo->gm3, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado'), "estado_migracion_sphinx" => $this->config->item('sphinx:actualizar')));
                //publicamos la portada del programa
                $this->portada_m->update($objPortadaPrograma->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                //existe el programa en la sección programas de la portada del canal
                //primero obtenemos la portada del canal
                $objPortadaCanal = $this->portada_m->get_by(array("tipo_portadas_id" => $this->config->item('portada:canal'), "origen_id" => $objVistaVideo->canales_id, "canales_id" => $objVistaVideo->canales_id));
                if (count($objPortadaCanal) > 0) {
                    //verificamos que el programa tenga una imagen small por lo menos
                    $objImagenSmallPrograma = $this->imagen_m->get_by(array("estado" => $this->config->item('estado:publicado'), "grupo_maestros_id" => $objVistaVideo->gm3, "tipo_imagen_id" => $this->config->item('imagen:small')));
                    if (count($objImagenSmallPrograma) > 0) {
                        //obtenemos la seccion programa de la portada del canal
                        $objSeccionProgramaCanal = $this->secciones_m->get_by(array("portadas_id" => $objPortadaCanal->id, "tipo_secciones_id" => $this->config->item('seccion:programa')));
                        if (count($objSeccionProgramaCanal) > 0) {
                            $detalle_seccion_programa = $this->detalle_secciones_m->get_by(array("secciones_id" => $objSeccionProgramaCanal->id, "grupo_maestros_id" => $objVistaVideo->gm3));
                            if (count($detalle_seccion_programa) > 0) {
                                //actualizamos el detalle sección
                                $this->detalle_secciones_m->update($detalle_seccion_programa->id, array("estado" => $this->config->item('estado:publicado'), "imagenes_id" => $objImagenSmallPrograma->id, "estado_migracion" => $this->config->item('migracion:actualizado')));
                                //activamos la sección programa de la portada canal
                                $this->secciones_m->update($objSeccionProgramaCanal->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                            } else {
                                //registramos el programa al detalle sección
                                $objBeanDetalleSeccion = new stdClass();
                                $objBeanDetalleSeccion->id = NULL;
                                $objBeanDetalleSeccion->secciones_id = $objSeccionProgramaCanal->id;
                                $objBeanDetalleSeccion->videos_id = NULL;
                                $objBeanDetalleSeccion->grupo_maestros_id = $objVistaVideo->gm3;
                                $objBeanDetalleSeccion->canales_id = NULL;
                                $objBeanDetalleSeccion->imagenes_id = $objImagenSmallPrograma->id;
                                $objBeanDetalleSeccion->peso = $this->obtenerPeso($objSeccionProgramaCanal->id);
                                $objBeanDetalleSeccion->descripcion_item = '';
                                $objBeanDetalleSeccion->estado = $this->config->item('estado:publicado');
                                $objBeanDetalleSeccion->fecha_registro = date("Y-m-d H:i:s");
                                $objBeanDetalleSeccion->usuario_registro = $user_id;
                                $objBeanDetalleSeccion->estado_migracion = $this->config->item('migracion:nuevo');
                                $objBeanDetalleSeccion->fecha_migracion = '0000-00-00 00:00:00';
                                $objBeanDetalleSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                                $this->detalle_secciones_m->save($objBeanDetalleSeccion);
                                //actualizamos la seccion
                                $this->secciones_m->update($objSeccionProgramaCanal->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Método para publicar un video que pertenece a una lista-programa-canal
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param object $objVistaVideo
     * @param string $ref
     */
    private function publicar_video_lista_programa_canal($objVistaVideo, $ref) {
        //cargamos el config si el origen de llamada es diferente al cms
        if ($ref != 'cms' && $ref != 'importacion') {
            //$this->config->load('videos/uploads');
            $user_id = 1; //usuario administrador
        } else {
            $user_id = (int) $this->session->userdata('user_id');
        }
        //preguntamos si el programa cuenta con una imagen XL o S para estar apto a publicar
        $objImagenXLPrograma = $this->imagen_m->where_in('tipo_imagen_id', array($this->config->item('imagen:extralarge')))->get_by(array("estado" => $this->config->item('estado:publicado'), "grupo_maestros_id" => $objVistaVideo->gm3));
        $objImagenSPrograma = $this->imagen_m->where_in('tipo_imagen_id', array($this->config->item('imagen:small')))->get_by(array("estado" => $this->config->item('estado:publicado'), "grupo_maestros_id" => $objVistaVideo->gm3));
        //verificamos que la lista también tenga una imagen activa
        $objImagenSLista = $this->imagen_m->where_in('tipo_imagen_id', array($this->config->item('imagen:small')))->get_by(array("estado" => $this->config->item('estado:publicado'), "grupo_maestros_id" => $objVistaVideo->gm1));
        //verificamos si tiene portada del programa
        $objPortadaPrograma = $this->portada_m->get_by(array("origen_id" => $objVistaVideo->gm3, "tipo_portadas_id" => $this->config->item('portada:programa')));
        if (count($objPortadaPrograma) > 0) {
            //validamos que esté en estado publicado
            if ($objPortadaPrograma->estado != $this->config->item('estado:publicado')) {
                if (count($objImagenXLPrograma) > 0 && count($objImagenSPrograma) > 0 && count($objImagenSLista) > 0) {
                    //verificamos si el programa está registrado en el destacado de la portada programa
                    $objSeccionDestacadoPrograma = $this->secciones_m->get_by(array("portadas_id" => $objPortadaPrograma->id, "tipo_secciones_id" => $this->config->item('seccion:destacado')));
                    if (count($objSeccionDestacadoPrograma) > 0) {
                        //obtenemos el detalle seccion del destacado de un programa
                        $detalle_seccion = $this->detalle_secciones_m->get_by(array("secciones_id" => $objSeccionDestacadoPrograma->id, "grupo_maestros_id" => $objVistaVideo->gm3));
                        if (count($detalle_seccion) > 0) {
                            //actualizamos el detalle seccion destacado
                            $this->detalle_secciones_m->update($detalle_seccion->id, array("imagenes_id" => $objImagenXLPrograma->id, "estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                            //activamos la sección destacado
                            $this->secciones_m->update($objSeccionDestacadoPrograma->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                        } else {
                            //registramos el detalle seccion de la seccion destacado
                            $objBeanDetalleSeccion = new stdClass();
                            $objBeanDetalleSeccion->id = NULL;
                            $objBeanDetalleSeccion->secciones_id = $objSeccionDestacadoPrograma->id;
                            $objBeanDetalleSeccion->videos_id = NULL;
                            $objBeanDetalleSeccion->grupo_maestros_id = $objVistaVideo->gm3;
                            $objBeanDetalleSeccion->canales_id = NULL;
                            $objBeanDetalleSeccion->imagenes_id = $objImagenXLPrograma->id;
                            $objBeanDetalleSeccion->peso = $this->obtenerPeso($objSeccionDestacadoPrograma->id);
                            $objBeanDetalleSeccion->descripcion_item = '';
                            $objBeanDetalleSeccion->estado = $this->config->item('estado:publicado');
                            $objBeanDetalleSeccion->fecha_registro = date("Y-m-d H:i:s");
                            $objBeanDetalleSeccion->usuario_registro = $user_id;
                            $objBeanDetalleSeccion->estado_migracion = $this->config->item('migracion:nuevo');
                            $objBeanDetalleSeccion->fecha_migracion = '0000-00-00 00:00:00';
                            $objBeanDetalleSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                            $this->detalle_secciones_m->save($objBeanDetalleSeccion);
                            //actualizamos la seccion
                            $this->secciones_m->update($objSeccionDestacadoPrograma->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                        }
                    }
                }
            }
            if (count($objImagenXLPrograma) > 0 && count($objImagenSPrograma) > 0 && count($objImagenSLista) > 0) {
                //verificamos la imagen de la lista
                $objImagenLista = $this->imagen_m->get_by(array("grupo_maestros_id" => $objVistaVideo->gm1, "tipo_imagen_id" => $this->config->item('imagen:small'), "estado" => $this->config->item('estado:publicado')));
                if (count($objImagenLista) > 0) {
                    //registramos la sección listas de la portada programa
                    //obtenemos el objecto seccion lista de la portada programa
                    $objSeccionListaPrograma = $this->secciones_m->get_by(array("portadas_id" => $objPortadaPrograma->id, "tipo_secciones_id" => $this->config->item('seccion:lista')));
                    if (count($objSeccionListaPrograma) > 0) {
                        //obtenemos la seccion video de la portada programa
                        $detalle_seccion = $this->detalle_secciones_m->get_by(array("secciones_id" => $objSeccionListaPrograma->id, "grupo_maestros_id" => $objVistaVideo->gm1));
                        if (count($detalle_seccion) > 0) {
                            //actualizamos el detalle sección
                            $this->detalle_secciones_m->update($detalle_seccion->id, array("imagenes_id" => $objImagenLista->id, "estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                            //activamos la sección destacado
                            $this->secciones_m->update($objSeccionListaPrograma->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                        } else {
                            //registramos el video en esta sección
                            $objBeanDetalleSeccion = new stdClass();
                            $objBeanDetalleSeccion->id = NULL;
                            $objBeanDetalleSeccion->secciones_id = $objSeccionListaPrograma->id;
                            $objBeanDetalleSeccion->videos_id = NULL;
                            $objBeanDetalleSeccion->grupo_maestros_id = $objVistaVideo->gm1;
                            $objBeanDetalleSeccion->canales_id = NULL;
                            $objBeanDetalleSeccion->imagenes_id = $objImagenLista->id;
                            $objBeanDetalleSeccion->peso = $this->obtenerPeso($objSeccionListaPrograma->id);
                            $objBeanDetalleSeccion->descripcion_item = '';
                            $objBeanDetalleSeccion->estado = $this->config->item('estado:publicado');
                            $objBeanDetalleSeccion->fecha_registro = date("Y-m-d H:i:s");
                            $objBeanDetalleSeccion->usuario_registro = $user_id;
                            $objBeanDetalleSeccion->estado_migracion = $this->config->item('migracion:nuevo');
                            $objBeanDetalleSeccion->fecha_migracion = '0000-00-00 00:00:00';
                            $objBeanDetalleSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                            $this->detalle_secciones_m->save($objBeanDetalleSeccion);
                            //actualizamos la seccion
                            $this->secciones_m->update($objSeccionListaPrograma->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                        }
                    }
                }
                //publicamos el maestro programa
                $this->grupo_maestro_m->update($objVistaVideo->gm3, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado'), "estado_migracion_sphinx" => $this->config->item('sphinx:actualizar')));
                //publicamos el maestro lista
                $this->grupo_maestro_m->update($objVistaVideo->gm1, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado'), "estado_migracion_sphinx" => $this->config->item('sphinx:actualizar')));
                //publicamos la portada del programa
                $this->portada_m->update($objPortadaPrograma->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                //existe el programa en la sección programas de la portada del canal
                //primero obtenemos la portada del canal
                $objPortadaCanal = $this->portada_m->get_by(array("tipo_portadas_id" => $this->config->item('portada:canal'), "origen_id" => $objVistaVideo->canales_id, "canales_id" => $objVistaVideo->canales_id));
                if (count($objPortadaCanal) > 0) {
                    //verificamos que el programa tenga una imagen small por lo menos
                    if (count($objImagenSPrograma) > 0) {
                        //obtenemos la seccion programa de la portada del canal
                        $objSeccionProgramaCanal = $this->secciones_m->get_by(array("portadas_id" => $objPortadaCanal->id, "tipo_secciones_id" => $this->config->item('seccion:programa')));
                        if (count($objSeccionProgramaCanal) > 0) {
                            $detalle_seccion_programa = $this->detalle_secciones_m->get_by(array("secciones_id" => $objSeccionProgramaCanal->id, "grupo_maestros_id" => $objVistaVideo->gm3));
                            if (count($detalle_seccion_programa) > 0) {
                                //actualizamos el detalle sección
                                $this->detalle_secciones_m->update($detalle_seccion_programa->id, array("estado" => $this->config->item('estado:publicado'), "imagenes_id" => $objImagenSPrograma->id, "estado_migracion" => $this->config->item('migracion:actualizado')));
                                //activamos la sección programa de la portada canal
                                $this->secciones_m->update($objSeccionProgramaCanal->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                            } else {
                                //registramos el programa al detalle sección
                                $objBeanDetalleSeccion = new stdClass();
                                $objBeanDetalleSeccion->id = NULL;
                                $objBeanDetalleSeccion->secciones_id = $objSeccionProgramaCanal->id;
                                $objBeanDetalleSeccion->videos_id = NULL;
                                $objBeanDetalleSeccion->grupo_maestros_id = $objVistaVideo->gm3;
                                $objBeanDetalleSeccion->canales_id = NULL;
                                $objBeanDetalleSeccion->imagenes_id = $objImagenSPrograma->id;
                                $objBeanDetalleSeccion->peso = $this->obtenerPeso($objSeccionProgramaCanal->id);
                                $objBeanDetalleSeccion->descripcion_item = '';
                                $objBeanDetalleSeccion->estado = $this->config->item('estado:publicado');
                                $objBeanDetalleSeccion->fecha_registro = date("Y-m-d H:i:s");
                                $objBeanDetalleSeccion->usuario_registro = $user_id;
                                $objBeanDetalleSeccion->estado_migracion = $this->config->item('migracion:nuevo');
                                $objBeanDetalleSeccion->fecha_migracion = '0000-00-00 00:00:00';
                                $objBeanDetalleSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                                $this->detalle_secciones_m->save($objBeanDetalleSeccion);
                                //actualizamos la seccion
                                $this->secciones_m->update($objSeccionProgramaCanal->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Método para publicar un video relacionado a una coleccion-programa-canal
     * Johnny Huamani <johnny1402@gmail.com>
     * @param object $objVistaVideo
     * @param string $ref
     */
    private function publicar_video_coleccion_programa_canal($objVistaVideo, $ref) {
        //cargamos el config si el origen de llamada es diferente al cms
        if ($ref != 'cms' && $ref != 'importacion') {
            //$this->config->load('videos/uploads');
            $user_id = 1; //usuario administrador
        } else {
            $user_id = (int) $this->session->userdata('user_id');
        }
        //preguntamos si el programa cuenta con una imagen XL o S para estar apto a publicar
        $objImagenXLPrograma = $this->imagen_m->where_in('tipo_imagen_id', array($this->config->item('imagen:extralarge')))->get_by(array("estado" => $this->config->item('estado:publicado'), "grupo_maestros_id" => $objVistaVideo->gm3));
        $objImagenSPrograma = $this->imagen_m->where_in('tipo_imagen_id', array($this->config->item('imagen:small')))->get_by(array("estado" => $this->config->item('estado:publicado'), "grupo_maestros_id" => $objVistaVideo->gm3));
        //verificamos si tiene portada del programa
        $objPortadaPrograma = $this->portada_m->get_by(array("origen_id" => $objVistaVideo->gm3, "tipo_portadas_id" => $this->config->item('portada:programa')));
        if (count($objPortadaPrograma) > 0) {
            //validamos que esté en estado publicado
            if ($objPortadaPrograma->estado != $this->config->item('estado:publicado')) {
                //verificamos que el programa tenga las imagenes S y XL
                if (count($objImagenXLPrograma) > 0 && count($objImagenSPrograma) > 0) {
                    //verificamos si el programa está registrado en el destacado de la portada programa
                    $objSeccionDestacadoPrograma = $this->secciones_m->get_by(array("portadas_id" => $objPortadaPrograma->id, "tipo_secciones_id" => $this->config->item('seccion:destacado')));
                    if (count($objSeccionDestacadoPrograma) > 0) {
                        //obtenemos el detalle seccion del destacado de un programa
                        $detalle_seccion = $this->detalle_secciones_m->get_by(array("secciones_id" => $objSeccionDestacadoPrograma->id, "grupo_maestros_id" => $objVistaVideo->gm3));
                        if (count($detalle_seccion) > 0) {
                            //actualizamos el detalle seccion destacado
                            $this->detalle_secciones_m->update($detalle_seccion->id, array("imagenes_id" => $objImagenXLPrograma->id, "estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                            //activamos la sección destacado
                            $this->secciones_m->update($objSeccionDestacadoPrograma->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                        } else {
                            //registramos el detalle seccion de la seccion destacado
                            $objBeanDetalleSeccion = new stdClass();
                            $objBeanDetalleSeccion->id = NULL;
                            $objBeanDetalleSeccion->secciones_id = $objSeccionDestacadoPrograma->id;
                            $objBeanDetalleSeccion->videos_id = NULL;
                            $objBeanDetalleSeccion->grupo_maestros_id = $objVistaVideo->gm3;
                            $objBeanDetalleSeccion->canales_id = NULL;
                            $objBeanDetalleSeccion->imagenes_id = $objImagenXLPrograma->id;
                            $objBeanDetalleSeccion->peso = $this->obtenerPeso($objSeccionDestacadoPrograma->id);
                            $objBeanDetalleSeccion->descripcion_item = '';
                            $objBeanDetalleSeccion->estado = $this->config->item('estado:publicado');
                            $objBeanDetalleSeccion->fecha_registro = date("Y-m-d H:i:s");
                            $objBeanDetalleSeccion->usuario_registro = $user_id;
                            $objBeanDetalleSeccion->estado_migracion = $this->config->item('migracion:nuevo');
                            $objBeanDetalleSeccion->fecha_migracion = '0000-00-00 00:00:00';
                            $objBeanDetalleSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                            $this->detalle_secciones_m->save($objBeanDetalleSeccion);
                            //actualizamos la seccion
                            $this->secciones_m->update($objSeccionDestacadoPrograma->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                        }
                    }
                }
            }
            if (count($objImagenXLPrograma) > 0 && count($objImagenSPrograma) > 0) {
                //verificamos la imagen del video
                $objImagenVideo = $this->imagen_m->get_by(array("videos_id" => $objVistaVideo->id, "tipo_imagen_id" => $this->config->item('imagen:small'), "estado" => $this->config->item('estado:publicado')));
                if (count($objImagenVideo) > 0) {
                    //obtenemos el objecto seccion coleccion de la portada programa
                    $objSeccionColeccionPrograma = $this->secciones_m->get_by(array("portadas_id" => $objPortadaPrograma->id, "tipo_secciones_id" => $this->config->item('seccion:coleccion'), "grupo_maestros_id" => $objVistaVideo->gm2));
                    if (count($objSeccionColeccionPrograma) > 0) {
                        //obtenemos el detalle seccion coleccion de la portada programa
                        $detalle_seccion = $this->detalle_secciones_m->get_by(array("secciones_id" => $objSeccionColeccionPrograma->id, "videos_id" => $objVistaVideo->id));
                        if (count($detalle_seccion) > 0) {
                            //actualizamos el detalle sección
                            $this->detalle_secciones_m->update($detalle_seccion->id, array("imagenes_id" => $objImagenVideo->id, "estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                            //activamos la sección destacado
                            $this->secciones_m->update($objSeccionColeccionPrograma->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                        } else {
                            //registramos el video en esta sección
                            $objBeanDetalleSeccion = new stdClass();
                            $objBeanDetalleSeccion->id = NULL;
                            $objBeanDetalleSeccion->secciones_id = $objSeccionColeccionPrograma->id;
                            $objBeanDetalleSeccion->videos_id = $objVistaVideo->id;
                            $objBeanDetalleSeccion->grupo_maestros_id = NULL;
                            $objBeanDetalleSeccion->canales_id = NULL;
                            $objBeanDetalleSeccion->imagenes_id = $objImagenVideo->id;
                            $objBeanDetalleSeccion->peso = $this->obtenerPeso($objSeccionColeccionPrograma->id);
                            $objBeanDetalleSeccion->descripcion_item = '';
                            $objBeanDetalleSeccion->estado = $this->config->item('estado:publicado');
                            $objBeanDetalleSeccion->fecha_registro = date("Y-m-d H:i:s");
                            $objBeanDetalleSeccion->usuario_registro = $user_id;
                            $objBeanDetalleSeccion->estado_migracion = $this->config->item('migracion:nuevo');
                            $objBeanDetalleSeccion->fecha_migracion = '0000-00-00 00:00:00';
                            $objBeanDetalleSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                            $this->detalle_secciones_m->save($objBeanDetalleSeccion);
                            //actualizamos la seccion
                            $this->secciones_m->update($objSeccionColeccionPrograma->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                        }
                    }
                }
                //publicamos el maestro programa
                $this->grupo_maestro_m->update($objVistaVideo->gm3, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado'), "estado_migracion_sphinx" => $this->config->item('sphinx:actualizar')));
                //publicamos el maestro coleccion
                $this->grupo_maestro_m->update($objVistaVideo->gm2, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado'), "estado_migracion_sphinx" => $this->config->item('sphinx:actualizar')));
                //publicamos la portada del programa
                $this->portada_m->update($objPortadaPrograma->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                //existe el programa en la sección programas de la portada del canal
                //primero obtenemos la portada del canal
                $objPortadaCanal = $this->portada_m->get_by(array("tipo_portadas_id" => $this->config->item('portada:canal'), "origen_id" => $objVistaVideo->canales_id, "canales_id" => $objVistaVideo->canales_id));
                if (count($objPortadaCanal) > 0) {
                    //verificamos que el programa tenga una imagen small por lo menos
                    if (count($objImagenSPrograma) > 0) {
                        //obtenemos la seccion programa de la portada del canal
                        $objSeccionProgramaCanal = $this->secciones_m->get_by(array("portadas_id" => $objPortadaCanal->id, "tipo_secciones_id" => $this->config->item('seccion:programa')));
                        if (count($objSeccionProgramaCanal) > 0) {
                            $detalle_seccion_programa = $this->detalle_secciones_m->get_by(array("secciones_id" => $objSeccionProgramaCanal->id, "grupo_maestros_id" => $objVistaVideo->gm3));
                            if (count($detalle_seccion_programa) > 0) {
                                //actualizamos el detalle sección
                                $this->detalle_secciones_m->update($detalle_seccion_programa->id, array("estado" => $this->config->item('estado:publicado'), "imagenes_id" => $objImagenSPrograma->id, "estado_migracion" => $this->config->item('migracion:actualizado')));
                                //activamos la sección programa de la portada canal
                                $this->secciones_m->update($objSeccionProgramaCanal->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                            } else {
                                //registramos el programa al detalle sección
                                $objBeanDetalleSeccion = new stdClass();
                                $objBeanDetalleSeccion->id = NULL;
                                $objBeanDetalleSeccion->secciones_id = $objSeccionProgramaCanal->id;
                                $objBeanDetalleSeccion->videos_id = NULL;
                                $objBeanDetalleSeccion->grupo_maestros_id = $objVistaVideo->gm3;
                                $objBeanDetalleSeccion->canales_id = NULL;
                                $objBeanDetalleSeccion->imagenes_id = $objImagenSPrograma->id;
                                $objBeanDetalleSeccion->peso = $this->obtenerPeso($objSeccionProgramaCanal->id);
                                $objBeanDetalleSeccion->descripcion_item = '';
                                $objBeanDetalleSeccion->estado = $this->config->item('estado:publicado');
                                $objBeanDetalleSeccion->fecha_registro = date("Y-m-d H:i:s");
                                $objBeanDetalleSeccion->usuario_registro = $user_id;
                                $objBeanDetalleSeccion->estado_migracion = $this->config->item('migracion:nuevo');
                                $objBeanDetalleSeccion->fecha_migracion = '0000-00-00 00:00:00';
                                $objBeanDetalleSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                                $this->detalle_secciones_m->save($objBeanDetalleSeccion);
                                //actualizamos la seccion
                                $this->secciones_m->update($objSeccionProgramaCanal->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Método para publicar un video relacionado a una lista-coleccion-programa-canal
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param object $objVistaVideo
     * @param string $ref
     */
    private function publicar_video_lista_coleccion_programa_canal($objVistaVideo, $ref) {
        Log::erroLog("ini - publicar_video_lista_coleccion_programa_canal ref: " . $ref . ' - ' . date("Y-m-d H:i:s") . var_dump($objVistaVideo->gm1));
        //cargamos el config si el origen de llamada es diferente al cms
        if ($ref != 'cms' && $ref != 'importacion') {
            Log::erroLog("ref != 'cms' && ref != 'importacion' true");
            //$this->config->load('videos/uploads');
            $user_id = 1; //usuario administrador
        } else {
            Log::erroLog("ref != 'cms' && ref != 'importacion' false");
            $user_id = (int) $this->session->userdata('user_id');
        }
        Log::erroLog("objVistaVideo->gm1: " . $objVistaVideo->gm1);
        Log::erroLog("objVistaVideo->gm3: " . $objVistaVideo->gm3);
        $objGrupoMaestro = $this->grupo_maestro_m->get_by(array("id" => $objVistaVideo->gm3));//gm1 lista gm3 programa
        if ($objGrupoMaestro->tipo_grupo_maestro_id == 3) {
            Log::erroLog("bjGrupoMaestro->tipo_grupo_maestro_id == 3");
            $objGrupoDetalles = $this->grupo_detalle_m->get_many_by(array('grupo_maestro_padre' => $objVistaVideo->gm1));
            Log::erroLog("objGrupoMaestro->canales_id: " . $objGrupoMaestro->canales_id);
            $objCanales = $this->canales_m->get_by(array('id' => $objGrupoMaestro->canales_id));
            $allowed = false;
            foreach ($objGrupoDetalles as $objGrupoDetalle) {
                Log::erroLog("objGrupoDetalle->video_id: " . $objGrupoDetalle->video_id);
                $objVideos = $this->videos_m->get_by(array('id' => $objGrupoDetalle->video_id));
                Log::erroLog('fecha transmision: ' . $objVideos->fecha_transmision . ' ' . $objVideos->horario_transmision_inicio);
                Log::erroLog('valida mayor: ' . strtotime(date("Y-m-d H:i:s")) . ' > ' . $this->add_hours_to_date($objVideos->fecha_transmision . ' ' . $objVideos->horario_transmision_inicio, $objCanales->ibope));
                Log::erroLog('verdadero o falso: ' . print_r(strtotime(date("Y-m-d H:i:s")) > $this->add_hours_to_date($objVideos->fecha_transmision . ' ' . $objVideos->horario_transmision_inicio, $objCanales->ibope) ));
                if (strtotime(date("Y-m-d H:i:s")) > $this->add_hours_to_date($objVideos->fecha_transmision . ' ' . $objVideos->horario_transmision_inicio, $objCanales->ibope)) {
                    $allowed = true;
                }
            }
            
            $objSeccion = $this->secciones_m->get_by(array('grupo_maestros_id' => $objVistaVideo->gm3));
            
           Log::erroLog("array datos de objSeccion");
           Log::erroLog($objSeccion);
                        
            if ($objSeccion->id != NULL) {
                $objDetalleSecciones = $this->detalle_secciones_m->get_by(array("grupo_maestros_id" => $objVistaVideo->gm1, "secciones_id" => $objSeccion->id));

                if (count($objDetalleSecciones) == 0) {
                    $allowed = TRUE;
                } else {
                    $allowed = FALSE;
                }
            } else {
                $allowed = FALSE;
            }
                
            
            Log::erroLog("allowed: " . print_r($allowed));
            
                       
            if ($allowed) {
                
                $objImagenLista = $this->imagen_m->get_by(array("grupo_maestros_id" => $objVistaVideo->gm1, "tipo_imagen_id" => $this->config->item('imagen:small'), "estado" => $this->config->item('estado:publicado')));
                //registramos el detalle seccion de la seccion destacado
                $objBeanDetalleSeccion = new stdClass();
                $objBeanDetalleSeccion->id = NULL;
                $objBeanDetalleSeccion->secciones_id = $objSeccion->id;
                $objBeanDetalleSeccion->videos_id = NULL;
                $objBeanDetalleSeccion->grupo_maestros_id = $objVistaVideo->gm1;
                $objBeanDetalleSeccion->canales_id = NULL;
                $objBeanDetalleSeccion->imagenes_id = $objImagenLista->id;
                $objBeanDetalleSeccion->peso = $this->obtenerPeso($objSeccion->id);
                $objBeanDetalleSeccion->descripcion_item = '';
                $objBeanDetalleSeccion->estado = $this->config->item('estado:publicado');
                $objBeanDetalleSeccion->fecha_registro = date("Y-m-d H:i:s");
                $objBeanDetalleSeccion->usuario_registro = $user_id;
                $objBeanDetalleSeccion->estado_migracion = $this->config->item('migracion:nuevo');
                $objBeanDetalleSeccion->fecha_migracion = '0000-00-00 00:00:00';
                $objBeanDetalleSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                $this->detalle_secciones_m->save($objBeanDetalleSeccion);

                Log::erroLog("INSERTADO DETALLE SECCION");
                $this->secciones_m->update($objSeccion->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                Log::erroLog("SECCION ACTUALIZADA");

                $this->sort_detalle_seccion($objVistaVideo->gm3, $objSeccion->id);
            }
        }
        
        //preguntamos si el programa cuenta con una imagen XL o S para estar apto a publicar
        $objImagenXLPrograma = $this->imagen_m->where_in('tipo_imagen_id', array($this->config->item('imagen:extralarge')))->get_by(array("estado" => $this->config->item('estado:publicado'), "grupo_maestros_id" => $objVistaVideo->gm3));
        $objImagenSPrograma = $this->imagen_m->where_in('tipo_imagen_id', array($this->config->item('imagen:small')))->get_by(array("estado" => $this->config->item('estado:publicado'), "grupo_maestros_id" => $objVistaVideo->gm3));
        //verificamos si tiene portada del programa
        $objPortadaPrograma = $this->portada_m->get_by(array("origen_id" => $objVistaVideo->gm3, "tipo_portadas_id" => $this->config->item('portada:programa')));
        if (count($objPortadaPrograma) > 0) {
            Log::erroLog("count(objPortadaPrograma) > 0");
            //validamos que esté en estado publicado
            if ($objPortadaPrograma->estado != $this->config->item('estado:publicado')) {
                Log::erroLog("objPortadaPrograma->estado != this->config->item('estado:publicado')");
                //verificamos que el programa tenga las imagenes S y XL
                if (count($objImagenXLPrograma) > 0 && count($objImagenSPrograma) > 0) {
                    Log::erroLog("count(objImagenXLPrograma) > 0 && count(objImagenSPrograma) > 0");
                    //verificamos si el programa está registrado en el destacado de la portada programa
                    $objSeccionDestacadoPrograma = $this->secciones_m->get_by(array("portadas_id" => $objPortadaPrograma->id, "tipo_secciones_id" => $this->config->item('seccion:destacado')));
                    if (count($objSeccionDestacadoPrograma) > 0) {
                        Log::erroLog("count(objSeccionDestacadoPrograma) > 0");
                        //obtenemos el detalle seccion del destacado de un programa
                        $detalle_seccion = $this->detalle_secciones_m->get_by(array("secciones_id" => $objSeccionDestacadoPrograma->id, "grupo_maestros_id" => $objVistaVideo->gm3));
                        if (count($detalle_seccion) > 0) {
                            Log::erroLog("count(detalle_seccion) > 0 true");
                            //actualizamos el detalle seccion destacado
                            $this->detalle_secciones_m->update($detalle_seccion->id, array("imagenes_id" => $objImagenXLPrograma->id, "estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                            //activamos la sección destacado
                            $this->secciones_m->update($objSeccionDestacadoPrograma->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                        } else {
                            Log::erroLog("count(detalle_seccion) > 0 false");
                            //registramos el detalle seccion de la seccion destacado
                            $objBeanDetalleSeccion = new stdClass();
                            $objBeanDetalleSeccion->id = NULL;
                            $objBeanDetalleSeccion->secciones_id = $objSeccionDestacadoPrograma->id;
                            $objBeanDetalleSeccion->videos_id = NULL;
                            $objBeanDetalleSeccion->grupo_maestros_id = $objVistaVideo->gm3;
                            $objBeanDetalleSeccion->canales_id = NULL;
                            $objBeanDetalleSeccion->imagenes_id = $objImagenXLPrograma->id;
                            $objBeanDetalleSeccion->peso = $this->obtenerPeso($objSeccionDestacadoPrograma->id);
                            $objBeanDetalleSeccion->descripcion_item = '';
                            $objBeanDetalleSeccion->estado = $this->config->item('estado:publicado');
                            $objBeanDetalleSeccion->fecha_registro = date("Y-m-d H:i:s");
                            $objBeanDetalleSeccion->usuario_registro = $user_id;
                            $objBeanDetalleSeccion->estado_migracion = $this->config->item('migracion:nuevo');
                            $objBeanDetalleSeccion->fecha_migracion = '0000-00-00 00:00:00';
                            $objBeanDetalleSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                            $this->detalle_secciones_m->save($objBeanDetalleSeccion);
                            //actualizamos la seccion
                            $this->secciones_m->update($objSeccionDestacadoPrograma->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                        }
                    }
                }
            }
            if (count($objImagenXLPrograma) > 0 && count($objImagenSPrograma) > 0) {
                Log::erroLog("count(objImagenXLPrograma) > 0 && count(objImagenSPrograma) > 0");
                //verificamos la imagen de la lista
                $objImagenLista = $this->imagen_m->get_by(array("grupo_maestros_id" => $objVistaVideo->gm1, "tipo_imagen_id" => $this->config->item('imagen:small'), "estado" => $this->config->item('estado:publicado')));
                if (count($objImagenLista) > 0) {
                    Log::erroLog("count(objImagenLista) > 0");
                    //obtenemos el objecto seccion coleccion de la portada programa
                    $objSeccionColeccionPrograma = $this->secciones_m->get_by(array("portadas_id" => $objPortadaPrograma->id, "tipo_secciones_id" => $this->config->item('seccion:coleccion'), "grupo_maestros_id" => $objVistaVideo->gm2));
                    if (count($objSeccionColeccionPrograma) > 0) {
                        Log::erroLog("count(objSeccionColeccionPrograma) > 0");
                        //obtenemos el detalle seccion coleccion de la portada programa
                        $detalle_seccion = $this->detalle_secciones_m->get_by(array("secciones_id" => $objSeccionColeccionPrograma->id, "grupo_maestros_id" => $objVistaVideo->gm1));
                        if (count($detalle_seccion) > 0) {
                            Log::erroLog("count(detalle_seccion) > 0 true");
                            //actualizamos el detalle sección
                            $this->detalle_secciones_m->update($detalle_seccion->id, array("imagenes_id" => $objImagenLista->id, "estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                            //activamos la sección destacado
                            $this->secciones_m->update($objSeccionColeccionPrograma->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                        } else {
                            Log::erroLog("count(detalle_seccion) > 0 false");
                            //registramos el video en esta sección
                            $objBeanDetalleSeccion = new stdClass();
                            $objBeanDetalleSeccion->id = NULL;
                            $objBeanDetalleSeccion->secciones_id = $objSeccionColeccionPrograma->id;
                            $objBeanDetalleSeccion->videos_id = NULL;
                            $objBeanDetalleSeccion->grupo_maestros_id = $objVistaVideo->gm1;
                            $objBeanDetalleSeccion->canales_id = NULL;
                            $objBeanDetalleSeccion->imagenes_id = $objImagenLista->id;
                            $objBeanDetalleSeccion->peso = $this->obtenerPeso($objSeccionColeccionPrograma->id);
                            $objBeanDetalleSeccion->descripcion_item = '';
                            $objBeanDetalleSeccion->estado = $this->config->item('estado:publicado');
                            $objBeanDetalleSeccion->fecha_registro = date("Y-m-d H:i:s");
                            $objBeanDetalleSeccion->usuario_registro = $user_id;
                            $objBeanDetalleSeccion->estado_migracion = $this->config->item('migracion:nuevo');
                            $objBeanDetalleSeccion->fecha_migracion = '0000-00-00 00:00:00';
                            $objBeanDetalleSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                            $this->detalle_secciones_m->save($objBeanDetalleSeccion);
                            //actualizamos la seccion
                            $this->secciones_m->update($objSeccionColeccionPrograma->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                        }
                    }
                }
                //publicamos el maestro programa
                $this->grupo_maestro_m->update($objVistaVideo->gm3, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado'), "estado_migracion_sphinx" => $this->config->item('sphinx:actualizar')));
                //publicamos el maestro coleccion
                $this->grupo_maestro_m->update($objVistaVideo->gm2, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado'), "estado_migracion_sphinx" => $this->config->item('sphinx:actualizar')));
                //publicamos el maestro lista
                $this->grupo_maestro_m->update($objVistaVideo->gm1, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado'), "estado_migracion_sphinx" => $this->config->item('sphinx:actualizar')));
                //publicamos la portada del programa
                $this->portada_m->update($objPortadaPrograma->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                //existe el programa en la sección programas de la portada del canal
                //primero obtenemos la portada del canal
                $objPortadaCanal = $this->portada_m->get_by(array("tipo_portadas_id" => $this->config->item('portada:canal'), "origen_id" => $objVistaVideo->canales_id, "canales_id" => $objVistaVideo->canales_id));
                if (count($objPortadaCanal) > 0) {
                    Log::erroLog("count(objPortadaCanal) > 0");
                    //verificamos que el programa tenga una imagen small por lo menos
                    if (count($objImagenSPrograma) > 0) {
                        Log::erroLog("count(objImagenSPrograma) > 0");
                        //obtenemos la seccion programa de la portada del canal
                        $objSeccionProgramaCanal = $this->secciones_m->get_by(array("portadas_id" => $objPortadaCanal->id, "tipo_secciones_id" => $this->config->item('seccion:programa')));
                        if (count($objSeccionProgramaCanal) > 0) {
                            Log::erroLog("count(objSeccionProgramaCanal) > 0");
                            $detalle_seccion_programa = $this->detalle_secciones_m->get_by(array("secciones_id" => $objSeccionProgramaCanal->id, "grupo_maestros_id" => $objVistaVideo->gm3));
                            if (count($detalle_seccion_programa) > 0) {
                                Log::erroLog("count(detalle_seccion_programa) > 0 true");
                                //actualizamos el detalle sección
                                $this->detalle_secciones_m->update($detalle_seccion_programa->id, array("estado" => $this->config->item('estado:publicado'), "imagenes_id" => $objImagenSPrograma->id, "estado_migracion" => $this->config->item('migracion:actualizado')));
                                //activamos la sección programa de la portada canal
                                $this->secciones_m->update($objSeccionProgramaCanal->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                            } else {
                                Log::erroLog("count(detalle_seccion_programa) > 0 false");
                                //registramos el programa al detalle sección
                                $objBeanDetalleSeccion = new stdClass();
                                $objBeanDetalleSeccion->id = NULL;
                                $objBeanDetalleSeccion->secciones_id = $objSeccionProgramaCanal->id;
                                $objBeanDetalleSeccion->videos_id = NULL;
                                $objBeanDetalleSeccion->grupo_maestros_id = $objVistaVideo->gm3;
                                $objBeanDetalleSeccion->canales_id = NULL;
                                $objBeanDetalleSeccion->imagenes_id = $objImagenSPrograma->id;
                                $objBeanDetalleSeccion->peso = $this->obtenerPeso($objSeccionProgramaCanal->id);
                                $objBeanDetalleSeccion->descripcion_item = '';
                                $objBeanDetalleSeccion->estado = $this->config->item('estado:publicado');
                                $objBeanDetalleSeccion->fecha_registro = date("Y-m-d H:i:s");
                                $objBeanDetalleSeccion->usuario_registro = $user_id;
                                $objBeanDetalleSeccion->estado_migracion = $this->config->item('migracion:nuevo');
                                $objBeanDetalleSeccion->fecha_migracion = '0000-00-00 00:00:00';
                                $objBeanDetalleSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                                $this->detalle_secciones_m->save($objBeanDetalleSeccion);
                                //actualizamos la seccion
                                $this->secciones_m->update($objSeccionProgramaCanal->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                            }
                        }
                    }
                }
            }
        }
        Log::erroLog("fin - publicar_video_lista_coleccion_programa_canal ref: " . $ref . ' - ' . date("Y-m-d H:i:s"));
    }

    /**
     * Método para publicar un video relacionado a  una lista, coleccion y canal
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param object $objVistaVideo
     * @param string $ref
     */
    private function publicar_lista_coleccion_canal($objVistaVideo, $ref) {
        //cargamos el config si el origen de llamada es diferente al cms
        if ($ref != 'cms' && $ref != 'importacion') {
            //$this->config->load('videos/uploads');
            $user_id = 1; //usuario administrador
        } else {
            $user_id = (int) $this->session->userdata('user_id');
        }
        //verificamos si la lista tiene imagen, la colección no importa mucho en este caso solo la imagen del maestro lista
        $objImagenLista = $this->imagen_m->get_by(array("grupo_maestros_id" => $objVistaVideo->gm1, "tipo_imagen_id" => $this->config->item('imagen:small'), "estado" => $this->config->item('estado:publicado')));
        if (count($objImagenLista) > 0) {
            //publicamos el maestro lista y el maestro coleccion
            $this->grupo_maestro_m->update($objVistaVideo->gm1, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado'), "estado_migracion_sphinx" => $this->config->item('sphinx:actualizar')));
            $this->grupo_maestro_m->update($objVistaVideo->gm2, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado'), "estado_migracion_sphinx" => $this->config->item('sphinx:actualizar')));
            //obtenemos la portada del canal 
            $objPortadaCanal = $this->portada_m->get_by(array("canales_id" => $objVistaVideo->canales_id, "origen_id" => $objVistaVideo->canales_id, "tipo_portadas_id" => $this->config->item('portada:canal')));
            if (count($objPortadaCanal) > 0) {
                //verificamos si la portada esta publicada
                //if ($objPortadaCanal->estado == $this->config->item('estado:publicado')) {
                    //buscamos la sección coleccion del canal para el maestro coleccion que fue asignado
                    $objSeccionColeccionCanal = $this->secciones_m->get_by(array("portadas_id" => $objPortadaCanal->id, "tipo_secciones_id" => $this->config->item('seccion:coleccion'), "grupo_maestros_id" => $objVistaVideo->gm2));
                    if (count($objSeccionColeccionCanal) > 0) {
                        //verificamos que no exista la lista registrado en sus detallles
                        $coleccion_detalle_seccion = $this->detalle_secciones_m->get_by(array("secciones_id" => $objSeccionColeccionCanal->id, "grupo_maestros_id" => $objVistaVideo->gm1));
                        if (count($coleccion_detalle_seccion) > 0) {
                            //actualizamos el detalle seccion
                            $this->detalle_secciones_m->update($coleccion_detalle_seccion->id, array("estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                            //actualizamos el estado de la sección
                            $this->secciones_m->update($objSeccionColeccionCanal->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                        } else {
                            //registramos el maestro en el detalle de la sección
                            $objBeanDetalleSeccion = new stdClass();
                            $objBeanDetalleSeccion->id = NULL;
                            $objBeanDetalleSeccion->secciones_id = $objSeccionColeccionCanal->id;
                            $objBeanDetalleSeccion->videos_id = NULL;
                            $objBeanDetalleSeccion->grupo_maestros_id = $objVistaVideo->gm1;
                            $objBeanDetalleSeccion->canales_id = NULL;
                            $objBeanDetalleSeccion->imagenes_id = $objImagenLista->id;
                            $objBeanDetalleSeccion->peso = $this->obtenerPeso($objSeccionColeccionCanal->id);
                            $objBeanDetalleSeccion->descripcion_item = '';
                            $objBeanDetalleSeccion->estado = $this->config->item('estado:publicado');
                            $objBeanDetalleSeccion->fecha_registro = date("Y-m-d H:i:s");
                            $objBeanDetalleSeccion->usuario_registro = $user_id;
                            $objBeanDetalleSeccion->estado_migracion = $this->config->item('migracion:nuevo');
                            $objBeanDetalleSeccion->fecha_migracion = '0000-00-00 00:00:00';
                            $objBeanDetalleSeccion->fecha_migracion_actualizacion = '0000-00-00 00:00:00';
                            $this->detalle_secciones_m->save($objBeanDetalleSeccion);
                            //actualizamos la seccion
                            $this->secciones_m->update($objSeccionColeccionCanal->id, array("fecha_actualizacion"=>date("Y-m-d H:i:s"),"estado" => $this->config->item('estado:publicado'), "estado_migracion" => $this->config->item('migracion:actualizado')));
                        }
                    }
                //}
            }
        }
    }

    /**
     * Método para obtener los primeros pesos disponibles
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $seccion_id
     * @return int
     */
    private function obtenerPeso($seccion_id) {
        $returnValue = 1;
        $lista_detalles = $this->detalle_secciones_m->order_by('peso', 'ASC')->get_many_by(array("secciones_id" => $seccion_id));
        if (count($lista_detalles) > 0) {
            $peso = 2;
            foreach ($lista_detalles as $puntero => $objDetalle) {
                $this->detalle_secciones_m->update($objDetalle->id, array("peso" => $peso, "estado_migracion" => $this->config->item('migracion:actualizado')));
                $peso++;
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

    /**
     * Sorts an array but last parameter first
     * 
     * @param string $elem_1
     * @param string $elem_2
     * @param string $but
     * @return int
     */
    function sortArrayBut($elem_1, $elem_2, $but) {
        if($elem_1 == $elem_2) return 0;

        if($elem_2 == $but) return 1;

        if(($elem_1 > $elem_2) || ($elem_1 == $but)) {
            return 1;
        } else {
            return -1;
        }
    }

    /**
     * Sorts a detalle secciones
     * 
     * @param int $programa_id
     * @param int $secciones_id
     */
    private function sort_detalle_seccion($programa_id, $secciones_id)
    {
        $objDetalleSecciones = $this->detalle_secciones_m->order_by('peso', 'ASC')->get_many_by(array("secciones_id" => $secciones_id));
        if (count($objDetalleSecciones) > 0) {
            $peso = 2;
            foreach ($objDetalleSecciones as $puntero => $objDetalleSeccion) {
                if ($objDetalleSeccion->grupo_maestros_id == $programa_id) {
                    $this->detalle_secciones_m->update($objDetalleSeccion->id, array("peso" => 1, "estado_migracion" => $this->config->item('migracion:actualizado')));
                } else {
                    $this->detalle_secciones_m->update($objDetalleSeccion->id, array("peso" => $peso, "estado_migracion" => $this->config->item('migracion:actualizado')));
                    $peso++;
                }
            }
        }
    }
    
    /**
     * Adds hours to date
     * 
     * @param datetime $originalDate Datetime from database
     * @param integer $hours Amount of hours to add
     * @return type
     */
    function add_hours_to_date($originalDate, $hours){
        return ($hours * 3600) + strtotime($originalDate);
    }
}