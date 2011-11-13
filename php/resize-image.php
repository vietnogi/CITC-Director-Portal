<?php
//5:07 PM 11/3/2009
function img_resize($path, $w=0, $h=0, $quality=100, $save=''){
	$image_data = @getimagesize($path);
	
	$image_type = $image_data[2];
	$gd_ext = array('', 'jpg', 'gif', 'png');
	if($image_type != 1 && $image_type != 2){
		return false;
	}
	if($save == ''){
		header('Content-type: '.$image_data['mime']);
	}
	else{
		$save = eregi_replace('%ext', $gd_ext[$image_type], $save);
	}
	if($w!=0){
		$rapporto = $image_data[0]/$w;
		if($h!=0){
			if( $image_data[1]/$rapporto > $h ){
				$rapporto = $image_data[1] / $h;
			}
		}
	}
	elseif($h!=0){
		$tmp_h = $image_data[1]/$h;
	}
	else{
		return false;
	}
	
	$thumb_w = $image_data[0]/$rapporto;
	$thumb_h = $image_data[1]/$rapporto;
	
	if($image_type == 1){
		$img_src = @imagecreatefromgif($path);
	}
	elseif($image_type == 2){
		$img_src = @imagecreatefromjpeg($path);
	}
	
	$img_thumb = @imagecreatetruecolor($thumb_w, $thumb_h);
	$result = @imagecopyresampled($img_thumb, $img_src, 0, 0, 0, 0, $thumb_w, $thumb_h, $image_data[0], $image_data[1]);
	if(!$img_src||!$img_thumb||!$result){
		return false;
	}
	if($image_type==1){
		$result = @imagegif($img_thumb, $save);
	}
	elseif($image_type==2){
		$result = @imagejpeg($img_thumb, $save, $quality);
	}
	return $result;
}

class SimpleImage {
   
   var $image;
   var $image_type;
 
   function load($filename) {
      $image_info = getimagesize($filename);
      $this->image_type = $image_info[2];
      if( $this->image_type == IMAGETYPE_JPEG ) {
         $this->image = imagecreatefromjpeg($filename);
      } elseif( $this->image_type == IMAGETYPE_GIF ) {
         $this->image = imagecreatefromgif($filename);
      } elseif( $this->image_type == IMAGETYPE_PNG ) {
         $this->image = imagecreatefrompng($filename);
      }
   }
   function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image,$filename,$compression);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image,$filename);         
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image,$filename);
      }   
      if( $permissions != null) {
         chmod($filename,$permissions);
      }
   }
   function output($image_type=IMAGETYPE_JPEG) {
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image);         
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image);
      }   
   }
   function getWidth() {
      return imagesx($this->image);
   }
   function getHeight() {
      return imagesy($this->image);
   }
   function resizeToHeight($height) {
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }
   function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }
   function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100; 
      $this->resize($width,$height);
   }
   function resize($width,$height) {
      $new_image = imagecreatetruecolor($width, $height);
      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image;   
   }      
}


/*
function img_resize($path, $w=0, $h=0, $quality=100, $save=''){
	$image_data = @getimagesize($path);
	
	$image_type = $image_data[2];
	$gd_ext = array('', 'jpg', 'gif');
	if($image_type != 1 && $image_type != 2){
		return false;
	}
	if($save == ''){
		header('Content-type: '.$image_data['mime']);
		else $save=eregi_replace('%ext',$gd_ext[$image_type],$save);
	
	if($w!=0){
	$rapporto=$image_data[0]/$w;
	if($h!=0){
	 if($image_data[1]/$rapporto>$h) $rapporto=$image_data[1]/$h;
	}
	}elseif($h!=0){
	$tmp_h=$image_data[1]/$h;
	}else{
	return false;
	}
	
	$thumb_w=$image_data[0]/$rapporto;
	$thumb_h=$image_data[1]/$rapporto;
	
	if($image_type==1) $img_src=@imagecreatefromgif($path);
	elseif($image_type==2) $img_src=@imagecreatefromjpeg($path);
	
	$img_thumb=@imagecreatetruecolor($thumb_w,$thumb_h);
	$result=@imagecopyresampled($img_thumb,$img_src,
	0,0,0,0,$thumb_w,$thumb_h,$image_data[0],$image_data[1]);
	if(!$img_src||!$img_thumb||!$result) return false;
	
	if($image_type==1) $result=@imagegif($img_thumb,$save);
	elseif($image_type==2) $result=@imagejpeg($img_thumb,$save,$quality);
	
	return $result;
}
*/

