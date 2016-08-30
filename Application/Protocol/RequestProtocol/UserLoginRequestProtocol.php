<?php

namespace Application\Protocol\RequestProtocol;

use Application\Protocol\BaseMsgProtocol;
use Application\Protocol\ProtocolTypeId;
class UserLoginRequestProtocol extends BaseMsgProtocol{
	public $userName;
	public $nickName;
	public function __construct(){
		parent::__construct(ProtocolTypeId::LOGIN_REQ);
	}
	/* (non-PHPdoc)
	 * @see \Application\Protocol\BaseMsgProtocol::_serialize()
	 */
	public function _serialize(&$outBuffer) {
		// TODO Auto-generated method stub
	}

	/* (non-PHPdoc)
	 * @see \Application\Protocol\BaseMsgProtocol::_unserialize()
	 */
	public function _unserialize(&$inBuffer) {
		// TODO Auto-generated method stub
		$this->userName = $inBuffer['userName'];
		$this->nickName = $inBuffer['nickName'];
	}

	
}
