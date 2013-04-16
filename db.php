<?php
$mysqli = new mysqli('10.203.31.139', 'micanalcmsdev', 'joh2Yeyeimaeb4', 'micanalcmsdevdb');
 
if (mysqli_connect_error()) {
    die('Connect Error (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
}
 
echo 'Success... ' . $mysqli->host_info . "<br />";
echo 'Retrieving dumpfile' . "<br />";
 
$sql = file_get_contents('procesos/sql/data.150313_2.sql');
if (!$sql){
	die ('Error opening file');
}
 
echo 'processing file <br />';
mysqli_multi_query($mysqli,$sql);
 
echo 'done.';
$mysqli->close();
?>