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
class Tags_m extends MY_Model 
{

    protected $_table = 'default_cms_tags';

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
    /*public function get_many_by($where = array()) 
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
    */
    
    
    /**
     * Inserta un nuevo tag en la base de datos
     * 
     * @param array $input La data a insertar
     * @return string
     */
    public function insert($input = array())
<<<<<<< HEAD
    {
        //print_r($input);
        //echo 'tematicas: ' . $input['tematicas'] . '<br/>';
        //echo 'personajes: ' . $input['personajes'];
        
        $arr_tematicas = explode(',', $input['tematicas']);
        $arr_personajes = explode(',', $input['personajes']);
        
        // Tags tematicas OJO VERIFICAR LOS VALORES
        foreach ($arr_tematicas as $tematicas) {
            parent::insert(array(
                    'tipo_tags_id' => '1',
                    'nombre' => $input['nombre'],
                    'descripcion' => $input['descripcion'],
                    'alias'  => url_title(strtolower(convert_accented_characters($input['nombre']))),
                    'estado' => '1',
                    'usuario_registro' => (int) $this->session->userdata('user_id'),
                    'fecha_registro' => date('Y-m-d H:i:s')
            ));
        }
        
        // Tags personajes
        foreach ($arr_personajes as $personajes) {
            parent::insert(array(
                    'title' => $input['title'],
                    'slug'  => url_title(strtolower(convert_accented_characters($input['title'])))
            ));
        }
            
       return $input['title'];
=======
    {        
        $arr_tematicas = explode(',', $input['tematicas']);
        $arr_personajes = explode(',', $input['personajes']);
        
        // Tags tematicas
        foreach ($arr_tematicas as $indice => $valor) {
            
            $valor = trim($valor);
            $tag_ids[] = parent::insert(array(
                                'tipo_tags_id' => '1', // Tags tematicos
                                'nombre' => $valor,
                                'descripcion' => $valor,
                                'alias'  => url_title(strtolower(convert_accented_characters($valor))),
                                'estado' => '1',
                                'usuario_registro' => (int) $this->session->userdata('user_id'),
                                'fecha_registro' => date('Y-m-d H:i:s')
                        ));
        }
        
        // Tags personajes
        foreach ($arr_personajes as $indice => $personaje) {
        
            $personaje = trim($personaje);
            $tag_ids[] = parent::insert(array(
                            'tipo_tags_id' => '2', // Tags personajes
                            'nombre' => $personaje,
                            'descripcion' => $personaje,
                            'alias'  => url_title(strtolower(convert_accented_characters($personaje))),
                            'estado' => '1',
                            'usuario_registro' => (int) $this->session->userdata('user_id'),
                            'fecha_registro' => date('Y-m-d H:i:s')
                    ));
        }
            
       return $tag_ids;
>>>>>>> 1201ee8a8121b87db20ea4af381bef22058262ef
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