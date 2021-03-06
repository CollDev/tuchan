<?php
/**
 * Complements cmsapi
 *
 * @author Joe Robles <joe.robles.pdj@gmail.com>
 */
class migrate_lib extends MX_Controller {
    
    private $base = "/tmp/";
    
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

    public function getVideosList($key_canal)
    {
        return $this->videos_m->getAll($key_canal);
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
        if (isset($_POST['filename']) && $_POST['filename'] !== '' && isset($_POST['url']) && $_POST['url'] !== '') {
            $route = $this->base . $_POST['filename'];
            file_put_contents($route, fopen($_POST['url'], 'r'));//Download
            if (file_exists($route)) {
                $response_one = $this->ooyalaapi->post('assets', array(
                        "name" => $_POST['titulo'],
                        "file_name" => $_POST['filename'],
                        "asset_type" => "video",
                        "file_size" => filesize($route),
                        "post_processing_status" => "live"
                    )
                );//Request space
                $response_two = $this->ooyalaapi->get('assets/' . $response_one->embed_code . '/uploading_urls');//upload url request
                
                $file_name_with_full_path = realpath($this->base . $_POST['filename']);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $response_two[0]);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, array('file_contents'=>'@'.$file_name_with_full_path));
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $result = curl_exec($ch);
                curl_close($ch);//upload
                
                $response_three = $this->ooyalaapi->put('assets/' . $response_one->embed_code . '/upload_status', array("status" => "uploaded"));//set as uploaded

                if (isset($response_three->status) && $response_three->status == 'uploaded') {
                    unlink($file_name_with_full_path);//remove file
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
                    'title' => 'Error.',
                    'message' => 'Video no descargado.',
                );
            }
        } else {
            if (isset($_POST['filename'])) {
                $return = array(
                    'type' => 'info',
                    'title' => 'Dato no ingresado.',
                    'message' => 'Ingrese una url.',
                );
            } else if (isset($_POST['url'])) {
                $return = array(
                    'type' => 'info',
                    'title' => 'Dato no ingresado.',
                    'message' => 'Ingrese un nombre de archivo.',
                );
            } else {
                $return = array(
                    'type' => 'info',
                    'title' => 'Dato no ingresado.',
                    'message' => 'No se ha ingresado una url ni un nombre de archivo.',
                );
            }
        }
        
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($return);
    }
    
    public function upload()
    {
        if (isset($_GET['filename']) && $_GET['filename'] !== '' && isset($_GET['url']) && $_GET['url'] !== '') {
            $file_name_with_full_path = realpath($this->base . $_GET['filename']);

            $post = array('file_contents'=>'@'.$file_name_with_full_path);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $_GET['url']);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($ch);
            curl_close($ch);
        } else {
            if (isset($_GET['filename'])) {
                echo 'ingrese una url';
            } else if (isset($_GET['url'])) {
                echo 'ingrese un nombre de archivo';
            } else {
                echo 'no se ha ingresado una url ni un nombre de archivo';
            }
        }
    }
    
    public function receive()
    {
        $uploaddir = '/tmp/';
        $uploadfile = $uploaddir . basename($_FILES['file_contents']['name']);
        
	if (move_uploaded_file($_FILES['file_contents']['tmp_name'], $uploadfile)) {
            $return = array(
                'type' => 'success',
                'title' => 'Video subido.',
                'message' => 'Finalizado satisfactoriamente.',
            );
	} else {
            $return = array(
                'type' => 'danger',
                'title' => 'Error.',
                'message' => 'Video no subido.',
            );
	}
        
        
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($return);
    }

    public function actualizar_video()
    {
        $response = $this->ooyalaapi->get('assets/' . $_POST['embed_code'] . '/streams');
        $this->videos_m->updateOoyala($_POST['id'], $response[1]->url, $response[0]->url, $_POST['embed_code']);
    }
}