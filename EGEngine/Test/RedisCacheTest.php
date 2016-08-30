<?php
use ConfigReader\EGJson;
use Cache\EGCacheFactory;
// 默认路径为该工程的根目录
define ( 'TEST_ROOT', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR );

// 为方便起见，使用autoload自动加载
include TEST_ROOT . 'requireEGEngine.php';

$configPath = TEST_ROOT . 'Cache' . DIRECTORY_SEPARATOR . 'cacheConfig.json';
$jsonConfData = EGJson::parse ( $configPath );

$redis = EGCacheFactory::getInstance ( $jsonConfData ['Redis'] );
$result = $redis->addCache ( 'testRedis', 'mingtingjian' );
var_dump ( $result );