/*<script>*/

(function ($) { //anonymous function to prevent global scope, "$" is a prototype reference		
	// create blind to hide background
	function openModal (option) {
		// defaults
		option = option || {};
		
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
		blind.show('fade', 100);
		
		// handle popup
		var popUp = $('<div class="modal-popUp"></div>');
		popUp.css({
			'z-index': zIndex + 1
		});
		popUp.appendTo(outerContainer);
		
		var closeModal = function () {
			outerContainer.remove();
			blind.hide('fade', 150, function() {
				$(this).remove;
			});
		};
		
		// handle close
		var closeAnchor = $('<a class="modal-close" title="Close">x</a>').click(closeModal);
		closeAnchor.appendTo(popUp);
		blind.click(closeModal);
		
		// handle content
		var contentContainer = $('<div class="modal-content">Loading...</div>');
		popUp.append(contentContainer);
		
		// ajax fill
		if (option.href !== undefined) {
			contentContainer.attr('data-json', '{href: \'' + option.href + '\'}');
			contentContainer.ajaxFill();
		}
		
	}

	// have to be at the end because other functions have to declared
	$.fn.modal = function () { // protyping object to have valform method
		this.each(function () {
			$(this).click(function () {
				var option = $(this).metadata();
				if(this.href !== undefined) {
					option.href = this.href;	
				}
				openModal(option);
				return false;
			});
		});
	};
})(jQuery); //pass jQuery object into function

