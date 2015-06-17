<?php
/**
 *  [wxinfo] 2015
 *  table_wxinfo.php
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class table_wxinfo extends discuz_table {

    public function __construct() {
        $this->_table = 'wxinfo';
        $this->_pk = 'id';

        parent::__construct();
    }
    
    public function fetch_all($start, $limit,$order='id') {
        return DB::fetch_all('SELECT * FROM %t ORDER BY '.$order.' desc ' . DB::limit($start, $limit), array($this->_table), $this->_pk);
    }

    public function update_by_id($data,$id) {
        return DB::update($this->_table,$data,"id=".$id);
    }

    
}
