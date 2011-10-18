/*<script>*/

(function ($) { //anonymous function to prevent global scope, "$" is a prototype reference	
	function fill (container) {
		var $container = $(container);
		var option = $container.metadata();
		
		// show loading graphic
		// maintain container height before empty
		$container.height($container.height());
		$container.empty();
		$('<p><img src="' + FW.CR + '/images/library/loading.gif" /></p>').appendTo(container);
		
		$.ajax({
			url: option.href
			, type: 'get'
			, dataType: 'text'
			, cache: false
			, success: function (data, textStatus, jqXHR) {
				var $container = $(container);
				$container.empty();
				$(data).appendTo(container);
				// prevent overflow for new html in container
				$container.height('100%');
				
				// set universal stuff
				Markup(container);
				Events(container);
				
				// handle ajax submit refresh
				$('[class*="ajax-submit"]', container).bind('ajaxSubmitSuccess', function () {
					fill(container)
				});
				
				// trigger fill complete
				$container.trigger('fillSuccess');
			} 
			, complete: FW.ajaxComplete
			, error: FW.error
		});
	}
	
	// not sure if this is needed
	/*
	function setEvents (container) {
		$container = $(container);
		$container.bind('refresh', function() {
			fill(this);
		});
	}
	*/
	
	//have to be at the end because other functions have to declared
	$.fn.ajaxFill = function () { //protyping object to have valform method
		this.each(function () {
			//setEvents(this);
			fill(this);
		});
	};
})(jQuery); //pass jQuery object into function
