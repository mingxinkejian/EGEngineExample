<?php

namespace DB;

interface EGIDB {
	
	/**
	 * 初始化数据库连接
	 * @param string $isMaster
	 */
	public function initConnection($isMaster=true);
	
	/**
	 * 连接数据库
	 */
	public function connection($config,$connId=0);
	
	/**
	 * 默认采用主库写，从库读，可以在配置文件中设置
	 * 分布式数据库连接
	 */
	public function multiConnection($isMaster=true);
	
	/**
	 * 选择默认数据库
	 * @param string $dbName
	 */
	public function selectDB($dbName);
	
	/**
	 * 释放查询结果
	 */
	public function freeResult();
	/**
	 * 关闭数据库连接
	*/
	public function close();
	
	/**
	 * 获取数据库错误
	*/
	public function getDBError();
	
	/**
	 * 获取上一次执行sql的语句
	*/
	public function getLastSql();
	
	/**
	 * 获取上一次插入数据的主键Id
	*/
	public function getLastId();
	/**
	 * 取得当前数据库的表信息
	 */
	public function getTables();
}
