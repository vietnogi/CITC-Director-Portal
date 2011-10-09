/*<script>*/
var ParentPack = function () {
	$('.emg-form p.add-friend a').live('click', function () {
		this.blur();
		var thisLi = $(this).closest('li');
		var newLi = thisLi.clone();
		
		// Update ids
		newLi.children().each(function() {
			if (this.id) {
				var parts = this.id.split('-');
				parts[parts.length - 1] = parseInt(parts[parts.length - 1]) + 1;
				this.id = parts.join('-');
			}
			else if ($(this).is('label')) {
				var parts = this.innerHTML.split(' ');
				parts[parts.length - 1] = parseInt(parts[parts.length - 1]) + 1;
				this.innerHTML = parts.join(' ');
			}
			this.value = '';
		});
		
		$('p.add-friend a', thisLi).remove();
		
		thisLi.after(newLi);
		
		return false;
	});
	
};