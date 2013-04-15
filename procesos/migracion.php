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

    private $archivo = 'sql/data.150313.sql';

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

//        $sql = explode(";", file_get_contents($this->archivo)); //
//        foreach ($sql as $query) {
//            try {
//                echo $query."\n";
//                $this->setQuery($query);
//            } catch (Exception $e) {
//                return $e->getMessage();
//            }
//        }

        $lines = file($this->archivo);
// Loop through each line
        foreach ($lines as $line) {
            // Skip it if it's a comment
            if (substr($line, 0, 2) == '--' || $line == '')
                continue;

            // Add this line to the current segment
            $templine .= $line;
            // If it has a semicolon at the end, it's the end of the query
            if (substr(trim($line), -1, 1) == ';') {

                echo $templine . "\n";
                // Perform the query
                mysql_query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
                // Reset temp variable to empty
                $templine = '';
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