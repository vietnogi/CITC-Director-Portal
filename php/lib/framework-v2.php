<?
//2:15 PM 7/15/2011
class FW {
	public $isAction = false;
	public $isAjax = false;
	public $isContent = false;
	public $p = NULL;
	public $file = NULL;
	public $cleanFile = NULL;
	public $cleanUri = NULL;
	public $meta = array('title' => NULL
						 , 'h2' => NULL
						 , 'description' => NULL
						 , 'keywords' => NULL
						 );
	public $gd = array();
	public $data = array();
	public $cms = NULL;
	
	private $queryNames = array('p', 'index', 'print');
	private $redirect = NULL;
	
	public function __construct () {
		$this->determinePageType(); //determine if request isAction, isAjax, or isContent
		$this->handleClasses(); //declare common classes, ie. mysql, login, permission, bc, etc/
		$this->rebuildQueryString(); //since .htaccess add variables like p, index, print, we rebuild $_SERVER['QUERY_STRING'] so we may use it as expected

		if ((isset($GLOBALS['login']) && isset($GLOBALS['permission']))) {
			$this->handleLogin(); //check if user is logged in, set defines
			$this->handlePermission(); //check if user has permission to access current uri
		}
		
		$this->handleFile(); //determine file to use
		
		if (!$this->isAction) {
			if (empty($this->p)) {
				$this->handleCMS(); //needs to be before handle 404 because handle404 check if CMS is available
			}
			$this->handle404(); //should be before handle data so 404 data file can be handled
			$this->handleData();
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
			$this->handleHTML();
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
		if (!empty($_GET['index'])) {
			$this->isAjax = $_GET['index'] == 'ajax' ? true : false;
			$this->isAction = $_GET['index'] == 'action' ? true : false;
		}
		$this->isContent = !$this->isAjax && !$this->isAction ? true : false;	
	}
	
	private function generatePathTitle ($delimiter = '&rarr;') {
		
		if (!empty($_GET['crumb-tips'])) {
			$tips = explode('::', $_GET['crumb-tips']);	
		}
		
		$skipPages = array('main'
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
	
	//rebuild $_SERVER['QUERY_STRING'] to remove framework get variables
	private function rebuildQueryString () {
		//remove framework variables, variables are not for query, but rather flags for display, so it wouldnt make sense to be in the $_SERVER['QUERY_STRING']
		parse_str($_SERVER['QUERY_STRING'], $queryVariables);
		$queryVariables = array_diff_key($queryVariables, array_flip($this->queryNames));
		$_SERVER['QUERY_STRING'] = http_build_query($queryVariables); // Generates a URL-encoded query string from the associative array
		unset($queryVariables);	
	}
	
	//create global "singleton" objects, more then likly we only need one instance of each class throughout framework.
	private function handleClasses () {
		if (class_exists('mysql')) {
			$GLOBALS['mysql'] = new mysql();
			if (class_exists('login')) {
				$GLOBALS['login'] = new login();
				if (class_exists('permission')) {
					$GLOBALS['permission'] = new Permission();
				}
			}
		}
		if (class_exists('Breadcrumbs')) {
			$GLOBALS['bc'] = new Breadcrumbs();
		}
		if (class_exists('Dates')) {
			$GLOBALS['dates'] = new Dates();
		}
		if (class_exists('Validate')) {
			$GLOBALS['validate'] = new Validate();
		}
	}
	
	private function handleFile () {
		$this->p = '';
		if ($this->isAction) {
			$this->p .= '/action';
		}
		
		if (empty($_GET['p'])) { //no targeted file, use default
			$this->p .= DEFAULTPAGE;
		}
		else{
			$this->p .= '/' . $_GET['p'];
		}
		
		$parts = explode('.php', $this->p);
		$this->file = preg_replace('/\//', '', $parts[0], 1); //php filename
		$this->cleanFile = cleanUrl($this->file);
		$this->cleanUri = cleanUrl(preg_replace('/\//', '', $GLOBALS['bc']->uri, 1));	
	}
	
	private function handleLogin () {
		$user = $GLOBALS['login']->isLoggedIn();
		if (notEmptyArray($user)) { //session is available
			if ($GLOBALS['login']->isActive($user)) { //account is active
				if ($GLOBALS['login']->isTimeOut($user)) { //session has timed out
					$GLOBALS['login']->logout($user);
					$_SESSION[CR]['user-error'] = 'You have been logged out because your session has expired.';
				}
				else{ //session has not timed out yet
					$GLOBALS['login']->renewSession($user, SESSIONLENGTH);
					$this->setUserDefines($user);
					define('LOGGEDIN', true);
				}
			}
			else{ //account is not active
				$GLOBALS['login']->logout($user);
				$_SESSION[CR]['user-error'] = 'You have been logged out because your account has been deactivated.';	
			}
		}
		
		if (!defined('LOGGEDIN')) {
			define('LOGGEDIN', false);	
		}
	}
	
	private function setUserDefines ($user) {
		define('LOGIN', $user['login']);
		define('USERID', $user['user_id']);
		define('SESSIONEXPIRES', $user['expires']);
		define('TOKEN', 'lv2-token=' . $user['token']); // attach to action urls
	}
	
	private function handlePermission () {
		if (!defined('PROTECTEDPATHS')) {
			error('PROTECTEDPATHS is not defined');
		}
		
		//determine if current path is proteced
		$protectedPaths = explode(', ', PROTECTEDPATHS);
		$isPathProtected = $GLOBALS['permission']->isPathProtected($GLOBALS['bc']->uri, $protectedPaths);
		if (!$isPathProtected) {
			return;	
		}
		
		//get permissions
		$permissions = array();	
		if (defined('USERID')) {
			$permissions = $GLOBALS['permission']->getPermissions(USERID);
		}
		
		//check if uri is accessible
		$canAccess = $GLOBALS['permission']->canAccess($GLOBALS['bc']->uri, $permissions);
		
		if (!$canAccess) {
			/*
			disable feature for now, not sure if its needed plus it causes many problems.
			if ($this->isContent) {
				$_SESSION[CR]['redirect-after-login'] = $_SERVER['REQUEST_URI'];
			}
			*/
			$_SESSION[CR]['user-error'] = 'Please login to continue.';
			died('/login', $this->isAjax);
		}
	}
	
	private function handleData () {
		//include globals
		$globalFile = '/data/global.php';
		if (file_exists(DR . $globalFile)) {
			require DR . $globalFile;
		}
		
		$file = '/data' . $this->p;
		if (file_exists(DR . $file)) {
			require DR . $file;
		}
	}
	
	private function handleCMS () {
		$file = '/data/cms.php';
		if (file_exists(DR . $file)) {
			require DR . $file;
		}
	}
	
	private function handleAction () {

		$this->redirect = false; // redirect url
		
		//handle passive cross site fradulent request
		/*if (LOGGEDIN && isset($GLOBALS['login'])) {
			if ($_GET['lv2-token'] != $GLOBALS['login']->getToken()) {
				logError(__FILE__ . ' ' . __LINE__ . ': Invalid token', false);
				died('/login');
			}
		}*/
		
		require DR . $this->p; // include action file
		
		if ($this->redirect) {
			$useCR = true;
			if (CR != '') { //prevent warning from strpos
				$useCR = strpos($this->redirect, CR) === false ? true : false;
			}
			died($this->redirect, isset($_POST['useiframe']) ? true : false, $useCR, $this->isAjax);
		}
	}
	
	private function handle404 () {
		if (!file_exists(DR . '/html' . $this->p) && $this->cms == NULL) {
			$this->p = '/404.php';
		}	
	}
}

?>