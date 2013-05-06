<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Video_tags_mp extends CI_Model {

    protected $_table = 'default_cms_video_tags';
//    protected $_table_canales = 'default_cms_canales';
//    protected $_table_grupo_maestros = 'default_cms_grupo_maestros';
//    protected $_table_videos = 'default_cms_videos';
//    protected $_table_categorias = 'default_cms_categorias';
//    protected $_table_grupo_detalles = 'default_cms_grupo_detalles';
//    protected $_table_portadas = 'default_cms_portadas';
//    protected $_table_secciones = 'default_cms_secciones';
//    protected $_table_detalle_secciones = 'default_cms_detalle_secciones';
//    protected $_table_imagenes = 'default_cms_imagenes';
    protected $_table_tags = 'default_cms_tags';
    
    
    function getTagsVideosXId($id){
        $query= "SELECT GROUP_CONCAT(nombre SEPARATOR ' ' ) AS 'tags' FROM ".$this->_table."  vta INNER JOIN ".$this->_table_tags."  tag ON vta.tags_id=tag.id WHERE vta.videos_id=".$id;
        return $this->db->query($query)->result();
    }   
    
}
    
    
    