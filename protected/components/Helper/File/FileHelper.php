<?php
class FileHelper{
	public static function createFolder($name, $url){
		if (!is_dir($url.'/'.$name)) {
		    return mkdir($url.'/'.$name, 0777, true);
		}
		return false;
	}
	
	public static function createClientFileFolder($clientName){
		return FileHelper::createFolder($clientName, Yii::app()->getBasePath(true).'/../'.Yii::app()->params['CLIENT_FILES_PATH']);
	}

	public static function createProductImageFolder(){
		return FileHelper::createFolder('productImage', Yii::app()->getBasePath(true).'/../user_resouce/images/');
	}

	public static function createProductThumbImageFolder(){
		return FileHelper::createFolder('productThumbImage', Yii::app()->getBasePath(true).'/../user_resouce/images/');
	}

	public static function createCategoryImageFolder(){
		return FileHelper::createFolder('categoryImage', Yii::app()->getBasePath(true).'/../user_resouce/images/');
	}

	public static function createCategoryThumbImageFolder(){
		return FileHelper::createFolder('categoryThumbImage', Yii::app()->getBasePath(true).'/../user_resouce/images/');
	}
	
	public static function createFileFolderForProject($clientName, $projectName){
		return FileHelper::createFolder($projectName, Yii::app()->getBasePath(true).'/../'.Yii::app()->params['CLIENT_FILES_PATH'].'/'.$clientName);
	}
	
	public static function moveFileToProjectFolder($clientName, $projectName, $fileData, $isRename = false){
		FileHelper::createFileFolderForProject($clientName, $projectName);
		$fileName = $_FILES["file"]["name"];
		return move_uploaded_file($_FILES["file"]["tmp_name"], Yii::app()->getBasePath(true).'/../'.Yii::app()->params['CLIENT_FILES_PATH'].'/'.$clientName."/$projectName"."/$fileName");	
	}
	
	public static function getFileUrl($clientName, $projectName, $fileName){
		$url = Yii::app()->getBaseUrl(true).'/'.Yii::app()->params['CLIENT_FILES_PATH'].'/'.$clientName."/$projectName"."/$fileName";
		return $url;
	}
	
	public static function deleteFileFromProject($clientName, $projectName, $fileName){
		$url = Yii::app()->getBasePath(true).'/../'.Yii::app()->params['CLIENT_FILES_PATH'].'/'.$clientName."/$projectName"."/$fileName";
		if (file_exists($url)) {
			return unlink($url);
		}
		
		return false;
	}
	
	public static function getFileSizeText($size){
		if($size >= 1024 * 1024 * 1024){
			return number_format(($size/(1024 * 1024 * 1024)),0,'.','').' gb';
		}
		else if($size >= 1024 * 1024){
			return number_format(($size/(1024 * 1024)),0,'.','').' mb';
		}
		else if($size >= 1024){
			return number_format(($size/1024),0,'.','').' kb';
		}
		else{
			return ($size).' b';
		}
	}
	
