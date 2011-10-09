/*<script>*/
var Markup = function (container) {
	
	function externalLinks (container) {
		var anchors = $('a[rel~="external"]', container);
		for (var i = 0; i < anchors.length; i++) {
			anchors[i].target = "_blank";
		}	
	}
	
	function autoCompleteOff (container) {
		var inputs = $('input[class~="autocomplete-off"]', container);
		for (var i=0; i<inputs.length; i++) {
			inputs[i].setAttribute("autocomplete", "off");
		}
	}
	
	function defaultClear (container) {	
		var inputs = $('[class~="default-clear"]', container);
		
		var defaultClassName = 'default';
		
		inputs.focus(function() {
			if(this.value == this.defaultValue){
				this.value = '';
				$(this).removeClass(defaultClassName);
			}
		}).blur(function() {
			if(this.value == ''){
				this.value = this.defaultValue;
				$(this).addClass(defaultClassName);
			}
		});
	}
	
	// default container
	container = container || $('body')[0]; 
	
	$('.hide').hide(); //hide all elements with class hide
	externalLinks();
	autoCompleteOff();
	defaultClear();
}