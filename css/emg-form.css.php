<?
$widths = array('label'				=> 180
				, 'input' 			=> 200
				, 'textarea' 		=> 400
				, 'border'			=> 1
				);
				
$colors = array('border-fieldset'	=> 'ddd'
				, 'legend'			=> '369'
				, 'label'			=> '333'
				, 'input'			=> '333'
				, 'border-input'	=> '999'
				, 'border-focus'	=> '3a8ccf'
				, 'submit'			=> '111'
				, 'hint'			=> '999'
				, 'error'			=> 'c00'
				);

$padding = 6;

$margins = array('top'			=> 4
				, 'right'		=> 4
				, 'bottom'		=> 8
				, 'left'		=> 0
				);
?>
/*<style>*/
form ul.fields,
form ul.field-combo {
	margin: 0;
	padding: 0;
	list-style: none;
}

.emg-form {
	margin: 0 0 1em;
}
.modal-body .emg-form {
	margin: 0;
}
.emg-form input,
.emg-form select,
.emg-form textarea,
.emg-form label,
.emg-form optgroup {
	font: 12px Arial, Helvetica, sans-serif;
	color: #<?= $colors['input'] ?>;
}
.emg-form input[type="text"],
.emg-form input[type="password"] {
	width: <?= $widths['input'] ?>px;
}
.emg-form select {
	/* input width + 10 */
	width: <?= $widths['input'] + 10 ?>px;
}
.emg-form textarea {
	width: <?= $widths['textarea'] ?>px;
	height: 10em;
}
.emg-form textarea.fluid {
	width: auto;
	height: auto;
}
.emg-form .columns label {
	width: <?= $widths['label'] ?>px;
}
.emg-form .columns input[type="submit"],
.emg-form .columns .column,
.emg-form .columns p.hint {
	/* label width + right margin */
	margin-left: <?= $widths['label'] + $margins['right'] ?>px;
}
.emg-form .columns .label-column {
	float: left;
	padding-top: <?= $padding + $widths['border'] ?>px;
}
/* end Configuration */

/* Fieldset / Legend */
.emg-form fieldset {
	border-top: 1px dashed #<?= $colors['border-fieldset'] ?>;
	margin: 0;
}
.emg-form fieldset + fieldset {
	margin-top: 1em;
}
.emg-form fieldset.no-legend {
	border: none;
	padding: 0;
	margin: 0;
}
.emg-form legend {
	padding: 0 <?= $padding ?>px 0 0;
	color: #<?= $colors['legend'] ?>;
	font-size: 0.9em;
	letter-spacing: 1px;
	text-transform: uppercase;
	margin: 0 0 <?= $margins['bottom'] ?>px;
}
/* end Fieldset / Legend */

/* Label */
.emg-form label {
	display: block;
	margin: 0 0 <?= $margins['bottom'] ?>px;
	color: #<?= $colors['label'] ?>;
	font-weight: bold;
}
.emg-form label span.hint {
	font-size: 0.9em;
	font-weight: normal;
	color: #<?= $colors['hint'] ?>;
}
.emg-form input[type="radio"] + label,
.emg-form input[type="checkbox"] + label {
	display: inline;
	padding-right: 1em;
	vertical-align: middle;
	cursor: pointer;
}
.emg-form label em {
	color: #<?= $colors['error'] ?>;
}
/* end Label */

/* Input / Select / Textarea */
.emg-form input[type="text"],
.emg-form input[type="password"],
.emg-form select,
.emg-form textarea,
.emg-form input[type="file"] {
	border: 1px solid #<?= $colors['border-input'] ?>;;
	padding: <?= $padding ?>px;
	margin: 0 0 <?= $margins['bottom'] ?>px;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
}
.emg-form input[type="text"]:focus,
.emg-form input[type="password"]:focus,
.emg-form select:focus,
.emg-form textarea:focus,
.emg-form input[type="file"]:focus {
	border: 1px solid #<?= $colors['border-focus'] ?>;
}
.emg-form input.radio,
.emg-form input.checkbox {
	vertical-align: middle;
}
.emg-form select {
	padding: <?= $padding - 1 ?>px 0;
}
.emg-form button,
.emg-form input[type="submit"] {
	padding: <?= $padding ?>px <?= $padding * 2 ?>px;
	cursor: pointer;
	font-weight: bold;
	color: #<?= $colors['submit'] ?>;
	margin: <?= $margins['top'] ?>px <?= $margins['right'] ?>px <?= $margins['bottom'] ?>px 0;
	
	/* IE */
	width: auto;
	overflow: visible;
}
/* end Input / Select / Textarea */

.emg-form .column {
	float: left;
	margin: 0 0 <?= $margins['bottom'] ?>px;
}
.emg-form br {
	clear: left;
}

