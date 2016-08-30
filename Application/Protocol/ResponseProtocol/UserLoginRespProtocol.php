<?php

namespace Application\Protocol\ResponseProtocol;

use Application\Protocol\BaseMsgProtocol;
use Application\Protocol\ProtocolTypeId;
class UserLoginRespProtocol extends BaseMsgProtocol{
	public $userName;
	public $nickName;
	
	public function __construct(){
		parent::__construct(ProtocolTypeId::LOGIN_RESP);
	}
	
	/* (non-PHPdoc)
	 * @see \Application\Protocol\BaseMsgProtocol::_serialize()
	 */
	public function _serialize(&$outBuffer) {
		// TODO Auto-generated method stub
		$outBuffer = json_decode(json_encode($this),true);
	}

	/* (non-PHPdoc)
	 * @see \Application\Protocol\BaseMsgProtocol::_unserialize()
	 */
	public function _unserialize(&$inBuffer) {
		// TODO Auto-generated method stub
	}

	
}
