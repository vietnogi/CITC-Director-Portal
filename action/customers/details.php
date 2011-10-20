<?
$inputs = array(
	'customer_id' => newInput('customer_id', $_GET, 'min 1 int')
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
$wherestr = 'customer_id = :customer_id';
$wherevals = array(':customer_id' => $inputs['customer_id']);

$GLOBALS['mysql']->update('customer', $values, $wherestr, $wherevals);

$_SESSION[CR]['user-success'] = 'Customer updated successfuly.';

$this->redirect = $_SERVER['HTTP_REFERER'];
?>