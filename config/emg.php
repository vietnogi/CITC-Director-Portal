<?php
define('SITENAME', 'EMG Adventures');

// For outgoing emails
define('FROM', html_entity_decode(SITENAME)); // ie DizzyStop&reg;
define('FROMEMAIL', 'no-reply@no-reply.com');

define('MYSQLDB', 'emg');
define('MYSQLUSER', 'emg');
define('MYSQLPASS', 'ap$i#2i');
define('MYSQLHOST', 'localhost');

putenv('TZ=America/Los_Angeles');
define('TIME', time());
define('DATETIME', date('Y-m-d H:i:s', TIME));
define('DATE', substr(DATETIME, 0, 10));

// language definitions
function l ($key) {
	switch ($key) {
		case 'camper':
			return 'camper';
		case 'campers':
			return 'campers';
		case 'season':
			return 'season';
		case 'session':
			return 'session';
		case 'specialty':
			return 'specialty';
		case 'parent pack':
			return 'parent pack';
		default:
			trigger_error('language key: ' . $key . ' was not found', E_USER_WARNING);
	}
}

// configurations
function config ($key) {
	switch ($key) {
		case 'default page':
			return '/login.php';
		case 'stay signed in length':
			return 604800;
		case 'session length':
			return 1800;
		case 'lockout length':
			return 600;
		case 'max failed attempts':
			return 5;
		case 'failed attempt length':
			return 600;
		case 'referrers':
			return true;
		case 'session':
			return 'session';
		case 'specialty':
			return 'specialty';
		case 'business name':
			return 'EMG Adventures';
		case 'business address':
			return '17155 Newhope St';
		case 'business address 2':
			return 'Ste H';
		case 'business city':
			return 'Fountain Valley';
		case 'business state abbrv':
			return 'CA';
		case 'business zip':
			return '92708';
		case 'business phone':
			return '714-881-4487';
		case 'business web url':
			return 'http://www.eckxmediagroup.com';
		case 'credit-card-transaction-test-mode':
			return true;
		default:
			trigger_error('config key: ' . $key . ' was not found', E_USER_WARNING);
	}
}
?>