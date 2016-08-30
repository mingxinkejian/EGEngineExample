<?php

namespace Application\Protocol\ResponseProtocol;
use Application\Protocol\BaseMsgProtocol;
use Application\Protocol\ProtocolTypeId;
/*
 * 数据错误协议
 */
class DataErrorRespProtocol extends BaseMsgProtocol{
	
	public function __construct(){
		parent::__construct(ProtocolTypeId::LOGIN_RESP);
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
		
	}

}
