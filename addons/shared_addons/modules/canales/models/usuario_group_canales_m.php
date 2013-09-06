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
class Usuario_group_canales_m extends MY_Model 
{

    protected $_table = 'default_cms_usuario_group_canales';

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
            $where = array('user_id' => $where, 'estado'=>'1');
        }

        $this->db->select("*");
        $this->db->from($this->_table);
        $this->db->where($where);
        $result = $this->db->get()->result();

        return $result;
    }

    /**
     * Obtiene los canales que pertenecen al usuario logueado
     * @param array $where
     * @return object
     */
    public function get_canales_by_usuario($where = array()) 
    {
        if (!is_array($where)) {
            $where = array('user_id' => $where);
        }

        $this->db->select("urc.user_id, urc.group_id, urc.canal_id, c.nombre, urc.predeterminado,urc.estado");
        $this->db->from($this->_table . ' urc');
        $this->db->join('default_cms_canales c', 'c.id = urc.canal_id');
        $this->db->where($where);

        $result = $this->db->get()->result();

        return $result;
    }
    
    /**
     * Obtiene el canal predeterminado del usuario logueado
     * @param array $where
     * @return object
     */
    public function get_canal_default_by_usuario() 
    {        
        $where = array('user_id' => $this->session->userdata('user_id'), 'estado' => '1', 'predeterminado >' => '0');        

        $query = $this->db->get_where($this->_table, $where);

        if ($query->num_rows() > 0)     {
            $row = $query->row();
            $predeterminado = $row->predeterminado;
        }

        return $predeterminado;
    }
    
    /**
     * Obtiene los canales activos que pertenecen al usuario logueado
     * @param array $where
     * @return object
     */
    public function get_canales_activos_by_usuario($where = array()) 
    {
        if (!is_array($where)) {
            $where = array('user_id' => $where, 'urc.estado' => '1');
        }

        $this->db->select("urc.user_id, urc.group_id, urc.canal_id, c.nombre, urc.predeterminado,urc.estado");
        $this->db->from($this->_table . ' urc');
        $this->db->join('default_cms_canales c', 'c.id = urc.canal_id');
        $this->db->where($where);
        $result = $this->db->get()->result();

        return $result;
    }
    
    /**
     * Añade canales usuarios
     * @param array $params
     * @return int
     */
    public function insert($params)
    {                        
        $query = parent::insert(array(                    
                    'canal_id' => $params['canal_id'],
                    'user_id' => $params['user_id'],
                    'group_id' => $params['group_id'],
                    'estado' => $params['estado'],                    
                    'fecha_registro' => $params['fecha_registro'],
                    'usuario_registro' => $params['usuario_registro'],
                    'predeterminado' => $params['predeterminado']
        ));

        return $query;
    }

    /**
     * Update canales usuarios
     * @access	public
     * @param	string	$canal_id
     * @param	string	$user_id
     * @param	array	$params
     * @return	bool
     */
    public function update($canal_id = '', $user_id = '', $params = array()) 
    {
        $query = $this->db->update($this->_table, $params, array('canal_id' => $canal_id, 'user_id' => $user_id));
        return $query;
    }

    /**
     * Update canal predeterminado
     * @param string $user_id
     * @return boolean
     */
    public function update_predeterminado($params = array(), $canal_id = '', $user_id = '') 
    {
        // Actualiza todos los predeterminados a 0
        $this->db->update($this->_table, array('predeterminado' => 0), array('user_id' => $user_id));

        // Actualiza el predeterminado seleccionado
        $query2 = $this->db->update($this->_table, $params, array('user_id' => $user_id, 'canal_id' => $canal_id));

        return $query2;
    }

    public function unset_predeterminado($user_id)
    {
        $sql = "UPDATE " . $this->_table . " SET `predeterminado` = '0' WHERE `user_id` = '" . $user_id . "';";
        $result = $this->db->query($sql);
        
        return $result;
    }
}

/* End of file usuario_rol_canales_m.php */