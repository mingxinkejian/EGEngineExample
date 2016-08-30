<?php

namespace Application\Service;

use Application\Protocol\ProtocolPack;
use Application\Protocol\ResponseProtocol\DataErrorRespProtocol;
use Application\Protocol\ReturnCode;
use Application\Protocol\RequestProtocolId;
use DataModel\EGUserInfo;
use Application\Protocol\LogicServiceConf;
/*
 * 消息分发逻辑
 */
class MsgDispatchService {
	
	/**
	 * 消息处理
	 * @param unknown $request
	 * @param unknown $context
	 * @return string
	 */
	public function processMsg($server,EGUserInfo $request,$context){
		//$serialData可以随时替换为其他的数据结构
		$serialData = array();
		//$unserialData可以随时替换为其他的数据结构
		$unserialData = array();
		//解析数据
		$reqMsgObj=ProtocolPack::packDecode($request->_data, '1234567812345678');
		
		if (empty($reqMsgObj)){
			$response=$this->packErrorDataMsg();
			$response->_userInfo = $request;
			$response->_serialize($serialData);
			
			return $this->packData('RET_MSG_ERR',$serialData);
		}
		
		if (!RequestProtocolId::isRequestId($reqMsgObj['pId'])){
			$response=$this->packErrorDataMsg();
			$response->_userInfo = $request;
			$response->_serialize($serialData);
			
			return $this->packData('RET_MSG_ERR',$serialData);
		}
		
		if (!LogicServiceConf::isLogicServiceId($reqMsgObj['pId'])){
			$response=$this->packErrorDataMsg();
			$response->_userInfo = $request;
			$response->_serialize($serialData);
			
			return $this->packData('RET_MSG_ERR',$serialData);
		}
		$unserialData = $reqMsgObj;
		//处理消息协议
		$requestProtocolClassName = RequestProtocolId::getRequestId($reqMsgObj['pId']);
		$requestClassObj = new $requestProtocolClassName;
		$requestClassObj->_userInfo = $request;
		$requestClassObj->_unserialize($unserialData);
		//根据协议号获取service层
		$serviceClassName = LogicServiceConf::getLogicServiceId($reqMsgObj['pId']);
		$serviceClassObj = new $serviceClassName();
		$serviceClassObj->_userInfo = $request;
		$response = $serviceClassObj->handleProtocol($server,$requestClassObj,$context);
		$response->_serialize($serialData);
		
		return $this->packData('RET_OK',$serialData);
	}
	
	
	public function packErrorDataMsg(){
		$errorProtocol=new DataErrorRespProtocol();
		return $errorProtocol;
	}
	
	
	public function packData($retCodeKey,$data){
		
		$retCodeArray=ReturnCode::getRetCode($retCodeKey);
		
		$retData['ret']=$retCodeArray['ret'];
		$retData['msg']=$retCodeArray['msg'];
		$retData['data']=$data;
		$retData['timestamp']=time();
		
		
		$enCodeData=ProtocolPack::packEncode($retData, '1234567812345678');
		
		return $enCodeData;
	}
	
}
