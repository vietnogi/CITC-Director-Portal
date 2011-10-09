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

function is_assoc ($array) {
	$i = 0;
	foreach ($array as $index => $val) {
		if ($index !== $i) {
			return true;
		}
		$i++;
	}
	return false;
}

function logError ($error, $redirect = true) {
	$fh = fopen(DR . '/logs/errors.txt', 'a') or die('can\'t open error file');
	fwrite($fh, date('m/d/y h:i:s A', TIME) . ' :: ' . $_SERVER['REMOTE_ADDR'] . "\n" . $_SERVER['REQUEST_URI'] . ' : ' . $error . "\n");
	fclose($fh);
	if (DEVELOPMENT) {
		$_SESSION[CR]['error'] = $error; //so error page can output it
	}
	if ($redirect) {
		died('/error', false, true);	
	}
}

function pr($array, $die = true){
	print_r($array);
	if($die){
		die();	
	}
}
?>