/*<style>*/
body
{
	font-size:1em;
}

sup,
sub {
	height: 0;
	line-height: 1;
	vertical-align: baseline;
	_vertical-align: bottom;
	position: relative;
	font-size:smaller;
	
}

sup {
	bottom: 1ex;
}

sub {
	top: .5ex;
}

em
{
	font-style: italic;
}

.fresheditable[contentEditable=true]
{
	border-left:10px solid #ccc;
	padding-left:10px;
}

.fresheditable[contentEditable=true] img
{
	border:2px dashed #ccc;
	min-width:32px;
	min-height:32px;
	padding:1em;
}

.fresheditable .edit-button > span
{
	background-image:url("../images/content-editable/edit_icon.png");
	background-repeat:no-repeat;
	background-position:center center;
}

.clear
{
	clear:both;
}

/* fresheditor toolbar */
.fresheditor-toolbar 
{
	font-family:"Georgia", Serif;
	line-height:1.67em;
	font-size:1em;
}

.fresheditor-toolbar .buttons ul.toolbarSection
{
	float:left;
	
	border-right:1px solid #A6A6A6;
	list-style-type:none;
	list-style-position:inside;
	margin:0;
	padding:0;
	margin-right:5px!important;
	margin-bottom:0.62em;
}

.fresheditor-toolbar .buttons ul.toolbarSection li
{
	-moz-border-radius: 0px !important;
	-webkit-border-radius: 0px !important;
	border-radius: 0px !important;
	margin:0;
	float:left;
	
	background:#d3d3d3;
	background: -webkit-gradient(linear, left top, left bottom, from(#FBFBFB), to(#D3D3D3));
	background:-moz-linear-gradient(center top , #FBFBFB, #D3D3D3) repeat scroll 0 0 transparent;
	
}

.fresheditor-toolbar .buttons ul.toolbarSection li a
{
	text-decoration:none;
	-moz-border-radius: 0px !important;
	-webkit-border-radius: 0px !important;
	border-radius: 0px !important;
	
	height:24px;
	font-size:12px;
	color:Black;
	padding:0px;
	display:block;
	
	border:1px solid #999;
	text-shadow: 0 1px white;
	padding:0;
	width: 30px;
	border-bottom:1px solid #666;
	border-right:none;
	text-align:center;

	-moz-box-shadow:none;
	/*background: -webkit-gradient(linear, left top, left bottom, from(#FBFBFB), to(#D3D3D3));
	background:-moz-linear-gradient(center top , #FBFBFB, #D3D3D3) repeat scroll 0 0 transparent;*/
	border-color:#939393 #A6A6A6 #BCBCBC;
	margin:0!important;
}

.fresheditor-toolbar .buttons ul.toolbarSection li a:hover
{
	border-color:Black;
	border-right:1px solid;
	margin-right:-1px;
	padding-right:-1px;
}

.fresheditor-toolbar .buttons ul.toolbarSection li a.toolbar_bold
{
	font-weight:bold;
}

.fresheditor-toolbar .buttons ul.toolbarSection li a.toolbar_italic
{
	font-style:italic;
}

.fresheditor-toolbar .buttons ul.toolbarSection li a.toolbar_underline
{
	text-decoration:underline!important;
}

.fresheditor-toolbar .buttons ul.toolbarSection li a.toolbar_createLink
{
	font-weight:bold;
}

.fresheditor-toolbar .buttons ul.toolbarSection li a.toolbar_ol
{
	background-image:url("../images/content-editable/ol.gif");
	background-repeat:no-repeat;
	background-position:center center;
}

.fresheditor-toolbar .buttons ul.toolbarSection li a.toolbar_ul
{
	background-image:url("../images/content-editable/ul.gif");
	background-repeat:no-repeat;
	background-position:center center;
}

.fresheditor-toolbar .buttons ul.toolbarSection li a.toolbar_insertImage
{
	background-image:url("../images/content-editable/icon_image.gif");
	background-repeat:no-repeat;
	background-position:center center;
}

.fresheditor-toolbar .buttons ul.toolbarSection li a.toolbar_blockquote
{
	background-image:url("../images/content-editable/blockquote.gif");
	background-repeat:no-repeat;
	background-position:center center;
}

.fresheditor-toolbar .buttons ul.toolbarSection li a.toolbar_code
{
	font-weight:bold;
}

a.contenteditable-icon
{
	text-decoration:none!important;
	background-image:url("../images/content-editable/edit_icon.png");
	background-repeat:no-repeat;
	background-position:center center;
	
	width:16px;
	height:24px;
	display:block;
	margin-right:5px;
	
}

.hide
{
	display:none;
}
