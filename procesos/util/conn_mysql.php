<?php

class Conexion{

    public $connection;
    public $host = '192.168.1.34';
    public $user = 'root';
    public $pass = '123';
    public $db = 'pyro_admin';

    /* public $connection;
        public $host = 'localhost';
        public $user = 'root';
        public $pass = '123';
        public $db = 'pro_micanal';
    */

    public function __construct() {
        $this->connection = new mysqli($this->host, $this->user, $this->pass,$this->db);        
        $mysqli=$this->connection;

            if ($mysqli->connect_errno) {
                echo "Fallo al contenctar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
            }   
    }
    
    public function setConsulta($consultamysql)
    {
        $mysqli = $this->connection;
        $result = $mysqli->query($consultamysql);    
        return $result;
    }

    public function updateEstadoVideosLiquid($id,$valor){
       $mysqli = $this->connection;
       echo    "update default_cms_videos set estado_liquid=".$valor." where id=".$id."<br>";
       $result = $mysqli->query("update default_cms_videos set estado_liquid=".$valor." where id=".$id); 
       return $result;
    }

    public function updateMediaVideosLiquid($id,$valor){
        $mysqli = $this->connection;
        echo  "update default_cms_videos set codigo='".$valor."' where id =".$id."<br>";
        $result = $mysqli->query("update default_cms_videos set codigo='".$valor."' where id =".$id);    
        return $result;
    }

    public function __destruct() {
        $this->connection;
    }


public function saveImage(&$objBeanImage){
        $sql ="INSERT INTO pyro_admin.default_cms_imagenes
            (id,
             canales_id,
             grupo_maestros_id,
             videos_id,
             imagen,
             tipo_imagen_id,
             estado,
             fecha_registro,
             usuario_registro,
             fecha_actualizacion,
             usuario_actualizacion,
             estado_migracion,
             fecha_migracion,
             fecha_migracion_actualizacion,
             imagen_padre,
             procedencia)
VALUES ('".$objBeanImage->id."',
        '".$objBeanImage->canales_id."',
        '".$objBeanImage->grupo_maestros_id."',
        '".$objBeanImage->videos_id."',
        '".$objBeanImage->imagen."',
        '".$objBeanImage->tipo_imagen_id."',
        '".$objBeanImage->estado."',
        '".$objBeanImage->fecha_registro."',
        '".$objBeanImage->usuario_registro."',
        '".$objBeanImage->fecha_actualizacion."',
        '".$objBeanImage->usuario_actualizacion."',
        '".$objBeanImage->estado_migracion."',
        '".$objBeanImage->fecha_migracion."',
        '".$objBeanImage->fecha_migracion_actualizacion."',
        '".$objBeanImage->imagen_padre."',
        '".$objBeanImage->procedencia."')";
        $result = $this->connection->query($sql);
        $objBeanImage->id = $this->connection->insert_id;
        return $objBeanImage;
    }
    
    public function getTypeImage($width){
        $returnValue = 0;
        if($width>0){
            $sql ="SELECT * FROM default_cms_tipo_imagen WHERE ancho = '".$width."'";
            //var_dump($sql);
            $result = $this->connection->query($sql);
             while ($row = $result->fetch_object()) {
                 $returnValue = $row->id;
             }
        }
        return $returnValue;
    }
}
?>
