<?php

namespace DB;
abstract class EGADB implements EGIDB{

	/**
	 * 配置文件
	 * @var unknown
	 */
	protected $_config;
	/**
	 * 数据库操作句柄
	 * @var unknown
	 */
	protected $_handler=null;
	
	/**
	 * 连接数组，可以保持多个连接
	 * @var unknown
	 */
	protected $_handlerList=array();
	
	/**
	 * sql语句
	 * @var unknown
	 */
	protected $_sql='';
	
	protected $_dbErr;
	protected $_lastId;
	
	
	
	/**
	 * 初始化数据库连接
	 * @param string $isMaster
	 */
	public function initConnection($isMaster=true){
		if ($this->_config['connType']=='multi'){
			
			$this->_handler=$this->multiConnection($isMaster);
		}else{
			if (!$this->_handler ){
				$this->_handler=$this->connection($this->_config);
			}
		}
	}
	
	/**
	 * 解析配置文件
	 * @param unknown $config
	 */
	public function parseConfig($configData){
	    return $configData;
	}
	
	public function getLastSql() {
		// TODO Auto-generated method stub
		return $this->_sql;
	}
	
	
	public function getLastId() {
		// TODO Auto-generated method stub
		return $this->_lastId;
	}
	

	/**
	 * 魔术方法—调用DB对应的特定方法
	 * @param unknown $method
	 * @param unknown $args
	 * @return void|mixed
	 */
	public function __call($method,$args){
		//调用缓存类型自己的方法
		if(method_exists($this->_handler, $method)){
			return call_user_func_array(array($this->_handler,$method), $args);
		}else{
			return;
		}
	}
	
	public function __destruct(){
		// 释放查询
		if ($this->_handler){
			$this->freeResult();
		}
		// 关闭连接
		$this->close();
	}
}
