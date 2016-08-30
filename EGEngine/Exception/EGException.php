<?php

namespace Exception;

use Log\EGLog;
class EGException extends \Exception{
	
	public static function initException(){
		register_shutdown_function('Exception\EGException::fatalError');
		set_error_handler('Exception\EGException::appError');
		set_exception_handler('Exception\EGException::appException');
	}
	
	public static function fatalError(){		
		$e = error_get_last();
		if (!empty($e)) {
			switch ($e['type']) {
				case E_ERROR:
				case E_PARSE:
				case E_CORE_ERROR:
				case E_COMPILE_ERROR:
				case E_USER_ERROR:
					ob_end_clean();
					break;
			}
			//保存日志
			$msg="message:".$e['message']."\n"." file:".$e['file'];
			EGLog::error($msg);
		}
	}
	
	
	public static function appError($errno, $errstr, $errfile, $errline){
		$errorStr = "[{$errno}] {$errstr} {$errfile} on {$errline} line.";
		switch ($errno) {
			case E_ERROR:
			case E_PARSE:
			case E_CORE_ERROR:
			case E_COMPILE_ERROR:
			case E_USER_ERROR:
				EGLog::error($errorStr);
				break;
			case E_STRICT:
			case E_USER_WARNING:
			case E_USER_NOTICE:
			default:
				EGLog::info($errorStr);
				break;
		}
	}
	
	public static function appException($e){
		$error = [];
		$error['message'] = $e->getMessage();
		$error['file'] = $e->getFile();
		$error['line'] = $e->getLine();
		$error['trace'] = $e->getTraceAsString();
		// 记录异常日志
		EGLog::error($error['message'],'ERR');
	}
}