<?
$inputs = array(
	'customer_id' => newInput('customer_id', $_GET, 'min 1 int')
	, 'first_name' => newInput('first_name', $_POST, 'min 1')
	, 'last_name' => newInput('last_name', $_POST, 'min 1')
);

$GLOBALS['mysql']->insert('camper', $inputs);
$camperid = $GLOBALS['mysql']->lastInsertId();

$this->redirect = '/campers/detail?camper_id' = $camperid;
?>