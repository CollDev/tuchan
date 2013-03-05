<?

class Liquid{

  var $webUrl = 'http://webtv.liquidplatform.com/2.0/uploadMedia';
  //var $webUrl = 'http://192.168.1.34/videos/receptliquid.php';

  
  var $apiKey = '3b540fec2d40b445f91432821079128d';
  var $apiUrl = 'http://api.liquidplatform.com/2.0';
  //var $pathf  = $_SERVER["DOCUMENT_ROOT"]."uploads/videos/";


  function postXML($url, $post) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
      curl_setopt($ch, CURLOPT_URL,$url);
      curl_setopt($ch, CURLOPT_POST, TRUE);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
      curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml")); 
      $result = curl_exec($ch);
      curl_close ($ch);
      return $result;
   }

  function getXml($url){

     $result=file_get_contents($url);

     return $result;
  }


  function updatePublishedMedia($mediaId){
    $mediaId=trim($mediaId);

    $fecha=date('Y-m-d H:i:s');
    $date = date("Y-m-d\TH:i:sP", strtotime($fecha)); 

    $post = "<Media><published>true</published><publishDate>".$date."</publishDate></Media>";
    $url = $this->apiUrl . "/medias/".$mediaId."?key=".$this->apiKey; 
    echo $url."<br>";
    return $this->postXML($url, $post);
  }

  function updatePublishedMediaNode($mediaId, $datos){ 


    $mediaId=trim($mediaId);

    $tags = ''; 
    $date = date("Y-m-d\TH:i:sP", strtotime($arrdatos['fecha'])); 
    $post = "<Media><published>true</published>" . 
    "<description>descripcion</description><highlighted>false</highlighted>" . 
    "<publishDate>".$date."</publishDate><title>titulo</title>" . 
    "<channelId>2</channelId>" . 
      $tags . 
    "</Media>"; 
    $url = $this->apiUrl . "/medias/".$mediaId."?key=".$this->apiKey; 
    //echo $url."<br>";
    return $this->postXML($url, $post); 
   }


  function updateTitleMediaNode($mediaId, $datos){ 

    $mediaId=trim($mediaId);

    $tags = ''; 
    $date = date("Y-m-d\TH:i:sP", strtotime($datos->fecha)); 
    $post = "<Media>" . 
    "<description>{$datos->legend}</description>" . 
    "<title>{$datos->title}</title>" . 
      $tags . 
    "</Media>"; 
    $url = $this->apiUrl . "/medias/".$mediaId."?key=".$this->apiKey; 
    echo $url."<br>";
    return $this->postXML($url, $post); 
   }

  function uploadVideoLiquid($id_video,$ubi){
        $conexion = new Conexion();
        //$url="http://192.168.1.34/videos/receptliquid.php";

        //echo $url."<br>";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER,0);
        curl_setopt($ch, CURLOPT_VERBOSE,0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($ch, CURLOPT_URL, $this->webUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array ("Content-Type: multipart/form-data;")); 
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);    
        curl_setopt($ch, CURLOPT_TIMEOUT,1000);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 7);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
     

        $post = array(
            "file"=>"@".$ubi."uploads/videos/".$id_video.".mp4",
            "token"=>$this->apiKey
        );

        //echo "<br>";
        //print_r($post);
       // echo "<br>";

        curl_setopt($ch,CURLOPT_POSTFIELDS, $post);

        $conexion->updateEstadoVideosLiquid($id_video,3);

        $response = curl_exec($ch);
        curl_close($ch); 

        $mediaxml = new SimpleXMLElement($response);

        /*
        $jsonmedia=json_encode($media);
        echo json_encode($media);
        */

        $mediaarr=json_decode(json_encode($mediaxml),true);

        $media=$mediaarr["media"]["@attributes"]["id"];

        echo "<br>media: ".$media."<br>";

        if($media!=""){

          $conexion->updateEstadoVideosLiquid($id_video,4);
          $conexion->updateMediaVideosLiquid($id_video,$media);

          $ret["ret"]="true";
          $ret["med"]=trim($media);
          //return true;
          return $ret;

        }else{

          $conexion->updateEstadoVideosLiquid($id_video,2);
          $ret["ret"]="false";
          //return false;
          return $ret;

        }               
        echo "status: ".$mediaarr["status"];     
  }

  function obtenerDatosMedia($mediaId){

    $url = $this->apiUrl . "/medias/".$mediaId."?key=".$this->apiKey."&filter=id;published;status;numberOfViews;files";     
    echo $url."<br>";
    $response = $this->getXml($url);    
    $mediaxml = new SimpleXMLElement($response);
    //$jsonmedia=json_encode($media); 
    $mediaarr=json_decode(json_encode($mediaxml),true);
    
    //print_r($mediaarr);

    return $mediaarr;
  }


  function obtenerImagenesMedia($mediaId){
    $url = $this->apiUrl . "/medias/".$mediaId."?key=".$this->apiKey."&filter=id;thumbs";     
    $response = $this->getXml($url);    
    $mediaxml = new SimpleXMLElement($response);
    //$jsonmedia=json_encode($media); 
    $mediaarr=json_decode(json_encode($mediaxml),true);
    return $mediaarr;
  }

}

/*
    $liquid = new Liquid();
    $return = $liquid->validarPublishedMedia("ff11b61b2b9a4bab377f0c484db7a4ec");
    echo var_dump($return);
  
  */
   
/*
$arr1 = array('legend' => 'Titulo video pruebaaaaa');
$e = (object) $arr1;

$arr2 = array('fecha' => date('Y-m-d H:i:s'), 'title' => 'Descripcion video');
$n = (object) $arr2;
*/

/*

$arrdatos['fecha']  = date('Y-m-d H:i:s');
$arrdatos['title']  = '222222222';
$arrdatos['legend'] = '3333333';


$datos = (object) $arrdatos;

  $return=$liquid->updatePublishedMediaNode("c7028d2051a8f2e503f34d565138482d ",$datos);
echo $return;
*/

//33aca2812a57ea2d6b82fb7137b2544e
//4de69dff9ba1a9dfe8724d35fa8fd59d
//253107993bab5929e4c98bad756c27d8
?>
