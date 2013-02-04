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
            $where = array('user_id' => $where);
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
        
        $this->db->select("v.*, c.nombre as canal, cat.nombre as categoria, tv.nombre as tipo_video");
        $this->db->from($this->_table . ' v');
        $this->db->join('default_cms_canales c', 'c.id = v.canales_id');
        $this->db->join('default_cms_categorias cat', 'cat.id = v.categorias_id');
        $this->db->join('default_cms_tipo_videos tv', 'tv.id = v.tipo_videos_id');
        $this->db->where($where);

        $result = $this->db->get()->result();

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
}

/* End of file usuario_rol_canales_m.php */