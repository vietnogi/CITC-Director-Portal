<?
class FW {
	private $isAction = false;
	private $isAjax = false;
	private $isContent = false;
	private $p = NULL;
	private $meta = array(
		'title' => NULL
		, 'h2' => NULL
		, 'description' => NULL
		, 'keywords' => NULL
	);
	// global data (for multiple pages)
	private $gd = array();
	// local data (for single page)
	private $ld = array();
	
	private $systemVars = array(
		'p' => NULL
		, 'index' => NULL
		, 'print' => NULL
		, 'debug' => NULL
		, 't' => NULL
		, 'client' => NULL
	);
	private $redirect = NULL;
	private $debug = false;
	private $user = array();
	
	public function __construct () {
		// since .htaccess add variables like p, index, print
		$this->handleSystemVars();
		
		$this->handleClient();
		
		session_start();
		
		// handle debug flag
		if (DEVELOPMENT === true) {
			if (!isset($_SESSION[CR]['debug'])) {
				// set default value
				$_SESSION[CR]['debug'] = false; 	
			}
			if ($this->systemVars['debug'] !== NULL) {
				$_SESSION[CR]['debug'] = $this->systemVars['debug'];
			}
			$this->debug = isset($_SESSION[CR]['debug']) ? $_SESSION[CR]['debug'] : $this->debug;
		}
		// determine if request isAction, isAjax, or isContent
		$this->determinePageType();
		// declare common classes, ie. mysql, login, permission, bc, etc/, they will be in the $GLOBALS scope
		$this->handleClasses();
		
		// check if request is protected
		$protected = $this->isProtected();
		if ($protected) {
			// check if user is logged in, set defines
			$this->handleLogin();
			// check if user has permission to access current uri
			$requiredPermission = $this->handlePermission();
		}
		else {
			define('LOGGEDIN', false);	
		}
		
		if (!$this->handleFile()) {
			$this->handle404();
		}
		
		$this->handleGlobalData();
		
		if ($this->debug) {
			?>
			<style type="text/css">
				.debug-container {
					background: #fff;
					color: #333;
					font: 11px/15px Arial, Helvetica, sans-serif;
					overflow: hidden;
					padding: 16px 0 0;
				}
				.debug-container > .inner {
					width: 960px;
					margin: 0 auto;
				}
				.debug-container h1 {
					margin: 0 0 16px;
					font: bold 24px/1em Arial, Helvetica, sans-serif;
				}
				.debug-container dl {
					margin: 0;
				}
				.debug-container dt {
					font: bold 14px/1em Arial, Helvetica, sans-serif;
				}
				.debug-container dd {
					margin: 4px 0 16px;
					padding: 4px 8px;
					max-height: 200px;
					overflow-y: scroll;
					border: 1px solid #ddd;
				}
				.debug-container dd.textarea {
					max-height: none;
					overflow: auto;
					padding: 0;
					border: none;
				}
				.debug-container dd.textarea textarea {
					width: 100%;
					height: 640px;
				}
				.debug-container ul {
					margin: 0;
					padding: 0 0 0 16px;
					list-style: disc;
				}
			</style>
			<div class="debug-container">
				<div class="inner">
					<h1>Request: <?= $this->p ?>?<?= $_SERVER['QUERY_STRING'] ?> (<?= $protected ? 'protected' : 'unprotected' ?>)</h1>
					<dl>
						<?
						$debugData = array(
							'Global' => $this->gd
							, 'POST' => $_POST
							, 'GET' => $_GET
						);
						if ($this->debug == '2') {
							$debugData = array_merge($debugData, array(
								'Login' => $this->user
								, 'SESSION' => $_SESSION
								, 'COOKIE' => $_COOKIE
								, 'SERVER' => $_SERVER
							));
						}
						foreach ($debugData as $type => $data) {
							?>
							<dt><?= $type ?> Data</dt>
							<dd>
								<?
								pr($data);
								?>
							</dd>
							<?
						}
						?>
					</dl>
				</div>
			</div>
			<?
		}
		
		if (!$this->isAction) {
			$this->handleLocalData();
			
			if ($this->debug) {
				?>
				<div class="debug-container">
					<div class="inner">
						<dl>
							<dt>Local Data</dt>
							<dd>
								<?
								pr($this->ld);
								?>
							</dd>
						</dl>
					</div>
				</div>
				<?
			}
		}
		else {
			$this->handleAction();
		}
		
		if (isset($this->classes['mysql'])) {
			$this->classes['mysql']->close();
			unset($_mysql);	
		}
		
		if (!$this->isAction) {
			$this->automateMeta();
			
			if ($this->debug) {
				ob_start();	
			}
			
			$this->handleHTML();
			
			if ($this->debug) {
				$markup = htmlentities(ob_get_flush());
				?>
				<div class="debug-container">
					<div class="inner">
						<dl>
							<dt>Markup</dt>
							<dd class="textarea">
								<textarea rows="" cols=""><?= $markup ?></textarea>
							</dd>
						</dl>
					</div>
				</div>
				<?
			}
		}
	}
	
