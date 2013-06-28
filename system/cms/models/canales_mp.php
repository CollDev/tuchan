<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Canales_mp extends CI_Model {

    protected $_table = 'default_cms_canales';
    protected $_view_maestros = 'default_vw_maestros';
    protected $_table_videos = 'default_cms_videos';

    public function getCanales() {
        $query = "select * from " . $this->_table . " where apikey is not null and playerkey is not null";
        return $this->db->query($query)->result();
    }

    public function getCanalesXId($id) {
        $query = "SELECT ca.*,(SELECT COUNT(id) FROM " . $this->_table_videos . " vi WHERE vi.estado = 2 AND vi.canales_id =ca.id ) AS 'canal_cv',
                (SELECT COUNT(*) FROM " . $this->_view_maestros . " vm WHERE  canales_id = " . $id . "  AND ( (gm1 IS NULL AND gm2 IS NULL  AND gm3 IS NULL  AND tipo_grupo=3 ) OR 
                (gm1 IS NULL AND gm2 IS NULL  AND gm3 IS NULL  AND tipo_grupo=2 ) OR (gm1 IS NULL AND gm2 IS NULL  AND gm3 IS NULL  AND tipo_grupo=1 ))) AS 'canal_cs'
                FROM " . $this->_table . "  ca WHERE ca.id=" . $id;
        return $this->db->query($query)->result();
    }

    public function getCantVideosXCanalId($id) {
        $query = "SELECT ca.*,COUNT(vi.id) as 'cv' FROM " . $this->_table . "  ca INNER JOIN " . $this->_table_videos . "	vi ON vi.canales_id = ca.id WHERE  vi.estado = ".$this->config->item("v_e:publicado")." AND vi.canales_id =" . $id;
        return $this->db->query($query)->result();
    }

    public function getCantSeccionesXCanalId($id) {
        $query = "SELECT COUNT(*)  AS 'canal_cs' FROM " . $this->_view_maestros . " vm WHERE  canales_id = " . $id . "  AND ( (gm1 IS NULL AND gm2 IS NULL  AND gm3 IS NULL  AND tipo_grupo=3 ) OR 
                (gm1 IS NULL AND gm2 IS NULL  AND gm3 IS NULL  AND tipo_grupo=2 ) OR (gm1 IS NULL AND gm2 IS NULL  AND gm3 IS NULL  AND tipo_grupo=1 ))";
        return $this->db->query($query)->result();
    }

}
