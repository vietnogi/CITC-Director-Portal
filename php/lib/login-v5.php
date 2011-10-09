<?php

class Login{
	
	//private
	private $key = 'I like red pickled apples!';
	private $ckiePassIndex = '-login-password'; //for the associative index, CR is used for multiple portals
	private $ckieLoginIndex = '-login-login'; //for the associative index, CR is used for multiple portals
	private $sessionTokenIndex = '-login-token';
	private $status = NULL; //false: none,  1: no login, 2: timeout, 3: bad group
	
	private $maxFailedLogins = 100; //max number of failed logins before the login is locked
	private	$failedLoginSec = 3600; // the interval used to check maxFailedLogins: ie, num failed logins in the last $failedLoginSec seconds

	//constructors
	public function __construct ($userInputedToken = NULL) {
		//have to concat CR here because cant concat during declaration
		$this->key = CR . $this->key; // CR is used for multiple portals
		$this->ckiePassIndex = CR . $this->ckiePassIndex; // CR is used for multiple portals
		$this->ckieLoginIndex = CR . $this->ckieLoginIndex; //  CR is used for multiple portals
	}
	
	public function isLoggedIn () {
		$token = $this->getToken();
		if ($token != NULL) {
			$query = 'SELECT user.*, user_session.user_session_id, user_session.token, user_session.expires
					  FROM user
					  	INNER JOIN user_session ON user_session.user_id = user.user_id
					  WHERE user_session.token = :token
						AND logged_out IS NULL
						';
			$values = array(':token' => $token);
			$user = $GLOBALS['mysql']->getSingle($query, $values);
			if (!empty($user)) {
				return $user;
			}
		}
		
		return false;
	}
	
	public function isTimeOut ($user) {
		return TIME > strtotime($user['expires']) ? true : false;	
	}
	
	public function isLockedOut ($user) {
		if (strtotime($user['lockout_end']) > TIME) {
			return true;
		}
		
		return false;
	}
	
	public function isActive ($user) {
		return $user['active'] == '1' ? true : false;	
	}
	
	public function newSession ($user, $expireTime, $authenMethod = 'Login') {
		session_regenerate_id(true); //reduce session hijacking dmg
		
		//determine if we should use current token
		$loggedInUser = $this->isLoggedIn();
		if (notEmptyArray($loggedInUser)) {
			if ($loggedInUser['user_id'] == $user['user_id']) {
				$token = $this->getToken();
			}
		}
		
		if (!isset($token)) {
			$token = $this->generateToken($user['user_id']);
			$this->setToken($token);	
		}
		
		//client info
		$clientInfo = http_build_query(array('http_host' => $_SERVER['HTTP_HOST']
											, 'http_user_agent' => $_SERVER['HTTP_USER_AGENT']
											)
									   );
		
		//insert new session
		$values = array('token' => $token
						, 'expires' => date('Y-m-d H:i:s', TIME + $expireTime)
						, 'user_id' => $user['user_id']
						, 'authentication_method' => $authenMethod
						, 'client_information' => $clientInfo
						);
		$GLOBALS['mysql']->insert('user_session', $values);
		
		return $GLOBALS['mysql']->lastInsertId();
	}
	
	public function renewSession ($user, $length) {
		$values = array('expires' => date('Y-m-d H:i:s', TIME + $length));
		$wherestr = 'user_session_id = :user_session_id';
		$wherevals = array(':user_session_id' => $user['user_session_id']);
		$GLOBALS['mysql']->update('user_session', $values, $wherestr, $wherevals);
		
		return true;
	}
		
	//$cookieType[0:none, 1:loginname, 2:password
	//$errors: 0 - incorrect login, 1 - login deactivated, 2 - locked out
	public function authenticate ($login, $password) {
	
		if (empty($login) || empty($password)) {
			error('$login or $password is empty'); 
		}
		
		$hashPassword = $this->hashPassword($login, $password);
		$this->clean($login);
		
		$query = 'SELECT * FROM user WHERE login = :login AND password = :password';
		$values = array(':login' => $login
						, ':password' => $hashPassword
						);
		
		$user = $GLOBALS['mysql']->getSingle($query, $values);
		
		if (empty($user)) {
			return false;
		}
		
		return $user;
	}
	
