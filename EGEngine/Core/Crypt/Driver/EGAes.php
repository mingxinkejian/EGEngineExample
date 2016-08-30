<?php
namespace Core\Crypt\Driver;

/*
 * Aes加密类库
 * 
 * 必须安装mcrypt扩展库
 * 默认方式为 128位 ECB模式加密 base64编码
 */
class EGAes {
	//生成秘钥时的IV向量，默认使用16个0
	const DEFAULT_AES_IV ='0000000000000000';
	
	const AES_128=1;
	const AES_192=2;
	const AES_256=3;
	
	const AES_MODE_CBC=1;
	const AES_MODE_CFB=2;
	const AES_MODE_ECB=3;
	const AES_MODE_NOFB=4;
	const AES_MODE_OFB=5;

	const PAD_METHOD='pkcs5';
	/**
	 * 加密函数
	 * @param unknown $str 加密字符串
	 * @param unknown $key 秘钥
	 * @param unknown $encryptType 加密位数
	 * @param unknown $encryptMode 加密方式
	 * @param string $isBase64 是否使用base64
	 * @return string
	 */
	public static function encrypt($str, $key,$encryptType=EGAes::AES_128,$encryptMode=EGAes::AES_MODE_ECB) {

		switch ($encryptMode){
			case EGAes::AES_MODE_ECB:
				$encrypted = self::aesEncrypt_ECB($str, $key, $encryptType, $encryptMode);
				break;
			case EGAes::AES_MODE_CBC:
				$encrypted = self::aesEncrypt_CBC($str, $key, $encryptType, $encryptMode);
				break;
		}
		
		return $encrypted;
	}

	/**
	 * 解密函数
	 * @param unknown $str 加密字符串
	 * @param unknown $key 秘钥
	 * @param unknown $encryptType 加密位数
	 * @param unknown $encryptMode 加密方式
	 * @param string $isBase64 是否使用base64
	 * @return string
	 */
	public static function decrypt($str, $key,$encryptType=EGAes::AES_128,$encryptMode=EGAes::AES_MODE_ECB) {

		switch ($encryptMode){
			case EGAes::AES_MODE_ECB:
				$decrypted = self::aesDecrypt_ECB($str, $key, $encryptType, $encryptMode);
				break;
			case EGAes::AES_MODE_CBC:
				$decrypted = self::aesDecrypt_CBC($str, $key, $encryptType, $encryptMode);
				break;
		}
		
		return $decrypted;
	}
	
	
	private static function aesEncrypt_ECB($str, $key,$encryptType,$encryptMode){
		$type = self::getEnType($encryptType);
		$mode = self::getMode($encryptMode);
		
		$size = mcrypt_get_block_size($type, $mode);
		$input = self::pkcs5_pad($input, $size);
		$td = mcrypt_module_open($type, '', $mode, '');
		$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		mcrypt_generic_init($td, $key, $iv);
		$data = mcrypt_generic($td, $input);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);

		return $data;
	}
	private static function aesDecrypt_ECB($str, $key,$encryptType,$encryptMode){
		$type = self::getEnType($encryptType);
		$mode = self::getMode($encryptMode);
		
		$decrypted= mcrypt_decrypt($type,$key,$str,$mode);
		$dec_s = strlen($decrypted);
		$padding = ord($decrypted[$dec_s-1]);
		$decrypted = substr($decrypted, 0, -$padding);
		return $decrypted;
	}
	private static function aesEncrypt_CBC($str, $key,$encryptType,$encryptMode){
		
	}
	private static function aesDecrypt_CBC($str, $key,$encryptType,$encryptMode){
		
	}
	
	private static function getEnType($encryptType){
		$type=MCRYPT_RIJNDAEL_128;
		switch ($encryptType){
			case EGAes::AES_128:
				$type=MCRYPT_RIJNDAEL_128;
				break;
			case EGAes::AES_192:
				$type=MCRYPT_RIJNDAEL_192;
				break;
			case EGAes::AES_256:
				$type=MCRYPT_RIJNDAEL_256;
				break;
		}
		
		return $type;
	}
	private static function getMode($modeType){
		$mode=MCRYPT_MODE_ECB;
		switch ($modeType){
			case EGAes::AES_MODE_CBC:
				$mode=MCRYPT_MODE_CBC;
				break;
			case EGAes::AES_MODE_CFB:
				$mode=MCRYPT_MODE_CFB;
				break;
			case EGAes::AES_MODE_ECB:
				$mode=MCRYPT_MODE_ECB;
				break;
			case EGAes::AES_MODE_NOFB:
				$mode=MCRYPT_MODE_NOFB;
				break;
			case EGAes::AES_MODE_OFB:
				$mode=MCRYPT_MODE_OFB;
				break;
		}
		
		return $mode;
	}

	private static function pkcs5_pad ($text, $blocksize) {
		$pad = $blocksize - (strlen($text) % $blocksize);
		return $text . str_repeat(chr($pad), $pad);
	}
}
