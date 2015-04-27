<?php
class GeoHelper{
	public static function getCountryNameFromKey($key){
		$result = '';
		
		$countries = file_get_contents(Yii::app()->getBaseUrl(true).'/resources/geo/json/simpleCountries.json');
		$json = json_decode($countries, true);

		foreach($json as $country) {
		    if (strcmp(strtoupper($key), strtoupper($country['cca2'])) == 0) {
		    	$result = $country['name'];
		    	break;
		    }
		}

		return $result;
	}
}
?>