<?php

namespace Application\Protocol;
/***
 * 用来获取逻辑层
 */
class LogicServiceConf {
	
	/*
	 * 判断该协议号是否存在
	*/
	public static function isLogicServiceId($pId){
		$result=self::getLogicServiceId($pId);
		return isset($result);
	}
	
	public static function getLogicServiceId($pId){
		$serviceId = array(
				ProtocolTypeId::LOGIN_REQ => 'Application\Service\UserDataService'
		);
	
		return $serviceId[$pId];
	}
	
}
