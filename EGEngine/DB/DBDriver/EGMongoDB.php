<?php

namespace DB\DBDriver;

use DB\EGADB;

class EGMongoDB extends EGADB {

	protected $_mongo           =   null; // MongoDb Object
	protected $_collection      =   null; // MongoCollection Object
	protected $_dbName          =   ''; // dbName
	protected $_collectionName  =   ''; // collectionName
	protected $_cursor          =   null; // MongoCursor Object
	
	public function __construct($config) {
	    if (!extension_loaded('Mongo')){
	        echo "can not found Mongo extension \n";
	        return ;
	    }
	    if(empty($config)){
	        echo "config is null,please confirm config \n";
	        return ;
	    }
		$this->_config = $this->parseConfig ( $config );
	}

	public function connection($config,$connId=0){
		// TODO Auto-generated method stub
		$host = 'mongodb://'.($config['username']?"{$config['username']}":'').($config['password']?":{$config['password']}@":'').$config['masterName'].($config['hostPort']?":{$config['hostPort']}":'').'/'.($config['database']?"{$config['database']}":'');
		try{
			$this->_handlerList[$connId] = new \mongoClient( $host,$config['options']);
		}catch (\MongoConnectionException $e){
			echo "connect mongoClient failed :".$e->getMessage()."\n";
		}
		return $this->_handlerList[$connId];
	}
	
	/* 
	 * 分布式连接数据库，可以使用主从分离，主数据库写，从数据库读
	 */
	public function multiConnection($isMaster = true) {
		// TODO Auto-generated method stub
		//是否读写分离
		$masterIndex=0;
		if ($this->_config['rwSeparate']){
			if ($isMaster){
				$slaveIndex=$masterIndex;
			}else{
				// 读操作连接从服务器
				$slaveIndex = floor(mt_rand(1,count($this->_config['hostName'])-1));   // 每次随机连接的数据库
			}
		}else{
			// 读写操作不区分服务器，每次随机连接的数据库
			$slaveIndex = floor(mt_rand(0,count($this->_config['hostName'])-1));
		}
		$config['username']=$this->_config['username'];
		$config['password']=$this->_config['password'];
		$config['masterName']=$this->_config['hostName'][$slaveIndex];
		$config['hostPort']=$this->_config['hostPort'];
		$config['database']=$this->_config['database'];
		$config['options']=$this->_config['options'];
		return $this->connection($config);
	}

	/**
	 * 切换集合
	 * @param string $collection
	 * @param string $dbName
	 */
	public function switchCollection($collection,$dbName='',$isMaster=true){
	    if (!$this->_handler){
	    	$this->initConnection($isMaster);
	    }
	    try {
	    	//如果dbName不为空的话切换数据库
	    	if (!empty($dbName)){
	    		// 当前MongoDb对象
	    		$this->_dbName  =  $dbName;
	    		$this->_mongo = $this->selectDb($dbName);
	    	}
	    	if($this->_collectionName != $collection) {
	    		$this->_collection =  $this->_mongo->selectCollection($collection);
	    		$this->_collectionName  = $collection; // 记录当前Collection名称
	    	}
	    } catch (\MongoException $e) {
	    	echo "connect error :".$e->getMessage()."\n";
	    }
	    
	}

	public function selectDB($dbName) {
		// TODO Auto-generated method stub
		return $this->_handler->selectDb($dbName);
	}

	/*
	 * Runs JavaScript code on the database server.
	 */
	public function execute($code, $options=array()) {
		// TODO Auto-generated method stub
		$this->_sql = 'execute:'.$code;
		$result   = $this->_mongo->execute($code,$options);
		$this->debug(false);
		if($result['ok']) {
			return $result['retval'];
		}else{
			echo "execute run error! \n";
			return false;
		}
	}
	/**
	 * 执行命令
	 * @access public
	 * @param array $command  指令
	 * @return array
	 */
	public function commond($command=array()){
		$this->_sql = 'command:'.json_encode($command);
		$result   = $this->_mongo->command($command);
		if(!$result['ok']) {
			echo "commond run error! \n";
			return false;
		}
		return $result;
	}


	public function insert($table,$data, $options = array()) {
		// TODO Auto-generated method stub
		$this->switchCollection($table);
		
		$this->_sql  =  $this->_dbName.'.'.$this->_collectionName.'.insert(';
		$this->_sql .= $data?json_encode($data):'{}';
		$this->_sql .= ')';
		try {
			$result =  $this->_collection->insert($data);
			if($result) {
				$_id    = $result['_id'];
				if(is_object($_id)) {
					$_id = $_id->__toString();
				}
				$this->_lastId  = $_id;
			}
			return $result;
		} catch (\MongoCursorException $e) {
			echo $e->getMessage();
		}
	}


	public function insertArray($table,$dataList, $options = array()) {
		// TODO Auto-generated method stub
		$this->switchCollection($table);
		try {
			$result =  $this->_collection->batchInsert($dataList);
			return $result;
		} catch (\MongoCursorException $e) {
			echo $e->getMessage();
		}

	}


	public function selectInsert($table,$fields, $table, $options = array()) {
		// TODO Auto-generated method stub
		echo "mongoDB have not selectInsert \n";
	}


