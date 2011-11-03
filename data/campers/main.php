<?
require DR . '/php/filter.php';
require DR . '/php/pagination.php';

$this->ld['inputs'] = array(
	'sort_field' => newInput('sort_field', $_GET)
	, 'sort_desc' => newInput('sort_desc', $_GET, 'int', '0')
);

$filterFields = array(
	'camper' => array(
		'first_name' => 'string'
		, 'last_name' => 'string'
		, 'email' => 'string'
	)
);
// set filter fields to inputs
foreach ($filterFields as $table => $fields) {
	foreach ($fields as $field => $type) {
		$name = (empty($table) ? '' : $table . '-') . $field;
		$this->ld['inputs'][$name] = newInput($name, $_GET);
	}
}

$filter = new Filter('camper', $filterFields, $this->ld['inputs'], array(
	'select' => 'camper.camper_id, camper.first_name, camper.last_name, camper.email'
	, 'from' => 'camper'
	, 'groupby' => 'camper.camper_id'
));

$pagination = new Pagination(10, $filter->rowCount());
$this->ld['rows'] = $filter->getRows($pagination->offset, $pagination->limit);
$this->ld['row_headers'] = array(
	'#' => NULL
	, 'First Name' => 'camper-first_name'
	, 'Last Name' => 'camper-last_name'
	, 'Email' => 'camper-email'
);

?>