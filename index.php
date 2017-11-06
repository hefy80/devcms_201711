<?php
	header("Content-Type:text/html; charset=utf-8");
    define('APP_NAME', 'App');
    define('APP_PATH', './App/');
    define('APP_DEBUG', true);
    define('APP_UPLOADPATH', '/Upload/');
    define('RUNTIME_PATH', './Runtime/'.APP_NAME.'/');
	require './ThinkPHP/ThinkPHP.php';
?>