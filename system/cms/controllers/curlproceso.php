<?php
set_time_limit(TIME_LIMIT);

class curlProceso extends MX_Controller {
    
    function __construct() {
        $this->load->library("procesos_lib");
        $this->load->library("Procesos/log");
    }
    
    function index(){
       
    }
   
    public function procesoVideosXId($id){
        Log::erroLog("ini en curl - procesoVideosXId".$id);
        $this->procesos_lib->procesoVideosXId($id);
        Log::erroLog("fin en curl - procesoVideosXId".$id);
    }
    
    public function uploadVideosXId($id){        
        $this->procesos_lib->uploadVideosXId($id);
    }
    
    
    public function verificaVideosLiquidXId($id){
        $this->procesos_lib->verificaVideosLiquidXId($id);
    }
    
    public function  generarMiCanal(){
        $this->procesos_lib->generarMiCanal();
    }
    
    public function generarCanal(){
        $this->procesos_lib->generarCanal();
    }
    
    public function estadosVideos(){
        $this->procesos_lib->estadosVideos();
    }
     
    public function datosVideos($id){
        $this->procesos_lib->datosVideos($id);
    }
    
    public function datosProFun(){
        $this->procesos_lib->showProFun();
    }
    
    public function datosLog($date){
        $this->procesos_lib->showLog($date);
    }
}
?>