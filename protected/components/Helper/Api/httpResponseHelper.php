<?php
	/**
	 * HTTP RESPONSE STATUS HELPER FUNCTIONS
	 * @author Do Cong Bang docongbang1993@gmail.com
	 */
	class httpResponseHelper {
		
		function __construct() {
			
		}

		public function status500()
		{
			http_response_code(500);
			return ajaxHelper::error('Something went wrong. Please try again later.');
		}
	}
	
?>