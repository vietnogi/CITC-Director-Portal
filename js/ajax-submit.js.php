/*<script>*/
(function ($) { //anonymous function to prevent global scope, "$" is a prototype reference		
	//have to be at the end because other functions have to declared
	$.fn.ajaxSubmit = function () { //protyping object to have valform method
		this.each(function () {
			$(this).bind('submit', function() {
				var $form = $(this);
				$.ajax({
					url: this.action
					, type: this.method
					, dataType: 'text'
					, data: $(this).serialize()
					, cache: false
					, success: function (data, textStatus, jqXHR) {
						$form.trigger('ajaxSubmitSuccess');
					} 
					, complete: FW.ajaxComplete
					, error: FW.error
				});
				return false;
			});
		});
	};
})(jQuery); //pass jQuery object into function