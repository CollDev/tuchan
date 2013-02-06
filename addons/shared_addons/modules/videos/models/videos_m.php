<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroCMS Settings Model
 *
 * Allows for an easy interface for site settings
 *
 * @author		Dan Horrigan <dan@dhorrigan.com>
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Modules\Settings\Models
 */
class Videos_m extends MY_Model 
{

    protected $_table = 'default_cms_videos';

    /**
     * Get
     *
     * Gets a setting based on the $where param.  $where can be either a string
     * containing a slug name or an array of WHERE options.
     *
     * @access	public
     * @param	mixed	$where
     * @return	object
     */
    public function get($where) 
    {
        if (!is_array($where)) {
            $where = array('id' => $where);
        }

        return $this->db
                        ->select('*', 'FALSE')
                        ->where($where)
                        ->get($this->_table)
                        ->row();
    }

    /**
     * Get Many By
     *
     * Gets all settings based on the $where param.  $where can be either a string
     * containing a module name or an array of WHERE options.
     *
     * @access	public
     * @param	mixed	$where
     * @return	object
     */
    public function get_many_by($where = array()) 
    {
        if (!is_array($where)) {
            $where = array('user_id' => $where);
        }

        $this->db->select("*");
        $this->db->from($this->_table);
        $this->db->where($where);
        $result = $this->db->get()->result();

        return $result;
    }
    
    /**
     * Obtiene los videos que pertenecen al canal seleccionado
     * @param array $where
     * @return object
     */
    public function get_by_canal($where = array())
    {
        if (!is_array($where)) {
            $where = array('canales_id' => $where);
        }
        
        return $this->_obtener_query_videos($where['canales_id']);
        
    }
    
    /**
     * Query que retorna lista de videos
     * @param int $canal_id
     * @return array obj
     */
    private function _obtener_query_videos($canal_id)
    {        
        $query = "SELECT v.id, v.titulo, v.fuente, v.fecha_registro, v.fecha_publicacion, v.fecha_transmision, 
                    v.horario_transmision, v.estado,                            
                    c.nombre as canal, c.nombre as fuente, cat.nombre as categoria, tv.nombre as tipo_video, 
                    i.imagen, ";

        // tags tematicos
        $query .= "(SELECT group_concat(t.nombre)
                        FROM (`". $this->_table . "` v) 
                            JOIN `default_cms_video_tags` vt ON `vt`.`videos_id` = `v`.`id` 
                            JOIN `default_cms_tags` t ON `t`.`id` = `vt`.`tags_id` 
                            JOIN  default_cms_tipo_tags tt ON tt.id = t.tipo_tags_id
                        WHERE tt.id = 1
                   ) as tematico, ";

        // tags personajes
        $query .= "(SELECT group_concat(t.nombre)
                        FROM (`". $this->_table . "` v) 
                            JOIN `default_cms_video_tags` vt ON `vt`.`videos_id` = `v`.`id` 
                            JOIN `default_cms_tags` t ON `t`.`id` = `vt`.`tags_id` 
                            JOIN  default_cms_tipo_tags tt ON tt.id = t.tipo_tags_id
                        WHERE tt.id = 2
                    ) as personaje, ";
 
        // agrupar tag
	$query .= "group_concat(t.nombre) as tag ";
        
        $query .= "FROM (`". $this->_table . "` v) 
                    JOIN `default_cms_canales` c ON `c`.`id` = `v`.`canales_id` AND c.id = v.canales_id 
                    JOIN `default_cms_categorias` cat ON `cat`.`id` = `v`.`categorias_id` 
                    JOIN `default_cms_tipo_videos` tv ON `tv`.`id` = `v`.`tipo_videos_id` 
                    JOIN `default_cms_video_tags` vt ON `vt`.`videos_id` = `v`.`id` 
                    JOIN `default_cms_tags` t ON `t`.`id` = `vt`.`tags_id` 
                    JOIN  default_cms_tipo_tags tt ON tt.id = t.tipo_tags_id
                    JOIN  default_cms_imagenes i ON i.videos_id = v.id
                  WHERE `canales_id` = " . (int) $canal_id;
        
        $result = $this->db->query($query)->result();

        return $result;
    }

//
//	/**
//	 * Update
//	 *
//	 * Updates a setting for a given $slug.
//	 *
//	 * @access	public
//	 * @param	string	$slug
//	 * @param	array	$params
//	 * @return	bool
//	 */
//	public function update($slug = '', $params = array())
//	{
//		return $this->db->update($this->_table, $params, array('slug' => $slug));
//	}
//
//	/**
//	 * Sections
//	 *
//	 * Gets all the sections (modules) from the settings table.
//	 *
//	 * @access	public
//	 * @return	array
//	 */
//	public function sections()
//	{
//		$sections = $this->select('module')
//			->distinct()
//			->where('module != ""')
//			->get_all();
//
//		$result = array();
//
//		foreach ($sections as $section)
//		{
//			$result[] = $section->module;
//		}
//
//		return $result;
//	}
//        
//        public function publish($id = 0)
//	{
//            return parent::update($id, array('status' => '1'));
//	}
    
    /**
     * Publica video, cambia el estado a 2 y 
     * actualiza la fecha de publicacion
     * @param int $id
     * @return boolean
     */
    public function publish($id = 0)
    {
        return parent::update($id, array('estado' => '2', 'fecha_publicacion' => date('Y-m-d H:i:s')));
    }
}

/* End of file videos_m.php */