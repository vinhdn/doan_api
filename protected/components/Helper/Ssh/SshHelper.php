<?php
class SshHelper{
	public static function connectToSSH($url, $username, $password){
		$ssh = Yii::app()->phpseclib->createSSH2($url);
		if (!$ssh->login($username, $password)) {
		  //exit('Login Failed');
		  return null;
		}
		else{
			return $ssh;
		}
	}

	public static function pwd($ssh){
		return $ssh->exec('pwd');
	}

	public static function ls($ssh){
		return $ssh->exec('ls');
	}

	public static function cd($ssh, $path){
		return $ssh->exec('cd '.$path);
	}

	public static function chdir($ssh, $path){
		return $ssh->exec('chdir '.$path);
	}

	/**
	 * params: 
	 * 	ssh: ssh object
	 * 	srcFilePath: file path in the current system
	 *  desFilePath: file path in the destination system
	 * 	desSystem: format: username@hostname . If null, use the current system
	 */
	public static function copyFile($ssh, $srcFilePath, $desFilePath, $desSystem){
		if($desSystem){
			return $ssh->exec("scp -r $srcFilePath $desSystem:$desFilePath");
		}
		else{
			return $ssh->exec("scp -r $srcFilePath $desFilePath");
		}
	}

	public static function copyFolderContent($ssh, $srcFolderPath, $desFolderPath){
		return $ssh->exec("scp -r $srcFilePath/* $desFilePath");
	}

	public static function runSh($ssh, $path, $params){
		$paramCommand = ' ';
		if(is_array($params) && count($params) > 0){
			foreach($params as $param){
				$paramCommand .= $param.' ';
			}
		}
		return $ssh->exec("sh ".$path.$paramCommand);
	}
}
?>