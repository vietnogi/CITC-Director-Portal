/*<script>*/
var Events = function (container) {
	
	// default container
	container = container || $('body')[0]; 
	
	//val form
	$('form.val-form', container).valform({abc: 123, xyz: 'abc'});
	
	// js clicks
	$('.js-link', container).click(function () {
		var metadata = $(this).metadata();
		if (metadata.href === undefined) {
			return false;	
		}
		window.location = metadata.href;
	});
	
	// toggles
	$('.toggle', container).click(function () {
		var options = $(this).metadata();
		$(options.selector).toggleClass(options.class);
	});
	
	
	// max length next
	$('input[type="text"].max-length-next', container).keyup( function (event) {
		if (event.keyCode == 9) { //ignore tabs
			return;	
		}
		if (this.value.length == this.maxLength) {
			var focusid = classAfter('max-length-next', this.className);
			$('#' + focusid).focus();
		}
	});
	
	// set jquery plugins
	var jPlugins = {
		ajaxFill: '.ajax-fill'
		, ajaxSubmit: '.ajax-submit'
		, modal: '.modal'
		, address: 'form ul.address'
		, uploadImageCrop: '.upload-image-crop'
		, checkedValidation: '.checked-validation'
		, fresheditor: '.fresheditor'
	};
	var $_ = $(); // get jquery returned obj
	$.each(jPlugins, function(func, selector) {
		if ($.isFunction($_[func])) {
			$(selector)[func]();
		}
	});

};