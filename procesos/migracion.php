<?php
set_time_limit(1500);
define("MYSQLHOST", "10.203.31.139");
define("MYSQLDB", "micanalcmsdevdb");
define("MYSQLUSER", "micanalcmsdev");
define("MYSQLPASS", "joh2Yeyeimaeb4");

class Migracion extends Exception {

    private $archivo = 'sql/fu_aliaspa.sql';

    public function setQuery($consultamysql) {
        try {
            $this->mysql_conexion = new mysqli(MYSQLHOST, MYSQLUSER, MYSQLPASS, MYSQLDB);
            $this->mysql_conexion->query($consultamysql);
            $this->mysql_conexion->close();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function getSql() {
        try {
            echo '===  Inicio del proceso ===';

            $lines = file($this->archivo);

            foreach ($lines as $line) {

                if (substr($line, 0, 2) == '--' || $line == '') {
                    continue;
                }

                $templine .= $line;
                if (substr(trim($line), -1, 1) == ';') {
                    $this->setQuery($templine);
                    $templine = ''; 
                }
            }

            echo '===  Termino el proceso ===';
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    function throw_ex($er) {
        throw new Exception($er);
    }

}

$objMdb = new Migracion();
echo $objMdb->getSql();