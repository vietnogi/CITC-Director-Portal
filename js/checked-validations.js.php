/*<script>*/
(function ($) { //anonymous function to prevent global scope, "$" is a prototype reference	
	function setEvents (self) {
		$self = $(self);
		$self.bind('submit', function () {
			var option = $self.metadata();
			option = $.extend(true, {
				'groups': []
				, 'success_submit': true
			}, option);
			
			var success = false;
			$.each(option.groups, function (i, group) {
				group = $.extend({
					'inputs': null
					, 'failed_msg': null
					, 'place_at': null
					, 'place_position': 'after'
					, 'check_type': null
					, 'check_value': null
					, 'if_checked' : null
				}, group);
				
				// uniqueClass is used to referene the error, have to remove [ or ] due to jquery error
				var uniqueClass =  'checked-validation-' + $self[0].id + '-' + i;
				
				// remove any existing errors and add a new one if needed
				$('.' + uniqueClass).remove(); // can not use $self scope because the error can be addd anymore
				
				// handle if_checked
				if (group['if_checked'] && !$(group['if_checked']).is(':checked')) {
					// dont validate if not checked
					return;
				}
				
				// count the number of checked
				var checked = 0;
				$(group['inputs'], $self).each(function () {
					if (this.checked) {
						checked++;	
					}
				});
				
				// validations
				switch (group['check_type']) {
					case 'min':
						success = (group['check_value'] <= checked);
					break;
					case 'max':
						success = (group['check_value'] >= checked);
					break;
					case 'len':
						success = (group['check_value'] == checked);
					break;
					default:
						console.error('Invalid type: ' + group['check_type']);
				}
				
				if (!success) {
					// error, show msg
					$(group['place_at'])[group['place_position']]($(group['failed_msg']).addClass(uniqueClass));
				}
			});
			
			var errors = $('[class^="checked-validation-' + $self[0].id + '-"]');
			if (errors.length > 0) {
				// focus on first error
				$('body').scrollTo(errors[0], 200);	
			}
	
			$self.trigger( success ? 'checked-validation-success' : 'checked-validation-failed');	

 			return (success && option.success_submit) ? true : false;
		});
	}
	
	$.fn.checkedValidation = function () { // protyping object to have valform method
		this.each(function () {
			setEvents(this);
		});
		return this; // keep jquery strategy
	};
})(jQuery); // pass jQuery object into function