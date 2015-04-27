<?php
	/**
	 * HTTP FUNCTIONS
	 * @author Tran Quoc Viet
	 */
	class HttpResponse {
		
		function __construct() {
			
		}

		public static function http_response_code($newcode = NULL)
	    {
	    	if (!function_exists('http_response_code')){
	    		static $code = 200;
		        if($newcode !== NULL)
		        {
		            header('X-PHP-Response-Code: '.$newcode, true, $newcode);
		            if(!headers_sent())
		                $code = $newcode;
		        }       
		        return $code;
	    	}
	    	else{
	    		http_response_code($newcode);
	    	}
	    }
		
		public static function responseOk()
		{
			HttpResponse::http_response_code(200);
		}
		
		public static function responseCreated()
		{
			HttpResponse::http_response_code(201);
		}
		
		public static function responseAccepted()
		{
			HttpResponse::http_response_code(202);
		}
		
		public static function responseBadRequest()
		{
			HttpResponse::http_response_code(400);
		}
		
		public static function responseAuthenticationFailure()
		{
			HttpResponse::http_response_code(401);
		}
		
		public static function responseForbidden()
		{
			HttpResponse::http_response_code(403);
		}
		
		public static function responseNotFound()
		{
			HttpResponse::http_response_code(404);
		}
		
		public static function responseMethodNotAllow()
		{
			HttpResponse::http_response_code(405);
		}
		
		public static function responseConflict()
		{
			HttpResponse::http_response_code(409);
		}
		
		public static function responsePreconditionFailed()
		{
			HttpResponse::http_response_code(412);
		}
		
		public static function responseRequestEntityTooLarge()
		{
			HttpResponse::http_response_code(413);
		}
		
		public static function responseInternalServerError()
		{
			HttpResponse::http_response_code(500);
		}
		
		public static function responseNotImplemented()
		{
			HttpResponse::http_response_code(501);
		}
		
		public static function responseServiceUnavailable()
		{
			HttpResponse::http_response_code(503);
		}

		public static function responseAuthenticationDataIncorrect()
		{
			HttpResponse::http_response_code(535);
		}
	}
	
?>