/*<style>*/
table {
	width: 100%;
	border: 1px solid #ccc;
	border-collapse: separate;
}
th,
td {
	padding: 4px;
}
th:first-child,
td:first-child {
	padding-left: 8px;
}
th {
	font-weight: bold;
}
th span.hint {
	font-weight: normal;
}
th[scope="col"] {
	font-size: 13px;
	/*background: #369 url("../images/bg-header.gif") repeat-x center -52px;*/
	background: -moz-linear-gradient(top, #f2f2f2, #e5e5e5);
	background: -webkit-gradient(linear, left top, left bottom, from(#f2f2f2), to(#e5e5e5));
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#f2f2f2", endColorstr="#e5e5e5");
	border-top: 1px solid #fff;
	padding-top: 6px;
	padding-bottom: 6px;
	vertical-align: middle;
	white-space: nowrap;
}
thead th[scope="col"] {
	border-bottom: 1px solid #ccc;
}
th[scope="col"].sort {
	padding-right: 0;
}
th[scope="col"],
th a,
th a:hover {
	color: #333;
	text-shadow: 0 1px 0 #fff;
}
th.permissions {
	background: #247;
	vertical-align: middle;
	color: #fff;
	vertical-align:top;
}
td ,
th[scope="row"] {
	vertical-align: middle;
	border-bottom: 1px solid #ddd;
}
tbody tr:nth-child(even) td {
	/*background-color: #fafafa;*/
	background: url("../images/layout/row-even.png") repeat 0 0;
}

/* Search Results */
/*table.search-results td.row-count + td {
	font-weight: bold;
}*/
table.search-results tbody tr:last-child td {
	border-color: #ccc;
}
td.checkbox,
td.row-count {
	width: 1px;
}
th.checkbox input[type="checkbox"],
td.checkbox input[type="checkbox"] {
	float: left;
	margin: 2px 0;
}
th.checkbox input[type="checkbox"] + label {
	position: absolute;
	margin: -1px 0 0 18px;
}
td.position input[type="text"] {
	margin: 0;
}
tbody tr.updated td {
	/*background-color: #efd;*/
	background: url("../images/layout/row-updated.png") repeat 0 0;
}
tbody tr.active td {
	/*background-color: #ffc;*/
	background: url("../images/layout/row-active.png") repeat 0 0;
}
/*tbody tr:hover td,
tbody tr.even:hover td,
tfoot tr:hover td,
tfoot tr.even:hover td {
	background-image: url("../images/layout/bg-tr-hover.png");
	background-repeat: repeat-x;
	background-position: left bottom;
	border-color: #aaa;
}*/
tr.footer th {
	text-align: right;
}

/* User Log Details */
table.user-log th[scope="row"] { 
	vertical-align: top;
}

/* Dashboard */
table.dashboard {
	float: left;
	width: 50%;
	margin: 0 0 32px;
}
table.dashboard:nth-child(odd) {
	clear: left;
}
table.dashboard:nth-child(even) {
	margin-left: -1px;
}
table.dashboard th.count,
table.dashboard th[scope="row"].count {
	padding-left: 16px;
	padding-right: 8px;
}
table.dashboard th.count {
	text-align: right;
	width: 1px;
}
table.dashboard th[scope="row"].count {
	font: bold 2.5em Arial, Helvetica, sans-serif;
}
table.dashboard tbody td.amount {
	width: 120px;
}

/* Order Details */
table.shipping.billing, table.order.details {
	width: 640px;
}

table.shipping.billing td {
	vertical-align: top;
}

table.order.details tfoot th[scope="row"] {
	text-align: right;
}
table.order.details th.quantity, table.order.details td.quantity {
	text-align: center;
}
table.order.details tr.subtotal th, table.order.details tr.subtotal td {
	border-top: 1px solid #aaa;
}
table.order.details tr.total th, table.order.details tr.total td {
	background: #369 url("../images/bg-header.gif") repeat-x center -52px;
	color: #fff;
}
table.order.details tr.total td {
	font-weight: bold;
}

table.order.details dl.item-options dt, table.order.details dl.item-options dd {
	float: left;
}
table.order.details dl.item-options dt {
	clear: both;
	margin-right: 4px;
}