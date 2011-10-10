/*<style>*/
table.search-results {
	width: 100%;
}
table.search-results td,
td.row-actions {
	vertical-align: top;
	padding-bottom: 24px;
}
td.row-actions {
	font-weight: bold;
}
ul.row-actions {
	position: absolute;
	left: -2000em;
	margin: 4px 0 0;
	padding: 0;
	list-style: none;
	font-size: 11px;
	line-height: 1em;
	font-weight: normal;
	overflow: visible;
}
tr:hover ul.row-actions,
table.configuration ul.row-actions {
	left: auto;
}
ul.row-actions li {
	float: left;
	margin: 0;
}
ul.row-actions li + li {
	margin-left: 8px;
	border-left: 1px solid #bfbfbf;
	padding-left: 8px;
}
ul.row-actions li a {
	text-indent: 0;
	width: auto;
	line-height: 16px;
	padding-left: 20px;
}
ul.row-actions li a span.tip {
	position: static;
	margin: 0;
	padding: 0;
	border: none;
	background: none;
	max-width: none;
	color: inherit;
}
ul.row-actions li.download a img {
	position: absolute;
	left: -2000em;
	margin: 0 0 0 4px;
}
ul.row-actions li.download a:hover img {
	left: auto;
}