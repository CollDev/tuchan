<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MiCanal_mp extends CI_Model {

    private $_tabla = 'micanal';
    protected $_table_canales = 'default_cms_canales';
    protected $_table_grupo_maestros = 'default_cms_grupo_maestros';
    protected $_table_videos = 'default_cms_videos';
    protected $_table_categorias = 'default_cms_categorias';
    protected $_table_grupo_detalles = 'default_cms_grupo_detalles';
    protected $_table_portadas = 'default_cms_portadas';
    protected $_table_secciones = 'default_cms_secciones';
    protected $_table_detalle_secciones = 'default_cms_detalle_secciones';
    protected $_table_imagenes = 'default_cms_imagenes';
    protected $_table_tags = 'default_cms_tags';

    public function queryMysqlMiCanal($option, $id = "") {

        switch ($option) {
            case '1':
                $query = "SELECT *  FROM " . $this->_table_canales . " where estado=1";
                break;
            case '2':
                $query = "SELECT id,nombre,descripcion,alias,categorias_id FROM " . $this->_table_grupo_maestros . " WHERE tipo_grupo_maestro_id=3 AND canales_id=" . $id;
                break;
            case '3':
                $query = "SELECT id,nombre,descripcion,alias,categorias_id FROM " . $this->_table_grupo_maestros . "  WHERE id IN (SELECT grupo_maestro_id FROM default_cms_grupo_detalles WHERE grupo_maestro_padre=" . $id . " AND tipo_grupo_maestros_id=3) AND tipo_grupo_maestro_id=2 ";
                break;
            case '4':
                $query = "SELECT id,nombre,descripcion,alias,categorias_id FROM " . $this->_table_grupo_maestros . "  WHERE id IN (SELECT grupo_maestro_id  FROM default_cms_grupo_detalles  WHERE grupo_maestro_padre=" . $id . " AND tipo_grupo_maestros_id=2) AND tipo_grupo_maestro_id=1";
                break;
            case '5':
                $query = "SELECT vi.id,vi.titulo,vi.alias,vi.descripcion,vi.categorias_id,ca.nombre,vi.codigo,vi.fecha_transmision,vi.fragmento,vi.codigo,vi.reproducciones,fu_timeahhmmss(vi.duracion) as 'duracion',vi.canales_id,vi.valorizacion,vi.comentarios   
                    FROM " . $this->_table_videos . " vi   INNER JOIN " . $this->_table_categorias . " ca ON vi.categorias_id=ca.id   
                    WHERE vi.id IN ( SELECT video_id FROM " . $this->_table_grupo_detalles . " 
                    WHERE grupo_maestro_padre=" . $id . " AND tipo_grupo_maestros_id=1 ) 
                        ORDER BY fragmento ASC";
                break;
        }

        return $this->db->query($query)->result();
    }

    public function queryProcedure($option, $id) {
        switch ($option) {
            case '1':
                $query = "call sp_llenartiposeccion6789";                                
                break;
            case '4':
                $query = "call sp_obtenerdatos(" . $id . ")";
                break;            
        }


        $objresult = $this->db->query($query);
        //mysqli_next_result($this->db->conn_id);
         $objresult->free_result();
        return $objresult->result();        
    }

    public function queryMysql($option, $id = "") {

        switch ($option) {
            case '1':
                $query = "SELECT po.id, po.nombre, po.descripcion, po.tipo_portadas_id,IFNULL(po.origen_id,po.canales_id) AS 'origen_id',po.estado,po.id_mongo,po.estado_migracion 
                    FROM " . $this->_table_portadas . " po WHERE po.estado_migracion IN (0,9)";
                break;

            case '2':
                $query = "  SELECT se.id,se.nombre,se.peso,se.templates_id,se.tipo_secciones_id,se.id_mongo as mongo_se, po.id_mongo as mongo_po,  se.estado,se.estado_migracion,fu_aliaspa(tipo_portadas_id,origen_id) AS 'alias_pa',po.tipo_portadas_id,po.origen_id
                            FROM " . $this->_table_secciones . " se INNER JOIN " . $this->_table_portadas . " po ON se.portadas_id=po.id
                            where se.estado_migracion IN (0,9)";

                break;


            case '3' :
                $query = "SELECT se.id,se.id_mongo,se.tipo_secciones_id,po.tipo_portadas_id
                        FROM default_cms_secciones se INNER JOIN default_cms_portadas po ON se.portadas_id = po.id
                        WHERE se.estado=1 AND 
                        se.id IN (  SELECT DISTINCT ds.secciones_id FROM default_cms_detalle_secciones  ds WHERE ds.estado_migracion IN (0,9) AND ds.estado=1)  ";
                //echo "query3;" . $query . "\n";
                break;


            case '4':
                $query = "SELECT se.tipo_secciones_id,po.tipo_portadas_id,ds.id,ds.descripcion_item,ds.videos_id,ds.grupo_maestros_id,ds.canales_id,ds.imagenes_id,ds.peso,im.imagen,im.procedencia,ds.estado,ds.estado_migracion
                            FROM default_cms_detalle_secciones ds
                            INNER JOIN default_cms_imagenes im ON im.id=ds.imagenes_id
                            INNER JOIN default_cms_secciones se ON ds.secciones_id=se.id
                            INNER JOIN default_cms_portadas po ON po.id=se.portadas_id
                            WHERE secciones_id=".$id." AND ds.estado=1  ORDER BY peso ASC ";
               
                break;

            case '5':
                $query = "SELECT ca.descripcion AS 'canal_des',im.imagen AS 'canal_img',im.procedencia, (SELECT COUNT(id) FROM default_cms_videos WHERE canales_id=ca.id) AS 'canal_cv'
                        FROM " . $this->_table_canales . " ca  INNER JOIN " . $this->_table_imagenes . " im ON ca.id=im.canales_id  WHERE  
                        im.tipo_imagen_id=6 AND ca.estado=1 and ca.id=" . $id . " LIMIT 1";
                break;
        }

        return $this->db->query($query)->result();
    }

    public function queryMysqlTipoPortadas($option, $id) {

        switch ($option) {
            case '1': // principal
                $query = "SELECT ca.id,ca.nombre,ca.alias,ca.id_mongo FROM " . $this->_table_canales . " ca WHERE  ca.id=" . $id;
                break;

            case '2': // categoria
                $query = "SELECT ca.id,ca.nombre,ca.alias  FROM    " . $this->_table_categorias . "  ca  WHERE ca.id=" . $id;

                break;

            case '3': // tags
                $query = "SELECT ta.id, ta.nombre, ta.alias fROM " . $this->_table_tags . " ta  where ta.id=" . $id;
                break;

            case '4': // programas 
                $query = "SELECT gm.id, gm.nombre,gm.alias,gm.descripcion, ca.alias AS 'alias_ca' FROM " . $this->_table_grupo_maestros . " gm  INNER JOIN 
                                    " . $this->_table_categorias . " ca ON ca.id=gm.categorias_id
                                    WHERE  gm.id=" . $id;

                break;

            case '5': // canales
                $query = "SELECT ca.id,ca.nombre,ca.descripcion,ca.alias,ca.id_mongo,im.imagen AS 'canal_img',im.procedencia,
                            (SELECT COUNT(id) FROM " . $this->_table_videos . " WHERE canales_id=ca.id) AS 'canal_cv'
                            FROM " . $this->_table_canales . " ca  INNER JOIN " . $this->_table_imagenes . " im ON ca.id=im.canales_id  
                            WHERE  
                            im.tipo_imagen_id=6 and 
                            ca.id=" . $id;

                break;
        }


        return $this->db->query($query)->result();
    }

    public function updateIdMongoPortadas($id, $id_mongo) {
        $query = "update " . $this->_table_portadas . " set id_mongo='" . $id_mongo . "',estado_migracion=2 where id=" . $id;
//        echo $query;
        $this->db->query($query);
    }

    public function updateEstadoMigracionPortadas($id) {
        $query = "update " . $this->_table_portadas . " set estado_migracion=2,fecha_migracion=now() where id=" . $id;
        //echo $query;
        $this->db->query($query);
    }

    public function updateEstadoMigracionPortadasActualizacion($id) {
        $query = "update " . $this->_table_portadas . " set estado_migracion=2,fecha_migracion_actualizacion=now() where id=" . $id;
        $this->db->query($query);
    }

    public function updateIdMongoSecciones($id, $id_mongo) {
        $query = "update " . $this->_table_secciones . " set id_mongo='" . $id_mongo . "',estado_migracion=2 where id=" . $id;
        $this->db->query($query);
    }

    public function updateEstadoMigracionSecciones($id) {
        $query = "update " . $this->_table_secciones . " set estado_migracion=2,fecha_migracion=now() where id=" . $id;
        $this->db->query($query);
    }

    public function updateEstadoMigracionSeccionesActualizacion($id) {
        $query = "update " . $this->_table_secciones . " set estado_migracion=2,fecha_migracion_actualizacion=now() where id=" . $id;
        $this->db->query($query);
    }

    public function updateEstadoMigracionDetalleSecciones($id) {
        $query = "update " . $this->_table_detalle_secciones . " set estado_migracion=2,fecha_migracion=now() where id=" . $id;
       
        $this->db->query($query);
    }

    public function updateEstadoMigracionDetalleSeccionesActualizacion($id) {
        $query = "update " . $this->_table_detalle_secciones . " set estado_migracion=2,fecha_migracion_actualizacion=now() where id=" . $id;
       
        $this->db->query($query);
    }

    public function setItemCollection($objmongo) {
        return $this->mongo_db->insert($this->_tabla, $objmongo);
    }

    public function SetItemCollectionUpdate($set, $where) {
        $result= $this->mongo_db->where($where)->set($set)->update($this->_tabla);
    }

    public function SetItemCollectionDelete($id_mongo) {
       return $this->mongo_db->delete_where($this->_tabla,$id_mongo);
         
    }
    
    public function existe_id_mongo($id)
    {
        $id_mongo = new MongoId($id);
        $result = $this->mongo_db->get_where($this->_tabla, array('_id' => $id_mongo));
        
        if (count($result) > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}