<?php
use Server\EGWebSocketServer;
use ConfigReader\EGJson;
use Log\EGLog;
if (version_compare ( PHP_VERSION, '5.4.0', '<' ))
	die ( 'require PHP > 5.4.0 !' );

define ( 'DS', DIRECTORY_SEPARATOR );
// 默认路径为该工程的根目录
define ( 'WEB_ROOT', __DIR__ . DS . '..' . DS );


// 为方便起见，使用autoload自动加载
require WEB_ROOT.'requireEGEngine.php';

/**
 * 此例子是用来测试websocket的，继承Server下的websocketServer即可
 * 
 */

class Cocos2dxWebSocket extends EGWebSocketServer{
	
	public function __construct($host, $port, $logger,$isSetGlobal=true){
		parent::__construct($host, $port, $logger,$isSetGlobal);
		$this->_server->on('open', array($this,'onOpen'));
		$this->_server->on('start', array($this, 'onStart'));
		$this->_server->on('request' , array( $this , 'onRequest'));
		$this->_server->on('message' , array( $this , 'onMessage'));
		$this->_server->on('workerStart' , array( $this , 'onWorkerStart'));
		$this->_server->on('close', array( $this , 'onClose' ));
	}

	public function onWorkerStart(\swoole_server $server, $workerId) {
		// TODO Auto-generated method stub
		parent::onWorkerStart($server, $workerId);
	}

	/**
	 * 接收到来自客户端的消息
	 * @param \swoole_websocket_server $server
	 * @param unknown $frame
	 */
	public function onMessage(\swoole_websocket_server $server,$frame){
		$data=json_decode($frame->data,true);
		switch ($data['pId']){
			case 101:
				$server->push($frame->fd, '{"ret":1000,"data":"welcome to cocos2dx world"}');
				break;
			case 201:
				$data=$this->wsClose($frame->fd);
				var_dump($data);
				break;
		}
	}
}


$configPath = WEB_ROOT.'ServerRun'.DS. 'serverConf.json';
$configData = EGJson::parse ( $configPath );

EGLog::setConfig(WEB_ROOT);

$wsServer = new Cocos2dxWebSocket( '127.0.0.1', 9502,false);
$wsServer->loadConfig ( $configData ['webSocketServer'] );
$wsServer->setWebRoot ( WEB_ROOT );
$wsServer->startServer ();