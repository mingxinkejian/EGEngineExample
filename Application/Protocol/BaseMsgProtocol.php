<?php

namespace Application\Protocol;
/***
 * 抽象协议类
 */
abstract class BaseMsgProtocol {
	public $_pId;
	protected $_userInfo;
	
	public function __construct($pId){
		$this->_pId = $pId;
	}
	
	public function __set($name,$value){
		$this->$name = $value;
	}
	
	public function __get($name){
		return $this->name;
	}
	
	/**
	 * 引用类型
	 * 序列化抽象方法，用于request中,buffer是用来序列化数据用的
	 * @param unknown $outBuffer
	 */
	public abstract function _serialize(&$outBuffer);

	/**
	 * 引用类型
	 * 反序列化抽象方法，用于response中,buffer是用来序列化数据用的
	 * @param unknown $inBuffer
	 */
	public abstract function _unserialize(&$inBuffer);
}