<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sphinx_m extends CI_Model {

    var $CLIENTSPHINX;

    public function __construct() {
        $this->load->library('SphinxClient');
        $this->CLIENTSPHINX = new SphinxClient();
        $this->CLIENTSPHINX->SetServer($this->config->item("host:sphinx"), $this->config->item("port:sphinx"));
    }

    public function __destruct() {
        $this->CLIENTSPHINX;
    }

    public function busquedaVideos($palabrabusqueda = "",$fechaini = null ,$fechafin =null) {
        
        
        if($fechaini!=null){
            list($dia1, $mes1, $año1) = explode('-', $fechaini);
            //$fechaini = mktime(0, 0, 0, $mes1, $dia1, $año1);  
            $fechaini =  strtotime($año1.'-'.$mes1.'-'.$dia1.' 05:00:00');            
        }   
        
        if($fechafin!=null){
            list($dia2, $mes2, $año2) = explode('-', $fechafin);
            //$fechafin = mktime(0, 0, 0, $mes2, $dia2,  $año2);
            $fechafin =  strtotime($año2.'-'.$mes2.'-'.$dia2.' 05:00:00');  
        }
        
        
        error_log($palabrabusqueda);
        error_log($fechaini);
        error_log($fechafin);
        
        $palabrabusqueda = urldecode(str_replace("-", " ", $palabrabusqueda));
        
        $parametros = array();
        
//        if ($parametro == ESTADO_ACTIVO) {
//            $parametros['estado'] = ESTADO_ACTIVO;
//            $parametros["peso_videos"] = 
//                array('tags' => $this->config->item('peso_tag:sphinx'),
//                      'titulo' => $this->config->item('peso_titulo:sphinx'),
//                      'descripcion' => $this->config->item('peso_descripcion:sphinx')
//                    );
//        }        
                  
        $cl = $this->CLIENTSPHINX;
              
        $index = 'busquedavideos';
        //$cl->SetLimits(0, 100);
        //$cl->SetSortMode(SPH_SORT_EXTENDED,"fecha desc");
        $cl->SetMatchMode(SPH_MATCH_ALL);
        $cl->SetRankingMode(SPH_RANK_PROXIMITY);
        
//        if (!empty($parametros["peso_videos"])) {
//            $cl->SetFieldWeights($parametros["peso_videos"]);
//        }
//        if (!empty($parametros["estado"])) {
//            $cl->SetFilter('estado', array(2));
//        }
        
        if(!empty($fechaini) && !empty($fechafin)){
            error_log("SetFilterRange ");
             $cl->SetFilterRange('fecha', $fechaini, $fechafin);
        }elseif(!empty($fechaini) && empty($fechafin)) {
            error_log("SetFilter " . $fechaini);
            $cl->SetFilter('fecha', array($fechaini));
        }else{
            error_log("nada");          
        }
        
        $cl->AddQuery($palabrabusqueda, $index);
        $cl->ResetFilters();

        $cl->SetArrayResult(TRUE);
        $result = $cl->RunQueries();


        if ($result === false) {
            echo "fallo en Query: " . $cl->GetLastError() . ".n";
        } else {
            if ($cl->GetLastWarning()) {
                echo "WARNING: " . $cl->GetLastWarning() . "";
            }

           
            $arrvideos = array();
            if (!empty($result[0]["matches"])) {
                $res = $result[0]["matches"];
                for ($i = 0; $i < count($res); $i++) {
                    $arraytemp = array();

                    $arraytemp["id"]=$res[$i]["attrs"]["idvi"];
                    $arraytemp["nombre_vi"] = str_replace('"', '\"', $res[$i]["attrs"]["titulo"]);
                    //$arraytemp["descripcion"]=preg_replace("[\n|\r|\n\r]",' ', (strip_tags($res[$i]["attrs"]["descripcion"])));
                    $arraytemp["reproducciones"] = $res[$i]["attrs"]["reproducciones"];
                    $arraytemp["duracion"] = $res[$i]["attrs"]["duracion"];
                    //$arraytemp["fecha"]=$res[$i]["attrs"]["fecha_transmision"];
                    $arraytemp["nombre_ca"] = $res[$i]["attrs"]["nombre_ca"];
                    $arraytemp["categorias_id"] = $res[$i]["attrs"]["categorias_id"];
                    $arraytemp["categorias_no"] = $res[$i]["attrs"]["categorias_no"];


//                    $categoria = $res[$i]["attrs"]["categorias_al"];
//                    if ($categoria == ALIAS_SERIES_DE_TV || $categoria == ALIAS_REALITY || $categoria == ALIAS_NOVELAS || $categoria == ALIAS_INFANTILES || $categoria == ALIAS_HUMOR || $categoria == ALIAS_MODAS) {
//                        $arraytemp["categorias_al"] = "entretenimiento";
//                    } else {
//                        $arraytemp["categorias_al"] = $res[$i]["attrs"]["categorias_al"];
//                    }
                    error_log($res[$i]["attrs"]["fechaint"]);
                    $arraytemp["fecha"] = $res[$i]["attrs"]["fecha_format"];
                    $arraytemp["fecha_u"] = $res[$i]["attrs"]["fecha"];
                    $arraytemp["comentarios"] = $res[$i]["attrs"]["comentarios"];
                    $arraytemp["valorizacion"] = $res[$i]["attrs"]["valorizacion"];
                    $arraytemp["tags"] = $res[$i]["attrs"]["tags"];
                    $arraytemp["estado"] = $res[$i]["attrs"]["estado"];
                    $arraytemp["alias"]=$res[$i]["attrs"]["alias"];
                    $arraytemp["alias_pr"]=$res[$i]["attrs"]["alias_pr"];
                    $arraytemp["alias_co"]=$res[$i]["attrs"]["alias_co"];
                    $arraytemp["alias_lr"]=$res[$i]["attrs"]["alias_lr"];

                    if ($res[$i]["attrs"]["fecha_format"] == $res[$i]["attrs"]["alias_lr"]) {
                        $urltemp = $res[$i]["attrs"]["alias_pr"] . "/" . $res[$i]["attrs"]["fecha_format"] . "-" . $res[$i]["attrs"]["alias"];
                    } elseif(!empty ($res[$i]["attrs"]["alias_pr"])) {                       
                        $urltemp = $res[$i]["attrs"]["alias_pr"] . "/" . $res[$i]["attrs"]["alias_lr"] . "/" . $res[$i]["attrs"]["fecha_format"] . "-" . $res[$i]["attrs"]["alias"];
                    }else{
                        $urltemp =   "video/" . $res[$i]["attrs"]["fecha_format"] . "-" . $res[$i]["attrs"]["alias"];
                    }


                    $arraytemp["url"] = $urltemp;


                    if ($res[$i]["attrs"]["procedencia"] == 0) {
                        $tempimagen = $this->config->item("server:elemento") . $res[$i]["attrs"]["imagen"];
                    } else {
                        $tempimagen = $res[$i]["attrs"]["imagen"];
                    }

                    $arraytemp["imagen"] = $tempimagen;
                    array_push($arrvideos, $arraytemp);
                }
            }



            $arraygeneral["videos"] = $arrvideos;

            //$arraygeneral["parametros"] = $palabrabusqueda;

//            $arrayunix = array();

//            $arrayunix["now"] = strtotime('now');
//            //$arrayunix["hoy"]=strtotime('-1 day');
//            $arrayunix["esta_semana"] = strtotime('-1 Week');
//            $arrayunix["este_mes"] = strtotime('-1 Month');

//            $arrayunix["hoy"] = date("d-m-Y");

//            $arraygeneral["fechas"] = $arrayunix;

            
            return json_encode($arraygeneral, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        }
    }

   
}

?>
