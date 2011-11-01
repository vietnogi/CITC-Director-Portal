<?
$this->gd['navs'] = array(
	'Overview' => array(
		'Dashboard' => '/dashboard/overview'
	)
	, 'Parents' => array(
		'Parents' => '/parents/main'
		, 'Invoices' => '/invoices/main'
		, 'Payments' => '/payments/main'
	)
	, 'Campers' => array(
		'Campers' => '/campers/main'
		, 'Enrollments' => '/enrollments/main'
		, 'Cabins' => '/cabins/main'
	)
	, 'Parent Pack' => array(
		'Parent Packs' => '/parent-packs/main'
		, 'Forms' => '/parent-pack-forms/main'
		, 'Transportation Locations' => '/transportation-locations/main'
	)
	, 'Promotions' => array(
		'Early Bird Discounts' => '/early-bird-discounts/main'
		, 'Mult. Session Discounts' => '/multiple-session-discounts/main'
		, 'Sibling Discounts' => '/sibling-discounts/main'
		, 'Coupon Codes' => '/coupon-codes/main'
	)
	/*, 'Accounts' => array(
		'Staff' => '/staff/main' 
		, 'Parents' => '/parents/main'
		, 'Campers' => '/campers/main'
	)
	, 'Contacts' => array(
		'Leads' => '/leads/main'
		, 'Clients' => '/clients/main'
	)*/
	, 'Staff' => array(
		'Staff' => '/staff/main'
		, 'Staff Applications <span class="count">99</span>' => '/staff-applications/main'
	)
	, 'Reports' => array(
		'Enrollment Report' => ''
		, 'Sales Report' => ''
	)
	, 'Camp Sessions' => array(
		'Sessions' => '/sessions/main'
		, 'Session Categories' => '/session-categories/main'
		, 'Specialties' => '/specialties/main'
		, 'Specialty Categories' => '/specialty-categories/main'
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
$this->gd['num_nav_parents'] = count($this->gd['navs']);
$this->gd['num_nav_children'] = count($this->gd['navs'], COUNT_RECURSIVE) - $this->gd['num_nav_parents'];

// comment
if ($GLOBALS['bc']->path == '/comments') {
	$this->gd['comment_for'] = newInput('for', $_GET, 'min 1');
	$sections = array('parent', 'camper');
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