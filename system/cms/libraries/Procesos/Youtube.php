<?php


class Youtube {

    function descargaVideo($video_id){
        
    }
    
    function url_exists($url) {
        
        echo $url. "<br>";
        
        
        if(file_get_contents($url, FALSE, NULL, 0, 0) === false) return false;
        return true;
    }
    
    
    function obtenerVideo($id) {
        
        
        $page = @file_get_contents('http://www.youtube.com/get_video_info?&video_id=' . $id);

        preg_match('/token=(.*?)&thumbnail_url=/', $page, $token);

        $token = urldecode($token[1]);

//        $get = $title->video_details;

        $url_array = array("http://youtube.com/get_video?video_id=" . $id . "&t=" . $token,
            "http://youtube.com/get_video?video_id=" . $id . "&t=" . $token . "&fmt=18");

        if (self::url_exists($url_array[1]) === true) {
            $file = get_headers($url_array[1]);
        } elseif (self::url_exists($url_array[0]) === true) {
            $file = get_headers($url_array[0]);
        }

        print_r($file);
        
    }
}

?>
