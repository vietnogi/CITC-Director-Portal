/*<script>*/

(function ($) { //anonymous function to prevent global scope, "$" is a prototype reference		
	// create blind to hide background
	function openModal (option) {
		// set default options
		option = option || {};
		option = $.extend (true, {
			href: null
			, content: 'Loading...'
		}, option);
		
		// set z index according to previous blind, default to 100
		var previousBlind = $('.modal-blinds').last();
		var zIndex = previousBlind.length > 0 ? parseInt(previousBlind.css( 'zIndex' ), 10) * 2 : 100;
		
		// invisible container to center popup
		var outerContainer = $('<div class="modal-container"></div>');
		outerContainer.appendTo($('body'));
		
		// handle blinds/overlay
		var blind = $('<div class="modal-blinds"></div>');
		blind.hide();
		blind.appendTo($('body'));
		blind.css({
			'z-index': zIndex  
		});
		blind.show('fade', 200);
		
		// handle popup
		var popUp = $('<div class="modal-popUp"></div>');
		popUp.css({
			'z-index': zIndex + 1
		});
		popUp.appendTo(outerContainer);
		
		var closeModal = function () {
			outerContainer.remove();
			blind.hide('fade', 200, function() {
				$(this).remove;
			});
		};
		
		// handle close
		var closeAnchor = $('<a class="modal-close" title="Close">x</a>').click(closeModal);
		closeAnchor.appendTo(popUp);
		blind.click(closeModal);
		
		// handle content
		var contentContainer = $('<div class="modal-content"></div>');
		contentContainer.append(option.content);
		contentContainer.appendTo(popUp);
		
		// ajax fill
		if (option.href !== null) {
			contentContainer.attr('data-json', '{href: \'' + option.href + '\'}');
			contentContainer.ajaxFill();
		}
		
		return contentContainer;
	}

	// have to be at the end because other functions have to declared
	$.fn.modal = function (option) { // protyping object to have valform method
		// set default options
		option = option || {};
		option = $.extend (true, {
			event: 'click'
		}, option);
		this.each(function () {
			if (option.event == null) {
				// show modal right now
				var openOption = $(this).metadata();
				openOption.href = this.href || openOption.href;	// this.href takes priority
				if (openOption.href === undefined) {
					// if href is not defined, use content
					openOption.content = this;
				}
				openModal(openOption);
			}
			else {
				// show modal when event occur
				$(this).bind(option.event, function () {
					var openOption = $(this).metadata();
					openOption.href = this.href || openOption.href;	// this.href takes priority
					openModal(openOption);
					return false;
				});
			}
		});
		
		return this; // keep jquery strategy
	};
})(jQuery); //pass jQuery object into function

