<?php
class curlProceso extends MX_Controller {
    
    function __construct() {
        $this->load->library("procesos_lib");
    }
    
    function index(){
       
    }
   
    public function procesoVideosXId($id){
        error_log("ini en curl - procesoVideosXId".$id);
        $this->procesos_lib->procesoVideosXId($id);
        error_log("fin en curl - procesoVideosXId".$id);
    }
}
?>