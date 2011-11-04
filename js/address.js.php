/*<script>*/

(function ($) { //anonymous function to prevent global scope, "$" is a prototype reference	
	var USID = 1;
	function setEvents (container) {
		$('select[name*="country_id"]').change(function() {
			var provinceContainer = $('[id*="province-container"]', container);
			var provinceInput = $('input[name*="province"]', provinceContainer);
			var stateContainer = $('[id*="state-id-container"]', container);
			var stateInput = $('select[name*="state_id"]', stateContainer);
			var zipInput = $('input[name*="zip"]', container);
			
			if (this.value == USID) {
				// province
				provinceContainer.hide();
				provinceInput.removeClass('req');
				// state
				stateContainer.show();
				stateInput.addClass('req');
				// zip
				zipInput.addClass('req');				
			}
			else {
				// province
				provinceContainer.show();
				provinceInput.addClass('req');
				// state
				stateContainer.hide();
				stateInput.removeClass('req');
				// zip
				zipInput.removeClass('req');
			}
		});
	}
		
	//have to be at the end because other functions have to declared
	$.fn.address = function () { //protyping object to have valform method
		this.each(function () {
			setEvents(this);
		});
		return this; // keep jquery strategy
	};
})(jQuery); //pass jQuery object into function
