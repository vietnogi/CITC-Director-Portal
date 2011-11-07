<?
$user = $GLOBALS['login']->isLoggedIn();
if(notEmptyArray($user)){ //session is available
	$GLOBALS['login']->logout($user);
}
$_SESSION[CR]['user-success'] = 'You have been logged out.';
$this->redirect = '/up/login';
?>