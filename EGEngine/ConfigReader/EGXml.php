<?php
/**
 * |Do the most simple game's server framework
 * |++++++++++++++++++++++++++++++++++++++++++++++++++
 * |author mintingjian Date:2015-3-27 Time:下午4:54:41
 * |++++++++++++++++++++++++++++++++++++++++++++++++++
 * |	email:mingtingjian@sina.com                          
 * |++++++++++++++++++++++++++++++++++++++++++++++++++
 * |Copyright (c) 2015 EasyGameEngine
 * |++++++++++++++++++++++++++++++++++++++++++++++++++
 * |Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * |++++++++++++++++++++++++++++++++++++++++++++++++++
 * |Desc:
 */
namespace ConfigReader;

/*
 * Xml配置文件解析
 */
class EGXml {
	
	public static function parse($config){
		if(is_file($config)) {
			$content    =   simplexml_load_file($config);
		}else{
			$content    =   simplexml_load_string($config);
		}
		$result =   (array)$content;
		foreach($result as $key=>$val){
			if(is_object($val)) {
				$result[$key]   =   (array)$val;
			}
		}
		return $result;
	}
}
