<?php

namespace Application\Server;

use Server\EGWebSocketServer;
use Log\EGLog;
use Exception\EGException;
use Application\Protocol\ProtocolPack;
use Application\Service\KeyExchangeService;
use Application\Common\MessageDispatcher;
use DataModel\EGUserInfo;
/*
 * websoeck服务器，网关保存对应的uId
 */
class GameCellFightWebSocketServer extends EGWebSocketServer{
		
	public function __construct($host, $port ,$isSetGlobal=true){
		parent::__construct($host, $port ,$isSetGlobal);
		
		$this->_server->on('open', array($this,'onOpen'));
		$this->_server->on('workerstart', array($this, 'onWorkerStart'));
		$this->_server->on('start', array($this, 'onStart'));
		$this->_server->on('request' , array( $this , 'onRequest'));
		$this->_server->on('message' , array( $this , 'onMessage'));
		$this->_server->on('close', array( $this , 'onClose' ));
		$this->_server->on('task',array($this,'onTask'));
		$this->_server->on('finish',array($this,'onFinish'));
	}

	public function onOpen(\swoole_websocket_server $server,\swoole_http_request $request) {
		// TODO Auto-generated method stub
		if ($this->_debug){
			EGLog::printLog("client {$request->fd} open");
		}
		
		try {
			//绑定连接信息
			$fdInfo = $server->connection_info($request->fd);
			//判断是否为websocket连接
			if($fdInfo['websocket_status'] > 1){
				EGLog::printLog("onOpen ".json_encode($fdInfo)." is open!");
			}
		} catch (EGException $e) {
			EGException::appException($e);
		}
		
	}
	
	public function onClose($server, $clientId, $fromId){
		if ($this->_debug){
			EGLog::printLog("onClose {$clientId} is closed!");
		}
		$fdInfo = $server->connection_info($clientId);
		//判断是否为websocket连接
		//剔除连接信息
		
		if($fdInfo['websocket_status'] > 1){
			EGLog::printLog("onClose ".json_encode($fdInfo)." is closed!");
		}
		
		
	}
		
	public function onRequest(\swoole_http_request $request,\swoole_http_response $response){
		/*
		 * 交换加密秘钥
		 */
		$response->header('Server', self::SERVERNAME);
		if ($request->server['request_uri']=='/favicon.ico'){
			$response->end();
		}else{
			$keyDataService = new KeyExchangeService();
			$result = $keyDataService->handShakeRSA();
			$response->end($result);
		}
	}
	/**
	 * 接收到来自客户端的消息
	 * @param \swoole_websocket_server $server
	 * @param unknown $frame
	 */
	public function onMessage(\swoole_websocket_server $server,\swoole_websocket_frame $frame){

		if (strcmp($frame->data,ProtocolPack::HeartBeats_REQ) == 0){
			$server->push($frame->fd, ProtocolPack::HeartBeats_RESP);
		}else{
			

			//消息进入后绑定用户信息，并分发
			$userInfoData = new EGUserInfo();
			$userInfoData->_fd = $frame->fd;
			$userInfoData->_data = $frame->data;
			$userInfoData->_uId = 1;
			
			$server->task($userInfoData);
		}
	}

	
	public function onTask($server, $taskId, $fromId, $data){
		$retData = MessageDispatcher::dispatchMsg($server, $data);
		$pushResult = $server->push($data->_fd,$retData);
		//此处return的值为onFinish回调函数中的$data参数
		if ($pushResult){
			return true;
		}
		return false;
	}
	
	public function onFinish($server, $taskId, $data){
		EGLog::printLog($data);
	}
}
