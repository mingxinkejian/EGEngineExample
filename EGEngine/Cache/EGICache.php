<?php

namespace Cache;

interface EGICache {
	
	public function connection($config);
	
	public function addCache($key,$value,$expire);
	
	public function setCache($key,$value,$expire);
	
	public function getCache($key);
	
	public function delete($key);
		
	public function increment($key, $step = 1);
	
	public function decrement($key, $step = 1);
	
	public function clear();
}
