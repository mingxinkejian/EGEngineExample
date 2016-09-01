<?php

namespace Extension;
/**
 * 
 * |Do the most simple game's server framework
 * |++++++++++++++++++++++++++++++++++++++++++++++++++
 * |author mintingjian Date:2016-7-13 Time:下午5:40:29
 * |++++++++++++++++++++++++++++++++++++++++++++++++++
 * |	email:mingtingjian@sina.com                          
 * |++++++++++++++++++++++++++++++++++++++++++++++++++
 * |Copyright (c) 2015 EasyGameEngine
 * |++++++++++++++++++++++++++++++++++++++++++++++++++
 * |Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * |++++++++++++++++++++++++++++++++++++++++++++++++++
 * |Desc:用于封装二进制流
 */
class EGSockBuffer {
	//大端
	const SOCKBUFFER_BIG = 1;
	//小端
	const SOCKBUFFER_LITTLE = 2;
	
	private $_datas = '';
	public $_rPos = 0;
	public $_wPos = 0;
	private$_endian;
	public function __construct($endian = EGSockBuffer::SOCKBUFFER_BIG){
		$this->_endian = $endian;
	}
	
	public function data(){
		return $this->_datas;
	}
	
	public function setData($data){
		$this->_datas = $data;
		$this->_wPos += strlen($data);
	}
	/**
	 * 读取字节
	 * @param unknown $len
	 * @return boolean|string
	 */
	public function readBytes($len){
		if ($len < 1) {
			return false;
		}
		$str = substr($this->_datas, $this->_rPos, $len);
		$this->_rPos += $len;
		return $str;
	}
	/**
	 * 写入字节
	 * @param unknown $bytes
	 */
	public function writeBytes($bytes) {
		$this->_datas .= $bytes;
		$this->_wPos += strlen ( $bytes );
	}
	/**
	 * test ok
	 * @return Ambigous <>
	 */
	public function readInt8(){
		$bytes = $this->readBytes(1);
		$result = unpack('c', $bytes);
		return $result[1];
	}
	/**
	 * test ok
	 * @return Ambigous <>
	 */
	public function readInt16(){
		$bytes = $this->readBytes(2);
		$result = unpack('s', $bytes);
		return $result[1];
	}
	/**
	 * test ok
	 * @return Ambigous <>
	 */
	public function readInt32(){
		$bytes = $this->readBytes(4);
		$result = unpack('l', $bytes);
		return $result[1];
	}
	/**
	 * test ok
	 * @return Ambigous <>
	 */
	public function readInt64(){
		$bytes = $this->readBytes(8);
		
		if ($this->_endian == EGSockBuffer::SOCKBUFFER_BIG){
			list ($hi,$lo) = array_values(unpack('N*N*',strrev($bytes)));
		}else{
			list ($hi,$lo) = array_values(unpack('N*N*',$bytes));
		}
		if ($hi <0) $hi += (1 << 32);
		if ($lo <0) $lo += (1 << 32);
		return ($hi << 32) + $lo;
	}
	/**
	 * test ok
	 * @return Ambigous <>
	 */
	public function readUint8(){
		$bytes = $this->readBytes(1);
		$result = unpack('C', $bytes);
		return $result[1];
	}
	/**
	 * test ok
	 * @return Ambigous <>
	 */
	public function readUint16(){
		$bytes = $this->readBytes(2);
		if ($this->_endian == EGSockBuffer::SOCKBUFFER_BIG){
			$result = unpack('n', $bytes);
		}else{
			$result = unpack('v', $bytes);
		}
		return $result[1];
	}
	/**
	 * test ok
	 * @return Ambigous <>
	 */
	public function readUint32(){
		$bytes = $this->readBytes(4);
		if ($this->_endian == EGSockBuffer::SOCKBUFFER_BIG){
			$result = unpack('N', $bytes);
		}else{
			$result = unpack('V', $bytes);
		}
		return $result[1];
	}
	
