<?php

namespace Application\Service;

use Application\Protocol\BaseMsgProtocol;
abstract class BaseService {

	/**
	 * 协议处理层，逻辑处理
	 * @param unknown $server 服务器
	 * @param unknown $request 请求
	 * @param unknown $context 预留参数
	 */
	public abstract function handleProtocol($server,BaseMsgProtocol $request,$context);
}
