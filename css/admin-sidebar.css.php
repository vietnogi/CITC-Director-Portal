/*<style>*/
#sidebar {
	position: absolute;
	z-index: 0;
	margin: 0;
	width: 160px;
	overflow: hidden;
	top: 0;
	bottom: 0;
}
#sidebar .shadow {
	position: absolute;
	top: 0;
	right: -1px;
	bottom: 0;
	width: 1px;
	-moz-box-shadow: 0 0 6px #000;
	-webkit-box-shadow: 0 0 6px #000;
	box-shadow: 0 0 6px #000;
}

ul.nav,
ul.nav ul {
	margin: 0;
	padding: 0;
	list-style: none;
}

.sidebar {
	background: #d9d9d9;
}

.sidebar ul.nav > li > span,
.sidebar ul.nav a {
	display: block;
	padding: 8px 12px;
	
	font-size: 11px;
	line-height: 16px;
	font-weight: bold;
}
.sidebar ul.nav > li > span {
	font-size: 14px;
	font-weight: bold;
	border-top: 1px solid #fff;
	text-shadow: 0 1px 0 #fff;
	
	background: -moz-linear-gradient(top, #f2f2f2, #e5e5e5);
	background: -webkit-gradient(linear, left top, left bottom, from(#f2f2f2), to(#e5e5e5));
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#f2f2f2", endColorstr="#e5e5e5");
	border-bottom: 1px solid #ccc;
	
	white-space: nowrap;
}
.sidebar ul.nav ul {
	background: #fff;
}
.sidebar ul.nav a {
	padding-top: 4px;
	padding-bottom: 4px;
}
.sidebar ul.nav li.current a {
	background: #3a8ccf;
	color: #fff;
	text-shadow: 0 1px 0 #0b2d47;
	
	/*position: relative;
	z-index: 1;*/
}
.sidebar ul.nav > li {
	border: 1px solid #ccc;
	border-width: 1px 0;
	margin-bottom: 4px;
}
.sidebar ul.nav > li:first-child {
	border-top: none;
}

.sidebar ul.nav span.count {
	position: absolute;
	right: 6px;
	font-weight: normal;
	background: #fff9b9;
	color: #0b2d47;
	text-shadow: 0 1px 0 #fff;
	border: 1px solid #cdcdcd;
	padding: 0 4px;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
	margin: -2px 0 0 4px;
	
	background: -moz-linear-gradient(top, #fffbd1, #fff7a3);
	background: -webkit-gradient(linear, left top, left bottom, from(#fffbd1), to(#fff7a3));
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#fffbd1", endColorstr="#fff7a3");
}