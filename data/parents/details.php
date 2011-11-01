<?
$inputs = array(
	'parent_id' => newInput('parent_id', $_GET, 'int min 1')
);

$query = 'SELECT * FROM parent WHERE parent.parent_id = :parent_id';
$values = array(':parent_id' => $inputs['parent_id']);
$this->ld['parent'] = $GLOBALS['mysql']->getSingle($query, $values);
if (empty($this->ld['parent'])) {
	logError('Parent is empty.');
}

?>