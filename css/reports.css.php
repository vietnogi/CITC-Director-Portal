/*<style>*/
/* Main */
.report-select {
	margin: 0 0 18px;
}
.emg-form.reports fieldset.report-fields {
	margin: 0 0 12px;
	display: none;
}
.emg-form.reports fieldset.report-fields.active {
	display: block;
}
.emg-form label.checkbox-label.reports-select {
	padding-left: 20px;
	background: url("../images/layout/icons/fugue/arrow-continue-270.png") no-repeat 0 -1px;
	height: 16px;
}

/* Graphs */
ul.reports-graphs {
	margin: 0;
	padding: 0;
	list-style: none;
}

dl.report-graphs dt {
	font: bold 1.4em Arial, Helvetica, sans-serif;
	color: #036;
	margin: 0 0 12px;
}
dl.report-graphs dd {
	margin: 0 0 18px;
}

.pie-chart {
	margin: 0 0 24px;
}

.reports h3, dl.report-graphs dt {
	padding: 12px 0 0;
	border-top: 1px dashed #ccc;
}

table.report {
	margin: 0 0 18px;
}
table.report th, table.report td {
	border: 1px solid #ddd;
	border-width: 0 1px;
	padding: 4px 8px;
}
table.report th[scope="col"] {
	border-color: #222;
}
table.report th[scope="row"].single {
	font-weight: normal;
}
table.report th.percentage, table.report td.bup {
	text-align: right;
	white-space: nowrap;
}
table.report td.count, table.report td.grade, table.report td.total, table.report td.score {
	text-align: center;
}
table.report td.total, table.report td.first.percent {
	font-weight: bold;
}
table.report td span.zero {
	color: #ccc;
}
table.report td.group {
	background: url("../images/layout/bg-td-group.png") no-repeat center 0;
}
table.report th.first.percent, table.report td.first.percent {
	text-align: right;
	white-space: nowrap;
}


table.program-action-plan, table.program-action-plan-header {
	width: 100%;
}
table.report.cst-scores th[scope="row"] {
	white-space: nowrap;
}

table.program-action-plan-header {
	border-top: 1px solid #ddd;
}
table.program-action-plan-header td {
	border-left: none;
}
table.program-action-plan-header th[scope="row"] {
	border-right: none;
}
table.program-action-plan-header th[scope="row"].of-volunteers {
	width: 104px;
}
table.program-action-plan-header th[scope="row"].of-paraprofessionals {
	width: 152px;
}
table.program-action-plan-header th[scope="row"].of-credentialed-teachers {
	width: 184px;
}