/*<style>*/
p.no-filters {
	float: none;
	display: inline-block;
	margin: 0 0 12px;
}
dl.report-filters {
	overflow: hidden;
}
dl.report-filters > dt {
	margin: 0;	
	position: relative;
	z-index: 1;
}
dl.report-filters > dt a {
	background: url("../images/layout/icons/fugue/plus-white.png") no-repeat 8px center;
}
dl.report-filters.view > dt a {
	border-bottom-color: #f3f3f3;
}
dl.report-filters.view > dt a, dl.report-filters.no-filters > dt a, p.no-filters span {
	background-image: url("../images/layout/icons/fugue/minus-white.png");
}
dl.report-filters.view > dt a:hover {
	border-bottom-color: #aaa;
}
dl.report-filters > dd {
	margin: -1px 0 0;
	clear: left;
	float: left;
	padding: 0;
	border: 1px solid #ddd;
	border-width: 1px 1px 0;
	background: #f3f3f3;
	display: none;
}
dl.report-filters.view > dd {
	display: block;
}
dl.report-filters dd table.filters {
	margin: 0;
}
table.filters th, table.filters td {
	padding: 4px 8px;
}
table.filters th[scope="row"] {
	border-right: 1px dashed #ddd;
}