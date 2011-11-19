/* <style> */
/* Fixes issue here http://code.google.com/p/jcrop/issues/detail?id=1 */
.jcrop-holder { text-align: left; }

.jcrop-vline, .jcrop-hline
{
	font-size: 0px;
	position: absolute;
	background: white url('../images/jcrop.gif') top left repeat;
}
.jcrop-vline { height: 100%; width: 1px !important; }
.jcrop-hline { width: 100%; height: 1px !important; }
.jcrop-vline.right { right: 0px; }
.jcrop-hline.bottom { bottom: 0px; }
.jcrop-handle {
	font-size: 1px;
	width: 7px !important;
	height: 7px !important;
	border: 1px #eee solid;
	background-color: #333;
}

.jcrop-tracker { width: 100%; height: 100%; }

.custom .jcrop-vline,
.custom .jcrop-hline
{
	background: yellow;
}
.custom .jcrop-handle
{
	border-color: black;
	background-color: #C7BB00;
	-moz-border-radius: 3px;
	-webkit-border-radius: 3px;
}
