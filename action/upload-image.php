<?
$inputs = array(
	'image' => newInput('image', $_FILES, array('tmp_name' => 'min 1'))
	, 'for' => newInput('for', $_GET, 'min 1')
	, 'id' => newInput('id', $_GET, 'min 1 int')
);

switch ($inputs['for']) {
	case 'season':
		$restrictions = array(
			'w' => 500
			, 'h' => 500
		);
	break;
	default:
		throw new Exception('The following for is not valid: ' . $inputs['for']);
}

$error = uploadImage($inputs['image']['tmp_name'], $inputs['image']['tmp_name'], $restrictions);

echo json_encode(array(
	'success' => ($error === NULL) ? true : false
	, 'error' => $error
	, 'tmp_name' => basename($inputs['image']['tmp_name'])
));
?>