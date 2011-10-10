/*<style>*/
.table-actions {
	overflow: hidden;
}
.table-actions p,
.table-actions ul,
.table-actions dl {
	margin: 0 0 8px;
}
.table-actions ul {
	padding: 0;
	list-style: none;
}
.table-actions p.go {
	float: left;
	margin-right: 16px;
}
.table-actions ul.global {
	float: right;
	margin-right: -4px;
}
.table-actions ul.batch {
	float: left;
}
.table-actions ul.batch.basic {
	float: left;
	line-height: 26px;
	border: 1px solid #d1d1d1;
	background-color: #f5f5f5;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	border-radius: 8px;
	font-weight: bold;
}
.table-actions ul.batch.basic span {
	cursor: pointer;
	color: #6096c3;
}
.table-actions ul.batch.basic span:hover {
	color: #264863;
}
.table-actions ul.batch.basic span,
.table-actions ul.batch.basic a {
	display: block;
	padding: 0 16px;
}
.table-actions ul.batch.basic ul {
	position: absolute;
	left: -2000em;
}
.table-actions ul.batch.basic li:hover ul {
	left: auto;
}