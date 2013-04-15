<<<<<<< HEAD
<?php defined('BASEPATH') or exit('No direct script access allowed');
=======
<?php

defined('BASEPATH') or exit('No direct script access allowed');
>>>>>>> 1201ee8a8121b87db20ea4af381bef22058262ef

/**
 * PyroCMS Settings Model
 *
 * Allows for an easy interface for site settings
 *
 * @author		Dan Horrigan <dan@dhorrigan.com>
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Modules\Settings\Models
 */
<<<<<<< HEAD

class Canales_m extends MY_Model 
{
    protected $_table = 'default_cms_canales';

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
		if ( ! is_array($where))
		{
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
		if ( ! is_array($where))
		{
			$where = array('module' => $where);
		}

		return $this
			//->select('*, IF(`value` = "", `default`, `value`) as `value`', FALSE)
                        ->select('*', 'FALSE')
			->where($where)
			->order_by('`nombre`', 'ASC')
			->get_all();
	}

	/**
	 * Update
	 *
	 * Updates a setting for a given $slug.
	 *
	 * @access	public
	 * @param	string	$slug
	 * @param	array	$params
	 * @return	bool
	 */
	public function update($slug = '', $params = array())
	{
		return $this->db->update($this->_table, $params, array('slug' => $slug));
	}

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

		foreach ($sections as $section)
		{
			$result[] = $section->module;
		}

		return $result;
	}
        
        public function publish($id = 0)
	{
            return parent::update($id, array('estado' => '1'));
	}
=======
class Canales_m extends MY_Model {

    protected $_table = 'default_cms_canales';

    /**
     * Sections
     *
     * Gets all the sections (modules) from the settings table.
     *
     * @access	public
     * @return	array
     */
    public function sections() {
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

    public function publish($id = 0) {
        return parent::update($id, array('estado' => '1'));
    }

    public function getCanalDropDown($where, $order) {
        $returnValue = array();
        $arrayData = $this->getCanales($where, $order);
        if (count($arrayData) > 0) {
            foreach ($arrayData as $index => $objTipo) {
                $returnValue[$objTipo->id] = $objTipo->nombre;
            }
        }
        $returnValue[0] = lang('videos:select_channel');
        return $returnValue;
    }
    
    public function getCanales($where = array(), $order = NULL) {
        $this->db->select("*");
        $this->db->from($this->_table);
        $this->db->where($where);
        if ($order != NULL) {
            $this->db->order_by($order);
        }
        $resultValue = $this->db->get()->result();
        return $resultValue;
<<<<<<< HEAD
    }    
>>>>>>> 1201ee8a8121b87db20ea4af381bef22058262ef
=======
    }
    
    public function save($objBeanCanal){
        $objBeanCanal->id = parent::insert(array(
                'tipo_canales_id' => $objBeanCanal->tipo_canales_id,
                'alias'  => $objBeanCanal->alias,
                'nombre' => $objBeanCanal->nombre,
                'descripcion' => $objBeanCanal->descripcion,
                'apikey' => $objBeanCanal->apikey,
                'playerkey' => $objBeanCanal->playerkey,
                'id_mongo' => $objBeanCanal->id_mongo,
                'cantidad_suscriptores' => $objBeanCanal->cantidad_suscriptores,
                'estado' => $objBeanCanal->estado,
                'fecha_registro' => $objBeanCanal->fecha_registro,
                'usuario_registro' => $objBeanCanal->usuario_registro,
                'fecha_actualizacion' => $objBeanCanal->fecha_actualizacion,
                'usuario_actualizacion' => $objBeanCanal->usuario_actualizacion,
                'estado_migracion' => $objBeanCanal->estado_migracion,
                'fecha_migracion' => $objBeanCanal->fecha_migracion,
                'fecha_migracion_actualizacion' => $objBeanCanal->fecha_migracion_actualizacion
        ));
        return $objBeanCanal;           
    }
  
    public function actualizar($objBeanCanal){
        parent::update($objBeanCanal->id, array("tipo_canales_id"=>$objBeanCanal->tipo_canales_id,
            "alias"=>$objBeanCanal->alias,
            "nombre"=>$objBeanCanal->nombre,
            "descripcion"=>$objBeanCanal->descripcion,
            "apikey"=>$objBeanCanal->apikey,
            "playerkey"=>$objBeanCanal->playerkey,
            "fecha_actualizacion"=>$objBeanCanal->fecha_actualizacion,
            "usuario_actualizacion"=>$objBeanCanal->usuario_actualizacion));
    }
>>>>>>> c85177a8c0c2c3d827e64f8054e62c681aa45ef2

}

/* End of file settings_m.php */