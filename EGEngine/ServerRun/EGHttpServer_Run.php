<?php
use ConfigReader\EGJson;
use Server\EGHttpServer;
use Log\EGLog;
if (version_compare ( PHP_VERSION, '5.4.0', '<' ))
	die ( 'require PHP > 5.4.0 !' );

define ( 'DS', DIRECTORY_SEPARATOR );
// 默认路径为该工程的根目录
define ( 'WEB_ROOT', __DIR__ . DS . '..' . DS );

// 为方便起见，使用autoload自动加载
require WEB_ROOT.'requireEGEngine.php';

$configPath = __DIR__ . DS . 'serverConf.json';
$configData = EGJson::parse ( $configPath );

$logRoot=WEB_ROOT.'Runtime'.DS;

EGLog::setConfig($logRoot);

$httpServer = new EGHttpServer( $configData['httpServer']['host'], $configData['httpServer']['port'] ,true);
$httpServer->loadConfig ( $configData ['httpServer'] );
$httpServer->setWebRoot ( WEB_ROOT );
$httpServer->startServer ();