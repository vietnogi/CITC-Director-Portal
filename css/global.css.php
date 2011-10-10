/*<style>*/
/* reset */
html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, dl, dt, dd, ul, ol, li,
form, fieldset, legend, input, label, textarea, button, table, caption, thead, tbody, tfoot, tr, th, td { margin: 0; padding: 0; font-family: inherit; font-style: inherit; font-size: 100%; font-weight: inherit; }
body { background: #fff; color: #333; }
ul, ol { list-style: none; }
:link, :visited { text-decoration: none; }
form, fieldset, img { border: 0; }
table { border-collapse: collapse; border-spacing: 0; }
td, th, caption { text-align: left; vertical-align: top; font-weight: normal; }
th { font-weight: bold; }
address { font-style: normal; }
blockquote:before, blockquote:after, q:before, q:after { content: ""; }
blockquote, q { quotes: "" ""; }
legend { color: #000; }

/* outline */
a { overflow: hidden; }

/* global classes */
.hidden { display: none; }
.no-wrap { white-space: nowrap; }
.clear { clear: both; }

<?
if (strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false ||
	strpos($_SERVER['HTTP_USER_AGENT'], 'AUDIOVOX') !== false ||
	strpos($_SERVER['HTTP_USER_AGENT'], 'avantgo') !== false ||
	strpos($_SERVER['HTTP_USER_AGENT'], 'Blackberry') !== false ||
	strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false ||
	strpos($_SERVER['HTTP_USER_AGENT'], 'Blazer') !== false ||
	strpos($_SERVER['HTTP_USER_AGENT'], 'elaine') !== false ||
	strpos($_SERVER['HTTP_USER_AGENT'], 'Fennec') !== false ||
	strpos($_SERVER['HTTP_USER_AGENT'], 'hiptop') !== false ||
	strpos($_SERVER['HTTP_USER_AGENT'], 'IEMobile') !== false ||
	strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') !== false ||
	strpos($_SERVER['HTTP_USER_AGENT'], 'Jasmine') !== false ||
	strpos($_SERVER['HTTP_USER_AGENT'], 'LG-') !== false ||
	strpos($_SERVER['HTTP_USER_AGENT'], 'Minimo') !== false ||
	strpos($_SERVER['HTTP_USER_AGENT'], 'mobile') !== false ||
	strpos($_SERVER['HTTP_USER_AGENT'], 'MOT-') !== false ||
	strpos($_SERVER['HTTP_USER_AGENT'], 'Nokia') !== false ||
	strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false ||
	strpos($_SERVER['HTTP_USER_AGENT'], 'OperaMobi') !== false ||
	strpos($_SERVER['HTTP_USER_AGENT'], 'plucker') !== false ||
	strpos($_SERVER['HTTP_USER_AGENT'], 'Polaris') !== false ||
	strpos($_SERVER['HTTP_USER_AGENT'], 'SAMSUNG') !== false ||
	strpos($_SERVER['HTTP_USER_AGENT'], 'SIE-') !== false ||
	strpos($_SERVER['HTTP_USER_AGENT'], 'SonyEricsson') !== false ||
	strpos($_SERVER['HTTP_USER_AGENT'], 'webOS') !== false ||
	strpos($_SERVER['HTTP_USER_AGENT'], 'xiino') !== false) {
	?>
	/* webkit text */
	html { -webkit-text-size-adjust: none; }
	<?
}
?>