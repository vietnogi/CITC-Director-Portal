/*<script>*/

(function ($) { //anonymous function to prevent global scope, "$" is a prototype reference
	
	function instance (form, parameters) { //each form will have an instance
		this.form = form;
		this.alertErrors = false;
		this.containerErrors = false;
		this.hideErrors = false;
		this.errorClass = 'val-error';
		this.errorContainerTag = 'div';
		this.errorContainerClass = 'val_error';
		this.dontDisableSubmit = false;
		
		if (parameters.alertErrors != null && parameters.alertErrors) {
			this.alertErrors = true;
		}
		if (parameters.containerErrors != null && parameters.containerErrors) {
			this.containerErrors = true;
		}
		if (parameters.hideErrors != null && parameters.hideErrors) {
			this.hideErrors = true;
		}
		if (parameters.errorClass != null) {
			this.errorClass = parameters.errorClass;
		}
		if (parameters.errorContainerTag != null) {
			this.errorContainerTag = parameters.errorContainerTag;
		}
		if (parameters.errorContainerClass != null) {
			this.errorContainerClass = parameters.errorContainerClass;
		}
		if (parameters.dontDisableSubmit != null) {
			this.dontDisableSubmit = parameters.dontDisableSubmit;
		}
	}
	
	//create the "Please Wait" button when a user clicks submit
	function createSubmitWait (instance) {
		$('input[type="submit"]', instance.form).each(function () {
			var waitBtn = '<input type="submit" disabled="disabled" value="Please Wait&hellip;" class="hidden please-wait ' + $(this).attr('class') + '">';
			$(waitBtn).insertAfter($(this));
		});
	}
	
	//hide submit and show the please wait
	function submitWait (instance) {
		$('input[type="submit"]', instance.form).hide();
		$('input[class*="please-wait"]', instance.form).show();
	}
	
	//hide the pelase wait and show submit button
	function resetSubmit (instance) {
		$('input[type="submit"]', instance.form).show();
		$('input[class*="please-wait"]', instance.form).hide();
	}
	
	//given an element and a class, will return the immediate post class
	function classAfter (element, needle) {
		var classes = $(element).attr('class').split(/\s+/);
		for(var i = 0; i < classes.length; i++) {
			if (classes[i] == needle && (i + 1 != classes.length)) {
				return classes[i+1];	
			}
		}
		
		return false;
	}
	
	//determine label for error
	function getLabel (instance, field) {		
		var fieldType = field.type.toLowerCase();
		if (fieldType == 'checkbox' && field.name.indexOf('[') != -1 ) { //for checkboxes, name is an array, get label base on first index id 
			var labelFor = $('[name="' + field.name + '"]', instance.form)[0].id;
		}
		else{
			// check for val_combo
			var idAsClass = classAfter(field, 'val-combo');
			if (idAsClass) {
				var labelFor = idAsClass;
			}
			else {
				var labelFor = field.id;
			}
		}
		
		if (labelFor == null) {
			alert(field.name + ': label not found');
			return;
		}
		
		var label = $('label[for=' + labelFor  + ']', instance.form);
		if (label.length > 0) {
			return {element: label
					, labelFor: labelFor
					, msg: label[0].innerHTML
					}; 	
		}
		
		//no label, try to use default value
		var labelForField = $('#' + labelFor);
		if (labelForField.length > 0) {
			return {element: null
					, labelFor: labelFor
					, msg: labelForField[0].defaultValue
					};
		}
		
		alert('unable to locate label');
	}
	
	
	function validate (instance, field) {
		var classStr = $(field).attr('class');
		
		if (classStr == null) { //ignore classless fields
			return true;
		}
		
		if (!$(field).is(':visible')) { //ignore invisible fields
			return true;	
		}
		
		//handle keywords
		var classes = classStr.split(/\s+/);
		var index = $.inArray('val_combo', classes);
		if (index != -1) { // found key word
			//handle keyword code needed
		}
		
		//value manipulation
		if (field.value && field.type.toLowerCase() != 'file') { //security error for file inputs
			field.value = $.trim(field.value); //auto strip whitespaces
		}
		
		clearError(instance, field);
		
		for(var i = 0; i < classes.length; i++) {
			if (validators[classes[i]] == null) {
				continue;
			}
			var error = validators[classes[i]](field);
			if (error) {
				handleError(instance, field, error);
				return false;
			}
		}
		
		return true;
	}
	
	function handleError (instance, field, error) {
		var label = getLabel(instance, field);
		var errorMsg = createErrorMsg(label.msg, error);
		
		if (!instance.hideErrors) {
			var html = '<' + instance.errorContainerTag + ' id="' + field.id + '-error" class="' + instance.errorContainerClass + '">' + errorMsg + '</' + instance.errorContainerTag + '>';
			//check to place error after a diferent element
			var idAsClass = classAfter(field, 'val-error-after');
			if (idAsClass) {
				var targetElement = $('#' + idAsClass);
			}
			else { //place error after field element
				var targetElement = field;
			}
			$(html).insertAfter(field);
		}
		
		$(field).addClass(instance.errorClass);
		
		if (label.element != null) {
			$(label.element).addClass(instance.errorClass);
		}
		
		return true;
	}
	
	function createErrorMsg (msg, error) {
		// Remove <em>*</em>, tooltip
		msg = msg.replace(/<em>\*<\/em>|<span class="hint">.+<\/span>|<span class="tip">.+<\/span>/gi, '');
		
		// Strip tags
		msg = $('<tag>' + msg + '</tag>').text(); // .text() will only work when string starts with html tag
		
		// Trim whitespace and ending colon
		msg = $.trim(msg);
		msg = msg.replace(/:$/gi, '') + ' ' + error;
		
		return msg;
	}
	
	function clearError (instance, field) {
		var label = getLabel(instance, field);
		
		if ($('#' + field.id + '-error').length > 0) {
			$('#' + field.id + '-error').remove();
		}
		$(field).removeClass(instance.errorClass);
		$(label.element).removeClass(instance.errorClass);
	}
	
	function setEvents (instance) {
		//handle fields
		var fieldSelector = 'input:not([type="submit"],[type="hidden"]),select,textarea';
		$(fieldSelector, instance.form).blur(function() {
			validate(instance, this);
		});
		
		//handle submit
		$(instance.form).submit(function(event) {
			//disable submit button so user can not submit twice acidently
			if (!instance.dontDisableSubmit) {
				submitWait(instance);
			}
			
			var focused = false;
			$(fieldSelector, instance.form).each(function() {
				var validated = validate(instance, this);
				if (!validated && !focused) {
					focused = true;
					this.focus();
				}
			});
			
			if (focused) {
				//an error occured, reset submit button so form can be submited again
				resetSubmit(instance);
				return false;
			}
			
			return true;
		});
	}
	
	//have to be at the end because other functions have to declared
	$.fn.valform = function (parameters) { //protyping object to have valform method
		this.each(function () {
			var newInstance = new instance(this, parameters);
			createSubmitWait(newInstance);
			setEvents(newInstance);
		});
	};
	
	var validators = {
		'val-num': function (field) {
			if (field.value.match(/(^-?\d\d*\.\d*$)|(^-?\d\d*$)|(^-?\.\d\d*$)/) || field.value == '') {
				return false;
			} 
			else {
				return 'needs to be a number.';
			}
		}
		
		, 'val-req': function (field) {
			var fieldType = field.type.toLowerCase();
			if (fieldType == 'checkbox' || fieldType == 'radio') {
				var values = $('[name="' + field.name + '"]', this.form);
				for(var i = 0; i < values.length; i++) {
					if (values[i].checked) {
						return false;	
					}
				}
			}  
			else if (field.value.length != 0) {
				return false;
			} 
			
			return 'is required.';
		}
		
		// Compare with default value (ex. fields without labels)
		, 'val-default': function (field) {
			if (field.value == field.defaultValue) {
				return 'is required.';	
			}
			return false;
		}
		
		, 'val-min-len': function (field) {
			var minLen = classAfter(field, 'val-min-len');
			if (field.value.length < parseFloat(minLen) && field.value != '') {
				return 'must be at least ' + minLen + ' characters long.';
			}
			else{
				return false;	
			}
		}
		
		, 'val-max-len': function (field) {
			var maxLen = classAfter(field, 'val-max-len');
			if (field.value.length > parseFloat(maxLen) && field.value != '') {
				return 'must be at most ' + maxLen + ' characters long.';
			}
			else{
				return false;	
			}
		}
		
		, 'val-max-num': function (field) {
			var maxNum = classAfter(field, 'val-max-num');
			if (!isNaN(field.value) && field.value > parseFloat(maxNum)) { 
				return 'must be ' + maxNum + ' or less.';
			}
			else{
				return false;	
			}
		}
		
		, 'val-min-num': function (field) {
			var minNum = classAfter(field, 'val-min-num');
			if (!isNaN(field.value) && (field.value < parseFloat(minNum)) && field.value != '') {
				return 'must be ' + minNum + ' or greater.';
			}
			else{
				return false;	
			}
		}
		
		, 'val-len': function (field) {
			var len = classAfter(field, 'val-len');
			if (field.value.length != parseFloat(len) && field.value != '') {
				return 'must be ' + len + ' characters long.';
			}
			else{
				return false;	
			}
		}
		, 'val-email': function (field) {
			if (field.value.match(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})$/) || field.value == '') {
				return false;
			} 
			else {
				return 'is not a valid email address.';
			}
		}
		, 'val-same': function (field, field2) {
			var field2 = classAfter(field, 'val-same');
			var field2Obj = $('#' + field2)[0];
			if (!field2Obj) {
				alert('val_same: ' + field2 + ' is not defined');
				return true;
			}
			if (field.value != field2Obj.value && field2Obj.value != '') {
				var field2Label = $('label[for=' + field2Obj.id + ']', this.form)[0].innerHTML;
				field2Label = field2Label.replace(/^<em>\*<\/em>|<span class="tip">.+<\/span>/gi, '');
				field2Label = $.trim($('<tag>' + field2Label + '</tag>').text()); // .text() will only work when string starts with html tag
				field2Label = field2Label.replace(/:$/gi, ''); // remove ending colon
				return 'does not match ' + field2Label + '.';
			}
			return false;
		}
	};
})(jQuery); //pass jQuery object into function
