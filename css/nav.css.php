/*<style>*/
#aside {
	float: left;
	margin: 12px 0 16px 16px;
}
#aside, #navs {
	width: 120px;
}
#navs {
	position: static;
	top: auto;
}
#navs.fixed {
	position: fixed;
	top: 8px;
}

#keep-login {
	font-size: 0.9em;
	margin: 0 0 24px;
}
#keep-login input[type="checkbox"], #keep-login label {
	float: left;
	cursor: pointer;
}
#keep-login input[type="checkbox"] {
	margin: 0 4px 0 6px;
}

#aside .box .heading, #nav-date {
	font: 1em "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
	padding: 4px 8px;
	color: #fff;
	margin: 0;
}
#nav-date {
	background: url("../images/bg-nav-bottom.gif") no-repeat;
	margin: 0 0 12px;
}
#aside .box .heading {
	background: #036 url("../images/bg-nav-h2.gif") no-repeat;
}

#aside ul.nav {
	border: 1px solid #999;
	border-width: 0 1px;
	margin: 0;
	padding: 0;
	list-style: none;
	line-height: 1em;
}
#aside ul.nav li a {
	display: block;
	padding: 7px 0 8px 8px;
	color: #369;
	border-bottom: 1px solid #ddd;
	background: #fff;
}
#aside ul.nav li a:hover {
	background: #eee url("../images/right-arrow-blue.gif") no-repeat 100px center;
}
#aside ul.nav li.current a{
	background: #369 url("../images/right-arrow.gif") no-repeat 100px center;
	color: #fff;
}

#aside ul.nav.icons li a {
	padding-left: 32px;
	background-repeat: no-repeat;
	background-position: 8px center;
}


#nav-summary {
	background: url("../images/bg-nav-bottom.gif") no-repeat 0 bottom;
	padding-bottom: 4px;
}