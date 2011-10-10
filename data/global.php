<?
$this->gd['navs'] = array(
	'Overview' => array(
		'Dashboard' => '/dashboard/overview'
		, 'Reports' => '/reports/menu'
	)
	, 'Projects' => array(
		'Projects' => '/projects/main' 
		, 'Hours' => '/hours/main'
	)
	, 'Contacts' => array(
		'Leads' => '/leads/main'
		, 'Clients' => '/clients/main'
	)
	, 'Settings' => array(
		'Users' => '/users/main'
		, 'User Groups' => '/user-groups/main'
		, 'User Log' => '/user-log/main'
		, 'Permissions' => '/permissions/main'
		, 'Configuration' => '/configuration/menu'
	)
);

if (LOGGEDIN === true) {
	// get staff info
	$query = 'SELECT * FROM staff WHERE user_id = :user_id';
	$values = array(':user_id' => USERID);
	$this->gd['staff'] = $GLOBALS['mysql']->getSingle($query, $values);
	if (empty($this->gd['staff'])) {
		logError('Staff is empty.');
	}
}
?>