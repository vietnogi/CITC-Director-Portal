<?
$for = $this->gd['comment_for'];
$this->ld['inputs'] = array(
	$for . '_id' => newInput($for . '_id', $_GET, 'int min 1')
);

$query = 'SELECT ' . $for . '_comment.*, staff.first_name, staff.last_name
	FROM ' . $for . '_comment INNER JOIN staff ON staff.staff_id = ' . $for . '_comment.staff_id
	WHERE ' . $for . '_id = :' . $for . '_id';
$values = array(':' . $for . '_id' => $this->ld['inputs'][$for . '_id']);
$this->ld['comments'] = $GLOBALS['mysql']->get($query, $values);

?>