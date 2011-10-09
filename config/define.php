<?php
//1:59 PM 5/17/2010
define('SITENAME', 'Orchard Ecommerce');

// For outgoing emails
define('FROM', html_entity_decode(SITENAME)); // ie DizzyStop&reg;
define('FROMEMAIL', 'no-reply@no-reply.com');

define('MYSQLDB', 'staging');
define('MYSQLUSER', 'staging');
define('MYSQLPASS', 'emgeckxemg');
define('MYSQLHOST', 'localhost');
define('DEFAULTPAGE', '/home.php');

define('CR', '');
define('DR', rtrim($_SERVER['DOCUMENT_ROOT'], '/') . CR);

putenv('TZ=America/Los_Angeles');
define('TIME', time());
define('DATETIME', date('Y-m-d H:i:s', TIME));
define('DATE', substr(DATETIME, 0, 10));
define('PROTECTEDPATHS', '/customer, /camper');

define('DEVELOPMENT', true);

define('LIBPATH', '/php/lib');

//user login stuff
define('SESSIONLENGTH', 6000); //user login session length, in seconds
define('LOCKOUTLENGTH', 600); //user login locout length, in seconds
define('MAXFAILEDATTEMPTS', 5); //
define('FAILEDATTEMPTLENGTH', 600); //user login login attempth length, in seconds
define('PORTAL', 'Public');
?>