<?
require DR . '/php/filter.php';
require DR . '/php/pagination.php';

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
	, NULL => array(
		'camper_count' => 'int'
	)
);
foreach ($filterFields as $table => $fields) {
	foreach ($fields as $field => $type) {
		$name = (empty($table) ? '' : $table . '-') . $field;
		$this->ld['inputs'][$name] = newInput($name, $_GET);
	}
}

$filter = new Filter('customer', $filterFields, $this->ld['inputs'], array(
	'select' => 'customer.customer_id, customer.first_name, customer.last_name, customer.email, IFNULL(COUNT(camper.camper_id), 0) AS camper_count'
	, 'from' => 'customer LEFT JOIN camper ON camper.customer_id = customer.customer_id'
	, 'groupby' => 'camper.camper_id'
));

$pagination = new Pagination(10, $filter->rowCount());
$this->ld['rows'] = $filter->getRows($pagination->offset, $pagination->limit);
$this->ld['row_headers'] = array(
	'#' => NULL
	, 'First Name' => 'customer-first_name'
	, 'Last Name' => 'customer-last_name'
	, 'Email' => 'customer-email'
	, '# of Campers' => 'camper_count'
);

// Table Actions
$this->ld['table_actions'] = array(
	'Add New Customer' => $this->url($GLOBALS['bc']->path . '/add')
);
?>