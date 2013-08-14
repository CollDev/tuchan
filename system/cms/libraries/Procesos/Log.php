<?php

set_time_limit(TIME_LIMIT);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Log {

    public function erroLog($log) {
        if (is_array($log)) {
            foreach ($log as $puntero => $value) {
                umask(0);
                $ruta = $this->config->item('path:log') . date("d-m-Y") . ".txt";
                //error_log($log);
                $fp = fopen($ruta, "a+");
                fwrite($fp, date('H:i:s') . " > " . $puntero."=>".$value . PHP_EOL);
                fclose($fp);
            }
        } else {
            umask(0);
            $ruta = $this->config->item('path:log') . date("d-m-Y") . ".txt";
            //error_log($log);
            $fp = fopen($ruta, "a+");
            fwrite($fp, date('H:i:s') . " > " . $log . PHP_EOL);
            fclose($fp);
        }
    }
    
    public function xmlLog($log) {
        if (is_array($log)) {
            
                umask(0);
                $ruta = $this->config->item('path:log') . date("d-m-Y") . "_xml.txt";
                
                $fp = fopen($ruta, "a+");
                fwrite($fp, date('H:i:s') . " > " .json_encode($log). PHP_EOL);
                fclose($fp);
            
        } else {
            umask(0);
            $ruta = $this->config->item('path:log') . date("d-m-Y") . "_xml.txt";
            //error_log($log);
            $fp = fopen($ruta, "a+");
            fwrite($fp, date('H:i:s') . " > " . $log . PHP_EOL);
            fclose($fp);
        }
    }
}

?>
