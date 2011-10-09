/*<script>*/
var Events = {
	init: function(){
		var bodyElement = $('body')[0]; // cant use document.body because error in IE7, no .select
		this.confirm(bodyElement);	
		this.maxLenNext(bodyElement);
		this.addressFields();
		this.captchaRefresh(bodyElement);
		
		// Span nav parents
		$("ul#nav li.parent > span").click(function() {
			$(this).closest('li.parent').toggleClass('hover');
		});
	}
	
	, confirm: function(container){
		$('a[rel~="confirm"]', container).click(function() {
			//event.preventDefault();
			var url = $(this)[0].href;
			var message = $(this)[0].title;
			var target = $(this)[0].target;
			if (target == '_blank') { // open link in new window
				confirm2(null, message, 'window.open(\'' + url + '\')'); 
			}
			else {
				confirm2(null, message, 'window.location = \'' + url + '\';'); 
			}
			return false;
	
		});	
	}
	
	//focus specified field when max length has been entered, good for phone numbers
	, maxLenNext: function(container){
		$('input[type="text"].max-length-next', container).keyup(function(event){
			if(event.keyCode == 9){ //ignore tabs
				return;	
			}
			if(this.value.length == this.maxLength){
				var focusid = classAfter('max-length-next', this.className);
				$('#' + focusid).focus();
			}
		});
	}
	
	, captchaRefresh: function(container){
		$('p.new-image a.captcha-hint', container).click(function() {
			var inputId = getHash(this.href);
			var input = $('#' + inputId);
			
			if (input) {
				var form = input.closest('form');
				var formId = $(form).attr('id');
				
				refreshCaptcha(formId);
			}
			
			return false;
		});	
	}
	
	, addressFields: function() {
		// Input names
		var provinceName = 'province';
		var stateName = 'stateid';
		var countryName = 'countryid';
		var zipName = 'zip';
		
		//comment out because auto trigger below should handle it
		//$('.emg-form input[name="' + provinceName + '"], .emg-form input[name^="' + provinceName + '["]').closest('li').hide();
		
		// Account for names and name arrays
		$('.emg-form select[name="' + countryName + '"], .emg-form select[name^="' + countryName + '["]').change(function() {
			// Id strings
			var provinceId = this.id.replace(countryName, provinceName);
			var stateId = this.id.replace(countryName, stateName);
			var zipId = this.id.replace(countryName, zipName);
			
			// Input fields
			var provinceInput = $('#' + provinceId);
			var stateSelect = $('#' + stateId);
			var zipInput = $('#' + zipId);
			
			// Check if state & zip are initially required
			var stateIsRequired = stateSelect.hasClass('val_req');
			var zipIsRequired = zipInput.hasClass('val_req');
			
			// Element containers
			var provinceContainer = $('#' + provinceId + '-container');
			var stateContainer = $('#' + stateId + '-container');
					
			// 1 => United States
			// Require state/zip if they are initially required
			if (this.value == 1) {
				provinceContainer.hide();
				if (stateIsRequired) {
					stateSelect.addClass('val_req');
				}
				//stateSelect.addClass('val_req'); // if country is US, require state
				
				stateContainer.show();
				
				zipInput.addClass('val_exist zip');
				if (zipIsRequired) {
					zipInput.addClass('val_req');
				}
				//zipInput.addClass('val_req'); // if country is US, require zip
				
			}
			else {
				stateContainer.hide();
				stateSelect.removeClass('val_req');
				
				provinceContainer.show();
				
				zipInput.removeClass('val_req val_exist zip');
			}
		});
		
		//set based on default value
		var ul = $('.emg-form input[name="' + provinceName + '"], .emg-form input[name^="' + provinceName + '["]').closest('ul.fields');
		var countryField = $('[name="' + countryName + '"], [name^="' + countryName + '["]', ul);
		countryField.trigger('change');
	}
}