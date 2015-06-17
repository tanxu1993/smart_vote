<?php
/**
 *  [info_atten] 2015
 *  table_info_atten.php
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class table_info_atten extends discuz_table {

    public function __construct() {
        $this->_table = 'info_atten';
        $this->_pk = 'id';

        parent::__construct();
    }
    
    // public function fetch_all($start, $limit,$order='id') {
    //     return DB::fetch_all('SELECT * FROM %t ORDER BY '.$order.' desc ' . DB::limit($start, $limit), array($this->_table), $this->_pk);
    // }

    public function update_by_id($data,$id) {
        return DB::update($this->_table,$data,"id=".$id);
    }
    public function insert($data) {
        return DB::insert($this->_table,$data);
    }

    public function fetch_by_unionid($unionid) {
        return DB::fetch_first('SELECT * FROM %t WHERE unionid=%d', array($this->_table, $unionid));
    }

    public function fetch_by_openid($openid) {
        return DB::fetch_first('SELECT * FROM %t WHERE openid=%d', array($this->_table, $openid));
    }
    
}