	private function handleClient () {
		// handle getting client define file, added the '.' to prevent hacks
		$clientDefined = false;
		if (!empty($this->systemVars['client']) && strpos($this->systemVars['client'], '.') === false) {
			$path = '/config/' . $this->systemVars['client'] . '.php';
			if (file_exists(DR . $path)) {
				require DR . $path;
				$clientDefined  = true;
				define('CR', '/' . $this->systemVars['client']);
			}
			unset($this->systemVars['client']);
		}
		if (!$clientDefined) {
			header('HTTP/1.1 404 Not Found');
			exit;
		}
	}
	
	private function handleHTML () {
		$meta = '/data/meta.php';
		if (file_exists(DR . $meta)) {
			require DR . $meta;
		}
		
		require DR . '/html/index.php';	
	}
	
	private function envCheck () {
		$error = false;
		if (get_magic_quotes_gpc()) {
			$error = 'Magic quotes must be off.';
		}
		
		if ($error) {
			die($error);	
		}
	}
	
	private function automateMeta () {
		if ($this->meta['title'] == NULL) {
			$this->title = $this->generatePathTitle();
		}
		if ($this->meta['h2'] == NULL) {
			$this->meta['h2'] = $this->title;
		}
	}							
	
	private function determinePageType () {
		if ($this->systemVars['index'] !== NULL) {
			$this->isAjax = $this->systemVars['index'] == 'ajax' ? true : false;
			$this->isAction = $this->systemVars['index'] == 'action' ? true : false;
		}
		$this->isContent = !$this->isAjax && !$this->isAction ? true : false;	
	}
	
	private function generatePathTitle ($delimiter = '&rarr;') {
		
		$skipPages = array(
			'main'
			, 'manage-links'
		);
		$crumbCount = count($GLOBALS['bc']->crumbs);
		$crumbs = array();
		for ($i = 0; $i < $crumbCount; $i++) {
			if (!in_array($GLOBALS['bc']->crumbs[$i], $skipPages)) {
				$crumb = !empty($this->gd['cleanNameMap'][$GLOBALS['bc']->crumbs[$i]]) ? $this->gd['cleanNameMap'][$GLOBALS['bc']->crumbs[$i]] : uncleanUrl($GLOBALS['bc']->crumbs[$i]);
				if (isset($tips[$i])) {
					$crumb .= ' <span class="name">(' . html_entity_decode($tips[$i]) . ')</span>';	
				}
				array_push($crumbs, $crumb);
			}
		}
		return implode(' <span class="delimiter">' . $delimiter . '</span> ', $crumbs);
	}
	
	//need to remove system variables from enviroment
	private function handleSystemVars () {
		
		$names = array_keys($this->systemVars);
		
		//save values and remove from get
		foreach ($names as $name) {
			if (!isset($_GET[$name])) {
				continue;	
			}
			$this->systemVars[$name] = $_GET[$name];
			unset($_GET[$name]);
		}
		
		//remove from $_SERVER['QUERY_STRING']
		$queryVariables = array();
		parse_str($_SERVER['QUERY_STRING'], $queryVariables);
		$queryVariables = array_diff_key($queryVariables, array_flip($names));
		$_SERVER['QUERY_STRING'] = http_build_query($queryVariables); // Generates a URL-encoded query string from the associative array
	}
	
