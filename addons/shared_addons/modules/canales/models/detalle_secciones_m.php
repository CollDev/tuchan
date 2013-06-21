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
class Detalle_secciones_m extends MY_Model {

    /**
     *  nombre de la tabla
     * @var string 
     */
    protected $_table = 'default_cms_detalle_secciones';

    /**
     * Método para volcar variables
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param undefined $var
     */
    public function vd($var) {
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }

    /**
     * Método para registrar en la Base de datos un detalle seccion
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param object $objBeanSeccion
     * @return object
     */
    public function save($objBeanSeccion) {
        $objBeanSeccion->id = parent::insert(array(
                    'secciones_id' => $objBeanSeccion->secciones_id,
                    //'reglas_id'  => $objBeanSeccion->reglas_id,
                    'videos_id' => $objBeanSeccion->videos_id,
                    'grupo_maestros_id' => $objBeanSeccion->grupo_maestros_id,
                    'canales_id' => $objBeanSeccion->canales_id,
                    //'categorias_id' => $objBeanSeccion->categorias_id,
                    //'tags_id' => $objBeanSeccion->tags_id,
                    'imagenes_id' => $objBeanSeccion->imagenes_id,
                    'peso' => $objBeanSeccion->peso,
                    'descripcion_item' => $objBeanSeccion->descripcion_item,
                    //'templates_id' => $objBeanSeccion->templates_id,
                    'estado' => $objBeanSeccion->estado,
                    'fecha_registro' => $objBeanSeccion->fecha_registro,
                    'usuario_registro' => $objBeanSeccion->usuario_registro,
                    'estado_migracion' => $objBeanSeccion->estado_migracion,
                    'fecha_migracion' => $objBeanSeccion->fecha_migracion,
                    'fecha_migracion_actualizacion' => $objBeanSeccion->fecha_migracion_actualizacion
        ));
        return $objBeanSeccion;
    }

    /**
     * Método para obtener una lista de items de detalle seccion x sus ID's
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param array $array_index
     * @return array
     */
    public function getListaOriginal($array_index) {
        $sql = 'SELECT * FROM ' . $this->_table . ' WHERE id IN(' . implode(',', $array_index) . ') ORDER BY peso ASC';
        $result = $this->db->query($sql)->result();
        return $result;
    }

    /**
     * Método que especializa al update para generar disparadores por este evento
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $id
     * @param array $array
     */
    public function update($id, $array) {
        parent::update($id, $array);
        //$this->procesos_lib->actualizarDetalleSecciones();
    }

    /**
     * Método para obtener el objeto detalle seccion del siguiente item mayor
     * @author Johnny Huamani <johnny1402@gmail.com>
     * @param int $seccion_id
     * @param int $peso
     * @return object
     */
    public function obtener_detalle_seccion_mayor($seccion_id, $peso) {
        $sql = 'SELECT * FROM ' . $this->_table . ' WHERE secciones_id = '.$seccion_id.' AND estado = '.$this->config->item('estado:publicado').' AND peso > '.$peso.' ORDER BY peso ASC LIMIT 1';
        $result = $this->db->query($sql)->result();
        return $result;        
    }
    public function obtener_detalle_seccion_menor($seccion_id, $peso) {
        $sql = 'SELECT * FROM ' . $this->_table . ' WHERE secciones_id = '.$seccion_id.' AND estado = '.$this->config->item('estado:publicado').' AND peso < '.$peso.' ORDER BY peso DESC LIMIT 1';
        $result = $this->db->query($sql)->result();
        return $result;        
    }

    public function limpiar()
    {
        $sql = 'DELETE FROM ' . $this->_table;
        $this->db->query($sql);
    }
}