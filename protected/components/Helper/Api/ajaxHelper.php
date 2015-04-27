<?php
	/**
	 * AJAX HELPER FUNCTIONS
	 * @author Do Cong Bang docongbang1993@gmail.com
	 */
	class ajaxHelper {
		
		function __construct() {
			
		}
		
		public function success($data = array(), $message = null)
		{
			echo json_encode(array(
								'error'		=>	0,
								'data'		=>	$data,
								'message'	=>	$message
							));
		}
		
		public function error($message = null)
		{
			echo json_encode(array(
								'error'		=>	1,
								'message'	=>	$message,
							));
		}
	}
	
?>