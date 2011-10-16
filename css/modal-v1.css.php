/*<style>*/
.modal-popUp{
	position: absolute;
	border: 2px solid #111;
	padding: 0;
	background: #fff;
  /*
	.modal-window {
  position: absolute;
  z-index: 2;
  background: #fff;
  width: 400px;
  height: 200px;
  top: 50%;
  left: 50%;
  margin: -100px 0 0 -200px;
  */

}
/*.modal-popUp.add .modal-body,
.modal-popUp.edit .modal-body,
.modal-popUp.configuration .modal-body {
	min-width: 640px;
}*/

.modal-content{
	clear: both;
	margin: 5px;
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

.modal-blinds {
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