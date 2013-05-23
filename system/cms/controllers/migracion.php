<?php
set_time_limit(1500);

class Migracion extends MX_Controller {
   

    function __construct() {
        $this->load->model('micanal_mp');
        $this->load->model('canales_mp');
    }

    private function index() {
        
    }
    
    private function _setMicanalMongo(){
        $objmongo['tipo']="portada";
        $objmongo['nombre']="Portada Principal Mi Canal";
        $objmongo['estado']="1";
        $objmongo['canal']="Mi Canal";
        $objmongo['tipo_portadas_id']="1";
        $objmongo['alias']="";
        
        $id_mongo = $this->micanal_mp->setItemCollection($objmongo);
        
        $canales = $this->portadas_mp->getPortadasMiCanal();
        
        foreach ($canales as $value) {                    
             $this->micanal_mp->updateIdMongoPortadas($value->id, $id_mongo);            
        }
        
    }

    private function _setQuery($consultamysql) {
        try {
            $this->db->query($consultamysql);
        } catch (Exception $exc) {
            
        }
    }

    public function setRestoreBD() {
        self::procesoMigra("data.restore.sql");
        self::_setMicanalMongo();
    }

    public function setRestoreFechaBD($fecha) {
        self::procesoMigra($fecha . ".sql");
    }

    public function setBackupFechaBD() {
        $file = (PATH_SQL . "" . date("Y_m_d") . ".sql");
        //$file = "/home/idigital/Escritorio/prueba.sql";
        self::backupmysql($this->db->hostname, $this->db->username, $this->db->password, $this->db->database, $file);
        system('mysqldump --host=' . $this->db->hostname . ' --user=' . $this->db->username . ' --password=' . $this->db->password . '   ' . $this->db->database . ' > ' . $file);
    }

