<?php

namespace Server;
use Log\EGLog;
/*
 * Server基类
 */
class EGBaseServer {
	protected $_defaultPort;
	protected $_defaultHost;
	public $_server;
	protected $_config;
	protected $_debug = false;

	/**
	 * 设置debug
	 *
	 * @param string $debug        	
	 */
	public function setDebug($debug = false) {
		$this->_debug = $debug;
	}
	/**
	 * 读取配置文件，在对应服务器的子类中覆盖
	 *
	 * @param unknown $fileName        	
	 */
	public function loadConfig($config) {
		if (empty($config)){
			$config=array(
					//工作进程
					'worker_num' => 4,
					//是否守护进程
					'daemonize' => false,
					//最大请求数
					'max_request' => 10000,
					//工作模式
					'dispatch_mode' => 2
			);
		}
		$this->_config=$config;
		
		$this->_server->set($config);
	}
	/**
	 * 启动服务器
	 */
	public function startServer() {
		$this->_server->start ();
	}
	
	/**
	 * 添加监听的地址和端口
	 *
	 * @param unknown $host        	
	 * @param unknown $port        	
	 * @param string $type        	
	 */
	public function addListener($host, $port, $type = SWOOLE_SOCK_TCP) {
		if ($this->_server) {
			$this->_server->addListener ( $host, $port, $type );
		}
	}
	
	/**
	 * 停止
	 *
	 * @param unknown $server        	
	 */
	public function onShutdown($server) {
		if ($this->_debug) {
			EGLog::debug ( "server shutdown" );
		}
	}
	
	/**
	 * 工作进程
	 *
	 * @param
	 *        	$server
	 * @param
	 *        	$workerId
	 */
	public function onWorkerStart($server, $workerId) {
		// swoole_set_process_name('EGServer_worker');
		echo "WorkerStart: MasterPid={$server->master_pid}|Manager_pid={$server->manager_pid}|WorkerId={$server->worker_id}|WorkerPid={$server->worker_pid}".PHP_EOL;
		if ($this->_debug) {
			EGLog::debug ( "WorkerStart: MasterPid={$server->master_pid}|Manager_pid={$server->manager_pid}|WorkerId={$server->worker_id}|WorkerPid={$server->worker_pid}" );
		}
	}
	
	/**
	 * 关闭客户端
	 * @param unknown $fd
	 * @param number $from_id
	 */
	public function serverClose($fd, $from_id = 0){
		return $this->_server->close ( $fd );
	}
	
}
