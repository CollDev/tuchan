<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Grupo_Maestros_mp extends CI_Model {

    protected $_table = 'default_cms_grupo_maestros';
    protected $_table_canales = 'default_cms_canales';
    protected $_table_grupo_detalles = 'default_cms_grupo_detalles';
    protected $_view_maestro_videos = 'default_vw_maestros_videos';

    function getGrupoMaestro() {
        $this->db
                ->select('*')
                ->from($this->_table)                
                ->order_by("tipo_grupo_maestro_id", "desc");

        $query = $this->db->get();
        return $query->result();        
    }

    function getGrupoMaestroXId($tgm, $id) {
        $query = "SELECT gm.id,gm.nombre,gm.descripcion,gm.alias,gm.categorias_id,gm.estado,gm.estado_migracion,gm.id_mongo,ca.nombre AS 'nombre_ca',ca.alias AS 'alias_ca',ca.id_mongo AS 'idmongo_ca',
                    (SELECT COUNT(id) FROM " . $this->_view_maestro_videos . " vmv  WHERE  vmv.gm" . $tgm . " = " . $id . "  AND vmv.v='v') AS 'vi',
                    (SELECT gm2.id_mongo FROM " . $this->_table . " gm2 INNER JOIN " . $this->_table_grupo_detalles . " gd2 ON gm2.id = gd2.grupo_maestro_padre WHERE 
                        gd2.grupo_maestro_id = gm.id) AS 'idmongo_pa' 
                        FROM " . $this->_table . " gm INNER JOIN " . $this->_table_canales . " ca ON gm.canales_id = ca.id
                        WHERE gm.tipo_grupo_maestro_id=" . $tgm . " AND gm.id =" . $id;

        return $this->db->query($query)->result();
    }

    function getCantidadVideosXMaestroId($tgm, $id) {
        $query = "SELECT gm.id,gm.id_mongo,(SELECT COUNT(id) FROM " . $this->_view_maestro_videos . " vmv  WHERE  vmv.estado=2 and vmv.gm3 = " . $id . "  AND vmv.v='v') AS 'cv'
                        FROM " . $this->_table . " gm INNER JOIN " . $this->_table_canales . " ca ON gm.canales_id = ca.id
                        WHERE gm.tipo_grupo_maestro_id=" . $tgm . " AND gm.id =" . $id;

        return $this->db->query($query)->result();
    }

    function getMaestroDetalles() {
        $query = "SELECT id,grupo_maestro_id , COUNT(grupo_maestro_padre)  AS 'cant' FROM default_cms_grupo_detalles GROUP BY grupo_maestro_id ORDER BY cant DESC";
        return $this->db->query($query)->result();
    }

    function getMaestroDetallesXId($id) {      
         $this->db
                ->select('*')
                ->from($this->_table_grupo_detalles)                
                ->where(array('grupo_maestro_id'=>$id));

        $query = $this->db->get();
        return $query->result();    
    }

    function updateIdMongoGrupoMaestros($id, $id_mongo) {
//        $query = "update " . $this->_table . " set id_mongo='" . $id_mongo . "' where id=" . $id;
//        $this->db->query($query);
        
        $data = array('id_mongo' => $id_mongo);
        $this->db->where('id', $id);
        $this->db->update($this->_table, $data);
    }

    function updateEstadoMigracionGrupoMaestros($id) {
        $query = "update " . $this->_table . "  set estado_migracion=2, fecha_migracion = now() where id=" . $id;
        $this->db->query($query);
    }

    function updateEstadoMigracionGrupoMaestrosActualizacion($id) {
        $query = "update " . $this->_table . "  set estado_migracion=2, fecha_migracion_actualizacion = now() where id=" . $id;
        $this->db->query($query);
    }

    function deleteMaestroDetallesXId($id) {
        $query = "delete from default_cms_grupo_detalles WHERE  id =" . $id;
        return $this->db->query($query);
    }

    public function getExisteGrupoMaestroXIdMongo($id) {       
         $this->db
                ->from($this->_table)
                ->where(array('id_mongo' => $id));
        return $this->db->count_all_results();
    }

    public function getProgramasList($where = array(), $order = NULL) {
        $this->db->select("*");
        $this->db->from($this->_table);
        $this->db->where($where);
        if ($order != NULL) {
            $this->db->order_by($order);
        }

        return $this->db->get()->result();
    }

    public function getColeccionesDropDown($arrayCollection) {
        $returnValue = array();
        if (count($arrayCollection) > 0) {
            $array_id_maestro = array();
            foreach ($arrayCollection as $index => $objCollection) {
                if ($objCollection->grupo_maestro_id != NULL) {
                    if ($this->isType($objCollection->grupo_maestro_id, 2)) {
                        array_push($array_id_maestro, $objCollection->grupo_maestro_id);
                    }
                }
            }
            if (count($array_id_maestro) > 0) {
                $arrayCollectionMaestro = $this->getListCollection($array_id_maestro);
            } else {
                $arrayCollectionMaestro = array();
            }
            if (count($arrayCollectionMaestro) > 0) {
                foreach ($arrayCollectionMaestro as $indice => $objMaestro) {
                    if ($objMaestro->estado < 2) {
                        $returnValue[$objMaestro->id] = $objMaestro->nombre;
                    }
                }
            }
        }

        return $returnValue;
    }

    public function getListCollection($array_id_maestro) {
        $query = "SELECT * FROM " . $this->_table . " WHERE id IN (" . implode(',', $array_id_maestro) . ")";

        return $this->db->query($query)->result();
    }

    public function get_by($by) {
        $key = array_keys($by);
        $value = array_values($by);
        $query = "SELECT * FROM default_cms_grupo_maestros WHERE `" . $key[0] . "` = '" . $value[0] . "';";

        return $this->db->query($query)->result();
    }

}

