<?php

namespace Cache\CacheDriver;

use Cache\EGABaseCache;
use Exception\EGException;
class EGRedis extends EGABaseCache{
	

	public function __construct(){
		if (extension_loaded('redis')){
			
		}
		$this->_handler=new \Redis();
	}
	
	/* 
	 * 连接支持主从分布式处理，设置是否为主库即可，否则将连接从库
	 * @see \Cache\EGABaseCache::connection()
	 */
	public function connection($config){
		$this->_config=$config;
		
		$host=$config['host'];
		$port=$config['port'];
		
		$isMaster=$config['isMaster'];
		$connIndex=0;
		if ($isMaster==false){
			$count=count($host);
			if ($count>1){
				$connIndex=rand(1, $count-1);
				$this->_handler->connect($host[$connIndex],$port);
			}
		}
		
		if ($config['conType']=='pconnect'){
			$this->_handler->pconnect($host[$connIndex],$port,$config['timeout']);
		}else{
			$this->_handler->connect($host[$connIndex],$port);
		}
		
		if(count($config['auth'])>0){
			$this->_handler->auth($this->_config['auth'][$connIndex]);
		}
	}
	
	/* 
	 * 数据会进行序列化处理，目前暂只支持json序列化，后期会加入其他序列化方式
	 * @see \Cache\EGABaseCache::addCache()
	 */
	public function addCache($key, $value, $expire=0 ) {

		// TODO Auto-generated method stub
		return $this->setCache($key, $value, $expire);
	}


	public function setCache($key, $value, $expire=0 ) {
		// TODO Auto-generated method stub
		try {
			if($expire==0) {
				$expire  =  isset($this->_config['expire'])? $this->_config['expire']: 0;
			}
			//对数组/对象数据进行缓存处理，保证数据完整性
			$value  =  (is_object($value) || is_array($value)) ? json_encode($value) : $value;
			if($expire > 0) {
				$result = $this->_handler->setex($key, $expire, $value);
			}else{
				$result = $this->_handler->set($key, $value);
			}
		} catch (EGException $e) {
			EGException::appException($e);
		}
		return $result;
	}


	public function getCache($key) {
		// TODO Auto-generated method stub
		
		$value = $this->_handler->get($key);
		$jsonData  = json_decode( $value, true );
		return (empty($jsonData)) ? $value : $jsonData;	//检测是否为JSON数据 true 返回JSON解析数组, false返回源数据
	}


	public function delete($key) {
		// TODO Auto-generated method stub
		
		return $this->_handler->delete($key);
	}

	/* 
	 * 适用于数值类型
	 * @see \Cache\EGABaseCache::increment()
	 */
	public function increment($key, $step = 1) {
		// TODO Auto-generated method stub
		
		return $this->_handler->incrBy($key, $step);
	}

	/*
	 * 适用于数值类型
	* @see \Cache\EGABaseCache::increment()
	*/
	public function decrement($key, $step = 1) {
		// TODO Auto-generated method stub
		
		return $this->_handler->decrBy($key, $step);
	}

	/**
	 * 清除所有键及缓存
	 * @access public
	 * @return boolean
	 */
	public function clear() {
		// TODO Auto-generated method stub
		
		return $this->_handler->flushDB();
	}
	
	/*
	 * 定时ping Redis服务器，如果出现连接失败，重连
	 */
	public function ping(){
		if (empty($this->_handler->ping())){
			$this->connection($this->_config);
		}
	}
	

	public function __destruct(){
		if ($this->_config['conType']=='pconnect'){
			
			$this->_handler->close();
		}
	}
	
}
