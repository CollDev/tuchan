<?php

define("MYSQLHOST", "10.203.31.139");
define("MYSQLDB", "micanacmsdevdb");
define("MYSQLUSER", "micanalcmsdev");
define("MYSQLPASS", "joh2Yeyeimaeb4");

/*
  host: 10.203.31.139
  usuario: micanalcmsdev
  db: micanacmsdevdb
  password: joh2Yeyeimaeb4
 */

class Migracion extends Exception {

    private $archivo = 'sql/mysql.150413.sql';

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
        echo '===  Inicio del proceso ===';

        $sql = explode(";", file_get_contents($this->archivo)); //
        foreach ($sql as $query) {
            try {
                echo $query."\n";
                $this->setQuery($query);
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }
        echo '===  Termino el proceso ===';
    }

    function throw_ex($er) {
        throw new Exception($er);
    }

}

$objMdb = new Migracion();
echo $objMdb->getSql();