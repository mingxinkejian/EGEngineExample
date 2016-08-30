<?php
use DB\EGDBFactory;
use ConfigReader\EGJson;
// 默认路径为该工程的根目录
define ( 'TEST_ROOT', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR );

// 为方便起见，使用autoload自动加载
include TEST_ROOT . 'requireEGEngine.php';

$configPath = TEST_ROOT . 'Cache' . DIRECTORY_SEPARATOR . 'cacheConfig.json';
$jsonConfData = EGJson::parse ( $configPath );
$mongoDb = EGDBFactory::getInstance ( $jsonConfData ['mongoDB'], EGDBFactory::DBTYPE_MONGODB );
$mongoDb->switchCollection ( 't_admin', 'gameManager' );
$mongoDb->select ( 't_admin' );
$cursor = $mongoDb->limit ( 1, 0 );
$sql = $mongoDb->getLastSql ();
var_dump ( iterator_to_array ( $cursor ) );