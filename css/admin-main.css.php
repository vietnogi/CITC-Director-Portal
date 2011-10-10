/*<style>*/
body {
	background: none;
	font: 12px/18px Arial, Helvetica, sans-serif;
}
html,
body {
	height: 100%;
}

a {
	color: #3a8ccf;
}
a:hover {
	color: #0b2d47;
}

#container {
	height: auto;
	min-height: 100%;
	position: relative;
}
#container.no-login {
	background: #f0f0f0;
}
#content-container {
	width: auto;
	margin: 0;
	padding: 0 16px 32px;
	/* sidebar bg */
	border-left: 160px solid #d9d9d9;
}
.no-login #content-container {
	margin-left: 16px;
	padding-left: 0;
	border-left: none;
	background: none;
	min-height: none;
}
#content {
	float: none;
	width: auto;
	margin: 0;
}

p.go,
p.go a,
ul.buttons li,
ul.buttons li a {
	display: inline-block;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	border-radius: 8px;
}
p.go,
ul.buttons li {
	border: 1px solid #245485;
	
	background: -moz-linear-gradient(top, #83b3db, #669bce);
	background: -webkit-gradient(linear, left top, left bottom, from(#83b3db), to(#669bce));
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#83b3db", endColorstr="#669bce");
}
p.go a,
ul.buttons li a {
	display: block;
	font: bold 12px/24px Arial, Helvetica, sans-serif;
	border: 1px solid #9ec4e3;
	color: #fff;
	text-shadow: 0 -1px 0 #305579;
	padding: 0 16px;
}
p.go.add {
	border-color: #406108;
	background: -moz-linear-gradient(top, #7fb614, #5e9b0a);
	background: -webkit-gradient(linear, left top, left bottom, from(#7fb614), to(#5e9b0a));
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#7fb614", endColorstr="#5e9b0a");
}
p.go.add a {
	font-size: 14px;
	border-color: #9cc849;
	text-shadow: 0 -1px 0 #406108;
}
ul.buttons {
	margin: 0 0 8px;
	padding: 0;
	list-style: none;
	overflow: hidden;
}
ul.buttons.confirm {
	margin-bottom: 0;
}
ul.buttons li {
	float: left;
	margin-right: 4px;
}
ul.buttons li a {
	background-position: 8px center;
	padding: 0 8px 0 28px;
}