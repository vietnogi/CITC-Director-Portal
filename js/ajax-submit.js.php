/*<script>*/
/*
action page should always ouput a json with at least a success (boolean) property and a error (string) property
binded to the form's submit event and will return false to prevent the form from submitting
*/
(function ($) { //anonymous function to prevent global scope, "$" is a prototype reference

	function uploadProgress (form) {
		// handle file upload progress
		var hash = $('input[name="UPLOAD_IDENTIFIER"]', form);
		if (hash.length == 0) {
			return;
		}
				
		var fileUploadMeta = $('<div><p>Uploading: <span class="file-name"></span></p><p><span class="uploaded"></span>/<span class="file-size"></span></p></div>');
		
		// create progress bar and place into modal window
		var progressModal = $('<div id="progress-bar-' + hash[0].value + '" style="width: 100px"></div>').progressbar({ value: 0 });
		progressModal.appendTo(fileUploadMeta);
		
		// since check progress may occur before form with file is submitted
		var uploadStarted = false;
		var maxAttempts = 10;
		var attemptCount = 0;
		var checkProgress = function () {
			var progressModal = $(this);
			$.ajax({
				url: FW.CR + '/ajax/upload-progress?hash=' + hash[0].value + '&t=' + FW.TOKEN
				, dataType: 'json'
				, cache: false
				, success: function (data, textStatus, jqXHR) {
					//window.open(this.url);
					if (data === null) { // no longer uploading
						if (!uploadStarted) {
							if (maxAttempts == attemptCount) {
								console.error('Reached max attempts to check if file started uploading.');
								return;	
							}
							attemptCount++;
						}
						else {
							// upload complete, clean up stuff
							fileUploadMeta.remove();
							form = undefined;
							fileUploadMeta = undefined;
							uploadStarted = undefined;
							maxAttempts = undefined;
							attemptCount = undefined;
							progressModal = undefined;
							return;	
						}
					}
					else { // uploading
						if (!uploadStarted) {
							$('input[type="file"]', form).after(fileUploadMeta);
						}
						$('.file-name', fileUploadMeta).html(data.filename);
						$('.uploaded', fileUploadMeta).html(Math.round(parseInt(data.bytes_uploaded) / 1000));
						$('.file-size', fileUploadMeta).html(Math.round(parseInt(data.bytes_total) / 1000) + ' kb');
						data.percentage = parseInt(data.percentage);
						uploadStarted = true;
						$('#progress-bar-' + hash[0].value).progressbar('option', 'value', data.percentage);
					}
					// set the next check
					setTimeout(checkProgress, 200);
				} 
				, complete: FW.ajaxComplete
				, error: FW.error
			});
		};
		
		// cant fire trigger right away because form request may have not reached server yet
		checkProgress();
	}
	
	// create a temproary iframe to submit
	function submitToIframe(form) {
		var $form = $(form);
		var rand = Math.floor(Math.random() * 9999999);
		// create temproary iframe to target
		var iframe = $('<iframe name="iframe-target-' + rand + '"></iframe>');
		iframe.hide();
		iframe.appendTo('body');
		
		// add upload hash for progress
		var uploadHash = $('input[name="UPLOAD_IDENTIFIER"]');
		if (uploadHash.length == 0) {
			// * important, UPLOAD_IDENTIFIER must go before any file inputs in order for php progress to work
			$('<input type="hidden" name="UPLOAD_IDENTIFIER" value="' + rand + '" />').prependTo(form);
		}
		else {
			uploadHash[0].value = rand;
		}
		
		// target iframe and submit
		$form.attr({
			'target': 'iframe-target-' + rand
		});
		
		// add _ to prevent cache and flag as ajax
		var _action = $form.attr('action'); // need variable so we can set it back when submit is complete 
		$form.attr({
			'action': FW.addToUrlGet(_action, {
				'_' : new Date().getTime()
			})
		});
		form.submit();
		
		// remove iframe when submit completes
		iframe.load(function () {
			// handle iframe response
			try {
				var data = $.parseJSON($(this).contents().find('body').html());
				handleActionResponse($form, data);
			}
			catch (err) { // most likly 404
				alert('There was a problem submitting your form, please try again.');
				console.error('Unable to parse JSON for action: ' + _action);
			}
			// remove iframe
			$(this).remove();
			// set form back to action (w.out _)
			$form.attr({
				'action': _action
			});
			_action = undefined; // good to free memory if easy
		});
		
		// upload progress
		uploadProgress(form);
	}
	
	function handleActionResponse($form, data) {
		if (data.location !== undefined) { // redirect takes priority
			window.location.href = data.location;
		}
		else if (data.success !== undefined && data.success === 'true') {
			$form.trigger('ajax-submit-success', data);
		}
		else {
			// automatically alert error if providded
			if (data.error !== undefined && data.error !== null) {
				alert(data.error);	
			}
			$form.trigger('ajax-submit-fail', data);	
		}
	}
	
	function setEvents(form) {
		var bindEvent = form.action !== undefined ? 'submit' : 'click';
		var $form = $(form);
			
		// handle valform
		if (bindEvent == 'submit') {
			var events = $form.data('events');
			if (events !== undefined) {
				if (events.resetSubmit !== undefined) {
					// bind valform-success instead of submit
					bindEvent = 'valform-success';
					$form.bind('submit', function() {
						return false;
					});
				}
			}
		}
		
		$form.bind(bindEvent, function() {
			var isForm = (this.action !== undefined) ? true : false;
			
			if (isForm) {
				var method = this.method;
				if ($form.attr('enctype').toLowerCase() == 'multipart/form-data') {
					// require file upload, resort to iframe	
					submitToIframe(this);
					return;
				}
				var data = $form.serialize();
				var url = this.action;
			}
			else {
				// link or other dom element
				if (this.href !== undefined) {
					var url = this.href;
				}
				else {
					var option = $form.metadata();
					if (option.href === undefined) {
						alert('Can not find href.');
						return false;
					}
					var url = option.href;
				}
				var method = 'get';
			}
			
			$.ajax({
				url: url
				, type: method
				, dataType: 'json'
				, data: data
				, cache: true
				, success: function (data, textStatus, jqXHR) {
					handleActionResponse($form, data);
				} 
				, complete: FW.ajaxComplete
				, error: FW.error
			});
			
			// upload progress
			if (isForm) {
				uploadProgress(this);
			}
		});		
	}
	
	
	//have to be at the end because other functions have to declared
	$.fn.ajaxSubmit = function () { //protyping object to have valform method
		this.each(function () {
			setEvents(this);
		});
		
		return this; // keep jquery strategy
	};
})(jQuery); //pass jQuery object into function