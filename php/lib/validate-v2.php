<?php
//12:09 PM 3/10/2011
//email, alpha, alphaSpace, alphaNum, int, num, url, num 
//min, max, len,
/*

	*/
	
class Validate{	

	public function __construct() {
		//nothing
	}

	function single($value, $checks, &$msg, $parameters = array()){
		foreach($checks as $check){
			if(isset($parameters[$check])){
				$parameter = $parameters[$check];
			}
			else{
				$parameter = NULL;	
			}
			if(!$this->check($value, $check, $msg, $parameter)){
				return false;	
			}
		}
		return true;
	}
	
	function check($val, $check, &$msg, $parameter = NULL){
		switch($check){ 
			case 'val_req':
			case 'req':
				$msg = 'is required';
				return $this->isMin($val, 1);
			break;
			
			case 'email': 
			case 'val_email':
				$msg = 'is not a valid email address';
				return $this->isEmail($val); 
			break;
			
			case 'alpha':
			case 'val_alpha': 
				$msg = 'must contain only alphabet letters';
				return $this->isAlpha($val); 
			break;
			
			case 'alphaSpace': 
			case 'val_alphaSpace':
				$msg = 'must contain only alphabet letters and spaces';
				return $this->isAlphaSpace($val);
			break;
			
			case 'alphaNum':
			case 'val_alphaNum':
				$msg = 'must be alpha numeric';
				return $this->isAlphaNum($val);
			break;
			
			case 'alphaNumSym':
			case 'val_alphaNumSym':
				return $this->isAlphaNumSym($val);
			break;
			
			case 'int':
			case 'val_int':
				$msg = 'must be a whole number';
				return $this->isInt($val);
			break;
			
			case 'min':
			case 'val_min':
				$msg = 'must be greater than ' . $parameter;
				return $this->isMin($val, $parameter);
			break;
			
			case 'max':
			case 'val_max':
				$msg = 'must be less than ' . $parameter;
				return $this->isMax($val, $parameter);
			break;
			
			case 'len':
			case 'val_len':
				$msg = 'must be ' . $parameter . ' characters';
				return $this->isLen($val, $parameter);
			break;
			
			case 'num':
			case 'val_num':
				$msg = 'must be numeric';
				return empty($str) ? true : is_numeric($val);
			break;
		}
		
		return true;
	}
	
	/*
	ie
	$validate = array();
	$validate[]['min 1'] = htmlentities($_POST['name']);
	$validate[]['min 1'] = $_POST['sku'];
	$validate[]['num'] = $_POST['price'];
	$validate[]['num'] = $_POST['quantity'];
	$validate[]['num'] = $_POST['onsale'];
	$validate[]['num'] = $_POST['shipping'];
	$validate[]['num'] = $_POST['width'];
	$validate[]['num'] = $_POST['height'];
	$validate[]['num'] = $_POST['depth'];
	$validate[]['num'] = $_POST['price'];
	$validate[]['num'] = $_POST['weight'];
	*/
	
	function many($values, $error = true){
		if(!is_array($values)){
			die('validate: error, first argument must be an array');
		}
		foreach($values as $index => $value){
			foreach($value as $key=>$val){
				$checks = explode(' ', $key);
				$len = count($checks);
				for($i=0; $i<$len; $i++){
					$parameter = isset($checks[$i + 1]) ? $checks[$i + 1] : NULL;
					$check = $this->check($val, $checks[$i], $msg, $parameter);
					if(!$check){
						logError($_SERVER['REQUEST_URI'].' :: validate failed: index: ' . $index . ', check: ' . $checks[$i] . ', value: ' . $val, ($error ? true : false));
						return false;
					}
				}
			}
		}
		return true;
	}

	public function isAlphaNum($str){
		if (!eregi("^[a-z0-9]+$", $str) && $str != ''){
			return false;
		}
		return true;
	}

	public function isAlphaNumSym($str){
		if (!eregi("^[a-z0-9_.\-]+$", $str)  && $str != ''){
			return false;
		}
		return true;
	}
	
	public function isAlpha($str){
		if (!eregi("^[a-z]+$", $str)  && $str != ''){
			return false;
		}
		return true;
	}
	
	public function isAlphaSpace($str){
		if (!eregi("^[a-z]+$", $str) && $str != ''){
			return false;
		}
		return true;
	}
	
	public function isEmail($str){
		if(empty($str)){
			return true;
		}
		return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $str);
	}
	
	public function isMax($str, $max){
		if(isset($str[$max])){
			return false;
		}
		return true;
	}
	
	public function isMin($str, $min){
		if(isset($str[$min-1])){
			return true;
		}
		return false;
	}
	
	public function isLen($str, $len){
		if( !isset($str[$len-1]) || isset($str[$len]) ){
			return false;
		}
		return true;
	}
	
	public function isInt($str){
		if($str != ''){
			if(!is_numeric($str)){
				return false;
			}
			if(strstr($str, '.')){
				return false;
			}
		}
		return true;
	}

}
//----- Validators
?>