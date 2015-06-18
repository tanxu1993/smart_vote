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

CREATE TABLE IF NOT EXISTS `pre_smart_token` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tokenid` varchar(200) NOT NULL DEFAULT '',
  `timedate` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `pre_info_atten` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `openid` varchar(50) NOT NULL,
  `unionid` varchar(50) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `cancel` int(1) NOT NULL COMMENT '1为取消',
  `timedate` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`)
);

EOF;
runquery($sql);

$finish = TRUE;

 ?>