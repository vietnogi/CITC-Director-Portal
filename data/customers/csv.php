<?
require DR . '/php/filter.php';

$this->ld['inputs'] = array(
	'sort_field' => newInput('sort_field', $_GET)
	, 'sort_desc' => newInput('sort_desc', $_GET, 'int', '0')
);

$filterFields = array(
	'customer' => array(
		'first_name' => 'string'
		, 'last_name' => 'string'
		, 'email' => 'string'
	)
);
foreach ($filterFields as $table => $fields) {
	foreach ($fields as $field => $type) {
		$name = (empty($table) ? '' : $table . '-') . $field;
		$this->ld['inputs'][$name] = newInput($name, $_GET);
	}
}

$filter = new Filter('customer', $filterFields, $this->ld['inputs'], array(
	'select' => 'customer.customer_id, customer.first_name, customer.last_name, customer.email'
	, 'from' => 'customer'
));

header('Content-type: text/csv');
header('Content-Disposition: attachment; filename="' . date('y-m-d') . '-customers.csv"');
header("Pragma: public");// need for IE

$interval = 100;
$rowCount = $filter->rowCount();

// output csv
$outstream = fopen("php://output", 'w');
// headers
fputcsv($outstream, array(
	'Customer ID'
	, 'First Name'
	, 'Last Name'
	, 'Email'
	, 'Balance'
), ',', '"');

for($i = 0; $i < $rowCount; $i += $interval){
	$rows = $filter->getRows($i, $interval);
	foreach ($rows as $row) {
		fputcsv($outstream, $row, ',', '"');
	}
}

?>