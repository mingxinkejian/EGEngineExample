<?php

namespace Server;
/*
 * webServer 子类为httpServer或websocketServer
 */
class EGWebServer extends EGBaseServer{
	const SERVERNAME ='EGWebServer';
	
	protected $_webRoot;
	
	public function setWebRoot($webRoot){
		$this->_webRoot=$webRoot;
	}
}
