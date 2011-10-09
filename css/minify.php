<?
//10:19 AM 9/1/2010
require '../config/define.php';
require DR . '/php/minify.php';
require DR . '/php/lib/breadcrumbs.php';
$minify = new Minify(array('global', 'main'), array('ie', 'ie6', 'ie7', 'phpbb', 'wordpress', 'print'));
?>