<?php

namespace Application\Common;
/*
 * 游戏世界管理器
 */
class GameWroldManager {
	//单例
	private static $_instance;
	public static function getInstance()
	{
		if(! (self::$_instance instanceof self) )
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	
	}
	
	public function init(){

	}
}
