<?php
set_time_limit(2000);

class curlProceso extends MX_Controller {
    
    function __construct() {
        $this->load->library("procesos_lib");
    }
    
    function index(){
       
    }
   
    public function procesoVideosXId($id){
        //error_log("ini en curl - procesoVideosXId".$id);
        $this->procesos_lib->procesoVideosXId($id);
        //error_log("fin en curl - procesoVideosXId".$id);
    }
    
    public function  generarMiCanal(){
        $this->procesos_lib->generarMiCanal();
    }
    
    public function generarCanal(){
        $this->procesos_lib->generarCanal();
    }
            
}
?>