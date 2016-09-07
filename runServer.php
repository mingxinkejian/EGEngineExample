<?php
use Core\EGLoader;
use Log\EGLog;
use Application\Server\GameCellFightWebSocketServer;
use Cache\EGCacheFactory;
use Application\Common\GateWayManager;
use Application\Common\ConfigManager;
use Application\Common\GameWroldManager;
use Application\Controller\MsgDispatcher;
use ConfigReader\EGIni;
use Core\EGRunTime;
use ConfigReader\EGJson;
use Extension\EGSockBuffer;
if (version_compare ( PHP_VERSION, '5.4.0', '<' ))
	die ( 'require PHP > 5.4.0 !' );

define ( 'DS', DIRECTORY_SEPARATOR );
define('APPROOT', dirname(__FILE__) . DS . 'Application' . DS);
define('WEB_ROOT', dirname(__FILE__).DS );
//设置时区
date_default_timezone_set('PRC');
// 为方便起见，使用autoload自动加载
require WEB_ROOT.'EGEngine'.DS.'requireEGEngine.php';
EGLoader::addNameSpace('Application', APPROOT);

function initServer(){
	//日志目录
	$logRoot=WEB_ROOT.'Runtime'.DS;
	EGLog::setConfig($logRoot);
	
	//服务器所需配置
	$configPath = WEB_ROOT. 'serverConf.ini';
	$configData = EGIni::parse ( $configPath ,true);
	$wsServer = new GameCellFightWebSocketServer( $configData['webSocketServer']['host'], $configData['webSocketServer']['port'] ,false);
	$wsServer->loadConfig ( $configData ['webSocketServer'] );
	$wsServer->setDebug($configData['Debug']['isDebug']);
	$wsServer->setWebRoot ( WEB_ROOT );
	
	//长连接redis
	$configPath = WEB_ROOT. 'cacheConf.json';
	$jsonConfData = EGJson::parse ( $configPath );
	$redis=EGCacheFactory::getInstance($jsonConfData ['Redis']);
	
	//网关
	GateWayManager::setWorkServer($wsServer);
	//启动服务器
	return $wsServer;
}

function initConfig(){
	//应用配置
	$appConfig = APPROOT.'Conf'.DS.'config.php';
	$appConfig = require_once $appConfig;
	ConfigManager::getInstance()->addAppConfig($appConfig);
}
function initGameWorld(){
	MsgDispatcher::getInstance();
	GameWroldManager::getInstance()->init();
}
/**
 * 运行函数
 */
function main(){
	$runTime = new EGRunTime();
	echo "---------begin run server Swoole Version:".swoole_version()."--------".PHP_EOL;
	$runTime->start();
	//初始化配置
	initConfig();
	$runTime->stop();
	echo "---------finish load config| spend time:".$runTime->getRunTime()."--------".PHP_EOL;
	echo "---------begin init server--------".PHP_EOL;
	$runTime->start();
	//初始化服务器
	$server=initServer();
	$runTime->stop();
	echo "---------finish load server config| spend time:".$runTime->getRunTime()."--------".PHP_EOL;
	echo "---------begin init gameWorld--------".PHP_EOL;
	$runTime->start();
	//初始化游戏世界
	initGameWorld();
	$runTime->stop();
	echo "---------finish load gameWrold | spend time:".$runTime->getRunTime()."--------".PHP_EOL;
	
// 	EGSockBuffer::testBuffer();
// 	return ;
	//启动服务器
	$server->startServer();
}

main();
