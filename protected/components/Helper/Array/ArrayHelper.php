<?php
class ArrayHelper{
	public static function sortArrayByField($array, $field, $isAsc = true){
		usort($array, function($a, $b)
		{
			return strcmp($a->__get($field), $b->__get($field));
		});
		
		return $array;
	}
}