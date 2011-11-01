<?
require DR . '/php/filter.php';
require DR . '/php/pagination.php';

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

$pagination = new Pagination(10, $filter->rowCount());
$this->ld['rows'] = $filter->getRows($pagination->offset, $pagination->limit);

// Row Headers
$this->ld['row_headers'] = array(
	'#' => NULL
	, 'First Name' => 'parent-first_name'
	, 'Last Name' => 'parent-last_name'
	, 'Email' => 'parent-email'
	, 'Balance' => NULL
	, 'Flags' => NULL
);

// Table Actions
$this->ld['table_actions'] = array(
	'Add New Parent' => $this->url($GLOBALS['bc']->path . '/add')
);
?>