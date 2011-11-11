<?
$inputs = array(
	'login' => new Input('login', $_POST, 'min 1 email')
	, 'password' => new Input('password', $_POST, 'min 1')
	);

$error = false;

$user = $GLOBALS['login']->authenticate($inputs['login']->value, $inputs['password']->value);
if (notEmptyArray($user)) {
	//authenticate successful
	if ($GLOBALS['login']->isLockedOut($user)) {
		//user is locked out
		$error = 'Due to the number of failed login attempts in a short period of time, your account is temporarily until ' . date('m/d/Y h:i:s a', strtotime($user['lockout_end']));
	}
	else if (!$GLOBALS['login']->isActive($user)) {
		//user is inactive
		$error = 'The login account has been deactivated.';
	}
	else {
		//create login session
		$GLOBALS['login']->newSession($user, config('session length'));
		$GLOBALS['login']->resetFails($user);
	}
}
else {
	//authenticate not successful
	$user = $GLOBALS['login']->updateFailedLogin($inputs['login']->value, config('failed attempt length'));
	if (notEmptyArray($user)) {
		//login was valid
		if ($user['num_failed_logins'] > config('max failed attempts')) {
			//lockout for a certain amount of time
			$GLOBALS['login']->lockout($user, config('lockout length'));
		}
	}
	$error = 'The login information was incorrect.';
}

if (!empty($error)) {
	$_SESSION[CR]['user-error'] = $error;
	$this->redirect = '/public/login';
}
else {
	$this->redirect = '/dashboard/main?t=' . $GLOBALS['login']->getToken();	
}
?>