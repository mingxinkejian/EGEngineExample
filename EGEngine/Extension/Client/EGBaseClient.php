<?php

namespace Extension\Client;

class EGBaseClient {
	const EOF = "\r\n"; //默认分隔符
	const DEFAULT_PORT = 80; //默认端口
	protected $_client;
	protected $_buffer = '';
}
