<?php

defined('BASEPATH') or exit('No direct script access allowed');
<<<<<<< HEAD
=======
define('TAG_TEMATICO', '1');
define('TAG_PERSONAJE', '2');
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
class Videos_m extends MY_Model 
{

    protected $_table = 'default_cms_videos';
    
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
        
        return $this->_obtener_query_videos($where['canales_id']);
        
    }
    
    /**
     * Query que retorna lista de videos
     * @param int $canal_id
     * @return array obj
     */
    private function _obtener_query_videos($canal_id)
<<<<<<< HEAD
    {        
        $query = "SELECT v.id, v.titulo, v.fuente, v.fecha_registro, v.fecha_publicacion_inicio, fecha_publicacion_fin,
                    v.fecha_transmision, v.horario_transmision_inicio, v.horario_transmision_fin, v.estado,                            
                    c.nombre as canal, c.nombre as fuente, cat.nombre as categoria, tv.nombre as tipo_video, 
                    i.imagen, ";

        // tags tematicos
        $query .= "(SELECT group_concat(t.nombre)
                        FROM (`". $this->_table . "` v) 
                            JOIN `default_cms_video_tags` vt ON `vt`.`videos_id` = `v`.`id` 
                            JOIN `default_cms_tags` t ON `t`.`id` = `vt`.`tags_id` 
                            JOIN  default_cms_tipo_tags tt ON tt.id = t.tipo_tags_id
                        WHERE tt.id = 1
                   ) as tematico, ";

        // tags personajes
        $query .= "(SELECT group_concat(t.nombre)
                        FROM (`". $this->_table . "` v) 
                            JOIN `default_cms_video_tags` vt ON `vt`.`videos_id` = `v`.`id` 
                            JOIN `default_cms_tags` t ON `t`.`id` = `vt`.`tags_id` 
                            JOIN  default_cms_tipo_tags tt ON tt.id = t.tipo_tags_id
                        WHERE tt.id = 2
                    ) as personaje, ";
 
        // agrupar tag
	$query .= "group_concat(t.nombre) as tag ";
        
        $query .= "FROM (`". $this->_table . "` v) 
                    JOIN `default_cms_canales` c ON `c`.`id` = `v`.`canales_id` AND c.id = v.canales_id 
                    JOIN `default_cms_categorias` cat ON `cat`.`id` = `v`.`categorias_id` 
                    JOIN `default_cms_tipo_videos` tv ON `tv`.`id` = `v`.`tipo_videos_id` 
                    JOIN `default_cms_video_tags` vt ON `vt`.`videos_id` = `v`.`id` 
                    JOIN `default_cms_tags` t ON `t`.`id` = `vt`.`tags_id` 
                    JOIN  default_cms_tipo_tags tt ON tt.id = t.tipo_tags_id
                    JOIN  default_cms_imagenes i ON i.videos_id = v.id
                  WHERE `canales_id` = " . (int) $canal_id;
        
=======
    {           
            $query = "SELECT v.id, v.canales_id, v.titulo, v.fecha_registro, v.fecha_publicacion_inicio, v.fecha_publicacion_fin,
            v.fecha_transmision, v.horario_transmision_inicio, v.horario_transmision_fin, v.estado,                            
            c.nombre as canal, c.nombre as fuente, cat.nombre as categoria, tv.nombre as tipo_video, 
            i.imagen, ";
                    
            // Tags tematicos
            $query .= "(SELECT group_concat(t.nombre)
                            FROM (`". $this->_table . "` vi) 
                                LEFT JOIN `default_cms_video_tags` vt ON `vt`.`videos_id` = `vi`.`id` 
                                LEFT JOIN `default_cms_tags` t ON `t`.`id` = `vt`.`tags_id` 
                                LEFT JOIN  default_cms_tipo_tags tt ON tt.id = t.tipo_tags_id                            
                            WHERE tt.id = '" . TAG_TEMATICO ."' AND vi.id = v.id
                       ) as tematico, ";

            // Tags personajes
            $query .= "(SELECT group_concat(t.nombre)
                            FROM (`". $this->_table . "` vi) 
                                LEFT JOIN `default_cms_video_tags` vt ON `vt`.`videos_id` = `vi`.`id` 
                                LEFT JOIN `default_cms_tags` t ON `t`.`id` = `vt`.`tags_id` 
                                LEFT JOIN  default_cms_tipo_tags tt ON tt.id = t.tipo_tags_id
                            WHERE tt.id = '" . TAG_PERSONAJE ."' AND vi.id = v.id
                        ) as personaje, ";

            // Agrupar tag
            $query .= "GROUP_CONCAT(t.nombre) as tag ";

            $query .= "FROM (`". $this->_table . "` v) 
                        LEFT JOIN `default_cms_canales` c ON `c`.`id` = `v`.`canales_id` AND c.id = v.canales_id 
                        LEFT JOIN `default_cms_categorias` cat ON `cat`.`id` = `v`.`categorias_id` 
                        LEFT JOIN `default_cms_tipo_videos` tv ON `tv`.`id` = `v`.`tipo_videos_id` 
                        LEFT JOIN `default_cms_video_tags` vt ON `vt`.`videos_id` = `v`.`id` 
                        LEFT JOIN `default_cms_tags` t ON `t`.`id` = `vt`.`tags_id` 
                        LEFT JOIN  default_cms_tipo_tags tt ON tt.id = t.tipo_tags_id
                        LEFT JOIN  default_cms_imagenes i ON i.videos_id = v.id
                      WHERE v.`canales_id` = " . (int) $canal_id . " 
                      GROUP BY v.id";
            
