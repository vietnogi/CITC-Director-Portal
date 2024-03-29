/*<script>*/

(function ($) { //anonymous function to prevent global scope, "$" is a prototype reference
	// Remove <em>*</em>, tooltip
	var labelCleanRegExp = /<em>\*<\/em>|<span class="hint">.+<\/span>|<span class="tip">.+<\/span>/gi;
	var colonRegExp = /:$/gi;
	
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
	function getLabel (field) {
		var form = $(field).parents('form');

		// check for combo
		var idAsClass = classAfter(field, 'combo');
		var labelFor = idAsClass ? idAsClass : field.id;
	
		// Only get first label linked to input
		var label = $('label[for=' + labelFor  + ']', form).first();
		if (label.length == 0) {
			return undefined;	
		}
		
		return label[0];
	}
	
		
	function getLabelText (field, label) {
		var option = $(field).metadata();
		if (option.label !== undefined) { // text base on meta data
			return option.label;
		}
		if (label != undefined) { // text base on label element
			var msg = $(label).html();
			// Remove <em>*</em>, tooltip
			msg = msg.replace(labelCleanRegExp, '');
			// Strip tags
			msg = $('<tag>' + msg + '</tag>').text(); // .text() will only work when string starts with html ta
			// Trim whitespace and ending colon
			msg = $.trim(msg);
			msg = msg.replace(colonRegExp, '');
			return msg;
		}
		
		//no label, try to use default value
		return field.defaultValue;
	}
	
	
	function validate (instance, field) {
		var classStr = $(field).attr('class');
		
		if (classStr == null) { // ignore classless fields
			return true;
		}
		
		if (!$(field).is(':visible')) { // ignore invisible fields
			return true;	
		}
		
		var classes = classStr.split(/\s+/);
		
		// value manipulation
		if (field.value && field.type.toLowerCase() != 'file') { // security error for file inputs
			field.value = $.trim(field.value); // auto strip whitespaces
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
		var label = getLabel(field);
		var errorMsg = getLabelText(field, label) + ' ' + error;
		var classes = $(field).attr('class').split(/\s+/);
		var errorid = (label === undefined) ? field.id : $(label).attr('for');
		errorid += '-error';
		
		// combo
		var index = $.inArray('combo', classes);
		if (index != -1) {
			// make sure related field has not error yet
			var relatedFields = $(instance.errorContainerTag + '[id="' + errorid + '"]');
			if (relatedFields.length != 0) {
				return true;	
			}
		}
		
		if (!instance.hideErrors) {
			var html = '<' + instance.errorContainerTag + ' id="' + errorid + '" class="' + instance.errorContainerClass + '">' + errorMsg + '</' + instance.errorContainerTag + '>';
			// check to place error after a diferent element
			var idAsClass = classAfter(field, 'error-after');
			var targetElement = idAsClass ? $('#' + idAsClass) : field;
			$(html).insertAfter(targetElement);
		}
		
		$(field).addClass(instance.errorClass);
		
		if (label !== undefined) {
			$(label).addClass(instance.errorClass);
		}
		
		return true;
	}
	
	function clearError (instance, field) {
		var label = getLabel(field);
		
		var errorid = (label === undefined) ? field.id : $(label).attr('for');
		errorid += '-error';
		var $error = $('#' + errorid);
		
		if ($error.length > 0) {
			$error.remove();
		}
		$(field).removeClass(instance.errorClass);
		$(label).removeClass(instance.errorClass);
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
				$(this).trigger('valform-failed');
				return false;
			}
			
			$(this).trigger('valform-success');
			
			return true;
		});
		
		$(instance.form).bind('resetSubmit', function() {
			resetSubmit(instance);
		});
		
	}
	
	//have to be at the end because other functions have to declared
	$.fn.valform = function (parameters) { //protyping object to have valform method
		this.each(function () {
			var newInstance = new instance(this, parameters);
			createSubmitWait(newInstance);
			setEvents(newInstance);
		});
		return this; // keep jquery strategy
	};
	
	var validators = {
		'numeric': function (field) {
			if (field.value.match(/(^-?\d\d*\.\d*$)|(^-?\d\d*$)|(^-?\.\d\d*$)/) || field.value == '') {
				return false;
			} 
			else {
				return 'needs to be a number.';
			}
		}
		
		, 'int': function (field) {
			if (field.value.match(/^[\d\-,]*$/) || field.value == '') {
				return false;
			} 
			else {
				return 'needs to be a whole number.';
			}
		}
		, 'req': function (field) {
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
		, 'default': function (field) {
			if (field.value == field.defaultValue) {
				return 'is required.';	
			}
			return false;
		}
		
		, 'min-len': function (field) {
			var minLen = classAfter(field, 'min-len');
			if (field.value.length < parseFloat(minLen) && field.value != '') {
				return 'must be at least ' + minLen + ' characters long.';
			}
			else{
				return false;	
			}
		}
		
		, 'max-len': function (field) {
			var maxLen = classAfter(field, 'max-len');
			if (field.value.length > parseFloat(maxLen) && field.value != '') {
				return 'must be at most ' + maxLen + ' characters long.';
			}
			else{
				return false;	
			}
		}
		
		, 'max-num': function (field) {
			var maxNum = classAfter(field, 'max-num');
			if (!isNaN(field.value) && field.value > parseFloat(maxNum)) { 
				return 'must be ' + maxNum + ' or less.';
			}
			else{
				return false;	
			}
		}
		
		, 'min-num': function (field) {
			var minNum = classAfter(field, 'min-num');
			if (!isNaN(field.value) && (field.value < parseFloat(minNum)) && field.value != '') {
				return 'must be ' + minNum + ' or greater.';
			}
			else{
				return false;	
			}
		}
		
		, 'len': function (field) {
			var len = classAfter(field, 'len');
			if (field.value.length != parseFloat(len) && field.value != '') {
				return 'must be ' + len + ' characters long.';
			}
			else{
				return false;	
			}
		}
		, 'email': function (field) {
			if (field.value.match(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})$/) || field.value == '') {
				return false;
			} 
			else {
				return 'is not a valid email address.';
			}
		}
		, 'same': function (field, field2) {
			var field2 = classAfter(field, 'same');
			var field2Obj = $('#' + field2)[0];
			if (!field2Obj) {
				alert('val_same: ' + field2 + ' is not defined');
				return true;
			}
			if (field.value != field2Obj.value && field2Obj.value != '') {
				var field2Label = getLabel(field2Obj);
				return 'does not match ' + field2Label.msg + '.';
				
			}
			return false;
		}
		, 'phone': function (field) {
			if (field.value == '') {
				return false;	
			}
			var numbers = field.value.replace(/[^0-9]/g, ''); //remove all non numerics
			if (numbers.length < 10) {
				return 'needs to be 10 digits.';	
			}
			field.value = numbers.substr(0, 3) + '-' + numbers.substr(3, 3) + '-' + numbers.substr(6, 4);
			// handle extensions
			if (numbers.length > 10) {
				field.value += ' x ' + numbers.substr(10);
			}
			return false;
		}
		, 'date': function (field) {
			if (field.value == '') {
				return false;	
			}
			else if (field.value.match(/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/)) {
				//make sure date is valid
				var dateParts = field.value.split('/');
				var day = dateParts[1];
				var month = dateParts[0];
				var year = dateParts[2];
				var dteDate = new Date(year, month - 1, day);
				if (day == dteDate.getDate() && (month == dteDate.getMonth() + 1) && year == dteDate.getFullYear()) {
					return false;
				}
				return 'is an invalid date.';
			} 
			else {
				return 'needs to be mm/dd/yyyy.';
			}
		}
		, 'money': function (field) {
			field.value = field.value.replace(/[^0-9\-\.]/g, '');
			if (field.value == '') {
				return;	
			}
			if (isNaN(field.value)) {
				formated = '0.00';
			}
			else {
				var formated = Math.round(field.value * 1000) / 1000; //1000 for partial cents
				formated = formated.toString();
				if (formated.indexOf('.') == -1) {
					formated += '.00';
				}
				else {
					var parts = formated.split('.');
					if (parts[1].length == 1) {
						formated += '0';	
					}
				}
			}
			field.value = formated;
		}
		, 'decimal': function(field, precision) {
			var precision = classAfter(field, 'decimal');
			field.value = field.value.replace(/[^0-9\-\.]/g, '');
			if (field.value == '') {
				return;	
			}
			if (isNaN(field.value)) {
				formated = '0.00';
			}
			else {
				var formated = Math.round(field.value * Math.pow(10, precision)) / Math.pow(10, precision);
				formated = formated.toString();
				if (formated.indexOf('.') == -1) {
					formated += '.00';
				}
				else {
					var parts = formated.split('.');
					if (parts[1].length == 1) {
						formated += '0';	
					}
				}
			}
			field.value = formated;
		}
	};
})(jQuery); //pass jQuery object into function
