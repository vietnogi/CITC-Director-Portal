<?
function uploadImage($tmpName, $destPath) {
	// validate file size
	$maxSize = 5 * 1024 * 1000 * 1000; //MB
	$validMimes = array(
		'image/jpeg' => 'jpg'
		, 'image/gif' => 'gif'
		, 'image/png' => 'png'
	);
	$error = NULL;
	
	if (!file_exists($tmpName)) {
		$error = 'The file could not be uploaded.';
	}
	else if (filesize($tmpName) > $maxSize) {
		$error = 'The uploaded file is too big; the max file size is: ' . $maxSize . ' MB';
	}
	else {
		$mime = isMime($tmpName, array_keys($validMimes));
		if($mime === false){
			$error = 'The file type is invalid, please upload a JPG, GIF or PNG file.';
		}
		else {
			if (!move_uploaded_file($tmpName, $destPath . '.' . $validMimes[$mime])) {
				throw new Exception('Unable to move uploaded file to ' . $destPath);
			}
			// remove any old file that may have have diferent mime
			foreach ($validMimes as $validMime => $ext) {
				if ($validMime == $mime) { // file that was just uploaded
					continue;
				}
				if (file_exists($destPath . '.' . $ext)) {
					unlink($destPath . '.' . $ext);
				}
			}	
		}
	}
	
	return $error;
}
?>