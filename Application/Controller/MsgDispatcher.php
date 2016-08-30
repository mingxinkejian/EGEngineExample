<?php

namespace Application\Controller;
use Application\Service\MsgDispatchService;
use Application\Service\KeyExchangeService;
/*
 * 消息分发器
 */
class MsgDispatcher {
	
	//单例
	private static $_instance;
	public static function getInstance()
	{
		if(! (self::$_instance instanceof self) )
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	
	}
	
	
	/*
	 * 网络层进来的消息根据消息号分发到不同逻辑层处理
	 */
	public function msgDispather($server,$userInfoData,$context){
		//解密组装消息
		$service = new MsgDispatchService();
		$response = $service->processMsg($server,$userInfoData,$context);
		return $response;
	}
	
	
	public function handShakeRSA(){
		$service=new KeyExchangeService();
		$response=$service->handShakeRSA();
		return $response;
	}
}
