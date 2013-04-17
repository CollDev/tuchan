<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Videos_m extends CI_Model {
    
    protected $_table = 'default_cms_videos';
    
    public function getVideosxId($id){
        $query="select * from ".$this->_table. " where id=".$id;
        return $this->db->query($query)->result();        
    }

    public function get($id, $table) {
        return $this->db->where($this->primary_key, $id)
                        ->get($table)
                        ->row_array();
    }

    public function queryMysqlMiCanal($option, $id = "") {

        switch ($option) {
            case '1':
                $query = "SELECT *  FROM default_cms_canales where estado=1";
                break;
            case '2':
                $query = "SELECT id,nombre,descripcion,alias,categorias_id FROM default_cms_grupo_maestros WHERE tipo_grupo_maestro_id=3 AND canales_id=" . $id;
                break;
            case '3':
                $query = "SELECT id,nombre,descripcion,alias,categorias_id FROM default_cms_grupo_maestros  WHERE id IN (SELECT grupo_maestro_id FROM default_cms_grupo_detalles WHERE grupo_maestro_padre=" . $id . " AND tipo_grupo_maestros_id=3) AND tipo_grupo_maestro_id=2 ";
                break;
            case '4':
                $query = "SELECT id,nombre,descripcion,alias,categorias_id FROM default_cms_grupo_maestros  WHERE id IN (SELECT grupo_maestro_id  FROM default_cms_grupo_detalles  WHERE grupo_maestro_padre=" . $id . " AND tipo_grupo_maestros_id=2) AND tipo_grupo_maestro_id=1";
                break;
            case '5':
                $query = "SELECT vi.id,vi.titulo,vi.alias,vi.descripcion,vi.categorias_id,ca.nombre,vi.codigo,vi.fecha_transmision,vi.fragmento,vi.codigo,vi.reproducciones,fu_timeahhmmss(vi.duracion) as 'duracion',vi.canales_id,vi.valorizacion,vi.comentarios             FROM default_cms_videos vi   INNER JOIN default_cms_categorias ca ON vi.categorias_id=ca.id   WHERE vi.id IN ( SELECT video_id FROM default_cms_grupo_detalles WHERE grupo_maestro_padre=" . $id . " AND tipo_grupo_maestros_id=1 )  ORDER BY fragmento ASC";
                break;
        }

        return $this->db->query($query)->result();
    }

//	public function get_all()
//	{
//		//$this->db->where('site_id', $this->site->id);
//		return $this->db->get('redirects')->result();
//	}
//
//	public function get_from($from)
//	{
//		//$this->db->where('site_id', $this->site->id);
//		// Reverse like query
//		$redirects_table = $this->db->dbprefix('redirects');
//		if ($this->db->platform() == 'mysql')
//		{
//			$data = $this->db->query("SELECT * FROM (`$redirects_table`) WHERE ? LIKE $redirects_table.from", 
//				array($from))->row();
//		}
//		// Postgres version * Not tested *
//		else
//		{
//			$data = $this->db->query("SELECT * FROM $redirects_table WHERE ? LIKE $redirects_table.from",
//				array($from))->row();
//		}
//		return $data;
//	}
//
//	public function count_all()
//	{
//		//$this->db->where('site_id', $this->site->id);
//		return $this->db->count_all_results('redirects');
//	}
//
//	public function insert($input = array())
//	{
//		return $this->db->insert('redirects', array(
//			'`type`' => $input['type'],
//			'`from`' => str_replace('*', '%', $input['from']),
//			'`to`' => trim($input['to'], '/'),
//		//	'site_id' => $this->site->id
//		));
//	}
//
//	public function update($id, $input = array())
//	{
//		$this->db->where(array(
//			'id' => $id,
//		//	'site_id' => $this->site->id
//		));
//
//		return $this->db->update('redirects', array(
//			'`type`' => $input['type'],
//			'`from`' => str_replace('*', '%', $input['from']),
//			'`to`' => trim($input['to'], '/')
//		));
//	}
//
//	public function delete($id)
//	{
//		return $this->db->delete('redirects', array(
//			'id' => $id,
//		//	'site_id' => $this->site->id
//		));
//	}
//
//	// Callbacks
//	public function check_from($from, $id = 0)
//	{
//		if($id > 0)
//		{
//			$this->db->where('id !=', $id);
//		}
//
//		return $this->db->where(array(
//			'`from`' =>  str_replace('*', '%', $from),
//		//	'site_id' => $this->site->id
//		))->count_all_results('redirects');
//	}
}