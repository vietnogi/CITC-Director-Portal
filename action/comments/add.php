<?
$inputs = array(
	$this->gd['comment_for'] . '_id' => newInput($this->gd['comment_for'] . '_id', $_GET, 'min 1 int')
	, 'comment' => newInput('comment', $_POST, 'min 1')
);

$values = array(
	'created' => DATETIME
	, $this->gd['comment_for'] . '_id' => $inputs[$this->gd['comment_for'] . '_id']
	, 'staff_id' => $this->gd['staff']['staff_id']
	, 'comment' => $inputs['comment']
);

$GLOBALS['mysql']->insert($this->gd['comment_for'] . '_comment', $values);

echo json_encode(array(
	'success' => true
));
?>