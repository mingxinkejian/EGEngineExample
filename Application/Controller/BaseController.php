<?php

namespace Application\Controller;
/*
 * 控制器基类
 */
class BaseController {
	
	public function __construct(){
	}
	
	public function __call($method,$args){
		
		if(method_exists($this, $method)){
			return call_user_func_array(array($this,$method), $args);
		}else{
		}
	}
	/*
	 * 返回数据
	 */
	public function retClientData($retPId,$retCode,$msg,$data,$timestamp){
		//判断返回码
		$timestamp=time();
		
		$data = array (
				'pId'=>$retPId,
				'ret' => $retCode,
				'msg' =>$msg,
				'data' => $data,
				'timestamp' => $timestamp
		);
		
		$jsonStr=str_replace("\\/", "/",  json_encode($data));
		$compressData=gzdeflate($jsonStr,6,ZLIB_ENCODING_GZIP);
		$base64Str=base64_encode($compressData);
		
		return $base64Str;
	}
}
