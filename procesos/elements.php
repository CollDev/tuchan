<?

class Elements{
	
    public function elemento_upload($id, $file, $mensaje = 'cms.micanal.pe') {
        $url = "http://dev.e3.pe/index.php/api/v1";
        $remotedir = $this->elemento_basepath($id);
        $ext = explode('.', $file);
        $infofile = urlencode(file_get_contents($file)); //encode_content_file($file);
        $data = array(
            'apikey' => '590ee43e919b1f4baa2125a424f03cd160ff8901',
            'name' => $id . '.' . $ext[1],
            'content' => $infofile,
            //'ruta' => 'files/' . $remotedir,
            'ruta' => $remotedir,
        );  

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, $mensaje);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        error_log(print_r($result,true));
        curl_close($ch);
        return $data['ruta'] . $data['name'];
    }

    public function elemento_basepath($fid, $container = 'dev.e.micanal.e3.pe') {
//    $container = md_elemento_container($ext);
        $filename = str_pad($fid, 8, "0", STR_PAD_LEFT);
        $dir_split_file = preg_split('//', substr($filename, 0, strlen($filename) - 3), -1, PREG_SPLIT_NO_EMPTY);
        $scheme_dir = implode('/', $dir_split_file);
        return $container . '/' . $scheme_dir . '/';
    }

}
?>