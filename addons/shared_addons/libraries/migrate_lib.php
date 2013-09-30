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
    
//    //url para reproducir
//    $response_five = $this->ooyalaapi->get('assets/' . $response_one->embed_code . '/streams');
}