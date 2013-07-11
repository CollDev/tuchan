<?php
/**
 * Autocompletar para las búsquedas
 * @author idigital
 */
class Version_mp extends CI_Model
{
    private $_tabla = 'version';
    
    function __construct()
    {
        parent::__construct();
    }
            
    /**
     * Obtener version       
     * @return array
     */
    public function get_version($tipo)
    {
        $result = $this->mongo_db->where(array('tipo' => $tipo))->get($this->_tabla);       
        return $result;
    }
    
    /**
     * Actualizar version     
     */
    public  function set_version($tipo,$valor){
        return $this->mongo_db->where(array('tipo' => $tipo))->set(array('version' => $valor))->update($this->_tabla);
    }
}