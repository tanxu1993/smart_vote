<?php
/**
 *  [smart_info] 2015
 *  table_smart_info.php
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class table_smart_count extends discuz_table {

    public function __construct() {
        $this->_table = 'smart_count';
        $this->_pk = 'id';

        parent::__construct();
    }
    

    // public function update_by_id($data,$where) {
    //     return DB::update($this->_table,$data,$where);
    // }

    public function insert($data) {
        return DB::insert($this->_table,$data);
    }

    public function update_by_id($data,$cid) {
        return DB::update($this->_table,$data, '`cid`=' . $cid);
    }


    // public function fetch_by_unionid($unionid) {
    //     return DB::fetch_first('SELECT * FROM %t WHERE unionid=%d', array($this->_table, $unionid));
    // }

    public function fetch_by_openid($openid) {
        return DB::fetch_first('SELECT * FROM %t WHERE openid=%s', array($this->_table, $openid));
    }
    
}
