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

function isAjax () {
	return !empty($_GET['_']);	
}
?>