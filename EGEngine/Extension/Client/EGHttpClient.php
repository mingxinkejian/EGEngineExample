<?php

namespace Extension\Client;

class EGHttpClient extends EGBaseClient{
	private $_timeout = 60; //默认超时时间
	private $_url;
	private $_uri;
	private $_requestHeader;
	private $_isError = false;
	private $_isFinish = false;
	private $_status = array();
	private $_respHeader = array();
	private $_body = '';
	private $_trunkLength = 0;
	private $_userAgent = 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36';
	private $_onReadyCallback;
	private $_postData;
	private $_method = 'GET'; //默认使用get方式
	private $_isAsync = false;


	function __construct($url){
		$this->_url = $url;
		$this->uri = parse_url($this->_url);
	
		if (empty($this->_uri['port'])){
			$this->_uri['port'] = self::DEFAULT_PORT;
		}
	}
	
	/**
	 * 解析头文件
	 * @param unknown $data
	 */
	private function parseHeader($data){
		$expData = explode("\r\n\r\n", $data,2);
		//头信息拆分
		$headerDataArray = explode("\r\n", $expData[0]);
		// HTTP协议头,方法，路径，协议[RFC-2616 5.1]
		list($status['method'], $status['uri'], $status['protocol']) = explode(' ', $headerDataArray[0], 3);
		//错误的HTTP请求
		if (empty($status['method']) or empty($status['uri']) or empty($status['protocol'])){
			return false;
		}
		unset($headerDataArray[0]);
		
		//解析header
		$this->_respHeader = EGHttpParser::parseHeader($headerDataArray);
		$this->_status = $status;
		if (isset($expData[1])) {
			$this->_buffer = $expData[1];
		}
		return true;
	}
	/**
	 * 输出error log
	 * @param unknown $line
	 * @param unknown $msg
	 */
	private function errorLog($line, $msg) {
        echo "Line $line: $msg\n";
    }
    
    private function parseBody(){
    	//解析trunk
    	if (isset($this->_respHeader['Transfer-Encoding']) and $this->_respHeader['Transfer-Encoding'] == 'chunked'){
    		while (true){
    			if ($this->_trunkLength == 0){
    				$len = strstr($this->_buffer, "\r\n",true);
    				if ($len === false){
    					$this->errorLog(__LINE__, "trunk: length is error ,$len");
    					return false;
    				}
    				$length = hexdec($len);
    				if ($length == 0){
    					$this->_isFinish = true;
    					return true;
    				}
    				$this->_trunkLength = $length;
    				$this->_buffer = substr($this->_buffer, strlen($len),2);
    			}else{
    				if (strlen($this->_buffer) < $this->_trunkLength){
    					return false;
    				}
    				
                    $this->_body .= substr($this->_buffer, 0, $this->_trunkLength);
                    $this->_buffer = substr($this->_buffer, $this->_trunkLength + 2);
                    $this->_trunkLength = 0;
    			}
    		}
    		return false;
    	}else{
    		//普通的Content-Length约定
    		if (strlen($this->_buffer) < $this->_respHeader['Content-Length']){
    			return false;
    		}else{
    			$this->_body = $this->_buffer;
    			$this->_isFinish = true;
    			return true;
    		}
    	}
    }
    //判断是否是压缩过的数据
    private static function getZIPData($data,$type = 'gzip'){
    	if ($type == 'gzip'){
    		return gzdecode($data);
    	}else if ($type == 'deflate'){
    		return gzinflate($data);
    	}else if($type == 'compress'){
    		return gzinflate(substr($data,2,-4));
    	}
    	else{
    		return $data;
    	}
    }
    
    public function setUserAgent($_userAgent) {
    	$this->_userAgent = $_userAgent;
    }
    
    public function setHeader($key,$value){
    	$this->_requestHeader[$key] = $value;
    }
    
    public function onConnect(\swoole_client $cli) {
    	echo "Connected\n";
    	$header = $this->_method.' '.$this->_uri['path'].' HTTP/1.1'. self::EOF;
    	$header .= 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8' . self::EOF;
    	$header .= 'Accept-Encoding: gzip,deflate' . self::EOF;
    	$header .= 'Accept-Language: zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4,ja;q=0.2' . self::EOF;
    	$header .= 'Host: '.$this->_uri['host']. self::EOF;
    	$header .= $this->_userAgent . self::EOF;
    
    	if (!empty($this->_requestHeader))
    	{
    		foreach ($this->_requestHeader as $k => $v)
    		{
    			$header .= $k . ': ' . $v . self::EOF;
    		}
    	}
    
    	$this->errorLog(__LINE__, $header);
    	$body = '';
    	if ($this->_postData)
    	{
    		$header .= 'Content-Type: application/x-www-form-urlencoded' . self::EOF;
    		$header .= 'Content-Length: ' . strlen($this->_postData) . self::EOF;
    		$body = $this->_postData;
    	}
    	$cli->send($header . self::EOF . $body);
    }
    
    public function  onReady($func) {
    	if (is_callable($func)) {
    		$this->onReadyCallback = $func;
    	}else {
    		throw new \Exception(__CLASS__.": function is not callable.");
    	}
    }
    
    public function onReceive($cli,$data){
    	$this->_buffer .= $data;
    	if ($this->_trunkLength > 0 and strlen($this->_buffer) < $this->_trunkLength){
    		return ;
    	}
    	if (empty($this->_respHeader)){
    		$ret = $this->parseHeader($this->_buffer);
    		if ($ret === false){
    			return ;
    		}else {
    			if (strlen($this->_buffer) > 0){
    				goto parseBody;
    			}
    		}
    	}else{
    		parseBody:
    		if ($this->parseBody() === true and $this->_isFinish){
    			$compressType = empty($this->_respHeader['Content-Encoding']) ? '': $this->respHeader['Content-Encoding'];
    			$this->_body = self::getZIPData($this->_body,$compressType);
    			
    			call_user_func($this->_onReadyCallback,$this,$this->_body,$this->_respHeader);
    		}
    	}
    }
    
    /**
     * 设置请求超时时间
     * @param unknown $timeout
     */
    public function setTimeout($timeout) {
    	$this->_timeout = $timeout;
    }
    
    public function onError($cli) {
    	echo "Connect to server failed.\n";
    }
    
    public function onClose($cli) {
    	echo "Server close\n";
    }
    /**
     * 执行请求，默认使用同步模式
     * @throws \Exception
     */
    private function runRequest(){
    	if (empty($this->_onReadyCallback)){
    		throw new \Exception(__CLASS__." require onReadyCallback");
    	}
    	
    	$cli = new \swoole_client(SWOOLE_TCP,$this->_isAsync == true? SWOOLE_SOCK_ASYNC: SWOOLE_SOCK_SYNC);
    	
    	$this->_client = $cli;
    	$cli->on('connect', array($this, 'onConnect'));
    	$cli->on('error', array($this, 'onError'));
    	$cli->on('Receive', array($this, 'onReceive'));
    	$cli->on('close', array($this, 'onClose'));
    	$cli->connect($this->_uri['host'], $this->_uri['port'], $this->_timeout);
    }
    /**
     * get请求
     */
    function get(){
    	$this->runRequest();
    }
    /**
     * post请求
     * @param array $data
     */
    function post(array $data){
    	$this->_postData = http_build_query($data);
    	$this->_method = 'POST';
    	$this->runRequest();
    }
}
