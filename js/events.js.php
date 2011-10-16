/*<script>*/
var Events = function (container) {

	
	function addressFields() {
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
	
	// default container
	container = container || $('body')[0]; 
	
	// Span nav parents
	$("ul#nav li.parent > span").click(function() {
		$(this).closest('li.parent').toggleClass('hover');
	});
	
	//val form
	$('form.val-form', container).valform({abc: 123, xyz: 'abc'});
	
	// row clicks
	$('tr[href]', container).click(function () {
		window.location = $(this).attr('href');
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
	
	// address fill
	$('.ajax-fill', container).ajaxFill();
	
	// ajax submit
	$('.ajax-submit', container).ajaxSubmit();
	
	// modal
	$('.modal', container).modal();
	
	addressFields();
};