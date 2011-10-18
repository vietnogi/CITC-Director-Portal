<?
$inputs = array(
	'camper_id' => newInput('camper_id', $_GET, 'int min 1')
);

$query = 'SELECT * FROM camper WHERE camper.camper_id = :camper_id';
$values = array(':camper_id' => $inputs['camper_id']);
$this->ld['camper'] = $GLOBALS['mysql']->getSingle($query, $values);
if (empty($this->ld['camper'])) {
	logError('camper is empty.');
}

?>