>>>>>>> 1201ee8a8121b87db20ea4af381bef22058262ef
        $result = $this->db->query($query)->result();

        return $result;
    }
    
    public function countVideo($canal_id){
            $query = "SELECT v.id, v.canales_id, v.titulo, v.fecha_registro, v.fecha_publicacion_inicio, v.fecha_publicacion_fin,
            v.fecha_transmision, v.horario_transmision_inicio, v.horario_transmision_fin, v.estado,                            
            c.nombre as canal, c.nombre as fuente, cat.nombre as categoria, tv.nombre as tipo_video, 
            i.imagen, ";
                    
            // Tags tematicos
            $query .= "(SELECT group_concat(t.nombre)
                            FROM (`". $this->_table . "` vi) 
                                LEFT JOIN `default_cms_video_tags` vt ON `vt`.`videos_id` = `vi`.`id` 
                                LEFT JOIN `default_cms_tags` t ON `t`.`id` = `vt`.`tags_id` 
                                LEFT JOIN  default_cms_tipo_tags tt ON tt.id = t.tipo_tags_id                            
                            WHERE tt.id = '" . TAG_TEMATICO ."' AND vi.id = v.id
                       ) as tematico, ";

            // Tags personajes
            $query .= "(SELECT group_concat(t.nombre)
                            FROM (`". $this->_table . "` vi) 
                                LEFT JOIN `default_cms_video_tags` vt ON `vt`.`videos_id` = `vi`.`id` 
                                LEFT JOIN `default_cms_tags` t ON `t`.`id` = `vt`.`tags_id` 
                                LEFT JOIN  default_cms_tipo_tags tt ON tt.id = t.tipo_tags_id
                            WHERE tt.id = '" . TAG_PERSONAJE ."' AND vi.id = v.id
                        ) as personaje, ";

            // Agrupar tag
            $query .= "GROUP_CONCAT(t.nombre) as tag ";

            $query .= "FROM (`". $this->_table . "` v) 
                        LEFT JOIN `default_cms_canales` c ON `c`.`id` = `v`.`canales_id` AND c.id = v.canales_id 
                        LEFT JOIN `default_cms_categorias` cat ON `cat`.`id` = `v`.`categorias_id` 
                        LEFT JOIN `default_cms_tipo_videos` tv ON `tv`.`id` = `v`.`tipo_videos_id` 
                        LEFT JOIN `default_cms_video_tags` vt ON `vt`.`videos_id` = `v`.`id` 
                        LEFT JOIN `default_cms_tags` t ON `t`.`id` = `vt`.`tags_id` 
                        LEFT JOIN  default_cms_tipo_tags tt ON tt.id = t.tipo_tags_id
                        LEFT JOIN  default_cms_imagenes i ON i.videos_id = v.id
                      WHERE v.`canales_id` = " . (int) $canal_id . " 
                      GROUP BY v.id";
            
        $result = $this->db->query($query)->count_by();

        return $result;        
    }
    
    /**
     * Inserta un nuevo video en la base de datos
     * 
     * @param array $input La data a insertar
     * @return string
     */
    public function insert($input = array())
