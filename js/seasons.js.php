/*<script>*/
var Seasons = function () {
	// create account form, check if email exists
	$('#upload-season-image').bind('ajax-submit-success', function(e, data) {
		// success
		$('#season-img').attr({
			'src' : $('#season-img').attr('src')
		});
	});
	
	$('#season-description-form').bind('submit', function() {
		$self = $(this);
		$('#season-description-edit').fresheditor("save", function (id, parsedHtml) {
			$('#season-description-textarea').html(parsedHtml);
		});
	});
	
};