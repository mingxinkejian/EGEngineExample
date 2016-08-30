<?php

namespace Application\Common;
use DataModel\EGUserInfo;
use Application\Controller\MsgDispatcher;
/***
 * 消息分发器
 */
class MessageDispatcher {
	
	public static function dispatchMsg($server,EGUserInfo $data){
		//解析数据
		$retMsg = MsgDispatcher::getInstance()->msgDispather($server,$data,'context');
		
		return $retMsg;
	}
	
}
