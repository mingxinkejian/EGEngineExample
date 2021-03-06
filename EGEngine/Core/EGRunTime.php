<?php

namespace Core;
/*
 * 用来计算程序运行消耗时间
 */
class EGRunTime {
	private $_startTime = 0;//程序运行开始时间
	private $_stopTime  = 0;//程序运行结束时间
	private $_timeSpent = 0;//程序运行花费时间
	function start(){//程序运行开始
		$this->_startTime = microtime();
	}
	function stop(){//程序运行结束
		$this->_stopTime = microtime();
	}
	function getRunTime(){//程序运行花费的时间
		list($StartMicro, $StartSecond) = explode(" ", $this->_startTime);
		list($StopMicro, $StopSecond) = explode(" ", $this->_stopTime);
		$start = doubleval($StartMicro) + $StartSecond;
		$stop = doubleval($StopMicro) + $StopSecond;
		$this->_timeSpent = $stop - $start;
		return substr($this->_timeSpent,0,8)." s";//返回获取到的程序运行时间差
	}
}
