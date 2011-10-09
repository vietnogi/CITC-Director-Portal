<?
$baseMargin = 0;
?>
/*<style>*/
body {
	font: 0.7em Verdana, Arial, Helvetica, sans-serif;
	color: #333;
	background: #fff url("../images/bg-header.gif") repeat-x 0 0;
}

a {
	color: #369;
	text-decoration: none;
	/*cursor: pointer;*/
	outline: none;
}
a:hover {
	color: #036;
}

h1, h2, h3, h4, h5, h6 {
	font: bold 2em Arial, Helvetica, sans-serif;
	margin: 0 0 12px;
	color: #036;
}
h2 span.name,
h2 span.delimiter {
	color: #999;
}
h3, h4, h5, h6 {
	font-size: 1.4em;
}
h3 span.tip {
	font-weight: normal;
	font-size: 0.7em;
	color: #666;
}
h2 + p.print-external {
	float: right;
	margin-top: -36px;
}

p, ul, ol, dl, table {
	line-height: 1.5em;
	margin: 0 0 12px;
}

ul, ol {
	padding-left: <?= $baseMargin * 2 ?>px;
}
ul {
	list-style: disc;
}
ol {
	list-style: decimal;
}

span.ul {
	text-decoration: underline;
}

#container {
	/*overflow: hidden;*/
}
#content {
	float: left;
	margin: 32px 16px;
	width: 800px;
}

#header {
	height: 80px;
	margin: 0;
	overflow: hidden;
}
#logo h1 a {
	text-indent: -2000em;
	display: block;
	width: 230px;
	height: 80px;
	background: url("../images/logo.gif") no-repeat;
}

.emg-form .check-all input[type="checkbox"], .emg-form .check-all label.checkbox-label {
	float: left;
	margin: 0 4px;
}

#export_fields input {
	float: none;
}

.details-col {
	float: left;
	margin-right: 16px;
}
.details-col textarea {
	width: 200px;
}

#footer {
	clear: both;
}

#upload-progress{
	margin-left:8px;
}

ul.columns {
	margin: 0;
	padding: 0;
	list-style: none;
}

p.toggle-note {
	margin: 0 0 8px;
	font-size: 0.8em;
	text-transform: uppercase;	
}