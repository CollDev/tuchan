<?php
set_time_limit(TIME_LIMIT);

class Youtube {

    function descargaVideo($id, $data) {

        $filePath = PATH_VIDEOS . $id . ".mp4";

        if (is_readable($filePath)) {
            return TRUE;
        } else {
            
            $i = 0 ;
            
            DONWLOADVIDEO:

            $fp = fopen($filePath, "w");
            $ruta = $data['url'] . '&signature=' . $data['sig'];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $ruta);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);

            if (is_readable($filePath)) {
                Log::erroLog("Termino descarga de Video id: " . $id);
                return TRUE;
            } else {
                if($i < 5){
                    Log::erroLog("Error de descarga reintento para video id: " . $id . " intento ".$i++);
                    goto DONWLOADVIDEO;
                }  else {
                    return FALSE;
                }
                
            }
        }
    }

    function url_exists($url) {
        if (file_get_contents($url, FALSE, NULL, 0, 0) === false) {
            return false;
        }
        return true;
    }

    function curlGet($url) {
        $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
        $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
        $header[] = "Cache-Control: max-age=0";
        $header[] = "Connection: keep-alive";
        $header[] = "Keep-Alive: 300";
        $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
        $header[] = "Accept-Language: en-us,en;q=0.5";
        $header[] = "Pragma: ";

        $browsers = array("Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.3) Gecko/2008092510 Ubuntu/8.04 (hardy) Firefox/3.0.3", "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1) Gecko/20060918 Firefox/2.0", "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3", "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; .NET CLR 3.0.04506)");
        $choice2 = array_rand($browsers);
        $browser = $browsers[$choice2];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_USERAGENT, $browser);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        curl_setopt($ch, CURLOPT_HTTPGET, true);

        $tmp = curl_exec($ch);
        curl_close($ch);
        return $tmp;
    }

    function obtenerVideo($v) {
         Log::erroLog($v);
        $url_info = 'http://www.youtube.com/get_video_info?&video_id=' . $v;
         Log::erroLog($url_info);

        $contador = 0;

        INICIO:
         Log::erroLog("contador : " . $contador);

        $page = self::curlGet($url_info);

         Log::erroLog("page : " . $page);

        parse_str($page, $varia);

        if (empty($varia['url_encoded_fmt_stream_map'])) {
            if ($contador == 10) {
                sleep(10);
                return FALSE;
            } else {
                $contador++;
                goto INICIO;
            }
        }

        $streams = $varia['url_encoded_fmt_stream_map'];

        $streams = explode(',', $streams);
        $format = "video/mp4";
        foreach ($streams as $key => $stream) {
            parse_str($stream, $data); //decode the stream

            if (stripos($data['type'], $format) !== false) {
                unset($streams[$key]);
            }
        }


        $quality = array(1 => 'hd1080', 2 => 'hd720', 3 => 'medium');

        $videostream = null;

        foreach ($quality as $value) {
            foreach ($streams as $stream) {
                parse_str($stream, $data);                                 
                if ($data["quality"] == $value) {                   
                    $videostream = $stream;
                    break 2;
                }
            }
        }
        parse_str($videostream, $data2);
        return $data2;
    }
}

?>
