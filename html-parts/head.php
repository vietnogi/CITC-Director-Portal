<?
$titlePrefix = !empty($this->meta['title']) ? strip_tags($this->meta['title']) . ' &lsaquo; ' : '';
?>
<title><?= $titlePrefix . SITENAME ?></title>
<meta name="description" content="<?= strip_tags($this->meta['description']) ?>" />
<meta name="keywords" content="<?= strip_tags($this->meta['keywords']) ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="icon" type="image/vnd.microsoft.icon" href="<?= CR ?>/favicon.ico" />
<link rel="shortcut icon" type="image/x-icon" href="<?= CR ?>/favicon.ico" />
<link rel="stylesheet" media="all" href="<?= CR ?>/css/minify.css" />
<link rel="stylesheet" media="<?= !empty($_GET['print']) ? 'all' : 'print' ?>" href="<?= CR ?>/css/print.css" />
<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />

<!--[if lte IE 8]>
<link rel="stylesheet" media="all" href="<?= CR ?>/css/ie.css" />
<![endif]-->
<!--[if lte IE 7]>
<link rel="stylesheet" media="all" href="<?= CR ?>/css/ie7.css" />
<![endif]-->
<!--[if lte IE 6]>
<link rel="stylesheet" media="all" href="<?= CR ?>/css/ie6.css" />
<![endif]-->
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
<!--
	google.load("swfobject", "2.2");
	google.load("jquery", "1");
	google.load("jqueryui", "1");
//-->
</script>
<script type="text/javascript" src="<?= CR ?>/js/minify.js"></script>
<script type="text/javascript">
<!--
	<?
	if(defined('CR')){
		?>
		FW.CR = '<?= CR ?>';
		<?
	}
	if(defined('TOKEN')){
		?>
		FW.TOKEN = '<?= TOKEN ?>';
		<?
	}
	?>
//-->
</script>