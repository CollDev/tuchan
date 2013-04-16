<?php
include ("config.php");
    
$id_padre=$_POST["id_padre"];
$id_hijo=$_POST["id_hijo"];
$inicio=$_POST["inicio"];
$duracion=$_POST["duracion"];

//
//$id_padre=1146;
//$id_hijo=2017;
//$inicio='00:00:00';
//$duracion='00:00:15';



$claseffmpeg =  new Ffmpeg();
$clasemysql =  new Conexion_MySql();

$return  = $clasemysql->setQueryRow("select id,ruta from default_cms_videos where id=".$id_padre);
//print_r($return);

if(count($return)>0){
    
    if($claseffmpeg->downloadVideo($return["id"], $return["ruta"])){
        //echo "archivo en disco \n";
        
        if($claseffmpeg->splitVideo($id_padre,$id_hijo, $inicio, $duracion)){
           // echo "archivo cortado \n";
            
            $clasemysql->setUpdate("update default_cms_videos set estado_liquid = 2, estado=0 where id=".$id_hijo);
           /// echo "OK";
        }
    }
            
}






?>
