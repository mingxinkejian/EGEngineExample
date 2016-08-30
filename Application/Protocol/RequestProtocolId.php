<?php

namespace Application\Protocol;
/*
 * 客户端请求协议号
 * mingtingjian 2015-07-24 16:48:47
 */
class RequestProtocolId {
	/*
	 * 判断该协议号是否存在
	*/
	public static function isRequestId($pId){
		$result=self::getRequestId($pId);
		return isset($result);
	}
	
	public static function getRequestId($pId){
		$requestId=array(
				ProtocolTypeId::LOGIN_REQ => 'Application\Protocol\RequestProtocol\UserLoginRequestProtocol'
		);
	
		return $requestId[$pId];
	}	
}
