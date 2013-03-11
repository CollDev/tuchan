<?php 


include_once 'ffmpeg.php';
include_once 'liquid.php';
include_once 'util/conn_mysql.php';


class EnvioVideos{

	function EnvioVideosNuevos($arrdatos){

		$conexion = new Conexion();
		$ffmpeg= new Ffmpeg();

		$id_video=$arrdatos['id'];
		$ubi=$arrdatos['ubi'];
		$datos = (object) $arrdatos;


		$retffmpeg=$ffmpeg->convertVideotoMp4($id_video,$ubi);
		//echo "convirtio";	

		
		if($retffmpeg==true){
			$liquid = new Liquid();
			$retliquid = $liquid->uploadVideoLiquid($id_video,$ubi);

		print_r($retliquid);
		//exit();	

/*
			if($retliquid["ret"]=="true"){

				echo $retliquid["med"]."<br>";
				echo "paso 1";
				$liquid->updatePublishedMediaNode($retliquid["med"],$datos);
				
				echo "paso 2";
				
				$returndame = $liquid->obtenerDatosMedia($retliquid["med"]);

				echo "paso 3";
				print_r($returndame);
				

				if($returndame["published"]=="true"){
					//echo "entro en 6";
					$conexion->updateEstadoVideosLiquid($id_video,6);
					//echo "FIN";	
				}else{
					//echo "entro en 5";		
					$conexion->updateEstadoVideosLiquid($id_video,5);
				}
			}

			*/
		}
	}

	function RePublishedMedia($arrdatos){

		$conexion = new Conexion();
		$ffmpeg= new Ffmpeg();

		$id_video=$datos['id'];
		$datos = (object) $arrdatos;

		$liquid->updatePublishedMediaNode($retliquid["med"],$datos);

		$return = $liquid->obtenerDatosMedia($retliquid["med"]);

		if($return["published"]=="true"){
					$liquid->updateEstadoVideosLiquid($id_video,6);
					echo "FIN";	
		}else{
					$liquid->updateEstadoVideosLiquid($id_video,5);
		}

	}

}

echo 'post_max_size = ' . ini_get('post_max_size') . "\n";

echo 'upload_max_filesize = ' . ini_get('upload_max_filesize') . "\n";

echo 'max_execution_time = ' . ini_get('max_execution_time') . "\n";




$conexion = new Conexion();	


while(true){


	$query="SELECT id,titulo,descripcion,estado_liquid,codigo FROM default_cms_videos where estado_liquid=0 limit 1";
	//echo $query."<br>";
	$returconsulta=$conexion->setConsulta($query);
	

	
	if ($returconsulta) {
		while ($row = $returconsulta->fetch_object()) {

			if($_SERVER["DOCUMENT_ROOT"]=="/"){
				$arrdatos['ubi']="/home/idigital3/sites/adminmicanal/";
			}

			$arrdatos['ubi']=$_SERVER["DOCUMENT_ROOT"]."/";
			echo "path: ".$arrdatos['ubi'];

			//$arrdatos['ubi']=$_SERVER["DOCUMENT_ROOT"]."/videos/";

		    $arrdatos['id']  = $row->id;
		    $arrdatos['fecha']  = date('Y-m-d H:i:s');
			$arrdatos['title']  = $row->titulo;
			$arrdatos['legend'] = $row->descripcion;
			$arrdatos['codigo'] = $row->codigo;
			$arrdatos['estado_liquid'] = $row->estado_liquid;

			print_r($arrdatos);


			$enviovideos= new EnvioVideos();	

			$enviovideos->EnvioVideosNuevos($arrdatos);

		}
	 }
	 
	 	
	sleep(20);
}


?>