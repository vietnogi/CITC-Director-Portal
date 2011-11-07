<?
class Permission {
	private $permissions = array();
	private $pagePerm = '';
	private $groupPerm = array();
	private $portal = NULL;
	
	public function __construct() {
	}
	
	public function getPermissions ($userid) {
		if(!is_numeric($userid)){
			trigger_error('$userid is not numeric', E_USER_ERROR);
		}
		
		$query = 'SELECT user_permission.*
				  FROM user_permission
				  	INNER JOIN user_group_link ON user_group_link.user_group_id = user_permission.user_group_id
				  WHERE user_group_link.user_id = :user_id
				  	AND user_permission.portal = :portal';
		$values = array(
			':user_id' => $userid
			, ':portal' => PORTAL
		);
		return $GLOBALS['mysql']->get($query, $values);
	}
	
	public function isPathProtected ($uri = '', $unprotectedPaths = array('/up')) {
		// check if uri starts with any paths in $unprotectedPaths
		foreach ($unprotectedPaths as $path) {
			if (preg_match('/^' . str_replace('/', '\/', $path) . '/', $uri)) {
				return false;
			}
		}
		return true;
	}
	
	public function getPathPermission ($uri = '', $permissions = array()) {
		foreach($permissions as $permission){
			//check if current path starts with permission path
			$strStartWith = strncmp($uri, $permission['path'], strlen($permission['path'])) == 0 ? true : false;
			if($strStartWith){
				return $permission;	
			}
		}
		return array();
	}
	
	public function canRead ($uri, $permissions) {
		$permission = $this->getPathPermission($uri, $permissions);
		if (empty($permission)) {
			return false;	
		}
		if ($permission['level'] == 'No Access') {
			return false;	
		}
		return true;
	}
	
	public function canWrite ($uri, $permissions) {
		$permission = $this->getPathPermission($uri, $permissions);
		if (empty($permission)) {
			return false;	
		}
		if ($permission['level'] == 'Read/Write') {
			return true;
		}
		return false;
	}
	
}

?>