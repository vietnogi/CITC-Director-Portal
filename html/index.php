<?
if(isAjax()){
	require DR . '/html-parts/user-msg.php';
	$htmlFile = '/html' . $this->p;
	if (file_exists(DR . $htmlFile)) {
		require DR . $htmlFile;
	}
	else if ($this->cms != NULL) {
		echo $this->cms['body'];
	}
	else{
		trigger_error('No HTML available.', E_USER_WARNING);
	}
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?
require DR . '/html-parts/head.php';
?>
</head>
<body id="top" class="<?= trim(urlToClassStr($GLOBALS['bc']->uri . '/' . $this->p) . (!empty($this->gd['body_classes']) ? ' ' . implode(' ', $this->gd['body_classes']) : '')) ?>">
<?
require DR . '/html-parts/body.php';
?>
</body>
</html>