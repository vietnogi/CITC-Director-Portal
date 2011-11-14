<?
$inputs = array(
	'login' => newInput('login', $_POST, 'min 1 email')
	, 'password' => newInput('password', $_POST, 'min 1')
	, 'stay_signed_in' => newInput('stay_signed_in', $_POST, 'int', '0')
);

$error = false;

$user = $GLOBALS['login']->authenticate($inputs['login'], $inputs['password']);
if (notEmptyArray($user)) {
	// authenticate successful
	if ($GLOBALS['login']->isLockedOut($user)) {
		// user is locked out
		$error = 'Due to the number of failed login attempts in a short period of time, your account is temporarily until ' . date('m/d/Y h:i:s a', strtotime($user['lockout_end']));
	}
	else if (!$GLOBALS['login']->isActive($user)) {
		// user is inactive
		$error = 'The login account has been deactivated.';
	}
	else {
		// create login session
		$sessionLength = ($inputs['stay_signed_in'] == '1') ? config('stay signed in length') : config('session length');
		$GLOBALS['login']->newSession($user, $sessionLength);
		$GLOBALS['login']->resetFails($user);
		
		// handle stay signed in
		if ($inputs['stay_signed_in'] == '1') {
			// save token to cookie
			if(!setcookie('t', $GLOBALS['login']->getToken(), TIME + $sessionLength, CR . '/', $_SERVER['HTTP_HOST'], true, true)) {
				throw new Exception('Unable to set cookie data for $t.');
			}
		}
	}
}
else {
	// authenticate not successful
	$user = $GLOBALS['login']->updateFailedLogin($inputs['login'], config('failed attempt length'));
	if (notEmptyArray($user)) {
		// login was valid
		if ($user['num_failed_logins'] > config('max failed attempts')) {
			// lockout for a certain amount of time
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