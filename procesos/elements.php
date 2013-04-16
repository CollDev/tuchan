<?
include ("util/conn_mysql.php");

class Elements{
        var $conexionmysql="";
        var $rutabase="/home/user/Escritorio/micanal/";

        function __construct() {
            
           $this->conexionmysql = new Conexion();       
        }

        function CargarImagenes(){

        $query="SELECT id,imagen FROM default_cms_imagenes WHERE procedencia=2";
        $resquery = $this->conexionmysql->setConsulta($query);
    
        if ($resquery) {
            while ($row = $resquery->fetch_object()) {

                $file=$this->rutabase.$row->imagen;

                $path_image_element=$this->elemento_upload($row->id,$file,'cms.micanal.pe');

                echo $path_single_element;

                $array_path = explode("/", $path_image_element);

                if($array_path[0] == "dev.e.micanal.e3.pe"){
                    unset($array_path[0]);
                }
                $path_single_element = implode('/', $array_path);

                $query2="UPDATE default_cms_imagenes SET imagen='".$path_single_element."',procedencia=0 WHERE id=".$row->id;
                $this->conexionmysql->setConsulta($query2);
                //end();
            }   
        }
    }

    /**
     * 
     * @param type $fid id  de la imagen local de la BD
     * @param type $file nombre de la imagen [ruta absuluta /var/...]
     * @param type $mensaje 
     * @return string  name, direccion real de la imagen dominio
     */

        function elemento_upload($fid, $file, $mensaje = 'cms.micanal.pe') {
        $url = "http://dev.e3.pe/index.php/api/v1";
        $remotedir = $this->elemento_basepath($fid,"dev.e.micanal.e3.pe");
        $ext = explode('.', $file);
        $infofile = urlencode(file_get_contents($file)); //encode_content_file($file);
        $data = array(
            'apikey' => '590ee43e919b1f4baa2125a424f03cd160ff8901',
            'name' => $fid . '.' . $ext[1],
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
        curl_close($ch);
        return $data['ruta'] . $data['name'];
    }

    function elemento_basepath($fid, $container = 'dev.e.micanal.e3.pe') {
//    $container = md_elemento_container($ext);
        $filename = str_pad($fid, 8, "0", STR_PAD_LEFT);
        $dir_split_file = preg_split('//', substr($filename, 0, strlen($filename) - 3), -1, PREG_SPLIT_NO_EMPTY);
        $scheme_dir = implode('/', $dir_split_file);
        return $container . '/' . $scheme_dir . '/';
    }
	
   

}
$clase = new Elements();
$clase->CargarImagenes();
?>