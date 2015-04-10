<?php
	if (!defined('BASEPATH')) exit('此文件不可被直接访问');

	$active_group = 'local';
	$active_record = TRUE;

	/* 阿里云RDS数据库 */
	$db['aliyun_rds']['hostname'] = 'sensestrong.mysql.rds.aliyuncs.com:3306';
	$db['aliyun_rds']['username'] = 'mycar';
	$db['aliyun_rds']['password'] = 'mycar2014';
	$db['aliyun_rds']['database'] = 'mycar';
	$db['aliyun_rds']['dbdriver'] = 'mysql';
	$db['aliyun_rds']['dbprefix'] = '';
	$db['aliyun_rds']['pconnect'] = TRUE;
	$db['aliyun_rds']['db_debug'] = TRUE;
	$db['aliyun_rds']['cache_on'] = FALSE;
	$db['aliyun_rds']['cachedir'] = '';
	$db['aliyun_rds']['char_set'] = 'utf8';
	$db['aliyun_rds']['dbcollat'] = 'utf8_general_ci';
	$db['aliyun_rds']['swap_pre'] = '';
	$db['aliyun_rds']['autoinit'] = TRUE;
	$db['aliyun_rds']['stricton'] = FALSE;

	/* 本地测试数据库 */
	$db['local']['hostname'] = 'localhost';
	$db['local']['username'] = 'root';
	$db['local']['password'] = '';
	$db['local']['database'] = 'mycar';
	$db['local']['dbdriver'] = 'mysql';
	$db['local']['dbprefix'] = '';
	$db['local']['pconnect'] = TRUE;
	$db['local']['db_debug'] = TRUE;
	$db['local']['cache_on'] = FALSE;
	$db['local']['cachedir'] = '';
	$db['local']['char_set'] = 'utf8';
	$db['local']['dbcollat'] = 'utf8_general_ci';
	$db['local']['swap_pre'] = '';
	$db['local']['autoinit'] = TRUE;
	$db['local']['stricton'] = FALSE;

/* End of file database.php */
/* Location: ./application/config/database.php */