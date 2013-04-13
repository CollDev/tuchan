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
class Imagenes_m extends MY_Model 
{

    protected $_table = 'default_cms_imagenes';

    /**
     * Sections
     *
     * Gets all the sections (modules) from the settings table.
     *
     * @access	public
     * @return	array
     */
    public function sections() 
    {
        $sections = $this->select('module')
                ->distinct()
                ->where('module != ""')
                ->get_all();

        $result = array();

        foreach ($sections as $section) {
            $result[] = $section->module;
        }

        return $result;
    }

    /**
     * Obtiene el logo del canal según id
     * @param type $where
     * @param type $order
     * @return type
     */      
    public function getLogo($where = array()) 
    {
        $this->db->select("*");
        $this->db->from($this->_table);
        $this->db->where($where);    
        $resultValue = $this->db->get()->result();
        return $resultValue;
    }
    
}

/* End of file settings_m.php */