<<<<<<< HEAD
    {
            parent::insert(array(
                    'title' => $input['title'],
                    'slug'  => url_title(strtolower(convert_accented_characters($input['title'])))
            ));

            return $input['title'];
=======
    {        
        $video_id = parent::insert(array(
                'titulo' => $input['titulo'],
                'alias'  => url_title(strtolower(convert_accented_characters($input['titulo']))),
                'descripcion' => $input['descripcion'],
                'fragmento' => $input['fragmento'],
                'categorias_id' => $input['categorias_id'],
                'usuarios_id' => $input['usuarios_id'],
                'tipo_videos_id' => $input['tipo_videos_id'],
                //'fuente' => $input['fuente'],
                'fecha_publicacion_inicio' => date("Y-m-d H:i:s", strtotime($input['fecha_publicacion_inicio'])),
                'fecha_publicacion_fin' => date("Y-m-d H:i:s", strtotime($input['fecha_publicacion_fin'])),
                'fecha_transmision' => date("Y-m-d H:i:s", strtotime($input['fecha_transmision'])),
                'horario_transmision_inicio' => $input['horario_transmision_inicio'],
                'horario_transmision_fin' => $input['horario_transmision_fin'],
                'ubicacion' => $input['ubicacion'],
                'canales_id' => $input['canales_id'],
                'estado' => $input['estado'],
                'fecha_registro' => date('Y-m-d H:i:s'),
                'usuario_registro' => $input['usuarios_id'],
        ));

        return $video_id;
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
<<<<<<< HEAD
=======
    
    public function save_video($objBeanVideo){
        $objBeanVideo->id = parent::insert(array(
                    'tipo_videos_id' => $objBeanVideo->tipo_videos_id,
                    'categorias_id' => $objBeanVideo->categorias_id,
                    'usuarios_id' => $objBeanVideo->usuarios_id,
                    'canales_id' => $objBeanVideo->canales_id,
                    //'fuente' => $objBeanVideo->fuente,
                    'titulo' => $objBeanVideo->titulo,
                    'alias' => $objBeanVideo->alias,
                    'descripcion' => $objBeanVideo->descripcion,
                    'fragmento' => $objBeanVideo->fragmento,
                    'fecha_publicacion_inicio' => $objBeanVideo->fecha_publicacion_inicio,
                    'fecha_publicacion_fin' => $objBeanVideo->fecha_publicacion_fin,
                    'fecha_transmision' => $objBeanVideo->fecha_transmision,
                    'horario_transmision_inicio' => $objBeanVideo->horario_transmision_inicio,
                    'horario_transmision_fin' => $objBeanVideo->horario_transmision_fin,
                    'ubicacion' => $objBeanVideo->ubicacion,
                    'estado' => $objBeanVideo->estado,
                    'estado_liquid' => $objBeanVideo->estado_liquid,
                    'fecha_registro' => $objBeanVideo->fecha_registro,
                    'usuario_registro' => $objBeanVideo->usuario_registro,
                    'estado_migracion' => $objBeanVideo->estado_migracion,
                    'estado_migracion_sphinx_tit' => $objBeanVideo->estado_migracion_sphinx_tit,
                    'estado_migracion_sphinx_des' => $objBeanVideo->estado_migracion_sphinx_des,
                    'padre' => $objBeanVideo->padre
        ));
        $objBeanVideo->alias = $objBeanVideo->alias.'-'.$objBeanVideo->id;
        parent::update($objBeanVideo->id, array('alias'=>$objBeanVideo->alias));
        return $objBeanVideo;
    }
    
    public function update_video($objBeanVideo){
        return parent::update($objBeanVideo->id, array(
            'tipo_videos_id' => $objBeanVideo->tipo_videos_id, 
            'categorias_id' => $objBeanVideo->categorias_id,
            'usuarios_id' => $objBeanVideo->usuarios_id,
            'canales_id' => $objBeanVideo->canales_id,
            //'fuente' => $objBeanVideo->fuente,
            'titulo' => $objBeanVideo->titulo,
            'alias' => $objBeanVideo->alias,
            'descripcion' => $objBeanVideo->descripcion,
            'fragmento' => $objBeanVideo->fragmento,
            'fecha_publicacion_inicio' => $objBeanVideo->fecha_publicacion_inicio,
            'fecha_publicacion_fin' => $objBeanVideo->fecha_publicacion_fin,
            'fecha_transmision' => $objBeanVideo->fecha_transmision,
            'horario_transmision_inicio' => $objBeanVideo->horario_transmision_inicio,
            'horario_transmision_fin' => $objBeanVideo->horario_transmision_fin,
            'ubicacion' =>$objBeanVideo->ubicacion,
            'fecha_actualizacion' => $objBeanVideo->fecha_actualizacion,
            'usuario_actualizacion' => $objBeanVideo->usuario_actualizacion,
            'padre' => $objBeanVideo->padre
            ));        
    }
    
    public function existVideo($title, $canal_id,$video_id, $video_update = 0, $type = 0){
        $returnValue = false;
        if($video_id > 0){
            if($video_update > 0){
                $query="SELECT * FROM ".$this->_table." WHERE id = '".$video_id."' AND id NOT IN (".$video_update.") AND upper(titulo) like '".  strtoupper($title)."' AND canales_id =".$canal_id;
                //error_log("1111".$query);
            }else{
                $query="SELECT * FROM ".$this->_table." WHERE id= '".$video_id."' AND upper(titulo) like '".  strtoupper($title)."' AND canales_id =".$canal_id;
                //$query="SELECT * FROM ".$this->_table." WHERE id NOT IN (".$video_id.") AND upper(titulo) like '".  strtoupper($title)."' AND canales_id =".$canal_id;
                //error_log("2222".$query);
            }
        }else{
            $query="SELECT * FROM ".$this->_table." WHERE upper(titulo) like '".  strtoupper($title)."' AND canales_id =".$canal_id;
            //error_log("3333".$query);
        }
        $result = $this->db->query($query)->result();
        if(count($result)>0){
            $returnValue = true;
        }
        return $returnValue;        
    }
>>>>>>> 1201ee8a8121b87db20ea4af381bef22058262ef
}

/* End of file videos_m.php */