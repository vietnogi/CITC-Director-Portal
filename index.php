<?
// universal defines
define('DR', rtrim($_SERVER['DOCUMENT_ROOT'], '/'));
define('DEVELOPMENT', true);
define('LIBPATH', '/php/lib');
define('PORTAL', 'Administration');
define('USID', '1');
define('CUSTOMERGROUP', '2');

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
