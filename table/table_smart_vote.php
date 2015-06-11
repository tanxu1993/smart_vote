<?php
/**
 *  [smart_vote] 2015
 *  table_smart_vote.php
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class table_smart_vote extends discuz_table {

    public function __construct() {
        $this->_table = 'smart_vote';
        $this->_pk = 'id';

        parent::__construct();
    }
    //取报名数
    public function count_all() {
        return (int) DB::result_first("SELECT count(*) FROM %t", array($this->_table));
    }
    
    public function fetch_all($start, $limit,$order='id') {
        return DB::fetch_all('SELECT * FROM %t ORDER BY '.$order.' desc ' . DB::limit($start, $limit), array($this->_table), $this->_pk);
    }

    // public function fetch_all($start, $limit,$order='id',$keys='') {
    //     $searchsql='';
    //     if($keys)
    //     {
    //         $wheresql=' where '.DB::field('nick_name', '%'.$keys.'%', 'like');
    //         return DB::fetch_all('SELECT * FROM %t %i ORDER BY '.$order.' desc ' . DB::limit($start, $limit), array($this->_table,$wheresql), $this->_pk);
    //     }
    //     return DB::fetch_all('SELECT * FROM %t ORDER BY '.$order.' desc ' . DB::limit($start, $limit), array($this->_table), $this->_pk);
    // }
    //根据id取结果集
    public function fetch_by_id($id) {
        return DB::fetch_first('SELECT * FROM %t WHERE id=%d', array($this->_table, $id));
    }
    //根据名字取结果集
    public function fetch_by_name($name) {
        return DB::fetch_first('SELECT * FROM %t WHERE name=%d', array($this->_table, $name));
    }
    // public function fetch_by_id_one($id,$field) {
    //     return DB::result_first("SELECT $field FROM %t WHERE id=%d", array($this->_table, $id));
    // }

    //根据id更新一条数据
    public function update_by_id($data,$id) {
        return DB::update($this->_table,$data,"id=".$id);
    }
    //插入一条投票记录    
    public function insert($data) {
        return DB::insert($this->_table,$data,true);
    }
    //根据id删除一条投票记录
    public function delete_by_id($id) {
        return DB::delete($this->_table,"id=".$id,true);    
    }
}
