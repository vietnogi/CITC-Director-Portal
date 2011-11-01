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
foreach ($filterFields as $table => $fields) {
	foreach ($fields as $field => $type) {
		$name = (empty($table) ? '' : $table . '-') . $field;
		$this->ld['inputs'][$name] = newInput($name, $_GET);
	}
}

$filter = new Filter('camper', $filterFields, $this->ld['inputs'], array(
	'select' => 'season.name AS season, camper.first_name, camper.last_name, IFNULL(COUNT(enrollment_session_link.enrollment_session_link_id), 0) AS session_count'
	, 'from' => 'enrollment INNER JOIN season ON enrollment.season_id = season.season_id
				INNER JOIN camper ON camper.camper_id = enrollment.camper_id
				LEFT JOIN enrollment_session_link ON enrollment_session_link.enrollment_id = enrollment.enrollment_id'
	, 'where' => 'season.enrollment_end_date < "' . DATE . '"'
	, 'groupby' => 'enrollment.enrollment_id'
));

$pagination = new Pagination(10, $filter->rowCount());
$this->ld['rows'] = $filter->getRows($pagination->offset, $pagination->limit);
$this->ld['row_headers'] = array(
	'#' => NULL
	, 'First Name' => 'camper-first_name'
	, 'Last Name' => 'camper-last_name'
	, 'Season' => 'season-name'
	, 'Num of Sessions' => 'session_count'
);

?>