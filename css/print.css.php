<?
header('Content-type: text/css');
?>
/*<style>*/
/*body, a, h1, h2, h3, h4, h5, h6, th, th[scope="col"], th[scope="col"] a {
	color: #000;
}*/
* {
	color: #000 !important;
}
body {
	background: none;
}
#header, #aside, form.search-form, .page-links ol.links, .page-links form, ul.tools, fieldset.check-all, .emg-form fieldset.submit, a.input-link, p.print-external, .emg-form input[type="submit"] {
	display: none;
}
tr.even, th[scope="col"] {
	background: #eee;
}
tr {
	border-bottom: 1px solid #ddd;
}
tr.even {
	background: none;
}
th.checkbox, td.checkbox, th.actions, td.actions {
	display: none;
	padding: 0;
	width: 0;
}
table.search-results {
	margin-bottom: 0;
}

#container {
	overflow: visible;
}
#content {
	float: none;
	margin-top: 16px;
}

/*.emg-form input[type="text"], .emg-form input[type="text"]:focus, .emg-form select, .emg-form select:focus, .emg-form textarea {
	border: none;
}
.emg-form textarea {
	display: block;
	height: 100%;
	overflow: visible;
}*/
.emg-form .columns .value {
	max-width: 600px;
	overflow: visible;
}

table.report {
	margin-top: 1px;
}
table.report th[scope="col"] {
	border: 1px solid #ddd;
}

table.program-action-plan {
	width: 100%;
}
