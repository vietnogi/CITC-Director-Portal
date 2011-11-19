<?

function died ($location, $useJSON, $useCR = true, $response = '302 Temporary Redirect') {
	
	if (empty($location)) {
		trigger_error('$location is empty', E_USER_ERROR);	
	}
	
	header('HTTP/1.1 ' . $response);

	$location = ($useCR ? CR : '') . $location;
	
	if ($useJSON) {
		$json = array('location' => $location);
		echo json_encode($json);
	}
	else {
		header('location: ' . $location);
	}
	
	exit;
}

function notEmptyArray ($array) {
	return is_array($array) && !empty($array);
}

function isAssociativeArray ($array) {
	return array_keys($array) !== range(0, count($array) - 1);
}

function findDuplicates($array) {
	return array_unique(array_diff_assoc($array, array_unique($array)));
}

/*
newInput is a solution to use Input and return value;
can use $validate recursivly
ie.
$inputs = array(
	'id' => newInput('id', $_POST, 'min 1 int')
	, 'campers' => newInput('campers', $_POST, array(
		'first_name' => 'min 1'
		, 'last_name' => 'min 1'
		, 'sessions' => array(
			'1' => array(
				'name' => null
				, 'date' => NULL
			)
			, '2' => array(
				'name' => NULL
				, 'date' => NULL
			)
		)
	))
);
*/
function newInput ($name, $source = NULL, $validate = NULL, $default = NULL) {
	// handle recursive array
	if (notEmptyArray($validate)) {
		$inputs = array();
		foreach ($validate as $subname => $subvalidate) {
			if (!notEmptyArray($source[$name])) {
				$source[$name] = array();
			}
			$subdefault = isset($default[$name]) ? $default[$name] : NULL;
			$inputs[$subname] = newInput($subname, $source[$name], $subvalidate, $subdefault);
		}
		return $inputs;
	}
	
	// single
	$input = new Input($name, $source, $validate, $default);
	return $input->value;
}

// warning, $path should already have DR
function isMime ($path, $mimes = array()) {
	$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
	$mime = finfo_file($finfo, $path);
	finfo_close($finfo);
	if (!in_array($mime, $mimes)) {
		return false;
	}
	return $mime;
}

function isAjax() {
	return !empty($_GET['_']);	
}

function uploadImage($tmpName, $destPath = NULL, $restrictions = array()) {
	// handle defaults
	$restrictions = array_merge(array(
		'max_size' => 5 * 1024 * 1000 //MB
		, 'mimes' => array(
			'image/jpeg' => 'jpg'
			, 'image/gif' => 'gif'
			, 'image/png' => 'png'
			)
		, 'h' => 2000
		, 'w' => 2000
	), $restrictions);
	
	$error = NULL;
	
	if (!file_exists($tmpName)) {
		$error = 'The file could not be uploaded.';
	}
	else if (filesize($tmpName) > $restrictions['max_size']) {
		$error = 'The uploaded file is too big; the max file size is: ' . ($restrictions['max_size'] / 1000 / 1024) . ' MB';
	}
	else {
		$mime = isMime($tmpName, array_keys($restrictions['mimes']));
		if($mime === false){
			$error = 'The file type is invalid, please upload a JPG, GIF or PNG file.';
		}
		else {
			// handle max w/h
			$image = new imagick($tmpName); 
			$dim = $image->getImageGeometry(); 
  			if ($dim['width'] > $restrictions['w'] || $dim['height'] > $restrictions['h']) {
				$image->resizeImage($restrictions['w'], $restrictions['h'], NULL, 1, true);
				$image->writeImage($tmpName);
			}
			$image->clear();
			$image->destroy();
			
			// handle destination path
			if(!empty($destPath)) {
				if (!move_uploaded_file($tmpName, $destPath . '.' . $restrictions['mimes'][$mime])) {
					throw new Exception('Unable to move uploaded file to ' . $destPath);
				}
				// remove any old file that may have have diferent mime
				foreach ($restrictions['mimes'] as $validMime => $ext) {
					if ($validMime == $mime) { // file that was just uploaded
						continue;
					}
					if (file_exists($destPath . '.' . $ext)) {
						unlink($destPath . '.' . $ext);
					}
				}	
			}
		}
	}
	
	return $error;
}

function cropImage ($src, $dest, $restrictions) {
	$image = new imagick($src);
	$properties = $image->identifyImage();
	
	$image->cropImage($restrictions['w'], $restrictions['h'], $restrictions['x'], $restrictions['y']);
	
	// figure out extention to use and write to destination
	list($format) = explode(' ', $properties['format']);
	$extensions = array(
		'JPEG' => 'jpg'
		, 'GIF' => 'gif'
		, 'PNG' => 'png'
	);
	if (!isset($extensions[$format])) {
		throw new Exception('Format is not supported: ' . $format);
	}
	
	// clear any existing files
	foreach ($extensions as $ext) {
		if (is_file($dest . '.' . $ext)) {
			unlink($dest . '.' . $ext);
		}
	}
	
	$image->writeImage($dest . '.' . $extensions[$format]);
	$image->clear();
	$image->destroy();
	
	return true;
}
?>