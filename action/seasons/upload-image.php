<?
$inputs = array(
	'season_id' => newInput('season_id', $_GET, 'min 1 int')
	, 'season_image' => newInput('season_image', $_FILES, array('tmp_name' => 'min 1'))
);

$error = uploadImage($inputs['season_image']['tmp_name'], CLIENTFILES . '/' . $this->systemVars['client'] . '/seasons/' . $inputs['season_id']);

echo json_encode(array(
	'success' => ($error === NULL) ? true : false
	, 'error' => $error
));

?>