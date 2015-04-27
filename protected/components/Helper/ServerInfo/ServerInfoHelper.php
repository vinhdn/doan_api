<?php
class ServerInfoHelper{
	public static function get_server_cpu_usage(){
		$load = sys_getloadavg();
		return $load[0];
	}

	public static function get_server_memory_usage(){
		try{
			$free = shell_exec('free');
			$free = (string)trim($free);
			$free_arr = explode("\n", $free);

			if(count($free_arr) <= 1){
				throw new Exception('Fail to get the server memory usage. This function only support unix system!');
				return 0;
			}

			$mem = explode(" ", $free_arr[1]);
			$mem = array_filter($mem);
			$mem = array_merge($mem);
			$memory_usage = $mem[2]/$mem[1]*100;
		 
			return 0;
		}
		catch (Exception $e){
			throw new Exception('Fail to get the server memory usage. This function only support unix system!');
		}
		
	}
}
?>