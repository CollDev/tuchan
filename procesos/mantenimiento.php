<?php
include_once 'liquid.php';
include_once 'util/conn_mysql.php';

class  Mantenimiento{

	var  $liquid="";
	var  $conexionmysql="";

	function __construct() {
		$this->liquid = new  Liquid();
		$this->conexionmysql = new Conexion();       
   	}


	function publicarPendientes(){


		$returconsulta=$this->conexionmysql->setConsulta("SELECT id,titulo,descripcion,codigo FROM default_cms_videos where estado_liquid in (4,5)");

		if ($returconsulta) {
			while ($row = $returconsulta->fetch_object()) {

			    $arrdatos['id']  = $row->id;
			    $arrdatos['fecha']  = date('Y-m-d H:i:s');
				$arrdatos['title']  = $row->titulo;
				$arrdatos['legend'] = strip_tags($row->descripcion);
				$arrdatos['codigo'] = $row->codigo;

				//print_r($arrdatos);

				$retorno = $this->liquid->obtenerDatosMedia($arrdatos['codigo']);


				if(count($retorno)!=0){
					$duracion =($retorno['files']['file'][0]['videoInfo']['duration'])/(60*100);
					$reproducciones=$retorno['numberOfViews'];
				}

				
/*
				//echo "published: ".$retorno["published"]."<br>";

				

				if($retorno["published"]=="false"){
					///echo "entro aki";
					$this->liquid -> updatePublishedMediaNode($arrdatos['codigo'],$arrdatos);
					//echo "paso aki";
					$retornopublished = $this->liquid -> obtenerDatosMedia($arrdatos['codigo']);
					//echo "mostrando : ";		
					//print_r($retornopublished);

					if($retornopublished["published"]=="true"){
					//echo "entro en 6";
						$this->conexionmysql->updateEstadoVideosLiquid($row->id,6);
					//echo "FIN";	
					}else{
						//echo "entro en 5";		
						$this->conexionmysql->updateEstadoVideosLiquid($row->id,5);
					}
				}
				else{

					$this->conexionmysql->updateEstadoVideosLiquid($row->id,6);
				}
				*/

			}
		 }		
	}

	function obtenerImagenes(){

		

	}
}

	$mantenimiento = new Mantenimiento();
	$mantenimiento->publicarPendientes();

		//while (true) {	
		//$mantenimiento->$retorno['files']['file'][0]['videoInfo']['duration'];
		//sleep(30);}
	

?>