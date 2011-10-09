/*<script>*/
function siteInit() {
	var sessionSelect = new Tabbed({navClass: 'ul.accordion.session-specialties'
									, currentClass: 'expanded'
									, tabbedClass: 'collapsed'
									, toggle: true
									});
	
	//handle parent pack transportation accordions
	var transSessAccordions = $('ul[id*="accordion-session-"]').each( function(index, value) {
		var navClass = 'ul.' + $(this).attr('class').replace(/ /g, '.');
		var transportationSelect = new Tabbed({navClass: navClass
									, currentClass: 'expanded'
									, tabbedClass: 'collapsed'
									, toggle: false
									, noDefaultCurrent: true
									, callback: function(li){
										//checks hidden radio for location
										$('#' + li[0].id + '-input')[0].checked = true;
										
										//if location has only one method, auto check it
										var methodInputs = $('#' + li[0].id + ' input.transportation-method-choice');
										if(methodInputs.length == 1){
											//simulate as if user clicked it
											methodInputs[0].checked = true;
											methodInputs.trigger('click');
										}		
									}
									});
	});
	
	// Billing accordions
	var billingSelect = new Tabbed({navClass: 'ul.accordion.billing'
								, currentClass: 'expanded'
								, tabbedClass: 'collapsed'
								, toggle: false
								, noDefaultCurrent: true
								, callback: function(li){
									$('#' + li[0].id + '-input')[0].checked = true;
								}
								});
	
	ppAddFriendHandler();
	
	// Append &raquo; (>>) for enrollment submit buttons
	$('.enrollment input[type="submit"]:not([class~="secondary"],[class~="hidden"]), .parent-pack input[type="submit"]:not([class~="secondary"],[class~="hidden"])').val(function(i, val) {
		return val + ' \xBB';
	});
}

function setSiteEventHandlers(container){
	$('#payment-option-pay-in-full').click(function() {
		updateBalances();
		// remove deposit amount requirement
		$('#payment-option-deposit-amount').removeClass('val_req');
		
		//byId('payment-option-deposit-amount').value = '';
	});
	
	$('#payment-option-pay-deposit').click(function() {
		updateBalances();
		// add deposit amount requirement
		if (!$('#payment-option-deposit-amount').hasClass('val_req')) {
			$('#payment-option-deposit-amount').addClass('val_req');
		}
	});
	
	$('#payment-option-deposit-amount').blur(function() {
		updateBalances(true);
	});
	
	// hide cc fields if needed
	if ($('#credit-card-fields')[0] != null && $('#credit-cardid-new')[0] != null) {
		setBillingInfoReq('credit-card-fields', false);
		$('#credit-card-fields').hide(); 
	}
	
	// hide credit card options if needed
	if ($('a.use-different-credit-card')[0] != null && $('div.credit-card-options')[0] != null) {
		$('div.credit-card-options').hide(); 
	}
	
	/*$('a.use-different-credit-card').click(function() {
		if ($(this).hasClass('cancel')) { //hide cc options
			$('div.credit-card-options').hide();
			$(this).text('Use Different Credit Card');
			$(this).removeClass('cancel');
			
			// disable billing requirements
			$('#credit-card-fields').hide(); //hide cc fields
			setBillingInfoReq('credit-card-fields', false);
			$('input.payment-method').removeAttr('checked'); // uncheck payment methods
		}
		else {
			$('div.credit-card-options').show(); //show cc options
			$(this).addClass('cancel');
			$(this).text('Cancel');
			$('input.payment-method')[0].checked = true;
		}
		return false;
	});*/
	//$('a[href="#credit-cardid-new-details"]').click(function() {
	$('ul.billing.accordion a.header').click(function() {
		if (getHash(this.href) == 'credit-cardid-new-details') {
			$('#credit-card-fields').show();
			setBillingInfoReq('credit-card-fields', true);
		}
		else {
			$('#credit-card-fields').hide();
			setBillingInfoReq('credit-card-fields', false);
		}
		
		return false;
	});
	
	$('input.payment-method').click(function() {
		if (this.value == 'new-credit-card') {
			$('#credit-card-fields').show(); //show cc fields
			setBillingInfoReq('credit-card-fields', true);
		}
		else {
			$('#credit-card-fields').hide(); //hide cc fields
			setBillingInfoReq('credit-card-fields', false);
		}
	});
	
	//show proper transportation information and form when user selects a method
	$('input[class*="transportation-method-choice"]').click(function() {
		//get sessionid from id
		var parts = this.id.split('-');
		var sessionid = parts[0];
		
		//reset
		$('.' + sessionid + '-transportation-information').hide();
		$('.' + sessionid + '-transportation-information input').removeClass('val_req');
		
		//show the one selected
		$('#' + this.id + '-information').show();
		$('#' + this.id + '-information input[type!="checkbox"]').addClass('val_req'); //checkbox (#   Camper is traveling as an unaccompanied mino) is not required
	});
	
	$('ul.session-specialties > li > a.header').click(function() {
		if (!$(this).hasClass('expanded')) {
			$('input[type="radio"]', $(this).parent()).each(function() {
				this.checked = false;
			});
		}
	});
}

