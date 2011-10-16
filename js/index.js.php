/*<script>*/
$(document).ready(function () {
	// configure metadata to read from "metadata" property
	$.metadata.setType('attr', 'metadata');
	BrowserDetect.init();
	FW.init();
	Events();
	Markup();
	Customer();
});
