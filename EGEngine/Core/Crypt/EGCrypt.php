<?php

namespace Core\Crypt;

/*
 * 加密类库 支持 AES DES Base64 Xxtea
 */
class EGCrypt {
	
	private static $_handler    =   '';
	
	const BaseURI ='Core\\Crypt\\Driver\\EG';
	
	public static function init($type='Aes'){
		$class  =   self::BaseURI.ucwords(strtolower($type));
		self::$_handler  =    $class;
	}
	
	/**
	 * 加密字符串
	 * @param string $str 字符串
	 * @param string $key 加密key
	 * @param integer $expire 有效期（秒） 0 为永久有效
	 * @return string
	 */
	public static function encrypt($data,$key,$encryptType=1,$encryptMode=1){
		if(empty(self::$handler)){
			self::init();
		}
		$class  =   self::$_handler;
		return $class::encrypt($data,$key,$encryptType,$encryptMode);
	}
	
	/**
	 * 解密字符串
	 * @param string $str 字符串
	 * @param string $key 加密key
	 * @return string
	 */
	public static function decrypt($data,$key,$encryptType=1,$encryptMode=1){
		if(empty(self::$handler)){
			self::init();
		}
		$class  =   self::$_handler;
		return $class::decrypt($data,$key,$encryptType,$encryptMode);
	}
}
