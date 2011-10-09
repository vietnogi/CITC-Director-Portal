<?
// Icons above/below main listing
$toolsIcons = array('add' => 'plus'
					, 'csv' => 'document-excel-csv'
					, 'delete' => 'cross'
					, 'duplicate' => 'layers-stack-arrange'
					, 'edit' => 'pencil'
					, 'emailer' => 'mails-stack'
					, 'hours' => 'clock-history'
					, 'no' => 'slash'
					, 'option-tag-manager' => 'tags'
					, 'print' => 'printer--arrow'
					, 'tag.add' => 'tag-plus'
					, 'tag.delete' => 'tag-minus'
					, 'tag-manager' => 'tags-label'
					, 'update' => 'arrow-circle'
					, 'upload' => 'drive-upload'
					, 'view' => 'magnifier'
					, 'yes' => 'tick'
					// Application specific
					);

// Row icons
$rowActionsIcons = array('address.edit' => 'address-book--pencil'
						, 'attachments' => 'paper-clip'
						, 'client' => 'user-business'
						, 'contacts' => 'address-book'
						, 'delete' => 'cross'
						, 'details' => 'table'
						, 'download' => 'drive-download'
						, 'download.no' => 'document-attribute-x'
						, 'edit' => 'pencil'
						, 'email' => 'mail'
						, 'email.send' => 'mail--arrow'
						, 'files' => 'inbox-document'
						, 'hours' => 'clock'
						, 'images' => 'images-stack'
						, 'lead' => 'user-silhouette-question'
						, 'lead.convert' => 'user--arrow'
						, 'notes' => 'sticky-note-text'
						, 'option-tag-manager' => 'tags'
						, 'print' => 'printer'
						, 'reminders' => 'flag'
						, 'reviews' => 'ballons'
						, 'tag-manager' => 'tags-label'
						, 'values' => 'ui-radio-buttons'
						// Summary
						, 'top' => 'arrow-090-medium'
						, 'emails' => 'mail'
						// Application specific
						);

$inputLinkIcons = array('wysiwyg' => 'document--pencil'
						, 'calendar' => 'calendar-day'
						, 'expand' => 'application-resize'
						, 'email-template-variables' => 'information'
						, 'client' => 'user-business'
						);

function liABgImage($icons, $parent, $isAnchor = false, $imgDir = '../images/layout/icons/fugue/') {
	if (is_array($icons)) {
		foreach ($icons as $class => $img) {
			?>
			<?= $parent ?>.<?= $class ?><?= !$isAnchor ? ' a' : '' ?> {
				background-image: url("<?= $imgDir . $img ?>.png");
				background-repeat: no-repeat;
			}
			<?
		}
	}
}
?>
/*<style>*/
/* Tools */
ul.tools {
	margin: 0 0 6px -6px;
	padding: 0;
	list-style: none;
	overflow: hidden;
}
ul.tools.standalone {
	margin-bottom: 0;
}
form.manager ul.tools, ul.tools.confirm {
	margin: 0;
}
ul.tools li, dl.report-filters > dt, p.no-filters {
	float: left;
	margin: 0 0 0 6px;
	background: #f3f3f3 url("../images/layout/bg-tool-hover.png") repeat-x 0 32px;
}
ul.tools li {
	margin-bottom: 6px;
}
ul.tools li:hover, dl.report-filters > dt:hover {
	background-color: none;
	background-position: 0 bottom;
}
ul.tools li a, dl.report-filters > dt a, p.no-filters span {
	display: block;
	border: 1px solid #ddd;
	background-repeat: no-repeat;
	background-position: 8px center;
	padding: 7px 8px 4px 28px;
	height: 20px;
	white-space: nowrap;
}
ul.tools li a:hover, dl.report-filters > dt a:hover {
	border-color: #aaa;
}
<?
liABgImage($toolsIcons, 'ul.tools li');
liABgImage($toolsIcons, 'ul.buttons li');
?>
ul.tools li.export-csv, ul.tools li.emailer, ul.tools li.print {
	float: right;
}

/* Row Actions */
<?
$margin = 4;
$width = 16 + $margin;
?>
ul.row-actions {
	margin: 0 0 0 -<?= $margin ?>px;
	padding: 0;
	list-style: none;
	overflow: hidden;
	white-space: nowrap;
	height: 16px;
}
ul.row-actions li {
	margin: 0 0 0 <?= $margin ?>px;
	border: none;
}
ul.row-actions li,
ul.row-actions li a,
a.input-link {
	display: -moz-inline-stack;
	display: inline-block;
}
ul.row-actions li a,
a.input-link {
	width: 16px;
	height: 16px;
}
a.input-link {
	vertical-align: top;
	background-repeat: no-repeat;
	background-position: 0 0;
	margin: 3px 0 0;
}
<?
liABgImage($rowActionsIcons, 'ul.row-actions li');
liABgImage($rowActionsIcons, '#aside ul.nav.icons li');
liABgImage($inputLinkIcons, 'a.input-link', true);
?>
.emg-form fieldset.no-legend.check-all {
	margin: 0 0 12px;
}

/* Tag Manager */
ol.tags li {
	clear: left;
	float: left;
	margin: 0 0 8px;
}
ol.tags li a {
	background: url("../images/layout/icons/fugue/tag-label.png") no-repeat left center;
	padding: 0 0 0 20px;
	display: block;
	height: 16px;
	width: auto;
}
ol.tags li a span.tip {
	padding: 0 0 0 20px;
	margin-top: 0;
	color: #999;
	border: none;
	background: url("../images/layout/icons/fugue/tick.png") no-repeat left center;
}
ol.tags li a:hover span.tip {
	margin-left: 16px;
}
ol.letters li {
	display: inline;
	padding: 0 2px;
}

/* Filter/Add Form actions */
.emg-form .form-actions p.clear-fields a {
	/*background-image: url("../images/layout/icons/fugue/minus-white.png");*/
	background-image: url("../images/layout/icons/fugue/cross-white.png");
}
.emg-form .form-actions p.advanced-search a, .emg-form.search-form .form-actions p.advanced-search span {
	background-image: url("../images/layout/icons/fugue/magnifier--plus.png");
}
.emg-form .form-actions p.advanced-search.expanded a {
	background-image: url("../images/layout/icons/fugue/magnifier--minus.png");
}
/*.emg-form .form-actions */p.print-external a {
	background: url("../images/layout/icons/fugue/printer--arrow.png") no-repeat left 0;
	display: block;
	height: 16px;
	padding: 0 0 0 20px;
}

/* Sort Headers */
th a {
	display: inline-block;
	height: 16px;
	padding-right: 16px;
	background: url("../images/layout/icons/arrows-sort.png") no-repeat right 16px;
}
th a:hover {
	background-position: right 0;
}
th a.sort-asc {
	background-position: right -16px;
}
th a.sort-desc {
	background-position: right -32px;
}