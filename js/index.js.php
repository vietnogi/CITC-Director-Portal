/*
Copyright © 2008 Eckx Media Group, LLC. All rights reserved.
Eckx Media Group respects the intellectual property of others, and we ask our users to do the same.
*/
/*<script>*/
$(document).ready(function(){
	BrowserDetect.init();
	FW.init();
	Events.init();
	Markup.init();
	$('form.val-form').valform({abc: 123, xyz: 'abc'});
	
	//create account form, check if email exists
	$('#create-account-form').submit(function(){
		var field = $('#create-account-email');
		if(field.length == 0){
			alert('#create-account-email field is missing.');	
		}
		var email = field[0].value;
		if(email == ''){
			return false;
		}
		var url = FW.CR + '/ajax/check-exist/email?check-value=' + email;
		var form = this; //so ajax functions can use
		$.ajax({
			   url: url
			   , type: 'get'
			   , dataType: 'text'
			   , cache: false
			   , success: function(data, textStatus, jqXHR){
				    if(data == '1'){
						alert('The email you have entered is already in use.');
						field.focus();
					}
					else{
						//check if there are any errors
						var errors = $('.val_error', form);
						if(errors.length == 0){
							form.submit();	
						}
					}
				 } 
			   , complete: function(jqXHR, textStatus) {
			     	//nothing needs to be done
				 }
			   , error: function(jqXHR, textStatus, errorThrown){
				 	alert('Unable to validate email address becuase the following error occured: ' + errorThrown);	  
			     }
			   });
		return false;
	});
	
	/*
	EmgAjax.init($('body').first());
	loginTimer.init();
	
	modal.init();
	*/
});

/*
$.ajax({
		type: 'POST'
		, url: FW.CR + '/ajax/test'
		, data: {abc: '123', xyz: 'abc'}
		, success: function(data){
			alert('success');	
		  }
		, complete: function(httpObj, textStatus){
			switch(1 * httpObj.status){
				case 301:
				case 302:
				case 0: //for ie
					try {
						var data = $.parseJSON(httpObj.responseText);
						window.location.href = data.location;
					} catch (err) {
						alert('Error trying to redirect.');
					}
				break;
				case 404:
					alert("Page Not Found");
				break;
			}
		  }
	});
*/