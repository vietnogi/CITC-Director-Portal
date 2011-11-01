<?
$inputs = array(
	'first_name' => newInput('first_name', $_POST, 'min 1')
	, 'last_name' => newInput('last_name', $_POST, 'min 1')
	, 'email' => newInput('email', $_POST, 'min 1 email')
	, 'password' => newInput('password', $_POST, 'min 6')
);

//create new user
$userExists = $GLOBALS['login']->exists($inputs['email']);
if ($userExists) { //user exists
	logError('email is already in use');
}

$newUserid = $GLOBALS['login']->create($inputs['email'], $inputs['password']);

if (!is_numeric($newUserid)) {
	logError('problem with creating login');	
}

//assign new user to group
$values = array(
	'user_id' => $newUserid
	, 'user_group_id' => PARENTGROUP
);
$GLOBALS['mysql']->insert('user_group_link', $values);


//create new parent
$values = array('user_id' => $newUserid
	, 'first_name' => $inputs['first_name']
	, 'last_name' => $inputs['last_name']
	, 'email' => $inputs['email']
	/*, 'receive_information_and_offers' => $inputs['offers']
	, 'parent_referrer_id' => $inputs['referrer']*/
);
$GLOBALS['mysql']->insert('parent', $values);
$parentid = $GLOBALS['mysql']->lastInsertId();

$this->redirect = $GLOBALS['bc']->path . '/detail?parent_id=' . $parentid;
?>