    public function procesoMigra($path) {
        try {

            $templine = "";

            echo '===  Inicio del proceso ===';

            $lines = file(PATH_SQL . "/" . $path);

            foreach ($lines as $line) {

                if (substr($line, 0, 2) == '--' || $line == '') {
                    continue;
                }

                $templine .= $line;
                if (substr(trim($line), -1, 1) == ';') {
                    self::_setQuery($templine);
                    $templine = '';
                }
            }

            echo '===  Termino el proceso ===';
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function backupmysql($host, $usuario, $passwd, $bd, $ruta) {


        $drop = FALSE;
        $truncate =TRUE;
//        $tablas = false; 
        
        $tablas = array(
            0 => 'core_sites',
            1 => 'core_users',
            2 => 'default_blog',
            3 => 'default_blog_categories',
            4 => 'default_ci_sessions',
            5 => 'default_cms_canales',
            6 => 'default_cms_categorias',
            7=> 'default_cms_comentarios',
            8=> 'default_cms_detalle_secciones',
            9=> 'default_cms_grupo_detalles',
            10 => 'default_cms_grupo_maestro_tags',
            11 => 'default_cms_grupo_maestros',
            12 => 'default_cms_imagenes',
            13 => 'default_cms_portadas',
            14 => 'default_cms_reglas',
            15 => 'default_cms_roles',
            16 => 'default_cms_secciones',
            17 => 'default_cms_tags',
            18 => 'default_cms_templates',
            19 => 'default_cms_tipo_canales',
            20 => 'default_cms_tipo_grupo_maestros',
            21 => 'default_cms_tipo_imagen',
            22 => 'default_cms_tipo_portadas',
            23 => 'default_cms_tipo_secciones',
            24 => 'default_cms_tipo_tags',
            25 => 'default_cms_tipo_videos',
            26 => 'default_cms_usuario_group_canales',
            27 => 'default_cms_usuarios',
            28 => 'default_cms_valorizaciones',
            29 => 'default_cms_video_tags',
            30 => 'default_cms_videos',
            31 => 'default_cms_visitas',
            32 => 'default_comments',
            33 => 'default_contact_log',
            34 => 'default_data_field_assignments',
            35 => 'default_data_fields',
            36 => 'default_data_streams',
            37 => 'default_email_templates',
            38 => 'default_file_folders',
            39 => 'default_files',
            40 => 'default_groups',
            41 => 'default_keywords',
            42 => 'default_keywords_applied',
            43 => 'default_migrations',
            44 => 'default_modules',
            45 => 'default_navigation_groups',
            46 => 'default_navigation_links',
            47 => 'default_page_chunks',
            48 => 'default_page_layouts',
            49 => 'default_pages',
            50 => 'default_permissions',
            51 => 'default_profiles',
            52 => 'default_redirects',
            53 => 'default_settings',
            54 => 'default_theme_options',
            55 => 'default_users',
            56 => 'default_variables',
            59 => 'default_widget_areas',
            60 => 'default_widget_instances',
            61 => 'default_widgets'
        );

        $create = false;

        $compresion = false;


        $conexion = mysql_connect($host, $usuario, $passwd)
                or die("No se puede conectar con el servidor MySQL: " . mysql_error());
        mysql_select_db($bd, $conexion)
                or die("No se pudo seleccionar la Base de Datos: " . mysql_error());
        /* Se busca las tablas en la base de datos */
        if (empty($tablas)) {
            $consulta = "SHOW TABLES FROM $bd;";
            $respuesta = mysql_query($consulta, $conexion)
                    or die("No se pudo ejecutar la consulta: " . mysql_error());
            while ($fila = mysql_fetch_array($respuesta, MYSQL_NUM)) {
                $tablas[] = $fila[0];
            }
        }
        /* Se crea la cabecera del archivo */
        $info['dumpversion'] = "1.1b";
        $info['fecha'] = date("d-m-Y");
        $info['hora'] = date("h:m:s A");
        $info['mysqlver'] = mysql_get_server_info();
        $info['phpver'] = phpversion();
        ob_start();
        print_r($tablas);
        $representacion = ob_get_contents();
        ob_end_clean();
        preg_match_all('/(\[\d+\] => .*)\n/', $representacion, $matches);
        $info['tablas'] = implode(";  ", $matches[1]);
        $dump = <<<EOT
# +===================================================================
# |
# | Generado el {$info['fecha']} a las {$info['hora']} 
# | Servidor: {$_SERVER['HTTP_HOST']}
# | MySQL Version: {$info['mysqlver']}
# | PHP Version: {$info['phpver']}
# | Base de datos: '$bd'
# | Tablas: {$info['tablas']}
# |
# +-------------------------------------------------------------------
 
EOT;
        foreach ($tablas as $tabla) {

            $drop_table_query = "";
            $create_table_query = "";
            $insert_into_query = "";

            /* Se halla el query que será capaz vaciar la tabla. */
            if ($drop) {
                $drop_table_query = "DROP TABLE IF EXISTS " . $tabla . ";";
            } else {
                $drop_table_query = "# No especificado.";
            }
            
            if ($truncate){
                $truncate_table_query = "truncate table  " . $tabla . ";";
            }else{
                 $truncate_table_query = "# No especificado.";
            }

            /* Se halla el query que será capaz de recrear la estructura de la tabla. */
           if ($create){
            $create_table_query = "";
            $consulta = "SHOW CREATE TABLE $tabla;";
            $respuesta = mysql_query($consulta, $conexion)
                    or die("No se pudo ejecutar la consulta: " . mysql_error());
            while ($fila = mysql_fetch_array($respuesta, MYSQL_NUM)) {
                $create_table_query = $fila[1] . ";";
            }
            
           }
            /* Se halla el query que será capaz de insertar los datos. */
            $insert_into_query = "";
            $consulta = "SELECT * FROM $tabla;";
            $respuesta = mysql_query($consulta, $conexion)
                    or die("No se pudo ejecutar la consulta: " . mysql_error());
            while ($fila = mysql_fetch_array($respuesta, MYSQL_ASSOC)) {
                $columnas = array_keys($fila);
                foreach ($columnas as $columna) {
                    if (gettype($fila[$columna]) == "NULL") {
                        $values[] = "NULL";
                    } else {
                        $values[] = "'" . mysql_real_escape_string($fila[$columna]) . "'";
                    }
                }
                $insert_into_query .= "INSERT INTO " . $tabla . " VALUES (" . implode(", ", $values) . ");\n";
                unset($values);
            }

            $dump .= <<<EOT
 
# | Vaciado de tabla '$tabla'
# +------------------------------------->
$drop_table_query
 

# | Truncate de tabla '$tabla'
# +------------------------------------->
$truncate_table_query
 
# | Estructura de la tabla '$tabla'
# +------------------------------------->
$create_table_query
 
 
# | Carga de datos de la tabla '$tabla'
# +------------------------------------->
$insert_into_query
 
EOT;
        }

        $file = fopen($ruta, "w+");
        fwrite($file, $dump);
        fclose($file);
    }

    function throw_ex($er) {
        throw new Exception($er);
    }

}