<?php
include ("util/conn_mysql.php");
include ("util/conn_mongodb.php");
include ("ffmpeg.php");
include ("liquid.php");

define('APIURL', 'http://api.liquidplatform.com/2.0');
define('WEBURL', 'http://webtv.liquidplatform.com/2.0/uploadMedia');
define("PATH_ELEMENTOS", "http://dev.e.micanal.e3.pe/");
define('PATH_VIDEOS', substr(__FILE__, 0, strrpos(__FILE__, "procesos")) . "uploads/videos/");

?>
