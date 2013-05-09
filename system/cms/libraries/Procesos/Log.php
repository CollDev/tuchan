<?php
set_time_limit(TIME_LIMIT);
if (!defined('BASEPATH'))     exit('No direct script access allowed');

class Log {
    public function erroLog($log){   
        umask(0);
        $ruta=$this->config->item('path:log').date("d-m-Y").".txt";
        //  //error_log($ruta);
        $fp = fopen($ruta,"a+");
        fwrite($fp,date('H:i:s')." > ".$log.PHP_EOL);
        fclose($fp);
    }
}

?>
