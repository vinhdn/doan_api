<?php
class SafeSqlHelper{
	public static function escapeInjection($value){
		return  mysql_real_escape_string($value);
	}
}