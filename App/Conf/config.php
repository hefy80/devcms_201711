<?php
	require APP_PATH.'/Conf/redisdef.php';

	if ($_SERVER['SERVER_ADDR'] == '127.0.0.1') {
		$ext = array(
			'LOAD_EXT_CONFIG'=>'default.db'
		);
	} else {
		$ext = array(
			'LOAD_EXT_CONFIG'=>'db,redis'
		);
	}
	
	$config = array(
		'LOG_RECORD' => true, // 开启日志记录
		'LOG_LEVEL'  =>'EMERG,ALERT,CRIT,ERR,DEBUG',
		'LOG_TYPE' =>3, //使用文件方式记录日志

		'SHOW_PAGE_TRACE'=>true,
		'SHOW_ADV_TIME'=>true,

		'APP_GROUP_LIST'=>'Home,Admin',
		'DEFAULT_GROUP'=>'Home',
	);

	return array_merge($config,$ext);
?>