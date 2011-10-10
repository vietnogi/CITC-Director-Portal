<?
define('DR', rtrim($_SERVER['DOCUMENT_ROOT'], '/'));

require DR . '/php/minify.php';
require DR . '/php/lib/breadcrumbs.php';
$minify = new Minify(array('global', 'main'), array('ie', 'ie6', 'ie7', 'phpbb', 'wordpress', 'print'));
?>