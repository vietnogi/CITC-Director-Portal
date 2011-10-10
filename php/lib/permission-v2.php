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
	
	public function isPathProtected ($uri = '', $unprotectedPaths = array('/nologin')) {
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
	
	private function can ($case, $uri = '', $permissions = array()) {
		if (empty($case)) {
			trigger_error('$case is empty', E_USER_ERROR);
		}
		
		$permission = $this->getPathPermission($uri, $permissions);
		if(empty($permission)){
			return false;	
		}
		if($permission[$case] != '1'){
			return false;	
		}
		return true;
	}
	
	public function canAccess ($uri, $permissions) {
		return $this->can('access', $uri, $permissions);
	}
	
	public function canAdd ($uri, $permissions) {
		return $this->can('add', $uri, $permissions);
	}
	
	public function canEdit ($uri, $permissions) {
		return $this->can('edit', $uri, $permissions);
	}
	
	public function canDelete ($uri, $permissions) {
		return $this->can('delete', $uri, $permissions);
	}
}

?>