<?
$inputs = array(
	'season_id' => newInput('season_id', $_GET, 'min 1 int')
	, 'season_image' => newInput('season_image', $_FILES, array('tmp_path' => 'min 1'))
);
$this->redirect = '/seasons/main';

$file = $_FILES[''];

// validate file size
$maxSize = 3 * 1024 * 1000 * 1000; //MB
$validMimes = array(
	'image/jpeg'
	, 'image/gif'
	, 'image/png'
);

if (filesize(DR . $file) > $maxSize) {
	$error = 'The uploaded file is too big; the max file size is: ' . $maxSize . ' MB';
}
else if(!isMime(DR . $file, $validMimes)){
	$error = 'The file type is invalid, please upload a JPG, GIF or PNG file.';	
}
else {
	$error = NULL;	
}


?>