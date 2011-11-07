<?
$inputs = array(
	'season_id' => newInput('season_id', $_GET, 'min 1 int')
);
$this->redirect = '/seasons/main';

// validate file size
$maxSize = 3 * 1024 * 1000 * 1000; //MB
if (filesize(DR . $file) > $maxSize) {
	$error = 'The uploaded file is too big; the max file size is: ' . $maxSize . ' MB';
}
/*
$uploadPath = CLIENTFILES . '/' . $this->systemVars['clent'] . '/seasons';
move_uploaded_file($_FILES['season_image']['tmp_name'], 
*/
?>