	public static function getIconUrlForFileType($type){
		if(strcmp($type, 'image/png') == 0){
			return Yii::app()->theme->baseUrl.'/assets/filetype/png.png';
		}
		else if(strcmp($type, 'audio/x-aac') == 0){
			return Yii::app()->theme->baseUrl.'/assets/filetype/aac.png';
		}
		else if(strcmp($type, 'audio/aiff') == 0){
			return Yii::app()->theme->baseUrl.'/assets/filetype/aiff.png';
		}
		else if(strcmp($type, 'video/msvideo') == 0){
			return Yii::app()->theme->baseUrl.'/assets/filetype/avi.png';
		}
		else if(strcmp($type, 'application/msword') == 0){
			return Yii::app()->theme->baseUrl.'/assets/filetype/docx.png';
		}
		else if(strcmp($type, 'application/x-msdownload') == 0){
			return Yii::app()->theme->baseUrl.'/assets/filetype/exe.png';
		}
		else if(strcmp($type, 'video/x-flv') == 0){
			return Yii::app()->theme->baseUrl.'/assets/filetype/flv.png';
		}
		else if(strcmp($type, 'image/gif') == 0){
			return Yii::app()->theme->baseUrl.'/assets/filetype/gif.png';
		}
		else if(strcmp($type, 'image/jpeg') == 0){
			return Yii::app()->theme->baseUrl.'/assets/filetype/jpg.png';
		}
		else if(strcmp($type, 'video/quicktime') == 0){
			return Yii::app()->theme->baseUrl.'/assets/filetype/mov.png';
		}
		else if(strcmp($type, 'audio/mpeg') == 0){
			return Yii::app()->theme->baseUrl.'/assets/filetype/mp3.png';
		}
		else if(strcmp($type, 'video/mp4') == 0){
			return Yii::app()->theme->baseUrl.'/assets/filetype/mp4.png';
		}
		else if(strcmp($type, 'video/mpeg') == 0){
			return Yii::app()->theme->baseUrl.'/assets/filetype/mpg.png';
		}
		else if(strcmp($type, 'application/pdf') == 0){
			return Yii::app()->theme->baseUrl.'/assets/filetype/pdf.png';
		}
		else if(strcmp($type, 'application/vnd.ms-powerpoint') == 0){
			return Yii::app()->theme->baseUrl.'/assets/filetype/ppt.png';
		}
		else if(strcmp($type, 'application/vnd.ms-powerpoint') == 0){
			return Yii::app()->theme->baseUrl.'/assets/filetype/pps.png';
		}
		else if(strcmp($type, 'image/vnd.adobe.photoshop') == 0){
			return Yii::app()->theme->baseUrl.'/assets/filetype/psd.png';
		}
		else if(strcmp($type, 'application/x-rar-compressed') == 0){
			return Yii::app()->theme->baseUrl.'/assets/filetype/rar.png';
		}
		else if(strcmp($type, 'text/plain') == 0){
			return Yii::app()->theme->baseUrl.'/assets/filetype/txt.png';
		}
		else if(strcmp($type, 'audio/wav') == 0){
			return Yii::app()->theme->baseUrl.'/assets/filetype/wav.png';
		}
		else if(strcmp($type, 'application/zip') == 0){
			return Yii::app()->theme->baseUrl.'/assets/filetype/zip.png';
		}
		
		return Yii::app()->theme->baseUrl.'/assets/filetype/blank.png';
	}

	public static function getTempName($params){
		return $params['tmp_name'];
	}

	public static function checkFileExtension($params, $extensions){
		//convert all extensions to undercase
		$newExtensions = array();
		foreach($extensions as $extension){
			array_push($newExtensions, strtolower($extension));
		}

		if(!is_null($newExtensions) && count($newExtensions) > 0){
			if ($params["error"] !== UPLOAD_ERR_OK) {
				throw new Exception(Yii::t(Yii::app()->params['ADMIN_TRANSLATE_FILE'],'An error occurred when upload file.'));
				return false;
			}

			$parts = pathinfo($params["name"]);
			if(isset($parts["extension"])){
				return in_array(strtolower($parts["extension"]), $newExtensions);
			}
			else{
				return false;
			}
		}
		else{
			return true;
		}
	}
	