	//create global "singleton" objects, more then likly we only need one instance of each class throughout framework.
	private function handleClasses () {
		$GLOBALS['mysql'] = new mysql();
		$GLOBALS['login'] = new login();
		$GLOBALS['permission'] = new Permission();
		$GLOBALS['bc'] = new Breadcrumbs();
		$GLOBALS['dates'] = new Dates();
		$GLOBALS['validate'] = new Validate();
	}
	
	private function handleFile () {
		$this->p = '';
		if ($this->isAction) {
			$this->p .= '/action';
		}
		
		if (empty($this->systemVars['p'])) { //no targeted file, use default
			$this->p .= config('default page');
		}
		else {
			$this->p .= '/' . $this->systemVars['p'] . '.php';
		}
		
		// check if nessary files exist
		if ($this->isAction) {
			if (!file_exists(DR . $this->p)) {
				// missing action file
				return false;
			}
		}
		else {
			if (!file_exists(DR . '/html' . $this->p)) {
				// missing html file for content
				return false;
			}
		}
		
		return true;
	}
	
	private function handleLogin () {
		$this->user = $GLOBALS['login']->isLoggedIn();
		if (notEmptyArray($this->user)) { //session is available
			//handle passive cross site fradulent request hack
			if ($this->systemVars['t'] != $this->user['token']) {
				logError('Session token does not match query token (t)');
			}
			if ($GLOBALS['login']->isActive($this->user)) { //account is active
				if ($GLOBALS['login']->isTimeOut($this->user)) { //session has timed out
					$GLOBALS['login']->logout($this->user);
					$_SESSION[CR]['user-error'] = 'You have been logged out because your session has expired.';
				}
				else{ //session has not timed out yet
					$GLOBALS['login']->renewSession($this->user, config('session length'));
					define('LOGGEDIN', true);
					define('USERID', $this->user['user_id']);
				}
			}
			else{ //account is not active
				$GLOBALS['login']->logout($this->user);
				$_SESSION[CR]['user-error'] = 'You have been logged out because your account has been deactivated.';	
			}
		}
		
		if (!defined('LOGGEDIN')) {
			define('LOGGEDIN', false);
		}
	}
	
	private function isProtected () {
		//determine if current path is proteced
		return $GLOBALS['permission']->isPathProtected($GLOBALS['bc']->uri);
	}
	
	private function handlePermission () {
		
		//get permissions
		$permissions = array();	
		if (defined('USERID')) {
			$permissions = $GLOBALS['permission']->getPermissions(USERID);
		}
		
		//check if uri is accessible
		$canAccess = $GLOBALS['permission']->canRead($GLOBALS['bc']->uri, $permissions);
		
		if (!$canAccess) {
			$_SESSION[CR]['user-error'] = 'Please login to continue.';
			died('/nologin/login', $this->isAjax);
		}
		
		return true;
	}
	
	private function handleGlobalData () {
		$globalFile = '/data/global.php';
		if (file_exists(DR . $globalFile)) {
			require DR . $globalFile;
		}	
	}
	
	private function handleLocalData () {
		$file = '/data' . $this->p;
		if (file_exists(DR . $file)) {
			require DR . $file;
		}
	}
	
	private function handleAction () {
		 // redirect url
		$this->redirect = false;
		
		if (!file_exists(DR . $this->p)) {
			logError('Page can not be found');
		}
		
		// include action file
		require DR . $this->p;
		
		if ($this->redirect) {
			$redirectUrl = $this->url($this->redirect);
			if ($this->debug) {
				?>
				<a href="<?= $redirectUrl ?>"><?= $redirectUrl ?></a>
				<?
			}
			else {
				died($redirectUrl, false, false, $this->isAjax);
			}
		}
	}
	
	private function handle404 () {
		logError('page not found', false, '/404.txt');
		$this->p = '/nologin/404.php';
	}
	
	private function url ($url) {
		// handle token/t
		if (!empty($this->systemVars['t'])) {
			$url .= (strpos($url, '?') === false) ? '?' : '&';
			$url .= 't=' . $this->systemVars['t'];
		}
		return CR . $url; 
	}
	
	private function actionUrl () {
        return $this->url('/action' . $GLOBALS['bc']->path . '/' . $GLOBALS['bc']->page);	
	}
}

?>