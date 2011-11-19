/*<script>*/

(function ($) { //anonymous function to prevent global scope, "$" is a prototype reference	
	function setEvents (self) {
		$(self).bind('click', function () {
			var $self = $(this);
			var option = $self.metadata();
			option = $.extend(true, {
				'for' : null
				, 'id' : null
				, 'crop': {
					bgColor: 'black'
					, minSize: [100, 100]
					, bgOpacity: .4
					, setSelect: [ 50, 50, 150, 150 ]
					, aspectRatio: 1 / 1
				}
			}, option);
			
			// create form
			$form = $('<form action="' + FW.CR + '/action/upload-image?t=' + FW.TOKEN + '&amp;for=' + option.for + '&amp;id=' + option.id + '" enctype="multipart/form-data" method="post"></form>');
			// create file input
			$('<input type="file" name="image" />').appendTo($form);
			// create submit button
			$('<input type="submit" value="Upload" />').appendTo($form);
			// ajax submit the file upload
			$form.ajaxSubmit().bind('ajax-submit-success', function (e, data) { //upload successful
				// uploaded img, becareful with updating w/h because of dependencies
				var img = $('<img src="' + FW.CR + '/bare/image?path=tmp/' + data.tmp_name + '&amp;w=500&amp;h=500" />');
				
				// finish form
				var finishForm = $('<form action="' + FW.CR + '/action/upload-image-crop?t=' + FW.TOKEN + '&amp;for=' + option.for + '&amp;id=' + option.id + '&amp;tmp_path=tmp/' + data.tmp_name + '" method="post"></form>');
				// hidden inputs for crop selection
				finishForm.append('<input type="hidden" name="x" value="50" />').append('<input type="hidden" name="y" value="50" />').append('<input type="hidden" name="w" value="100" />').append('<input type="hidden" name="h" value="100" />');
				// submit button
				finishForm.append('<input type="submit" value="Finish" />');
				finishForm.ajaxSubmit().bind('ajax-submit-success', function () {
					// close modal
					$('a.modal-close', modal.parent()).trigger('click');
					// trigger success event
					$self.trigger('upload-image-crop-success');
					// clean up outerscope vars
					$self = undefined
					option = undefined
					modal = undefined;
					finishForm = undefined;
				});
				
				// empty modal to use new stuff
				var modal = $form.parent();
				modal.empty().append('<p>Please crop image as needed.</p>').append(img).append(finishForm);
				// cropping
				option.crop.onSelect = function (c) {
					$('[name="x"]', finishForm).attr({ 'value' : c.x });
					$('[name="y"]', finishForm).attr({ 'value' : c.y });
					$('[name="w"]', finishForm).attr({ 'value' : c.w });
					$('[name="h"]', finishForm).attr({ 'value' : c.h });
				};
				img.Jcrop(option['crop']);
			});
			// show form in a modal
			$form.modal({
				event: null
			});
			
			return false;
		});
	}
	
	// have to be at the end because other functions have to declared
	$.fn.uploadImageCrop = function () { // protyping object to have valform method
		this.each(function () {
			setEvents(this);
		});
		return this; // keep jquery strategy
	};
})(jQuery); // pass jQuery object into function