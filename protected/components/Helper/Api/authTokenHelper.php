<?php
	/**
	 * AUTHENTICATE TOKEN HELPER FUNCTIONS
	 * @author Do Cong Bang docongbang1993@gmail.com
	 */
	class authTokenHelper {
		
		function __construct() {
			
		}
		public function getKey()
		{
			$passphrase = 'fvfXfer34Qdfjn';
			return $passphrase;
			/* Turn a human readable passphrase
			 * into a reproducable iv/key pair
			 */
			/*
			$iv = substr(md5('iv'.$passphrase, true), 0, 8);
			$key = substr(md5('pass1'.$passphrase, true) . 
			               md5('pass2'.$passphrase, true), 0, 24);
			$opts = array('iv'=>$iv, 'key'=>$key);
			$fp = fopen(dirname(__FILE__).'\file.enc', 'rb');
			stream_filter_append($fp, 'mdecrypt.tripledes', STREAM_FILTER_READ, $opts);
			$data = rtrim(stream_get_contents($fp));
			fclose($fp);

			return $data;*/
		}
		public function clearAuthToken($userId)
		{
			$user 		= 	User::model()->findByPk($userId);
	        $user->token = NULL; 
	       	return 	$user->update();
		}

		public function updateAuthToken($userId)
		{
			$authToken 	=	authTokenHelper::generate($userId);
			$user 		= 	User::model()->findByPk($userId);
	        $user->token = $authToken; 
	       	return 	$user->update();
		}

		public function check(){
			if (!isset($_POST['token'])) {
				http_response_code(403);
				ajaxHelper::error('Cookie is missing from header.');
				return FALSE;
			} else {
				$user	=	User::model()->find('token=:session', array(':session' => $_POST['token']));
				if (!$user) {
					http_response_code(403);
					ajaxHelper::error('Cookie is expried');
					return FALSE;
				} else {
					return $user;
				}
			}				
		}

		public function generate($userId) {
			$d 		= 	date ("d");
			$m 		= 	date ("m");
			$y 		= 	date ("Y");
			$t 		= 	time();
			$dmt 	=	$d+$m+$y+$t;    
			$ran 	= 	rand(0,10000000);
			$dmtran = 	$dmt+$ran;
			$un 	=  	uniqid();
			$dmtun 	= 	$dmt.$un;
			$mdun 	= 	md5($dmtran.$un.$userId);
			$sort	= 	substr($mdun, 16); // if you want sort length code.

			return $mdun;
		}
	}
	
?>