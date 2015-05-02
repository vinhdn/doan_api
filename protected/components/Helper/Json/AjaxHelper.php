<?php
	/**
	 * AJAX HELPER FUNCTIONS
	 * @author 
	 */
	class AjaxHelper {
		
		function __construct() {
			
		}
		
		public function jsonSuccess($data = array(), $message = null, $prettyPrint = false)
		{
			if($prettyPrint == false){
				echo json_encode(array(
						'success'		=>	1,
						'message'	=>	$message,
						'data'		=>	$data
				));
			}
			else{
				echo AjaxHelper::prettyPrint(json_encode(array(
						'success'		=>	1,
						'message'	=>	$message,
						'data'		=>	$data
				)));
			}
			
		}
		
		public function jsonError($message = null, $prettyPrint = false)
		{
			if($prettyPrint == false){
				echo json_encode(array(
								'success'	=>	0,
								'message'	=>	$message
							));
			}
			else{
				echo AjaxHelper::prettyPrint(json_encode(array(
						'success'	=>	0,
						'message'	=>	$message
				)));
			}
		}
		
		public static function prettyPrint( $json )
		{
			$result = '';
			$level = 0;
			$prev_char = '';
			$in_quotes = false;
			$ends_line_level = NULL;
			$json_length = strlen( $json );
		
			for( $i = 0; $i < $json_length; $i++ ) {
				$char = $json[$i];
				$new_line_level = NULL;
				$post = "";
				if( $ends_line_level !== NULL ) {
					$new_line_level = $ends_line_level;
					$ends_line_level = NULL;
				}
				if( $char === '"' && $prev_char != '\\' ) {
					$in_quotes = !$in_quotes;
				} else if( ! $in_quotes ) {
					switch( $char ) {
						case '}': case ']':
							$level--;
							$ends_line_level = NULL;
							$new_line_level = $level;
							break;
		
						case '{': case '[':
							$level++;
						case ',':
							$ends_line_level = $level;
							break;
		
						case ':':
							$post = " ";
							break;
		
						case " ": case "\t": case "\n": case "\r":
							$char = "";
							$ends_line_level = $new_line_level;
							$new_line_level = NULL;
							break;
					}
				}
				if( $new_line_level !== NULL ) {
					$result .= "\n".str_repeat( "\t", $new_line_level );
				}
				$result .= $char.$post;
				$prev_char = $char;
			}
		
			return $result;
		}
	}
	
?>