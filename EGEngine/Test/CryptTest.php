<?php
use Core\EGLoader;
use Application\Protocol\ProtocolPack;
use Log\EGLog;
//加密测试



//设置时区
date_default_timezone_set('PRC');
// 默认路径为该工程的根目录
define ( 'TEST_ROOT', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR );
define('APPROOT', dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..' . DIRECTORY_SEPARATOR . 'Application' . DIRECTORY_SEPARATOR);
$logRoot=dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..' . DIRECTORY_SEPARATOR.'Runtime'.DIRECTORY_SEPARATOR;
// 为方便起见，使用autoload自动加载
include TEST_ROOT . 'requireEGEngine.php';

EGLoader::addNameSpace('Application', APPROOT);
EGLog::setConfig($logRoot);

//加密
$testData['pId']=100;
$testData['ret']=1000;
$testData['data']='hello world';

$cryptData = ProtocolPack::packEncode($testData, 'gamecell2014');
echo $cryptData."\n";

//解密

$deCryptData = ProtocolPack::packDecode($cryptData, 'gamecell2014');
echo json_encode($deCryptData)."\n";