	public static function handleFileUpload($params, $path, $keepFileName = false, $autoNameLength = 20, $override = false){
		if ($params["error"] !== UPLOAD_ERR_OK) {
			throw new Exception(Yii::t(Yii::app()->params['ADMIN_TRANSLATE_FILE'],'An error occurred when upload file.'));
			return;
		}
		
		if($keepFileName){
			// ensure a safe filename
			$name = preg_replace("/[^A-Z0-9._-]/i", "_", $params["name"]);
		}
		else{
			$parts = pathinfo($params["name"]);
			$name = StringHelper::generateRandomString($autoNameLength). "." . $parts["extension"];
		}
		
		if(!$override){
			$i = 0;
			$parts = pathinfo($name);
			while (file_exists($path . $name)) {
				$i++;
				$name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
			}
		}
		else{
			$name = $parts["filename"] . "." . $parts["extension"];
		}
		
		// preserve file from temporary directory
		try{
			if(!is_writable(dirname($path))){
				throw new Exception(Yii::t(Yii::app()->params['ADMIN_TRANSLATE_FILE'],'Permission denied for upload folder.'));
				return;
			}
			$success = move_uploaded_file($params["tmp_name"], $path . $name);
			if (!$success) {
				throw new Exception(Yii::t(Yii::app()->params['ADMIN_TRANSLATE_FILE'],'Unable to save file.'));
				return;
			}
		}
		catch(Exception $e){
			throw new Exception(Yii::t(Yii::app()->params['ADMIN_TRANSLATE_FILE'],$e->getMessage()));
			return;
		}
		
		return $name;
	}
	
	/* get the url to the prodcut image */
	public static function getProductImageUrl($filename, $absolute = true){
		return FileHelper::getProductImageFolderUrl($absolute).$filename;
	}

	/* get the url to the folder where we store prodcut image */
	public static function getProductImageFolderUrl($absolute = true){
		FileHelper::createProductImageFolder();
		if($absolute){
			return Yii::app()->getBaseUrl(true).'/user_resouce/images/productImage/';
		}
		else{
			return Yii::app()->getBasePath(true).'/../user_resouce/images/productImage/';
		}
	}

	/* get the url to the prodcut thumb image */
	public static function getProductThumbImageUrl($filename, $absolute = true){
		return FileHelper::getProductThumbImageFolderUrl($absolute).$filename;
	}

	/* get the url to the folder where we store prodcut image */
	public static function getProductThumbImageFolderUrl($absolute = true){
		FileHelper::createProductThumbImageFolder();
		if($absolute){
			return Yii::app()->getBaseUrl(true).'/user_resouce/images/productThumbImage/';
		}
		else{
			return Yii::app()->getBasePath(true).'/../user_resouce/images/productThumbImage/';
		}
	}

	/* get the url to the category image */
	public static function getCategoryImageUrl($filename, $absolute = true){
		return FileHelper::getCategoryImageFolderUrl($absolute).$filename;
	}

	/* get the url to the folder where we store prodcut image */
	public static function getCategoryImageFolderUrl($absolute = true){
		FileHelper::createCategoryImageFolder();
		if($absolute){
			return Yii::app()->getBaseUrl(true).'/user_resouce/images/categoryImage/';
		}
		else{
			return Yii::app()->getBasePath(true).'/../user_resouce/images/categoryImage/';
		}
	}

	/* get the url to the prodcut thumb image */
	public static function getCategoryThumbImageUrl($filename, $absolute = true){
		return FileHelper::getCategoryThumbImageFolderUrl($absolute).$filename;
	}

	/* get the url to the folder where we store prodcut image */
	public static function getCategoryThumbImageFolderUrl($absolute = true){
		FileHelper::createCategoryThumbImageFolder();
		if($absolute){
			return Yii::app()->getBaseUrl(true).'/user_resouce/images/categoryThumbImage/';
		}
		else{
			return Yii::app()->getBasePath(true).'/../user_resouce/images/categoryThumbImage/';
		}
	}

	/* --------------------------------------------- EXCELS ------------------------------------------------------ */

	/**
	 * Create the folder to hold the excel file  for income
	 */
	public static function createPosUserExportExcelToIncomesFolder($tenant){
		return FileHelper::createFolder($tenant, Yii::app()->getBasePath(true).'/../userdata/export/excel/incomes');
	}

	/**
	 * Get the url of the excel file for income
	 */
	public static function getPosUserExportExcelToIncomesUrl($tenant, $fileName, $absolute = true){
		return FileHelper::getPosUserExportExcelToIncomesFolderUrl($tenant, $absolute).$filename;
	}

