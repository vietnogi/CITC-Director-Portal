<?
$inputs = array(
	'customer_id' => newInput('customer_id', $_GET, 'min 1 int')
	, 'comment' => newInput('comment', $_POST, 'min 1')
);

$values = array(
	'created' => DATETIME
	, 'customer_id' => $inputs['customer_id']
	, 'comment' => $inputs['comment']
);

$GLOBALS['mysql']->insert('customer_comment', $values);
?>