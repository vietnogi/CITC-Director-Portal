<?
$inputs = array(
	'season_id' => newInput('season_id', $_GET, 'int min 1')
);

$query = 'SELECT * FROM season WHERE season.season_id = :season_id';
$values = array(':season_id' => $inputs['season_id']);
$this->ld['season'] = $GLOBALS['mysql']->getSingle($query, $values);
if (empty($this->ld['season'])) {
	logError('season is empty.');
}

?>