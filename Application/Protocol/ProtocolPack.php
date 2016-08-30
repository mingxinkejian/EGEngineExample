<?php

namespace Application\Protocol;
use Exception\EGException;
/*
 * 数据打包解包工具
 */
class ProtocolPack {
	
	const HeartBeats_REQ='{H*B}';
	const HeartBeats_RESP='{H_B}';
	
	/*
	 * 数据包使用json格式，传输时经过压缩加密处理
	 * 因为AES加密与C++客户端有问题，故暂时屏蔽，数据不加密
	 * 1、将数据转换为json格式
	 * 2、压缩数据zlib压缩 （ZLIB_ENCODING_DEFLATE）
	 * 3、加密(屏蔽)
	 * 4、base64编码
	 * 5、返回包
	 */
// 	public static function packEncode($data,$secret){
// 		$cryptData=null;
// 		try {
// 			//序列化
// 			$cryptData=str_replace("\\/", "/",  json_encode($data));
// 			//压缩
// 			$cryptData=zlib_encode($cryptData,ZLIB_ENCODING_DEFLATE,6);
			
// // 			var_dump(strtoupper(bin2hex($cryptData)));
// // // 			//加密
// // 			EGCrypt::init();
// // 			$cryptData=EGCrypt::encrypt($cryptData, $secret,EGAes::AES_128,EGAes::AES_MODE_ECB);
// // 			var_dump(strlen($cryptData));
// // 			var_dump('3 step bin:'.strtoupper(bin2hex($cryptData)));
// 			//base64 编码
// 			$cryptData=base64_encode($cryptData);
// 		} catch (EGException $e) {
// 			EGException::appException($e);
// 		}

// 		return $cryptData;
// 	}
	public static function packEncode($data,$secret){
		try {
			$cryptData=json_encode($data);
		} catch (EGException $e) {
			EGException::appException($e);
		}
	
		return $cryptData;
	}
	/*
	 * 数据包使用json格式，传输时经过压缩加密处理
	 * 因为AES加密与C++客户端有问题，故暂时屏蔽，数据不加密
	 * 1、base64解码
	 * 2、解密(屏蔽)
	 * 3、解压缩(zlib)
	 * 4、将字符串数据反序列化
	 * 5、返回数据
	*/
// 	public static function packDecode($data,$secret){
// 		$deCryptData=null;
// 		try {
// 			//base64解码
// 			$deCryptData=base64_decode($data);
// // 			var_dump(strtoupper(bin2hex($deCryptData)));
// 			//解密
// // 			EGCrypt::init();
// // 			$deCryptData=EGCrypt::decrypt($deCryptData, $secret,EGAes::AES_128,EGAes::AES_MODE_ECB);
// // 			var_dump(strtoupper(bin2hex($deCryptData)));
// // 			//解压缩
// // 			if (empty($deCryptData)){
// // 				return $deCryptData;
// // 			}
// 			$deCryptData=zlib_decode($deCryptData);
// // 			var_dump('uncompress:'.$deCryptData);
// 			//反序列化
// 			$deCryptData=json_decode($deCryptData);
// 		} catch (EGException $e) {
// 			EGException::appException($e);
// 		}
		
// 		return $deCryptData;
// 	}
	public static function packDecode($data,$secret){
		try {
			$deCryptData=json_decode($data,true);
		} catch (EGException $e) {
			EGException::appException($e);
		}
		return $deCryptData;
	}
}
