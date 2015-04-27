<?php
Yii::import('application.models.Bos.SystemClient');
class SiteHelper{
	public static function getClientSystemNameFromUrl(){
	
		return $_GET['sys_client'];
	}

	public static function getSystemClient(){
		$sys_client = $_GET['sys_client'];

		$client = SystemClient::model()->find('sys_name=:sys_name', array(':sys_name'=>$sys_client));
		
		if($client){
			return $client;
		}
		else{
			return null;
		}
	}
}