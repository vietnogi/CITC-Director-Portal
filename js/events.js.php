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
	};
	var $_ = $(); // get jquery returned obj
	$.each(jPlugins, function(func, selector) {
		if ($.isFunction($_[func])) {
			$(selector)[func]();
		}
	});
	
	$('#wysiwyg').wysiwyg({
		  controls: {
			bold          : { visible : true },
			italic        : { visible : true },
			underline     : { visible : true },
			strikeThrough : { visible : true },
			
			justifyLeft   : { visible : true },
			justifyCenter : { visible : true },
			justifyRight  : { visible : true },
			justifyFull   : { visible : true },

			indent  : { visible : true },
			outdent : { visible : true },

			subscript   : { visible : true },
			superscript : { visible : true },
			
			undo : { visible : true },
			redo : { visible : true },
			
			insertOrderedList    : { visible : true },
			insertUnorderedList  : { visible : true },
			insertHorizontalRule : { visible : true },

			h4: {
				visible: true,
				className: 'h4',
				command: ($.browser.msie || $.browser.safari) ? 'formatBlock' : 'heading',
				arguments: ($.browser.msie || $.browser.safari) ? '<h4>' : 'h4',
				tags: ['h4'],
				tooltip: 'Header 4'
			},
			h5: {
				visible: true,
				className: 'h5',
				command: ($.browser.msie || $.browser.safari) ? 'formatBlock' : 'heading',
				arguments: ($.browser.msie || $.browser.safari) ? '<h5>' : 'h5',
				tags: ['h5'],
				tooltip: 'Header 5'
			},
			h6: {
				visible: true,
				className: 'h6',
				command: ($.browser.msie || $.browser.safari) ? 'formatBlock' : 'heading',
				arguments: ($.browser.msie || $.browser.safari) ? '<h6>' : 'h6',
				tags: ['h6'],
				tooltip: 'Header 6'
			},
			
			cut   : { visible : true },
			copy  : { visible : true },
			paste : { visible : true },
			html  : { visible: true },
			increaseFontSize : { visible : true },
			decreaseFontSize : { visible : true },
			exam_html: {
				exec: function() {
					this.insertHtml('<abbr title="exam">Jam</abbr>');
					return true;
				},
				visible: true
			}
		  },
		  events: {
			click: function(event) {
				if ($("#click-inform:checked").length > 0) {
					event.preventDefault();
					alert("You have clicked jWysiwyg content!");
				}
			}
		  }
		});
};