	public function update($table,$data, $options = array(),$multiple=true) {
		// TODO Auto-generated method stub
		$this->switchCollection($table);
		try {
			$multipleOpt=array("multiple" => $multiple);
			$this->_sql   =  $this->_dbName.'.'.$this->_collectionName.'.update(';
			$this->_sql   .= $options?json_encode($options):'{}';
			$this->_sql   .=  ','.json_encode($data).',';
			$this->_sql   .=  json_encode($multiple).')';
			
			$result   = $this->_collection->update($options,$data,$multipleOpt);
			return $result;
		} catch (\MongoCursorException $e) {
			echo $e->getMessage()."\n";
		}
	}


	public function delete($table,$options = array()) {
		// TODO Auto-generated method stub
		$this->switchCollection($table);
		try{
			$this->_sql   =  $this->_dbName.'.'.$this->_collectionName.'.remove('.json_encode($options).')';
			$result   = $this->_collection->remove($options);
			return $result;
		} catch (\MongoCursorException $e) {
			echo $e->getMessage()."\n";
		}
	}


	public function clearTable($table,$options = array()) {
		// TODO Auto-generated method stub
		$this->switchCollection($table);
		try{
			$result   =  $this->_collection->drop();
			return $result;
		} catch (\MongoCursorException $e) {
			echo $e->getMessage()."\n";
		}
	}

	public function find($table,$options = array(),$field=array()){
		$this->switchCollection($table);
		try {
			$this->_sql   =  $this->_dbName.'.'.$this->_collectionName.'.findOne(';
			$this->_sql  .=  $options? json_encode($options):'{}';
			$this->_sql  .=  $field? ','.json_encode($field):'';
			$this->_sql  .=  ')';
			$result=$this->_collection->findOne($options,$field);
			return $result;
		} catch (\MongoCursorException $e) {
			echo $e->getMessage()."\n";
		}
	}

	/**
	 * 返回$cursor
	 * @param unknown $table
	 * @param unknown $options
	 * @param unknown $field
	 * @return Ambigous <NULL, unknown>
	 */
	public function select($table,$options = array(),$field=array()) {
		// TODO Auto-generated method stub
		$this->switchCollection($table);
		try {
			$this->_sql   =  $this->_dbName.'.'.$this->_collectionName.'.find(';
			$this->_sql  .=  $options? json_encode($options):'{}';
			$this->_sql  .=  $field? ','.json_encode($field):'';
			$this->_sql  .=  ')';
			$cursor=$this->_collection->find($options,$field);
			$this->_cursor =  $cursor;
			return $this->_cursor;
		} catch (\MongoCursorException $e) {
			echo $e->getMessage()."\n";
		}
	}
	
	/**
	 * 排序
	 * @param unknown $options
	 * @return Ambiguous
	 */
	public function order($options){
		if ($this->_cursor){
			return $this->_cursor->sort($options);
		}
		return null;
	}
	
	/**
	 * 分页
	 * @param unknown $offset
	 * @param unknown $lenth
	 * @return Ambiguous|Ambiguous
	 */
	public function limit($offset,$lenth){
		if ($this->_cursor){
			$this->_cursor->skip($offset);
			$cursor=$this->_cursor->limit($lenth);
			return $cursor;
		}
		return null;
	}
	
	/**
	 * findAndModify 原子级操作
	 * @param array $query
	 * @param array $update
	 * @param array $fields
	 * @param array $options
	 */
	public function findAndModify($table, array $query , array $update , array $fields , array $options ){
		$this->switchCollection($table);
		
		$this->_sql = $this->_dbName.'.'.$this->_collectionName.'.findAndModify('.json_encode($query).',';
		$this->_sql.= $update? json_encode($update):'{}';
		$this->_sql.= ','.$fields? json_encode($fields):'{}';
		$this->_sql.= ','.$options? json_encode($options):'{}';
		$this->_sql.= ')';
		
		return $this->_collection->findAndModify($query,$update,$fields,$options);
	}


	public function count($table,$options = array()) {
		// TODO Auto-generated method stub
		$this->switchCollection($table);
	}


	public function distinct($table,$files,$args=array()) {
		// TODO Auto-generated method stub
		$this->switchCollection($table);
		
		return $this->_collection->distinct($files,$args);
	}
	
	public function group($table,$keys,$initial,$reduce,$options=[]){
		$this->switchCollection($table);
		
		return $this->_collection->group($keys,$initial,$reduce,$options);
	}


	public function freeResult() {
		// TODO Auto-generated method stub
		$this->_cursor=null;
	}


	public function close() {
		// TODO Auto-generated method stub
		if($this->_handler) {
			$this->_handler->close();
			$this->_handler = null;
			$this->_mongo = null;
			$this->_collection =  null;
			$this->_cursor = null;
		}
	}


	public function getDBError() {
		// TODO Auto-generated method stub
		$this->_dbErr = $this->_mongo->lastError();
		return $this->_dbErr;
	}

	public function getTables() {
		// TODO Auto-generated method stub
		$this->_sql   =  $this->_dbName.'.getCollenctionNames()';
		$list   = $this->_mongo->listCollections();
		$info =  [];
		foreach ($list as $collection){
			$info[]   =  $collection->getName();
		}
		return $info;
	}

}