	public function authenticateWithCookie () {
		$login = $this->getCookieLogin();
		$password = $this->getCookiePassword();
		if (!empty($login) && !empty($password)) {
			return $this->authenticate($login, $password);
		}
		return false;
	}
	
	public function create ($login, $password) {
		
		$this->clean($login);

		if (empty($login) || empty($password)) {
			error('function $login->create() :: $login or $password is empty');
		}
		
		$hashPassword = $this->hashPassword($login, $password);
		$values = array('login' => $login
						, 'password' => $hashPassword
						);
		
		$GLOBALS['mysql']->insert('user', $values);
		
		return $GLOBALS['mysql']->lastInsertId();
	}
	
	public function getToken () {
		if (empty($_SESSION[CR][$this->sessionTokenIndex])) {
			return NULL;
		}
		return $_SESSION[CR][$this->sessionTokenIndex]; //CR is added to allow multiple portals
	}
	
	private function unsetToken () {
		unset($_SESSION[CR][$this->sessionTokenIndex]);	
	}
	
	private function setToken ($token) {
		$_SESSION[CR][$this->sessionTokenIndex] = $token;
	}
	
	//generates a unique token based on a random hasing algorithm, user_id, time, rand(0, 1000000)
	private function generateToken ($userid) {
		
		$rand = rand(0, 1000000); //so token changes, reduce hijacking dmg

		if ($rand % 2 == 0 && defined('hash')) {
			$hashAlgms = hash_algos();
			$randomAlgm = array_rand($hashAlgms);
			$token = hash($randomAlgm, CR . $userid . $rand . TIME);
		}
		else if ($rand % 3 == 0) {
			$token = sha1(CR . $userid . $rand . TIME);
		}
		else {
			$token = md5(CR . $userid . $rand . TIME);	
		}
		
		return $token;	
	}
	
	public function getCookieLogin () {
		if (!isset($_COOKIE[$this->ckieLoginIndex])) {
			return false;	
		}
		return decrypt($_COOKIE[$this->ckieLoginIndex]);
	}
	
	private function getCookiePassword () {
		if (!isset($_COOKIE[$this->ckiePassIndex])) {
			return false;	
		}
		return decrypt($_COOKIE[$this->ckiePassIndex]);
	}
	
	private function hashPassword ($login, $password) {
		return md5($login . $password);
	}
	
	private function saveCookieLogin ($login, $length) {
		cookie($this->ckieLoginIndex, encrypt($login), $length);
	}
	
	private function saveCookiePassword ($hashedPassword, $length) {
		$encryptedPassword = encrypt($hashedPassword);
		cookie($this->ckiePassIndex, $encryptedPassword, $length);
	}
	
	public function updateFailedLogin ($login, $length) {
		$this->clean($login);
		
		//find user with $login
		$query = 'SELECT user_id, num_failed_logins, last_failed_login FROM user WHERE login = :login';
		$values = array(':login' => $login);
		$user = $GLOBALS['mysql']->getSingle($query, $values);
		if (empty($user)) {
			return false;
		}
		
		$isRecent = (TIME - $length) < strtotime($user['last_failed_login']) ? true : false;
		if ($isRecent) {
			$user['num_failed_logins'] += 1;
		}
		else {
			$user['last_failed_login'] = 0;
		}
		
		//update
		$values = array('last_failed_login' => DATETIME
						, 'num_failed_logins' => $user['num_failed_logins']
						);
		$wherestr = 'user_id = :user_id';
		$wherevals = array(':user_id' => $user['user_id']);
		$GLOBALS['mysql']->update('user', $values, $wherestr, $wherevals);
		
		return $user;
	}
	
