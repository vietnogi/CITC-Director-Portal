<?
$user = $this->classes['login']->isLoggedIn();
if(notEmptyArray($user)){ //session is available
	$this->classes['login']->logout($user);
}
$_SESSION[CR]['user-success'] = 'You have been logged out.';
$this->redirect = '/login';
?>