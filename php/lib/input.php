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
		
		if (is_array($source)) {
			// handle if source is an array
			if (isset($source[$this->name])) {
				if (is_array($source[$this->name])) {
					// handle if value is an array
					$this->value = $this->isDate() ? $this->implodeDate($source[$this->name]) : implode($source[$this->name]);
				}
				else {
					$this->value = $source[$this->name];
				}
			}
			else {
				$this->value = $default;
			}
		}
		else {
			$this->value = $source === NULL ? $default : $source;	
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
		$this->value = preg_replace('/[^0-9]/', '', $this->value);	
	}
	
	private function isID() {
		return 	preg_match('/_id$/', $this->name);
	}
	
	private function implodeDate($parts) {
		$parts[0] = empty($parts[0]) ? '' : $parts[0];
		$parts[1] = empty($parts[1]) ? '' : $parts[1];
		$parts[2] = empty($parts[2]) ? '' : $parts[2];
		
		return $parts[2] . '-' . $parts[0] . '-' . $parts[1];
	}
}