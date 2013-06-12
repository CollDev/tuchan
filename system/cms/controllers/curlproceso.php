<?php
set_time_limit(TIME_LIMIT);

class curlProceso extends MX_Controller {
    
    function __construct() {
        $this->load->library("procesos_lib");
        $this->load->library("Procesos/log");
    }
    
    function index(){       
    }
   
    public function corteVideoXId($id_padre, $id_hijo, $inicio, $duracion) {
        Log::erroLog("ini en curl - corteVideoXId".$id_padre);
        $this->procesos_lib->corteVideoXId($id_padre, $id_hijo, $inicio, $duracion);
        Log::erroLog("fin en curl - corteVideoXId".$id_padre);
    }
    
    public function procesoVideosXId($id){
        Log::erroLog("ini en curl - procesoVideosXId".$id);
        $this->procesos_lib->procesoVideosXId($id);
        Log::erroLog("fin en curl - procesoVideosXId".$id);
    }
    
    public function uploadVideosXId($id){        
         Log::erroLog("ini en curl - uploadVideosXId".$id);
        $this->procesos_lib->uploadVideosXId($id);
         Log::erroLog("ini en curl - uploadVideosXId".$id);
    }
    
    public function updateMediaVideosXId($id,$media){
         Log::erroLog("ini en curl - updateMediaVideosXId".$id);
         $this->procesos_lib->updateMediaVideosXId($id,$media);
         Log::erroLog("fin en curl - updateMediaVideosXId".$id);
    }   
    
    public function updateErrorVideosXId($id){
        
    }
    
    public function setReproduccionesVideosXId($id,$cant){
        Log::erroLog("setReproduccionesVideosXId ".$id. ", cant: ".$cant);
        $this->procesos_lib->setReproduccionesVideosXId($id,$cant);
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
    
    public function  generarDetalleVideosXId($id, $mongo_id){
        $this->procesos_lib->generarDetalleVideosXId($id, $mongo_id);
    }           
    
    public function actualizarSecciones6789(){
        $this->procesos_lib->actualizarSecciones6789();
    }    
    
    public function actualizarPortadasMiCanal(){
        $this->procesos_lib->actualizarPortadasMiCanal();
    }
    
    public function actualizarPesoSeccion($id,$peso){
        $this->procesos_lib->actualizarPesoSeccion($id,$peso);
    }
    
    public function actualizarVideos(){
        $this->procesos_lib->actualizarVideos();
    }
    
    public function actualizarVideosXId($id){
        $this->procesos_lib->actualizarVideosXId($id);
    }
            
    public  function generarCanalesXId($id){
        $this->procesos_lib->generarCanalesXId($id);
    }
    
    public function desactivarVideosXId($id){
        $this->procesos_lib->desactivarVideosXId($id);
    }
    
    public function activarVideosXId($id){
        $this->procesos_lib->activarVideosXId($id);
    }
    
    public function generarPortadasMiCanalXId($id){
        $this->procesos_lib->generarPortadasMiCanalXId($id);
    }
    
    public function generarSeccionesMiCanalXSeccionId($id){
        $this->procesos_lib->generarSeccionesMiCanalXSeccionId($id);
    }
    
    public function publicarPendientes(){
        $this->procesos_lib->publicarPendientes();
    }
    
    public function obtenerVideoYoutube($id){
        $this->procesos_lib->obtenerVideoYoutube($id);
    }
    
    public function getDB(){
        echo "<pre>";
        print_r($this->db);
        echo "</pre>";
        
        echo "********************************************************************";
        
        echo "<pre>";
        print_r($this->config);
        echo "</pre>";        
    }
    
    public function getphpinfo(){
        phpinfo();
        
    }
    
}
?>