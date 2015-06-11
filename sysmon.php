<?php
require_once 'require.php';
$base_dir = __DIR__;
#读取配置
$config = parse_ini_file('config.ini',true);

#检查配置
foreach($config as $key => $c){
	if(!isset($c['file']) || !file_exists($base_dir.'/config/'.$c['file']) || !file_exists($base_dir.'/module/'.$c['module'].'.class.php')){
		unset($config[$key]);
		continue;
	}
	#读取模块
	require_once $base_dir.'/module/'.$c['module'].'.class.php';
	$monObj = new $c['module']();
	#使用不同模块检查
	$monObj -> check($base_dir.'/config/'.$c['file']);
}


#发送邮件
if(!empty(Log::$logs)){
	$to='xubowen@tinydeal.net;';
	$from = 'nebula@tinydeal.net';
	$subject = '192.168.0.34-后台监控-'.date('Y-m-d H:i:s');
	$text_content = '';
	Log::$logs = array_reverse(Log::$logs);
	foreach(Log::$logs as $level =>$logs){
		foreach($logs as $type => $log){
			if($level =='error'){
				$text_content .= "<h2><font color='red'>[{$level}][{$type}]</font></h2><br/>";
			}else{
				$text_content .= "<h2>[{$level}][{$type}]</h2><br/>";
			}
			$text_content .= implode("<br/>",$log);
		}
	}
	
	$mail = new Mail($to, $from, $subject, '',$text_content);
	$mail->send();
}