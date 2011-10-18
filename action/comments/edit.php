<?
$keyfield = $this->gd['comment_for'] . '_comment_id';
$inputs = array(
	$keyfield => newInput($keyfield, $_GET, 'min 1 int')
	, 'comment' => newInput('comment', $_POST, 'min 1')
);

$values = array(
	'comment' => $inputs['comment']
);
$wherestr = $keyfield . ' = :' . $keyfield;
$wherevals = array(':' . $keyfield => $inputs[$keyfield]);

$GLOBALS['mysql']->update($this->gd['comment_for'] . '_comment', $values, $wherestr, $wherevals);
?>