<?
function cleanUrl($string, $delimiter = '-') {
	// Character encoding
	setlocale(LC_ALL, 'en_US.UTF8');
	if (function_exists('iconv')) {
		$string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
	}
	
	$string = strip_tags($string);
	// Reverse HTML special chars (charset provided for &trade;, &hellip;, etc...)
	$string = html_entity_decode($string, ENT_COMPAT, 'cp1251');
	
	// Keep case insensitive alphanumeric & special chars to be replaced with delimiter (rather than removing)
	$chars = '.\/_|+ -';
	$string = preg_replace("/[^a-z0-9" . $chars . "]/i", '', $string);
	
	// Convert to lowercase, replace (repeating) special chars and trim
	$string = strtolower($string);
	$string = preg_replace("/[" . $chars . "]+/", $delimiter, $string);
	$string = trim($string, $delimiter);

	return $string;
}

// Convert css selector to html markup string
// Input:	ul#nav.nav.nav-2
// Output:	array('tag' => 'ul'
//				, 'id' => 'nav'
//				, 'class' => 'nav nav-2'
//				)
function uncleanUrl($url, $delimiter = '-') {
	return ucwords(str_replace($delimiter, ' ', $url));
}

// Convert url to multiple css classes
// Ex. '/directory/section/my-page' -> 'directory section my-page'
function urlToClassStr($url) {
	return uniqueClassStr($url, '/');
}

// Remove repeated classes from string
// Ex. 'class1 class1 class2' -> 'class1 class2'
function uniqueClassStr($str, $delimiter = ' ') {
	$classes = explode($delimiter, $str);
	$classes = array_unique(array_filter($classes));
	return implode(' ', $classes);
}

// Convert css selector to html markup string
// Input:	ul#nav.nav.nav-2
// Output:	array('tag' => 'ul'
//				, 'id' => 'nav'
//				, 'class' => 'nav nav-2'
//				)
function css2Html($selector) {
	// class1/class2 since it can come before or after id
	preg_match('/(?P<tag>\w+)?(?P<class1>[.\w-]+)?(?P<id>#[\w-]+)?(?P<class2>[.\w-]+)?/', $selector, $matches);
	
	// Clean up matches return values
	return array(
		'tag' => !empty($matches['tag']) ? $matches['tag'] : ''
		// Remove #
		, 'id' => str_replace('#', '', (!empty($matches['id']) ? $matches['id'] : ''))
		// Remove leading . and replace remaining with spaces
		, 'class' => str_replace('.', ' ', ltrim((!empty($matches['class1']) ? $matches['class1'] : '') . (!empty($matches['class2']) ? $matches['class2'] : ''), '.'))
	);
}

// Convert db rows to array
function rowsToArray($rows, $keyField, $valueField) {
	$array = array();
	if (notEmptyArray($rows)) {
		$useKeys = !empty($keyField) ? true : false;
		foreach ($rows as $row) {
			if ($useKeys == true) {
				$array[$row[$keyField]] = $row[$valueField];
			}
			else {
				array_push($array, $row[$valueField]);
			}
		}
	}
	return $array;
}

// Returns standard money format $123.45
function moneyFormat($num, $cents = false, $partialCents = true, $decimalPlaces = 2, $test = false) {
	// Negative sign if applicable
	$negativeStr = $num < 0 ? '- ' : '';
	
	//handle partial cents
	if($partialCents){
		$fraction = substr($num, strpos($num, '.') + 1);		
		if(!isset($fraction[2])){
			$decimalPlaces = 2;	
		}
		else{
			if($fraction % 10 > 0){
				$decimalPlaces = 3;	
			}
		}
	}
	
	// Dollar amount
	$amount = number_format(abs($num), $decimalPlaces, '.', ',');
	
	// Show dollars or cents
	$moneyStr = ($cents && $amount < 1) ? $amount * 100 . '&cent;' : '$' . $amount;
	
	return $negativeStr . $moneyStr;
	//return ($num < 0 ? '- ' : '') . '$' . number_format(abs($num), 2, '.', ',');
}

//group a set of rows into several sets of rows using keyField as the index
function rowsToGroups($rows, $keyField){
	if(!is_array($rows)){
		trigger_error(__FUNCTION__ . '() 1st argument needs to be an array');	
	}
	$array = array();
	foreach($rows as $row){
		$keyFieldValue = $row[$keyField];
		
		if(!isset($array[$keyFieldValue])){
			$array[$keyFieldValue] = array();
		}
		array_push($array[$keyFieldValue], $row);
	}
	
	return $array;
}


// Takes in numeric string
// returns US format: 1 (234) 234-5678 x 1234...
//
// +1		ITU country calling code
// NPA		Numbering Plan Area Code			Allowed range of [2-9] for the first digit, [0-8] for the second, and [0-9] for the third digit.
// NXX		Central Office (exchange) code		Allowed ranges [2-9] for the first digit, and [0-9] for both the second and third digits.
// xxxx		Subscribed Number					[0-9] for each of the four digits.
//
// http://en.wikipedia.org/wiki/North_American_Numbering_Plan
function phoneFormat($phone) {
	if (empty($phone)) {
		return NULL;	
	}
	if (!is_numeric($phone)) {
		trigger_error('$phone is not numeric', E_USER_WARNING);	
	}
	// Strip non-numeric characters & parse phone number parts
	$match = preg_match('/([1]?)?([2-9]{1}[0-8]{1}[0-9]{1})?([2-9]{1}\d{2})(\d{4})(\d*)/', preg_replace('/[^0-9]/', '', $phone), $matches);
	
	return
	($matches[3] && $matches[4] ?
							 // 1 (234)
							 ($matches[2] ?
									   ($matches[1] ? $matches[1] . ' ' : '')
									   . '(' . $matches[2] . ') ' : ''
									   )
							 // 234-5678
							 . $matches[3] . '-' . $matches[4]
							 // x 1234...
							 . ($matches[5] ?
										 ' x ' . $matches[5] : ''
										 ) : ''
							 );
}

function defaultArrays ($names, &$src) {
	foreach ($names as $name) {
		if (!isset($src[$name]) || !is_array($src[$name])) {
			$src[$name] = array();
		}
	}
}

// remove all non numbers except decimal
function toNumber($str){
	return preg_replace('/[^0-9\.]/', '', $str);
}
// Remove all non digit characters
function toInt($str) {
	return preg_replace('/[^0-9]/', '', $str);
}

// Takes US formatted date array [mm, dd, yyyy]
// Returns ISO 8601 formatted string (yyyy-mm-dd)
function implodeDate($parts) {
	$parts[0] = empty($parts[0]) ? '' : $parts[0];
	$parts[1] = empty($parts[1]) ? '' : $parts[1];
	$parts[2] = empty($parts[2]) ? '' : $parts[2];
	
	return $parts[2] . '-' . $parts[0] . '-' . $parts[1];
}

function dateToAge ($date) {
	return date_diff(date_create($date), date_create('now'))->y; 
}

function jsonEncode($array) {
	return str_replace('"', "'", json_encode($array));
}
?>