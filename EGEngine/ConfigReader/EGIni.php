<?php
/**
 * |Do the most simple game development framework
 * |++++++++++++++++++++++++++++++++++++++++++++++++++
 * |author mintingjian Date:2015-1-20 Time:下午2:36:37
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
 * Ini配置文件解析
 */
class EGIni {
	
	public static  function parse($config,$isReturnArray = false){
		if(is_file($config)) {
			return parse_ini_file($config,$isReturnArray);
		}else{
			return parse_ini_string($config,$isReturnArray);
		}
	}
}
