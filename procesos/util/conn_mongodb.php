<?php
define("MONGODB", "micanal_pre");
define("MONGOHOST", "localhost");
define("MONGOPORT", 27017);
define("MONGOUSER", "");
define("MONGOPASS", "");

class Conexion_MongoDb {

    private $mongodb_client;
    private $mongodb;
    private $mongodb_conexion;

    function __construct() {
        $this->mongodb_client = new MongoClient(MONGOHOST . ":" . MONGOPORT, array());
        $this->mongodb = $this->mongodb_client->selectDB(MONGODB);
    }

    function SetCollection($collection) {
        if (isset($collection)) {
            try {
                $this->mongodb_conexion = $this->mongodb->selectCollection($collection);
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
    }

    function GetItemsCollection($where = array(), $select = array()) {
        try {
            $result = $this->mongodb_conexion->find($where, $select);
            $ret = array();
            while ($result->hasNext()) {
                array_push($ret, $result->getNext());
            }
            return $ret;
        } catch (Exception $e) {
            return array();
        }
    }

    function SetItemCollection($objmongo = array()) {
        
        try {
            $this->mongodb_conexion->insert($objmongo);
            $arrmongo = array($objmongo);
            $idmongo = $arrmongo[0]['_id']->{'$id'};
            
            return $idmongo;
        } catch (Exception $e) {
            
            return "";
        }
    }

    function SetItemCollectionUpdate($arrkey = array(), $arrval = array()) {
        try {
            $this->mongodb_conexion->update($arrkey, array('$set' => $arrval));
            return TRUE;
        } catch (Exception $e) {
            return FALSE;
        }
    }

    function SetItemCollectionDelete($arrval = array()) {
        try {
            $this->mongodb_conexion->remove($arrval);
            return TRUE;
        } catch (Exception $e) {
            return FALSE;
        }
    }

}

?>