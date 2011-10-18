<?
$inputs = array(
	'email' => strtolower(newInput('email', $_GET, 'min 1 email'))
);
$query = 'SELECT user_id FROM user WHERE login = :login';
$values = array(':login' => $inputs['email']);
$this->ld['exists'] = $GLOBALS['mysql']->getSingle($query, $values);
?>