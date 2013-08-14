<?php

set_time_limit(TIME_LIMIT);

class curlProceso extends MX_Controller {

    function __construct() {
        $this->load->library("procesos_lib");
        $this->load->library("Procesos/log");
    }

    function index() {
        
    }

    public function corteVideoXId($id_padre, $id_hijo, $inicio, $duracion) {
        Log::erroLog("ini en curl - corteVideoXId" . $id_padre);
        $this->procesos_lib->corteVideoXId($id_padre, $id_hijo, $inicio, $duracion);
        Log::erroLog("fin en curl - corteVideoXId" . $id_padre);
    }

    public function procesoVideosXId($id) {
        Log::erroLog("ini en curl - procesoVideosXId" . $id);
        $this->procesos_lib->procesoVideosXId($id);
        Log::erroLog("fin en curl - procesoVideosXId" . $id);
    }

    public function uploadVideosXId($id) {
        Log::erroLog("ini en curl - uploadVideosXId" . $id);
        $this->procesos_lib->uploadVideosXId($id);
        Log::erroLog("fin en curl - uploadVideosXId" . $id);
    }

    public function updateMediaVideosXId($id, $media) {
        Log::erroLog("ini en curl - updateMediaVideosXId" . $id);
        $this->procesos_lib->updateMediaVideosXId($id, $media);
        Log::erroLog("fin en curl - updateMediaVideosXId" . $id);
    }

    public function updateEstadoVideosXId($id, $ev, $el) {
        $this->procesos_lib->updateEstadoVideosXId($id, $ev, $el);
    }

    public function updateErrorVideosXId($id) {
        
    }

    public function setReproduccionesVideosXId($id, $cant) {
        Log::erroLog("setReproduccionesVideosXId " . $id . ", cant: " . $cant);
        $this->procesos_lib->setReproduccionesVideosXId($id, $cant);
    }

    public function verificaVideosLiquidXId($id) {
        $this->procesos_lib->verificaVideosLiquidXId($id);
    }

    public function generarMiCanal() {
        $this->procesos_lib->generarMiCanal();
    }

    public function estadosVideos() {
        $this->procesos_lib->estadosVideos();
    }

    public function datosVideos($id) {
        $this->procesos_lib->datosVideos($id);
    }

    public function datosProFun() {
        $this->procesos_lib->showProFun();
    }

    public function datosLog($date) {
        $this->procesos_lib->showLog($date);
    }
    
    public function datosXml($date) {
        $this->procesos_lib->showXml($date);
    }

    public function generarDetalleVideosXId($id, $mongo_id) {
        $this->procesos_lib->generarDetalleVideosXId($id, $mongo_id);
    }

    public function actualizarSecciones6789() {
        $this->procesos_lib->actualizarSecciones6789();
    }

    public function actualizarPortadasMiCanal() {
        $this->procesos_lib->actualizarPortadasMiCanal();
    }

    public function actualizarPortadasMiCanalXId($id) {
        $this->procesos_lib->actualizarPortadasMiCanalXId($id);
    }

    public function actualizarPesoSeccion($id, $peso) {
        $this->procesos_lib->actualizarPesoSeccion($id, $peso);
    }

    public function actualizarVideos() {
        $this->procesos_lib->actualizarVideos();
    }

    public function actualizarVideosXId($id) {
        $this->procesos_lib->actualizarVideosXId($id);
    }
    
    public function actualizarPadreVideos(){
        $this->procesos_lib->actualizarPadreVideos();
    }
    
    public function sincronizarLibVideo($id){
        $this->procesos_lib->sincronizarLibVideo($id);
    }
    
    public function actualizarCantidadVideosXVideosId($id){
        $this->procesos_lib->actualizarCantidadVideosXVideosId($id);
    }

    public function generarCanalesXId($id) {
        $this->procesos_lib->generarCanalesXId($id);
    }

    public function actualizarGrupoMaestros() {
        $this->procesos_lib->actualizarGrupoMaestros();
    }

    public function generarGrupoMaestrosXId($tgm, $id) {
        $this->procesos_lib->generarGrupoMaestrosXId($tgm, $id);
    }

    public function generarProgramasXId($id) {
        $this->procesos_lib->generarProgramasXId($id);
    }

    public function activarVideosXId($id) {
        $this->procesos_lib->activarVideosXId($id);
    }

    public function desactivarVideosXId($id) {
        $this->procesos_lib->desactivarVideosXId($id);
    }

    public function publishedVideosXId($id) {
        $this->procesos_lib->publishedVideosXId($id);
    }

    public function unpublishedVideosXId($id) {
        $this->procesos_lib->unpublishedVideosXId($id);
    }

    public function generarPortadasMiCanalXId($id) {
        $this->procesos_lib->generarPortadasMiCanalXId($id);
    }

    public function generarSeccionesMiCanalXSeccionId($id) {
        $this->procesos_lib->generarSeccionesMiCanalXSeccionId($id);
    }

    public function publicarPendientes() {
        $this->procesos_lib->publicarPendientes();
    }

    public function obtenerVideoYoutube($id, $vi) {
        $this->procesos_lib->obtenerVideoYoutube($id, $vi);
    }

    public function videoYoutube($id) {
        $this->procesos_lib->videoYoutube($id);
    }

    public function limpiarMongo() {
        $this->procesos_lib->limpiarMongo();
    }

    public function getDB() {
        echo "<pre>";
        print_r($this->db);
        echo "</pre>";

        echo "********************************************************************";

        echo "<pre>";
        print_r($this->config);
        echo "</pre>";
    }

    public function getphpinfo() {
        phpinfo();
    }

    public function publicarPorIbope() {
        $this->procesos_lib->publicarPorIbope();
    }

    public function generarVideosXId($id) {
        $this->procesos_lib->generarVideosXId($id);
    }

    public function limpiarUploadVideo() {
        $this->procesos_lib->limpiarUploadVideo();
    }
    
    public function actualizarVersion($tipo,$version){
        $this->procesos_lib->actualizarVersion($tipo,$version);
    }
    
    public function postbackliquid() {
        $xml = @file_get_contents('php://input');    
        
        $this->procesos_lib->postbackliquid($xml);

       
    }
    
    public function postliquid() {
        $url = $_SERVER['HTTP_HOST']."/curlproceso/postbackliquid";
        $post = "<MediaEvent>
                <EventType>INSERT</EventType>
                    <Media>
                        <IdMedia>321321ec11c33c23</IdMedia>
                        <Title>adasdas sad sad sa sa das as</Title>
                    </Media>
                </MediaEvent>";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));

        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        echo $result;        
        echo $info['http_code'];

//        if ($info['http_code'] == '200') {
//            $resultado = json_decode($result);
//        } else {
//            $resultado = array();
//        }
    }

}