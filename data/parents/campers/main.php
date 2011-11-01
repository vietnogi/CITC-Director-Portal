<?
$this->ld['inputs'] = array(
	'parent_id' => newInput('parent_id', $_GET, 'int min 1')
);

$query = 'SELECT * FROM camper WHERE parent_id = :parent_id';
$values = array(':parent_id' => $this->ld['inputs']['parent_id']);
$this->ld['campers'] = $GLOBALS['mysql']->get($query, $values);

?>