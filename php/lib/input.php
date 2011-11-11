<?
class Input {
	public $name = NULL;
	public $value = NULL;
	public $validate = NULL;
	public $default = NULL;
			
	public function __construct ($name, $source = NULL, $validate = NULL, $default = NULL) {
		
		$this->name = $name;
		$this->validate = $validate;
		$this->default = $default;
		
		if (is_array($source)) { // handle if source is an array
			$this->value = isset($source[$this->name]) ? $source[$this->name] : $default;
		}
		else {
			$this->value = ($source === NULL) ? $default : $source;	
		}
		
		if ($this->isPhone()) {
			$this->cleanPhone();
		}
		
		if ($this->isID() && strpos($validate, 'int') === false) {
			$validate .= ' int';
		}
		
		if (!empty($validate)) {
			$checks = array();
			$checks[0] = array(
				$validate => $this->value
				, 'name' => $this->name
			);
			$GLOBALS['validate']->many($checks);
		}
	}
	
	private function isPhone() {
		return strpos($this->validate, 'phone') !== false;	
	}
	
	private function isDate() {
		return strpos($this->validate, 'date') !== false;		
	}
	
	private function cleanPhone() {
		$this->value = toInt($this->value);
	}
	
	private function isID() {
		return 	preg_match('/_id$/', $this->name);
	}
}