	public function lockout ($user, $length) {
		$values = array('lockout_end' => date('Y-m-d H:i:s', TIME + $length)
						);
		$wherestr = 'user_id = :user_id';
		$wherevals = array(':user_id' => $user['user_id']);
		$GLOBALS['mysql']->update('user', $values, $wherestr, $wherevals);
		return true;
	}
	
	private function clean (&$str) {
		$str = strtolower($str);
		$str = trim($str);
	}
	
	public function resetFails ($user) {
		$values = array('last_failed_login' => NULL
						, 'num_failed_logins' => NULL
						, 'lockout_end' => NULL
						);
		$wherestr = 'user_id = :user_id';
		$wherevals = array(':user_id' => $user['user_id']);
		$GLOBALS['mysql']->update('user', $values, $wherestr, $wherevals);	
	}
	
	public function logout ($user) {
		$this->unsetToken(); //reduce damange from hijacking
		session_regenerate_id(true); //reduce damange from hijacking
		
		$values = array('logged_out' => '1');
		$wherestr = 'user_session_id = :user_session_id';
		$wherevals = array(':user_session_id' => $user['user_session_id']);
		$GLOBALS['mysql']->update('user_session', $values, $wherestr, $wherevals); //reduce damange from hijacking
		
		return true;
	}
	
	//$check can be eaither login or username
	public function exists ($needle, $clean = false) {

		if (empty($needle)) {
			return false;	
		}
		
		if (!$clean) {
			$this->clean($needle);
		}
		
		$query = 'SELECT user_id FROM user WHERE login = :login';
		$values = array(':login' => $needle);
		
		$user = $GLOBALS['mysql']->getSingle($query, $values);
		
		return !empty($user);
	}
	
	public function resetPass ($user) {
		//create random password
		$rand = md5(rand(100, 1000000) . TIME);
		$tempPass = '';
		for($i = 0; $i < 8; $i++) {
			$tempPass .= $rand[$i];
		}
		
		$values = array('password' => md5($user['login'] . $tempPass));
		$wherestr = 'user_id = :user_id';
		$wherevals = array(':user_id' => $user['user_id']);
		$GLOBALS['mysql']->update('user', $values, $wherestr, $wherevals);
		
		return $tempPass;
	}
	
	public function updatePass ($userid, $newPass, $oldPass = NULL, $newLogin = NULL) {
		
		$query = 'SELECT login, password FROM user WHERE user_id = :user_id';
		$values = array(':user_id' => $userid);
		$user = $GLOBALS['mysql']->getSingle($query, $values);
		if (empty($user)) {
			return false;
		}
		
		$login = $user['login'];
		
		//handle cleaning
		$this->clean($login);
		
		if ($newLogin != NULL) {
			$this->clean($newLogin);	
		}
		
		if ($oldPass != NULL) { 
			$hashPassword = $this->hashPassword($login, $oldPass);
			//verify old password
			if ($hashPassword != $user['password']) {
				return false;
			}
		}
		
		$values = array();
		
		if ($newLogin != NULL && $newLogin != $login) { //new login is diferent, need to check for dups
			$exists = $this->exists($newLogin, false);
			if ($exists) {
				return false;	
			}
			$values['login'] = $newLogin;
			$values['password'] = $this->hashPassword($newLogin, $newPass);
		}
		else {
			$values['password'] = $this->hashPassword($login, $newPass);
		}
		
		$wherestr = 'user_id = :user_id';
		$wherevals = array(':user_id' => $userid);
		$GLOBALS['mysql']->update('user', $values, $wherestr, $wherevals);
		
		return true;
	}
	
	
	
	public function verifyOldPass ($login, $oldPass) {
		
		$this->clean($login);
		
		$hashedPassword = $this->hashPassword($login, $oldPass);
		
		$query = 'SELECT user_id FROM user WHERE login = :login AND password = :password';
		$values = array(':login' => $login
						, ':password' => $hashedPassword
						);
		$user = $GLOBALS['mysql']->getSingle($query, $values);
		
		if (empty($user)) {
			return false;
		}
		
		return true;
	}
	
	
}
?>