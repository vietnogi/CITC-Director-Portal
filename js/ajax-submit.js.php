/*<script>*/
(function ($) { //anonymous function to prevent global scope, "$" is a prototype reference
		
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
				var url, method, option, data = null, $self = $(this);
				
				if (this.action !== undefined) {
					// form
					method = this.method;
					data = $self.serialize();
					url = this.action;
				}
				else {
					// link or other dom element
					if (this.href !== undefined) {
						url = this.href;
					}
					else {
						option = $self.metadata();
						if (option.href === undefined) {
							alert('Can not find href.');
							return false;
						}
						url = option.href;
					}
					method = 'get';
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
				return false;
			});
		});
	};
})(jQuery); //pass jQuery object into function