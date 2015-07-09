<?php
/**
 *  [smart_vote] 2015
 *  table_smart_vote.php
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class table_smart_contact extends discuz_table {

    public function __construct() {
        $this->_table = 'smart_contact';
        $this->_pk = 'id';

        parent::__construct();
    }

    //根据uid取结果集
    public function fetch_by_uid($uid ,$openid ,$time) {
        return DB::fetch_first('SELECT * FROM %t WHERE `uid`=%d and `openid` = %s and `timedate`>%d ', array($this->_table, $uid ,$openid , $time));
    }
    // //根据名字取结果集
    // public function fetch_by_name($where) {
    //     return DB::fetch_all('SELECT * FROM %t WHERE %i', array($this->_table, $where));
    // }

    // //根据id更新一条数据
    // public function update_by_id($data,$id) {
    //     return DB::update($this->_table,$data,"id=".$id);
    // }
    //插入一条记录    
    public function insert($data) {
        return DB::insert($this->_table,$data);
    }
    
}
