<?php

class Proceso extends MX_Controller{

    public function __construct() {
        parent::__construct();  
       // $this->load->model('models/proceso_m');
        
    }

    public function index(){
        echo "prueba";
        
//        $result=$this->db->where("id","2045")
//			->get("default_cms_videos")
//			->row_array();
        
        //$result=$this->proceso_m->get("2045","default_cms_videos");
        print_r($result);
        
    }
    
    public function micanal(){

    }
    
    private function _generarPortadasMiCanal(){
        $result=$this->_queryMysqlMiCanal(1,"");
                
    }   

    private function _queryMysqlMiCanal($option, $id = "") {

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
       
        return  $this->db->query($query)->result();
    }    
    
}

?>