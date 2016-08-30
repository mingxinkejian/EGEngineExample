<?php

namespace Application\Protocol;

class ReturnCode {
	
	
	public static function getRetCode($key){
		$retData=array(
				'RET_OK'=>array('ret'=>1000,'msg'=>'ok'),
				'RET_MSG_ERR'=>array('ret'=>-1001,'msg'=>'数据格式错误')
		);
		
		
		
		
		return $retData[$key];
	}
	
}