/*
	for($i=1;$i<=4;$i++){
	img_resize('largeFiles/return_miters/'.$i.'.jpg',$w=600,$h=600,$quality=100,$save='shrinkFiles/return_miters/'.$i.'.jpg');
	echo "$i <br/>";
	}*/

function cropResizeImage($nw, $nh, $source, $dest, $cropX=0, $cropY=0, $quality = 100) {
	$imageInfo = getimagesize($source);
	$imageTypes = array('', 'gif', 'jpeg', 'png', 'swf', 'psd', 'bmp');
    if(!isset($imageTypes[$imageInfo['2']])){
		return false;
	}
	//validate image deminsion and check if allowed memory size
	if(!chkImgMem($imageInfo)){
		return false;
	}
	
	$imageInfo[0] -= $cropX; //need to subtract start to prevent out of image
	$imageInfo[1] -= $cropY; //need to subtract start to prevent out of image
	
	$ratio = $nw / $nh;
	$ratioedW = $imageInfo[0];
	
	//find ratioed WxH that fits the image
	while($ratioedW > 0){
		$ratioedH = $ratioedW /$ratio;
		if($ratioedH <= $imageInfo[1] && ($ratioedH - floor($ratioedH) == 0)){ //if height is not out of the image and is a whole number (prevents rounding issue)
			break;
		}
		$ratioedW--;
	}
	
	if($ratioedW == $imageInfo[0] && $ratioedH == $imageInfo[1]){ //no need to crop
		$cropX = 0;
		$cropY = 0;
	}
	eval('$simg = imagecreatefrom'.$imageTypes[$imageInfo['2']].'($source);');
	$nImg = imagecreatetruecolor($nw, $nh);
	imagecopyresampled($nImg, $simg, 0,0, $cropX, $cropY, $nw, $nh, $ratioedW, $ratioedH);
	eval('image'.$imageTypes[$imageInfo['2']].'($nImg,$dest,$quality);');
}
	
function cropImage($nw, $nh, $source, $stype, $dest, $startX=0, $startY=0) {
	$size = getimagesize($source);
	$w = $size[0];
	$h = $size[1];
	switch($stype) {
		case 'gif':
		$simg = imagecreatefromgif($source);
		break;
		case 'jpg' || 'jpeg':
		$simg = imagecreatefromjpeg($source);
		break;
		case 'png':
		$simg = imagecreatefrompng($source);
		break;
	}
	$dimg = imagecreatetruecolor($nw, $nh);
	imagecopyresampled($dimg, $simg, 0, 0, $startX, $startY, $nw, $nh, $nw, $nh);
	imagejpeg($dimg, $dest, 100);
}	
	//ie: cropImage(225, 165, '/path/to/source/image.jpg', 'jpg', '/path/to/dest/image.jpg');
