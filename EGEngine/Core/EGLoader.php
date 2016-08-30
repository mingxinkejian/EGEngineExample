<?php

namespace Core;

class EGLoader {
	
	//类名映射
	protected static $_classMap=[];
	//命名空间
	protected static $_namespaces=[];
	
	public function __construct(){
	
	}
	
	/**
	 * 类库自动加载
	 * @param unknown $class 对象类名
	 */
	public static function autoload($class){
		$root = explode('\\', trim($class, '\\'), 2);
	
		if (count($root) > 1 && isset(self::$_namespaces[$root[0]]))
		{
			$includePath=self::$_namespaces[$root[0]].DIRECTORY_SEPARATOR.str_replace('\\', '/', $root[1]).'.php';
			include $includePath;
		}
	}
	
	//注册classmap
	public static function addMap($class,$map=''){
		if (is_array($class)){
			self::$_classMap = array_merge(self::$_classMap, $class);
		}else{
			self::$_classMap[$class]=$map;
		}
	}
	
	// 注册自动加载机制
	public static function register($autoload = ''){
		spl_autoload_register($autoload ? $autoload : 'Core\EGLoader::autoload');
	}
	
	// 注册错误和异常处理
	public static function registerException(){
// 		register_shutdown_function('');
// 		set_error_handler('');
// 		set_exception_handler('');
	}
	
	/**
	 * 设置命名空间
	 * @param $root
	 * @param $path
	 */
	public static function addNameSpace($root, $path)
	{
		self::$_namespaces[$root] = $path;
	}
	
}
