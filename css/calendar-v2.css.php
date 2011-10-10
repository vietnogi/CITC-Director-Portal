/*<style>*/
.calendar-container {
	font: 10px/10px Arial, Helvetica, sans-serif;
	clear: both;
}
.calendar-container .tooltip span.tip {
	min-width: 112px;
}
.calendar-container .tooltip:hover span.tip {
	margin-top: 12px;
	margin-left: 12px;
}
.calendar-container ul, .calendar-container table {
	line-height: 10px;
}
.calendar-container ul.nav {
	margin: 0 5px 0 0;
	padding: 0;
	list-style: none;
	float: left;
}
.calendar-container ul.nav li {
	float: left;
	margin: 0 1px 0 0;
}
.calendar-container ul.nav li a {
	display: block;
	background: #369;
	height: 12px;
	padding: 4px 8px;
	color: #fff;
}
.calendar-container .heading {
	float: left;
	font: bold 14px/14px Arial, Helvetica, sans-serif;
	margin: 0 0 1px;
	padding: 3px 0;
}
table.calendar {
	clear: both;
	margin: 0 0 32px;
	padding: 0;
}
table.calendar tr, table.calendar tr:hover, table.calendar th, table.calendar td, table.calendar a {
	background: none;
	border: none;
	padding: 0;
}
table.calendar thead th {
	text-align: center;
	padding: 6px 0;
	background: #369;
	color: #fff;
	font-weight: normal;
	font-size: 11px;
}
table.calendar td {
	text-align: left;
	vertical-align: top;
	width: 112px;
	min-width: 112px;
	max-width: 112px;
	height: 100px;
	min-height: 100px;
	border: 1px solid #ddd;
	
	overflow: hidden;
}
table.calendar td:hover {
	overflow: visible;
	position: relative;
}
table.calendar td.day .day {
	text-align: right;
	padding: 4px;
	background: #eee;
	font: 10px/10px Arial, Helvetica, sans-serif;
	color: #333;
	margin: 0 0 2px;
}
table.calendar td.day.edge .day {
	font-weight: normal;
	color: #999;
}
table.calendar td.day.today .day {
	background: #fec;
	font-weight: bold;
}
table.calendar td.day.today .day .hint {
	float: left;
	margin-top: 2px;
	font-size: 8px;
	line-height: 8px;
	font-weight: normal;
	color: #c92;
	text-transform: uppercase;
	letter-spacing: 1px;
}

table.calendar td.day ul.calendar-events {
	margin: 0;
	padding: 0;
	list-style: none;
	line-height: 12px;
	overflow: visible;
}
table.calendar td.day ul.calendar-events li {
	margin: 0 0 1px;
	padding: 2px;
	border: none;
	
	background: #369;
	color: #fff;
	
	overflow: hidden;
	float: left;
	min-width: 108px;
	width: 108px;
}
/*
table.calendar td.day ul.calendar-events li a {
	color: #fff;
}
*/
table.calendar td.day.edge ul.calendar-events li {
	background: #69c;
}
table.calendar td.day ul.calendar-events li:hover {
	overflow: visible;
	position: relative;
	width: auto;
}