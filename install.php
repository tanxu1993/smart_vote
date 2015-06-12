<?php 

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

$sql = <<<EOF
CREATE TABLE IF NOT EXISTS pre_smart_vote (
  `id` int(10) unsigned NOT NULL auto_increment COMMENT 'primary key,autoincrement',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `name` char(16) NOT NULL DEFAULT '',
  `mobile` char(11) NOT NULL DEFAULT '',
  `votenum` int(10) NOT NULL DEFAULT '0',
  `filepath` char(64) NOT NULL DEFAULT '',
  `note` char(128) NOT NULL DEFAULT '',
  `ip` char(16) NOT NULL DEFAULT '',
  `timedate` int(10) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`,`uid`),
    KEY `name` (`name`)
) TYPE=MyISAM;
EOF;
runquery($sql);

$finish = TRUE;

 ?>