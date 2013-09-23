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
            "post_processing_status" => "paused"
        );
        $response_one = $this->ooyalaapi->post('assets', $post);
        $response_two = $this->ooyalaapi->get('assets/' . $response_one->embed_code . '/uploading_urls');
        $this->upload_curl($response_two[0], $file['video']['tmp_name']);
        $response_three = $this->ooyalaapi->put('assets/' . $response_one->embed_code . '/upload_status', array("status" => "uploaded"));
        
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($response_three);
    }
    
    public function upload_curl($upload_url, $file)
    {
        $ch = curl_init($upload_url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('file' => "@$file"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $postResult = curl_exec($ch);
        curl_close($ch);
        if ($postResult != '') {
            return true;
        } else {
            return false;
        }
    }
}