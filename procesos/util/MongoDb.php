<?php

class TestClass {

    public $connection;
    public $collection;
    const DB = 'micanal';

    public function __construct($host = 'localhost:27017') {
        $this->connection = new Mongo($host);
    }

    public function setDatabase() {
        $this->db = $this->connection->selectDB(self::DB);
    }

    public function setCollection($c) {
        $this->collection = $this->db->selectCollection($c);
    }

    public function setRelacionados($c) {
        $this->collection = $this->db->selectCollection($c);
    }

    public function insert($f) {
        $this->collection->insert($f);
    }

    public function get($f=NULL) {
        $cursor = $this->collection->find($f);
        $k = array();
        $i = 0;

        while ($cursor->hasNext()) {
            $k[$i] = $cursor->getNext();
            $i++;
        }

        return $k;
    }

    public function update($f1, $f2) {
        $this->collection->update($f1, $f2);
        //$this->db->{$collection}->update($this->wheres, $this->updates, $options);
    }

    public function getAll() {
        $cursor = $this->collection->find();
        foreach ($cursor as $id => $value) {
            $result[] = array('video_id' => $value['video_id'],
                'nro_reproducciones' => $value['nro_reproducciones']);
        }
        return $result;
    }

    public function delete($f, $one = FALSE) {
        $c = $this->collection->remove($f, $one);
        return $c;
    }

    public function drop() {
        $c = $this->collection->drop();
        return $c;
    }

    public function ensureIndex($args) {
        return $this->collection->ensureIndex($args);
    }

}
