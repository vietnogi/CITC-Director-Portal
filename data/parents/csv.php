<?
require DR . '/php/filter.php';

$this->ld['inputs'] = array(
	'sort_field' => newInput('sort_field', $_GET)
	, 'sort_desc' => newInput('sort_desc', $_GET, 'int', '0')
);

$filterFields = array(
	'parent' => array(
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

$filter = new Filter('parent', $filterFields, $this->ld['inputs'], array(
	'select' => 'parent.parent_id, parent.first_name, parent.last_name, parent.email'
	, 'from' => 'parent'
));

header('Content-type: text/csv');
header('Content-Disposition: attachment; filename="' . date('y-m-d') . '-parents.csv"');
header("Pragma: public");// need for IE

$interval = 100;
$rowCount = $filter->rowCount();

// output csv
$outstream = fopen("php://output", 'w');
// headers
fputcsv($outstream, array(
	'Parent ID'
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