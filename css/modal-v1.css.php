/*<style>*/
.modal-popUp{
	position: absolute;
	border: 2px solid #111;
	padding: 0;
	background: #fff;
}
/*.modal-popUp.add .modal-body,
.modal-popUp.edit .modal-body,
.modal-popUp.configuration .modal-body {
	min-width: 640px;
}*/

.modal-content{
	clear: both;
	overflow: auto;
	overflow-x: auto;
	overflow-y: auto;
}

.modal-body {
	float: left;
	padding: 0 16px 16px;
}
.no-padding .modal-body {
	padding: 0;
}

.modal-load{
	width: 200px;
	height: 50px;
	position: absolute;
	color: #fff;
	font-weight: bold;
	font-size: 14px;
}

.modal-close {
	float: right;
	height: 18px;
}
.modal-close br {
	clear: both;
}

.modal-close a.close {
	float: left;
	width: 16px;
	height: 16px;
	background: url("../images/library/modal-x.png") no-repeat 0 0;
	margin: 0;
}
.modal-close a.close:hover {
	background-position: 0 -16px;
}
<?
/*
,
.modal-close a.expand

.modal-close a.expand {
	background: url("../images/library/expand.gif") no-repeat 0 0;
}
.modal-close a.shrink {
	display: none;
	float: right;
	width: 16px;
	height: 16px;
	cursor: pointer;
	background: url(../images/library/x.gif);
	margin: 0;
	border: none;
}
*/
?>

#modal-blinds {
	display: none;
	position: absolute;
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

/*.modal-load {
	position: absolute;
	width: 200px;
	height: 50px;
	top: 50%;
	left: 50%;
	margin: -25px 0 0 -100px;
}

.modal-content {
	position: absolute;
	border: 2px solid #1a1a1a;
	background: #fff;
	padding: 16px;
}

.modal-content > a.close {
	display: block;
	text-indent: -2000em;
	width: 16px;
	height: 16px;
	background: url("../images/library/modal-x.png") no-repeat 0 0;
}
.modal-content > a.close:hover {
	background-position: 0 -16px;
}*/