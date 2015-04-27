<?php
class StringHelper{
	public static function spaceIfNullString($string){
		if($string){
			return $string;
		}

		return '&nbsp;';
	}
	
	public static function emptyIfNullString($string){
		if($string){
			return $string;
		}
	
		return '';
	}
	
	public static function generateRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }
	    return $randomString;
	}

	public static function generateRandomCustomerKey($length = 10, $prefix = "C") {
	    $characters = '0123456789';
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }
	    return $prefix.$randomString;
	}

	public static function generateRandomOrderKey($length = 10, $prefix = "#") {
	    $characters = '0123456789';
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }
	    return $prefix.$randomString;
	}

	public static function generateRandomInvoiceKey($length = 10, $prefix = "#") {
	    $characters = '0123456789';
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }
	    return $prefix.$randomString;
	}

	public static function generateRandomProductKey($length = 10, $prefix = "P") {
	    $characters = '0123456789';
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }
	    return $prefix.$randomString;
	}

	public static function generateProductKeyFromId($id, $length = 10, $prefix = "P"){
		$idString = str_pad($id, $length, '0', STR_PAD_LEFT);
		return $prefix.$idString;
	}
	
	public static function replaceSeperatorWithOther($string, $oldSeperator, $newSeperator){
		if(!$string){
			return null;
		}
		return str_replace($oldSeperator, $newSeperator, $string);
	}
	
	public static function convertArrayToStringWithSeperator($array, $seperator){
		$string = '';
		$count = 0;
		foreach($array as $item){
			$string = $string.$item;
			if($count <= count($array) - 1){
				$string = $string.$seperator;
			}
			$count++;
		}
		return $string;
	}
	
	public static function stringToNumber($string){
		if(!$string){
			return 0;
		}
		else{
			if(strcmp(strtoupper(trim($string)), 'TRUE') == 0){
				return 1;
			}
			else if(strcmp(strtoupper(trim($string)), 'FALSE') == 0){
				return 0;
			}
			else if(strcmp(strtoupper(trim($string)), '') == 0){
				return 0;
			}
			else{
				return (float)$string;
			}
		}
	}
}