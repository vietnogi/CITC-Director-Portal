<?
$this->ld['inputs'] = array(
	'customer_id' => newInput('customer_id', $_GET, 'int min 1')
);

$query = 'SELECT * FROM customer_comment WHERE customer_id = :customer_id';
$values = array(':customer_id' => $this->ld['inputs']['customer_id']);
$this->ld['comments'] = $GLOBALS['mysql']->get($query, $values);

?>