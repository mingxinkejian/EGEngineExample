<?php

namespace DB;

class EGDBFactory {
	
	const DBTYPE_MYSQL='MySqlDB';
	const DBTYPE_MONGODB='MongoDB';
	const DBTYPE_PDO='PdoDB';
	
	static $_instances=array();
	static $_configs=array();
	
	public static function getInstance($configData,$type=self::DBTYPE_MYSQL){
		if (empty($configData)){
			return null;
		}
		$md5    =   md5(serialize($configData));
		$name = $md5;
		
		if (!isset(self::$_instances[$name])){
			$class  =   strpos($type,'\\')? $type : 'DB\\DBDriver\\EG'.$type;
			$instance=new $class($configData);	
			self::$_configs[$name]=$configData;
			self::$_instances[$name]=$instance;
		}
		
		return self::$_instances[$name];
	}
}
