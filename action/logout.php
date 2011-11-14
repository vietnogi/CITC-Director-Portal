<?
$user = $GLOBALS['login']->isLoggedIn();
if(notEmptyArray($user)){ //session is available
	$GLOBALS['login']->logout($user);
}

// unset cookie
if(!setcookie('t', NULL, 0, CR . '/', $_SERVER['HTTP_HOST'], true, true)) {
	throw new Exception('Unable to set cookie data for $t.');
}

$_SESSION[CR]['user-success'] = 'You have been logged out.';
$this->redirect = '/up/login';
?>