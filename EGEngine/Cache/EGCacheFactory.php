<?php

namespace Cache;

class EGCacheFactory {
	const REDIS = 'Redis';
	const MEMCACHED = 'Memcached';
	static $_instances = array ();
	static $_configs = array ();
	/**
	 * 获取对应实例
	 * 
	 * @param unknown $type        	
	 */
	public static function getInstance($configData,$type = self::REDIS) {
		if (empty($configData)){
			return null;
		}
		$md5    =   md5(serialize($configData));
		$name = $md5;
		
		if (empty(self::$_instances[$name])){
			$class  =   strpos($type,'\\')? $type : 'Cache\\CacheDriver\\EG'.$type;
			$instance=new $class();
			$instance->connection($configData);
			
			self::$_configs[$name]=$configData;
			self::$_instances[$name]=$instance;
		}

		return self::$_instances[$name];
	}
	/**
	 * 删除实例
	 * @param unknown $names
	 */
	public static function destoryInstance($names = array()) {
		if (empty(self::$_instances)) {
			return true;
		}
		if (empty($names)) {
			foreach (self::$_instances as $name => $redis) {
				if (self::$_configs[$name]['persistent']) {
					continue;
				}
				$redis->close();
				unset(self::$_configs[$name]);
			}
		} else {
			foreach ($names as $name) {
				if (isset(self::$_instances[$name])) {
					if (self::$_configs[$name]['persistent']) {
						continue;
					}
					self::$_instances[$name]->close();
					unset(self::$_configs[$name]);
				}
			}
		}
		return true;
	}
}
