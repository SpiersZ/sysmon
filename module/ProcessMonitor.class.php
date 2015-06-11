<?php
class ProcessMonitor{
	private $log_type = 'process';
	public function check($file){
		//list config process
		exec(" cat {$file} | grep -v '^#' | grep -v '^$' | sort | uniq ",$check_process);
		$check_process = Util::array_trim($check_process);
		
		//system process
		exec(" ps aux | awk '{print $11,\$NF}' | grep -v '^$' | sort | uniq ",$system_process);
		$system_process = Util::array_trim($system_process);
		
		$only_in_check = array_diff($check_process,$system_process);
		if(!empty($only_in_check)){
			Log::_log('error',$this->log_type,"系统中未发现以下进程<br/>".implode("<br/>",$only_in_check));
		}
	}
}
