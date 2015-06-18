<?php

/**
 *  [smart_token] 2015
 *  table_smart_token.php
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class table_smart_token extends discuz_table {

    public function __construct() {
        $this->_table = 'smart_token';
        $this->_pk = 'id';

        parent::__construct();
    }

    public function fetch_by_id($id) {
        return DB::fetch_first('SELECT * FROM %t WHERE id=%d', array($this->_table, $id));
    }

    public function count_all() {
        return (int) DB::result_first("SELECT count(*) FROM %t", array($this->_table));
    }
    
    public function fetch_all($start, $limit,$order='id') {
        return DB::fetch_all('SELECT * FROM %t ORDER BY '.$order.' desc ' . DB::limit($start, $limit), array($this->_table), $this->_pk);
    }


    public function update_by_id($data,$id) {
        return DB::update($this->_table,$data,"id=".$id);
    }

    public function insert($data) {
        return DB::insert($this->_table,$data);
    }
    
}
