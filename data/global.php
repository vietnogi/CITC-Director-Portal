<?
$this->gd['navs'] = array(
	'Overview' => array(
		'Dashboard' => '/dashboard/overview'
		, 'Reports' => '/reports/menu'
	)
	, 'Accounts' => array(
		'Staff' => '/staff/main' 
		, 'Customers' => '/customers/main'
		, 'Campers' => '/campers/main'
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
foreach ($this->gd['navs'] as $category => &$links) {
	foreach ($links as &$link) {
		$link = $this->url($link);	
	}
}

// comment
if ($GLOBALS['bc']->path == '/comments') {
	$this->gd['comment_for'] = newInput('for', $_GET, 'min 1');
	$sections = array('customer', 'camper');
	if (!in_array($this->gd['comment_for'], $sections)) {
		logError('comment feature is not available for: ' . $inputs['for']);	
	}
}

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