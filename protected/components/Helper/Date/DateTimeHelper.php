<?php
class DateTimeHelper{
	public static function convertToCommonDateTimeFormat($datetime){
		if($datetime && strcmp($datetime,'')!=0){
			try {
				$date = date_create($datetime);
				return date_format($date, Yii::app()->params['DATE_FORMAT']);
			} catch (Exception $e) {
				return null;
			}
		}
	
		return null;
	}
	
	public static function toString($date){
		if($date){
			try {
				return date_format($date, Yii::app()->params['DATE_FORMAT']);
			} catch (Exception $e) {
				return null;
			}
		}
	
		return null;
	}
	
	public static function toEpoch($date){
		if($date){
			try {
				return strtotime($date);
			} catch (Exception $e) {
				return -1;
			}
		}
		
		return -1;
	}
	
	public static function nowtoEpoch(){
		return DateTimeHelper::toEpoch(DateTimeHelper::date());
	}
	
	public static function convertToCommonDateTimeFormatWithEndOfDay($datetime){
		if($datetime){
			try {
				$date = date_create($datetime);
				date_time_set($date, 23, 59, 59);
				return date_format($date, Yii::app()->params['DATE_FORMAT']);
			} catch (Exception $e) {
				return null;
			}
		}
	
		return null;
	}
	
	public static function convertToCommonDateTimeFormatWithStartOfDay($datetime){
		if($datetime){
			try {
				$date = date_create($datetime);
				date_time_set($date, 00, 00, 00);
				return date_format($date, Yii::app()->params['DATE_FORMAT']);
			} catch (Exception $e) {
				return null;
			}
		}
	
		return null;
	}
	
	public static function getEndOfYear($datetime){
		if($datetime){
			try {
				$date = date_create($datetime);
				date_time_set($date, 23, 59, 59);
				date_date_set($date, date_format($date, "Y"), 12, 31);
				return date_format($date, Yii::app()->params['DATE_FORMAT']);
			} catch (Exception $e) {
				return null;
			}
		}
	
		return null;
	}
	
	public static function getStartOfYear($datetime){
		if($datetime){
			try {
				$date = date_create($datetime);
				date_time_set($date, 00, 00, 00);
				date_date_set($date, date_format($date, "Y"), 01, 01);
				return date_format($date, Yii::app()->params['DATE_FORMAT']);
			} catch (Exception $e) {
				return null;
			}
		}
	
		return null;
	}
	
	public static function date(){
		return date(Yii::app()->params['DATE_FORMAT']);
	}

	public static function toDate($datetimeString){
		if($datetimeString){
			try {
				$date = date_create($datetimeString);
				return $date;
				
			} catch (Exception $e) {
				return null;
			}
		}
	
		return null;
	}
	
	public static function dateOnlyFromDateTimeString($datetimeString){
		if($datetimeString){
			try {
				$date = date_create($datetimeString);
				return date_format($date, Yii::app()->params['DATE_FORMAT_DATE_ONLY']);
			} catch (Exception $e) {
				return null;
			}
		}
	
		return "";
	}

	public static function dateTimeFromEpoch($epoch, $format = null){
		if($epoch){
			try {
				$date = new DateTime("@$epoch");
				if($format){
					return date_format($date, $format);
				}
				else{
					return date_format($date, Yii::app()->params['DATE_FORMAT']);
				}
				
			} catch (Exception $e) {
				return null;
			}
		}
	
		return "";
	}

	public static function dateTimeStringFromEpoch($epoch){
		if($epoch){
			try {
				$date = new DateTime("@$epoch");
				return date_format($date, Yii::app()->params['DATE_FORMAT']);
			} catch (Exception $e) {
				return null;
			}
		}
	
		return "";
	}
	
	public static function dateOnlyFromEpoch($epoch){
		if($epoch){
			try {
				$date = new DateTime("@$epoch");
				return date_format($date, Yii::app()->params['DATE_FORMAT_DATE_ONLY']);
			} catch (Exception $e) {
				return null;
			}
		}
	
		return "";
	}
	
	public static function timelineDateFromDateTimeString($datetimeString){
		if($datetimeString){
			try {
				$date = date_create($datetimeString);
				return date_format($date, Yii::app()->params['DATE_FORMAT_TIMELINE']);
			} catch (Exception $e) {
				return null;
			}
		}
	
		return "";
	}
	
	public static function dateFromDateTimeString($datetimeString){
		if($datetimeString){
			try {
				$date = date_create($datetimeString);
				return date_format($date, Yii::app()->params['DATE_FORMAT']);
			} catch (Exception $e) {
				return null;
			}
		}
	
		return "";
	}
	
	public static function getMonth($datetimeString){
		if($datetimeString){
			try {
				$date = date_create($datetimeString);
				return $month = date_format($date, "m");
			} catch (Exception $e) {
				return "";
			}
		}
		
		return "";
	}
}