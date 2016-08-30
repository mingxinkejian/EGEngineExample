<?php

namespace Server;

use Log\EGLog;
abstract class EGWebSocketServer extends EGWebServer {
	public function __construct($host, $port,$isSetGlobal = true) {
		if (! $host || ! $port ) {
			echo "please confirm the params !\n";
			exit ();
		}
		$this->_defaultHost = $host;
		$this->_defaultPort = $port;
		$this->_server = new \swoole_websocket_server ( $host, $port );
		if ($isSetGlobal == true) {
			$this->_server->setGlobal ( HTTP_GLOBAL_ALL, HTTP_GLOBAL_GET | HTTP_GLOBAL_POST );
		}
	}
	public function onStart($server) {
		// swoole_set_process_name('EGServer');
		echo "---------started server--------".PHP_EOL;
		EGLog::info ( 'webSocketServer start' );
	}
	
	/**
	 * 当WebSocket客户端与服务器建立连接并完成握手后会回调此函数
	 * 
	 * @param \swoole_websocket_server $server        	
	 * @param \swoole_http_request $request        	
	 */
	public function onOpen(\swoole_websocket_server $server, \swoole_http_request $request) {
		if ($this->_debug){
			EGLog::info ( "{$request->fd} is connect" );
		}
	}
	
	/**
	 * 设置onHandShake回调函数后不会再触发onOpen事件，需要应用代码自行处理
	 * 自定定握手规则，没有设置则用系统内置的（只支持version:13的）
	 * 
	 * @param \swoole_http_request $request        	
	 * @param \swoole_http_response $response        	
	 * @return boolean
	 */
	public function onCustomHandShake(\swoole_http_request $request,\swoole_http_response $response) {
		if (! isset ( $request->header ['sec-websocket-key'] )) {
			// 'Bad protocol implementation: it is not RFC6455.'
			$response->end ();
			return false;
		}
		if (0 === preg_match ( '#^[+/0-9A-Za-z]{21}[AQgw]==$#', $request->header ['sec-websocket-key'] ) || 16 !== strlen ( base64_decode ( $request->header ['sec-websocket-key'] ) )) {
			// Header Sec-WebSocket-Key is illegal;
			$response->end ();
			return false;
		}
		
		$key = base64_encode ( sha1 ( $request->header ['sec-websocket-key'] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11', true ) );
		$headers = array (
				'Upgrade' => 'websocket',
				'Connection' => 'Upgrade',
				'Sec-WebSocket-Accept' => $key,
				'Sec-WebSocket-Version' => '13',
				'KeepAlive' => 'off' 
		);
		foreach ( $headers as $key => $val ) {
			$response->header ( $key, $val );
		}
		$response->status ( 101 );
		$response->end ();
		
		$this->onOpen($this->_server, $request);
		return true;
	}
	
	public function onRequest(\swoole_http_request $request,\swoole_http_response $response){
		$response->header('Server', self::SERVERNAME);
		$response->end();
	}
	
	/**
	 * 接收到来自客户端的消息
	 * 
	 * @param \swoole_websocket_server $server        	
	 * @param unknown $frame        	
	 */
	public function onMessage(\swoole_websocket_server $server, \swoole_websocket_frame $frame) {
	}
	
	public function onClose($server, $clientId, $fromId){
		if ($this->_debug){
			EGLog::info("{$clientId} is close");
		}
	}
	
	/**
	 * websocket close
	 * @param unknown $fd
	 * @param number $from_id
	 */
	public function wsClose($fd, $from_id = 0) {
		return $this->serverClose($fd);
	}
	
	/**
	 * websocket推送
	 * @param unknown $fd
	 * @param unknown $data
	 * @param string $binary_data
	 * @param string $finish
	 */
	public function wsPush($fd, $data, $binary_data = false, $finish = true) {
		$this->_server->push ( $fd, $data, $binary_data, $finish );
	}
	/**
	 * websocket特殊情况推送，仅支持swoole1.7.17+
	 * @param unknown $fd
	 * @param unknown $response
	 * @param number $from_id
	 */
	public function wsSend($fd, $response, $from_id = 0) {
		if (version_compare ( swoole_version(), '1.7.16', '>' ) ){
			$this->_server->send ( $fd, $response, $from_id );
		}
	}
}
