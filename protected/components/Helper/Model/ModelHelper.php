<?php
class ModelHelper {
	/* Extra data can only be sent as Json string. */
	public static function convertFetchResult($data ,$convertToArray = false, $method = 'toDisplayArray', $extraData = null){
		if($convertToArray){
			return array_map(create_function('$m',' if(method_exists($m,\''.$method.'\')){return $m->'.$method.'(\''.$extraData.'\');}else{return $m->getAttributes(\''.$extraData.'\');}'),$data);
		}
		else{
			return $data;
		}
	}
	
	public static function convertModelToJson($model){
		return json_encode($model->getAttributes());
	}
}