<?php

namespace Log;

class EGLog {
	protected static $_logRoot;
	protected static $_logger;
	const ERROR=1;
	const WARN=2;
	const DEBUG=3;
	const INFO=4;
	
	const TYPE_FILE='EGLogFile';
	const TYPE_MYSQL='EGLogMySql';
	
	public static function setConfig($logDir,$logType=self::TYPE_FILE){
		self::$_logRoot=$logDir;
		$className='\\Log\\Driver\\'.$logType;
		self::$_logger=new $className($logDir);
	}
	
	public static function printLog($logMsg){
		echo $logMsg."\n";
	}
	
	public static function debug($debugMsg){
		$logMsg="[DEBUG] ".date('Y-M-d H:i:s').' '.$debugMsg;
		self::$_logger->write($logMsg);
	}
	
	public static function warn($warnMsg){
		$logMsg="[WARN] ".date('Y-M-d H:i:s').' '.$warnMsg;
		self::$_logger->write($logMsg);
	}
	public static function error($errorMsg){
		$logMsg="[ERROR] ".date('Y-M-d H:i:s').' '.$errorMsg;
		self::$_logger->write($logMsg);
	}
	public static function info($infoMsg){
		$logMsg="[INFO] ".date('Y-M-d H:i:s').' '.$infoMsg;
		self::$_logger->write($logMsg);
	}
}
