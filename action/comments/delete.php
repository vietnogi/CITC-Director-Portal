<?
$keyfield = $this->gd['comment_for'] . '_comment_id';
$inputs = array(
	$keyfield => newInput($keyfield, $_GET, 'min 1 int')
);

$wherestr = $keyfield . ' = :' . $keyfield;
$wherevals = array(':' . $keyfield => $inputs[$keyfield]);

$GLOBALS['mysql']->delete($this->gd['comment_for'] . '_comment', $wherestr, $wherevals);

echo json_encode(array(
	'success' => true
));
?>