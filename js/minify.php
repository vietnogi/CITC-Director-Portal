<?
define('DR', rtrim($_SERVER['DOCUMENT_ROOT'], '/'));
require DR . '/php/minify.php';
require DR . '/php/lib/breadcrumbs.php';
$minify = new Minify(array('index'));
?>