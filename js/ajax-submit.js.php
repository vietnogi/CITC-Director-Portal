/*<script>*/
(function ($) { //anonymous function to prevent global scope, "$" is a prototype reference

	function uploadProgress (form) {
		// handle file upload progress
		var uploadId = $('input[name="UPLOAD_IDENTIFIER"]', form);
		if (uploadId.length == 0) {
			return;
		}
		
		// create progress bar and place into modal window
		var progressModal = $('<div id="progress-bar-' + uploadId[0].value + '" style="width: 100px"></div>').progressbar({ value: 0 }).modal({ event: null });
		
		// bind a custom event so we can trigger when to check for progress
		progressModal.bind('check-progress').bind(function () {
			var progressModal = $(this);
			$.ajax({
				url: window.CR + '/ajax/upload-progress?upload_id' + uploadId[0].value
				, type: method
				, dataType: 'json'
				, cache: false
				, success: function (data, textStatus, jqXHR) {
					data.percentage = parseInt(data.percentage);
					$('#progress-bar-' + uploadId[0].value).progressbar('option', 'value', data.percentage);
					if (!(data.percentage < 100)) {
						// finish
					}
					else {
						// set the next check
						setTimeout(function () {
							progressModal.trigger('check-progress');
						}, 500);
					}
				} 
				, complete: FW.ajaxComplete
				, error: FW.error
			});
		});
		
		progressModal.trigger('check-progress');
	}
	
	
	//have to be at the end because other functions have to declared
	$.fn.ajaxSubmit = function () { //protyping object to have valform method
		this.each(function () {
			var bindEvent = this.action !== undefined ? 'submit' : 'click';
			
			// handle valform
			if (bindEvent == 'submit') {
				var events = $(this).data('events');
				if (events !== undefined) {
					if (events.resetSubmit !== undefined) {
						// bind valform-success instead of submit
						bindEvent = 'valform-success';
						$(this).bind('submit', function() {
							return false;
						});
					}
				}
			}
			
			$(this).bind(bindEvent, function() {
				var $self = $(this);
				var isForm = (this.action !== undefined) ? true : false;
				
				if (isForm) {
					var method = this.method;
					var data = $self.serialize();
					var url = this.action;
				}
				else {
					// link or other dom element
					if (this.href !== undefined) {
						var url = this.href;
					}
					else {
						var option = $self.metadata();
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
						if (data !== null && data.msg !== undefined) {
							alert(data.msg);
						}
						$self.trigger('ajaxSubmitSuccess');
					} 
					, complete: FW.ajaxComplete
					, error: FW.error
				});
				
				// upload progress
				if (isForm) {
					uploadProgress(this);
				}
				
				return false;
			});
		});
		
		return this; // keep jquery strategy
	};
})(jQuery); //pass jQuery object into function