function setBillingInfoReq(parentid, req){
	var reqFields = new Array('first_name'
							  , 'last_name'
							  , 'address'
							  , 'city'
							  , 'countryid'
							  , 'zip'
							  , 'credit_card_type'
							  , 'credit_card_number'
							  , 'expiration_month'
							  , 'expiration_year'
							  );
	
	var countryid = $('#' + parentid + ' select[name="countryid"]')[0].id;
	if (byId(countryid).value == '1' || byId(countryid).value == '') { // 1 => United States
		reqFields[reqFields.length] = 'stateid';
	}
	else {
		reqFields[reqFields.length] = 'province';
	}
	
	var ccvName = 'credit_card_verification_code';
	var ccvInput = $('#' + parentid + ' input[name="' + ccvName + '"]')[0];
	if (ccvInput != null) { // security code may not be required
		reqFields[reqFields.length] = ccvName;
	}
	
	$('#' + parentid + ' input, #' + parentid + ' select').each(function() {
		if (req && inArray(this.name, reqFields)) {
			$(this).addClass('val_req');
		}
		else {
			$(this).removeClass('val_req');
		}
	});
}

function ppAddFriendHandler() {
	$('.emg-form p.add-friend a').live('click', ppAddFriend);
}

function ppAddFriend() {
	this.blur();
	
	var thisLi = $(this).closest('li');
	var newLi = thisLi.clone();
	
	// Update ids
	newLi.children().each(function() {
		if (this.id) {
			var parts = this.id.split('-');
			parts[parts.length - 1] = parseInt(parts[parts.length - 1]) + 1;
			this.id = parts.join('-');
		}
		else if ($(this).is('label')) {
			var parts = this.innerHTML.split(' ');
			parts[parts.length - 1] = parseInt(parts[parts.length - 1]) + 1;
			this.innerHTML = parts.join(' ');
		}
		this.value = '';
	});
	
	$('p.add-friend a', thisLi).remove();
	
	thisLi.after(newLi);
	
	return false;
}

function valCC(ccFieldid){
	var ccNumInput = $('#' + ccFieldid)[0];
	var ccForm = ccNumInput.form;
	var ccTypeInput = $('#' + ccForm.id + ' select[name="credit_card_type"]')[0];
	
	if (ccTypeInput.value == '') { // no cc type, let cc type req validation take over
		return false;
	}
	
	if (ccNumInput.value.length == 0) { // no cc, let req validation take over
		return false;
	}
	
	if (checkCreditCard(ccNumInput.value, ccTypeInput.value)) { //valid cc
		return false;
	}
	else { // invalid cc
		return " is invalid.";
	}
}


function updateBalances(depositField){
	var url = window.CR + '/ajax/account/enrollment/get-balances';
	
	var payingDeposit = byId('payment-option-pay-deposit').checked;
	
	if (depositField && !payingDeposit) {
		return;
	}
	
	if (!payingDeposit) {
		url += '?pay-in-full=1';
	}
	else {
		var depositAmount = $('#payment-option-deposit-amount')[0].value;
		url += '?deposit-amount=' + encodeURIComponent(depositAmount);
	}
	
	var callBackComplete = function(ajaxReturn) {
		if (ajaxReturn.responseText == 'died') {
			window.location = window.CR + '/died';
			return;
		}
		else if (ajaxReturn.responseText == '0') {
			
		}
		else {
			var balances = $.parseJSON(ajaxReturn.responseText);
			$('#amount-being-paid').text(moneyFormat(balances['amount-being-paid']));
			$('#remaining-balance').text(moneyFormat(balances['remaining-balance']));
		}
		
	}
	
	var callBackFailed = function(ajaxReturn){
		return false;
	};
	
	$.ajax({
		   url: url
		   , type: 'get'
		   , dataType: 'text'
		   , error: callBackFailed
		   , complete: callBackComplete
		   , cache: false
	}); 
	
}
/*
function validateDeposit(fieldid){
	var payingDeposit = byId('payment-option-pay-deposit').checked;

	if (payingDeposit) {
		var url = window.CR + '/ajax/account/enrollment/get-balances';
		var depositAmount = $('#' + fieldid)[0].value;
		url += '?deposit-amount=' + encodeURIComponent(depositAmount);
			
		var valFormIndex = getValFormIndex(fieldid);
		valForms[valFormIndex].ajaxRunning[fieldid] = true;
		$.ajax({
			   url: url
			   , type: 'get'
			   , dataType: 'text'
			   , cache: false
			   , complete: function(ajaxReturn) {
								
								if (ajaxReturn.status == '404') { // page not found
									window.location = window.CR + '/error';
									return;
								}
								
								var response = ajaxReturn.responseText;
								var error = (response == '0') ? ' bad.' : false;
								
								valForms[valFormIndex].errorHandler($('#' + fieldid)[0], error);
								valForms[valFormIndex].ajaxRunning[fieldid] = false;
							}
			   });
	}
}
*/
function checkSessionInformation(){
	var chosen = false;
	$('#session-information-form input[type="radio"]').each(function() {
		if(this.checked){
			chosen = true;
			return;
		}
	});
	
	if(!chosen){
		alert('Please select a session(s).');	
	}
	
	return chosen;
}

function ppTransSubmitCheck(){
	//check if a location was chosen for all arrivals and departure
	var chosen = true;
	var transSessAccordions = $('ul[id*="accordion-session-"]').each( function(index, value) {
		if($('li.expanded', this).length == 0 && chosen){
			chosen = false;
			alert('Please choose: ' + this.id);
		}
	});
	
	return chosen;
}
