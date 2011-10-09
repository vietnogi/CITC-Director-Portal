/*<script>*/
var Enrollment = function () {
	// Billing accordions
	var billingSelect = new Tabbed({
		navClass: 'ul.accordion.billing'
		, currentClass: 'expanded'
		, tabbedClass: 'collapsed'
		, toggle: false
		, noDefaultCurrent: true
		, callback: function (li) {
			$('#' + li[0].id + '-input')[0].checked = true;
		}
	});
	
	// enrollment billing form
	$('#credit-card-info-form').submit(function(){
		var form = this;
		//var url = form.action;
		var url = FW.CR + '/action/customer/save-credit-card?t=' + FW.TOKEN;
		
		// submit the form with ajax
		$.ajax({
			   url: url
			   , type: 'post'
			   , data: $(form).serialize()
			   , dataType: 'text'
			   , success: function(data, textStatus, jqXHR){
					try {
						var result = $.parseJSON(data);
						if (result.status == 'Bad') { // error saving cc
							alert(result.error);
							$(form).trigger('resetSubmit');
							return;
						}
						else { // redirect to billing action
							window.location = form.action + '&customer_credit_card_id=' + result.customer_credit_card_id;
						}
					} catch (err) {
						alert('Error parsing JSON: ' + err.description + ' JSON: ' + data + ' URL : ' + url);
						$(form).trigger('resetSubmit');
						return;
					}
				 }
			   , complete: FW.ajaxComplete
			   , error: FW.ajaxError
			   , cache: false
		});
	
		return false;
	});
	
};