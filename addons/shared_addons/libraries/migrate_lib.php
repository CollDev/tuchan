<?php
/**
 * Complements cmsapi
 *
 * @author Joe Robles <joe.robles.pdj@gmail.com>
 */
class migrate_lib extends MX_Controller {
    
    function __construct()
    {
        $this->load->model('videos/videos_m');
        $this->load->library('OoyalaApi', array('apiKey' => OOYALA_API_KEY, 'secretKey' => OOYALA_API_SECRET, 'options' => array()));
    }
    
    public function getLiquidApi()
    {
        $xml = shell_exec('curl http://fast.api.liquidplatform.com/2.0/medias/?key=301c1e9aaadb739b6872abd1fce8ecda');
        $return = $this->xml2array($xml);
        
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($return);
    }

    public function getVideosList()
    {
        return $this->videos_m->getAll();
    }
    
    public function upload($file)
    {
        $form_post = $this->input->post();
        
        $post = array(
            "name" => $form_post['titulo'],
            "file_name" => $file['video']['name'],
            "asset_type" => "video",
            "file_size" => $file['video']['size'],
            "post_processing_status" => "live"
        );
        $preext = explode('.', $file['video']['name']);
        $ext = end($preext);
        $arrayExt = explode('|', 'mp4|mpg|flv|avi|wmv');
        
        if (in_array($ext, $arrayExt)) {
            if ($file['video']['size'] > 0 && $file['video']['size'] <= 2147483648) {
                if (file_exists($file['video']['tmp_name']) && strlen(trim($file['video']['name'])) > 0) {
                    $response_one = $this->ooyalaapi->post('assets', $post);
                    $response_two = $this->ooyalaapi->get('assets/' . $response_one->embed_code . '/uploading_urls');
                    $this->upload_curl($response_two[0], $file['video']['tmp_name']);
                    $response_three = $this->ooyalaapi->put('assets/' . $response_one->embed_code . '/upload_status', array("status" => "uploaded"));
                    
                    if (isset($response_three->status) && $response_three->status == 'uploaded') {
                        $return = array(
                            'type' => 'success',
                            'title' => 'Video subido con éxito.',
                            'message' => '',
                            'embed_code' => $response_one->embed_code
                        );
                    } else {
                        $return = array(
                            'type' => 'danger',
                            'title' => 'Error interno.',
                            'message' => 'Por favor repórtelo al área.',
                        );
                    }
                } else {
                    $return = array(
                        'type' => 'danger',
                        'title' => 'Error al subir video.',
                        'message' => 'Por favor vuelva a intentarlo en unos minutos.',
                    );
                }
            } else {
                $return = array(
                    'type' => 'info',
                    'title' => 'Video muy extenso',
                    'message' => 'El tamaño del video supera el permitido de 2GB.',
                );
            }
        } else {
            $return = array(
                'type' => 'info',
                'title' => 'Formato no permitido',
                'message' => 'Por favor suba un video: mp4,mpg,flv,avi,wmv.',
            );
        }

        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($return);
    }
    
    public function upload_curl($upload_url, $file)
    {
        $ch = curl_init($upload_url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('file' => "@$file"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $postResult = curl_exec($ch);
        curl_close($ch);
        if ($postResult) {
            return true;
        } else {
            return $postResult;
        }
    }
    
    public function verificar_estado_video($embed_code)
    {
        $response = $this->ooyalaapi->get('assets/' . $embed_code);
        
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($response);
    }
    
    private function xml2array($xml){ 
        $opened = array();
        $opened[1] = 0;
        $xml_parser = xml_parser_create();
        xml_parse_into_struct($xml_parser, $xml, $xmlarray);
        $array = array_shift($xmlarray);
        unset($array["level"]);
        unset($array["type"]);
        $arrsize = sizeof($xmlarray);
        for ($j = 0; $j < $arrsize; $j++) {
            $val = $xmlarray[$j];
            switch ($val["type"]) {
                case "open":
                    $opened[$val["level"]] = 0;
                    break;
                
                case "complete":
                    $index = "";
                    for ($i = 1; $i < ($val["level"]); $i++) {
                        $index .= "[" . $opened[$i] . "]";
                    }
                    $path = explode('][', substr($index, 1, -1));
                    $value = &$array;
                    foreach ($path as $segment) {
                        $value = &$value[$segment];
                    }
                    $value = $val;
                    unset($value["level"]);
                    unset($value["type"]);
                    if ($val["type"] == "complete") {
                        $opened[$val["level"]-1]++;
                    }
                    break;
                    
                case "close":
                    if (isset($opened[$val["level"]-1]) && $opened[$val["level"]-1] !== '') {
                        $opened[$val["level"]-1]++;
                        unset($opened[$val["level"]]);
                    }
                    break;
            }
        }
        
        return $array;
    }
    
    public function wget()
    {
        $route = "/tmp/" . $_GET['filename'];
        file_put_contents($route, fopen($_GET['url'], 'r'));
        if (file_exists($route)) {
            echo 'downloaded';
        } else {
            echo 'no downloaded';
        }
        if (unlink($route)) {
            echo 'deleted';
        } else {
            echo 'not deleted';
        }
    }
    
//    //url para reproducir
//    $response_five = $this->ooyalaapi->get('assets/' . $response_one->embed_code . '/streams');
}