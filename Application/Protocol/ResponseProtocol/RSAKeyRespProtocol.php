<?php

namespace Application\Protocol\ResponseProtocol;
use Application\Protocol\BaseMsgProtocol;
use Application\Protocol\ProtocolTypeId;

class RSAKeyRespProtocol extends BaseMsgProtocol {

	public $_key;
	
	public function __construct(){
		parent::__construct(ProtocolTypeId::EXCHANGE_KEY_RESP);
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
