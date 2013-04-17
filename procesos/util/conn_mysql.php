<?php
define("MYSQLDB", "pyro_admin2");
define("MYSQLHOST", "192.168.1.35");
define("MYSQLUSER", "root");
define("MYSQLPASS", "123");

class Conexion_MySql {

    private $mysql_conexion;

    public function __construct() {

        /*  $this->mysql_conexion = new mysqli(MYSQLHOST, MYSQLUSER, MYSQLPASS, MYSQLDB);

          if ($this->mysql_conexion->connect_errno) {
          echo "Fallo al conectar a MySQL: (" . $this->mysql_conexion->connect_errno . ") " . $this->$CONEXION_MYSQL->connect_error;
          }

         */
    }
    
    public function setQueryInsertReturnId($consultamysql){
        $this->mysql_conexion = new mysqli(MYSQLHOST, MYSQLUSER, MYSQLPASS, MYSQLDB);
        $resultquery = $this->mysql_conexion->query($consultamysql);
        $idreturn=$this->mysql_conexion->insert_id;        
        $this->mysql_conexion->close();
        return $idreturn;
    }
    

    public function setQueryRow($consultamysql) {
        try {
            $this->mysql_conexion = new mysqli(MYSQLHOST, MYSQLUSER, MYSQLPASS, MYSQLDB);
            $resultquery = $this->mysql_conexion->query($consultamysql);
            $this->mysql_conexion->close();
                        
            if ($resultquery != FALSE) {
                return $resultquery->fetch_array(MYSQLI_ASSOC);                
            }else{
                return null;
            }
        } catch (Exception $exc) {
            //echo $exc->getTraceAsString();
            return null;
        }
    }

    public function setQueryRows($consultamysql) {
        try {
            $this->mysql_conexion = new mysqli(MYSQLHOST, MYSQLUSER, MYSQLPASS, MYSQLDB);
            $resultquery = $this->mysql_conexion->query($consultamysql);
            $this->mysql_conexion->close();
            
            if ($resultquery != FALSE) {
                $result = array();
                
                while ($row = $resultquery->fetch_array(MYSQLI_ASSOC)) {
                    array_push($result, $row);
                }              
                return $result;
            }else{
                 return null;
            }
        } catch (Exception $exc) {
            //echo $exc->getTraceAsString();
            return null;
        }
    }

    public function setConsulta($consultamysql) {
        $this->mysql_conexion = new mysqli(MYSQLHOST, MYSQLUSER, MYSQLPASS, MYSQLDB);

        if (strlen(trim($consultamysql)) != 0) {
            try {
                //echo $consultamysql;
                $result = $this->mysql_conexion->query($consultamysql);
                return $result;
            } catch (Exception $e) {
                return null;
            }
        }
        $this->mysql_conexion->close();
    }

    public function setProcedure($consultamysql) {
        // echo "\n procedure ".$consultamysql." - ".strlen(trim($consultamysql));
        //var_dump($consultamysql);

        if (strlen(trim($consultamysql)) != 0) {
            try {
                //$mysqli = $this->$CONEXION_MYSQL;            
                $result = $this->mysql_conexion->query($consultamysql);
                return $result;
            } catch (Exception $e) {
                return null;
            }
        }
    }

    public function setUpdate($consultamysql) {
        $this->mysql_conexion = new mysqli(MYSQLHOST, MYSQLUSER, MYSQLPASS, MYSQLDB);
        if (isset($consultamysql)) {
            try {
                //$mysqli = $this->$CONEXION_MYSQL;
                $this->mysql_conexion->query($consultamysql);
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
        $this->mysql_conexion->close();
    }



    public function SetMysqlToMongo($table, $values) {

        if (isset($values["id"])) {

            $sql = 'UPDATE ' . $table . ' SET ';
            unset($values["_id"]);
            $cant = count($values);

            $i = 1;
            foreach ($values as $key => $value) {
                $sql.=" " . $key . " = " . $value . " ";
                if ($i != $cant) {
                    $sql.=",";
                }$i++;
            }

            $sql.=' WHERE id =' . $values["id"];

            $this->setUpdate($sql);
        }
    }

    public function __destruct() {
        /*
          $this->mysql_conexion->close();
          $this->mysql_conexion;

         */
    }

}
?>
