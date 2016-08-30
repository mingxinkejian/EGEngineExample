<?php

namespace Application\Common;
/*
 * 项目配置管理器
 */
class ConfigManager {
	private static $_config=array();
	
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
	/**
	 * 添加配置
	 * @param array $config
	 */
	public function addConfig(array $config){
		self::$_config = array_merge(self::$_config,$config);
	}
	/**
	 * 获取配置
	 * @param unknown $key
	 * @return multitype:|NULL
	 */
	public function getConfig($key){
		if (isset(self::$_config[$key])){
			return self::$_config[$key];
		}
		return null;
	}
}
