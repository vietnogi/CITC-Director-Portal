<?
require DR . '/php/filter.php';
require DR . '/php/pagination.php';

$this->ld['inputs'] = array(
	'sort_field' => newInput('sort_field', $_GET)
	, 'sort_desc' => newInput('sort_desc', $_GET, 'int', '0')
);

$filterFields = array(
	'season' => array(
		'name' => 'string'
	)
);
// set filter fields to inputs
foreach ($filterFields as $table => $fields) {
	foreach ($fields as $field => $type) {
		$name = (empty($table) ? '' : $table . '-') . $field;
		$this->ld['inputs'][$name] = newInput($name, $_GET);
	}
}

$filter = new Filter('season', $filterFields, $this->ld['inputs'], array(
	'select' => 'season.*, COUNT(session.session_id) AS session_count'
	, 'from' => 'season LEFT JOIN session ON session.season_id = season.season_id'
	, 'groupby' => 'season.season_id'
));

$pagination = new Pagination(10, $filter->rowCount());
$this->ld['rows'] = $filter->getRows($pagination->offset, $pagination->limit);

// Row Headers
$this->ld['row_headers'] = array(
	'#' => NULL
	, 'Name' => 'season-name'
	, 'Enrollment Period' => 'season-enrollment_start_date'
	, 'Session Count' => 'session_count'
	, 'Enrollment Count' => NULL
	, 'Specialty Count' => NULL
);

// Table Actions
$this->ld['table_actions'] = array(
	'Add New Season' => $this->url($GLOBALS['bc']->path . '/add')
);
?>