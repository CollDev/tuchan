<?php
set_time_limit(TIME_LIMIT);

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ffprobe {
    private function get($filename, $prettify = false)
    {       
        $options = '-loglevel quiet -show_format -show_streams -print_format json';

        if ($prettify) {
            $options .= ' -pretty';
        }

       
        setlocale(LC_CTYPE, 'en_US.UTF-8');

       
        $json = json_decode(shell_exec(sprintf('ffprobe %s %s', $options,escapeshellarg($filename))));

        if (!isset($json->format)) {
            throw new Exception('Archivo no soportado');
        }      

        return $json;
    }
}

?>
