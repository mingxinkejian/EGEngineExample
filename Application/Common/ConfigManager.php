<?php

namespace Application\Common;
/*
 * 项目配置管理器
 */
class ConfigManager {
	private static $_appconfig=array();
	private static $_config = array();
	
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
	public function addAppConfig(array $config){
		self::$_appconfig = array_merge(self::$_appconfig,$config);
	}
	/**
	 * 获取配置
	 * @param unknown $key
	 * @return multitype:|NULL
	 */
	public function getAppConfig($key){
		if (isset(self::$_appconfig[$key])){
			return self::$_appconfig[$key];
		}
		return null;
	}
	
	/**
	 * 添加配置
	 * @param array $config
	 */
	public function addConfig($configKey,array $config){
		self::$_config[$configKey] = array_merge(self::$_config[$configKey],$config);
	}
	/**
	 * 获取配置
	 * @param unknown $key
	 * @return multitype:|NULL
	 */
	public function getConfig($configKey,$key){
		if (isset(self::$_config[$configKey][$key])){
			return self::$_config[$configKey][$key];
		}
		return null;
	}
}
