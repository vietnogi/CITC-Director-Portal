<?
//validate
$checks = array();
$checks[]['min 1 email'] = $_POST['login'];
$checks[]['min 1'] = $_POST['password'];
$GLOBALS['validate']->many($checks);

$user = $GLOBALS['login']->authenticate($_POST['login'], $_POST['password']);
if (notEmptyArray($user)) {
	//authenticate successful
	if ($GLOBALS['login']->isLockedOut($user)) {
		//user is locked out
		$_SESSION[CR]['user-error'] = 'Due to the number of failed login attempts in a short period of time, your account is temporarily until ' . date('m/d/Y h:i:s a', strtotime($user['lockout_end']));
	}
	else if (!$GLOBALS['login']->isActive($user)) {
		//user is inactive
		$_SESSION[CR]['user-error'] = 'The login account has been deactivated.';
	}
	else {
		//create login session
		$GLOBALS['login']->newSession($user, SESSIONLENGTH);
		$GLOBALS['login']->resetFails($user);
		
		if (isset($_SESSION[CR]['redirect-after-login'])) {
			//user was trying to access a protected page, so it be nice if we send them back after logging in
			$this->redirect = $_SESSION[CR]['redirect-after-login'];
			unset($_SESSION[CR]['redirect-after-login']);
		}
		else {
			$this->redirect = '/customer/home';
		}
	}
}
else {
	//authenticate not successful
	$user = $GLOBALS['login']->updateFailedLogin($_POST['login'], FAILEDATTEMPTLENGTH);
	if (notEmptyArray($user)) {
		//login was valid
		if ($user['num_failed_logins'] > MAXFAILEDATTEMPTS) {
			//lockout for a certain amount of time
			$GLOBALS['login']->lockout($user, LOCKOUTLENGTH);
		}
	}
	$_SESSION[CR]['user-error'] = 'The login information was incorrect.';
}

if (empty($this->redirect)) {
	$this->redirect = $_SERVER['HTTP_REFERER'];
}
?>