	public function readUint64(){
		$bytes = $this->readBytes(8);
		if ($this->_endian == EGSockBuffer::SOCKBUFFER_BIG){
			list ($hi,$lo) = array_values(unpack('NN',$bytes));
			$lo << 32;
		}else{
			list ($hi,$lo) = array_values(unpack('N*N*',$bytes));
			$hi << 32;
		}
		return ($hi << 32) + $lo;
	}
	/**
	 * test ok
	 * @return Ambigous <>
	 */
	public function readFloat(){
		$bytes = $this->readBytes(4);
		if ($this->_endian == EGSockBuffer::SOCKBUFFER_BIG){
			$result = unpack('f', strrev($bytes));
		}else{
			$result = unpack('f', $bytes);
		}
		return $result[1];
	}
	/**
	 * test ok
	 * @return Ambigous <>
	 */
	public function readDouble(){
		$bytes = $this->readBytes(8);
		if ($this->_endian == EGSockBuffer::SOCKBUFFER_BIG){
			$result = unpack('d', strrev($bytes));
		}else{
			$result = unpack('d', $bytes);
		}
		return $result[1];
	}
	/**
	 * test ok
	 * @return Ambigous <>
	 */
	public function readString(){
		$len = $this->readUint32();
        if($len <=0){
            return false;
        }
        
        $bytes = $this->readBytes($len);
        $result = unpack('a*', $bytes);
        return $result[1];
	}
	/**
	 * test ok
	 * @return Ambigous <>
	 */
	public function writeInt8($value){
		$this->writeBytes(pack('c', $value));
	}
	/**
	 * test ok
	 * @return Ambigous <>
	 */
	public function writeInt16($value){
		$this->writeBytes(pack('s', $value));
	}
	/**
	 * test ok
	 * @return Ambigous <>
	 */
	public function writeInt32($value){
		$this->writeBytes(pack('l', $value));
	}
	/**
	 * test ok
	 * @return Ambigous <>
	 */
	public function writeInt64($value){
		if ($this->_endian == EGSockBuffer::SOCKBUFFER_BIG){
			$this->writeBytes(strrev(pack('N*N*',$value >> 32,$value&0xFFFFFFFF)));
		}else{
			$this->writeBytes(pack('N*N*',$value >> 32,$value&0xFFFFFFFF));
		}
	}
	/**
	 * test ok
	 * @return Ambigous <>
	 */
	public function writeUint8($value){
		$this->writeBytes(pack('C', $value));
	}
	/**
	 * test ok
	 * @return Ambigous <>
	 */
	public function writeUint16($value){
		if ($this->_endian == EGSockBuffer::SOCKBUFFER_BIG){
			$this->writeBytes(pack('n', $value));
		}else{
			$this->writeBytes(pack('v', $value));
		}
		
	}
	/**
	 * test ok
	 * @return Ambigous <>
	 */
	public function writeUint32($value){
		if ($this->_endian == EGSockBuffer::SOCKBUFFER_BIG){
			$this->writeBytes(pack('N', $value));
		}else{
			$this->writeBytes(pack('V', $value));
		}
	}
	/**
	 * 
	 * @param unknown $value
	 */
	public function writeUint64($value){
		
		$h = ($value & 0xFFFFFFFF00000000) >> 32;
		$l = $value & 0xFFFFFFFF;
		
		if ($this->_endian == EGSockBuffer::SOCKBUFFER_BIG){
			$this->writeBytes(pack('NN', $l, $h));
		}else{
			$this->writeBytes(pack('NN', $h, $l));
		}		
	}
	/**
	 * test ok
	 * @return Ambigous <>
	 */
	public function writeFloat($value){
		if ($this->_endian == EGSockBuffer::SOCKBUFFER_BIG){
			$this->writeBytes(strrev(pack('f', $value)));
		}else{
			$this->writeBytes(pack('f', $value));
		}
	}
	/**
	 * test ok
	 * @return Ambigous <>
	 */
	public function writeDouble($value){
		if ($this->_endian == EGSockBuffer::SOCKBUFFER_BIG){
			$this->writeBytes(strrev(pack('d', $value)));
		}else{
			$this->writeBytes(pack('d', $value));
		}
	}
	/**
	 * test ok
	 * @return Ambigous <>
	 */
	public function writeString($value){
		$len = strlen($value);
		$this->writeUint32($len);
		$this->writeBytes(pack('a'. $len, $value));
	}
	
	public function length(){
		return $this->_wPos - $this->_rPos;
	}
	
	public static function testBuffer(){

		$sockBuff = new EGSockBuffer();
		$sockBuff->writeInt8(127);
		$sockBuff->writeInt16(-32768);
		$sockBuff->writeInt32(-2147483648);
		$sockBuff->writeInt64 (9223372036854775800);
		$sockBuff->writeUint8(255);
		$sockBuff->writeUint16(1000);
		$sockBuff->writeUint32(4294967295);		
		$sockBuff->writeFloat(100);
		$sockBuff->writeDouble(2500);
		$sockBuff->writeString('{"hello":"test中国"}');
		
		$result = $sockBuff->readInt8();
		echo ($result) .PHP_EOL;
		$result = $sockBuff->readInt16();
		echo ($result) .PHP_EOL;
		$result = $sockBuff->readInt32();
		echo ($result) .PHP_EOL;
		$result = $sockBuff->readInt64();
		echo ($result) .PHP_EOL;
		$result = $sockBuff->readUint8();
		echo ($result) .PHP_EOL;
		$result = $sockBuff->readUint16();
		echo ($result) .PHP_EOL;
		$result = $sockBuff->readUint32();
		echo ($result) .PHP_EOL;
		$result = $sockBuff->readFloat();
		echo ($result) .PHP_EOL;
		$result = $sockBuff->readDouble();
		echo ($result) .PHP_EOL;
		$result = $sockBuff->readString();
		echo ($result) .PHP_EOL;
		return ;
	}
}
