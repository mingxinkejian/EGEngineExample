<?php

namespace Cache;

class EGABaseCache implements EGICache{
	
	protected $_handler;
	
	protected $_config;
	
	
	/**
	 * 魔术方法—调用Cache对应的特定方法
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
	/* (non-PHPdoc)
	 * @see \Cache\EGICache::connection()
	 */
	public function connection($config) {
		// TODO Auto-generated method stub
		
	}

	/* (non-PHPdoc)
	 * @see \Cache\EGICache::addCache()
	 */
	public function addCache($key, $value, $expire) {
		// TODO Auto-generated method stub
		
	}

	/* (non-PHPdoc)
	 * @see \Cache\EGICache::setCache()
	 */
	public function setCache($key, $value, $expire) {
		// TODO Auto-generated method stub
		
	}

	/* (non-PHPdoc)
	 * @see \Cache\EGICache::getCache()
	 */
	public function getCache($key) {
		// TODO Auto-generated method stub
		
	}

	/* (non-PHPdoc)
	 * @see \Cache\EGICache::delete()
	 */
	public function delete($key) {
		// TODO Auto-generated method stub
		
	}

	/* (non-PHPdoc)
	 * @see \Cache\EGICache::increment()
	 */
	public function increment($key, $step = 1) {
		// TODO Auto-generated method stub
		
	}

	/* (non-PHPdoc)
	 * @see \Cache\EGICache::decrement()
	 */
	public function decrement($key, $step = 1) {
		// TODO Auto-generated method stub
		
	}

	/* (non-PHPdoc)
	 * @see \Cache\EGICache::clear()
	 */
	public function clear() {
		// TODO Auto-generated method stub
		
	}


	public function ping(){
		
	}

}
