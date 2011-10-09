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

function logError ($error, $redirect = '/error', $errorLogPath = '/errors.txt') {
	$fh = fopen(DR . '/logs' . $errorLogPath, 'a') or die('Cannot open error file.');
	fwrite($fh, DATETIME . ' :: ' . $_SERVER['REMOTE_ADDR'] . "\n" . $_SERVER['REQUEST_URI'] . ' : ' . $error . "\n");
	fclose($fh);
	
	if (DEVELOPMENT && $redirect == '/error') {
		if ($_SESSION[CR]['debug']) {
			?>
			<h3 style="color:#FF0000">System Error: <?= $error ?></h3>
			<h4>Backtrace:</h4>
			<?
			pr(debug_backtrace());
			die();
		}
		$_SESSION[CR]['error'] = $error; //so error page can output it
	}
	
	if (!empty($redirect)) {
		died($redirect, false, true);	
	}
}

function findDuplicates($array) {
	return array_unique(array_diff_assoc($array, array_unique($array)));
}

// newInput is a solution to use Input and return value;
function newInput ($name, $source = NULL, $validate = NULL, $default = NULL) {
	$input = new Input($name, $source, $validate, $default);
	return $input->value;
}
?>