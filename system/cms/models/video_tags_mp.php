<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Video_Tags_mp extends CI_Model {

    protected $_table = 'default_cms_video_tags';
    protected $_table_tags = 'default_cms_tags';
    
    
    function getTagsVideosXId($id){
        $query= "SELECT GROUP_CONCAT(nombre SEPARATOR ' ' ) AS 'tags' FROM ".$this->_table."  vta INNER JOIN ".$this->_table_tags."  tag ON vta.tags_id=tag.id WHERE vta.videos_id=".$id;
        return $this->db->query($query)->result();
    }   
    
}
    
    
    