/* Columns */
.emg-form .columns input[type="text"],
.emg-form input[type="password"],
.emg-form .columns select,
.emg-form .columns textarea,
.emg-form .columns label,
.emg-form input[type="file"] {
	margin: 0 <?= $margins['right'] ?>px <?= $margins['bottom'] ?>px 0;
}
.emg-form .columns .value {
	float: left;
	margin: 0 <?= $margins['right'] ?>px <?= $margins['bottom'] ?>px 0;
	padding: <?= $padding + $widths['border'] ?>px <?= $padding ?>px;
}
.emg-form .columns .value.flush,
.emg-form .columns .value.flush-top,
.emg-form .columns .value.flush-left {
	padding-bottom: 0;
}
.emg-form .columns .value.flush {
	padding-top: 0;
	padding-left: 0;
}
.emg-form .columns .value.flush-top {
	padding-top: 0;
}
.emg-form .columns .value.flush-left {
	padding-left: 0;
}
.emg-form .columns label {
	float: left;
	clear: left;
	padding: <?= $padding + $widths['border'] ?>px 0;
	margin: 0 <?= $margins['right'] ?>px <?= $margins['bottom'] ?>px 0;
}
.emg-form .columns div.column {
	padding: <?= $padding + $widths['border'] ?>px 0;
}
.emg-form .columns div.column.checkbox,
.emg-form .columns div.column.radio {
	padding: 0 0 0 <?= $padding ?>px;
	margin: 0;
}
.emg-form .columns label em {
	float: right;
	margin: 0 <?= $padding ?>px 0 0;
}
.emg-form .columns input[type="radio"],
.emg-form .columns input[type="checkbox"],
.emg-form .columns input[type="radio"] + label,
.emg-form .columns input[type="checkbox"] + label {
	float: left;
	margin: <?= $margins['top'] ?>px <?= $margins['right'] ?>px <?= $margins['top'] ?>px <?= $margins['left'] ?>px;
	padding: 0;
}
.emg-form .columns input[type="radio"] + label,
.emg-form .columns input[type="checkbox"] + label {
	width: auto;
	clear: none;
}
.emg-form .columns label.radio-label {
	margin-right: 12px;
}
/* end Columns */

/* ValForm */
.emg-form .val_error {
	display: inline;
	padding: <?= $padding + $widths['border'] ?>px <?= $padding ?>px <?= $padding + $widths['border'] ?>px 0;
	/*position: absolute;*/
	padding: <?= $padding + $widths['border'] ?>px <?= $padding ?>px;
	font-size: 0.9em;
	color: #<?= $colors['error'] ?>;
	margin: 0 0 <?= $margins['bottom'] ?>px;
	white-space: nowrap;
	
	background: #fff;
}
/*.nl-error .val_error {
	float: right;
	text-align: right;
	margin: 0 12px 6px 0;
	width: 100%;
}*/
/* red labels instead of val_error */
.emg-form.hide-error .val_error {
	display: none !important;
}
/* Label highlight */
/*.emg-form label.val-error {
	color: #<?= $colors['error'] ?>;
}*/
/* end ValForm */

/* Custom Fields */
.emg-form input.phone-3 {
	width: 24px;
	margin-right: <?= $margins['right'] ?>px;
}
.emg-form input.phone-4 {
	width: 32px;
}
.emg-form input.digit {
	width: 16px;
}
.emg-form select.fluid,
.emg-form select.year,
.emg-form select.month,
.emg-form .columns select.fluid,
.emg-form .columns select.year,
.emg-form .columns select.month {
	width: auto;
}

.emg-form .columns p.hint {
	font-size: 0.9em;
	margin-top: <?= $margins['top'] ?>px;
	margin-bottom: <?= $margins['bottom'] ?>px;
	color: #<?= $colors['hint'] ?>;
	
	background: url(../images/layout/icons/arrow_down.png) no-repeat 0 1px;
	padding-left: 16px;
}

.emg-form p.hint .required {
	color: #<?= $colors['error'] ?>;
}

.emg-form select.compare {
	width: 80px;
}
.emg-form input.digit {
	width: 24px;
}

/* Configuration */
#config-form.emg-form .columns label {
	width: 248px;
}
#config-form.emg-form .columns input[type="submit"],
#config-form.emg-form .columns .column,
#config-form.emg-form .columns p.hint {
	margin-left: 252px;
}

.emg-form fieldset.submit {
	clear: both;
}

.emg-form input[type="text"].val_int {
	width: <?= $widths['input'] / 4 ?>px;
}
.emg-form input[type="text"].val_int.ssid {
	width: <?= $widths['input'] ?>px;
}

.emg-form .submit.clear input[type="submit"] {
	float: left;
}
