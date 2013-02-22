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
       $mysqli->query("update default_cms_videos set estado_liquid=".$valor." where id=".$id); 
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

}
?>
