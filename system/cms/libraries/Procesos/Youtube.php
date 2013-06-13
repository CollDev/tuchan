<?php

class Youtube {

    function descargaVideo($video_id) {
        
    }

    function url_exists($url) {

        echo $url . "<br>";


        if (file_get_contents($url, FALSE, NULL, 0, 0) === false)
            return false;
        return true;
    }

    function curlGet($URL) {
        $ch = curl_init();
        $timeout = 3;
        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        /* if you want to force to ipv6, uncomment the following line */
        //curl_setopt( $ch , CURLOPT_IPRESOLVE , CURLOPT_IPRESOLVE_V6);
        $tmp = curl_exec($ch);
        curl_close($ch);
        return $tmp;
    }

    function obtenerVideo($my_id) {


        $my_video_info = 'http://www.youtube.com/get_video_info?&video_id=' . $my_id;
        $my_video_info = self::curlGet($my_video_info);

        echo $my_video_info;
        
        /* TODO: Check return from curl for status code */

        var_dump(parse_str($my_video_info));
    }

}

?>
