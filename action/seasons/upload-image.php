<?
$inputs = array(
	'season_id' => newInput('season_id', $_GET, 'min 1 int')
	, 'season_image' => newInput('season_image', $_FILES, array('tmp_name' => 'min 1'))
);

// validate file size
$maxSize = 3 * 1024 * 1000 * 1000; //MB
$validMimes = array(
	'image/jpeg'
	, 'image/gif'
	, 'image/png'
);
$success = false;

if (!file_exists($inputs['season_image']['tmp_name'])) {
	$error = 'The file could not be uploaded.';
}
else if (filesize($inputs['season_image']['tmp_name']) > $maxSize) {
	$error = 'The uploaded file is too big; the max file size is: ' . $maxSize . ' MB';
}
else if(!isMime($inputs['season_image']['tmp_name'], $validMimes)){
	$error = 'The file type is invalid, please upload a JPG, GIF or PNG file.';	
}
else {
	$error = NULL;
	$success = true;
}

echo json_encode(array(
	'success' => $success
	, 'error' => $error
));

?>