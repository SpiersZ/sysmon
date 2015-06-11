<?php
class Log{
	
	public static $logs = array();
	
	public static function _log($level,$type,$msg =''){
		self::$logs[$level][$type][] = $msg;
		return true;
	}
}