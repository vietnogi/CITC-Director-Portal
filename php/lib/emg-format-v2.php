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

function uncleanUrl($url, $delimiter = '-') {
	return ucwords(str_replace($delimiter, ' ', $url));
}

function css2Html($selector) {
	// class1/class2 since it can come before or after id
	preg_match('/(?P<tag>\w+)?(?P<class1>[.\w-]+)?(?P<id>#[\w-]+)?(?P<class2>[.\w-]+)?/', $selector, $matches);
	
	// Clean up matches return values
	return array('tag' => $matches['tag']
			   // Remove #
			   , 'id' => isset($matches['id']) ? str_replace('#', '', $matches['id']) : NULL
			   // Remove leading . and replace remaining with spaces
			   , 'class' => isset($matches['class2']) ? str_replace('.', ' ', ltrim($matches['class1'] . $matches['class2'], '.')) : NULL
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


function decryptCreditCard ($creditCard) {
	$encryptedFields = array('first_name'
							 , 'last_name'
							 , 'address'
							 , 'address2'
							 , 'city'
							 , 'province'
							 , 'zip'
							 , 'phone'
							 , 'credit_card_number'
							 , 'expiration_month'
							 , 'expiration_year'
							 );
	foreach ($this->encryptedFields as $field) {
		$info[$field] = decrypt($info[$field]);
	}
}

function encryptCreditCard ($creditCard) {
	$encryptedFields = array('first_name'
							 , 'last_name'
							 , 'address'
							 , 'address2'
							 , 'city'
							 , 'province'
							 , 'zip'
							 , 'phone'
							 , 'credit_card_number'
							 , 'expiration_month'
							 , 'expiration_year'
							 );
	foreach ($this->encryptedFields as $field) {
			$info[$field] = encrypt($info[$field]);
		}	
}
?>