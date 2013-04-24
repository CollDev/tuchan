<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Modelo categoria
 *
 * metodos para obtener data de la tabla categorias
 *
 * @author		Johnny Huamani <jhuamani@idigital.pe>
 * @author		PyroCMS Dev Team
 * @package		Modules\videos\Models
 */
class Imagen_m extends MY_Model {

    /**
     *  nombre de la tabla
     * @var string 
     */
    protected $_table = 'default_cms_imagenes';

    /**
     * listado de categorias en orden ascendente alfabeticamente
     * @param array $where
     * @param string $order
     * @return array
     */
    public function getImagen($where = array(), $order = NULL) {
        $this->db->select("*");
        $this->db->from($this->_table);
        $this->db->where($where);
        if ($order != NULL) {
            $this->db->order_by($order);
        }
        $resultValue = $this->db->get()->result();
        return $resultValue;
    }

    public function vd($var) {
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }

    /**
     * 
     * @param type $where
     * @param type $order
     * @return type
     */
    public function getImagenDropDown($where = array(), $order = NULL) {
        $returnValue = array();
        $arrayData = $this->getImagen($where, $order);
        if (count($arrayData) > 0) {
            foreach ($arrayData as $index => $objTipo) {
                $returnValue[$objTipo->id] = $objTipo->nombre;
            }
        }
        $returnValue[0] = lang('videos:select_category');
        return $returnValue;
    }

    public function saveImage($objBeanImage) {
        $objBeanImage->id = parent::insert(array(
                    'canales_id' => $objBeanImage->canales_id,
                    'grupo_maestros_id' => $objBeanImage->grupo_maestros_id,
                    'videos_id' => $objBeanImage->videos_id,
                    'imagen' => $objBeanImage->imagen,
                    'tipo_imagen_id' => $objBeanImage->tipo_imagen_id,
                    'estado' => $objBeanImage->estado,
                    'fecha_registro' => $objBeanImage->fecha_registro,
                    'usuario_registro' => $objBeanImage->usuario_registro,
                    'fecha_actualizacion' => $objBeanImage->fecha_actualizacion,
                    'usuario_actualizacion' => $objBeanImage->usuario_actualizacion,
                    'estado_migracion' => $objBeanImage->estado_migracion,
                    'fecha_migracion' => $objBeanImage->fecha_migracion,
                    'fecha_migracion_actualizacion' => $objBeanImage->fecha_migracion_actualizacion,
                    'imagen_padre' => $objBeanImage->imagen_padre,
                    'procedencia' => $objBeanImage->procedencia
        ));
        return $objBeanImage;
    }

    public function uploadNameImage($imagen_id, $path_single_element) {
        return parent::update($imagen_id, array('imagen' => $path_single_element));
    }

    public function activarImagen($objBeanImagen) {
        return parent::update($objBeanImagen->id, array('estado' => $objBeanImagen->estado));
    }

    public function tieneHijos($imagen_id) {
        $returnValue = false;
        $listaImagenes = $this->getImagen(array("imagen_padre" => $imagen_id));
        if (count($listaImagenes) > 0) {
            $returnValue = true;
        }
        return $returnValue;
    }

    public function desactivarImagenes($imagen_id, $video_id) {
        $query = "UPDATE " . $this->_table . " SET estado = '0' WHERE videos_id =" . $video_id . " AND tipo_imagen_id IN (1,2,3,4)";
        $result = $this->db->query($query);
        return $result;
    }

    public function desactivarImagenesMaestro($maestro_id) {
        $query = "UPDATE " . $this->_table . " SET estado = '0' WHERE grupo_maestros_id =" . $maestro_id . " AND tipo_imagen_id IN (1,2,3,4)";
        $result = $this->db->query($query);
        return $result;
    }

    public function desabilitarImagenes($maestro_id, $tipo_imagen_id, $tipo_origen) {
        if ($tipo_origen == 'maestro') {
            $query = "UPDATE " . $this->_table . " SET estado = '0' WHERE grupo_maestros_id =" . $maestro_id . " AND tipo_imagen_id IN (" . $tipo_imagen_id . ")";
        } else {
            if ($tipo_origen = 'video') {
                $query = "UPDATE " . $this->_table . " SET estado = '0' WHERE videos_id =" . $maestro_id . " AND tipo_imagen_id IN (" . $tipo_imagen_id . ")";
            } else {
                if ($tipo_origen = 'canal') {
                    $query = "UPDATE " . $this->_table . " SET estado = '0' WHERE canales_id =" . $maestro_id . " AND tipo_imagen_id IN (" . $tipo_imagen_id . ")";
                }
            }
        }
        $result = $this->db->query($query);
        return $result;
    }

    public function desactivasImagenMaestroTipo($maestro_id, $tipo_imagen, $tipo = 'maestro') {
        if ($tipo == 'maestro') {
            $query = "UPDATE " . $this->_table . " SET estado = '0' WHERE grupo_maestros_id =" . $maestro_id . " AND tipo_imagen_id IN (" . $tipo_imagen . ")";
        } else {
            $query = "UPDATE " . $this->_table . " SET estado = '0' WHERE videos_id =" . $maestro_id . " AND tipo_imagen_id IN (" . $tipo_imagen . ")";
        }
        $result = $this->db->query($query);
        return $result;
    }

}