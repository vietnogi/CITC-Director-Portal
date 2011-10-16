/*<script>*/
var FW = {
	CR: null
	, TOKEN: null
	, scrollBarWidth: null
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

		this.scrollBarWidth = this.getScrollBarWidth();
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
				alert('Page Not Found');
			break;
		}
	}
	, error: function(jqXHR, textStatus, errorThrown){
		alert('Unable to complete request becuase the following error occured: ' + errorThrown);	
	}
	, getScrollBarWidth: function () {  
		var inner = document.createElement('p');
		inner.style.width = "100%";
		inner.style.height = "200px";
		
		var outer = document.createElement('div');
		outer.style.position = "absolute";
		outer.style.top = "0px";
		outer.style.left = "0px";
		outer.style.visibility = "hidden";
		outer.style.width = "200px";
		outer.style.height = "150px";
		outer.style.overflow = "hidden";
		outer.appendChild (inner);
		
		document.body.appendChild (outer);
		var w1 = inner.offsetWidth;
		outer.style.overflow = 'scroll';
		var w2 = inner.offsetWidth;
		if (w1 == w2) w2 = outer.clientWidth;
		
		document.body.removeChild (outer);
		
		return (w1 - w2);
	}
	,  getTopLeft: function (width, height){
		var windowWidth = $(window).width();
		var windowHeight = $(window).height();
		
		//compensate for scroll
		var xy = this.getScrollXY();
		
		//get %
		var top = (windowHeight/2 + xy[1] - (height/2));
		var left = (windowWidth/2 + xy[0] - (width/2));
		
		
		return [Math.round(top), Math.round(left)];
	}
	, getScrollXY: function () {
		var scrOfX = 0, scrOfY = 0;
		if( typeof( window.pageYOffset ) == 'number' ) {
			//Netscape compliant
			scrOfY = window.pageYOffset;
			scrOfX = window.pageXOffset;
		} else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
			//DOM compliant
			scrOfY = document.body.scrollTop;
			scrOfX = document.body.scrollLeft;
		} else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
			//IE6 standards compliant mode
			scrOfY = document.documentElement.scrollTop;
			scrOfX = document.documentElement.scrollLeft;
		}
		return [ scrOfX, scrOfY ];
	}
	, center: function (container) {
		$container = $(container);
		var topLeft = this.getTopLeft($container.width(), $container.height());
		$container.css('top', topLeft[0] + 'px');
		$container.css('left', topLeft[1] + 'px');
	}
};