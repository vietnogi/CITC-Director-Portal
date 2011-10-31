<?	
class Validate{	

	public function __construct() {
		//nothing
	}
	
	function check ($val, $check, $parameter = NULL) {
		switch (trim($check)) {
			case 'req':
				return $this->isMin($val, 1);
			
			case 'email': 
				return $this->isEmail($val); 
			
			case 'alpha':
				return $this->isAlpha($val); 
			
			case 'alpha_space': 
				return $this->isAlphaSpace($val);
			
			case 'alpha_num':
				return $this->isAlphaNum($val);
			
			case 'alpha_num_sym':
				return $this->isAlphaNumSym($val);
			break;
			
			case 'int':
				return $this->isInt($val);
			
			case 'min':
				return $this->isMin($val, $parameter);
			
			case 'max':
				return $this->isMax($val, $parameter);
			
			case 'len':
				return $this->isLen($val, $parameter);
			
			case 'numeric':
				return empty($str) ? true : is_numeric($val);
			
			case 'string':
				return is_string($val);
		}
		
		return true;
	}
	
	function many ($values) {
		if (!is_array($values)) {
			die('validate: error, first argument must be an array');
		}
		foreach ($values as $index => $value) {
			$keys = array_keys($value);
			$checkstr = $keys[0];
			$name = isset($value['name']) ? $value['name'] : NULL;
			$val = $value[$checkstr];
			//foreach($value as $key => $val){
				$checks = explode(' ', $checkstr);
				$len = count($checks);
				for ($i=0; $i < $len; $i++) {
					$parameter = isset($checks[$i + 1]) ? $checks[$i + 1] : NULL;
					$check = $this->check($val, $checks[$i], $parameter);
					if (!$check) {
						logError('Validate failed: index: ' . $index . ', check: ' . $checks[$i] . ', value: ' . $val . ', name: ' . (!empty($name) ? $name : ''));
						return false;
					}
				}
			//}
		}
		return true;
	}

	public function isAlphaNum ($str) {
		if (!preg_match('/^[a-z0-9]+([\\s]{1}[a-z0-9]|[a-z0-9])+$/i', $str) && $str != '') {
			return false;
		}
		return true;
	}
	
	public function isAlpha ($str) {
		if (!preg_match('/a-z/i', $str)  && $str != '') {
			return false;
		}
		return true;
	}
	
	public function isEmail ($email) {
		if (empty($email)) {
			return true;
		}
		
		$isValid = true;
		$atIndex = strrpos($email, '@');
		if ($atIndex === false) {
			return false;
		}
		
		$domain = substr($email, $atIndex +1);
		$local = substr($email, 0, $atIndex);
		$localLen = strlen($local);
		$domainLen = strlen($domain);
		if ($localLen < 1 || $localLen > 64) { // local part length exceeded
			return false;
		}
		if ($domainLen < 1 || $domainLen > 255) { // domain part length exceeded
			return false;
		}
		if ($local[0] == '.' || $local[$localLen-1] == '.') { // local part starts or ends with '.'
			return false;
		}
		if (preg_match('/\\.\\./', $local)) { // local part has two consecutive dots
			return false;
		}
		if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) { // character not valid in domain part
			return false;
		}
		if (preg_match('/\\.\\./', $domain)) { // domain part has two consecutive dots
			return false;
		}
		if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local))) {
			// character not valid in local part unless 
			// local part is quoted
			if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))) {
				return false;
			}
		}
		/*
		dont have check dns
		if (!(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))) {
			// domain not found in DNS
			$isValid = false;
		}
		*/
		
		return true;
	}
	
	public function isMax ($str, $max) {
		if (isset($str[$max])) {
			return false;
		}
		return true;
	}
	
	public function isMin ($str, $min) {
		if (isset($str[$min-1])) {
			return true;
		}
		return false;
	}
	
	public function isLen ($str, $len) {
		if(!isset($str[$len-1]) || isset($str[$len])) {
			return false;
		}
		return true;
	}
	
	public function isInt($str) {
		if ($str != '') {
			if (!is_numeric($str)) {
				return false;
			}
			if (strstr($str, '.')) {
				return false;
			}
		}
		return true;
	}

}
//----- Validators
?>