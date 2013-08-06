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
class Vw_maestro_video_m extends MY_Model {

    /**
     *  nombre de la tabla
     * @var string 
     */
    protected $_table = 'default_vw_maestros_videos';
    
    public function vd($var){
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }

    public function videos_x_programa($programa_id)
    {
        $query = "SELECT * FROM `" . $this->_table . "` WHERE `v` = 'v' AND `gm3` = '" . $programa_id . "' AND `gm1` IS NULL AND `gm2` IS NULL;";
        $result = $this->db->query($query)->result();
        
        return $result;
    }

    public function videos_x_coleccion($coleccion_id)
    {
        $query = "SELECT * FROM `" . $this->_table . "` WHERE `v` = 'v' AND `gm2` = '" . $coleccion_id . "';";
        $result = $this->db->query($query)->result();
        
        return $result;
    }

    public function videos_x_lista($lista_id)
    {
        $query = "SELECT * FROM `" . $this->_table . "` WHERE `v` = 'v' AND `gm1` = '" . $lista_id . "';";
        $result = $this->db->query($query)->result();
        
        return $result;
    }
}