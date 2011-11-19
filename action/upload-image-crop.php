<?
$inputs = array(
	'tmp_path' => newInput('tmp_path', $_GET, 'min 1 path')
	, 'for' => newInput('for', $_GET, 'min 1')
	, 'id' => newInput('id', $_GET, 'min 1 int')
	, 'x' => newInput('x', $_POST, 'min 1 int')
	, 'y' => newInput('y', $_POST, 'min 1 int')
	, 'w' => newInput('w', $_POST, 'min 1 int')
	, 'h' => newInput('h', $_POST, 'min 1 int')
);

switch ($inputs['for']) {
	case 'season':
		// make sure season id exists
		$query = 'SELECT season_id FROM season WHERE season_id = :season_id';
		$values = array(
			':season_id' => $inputs['id']
		);
		$season = $GLOBALS['mysql']->getSingle($query, $values);
		if (empty($season)) {
			throw new Exception('The season id dose not exist: ' . $inputs['id']);
		}
		$destinationPath = CLIENTFILES . '/season/' . $inputs['id'];
	break;
	default:
		throw new Exception('The following for is not valid: ' . $inputs['for']);
}

// get temp file
$files = glob('/' . $inputs['tmp_path'] . '*'); // glob because we dont know ext
if (empty($files)) {
	throw new Exception('tmpPath does not exist: ' . $inputs['tmp_path']);
}
else if (count($files) != 1) {
	throw new Exception('multiple tmpPaths found: ' . $inputs['tmp_path']);
}
else {
	$tmpPath = $files[0];	
}

// cropping the image
cropImage($tmpPath, $destinationPath, $inputs);

// delete the tmp file
unlink($tmpPath);

echo json_encode(array(
	'success' => true
	, 'error' => NULL
));
?>