	/* get the url to the folder where we store income export file */
	public static function getPosUserExportExcelToIncomesFolderUrl($tenant, $absolute = true){
		FileHelper::createPosUserExportExcelToIncomesFolder($tenant);
		if($absolute){
			return Yii::app()->getBaseUrl(true).'/userdata/export/excel/incomes/'.$tenant.'/';
		}
		else{
			return Yii::app()->getBasePath(true).'/../userdata/export/excel/incomes/'.$tenant.'/';
		}
	}

	/**
	 * Create the folder to hold the excel file  for expense
	 */
	public static function createPosUserExportExcelToExpensesFolder($tenant){
		return FileHelper::createFolder($tenant, Yii::app()->getBasePath(true).'/../userdata/export/excel/expenses');
	}

	/**
	 * Get the url of the excel file for expense
	 */
	public static function getPosUserExportExcelToExpensesUrl($tenant, $fileName, $absolute = true){
		return FileHelper::getPosUserExportExcelToExpensesFolderUrl($tenant, $absolute).$filename;
	}

	/* get the url to the folder where we store expense export file */
	public static function getPosUserExportExcelToExpensesFolderUrl($tenant, $absolute = true){
		FileHelper::createPosUserExportExcelToExpensesFolder($tenant);
		if($absolute){
			return Yii::app()->getBaseUrl(true).'/userdata/export/excel/expenses/'.$tenant.'/';
		}
		else{
			return Yii::app()->getBasePath(true).'/../userdata/export/excel/expenses/'.$tenant.'/';
		}
	}

	/* --------------------------------------------- TEMP ------------------------------------------------------ */
	/**
	 * Create the folder to hold the excel file
	 */
	public static function createPosUserTempFolder($tenant){
		return FileHelper::createFolder($tenant, Yii::app()->getBasePath(true).'/../temp');
	}

	/**
	 * Get the url of the excel file
	 */
	public static function getPosUserTempUrl($tenant, $fileName, $absolute = true){
		return FileHelper::getPosUserTempFolderUrl($tenant, $absolute).$filename;
	}

	/* get the url to the folder where we store prodcut image */
	public static function getPosUserTempFolderUrl($tenant, $absolute = true){
		FileHelper::createPosUserTempFolder($tenant);
		if($absolute){
			return Yii::app()->getBaseUrl(true).'/temp/'.$tenant.'/';
		}
		else{
			return Yii::app()->getBasePath(true).'/../temp/'.$tenant.'/';
		}
	}

	/**
	 * Save upload file to temp folder with random name
	 */
	public static function saveToTemp($tenant, $params){
		$tempPath = FileHelper::getPosUserTempFolderUrl($tenant, false);

		if ($params["error"] !== UPLOAD_ERR_OK) {
			throw new Exception(Yii::t(Yii::app()->params['ADMIN_TRANSLATE_FILE'],'An error occurred when upload file.'));
			return;
		}
		
		$parts = pathinfo($params["name"]);
		$name = StringHelper::generateRandomString(20). "." . $parts["extension"];
		
		// preserve file from temporary directory
		try{
			if(!is_writable(dirname($tempPath))){
				throw new Exception(Yii::t(Yii::app()->params['ADMIN_TRANSLATE_FILE'],'Permission denied for temp folder.'));
				return;
			}
			$success = move_uploaded_file($params["tmp_name"], $tempPath . $name);
			if (!$success) {
				throw new Exception(Yii::t(Yii::app()->params['ADMIN_TRANSLATE_FILE'],'Unable to save temp file.'));
				return;
			}
		}
		catch(Exception $e){
			throw new Exception(Yii::t(Yii::app()->params['ADMIN_TRANSLATE_FILE'],$e->getMessage()));
			return;
		}
		
		return $tempPath . $name;

	}
}