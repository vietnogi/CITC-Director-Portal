/*<script>*/
var Registration = function () {
	//create account form, check if email exists
	$('#create-account-form').submit(function() {
		var field = $('#create-account-email');
		if (field.length == 0) {
			alert('#create-account-email field is missing.');	
		}
		var email = field[0].value;
		if (email == '') {
			return false;
		}
		var url = FW.CR + '/ajax/check-exist/email?check-value=' + email;
		var form = this; //so ajax functions can use
		$.ajax({
			url: url
			, type: 'get'
			, dataType: 'text'
			, cache: false
			, success: function (data, textStatus, jqXHR) {
				if (data == '1') {
					alert('The email you have entered is already in use.');
					field.focus();
					$(form).trigger('resetSubmit');
				}
				else{
					//check if there are any errors
					var errors = $('.val_error', form);
					if (errors.length == 0) {
						form.submit();	
					}
				}
			} 
			, complete: FW.ajaxComplete
			, error: FW.ajaxError
		});
		return false;
	});	
}