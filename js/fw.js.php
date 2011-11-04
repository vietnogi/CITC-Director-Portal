/*<script>*/
var FW = {
	CR: null
	, TOKEN: null
	, IEVerNum: null
	, init: function(){
		// add useful functions to native objects
		if (typeof(String.prototype.trim) === "undefined") {
			String.prototype.trim = function() {
				return String(this).replace(/^\s+|\s+$/g, '');
			};
		}
		String.prototype.getUrlParam = function (name){
			var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(this);
			return results[1] || 0;
		}

	}
	, ie6Check: function () {
		if (BrowserDetect.browser == 'Explorer' && BrowserDetect.version < 7) {
			var ie6Notice = document.createElement('div');
			ie6Notice.id = 'ie6-notice';
			ie6Notice.innerHTML = '<p class="title">It seems like you are using Internet Explorer 6 or lower.</p><p>IE6 is an outdated web browser that cannot provide the rich web experience that a modern web browser is able to.  This site may not display and function correctly as a result.</p><p>You may want to upgrade to one of these newer web browsers:</p><ul class="browsers"><?
				$browsers = array('Internet Explorer 9' => 'http://www.microsoft.com/windows/downloads/ie/getitnow.mspx'
								, 'Mozilla Firefox' => 'http://www.mozilla.com/en-US/firefox/'
								, 'Google Chrome' => 'http://www.google.com/chrome'
								);
				foreach ($browsers as $browser => $url) {
					?><li><a href="<?= $url ?>" title="Download <?= $browser ?>">Download <?= $browser ?></a></li><?
				}
				?></ul><p class="hide-notice"><a href="#" onclick="document.getElementById(\'ie6-notice\').style.display = \'none\'; return false;" title="Hide this notice" rel="external">Hide this notice</a></p>';
			document.body.appendChild(ie6Notice);
		}
	}
	, ajaxComplete: function(jqXHR, textStatus) {
		// handle bad status
		switch(1 * jqXHR.status){
			case 301:
			case 302:
			case 0: //for ie
				try {
					var data = $.parseJSON(jqXHR.responseText);
					window.location.href = data.location;
				} catch (err) {
					alert('Error trying to redirect.');
				}
			break;
			case 404:
				alert('404 Error: Page not found, please verify url and try again');
			break;
		}
	}
	, error: function(jqXHR, textStatus, errorThrown){
		console.error('Unable to complete request becuase the following error occured: ' + errorThrown + ' for the URL: ' + this.url);
	}
};