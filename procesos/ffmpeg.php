<?php

class Ffmpeg{
	/*
	var $pathi=$_SERVER["DOCUMENT_ROOT"]."uploads/videos/";
	var $pathf=$_SERVER["DOCUMENT_ROOT"]."uploads/videos/";
	*/		
	function convertVideotoMp4($id_video,$ubi){
		//	echo $id_video."<br>";

		try {

		
			$conexion = new Conexion();

			$video_in=$ubi."uploads/videos/".$id_video.".vid";
			echo "qwdqwdwq:".$video_in."<br>";
			


			$video_out=$ubi."uploads/videos/".$id_video.".mp4";
				echo $video_out."<br>";
		
			$conexion->updateEstadoVideosLiquid($id_video,1);

			//exec("ffmpeg -i ".$video_in." ".$video_out);

			//$conexion->updateEstadoVideosLiquid($id_video,1);

			if (is_readable($video_out)) {
				$conexion->updateEstadoVideosLiquid($id_video,2);
			    return true;
			} else {
				$conexion->updateEstadoVideosLiquid($id_video,-1);
			    return false;
			}
			
	

		} catch (Exception $e) {
			echo "Horror !!!!!".$e->message;
			 return false;
		}
	}
}	

?>