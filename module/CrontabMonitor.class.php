<?php
class CrontabMonitor extends Monitor{
	private $log_type = 'crontab';
	public function check($file){
		// cat crontab | grep -v '^#' | grep -v '^$'
		
		//check crontab
		exec(" cat {$file} | grep -v '^#' | grep -v '^$' | sort | uniq ",$check_crontab);
		$check_crontab = Util::array_trim($check_crontab);
		//system crontab
		exec(" crontab -l | grep -v '^#' | grep -v '^$' | sort | uniq ",$system_crontab);
		$system_crontab = Util::array_trim($system_crontab);
		// in check crontab not in system crontab
		$only_in_check = array_diff($check_crontab,$system_crontab);
		if(!empty($only_in_check)){
			Log::_log('warn',$this->log_type,"系统中未发现以下crontab<br/>".implode("<br/>",$only_in_check));
		}
		//add to log,warn
		
		// in system crontab not in check crontab
		$only_in_system =  array_diff($system_crontab,$check_crontab);
		if(!empty($only_in_system)){
			Log::_log('notice',$this->log_type,"系统中发现新的crontab<br/>".implode("<br/>",$only_in_system));
		}
		//add to log,notice
		
		
		//if php ,check system crontab scrpit syntax
		if(!empty($system_crontab) && is_array($system_crontab)){
			foreach($system_crontab as $cron){
				preg_match('([\w|/]*\.php)',$cron,$php_file_path);
				if(empty($php_file_path)){
					continue;
				}
				$php_file_path = reset($php_file_path);

				if(!file_exists($php_file_path)){
					//add  to log ,error
					Log::_log('error',$this->log_type,"该执行文件不存在:{$php_file_path}<br/>");
					continue;
				}
				exec('php -l '.$php_file_path,$syntax_check_result);
				
				$syntax_check_result = reset($syntax_check_result);
				
				if(!stristr($syntax_check_result,'No syntax errors')){
					//add to log ,syntax error
					Log::_log('error',$this->log_type,"该执行文存在语法错误:{$syntax_check_result}<br/>");
					continue;
				}
			}
		}
		return true;
	}
}