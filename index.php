<?
// universal defines
define('DR', rtrim($_SERVER['DOCUMENT_ROOT'], '/'));
define('DEVELOPMENT', true);
define('LIBPATH', '/php/lib');
define('PORTAL', 'Administration');
define('USID', '1');

// handle getting client define file, added the '.' to prevent hacks
$clientDefined = false;
if (!empty($_GET['client']) && strpos($_GET['client'], '.') === false) {
	$path = '/config/' . $_GET['client'] . '.php';
	if (file_exists(DR . $path)) {
		require DR . $path;
		$clientDefined  = true;
		define('CR', '/' . $_GET['client']);
	}
	unset($_GET['client']);
}
if (!$clientDefined) {
	header('HTTP/1.1 404 Not Found');
	exit;
}

session_start();

//automaticaly include php files that is needed on every page
//php files should not have any executing code
function getPhpFiles ($path) {
	$handle = opendir(DR . $path);
	while (false !== ($subPath = readdir($handle))) {
		if ($subPath == '.' || $subPath == '..') {
			continue;	
		}
		$subPath = $path . '/' . $subPath;
		if (is_dir(DR . $subPath)) {
			$this->getPhpFiles($subPath);
		}
		else {
			$ext = substr($subPath, -3, 3);
			if ($ext == 'php') {
				require DR . $subPath;
			}
		}
	}
	closedir($handle);
}

getPhpFiles(LIBPATH);

$FW = new FW();
?>
