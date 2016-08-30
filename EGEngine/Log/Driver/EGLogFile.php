<?php

namespace Log\Driver;

class EGLogFile {
	private static $_logRoot;
	private static $_fileSize = 2097152;
	public function __construct($logRoot) {
		self::$_logRoot = $logRoot;
	}
	
	/**
	 * 日志写入接口
	 * 
	 * @access public
	 * @param string $log 日志信息      	
	 * @param string $destination 写入目标
	 * @return void
	 */
	public static function write($log, $destination = '') {
		$now = date ( 'c' );
		if (empty ( $destination ))
			$destination = self::$_logRoot . date ( 'y_m_d' ) . '.log';
			// 检测日志文件大小，超过配置大小则备份日志文件重新生成
		if (is_file ( $destination ) && floor ( self::$_fileSize ) <= filesize ( $destination )) {
			rename ( $destination, dirname ( $destination ) . '/' . time () . '-' . basename ( $destination ) );
		}
		error_log ( "[{$now}] " . "{$log}\r\n", 3, $destination );
	}
}
