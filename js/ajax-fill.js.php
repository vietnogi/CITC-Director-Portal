/*<script>*/

(function ($) { //anonymous function to prevent global scope, "$" is a prototype reference	
	function fill (container) {
		$container = $(container);
		$container.empty();
		// show loading graphic
		$('<p><img src="' + FW.CR + '/images/library/loading.gif" /></p>').appendTo(container);
		var option = $container.metadata();
		$.ajax({
			url: option.url
			, type: 'get'
			, dataType: 'text'
			, cache: false
			, success: function (data, textStatus, jqXHR) {
				var $container = $(container);
				$container.empty();
				$(data).appendTo(container);
				
				// set universal stuff
				Markup(container);
				Events(container);
				
				// handle ajax submit refresh
				$('form', container).bind('ajaxSubmitSuccess', function () {
					fill(container)
				});
				
				// trigger fill complete
				$container.trigger('fillSuccess');
			} 
			, complete: FW.ajaxComplete
			, error: FW.error
		});
	}
	
	function setEvents (container) {
		$container = $(container);
		$container.bind('refresh', function() {
			fill(this);
		});
	}
	
	//have to be at the end because other functions have to declared
	$.fn.ajaxFill = function () { //protyping object to have valform method
		this.each(function () {
			setEvents(this);
			fill(this);
		});
	};
})(jQuery); //pass jQuery object into function
