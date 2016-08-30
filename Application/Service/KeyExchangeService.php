<?php

namespace Application\Service;
use Core\Crypt\EGRSACrypt;
use Application\Protocol\ReturnCode;
use Application\Protocol\ResponseProtocol\RSAKeyRespProtocol;
use Application\Protocol\BaseMsgProtocol;
/*
 * 交换秘钥
 */
class KeyExchangeService extends BaseService{
	const RSA_PRI_KEY='-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQCm8r8B01f5mG1aoxcydBqkK1thk8+5E97Omk7F5IBj2LVrwjuy
IV1XWuW6AFq275vvH++qbhj333Zh5qMvEvkcpwMN5jx1MDzHv0vfUYcM8jOyk23f
2aUVAw3zcPa+3Y81q7nslm8/5kNYzklW+FbVScxWhF2E7dSUkNVgk+JYiwIDAQAB
AoGAOOlFbdTjB6YLmTtBnCRyNIbHNwNRa5E3ggyeT92uWts0NVDYUsgG7tGAwKCA
TB5wdNrZM6smfdFCpOsfs9tu6eWcBROb21FhbMWdDaIs8gVHU8uSR0th0vfIb+Y4
4gtARjYlFHKxtimAF76FpvqUADqXNBV0UjLDxktXIR7fwZkCQQDQ9KavMAaAXB2X
wJeYEZtSXdgbgfoUaW4diudW6luwwWpBCXOShMTwS8bw8FVXwIjtjtfJCJSpY13N
i0Ojbx3FAkEAzIj1SO+6ogbjql7i2bVhpfY5ONcXCRcj7W/M2nRuCU/+v0ZHFKTV
7e567bqp3583JLTF3yLA0M/xWCsQ5WTSDwJBAIHrW6gZSDGPFKdCC/91EwnHZ5LS
4dbHJrwfnW8hEBl6aMI/SiiTfI+bzfoItEd3Yqux4OESALzBF5WxPUDk2bECQAIh
r0S6Utyba8X7xSt1q3XaUhBYlYHfvt0bnCvuqLLqJYKCzfzWFvCq2pcdqeWNuDpm
s+U2bIIAmMhNcpiha1sCQEOC15oQzOfY8BoCnUVwWCnZ5OfuRGfas3EEI3HKdPDL
1XhE2lnQScsPozf+wWX04H38E8lPv4kgH/jQTlMjD2E=
-----END RSA PRIVATE KEY-----
';
	
	const RSA_PUB_KEY='-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCm8r8B01f5mG1aoxcydBqkK1th
k8+5E97Omk7F5IBj2LVrwjuyIV1XWuW6AFq275vvH++qbhj333Zh5qMvEvkcpwMN
5jx1MDzHv0vfUYcM8jOyk23f2aUVAw3zcPa+3Y81q7nslm8/5kNYzklW+FbVScxW
hF2E7dSUkNVgk+JYiwIDAQAB
-----END PUBLIC KEY-----
	';
	private $_priKey;
	
	public function __construct(){
		//秘钥交换路径
		$pubKeyPath = dirname(__FILE__).'/../' . 'RSAKey/gamecell_private_key.pem';
		
		$this->_priKey = file_get_contents($pubKeyPath);
	}
	
	/* (non-PHPdoc)
	 * @see \Application\Service\BaseService::handleProtocol()
	*/
	public function handleProtocol($server,BaseMsgProtocol $request,$context) {
		// TODO Auto-generated method stub
		return $this->exchangeDataKey();
	}
	
	/**
	 * 使用RSA算法进行秘钥交换，服务器端私钥加密，客户端公钥解密
	 * 此key为之后进行的数据交换的加密秘钥
	 * @return \Application\Protocol\CommProtocol\ExchangeKeyProtocol
	 */
	public function exchangeDataKey(){
		$exKeyProtocol = new RSAKeyRespProtocol();
		$key = $this->generateCommunicationKey(false);
		$exKeyProtocol->_key = $key;// EGRSACrypt::priKeyEncode($key, self::RSA_PRI_KEY);
		return $exKeyProtocol;
	}
	
	public function handShakeRSA(){
		$retCodeArray=ReturnCode::getRetCode('RET_OK');	
		$msg = $this->exchangeDataKey();
		
		$msg->_key = base64_encode($msg->_key);
	
		$retData['ret']=$retCodeArray['ret'];
		$retData['msg']=$retCodeArray['msg'];
		$retData['data']=$msg;
		$retData['timestamp']=time();
	
// 		$retData=str_replace("\\/", "/",  json_encode($retData));
// 		$retData=zlib_encode($retData,ZLIB_ENCODING_DEFLATE,6);
	
		$ret =base64_encode(json_encode($retData));
	
		return $ret;
	}
	
	/**
	 * 生成通信秘钥
	 * @param string $isFromCache
	 */
	public function generateCommunicationKey(){
		$key = 'abcdefghigklmn';
		return $key;
	}

}
