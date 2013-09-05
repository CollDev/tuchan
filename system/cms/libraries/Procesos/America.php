<?php
//require_once('simple_html_dom.php');
set_time_limit(TIME_LIMIT);

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class America {

    function envioDatos($url, $urlpostback, $user, $pass, $id) {

        $cookie_file_path = "/tmp/cookies.txt";
        $post = array(
            'name' => $user,
            'pass' => $pass,
            'form_id' => 'user_login'
            );

        $headers[] = "Accept: */*";
        $headers[] = "Connection: Keep-Alive";

        $username = "americatv";
        $password = "ahw0EeweM0leec";

        $agent = "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.62 Safari/537.36";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);

       curl_exec($ch);
       curl_close($ch);
            
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_URL, $urlpostback.$id);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE );;
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);

        $content = curl_exec($ch);
        curl_close( $ch );
        
        Log::erroLog("Video comunicado a america: " . $id);


    
    }

}

?>