function imageResizeBg($nw, $nh, $source, $dest, $quality = 100, $r = 255, $g = 255, $b = 255){
	//validate image type
	$imageInfo = getimagesize($source);
	$imageTypes = array('', 'gif', 'jpeg', 'png', 'swf', 'psd', 'bmp');
    if(!isset($imageTypes[$imageInfo['2']])){
		return false;
	}
	//validate image deminsion and check if allowed memory size
	if(!chkImgMem($imageInfo)){
		return false;
	}
	
	//find max w/h that less than new w/h
	$redPerc = 0.9999;
	$resizeW = $imageInfo[0];
	$resizeH = $imageInfo[1];
	while(1){
		if($resizeW <= $nw && $resizeH <= $nh){ //resize w/h must be less than new w/h
			//if(($resizeW - floor($resizeW) == 0) && ($resizeH - floor($resizeH) == 0)){ //resize w/h must be whole #s 
				break;
			//}
		}
		if($redPerc <= 0){ // no possible percent
			break;
		}
		$resizeW = floor($imageInfo[0] * $redPerc);
		$resizeH = floor($imageInfo[1] * $redPerc);
		$redPerc -= 0.0001;
	}
	//x,y where to place on dest image
	$destx = floor(($nw - $resizeW)/2);
	$desty = floor(($nh - $resizeH)/2);
	//echo "\$resizeW: $resizeW, \$resizeH: $resizeH, \$destx: $destx, \$desty: $desty, \$redPerc: $redPerc";
	//create src image
	eval('$simg = imagecreatefrom'.$imageTypes[$imageInfo['2']].'($source);');
	//create dest image
	$dimg = imagecreatetruecolor($nw, $nh);
	imagefill($dimg, 0, 0, imagecolorallocate($dimg, $r, $g, $b)); 
	//echo "$dimg, $simg, $destx, $desty, 0, 0, $resizeW, $resizeH";
	imagecopyresampled($dimg, $simg, $destx, $desty, 0, 0, $resizeW, $resizeH, $imageInfo[0], $imageInfo[1]);
	eval('image'.$imageTypes[$imageInfo['2']].'($dimg, $dest, $quality);');
	imagedestroy($dimg);
	imagedestroy($simg);
	return true;
}

function chkImgMem($imageInfo){
	$allowedMem = ereg_replace('[^0-9]', '', ini_get('memory_limit')); //only get the number
	$allowedMem = $allowedMem * 1024 * 1024; //convert to bytes
	if(($imageInfo[0] * $imageInfo[1] * 5) > $allowedMem){ //5 bytes per pixel
		return false;
	}
	return true;
}

// Transparent PNG
function imageResizeAlpha($img, $w, $h, $newfilename) {
	//Check if GD extension is loaded
	if (!extension_loaded('gd') && !extension_loaded('gd2')) {
		trigger_error("GD is not loaded", E_USER_WARNING);
		return false;
	}
	
	//Get Image size info
	$imgInfo = getimagesize($img);
	switch ($imgInfo[2]) {
		case 1: $im = imagecreatefromgif($img); break;
		case 2: $im = imagecreatefromjpeg($img);  break;
		case 3: $im = imagecreatefrompng($img); break;
		default:  trigger_error('Unsupported filetype!', E_USER_WARNING);  break;
	}
	
	//If image dimension is smaller, do not resize
	if ($imgInfo[0] <= $w && $imgInfo[1] <= $h) {
		$nHeight = $imgInfo[1];
		$nWidth = $imgInfo[0];
	}else{
		//yeah, resize it, but keep it proportional
		if ($w/$imgInfo[0] > $h/$imgInfo[1]) {
			$nWidth = $w;
			$nHeight = $imgInfo[1]*($w/$imgInfo[0]);
		}else{
			$nWidth = $imgInfo[0]*($h/$imgInfo[1]);
			$nHeight = $h;
		}
	}
	$nWidth = round($nWidth);
	$nHeight = round($nHeight);
	
	$newImg = imagecreatetruecolor($nWidth, $nHeight);
	
	/* Check if this image is PNG or GIF, then set if Transparent*/  
	if(($imgInfo[2] == 1) OR ($imgInfo[2]==3)){
		imagealphablending($newImg, false);
		imagesavealpha($newImg,true);
		$transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
		imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
	}
	imagecopyresampled($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight, $imgInfo[0], $imgInfo[1]);
	
	//Generate the file, and rename it to $newfilename
	switch ($imgInfo[2]) {
		case 1: imagegif($newImg,$newfilename); break;
		case 2: imagejpeg($newImg,$newfilename);  break;
		case 3: imagepng($newImg,$newfilename); break;
		default:  trigger_error('Failed resize image!', E_USER_WARNING);  break;
	}
	
	return $newfilename;
}

?>