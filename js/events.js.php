/*<script>*/
var Events = function (container) {
	
	// default container
	container = container || $('body')[0]; 
	
	// Span nav parents
	$("ul#nav li.parent > span").click(function() {
		$(this).closest('li.parent').toggleClass('hover');
	});
	
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
	
	// ajax fill
	$('.ajax-fill', container).ajaxFill();
	
	// ajax submit, make sure valform is set before this
	$('.ajax-submit', container).ajaxSubmit();
	
	// modal
	$('.modal', container).modal();
	
	// address
	$('form ul.address', container).address();
};