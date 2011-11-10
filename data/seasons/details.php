<? //a change
$inputs = array(
	'season_id' => newInput('season_id', $_GET, 'int min 1')
);

$query = 'SELECT * FROM season WHERE season.season_id = :season_id';
$values = array(':season_id' => $inputs['season_id']);
$this->ld['season'] = $GLOBALS['mysql']->getSingle($query, $values);
if (empty($this->ld['season'])) {
	logError('season is empty.');
}

$_POST = array(
	'id' => '1'
	, 'campers' => array(
		'first_name' => 'alex'
		, 'last_name' => 'tran'
		, 'sessions' => array(
			'1' => array(
				'date' => '10/15/2011'
			)
			, '2' => array(
				'name' => 'session 2'
			)
		)
	)
);

$inputs = array(
	'id' => newInput('id', $_POST, 'min 1 int')
	, 'campers' => newInput('campers', $_POST, array(
		'first_name' => 'min 1'
		, 'last_name' => 'min 1'
		, 'sessions' => array(
			'1' => array(
				'name' => null
				, 'date' => NULL
			)
			, '2' => array(
				'name' => NULL
				, 'date' => NULL
			)
		)
	))
);

print_r($inputs);

?>