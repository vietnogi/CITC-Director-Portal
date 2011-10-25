/*<script>*/

(function ($) { //anonymous function to prevent global scope, "$" is a prototype reference		
	// create blind to hide background
	function openModal (option) {
		// defaults
		option = option || {};
		
		// set z index according to previous blind, default to 100
		var previousBlind = $('.modal-blinds').last();
		var zIndex = previousBlind.length > 0 ? parseInt(previousBlind.css( 'zIndex' ), 10) * 2 : 100;
		
		// handle blinds/overlay
		var blind = $('<div class="modal-blinds"></div>');
		blind.appendTo($('body'));
		blind.css({
			'z-index': zIndex  
		});
		
		// invisible container to center popup
		var outerContainer = $('<div class="modal-container"></div>');
		outerContainer.appendTo($('body'));
		
		// handle popup
		var popUp = $('<div class="modal-popUp"></div>');
		popUp.css({
			'z-index': zIndex + 1
		});
		popUp.appendTo(outerContainer);
		
		var closeModal = function () {
			blind.remove();
			popUp.remove();
			$(window).unbind('scroll.modal-namespace-' + zIndex);
			$(window).unbind('resize.modal-namespace-' + zIndex);
		};
		
		// handle close
		var closeContainer = $('<div class="modal-close"></div>');
		var closeAnchor = $('<a class="close" title="Close"></a>').click(closeModal);
		closeAnchor.appendTo(closeContainer);
		blind.click(closeModal);
		
		// handle content
		var contentContainer = $('<div class="modal-content">Loading...</div>');
		popUp.append(closeContainer, contentContainer);
		//popUp.appendTo($('body'));
		
		// centering, need to use namespace to separate our bind because window is a global dom
		/*FW.center(popUp[0]);
		$(window).bind('scroll.modal-namespace-' + zIndex, function () {
			FW.center(popUp[0]);
		});
		$(window).bind('resize.modal-namespace-' + zIndex, function () {
			FW.center(popUp[0]);
		});*/
		
		// ajax fill
		if (option.href !== undefined) {
			contentContainer.attr('data-json', '{href:\'' + option.href + '\'}');
			contentContainer.ajaxFill();
			contentContainer.bind('fillSuccess', function() {
				//FW.center(popUp[0]);
				var wwww = contentContainer.width();
				popUp.width(contentContainer.width());
			});
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

