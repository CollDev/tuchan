<?php
/**
 * Complements cmsapi
 *
 * @author Joe Robles <joe.robles.pdj@gmail.com>
 */
class cmsapi_lib extends MX_Controller {
    
    function __construct() {
        $this->load->model("categoria_mp");
        $this->load->model("grupo_maestros_mp");
        $this->load->model("canal_mp");
        $this->load->model("grupo_detalle_mp");
    }

    public function getProgramasList($canal_id)
    {
        $returnValue = array();
        $arrayData = $this->grupo_maestros_mp->getProgramasList(array('tipo_grupo_maestro_id' => 3, 'canales_id' => $canal_id), 'nombre');
        if (count($arrayData) > 0) {
            foreach ($arrayData as $index => $objTipo) {
                if($objTipo->estado < 2){
                    $returnValue[$objTipo->id] = $objTipo->nombre;
                }
            }
        }
        
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($returnValue);
    }
    
    public function getCanalesList()
    {
        $returnValue = array();
        $arrayData = $this->canal_mp->getCanalesList(array('estado' => 1), 'nombre');
        if (count($arrayData) > 0) {
            foreach ($arrayData as $index => $objTipo) {
                $returnValue[$objTipo->id] = $objTipo->nombre;
            }
        }
        
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($returnValue);
    }
    
    public function getCategoriasList()
    {
        $returnValue = array();
        $arrayData = $this->categoria_mp->getCategoriasList(array('categorias_id' => 0), 'nombre');
        if (count($arrayData) > 0) {
            foreach($arrayData as $index => $objTipo) {
                if ($this->categoria_mp->isParent($objTipo->id)) {
                    $returnValue[$objTipo->nombre] = $this->getChildrenCategorias($objTipo->id);
                } else {
                    $returnValue[$objTipo->id] = $objTipo->nombre;
                }
            }
        }
        
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($returnValue);
    }
    
    private function getChildrenCategorias($category_id){
        $returnValue = array();
        $arrayData = $this->categoria_mp->getCategoriasList(array("categorias_id" => $category_id));
        if (count($arrayData) > 0) {
            foreach($arrayData as $index => $objTipo) {
                    $returnValue[$objTipo->id] = $objTipo->nombre;
            }
        }
        
        return $returnValue;        
    }
    
    public function getColeccionesList($programa_id)
    {
        $arrayCollection = $this->grupo_detalle_mp->getColeccionesList(array("grupo_maestro_padre" => $programa_id));
        //var_dump($arrayCollection);exit;
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
                $arrayCollectionMaestro = $this->grupo_maestros_mp->getListCollection($array_id_maestro);
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

        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($returnValue);
    }
    
    private function isType($grupo_maestro_id, $type) {
        $returnValue = false;
        $objMaestro = $this->grupo_maestros_mp->get_by(array('id' => $grupo_maestro_id));
        if ($objMaestro[0]->tipo_grupo_maestro_id == $type) {
            $returnValue = true;
        }
        
        return $returnValue;
    }
    
    public function getListasList($coleccion_id)
    {
        $arrayCollection = $this->grupo_detalle_mp->getColeccionesList(array("grupo_maestro_padre" => $coleccion_id));
        $returnValue = array();
        if (count($arrayCollection) > 0) {
            $array_id_maestro = array();
            foreach ($arrayCollection as $index => $objCollection) {
                if ($objCollection->grupo_maestro_id != NULL) {
                    if ($this->isType($objCollection->grupo_maestro_id, 1)) {
                        array_push($array_id_maestro, $objCollection->grupo_maestro_id);
                    }
                }
            }
            if (count($array_id_maestro) > 0) {
                $arrayCollectionMaestro = $this->grupo_maestros_mp->getListCollection($array_id_maestro);
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
                    
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($returnValue);
    }
}