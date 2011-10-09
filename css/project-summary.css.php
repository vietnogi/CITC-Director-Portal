/*<style>*/
.summary-section {
	margin: 0 0 12px;
	border-bottom: 1px dashed #ddd;
}
.summary-section table th[scope="row"] {
	/*background: #369 url(../images/bg-header.gif) repeat-x center -50px;
	color: #fff;*/
	color: #036;
	text-align: right;
	vertical-align: top;
	padding: 6px 8px;
}

h3 a.action {
	float: left;
	width: 16px;
	height: 16px;
	background-repeat: no-repeat;
	background-position: 0 0;
	margin: 1px 6px 0 0;
}
<?
$actionIcons = array('edit' => 'pencil'
					, 'uploads' => 'page_white_get'
					, 'notes' => 'note'
					, 'hours' => 'hourglass'
					);
foreach ($actionIcons as $class => $icon) {
	?>
	h3 a.action.<?= $class ?> {
		background-image: url(../images/layout/icons/<?= $icon ?>.png);
	}
	<?
}
?>
h3 a.action span.tip {
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
h3 a.action:hover span.tip {
	margin-left: 16px;
}