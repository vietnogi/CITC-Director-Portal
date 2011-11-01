<?
$inputs = array(
	'parent_id' => newInput('parent_id', $_GET, 'min 1 int')
	, 'first_name' => newInput('first_name', $_POST, 'min 1')
	, 'last_name' => newInput('last_name', $_POST, 'min 1')
	, 'email' => newInput('email', $_POST, 'min 1 email')
);

// Update
$values = array(
	'first_name' => $inputs['first_name']
	, 'last_name' => $inputs['last_name']
	, 'email' => $inputs['email']
);
$wherestr = 'parent_id = :parent_id';
$wherevals = array(':parent_id' => $inputs['parent_id']);

$GLOBALS['mysql']->update('parent', $values, $wherestr, $wherevals);

$_SESSION[CR]['user-success'] = 'Parent updated successfuly.';

$this->redirect = $_SERVER['HTTP_REFERER'];
?>