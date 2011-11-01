<?
$inputs = array(
	'customer_id' => newInput('customer_id', $_GET, 'int min 1')
);

$query = 'SELECT * FROM customer WHERE customer.customer_id = :customer_id';
$values = array(':customer_id' => $inputs['customer_id']);
$this->ld['customer'] = $GLOBALS['mysql']->getSingle($query, $values);
if (empty($this->ld['customer'])) {
	logError('Customer is empty.');
}

?>