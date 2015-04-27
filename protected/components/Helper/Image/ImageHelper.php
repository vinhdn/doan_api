<?php
class ImageHelper{
	public static function getSystemClientLogoUrl($filename){
		//return Yii::app()->getBaseUrl(true).'/images/system/Client/Logo/'.$filename;
		return 'http://bos.localsite'.'/images/system/Client/Logo/'.$filename;
	}
	
	public static function cropToSquareWithSize($folder, $fileName, $size, $isCreateNewFile = false){
		$manipulator = new ImageManipulator($folder.$fileName);
		// resizing to size
		$newImage = $manipulator->resample($size, $size);
		
		$filetype = IMAGETYPE_PNG;
		$filename = pathinfo($fileName); // Returns an array of file details
		$extension = $filename['extension'];
		
		if(strcmp($extension, '.png')){
			$filetype = IMAGETYPE_PNG;
		} else if(strcmp($extension, '.gif')){
			$filetype = IMAGETYPE_GIF;
		}
		else{
			$filetype = IMAGETYPE_JPEG;
		}
		
		// saving file to uploads folder
		if(!$isCreateNewFile){
			$manipulator->save($folder.$fileName, $filetype);
		}
		else{
			$manipulator->save($folder.$size.'x'.$size.'_'.$fileName, $filetype);
		}
	}

	public static function createThumb($src, $dest, $desired_width = 100, $desired_height = null){
	    $fparts = pathinfo($src);
	    $ext = strtolower($fparts['extension']);
	    /* if its not an image return false */
	    if (!in_array($ext,array('gif','jpg','png','jpeg'))) return false;

	    /* read the source image */
	    if ($ext == 'gif')
	        $resource = imagecreatefromgif($src);
	    else if ($ext == 'png')
	        $resource = imagecreatefrompng($src);
	    else if ($ext == 'jpg' || $ext == 'jpeg')
	        $resource = imagecreatefromjpeg($src);
	    
	    $width  = imagesx($resource);
	    $height = imagesy($resource);
	    /* find the "desired height" or "desired width" of this thumbnail, relative to each other, if one of them is not given  */
	    if(!$desired_height) $desired_height = floor($height*($desired_width/$width));
	    if(!$desired_width)  $desired_width  = floor($width*($desired_height/$height));
	  
	    /* create a new, "virtual" image */
	    $virtual_image = imagecreatetruecolor($desired_width,$desired_height);
	  
	    /* copy source image at a resized size */
	    imagecopyresized($virtual_image,$resource,0,0,0,0,$desired_width,$desired_height,$width,$height);
	    
	    /* create the physical thumbnail image to its destination */
	    /* Use correct function based on the desired image type from $dest thumbnail source */
	    $fparts = pathinfo($dest);
	    $ext = strtolower($fparts['extension']);
	    /* if dest is not an image type, default to jpg */
	    if (!in_array($ext,array('gif','jpg','png','jpeg'))) $ext = 'jpg';
	    $dest = $fparts['dirname'].'/'.$fparts['filename'].'.'.$ext;
	    
	    if ($ext == 'gif')
	        imagegif($virtual_image,$dest);
	    else if ($ext == 'png')
	        imagepng($virtual_image,$dest,1);
	    else if ($ext == 'jpg' || $ext == 'jpeg')
	        imagejpeg($virtual_image,$dest,100);
	    
	    return array(
	        'width'     => $width,
	        'height'    => $height,
	        'new_width' => $desired_width,
	        'new_height'=> $desired_height,
	        'dest'      => $dest
	    );
	}
}