<?php
class Validator{
	public static function validatePostParam($postParams, $isNumber = false){
		if(isset($_POST[$postParams])){
			return $_POST[$postParams];
		}

		if(!$isNumber){
			return '';
		}
		else{
			return 0;
		}
	}
	
	public static function getError($errors){
		$errorString = '';
		foreach($errors as $error)
		{
			$errorString .= $error[0].'</br>';
		}
		return $errorString;
	}
	
	public static function getFirstError($errors){
		$errorString = '';
		foreach($errors as $error)
		{
			$errorString = $error[0];
		}
		return $errorString;
	}
	
	public static function validateUsername($value){
		if ( preg_match('/\s/',$value)) {
			return 'Username cannot contain space';
		}
		
		if(!$value){
			return 'Username cannot empty';
		}
		
		if(strlen($value) < 5){
			return 'Username too short';
		}
		return null;
	}
	
	public static function validatePhoneNumber($value){
		if(!$value || strlen($value)==0){
			return null;
		}
		
		if (!preg_match('/^([0-9+_()]*)$/', $value)) {
			return 'Wrong phone number format!';
		}
		
		if(strlen($value) < 5 || strlen($value) > 15){
			return 'The length of phone number is wrong!';
		}
		return null;
	}
	
	public static function validatePassword($value){
		if(!$value || strlen($value)==0){
			return 'Password cannot empty';
		}
	
		if (strlen($value) < 6) {
			return 'Password need to have at least 6 character!';
		}

		return null;
	}
	
	public static function validateEmail($email){
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return true;
		}
		
		return false;
	}
	
	public static function isEmpty($variable){
		return (!isset($variable) || is_null($variable) || strcmp(trim($variable), '') == 0 || strcmp(trim($variable), 'null') == 0 );
	}
}