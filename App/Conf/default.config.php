<?php
	if ($_SERVER['SERVER_ADDR'] == '127.0.0.1') {
		require APP_PATH.'/Conf/default.db.php';
		require APP_PATH.'/Conf/default.redis.php';
	} else {
		require APP_PATH.'/Conf/config.db.php';
		require APP_PATH.'/Conf/config.redis.php';
	}
return array(
	'LOG_RECORD' => true, // 开启日志记录
	'LOG_LEVEL'  =>'EMERG,ALERT,CRIT,ERR,DEBUG',
	'LOG_TYPE' =>3, //使用文件方式记录日志

	'SHOW_PAGE_TRACE'=>true,
	'SHOW_ADV_TIME'=>true,

	'APP_GROUP_LIST'=>'Home,Admin',
	'DEFAULT_GROUP'=>'Home',

	'db_type'  => 'mysql',
	'db_name'  => 'zentao',
	'db_host'  => '192.168.92.177',
	'db_port'  => '3306',
	'db_prefix'=> 'zt_',
	'db_user'  => 'heyu',
	'db_pwd'   => 'root',
	'db_charset'=> 'utf8'
);
?>