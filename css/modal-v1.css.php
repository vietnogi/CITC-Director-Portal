<?
$modalOptions = array(
	'border' => array(
		'color' => '000'
		, 'width' => 2
	)
);
?>
/*<style>*/
.modal-container {
	position: fixed;
	width: 100%;
	height: 100%;
	top: 0;
	left: 0;
}
.modal-blinds {
	position: fixed;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	width: 100%;
	height: 100%;
	background: #000;
	filter: alpha(opacity=80);
	opacity: 0.8;
}

.modal-popUp {
	display: table;
	margin: 32px auto;
	position: relative;
	border: <?= $modalOptions['border']['width'] ?>px solid #<?= $modalOptions['border']['color'] ?>;
	background: #fff;
}
.modal-content {
	padding: 16px;
}

a.modal-close {
	cursor: pointer;
	position: absolute;
	z-index: 0;
	top: <?= -16 - $modalOptions['border']['width'] ?>px;
	right: 0;
	text-indent: -2000em;
	width: 16px;
	height: 16px;
	background: url("../images/library/modal-x.png") no-repeat 0 0;
	margin: 0;
}
a.modal-close:hover {
	background-position: 0 -16px;
}