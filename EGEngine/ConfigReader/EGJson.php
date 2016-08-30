<?php
/**
 * |Do the most simple game development framework
 * |++++++++++++++++++++++++++++++++++++++++++++++++++
 * |author mintingjian Date:2015-1-20 Time:下午2:39:16
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
 * Json配置文件解析
 */
class EGJson {
	
	public static function parse($config){
		if(is_file($config)) {
			//读取文件解析
			$jsonStr=file_get_contents($config);
			
			return json_decode($jsonStr,true);
		}else{
			return json_decode($config,true);
		}
	}
}
