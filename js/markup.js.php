/*<script>*/
var Markup = {
	init: function(){
		$('.hide').hide(); //hide all elements with class hide
		
		var bodyElement = $('body')[0]; // cant use document.body because error in IE7, no .select
		this.externalLinks(bodyElement);
		this.autoCompleteOff(bodyElement);
		this.defaultClear(bodyElement);		
	}
	
	, externalLinks: function(container){
		var anchors = $('a[rel~="external"]', container);
		for (var i = 0; i < anchors.length; i++) {
			anchors[i].target = "_blank";
		}	
	}
	
	, autoCompleteOff: function (container){
		var inputs = $('input[class~="autocomplete-off"]', container);
		for (var i=0; i<inputs.length; i++) {
			inputs[i].setAttribute("autocomplete", "off");
		}
	}
	
	, defaultClear: function (container){	
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
};