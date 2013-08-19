<?php
class Tipo_imagenes_mp extends CI_Model{
    protected $_table = 'default_cms_tipo_imagen';
    
    public function getTipoImagenes(){
        $this->db->select("*");
        $this->db->from($this->_table);
        $this->db->where("id < 5");
        $this->db->order_by("id", "asc"); 
        $resultValue = $this->db->get()->result();
        return $resultValue;
    }
    
 }

?>
