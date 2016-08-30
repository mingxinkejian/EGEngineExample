<?php

namespace Application\Common;

class GateWayManager {
	
	public static $_server = null;
	
	public static function setWorkServer($server){
		GateWayManager::$_server = $server;
	}
}
