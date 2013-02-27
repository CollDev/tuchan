<?php
include_once 'liquid.php';
include_once 'util/conn_mysql.php';

class  Mantenimiento{

	function publicarPendientes(){

		$conexion = new Conexion();
		$liquid = new  Liquid();

		$returconsulta=$conexion->setConsulta("SELECT id,titulo,descripcion,codigo FROM default_cms_videos where estado_liquid in (4,5)");

		if ($returconsulta) {
			while ($row = $returconsulta->fetch_object()) {

			    $arrdatos['id']  = $row->id;
			    $arrdatos['fecha']  = date('Y-m-d H:i:s');
				$arrdatos['title']  = $row->titulo;
				$arrdatos['legend'] = $row->descripcion;
				$arrdatos['codigo'] = $row->codigo;

				//print_r($arrdatos);

				$retorno = $liquid->obtenerDatosMedia($arrdatos['codigo']);

				//print_r($retorno);

				//echo "published: ".$retorno["published"]."<br>";


				if($retorno["published"]=="false"){
					///echo "entro aki";
					$liquid -> updatePublishedMediaNode($arrdatos['codigo'],$arrdatos);
					//echo "paso aki";
					$retornopublished = $liquid -> obtenerDatosMedia($arrdatos['codigo']);
					//echo "mostrando : ";		
					//print_r($retornopublished);

					if($retornopublished["published"]=="true"){
					//echo "entro en 6";
						$conexion->updateEstadoVideosLiquid($row->id,6);
					//echo "FIN";	
					}else{
						//echo "entro en 5";		
						$conexion->updateEstadoVideosLiquid($row->id,5);
					}

				}	
				

			}
		 }		
	}

	function obtenerImagenes(){

		

	}
}

	$mantenimiento = new Mantenimiento();


	//while (true) {
		$mantenimiento->publicarPendientes();	

		//sleep(30);}
	

?>