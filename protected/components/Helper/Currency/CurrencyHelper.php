<?php
class CurrencyHelper{
	public static function printCurrency($value){
		return  $value . ' ' . Yii::app()->params['CURRENCY'];
	}
}