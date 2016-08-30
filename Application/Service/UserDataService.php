<?php

namespace Application\Service;

use Application\Protocol\ResponseProtocol\UserLoginRespProtocol;
use Application\Protocol\BaseMsgProtocol;
use Application\Protocol\ProtocolTypeId;
class UserDataService extends BaseService{
		
	public function handleProtocol($server,BaseMsgProtocol $request,$context) {
		// TODO Auto-generated method stub
		$protocol = null;
		switch ($request->_pId){
			case ProtocolTypeId::LOGIN_REQ:
				$protocol = $this->userLogin($request,$context);
				break;
		}
		return $protocol;
	}

	/**
	 * 登陆处理
	 * @param unknown $msgObj
	 * @return \Application\Protocol\UserDataProtocol\UserLoginProtocol
	 */
	protected function userLogin($request,$context){		
		$userRespMsg = new UserLoginRespProtocol();
		$userRespMsg->userName = 'resp:'.$request->userName;
		$userRespMsg->nickName = 'resp:'.$request->nickName;
		return $userRespMsg;
	}
	
	
}