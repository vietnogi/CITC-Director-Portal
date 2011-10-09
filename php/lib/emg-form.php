<?
/*
EMG Form v.2

requires library/php/emg.php for cleanUrl()
requires library/php/emg-format.php for css2Html()
requires library/php/emg-markup.php for htmlSelect()
*/

class EmgForm {
	// Is entire new form or just inserting fields into existing form
	private $isForm = false; // if true, include <form> tags
	
	// Form attributes
	private $attributes = array('id' => ''
							  , 'action' => ''
							  , 'method' => 'get'
							  , 'target' => ''
							  , 'class' => ''
							  , 'onsubmit' => NULL // js should be external
							  
							  // Display options
							  , 'legendTag' => 'h4' // Tag for legend heading'
							  , 'requiredHint' => true // '* is required'
							  , 'labelColon' => true // Append colon after label
							  , 'labelAsterisk' => '<em>*</em>'
							  , 'labelAsteriskPrepend' => true // prepend to left or append to right
							  , 'phoneFields' => 1 // [1, 3 (4 for ext?)]
							  , 'dateFields' => 1 // [1, 3]
							  , 'ssnFields' => 1 // [1, 3]
							  , 'groupedFieldSets' => array() // [address]
							  );
		
	// Val form
	private $valFormReqClass = 'val-req';
	
	// ul.fields
	private $ulDepth = 0;
	
	// Input function map (non <input>)
	private $inputFunctions = array('select' => 'inputSelect'
								  , 'textarea' => 'inputTextarea'
								  , 'checkbox' => 'inputCheckboxRadio'
								  , 'radio' => 'inputCheckboxRadio'
								  );
	
	// Hard coded combo fields
	private $comboFields = array('checkbox'
							   , 'radio'
							   );
	
	// Helper to find duplicates
	private $ids = array();
	
	// Constructor
	public function __construct($args) {
		// Populate form attributes
		foreach ($this->attributes as $attribute => &$value) {
			$value = isset($args[$attribute]) ? $args[$attribute] : $value;
		}
		
		// If starting a new form
		if (!empty($this->attributes['id']) && !empty($this->attributes['action'])) {
			$this->isForm = true;
			?>
			<form id="<?= $this->attributes['id'] ?>" action="<?= $this->attributes['action'] ?>"<?= !empty($this->attributes['method']) ? ' method="'. $this->attributes['method'] . '"' : '' ?><?= !empty($this->attributes['target']) ? ' target="'. $this->attributes['target'] . '"' : '' ?><?= !empty($this->attributes['class']) ? ' class="'. trim($this->attributes['class']) . '"' : '' ?><?= ($this->attributes['onsubmit'] != NULL) ? ' onsubmit="' . $this->attributes['onsubmit'] . '"' : '' ?>>
			<?
		}
	}
	
	// Automate field name to match default db syntax
	private function generateFieldName($args) {
		return !empty($args['name']) ? $args['name'] : cleanUrl($args['label'], '_');
	}
	// Automate field id: formid-name
	private function generateFieldId($args) {
		$isFieldCombo = !empty($args['type']) && in_array($args['type'], $this->comboFields);
		
		// Append labelAfter to id if field combo
		return !empty($args['id']) ? $args['id'] : cleanUrl($this->attributes['id'] . '-' . $this->generateFieldName($args) . ($isFieldCombo ? '-' . $args['labelAfter'] : ''));
	}
	
	// Start fieldset (ul.fields) if needed
	public function ulStart($class = '') {
		if ($this->ulDepth > 0) {
			$this->ulEnd();
		}
		if ($this->ulDepth < 1) {
			$this->ulDepth++;
			?>
			<ul class="<?= trim('fields ' . $class) ?>">
			<?
		}		
	}
	// End fieldset (ul.fields) if needed
	public function ulEnd() {
		if ($this->ulDepth > 0) {
			$this->ulDepth--;
			?>
			</ul>
			<?			
		}		
	}
	
	// Heading (legend is inconsistent across browsers)
	public function legend($text, $selector = NULL) {
		$this->ulEnd();
		
		$tagParts = css2Html(!empty($selector) ? $selector : $this->attributes['legendTag']);
		echo '<' . $tagParts['tag'] . (!empty($tagParts['id']) ? ' id="' . $tagParts['id'] . '"' : '') . ' class="' . $tagParts['class'] . '">' . $text . '</' . $tagParts['tag'] . '>';
	}
	
	// List item / container
	public function li($args, $test = 0) {
		// Split into separate inputs
		if (isAssociativeArray($args)) {
			$inputs = array();
			array_push($inputs, $args);
		}
		else {
			$inputs = $args;
		}
		
		// Default values
		$defaultArgValues = array('after' => NULL
								, 'autocomplete-off' => false
								, 'before' => NULL
								, 'checked' => false
								, 'class' => NULL
								, 'default' => NULL
								, 'id' => NULL
								, 'label' => NULL
								, 'labelAfter' => NULL
								, 'labelColon' => NULL
								, 'maxlength' => NULL
								, 'name' => NULL
								, 'type' => NULL
								, 'required' => false
								, 'value' => NULL
								, 'values' => array()
								);
		
		// Generate names/ids if not given
		// and bool values
		foreach ($inputs as &$input) {
			// Set default values
			foreach ($defaultArgValues as $arg => $value) {
				if (empty($input[$arg])) {
					$input[$arg] = $value;
				}
			}
			
			// Automate name based on label if not passed in
			$input['name'] = $this->generateFieldName($input);
			
			// Automate id based on name if not passed in
			$input['id'] = $this->generateFieldId($input);
			array_push($this->ids, $input['id']);
			
			// Determine bools based on class string
			$bools = array('required' => 'val_req'
						 , 'maxlength' => 'val_max'
						 , 'autocomplete-off' => 'autocomplete-off'
						 );
			foreach ($bools as $arg => $class) {
				if (strpos($input['class'], $class) !== false) {
					$input[$arg] = true;
					
					if ($arg == 'maxlength') {
						preg_match('/' . $class . ' ([\d]+)/', $input['class'], $matches);
						$input[$arg] = $matches[1];
					}
				}
			}
		}
		unset($input);
		
		if ($this->ulDepth < 1) {
			$this->ulStart();
		}
		?>
		<li id="<?= $inputs[0]['id'] ?>-container"<?= !empty($inputs[0]['labelAfter']) ? ' class="label-after"' : '' ?>>
			<?= $inputs[0]['before'] ?>
			<?
			// Prepend label
			$this->label($inputs[0]);			
			
			// Use field-combo for multi inputs
			// ex. date (mm dd yyyy)
			// as well as radios/checkboxes
			$numInputs = count($inputs);
			$isFieldCombo = $numInputs > 1 || (!empty($args[0]) && in_array($args[0]['type'], $this->comboFields));
			if ($isFieldCombo) {
				?><ul class="field-combo <?= $args[0]['type'] ?>"><?
			}
			
			// Input field markup
			foreach ($inputs as $i => $input) {
				if ($isFieldCombo) {
					?><li><?
				}
				if (!empty($this->inputFunctions[$input['type']])) {
					eval('$this->' . $this->inputFunctions[$input['type']] . '($input);');
				}
				else {
					$this->inputText($input);
				}
				if ($isFieldCombo) {
					$this->labelAfter($input);
					?></li><?
				}
			}
			if ($isFieldCombo) {
				?></ul><?
			}
			else {			
				// Append label
				$this->labelAfter($inputs[0]);
			}
			?>
			<?= $inputs[0]['after'] ?>
		</li>
		<?
	}
	
	// Base Inputs elements
	public function label($args) {
		$labelColon = $this->attributes['labelColon'] || $args['labelColon'];
		if (!empty($args['label'])) {
			?>
			<label for="<?= $args['id'] ?>"><?= $this->attributes['labelAsteriskPrepend'] && $args['required'] ? $this->attributes['labelAsterisk'] . ' ' : '' ?><?= $args['label'] . ($labelColon ? ':' : '') ?><?= !$this->attributes['labelAsteriskPrepend'] && $args['required'] ? ' ' . $this->attributes['labelAsterisk'] : '' ?></label>
			<?
		}
	}
	// Lesser label for grouped fieldsets
	// or radio/checkbox inputs
	public function labelAfter($args) {
		if (!empty($args['labelAfter'])) {
			?>
			<label for="<?= $args['id'] ?>"><?= $args['labelAfter'] ?></label>
			<?
		}
	}
	public function inputSelect($args) {
		htmlSelect($args['values'], $args['id'], $args['name'], $args['class'], $args['default'], $args['value'], true);
	}
	public function inputTextarea($args) {
		?>
		<textarea id="<?= $args['id'] ?>" name="<?= $args['name'] ?>" class="<?= $args['class'] ?>" rows="" cols=""><?= $args['value'] ?></textarea>
		<?
	}
	public function inputText($args) {
		if ($args['type'] == 'text' && $args['maxlength'] < 1) {
			$args['maxlength'] = 255;
		}
		?>
		<input type="<?= $args['type'] ?>" id="<?= $args['id'] ?>" name="<?= $args['name'] ?>" value="<?= $args['value'] ?>" class="<?= trim($args['class']) ?>"<?= $args['maxlength'] ? ' maxlength="' . $args['maxlength'] . '"' : '' ?><?= $args['autocomplete-off'] ? ' autocomplete="off"' : '' ?> />
		<?
	}
	public function inputCheckboxRadio($args) {
		?>
		<input type="<?= $args['type'] ?>" id="<?= $args['id'] ?>" name="<?= $args['name'] ?>" value="<?= $args['value'] ?>" class="<?= trim($args['class']) ?>" <?= $args['checked'] ? 'checked="checked"' : '' ?> />
		<?
	}
	
	// Public usage functions
	public function text($args) {
		$this->li(array_merge($args, array('type' => 'text')));
	}
	public function password($args) {
		$this->li(array_merge($args, array('type' => 'password')));
	}
	public function select($args) {
		$this->li(array_merge($args, array('type' => 'select')));
	}
	public function textarea($args) {
		$this->li(array_merge($args, array('type' => 'textarea')));
	}
	public function hidden($args) {
		$this->li(array_merge($args, array('type' => 'hidden')));
	}
	public function checkboxes($checkboxes) {
		$this->checkboxRadioMergeLi($checkboxes, 'checkbox');
	}
	public function radios($radios) {
		$this->checkboxRadioMergeLi($radios, 'radio');
	}
	public function checkboxRadioMergeLi($items, $type) {
		$lis = array();
		foreach ($items as $args) {
			$li = array_merge($args, array('type' => $type));
			array_push($lis, $li);
		}
		$this->li($lis);
	}
	public function captcha($args = NULL) {
		$this->ulEnd();
		
		$args['label'] = !empty($args['label']) ? $args['label'] : 'CAPTCHA';
		$args['id'] = $this->attributes['id'] . '-captcha';
		?>
		<ul class="fields captcha">
			<li>
				<?
				$this->label($args);
				?>
				<input type="text" name="captcha" id="<?= $args['id'] ?>" class="val_req val_ajax verifyCaptcha autocomplete-off" />
                <input type="hidden" name="captchaid" value="<?= $args['id'] ?>" />
				<p class="captcha"><img src="<?= CR ?>/ajax/show-captcha?area=<?= $args['id'] ?>&amp;k=<?= mt_rand()?>" id="<?= $args['id'] ?>-img" alt="CAPTCHA" /></p>
				<p class="new-image"><a href="#<?= $args['id'] ?>" class="captcha-hint">Click here for new image</a></p>
			</li>
		</ul>
		<?
	}
	public function submit($args) {
		// Alert of duplicates
		$this->alertDuplicates();
		
		$this->ulEnd();
		?>
		<ul class="fields submit">
			<li>
				<input type="submit"<?= !empty($args['name']) ? ' name="' . $args['name'] . '"' : '' ?> value="<?= $args['value'] ?>" />
			</li>
		</ul>
		<?
		$this->endForm();
	}
	public function endForm() {
		if ($this->isForm) {
			?>
			</form>
			<?
		}
	}
	
	// Shortcuts
	public function phone($args) {
		if ($this->attributes['phoneFields'] == 3) {
			$phoneNumber = toNumber($args['value']);
			$lengths = array(3, 3, 4);
			$args['class'] .= ' val_int';
			$this->combo($lengths, $phoneNumber, $args);
		}
		else {
			$this->li(array_merge($args, array('type' => 'text'
											 , 'class' => (!empty($args['class']) ? $args['class'] : '') . ' val_phone'
											 )
								 )
					 );
		}
	}
	
	public function date($args) {
		if ($this->attributes['dateFields'] == 3) {
			if($args['value'] != ''){
				$date = date('mdY', strtotime($args['value']));
			}
			$lengths = array(2, 2, 4);
			$args['labelAfters'] = array('mm', 'dd', 'yyyy');
			$args['class'] .= ' val_int';
			$this->combo($lengths, $date, $args);
		}
		else {
			$this->li(array_merge($args, array('type' => 'text'
											 , 'class' => (!empty($args['class']) ? $args['class'] : '') . ' val_date'
											 )
								 )
					 );
		}
	}
	
	public function dateMonthYear($args) {
		$date = toNumber($args['value']);
		$lengths = array(2, 4);
		$args['labelAfters'] = array('mm', 'yyyy');
		$args['class'] .= ' val_int';
		$this->combo($lengths, $date, $args);
	}
	
	public function time($args) {
		if($args['value'] != ''){
			$values = date('h i a', strtotime(DATE . ' ' . $args['value']));
			$values = explode(' ', $values);
			$args['value'] = array('hour' => $values['0']
								   , 'minute' => $values['1']
								   , 'ampm' => $values['2']
								   );
		}
		$timeFields = array(array_merge($args, array('label' => 'Time'
														, 'name' => $args['name'] . '[0]'
														, 'value' => $args['value']['hour']
														, 'values' => $this->useValueKeys($args['hours'])
														)
										   )
							   , array_merge($args, array('label' => ''
														  , 'name' => $args['name'] . '[1]'
														  , 'value' => $args['value']['minute']
														  , 'values' => $this->useValueKeys($args['minutes'])
														  )
											 )
							   , array_merge($args, array('label' => ''
														  , 'name' => $args['name'] . '[2]'
														  , 'value' => $args['value']['ampm']
														  , 'values' => $this->useValueKeys($args['ampms'])
														  )
											 )
							   );
		$this->comboSelect($timeFields);
	}
	
	public function ssn($args) {
		if ($this->attributes['ssnFields'] == 3) {
			$ssn = toNumber($args['value']);
			$lengths = array(3, 2, 4);
			$args['class'] .= ' val_int';
			$this->combo($lengths, $ssn, $args);
		}
		else {
			$this->li(array_merge($args, array('type' => 'text'
											 , 'class' => $args['class'] . ' val_int'
											 )
								 )
					 );
		}
	}
	
	public function combo($lengths, $value, $args){
		
		$args['id'] = $this->generateFieldId($args);
		$args['name'] = $this->generateFieldName($args);
		
		//split value according to lengths
		$values = array();
		$start = 0;
		foreach($lengths as $length){
			array_push($values, substr($value, $start, $length));
			$start += $length;
		}
		
		$lastIndex = count($lengths) - 1;
		$lis = array();
		foreach($lengths as $i => $length){
			$first = $i == 0 ? true : false;
			$last =  $i == $lastIndex ? true : false;
			$maxLenNext = $last ? '' : ' max-length-next ' . $args['id'] . '-' . ($i + 1);
			$li = array('type' => 'text'
							, 'label' => $args['label']
							, 'labelAfter' => $args['labelAfters'][$i]
							, 'id' => $args['id'] . ($first ? '' : '-' . $i) //first id dosnt use suffix
							, 'name' => $args['name'] . '[' . $i . ']'
							, 'class' => $args['class'] . $maxLenNext . ' val_len ' . $length . ' val_max ' . $length . ' val_combo ' . $args['id'] . ' val_errorAfter ' . $args['id'] . '-' . $lastIndex
							, 'value' => $values[$i]
							);
			array_push($lis, $li);
		}
		$this->li($lis);
	}
	
	public function comboSelect($fields){
		$lastFieldArgs = end($fields);
		$lastFieldid = $this->generateFieldId($lastFieldArgs);
		$firstFieldArgs = reset($fields);
		$firstFieldid = $this->generateFieldId($firstFieldArgs);
		
		$lis = array();
		foreach ($fields as $i => $args) {
			$li = array_merge($args, array('type' => 'select'
										   , 'class' => $args['class'] . ' val_combo ' . $firstFieldid . ' val_errorAfter ' . $lastFieldid
										   )
							  );
			array_push($lis, $li);
		}
		$this->li($lis, 1);
	}
	
	public function address($args) {
		$isGrouped = in_array('address', $this->attributes['groupedFieldSets']) || $args['grouped'];
		
		$prefix = str_replace('Address', '', $args['label']);
		
		$this->li(array_merge($args, array('type' => 'text'
										 , 'labelAfter' => $isGrouped ? 'Street Address' : ''
										 , 'name' => 'street' . (!empty($args['name']) ? $args['name'] : '')
										 , 'value' => $args['value']['address']										 
										 )
									)
							);
		$this->li(array_merge($args, array('type' => 'text'
										 , 'label' => $isGrouped ? '' : $prefix . 'Address 2'
										 , 'labelAfter' => $isGrouped ? 'Street Address 2' : ''
										 , 'name' => 'street_2' . (!empty($args['name']) ? $args['name'] : '')
										 , 'class' => str_replace('val_req', '', $args['class'])
										 , 'value' => $args['value']['address2']
										 )
									)
							);
		$this->li(array_merge($args, array('type' => 'text'
										 , 'label' => $isGrouped ? '' : 'City'
										 , 'labelAfter' => $isGrouped ? 'City' : ''
										 , 'name' => 'city' . (!empty($args['name']) ? $args['name'] : '')
										 , 'value' => $args['value']['city']
										 )
									)
							);
		$this->li(array_merge($args, array('type' => 'text'
										 , 'label' => $isGrouped ? '' : 'Province'
										 , 'labelAfter' => $isGrouped ? 'Province' : ''
										 , 'name' => 'province' . (!empty($args['name']) ? $args['name'] : '')
										 , 'class' => ''
										 , 'value' => $args['value']['province']
										 )
									)
							);
		$this->li(array_merge($args, array('type' => 'select'
										 , 'label' => $isGrouped ? '' : 'State'
										 , 'labelAfter' => $isGrouped ? 'State' : ''
										 , 'name' => 'state_id' . (!empty($args['name']) ? $args['name'] : '')
										 , 'value' => (!empty($args['value']['stateid']) ? $args['value']['stateid'] : '')
										 , 'values' => (!empty($args['states']) ? $args['states'] : '')
										 , 'default' => 'State&hellip;'
										 
										 , 'test' => true
										 )
									)
							);
		// Default to US if no value and locale is US
		if (empty($args['value']['country_id'])) {
			$args['value']['country_id'] = USID;
		}
		//$args['value']['country_id'] = empty($args['value']['country_id']) && $args['us'] ? array_search('United States', $args['countries']) : (!empty($args['value']['country_id']) ? $args['value']['country_id'] : '');
		$this->li(array_merge($args, array('type' => 'select'
										 , 'label' => $isGrouped ? '' : 'Country'
										 , 'labelAfter' => $isGrouped ? 'Country' : ''
										 , 'name' => 'country_id' .  (!empty($args['name']) ? $args['name'] : '')
										 , 'value' => $args['value']['country_id']
										 , 'values' => $args['countries']
										 , 'default' => 'Country&hellip;'
										 
										// , 'before' => 'need to test js for state/country/province'
										 )
									)
							);
		$this->li(array_merge($args, array('type' => 'text'
										 , 'label' => $isGrouped ? '' : 'Zip'
										 , 'labelAfter' => $isGrouped ? 'Zip' : ''
										 , 'name' => 'zip' .  (!empty($args['name']) ? $args['name'] : '')
										 , 'value' => $args['value']['zip']
										 )
									)
							);
	}
	public function login($args = array()) {
		$this->li(array_merge($args, array('type' => 'text'
										 , 'label' => 'Email Address'
										 , 'name' => 'email'
										 , 'value' =>  $args['value']['email']
										 , 'class' => 'val_req'
										 )
							 )
				 );
		$this->li(array_merge($args, array('type' => 'password'
										 , 'label' => 'Password'
										 , 'name' => 'password'
										 , 'value' =>  $args['value']['password']
										 , 'class' => 'val_req'
										 )
							 )
				 );
	}
	public function creditCard($args) {
		$this->li(array_merge($args, array('type' => 'select'
										 , 'label' => 'Card Type'
										 , 'name' => 'credit_card_type'
										 , 'class' => 'val_req'
										 , 'default' => 'Card Type&hellip;'
										 , 'value' => $args['value']['credit_card_type']
										 , 'values' => $this->useValueKeys($args['creditCardTypes'])
										 )
									)
							);
		$this->li(array_merge($args, array('type' => 'text'
										 , 'label' => 'Card Number'
										 , 'name' => 'credit_card_number'
										 , 'value' =>  !empty($args['value']['credit_card_number']) ? $args['value']['credit_card_number'] : ''
										 , 'class' => 'val_req val_max 19 val_func valCC autocomplete-off'
										 )
									)
							);
		
		if ($args['ccvc']) { //ccode can not be stored so it may be optional
			$this->li(array_merge($args, array('type' => 'text'
											   , 'label' => 'Card Verification Code <a href="' . CR . '/what-is-cvv" rel="ajax modal" title="What is Card Verfication Code?">?</a>'
											   , 'name' => 'credit_card_verification_code'
											   , 'value' => ''
											   , 'class' => 'val_req val_int val_max 4 autocomplete-off'
											   )
								  )
					  );
		}
		
		$expDateFields = array(array_merge($args, array('label' => 'Expiration Date'
														, 'name' => 'expiration_month'
														, 'class' => 'val_req'
														, 'default' => 'Month&hellip;'
														, 'value' => $args['value']['expiration_month']
														, 'values' => $this->useValueKeys($args['months'])
														)
										   )
							   , array_merge($args, array('label' => ''
														  , 'name' => 'expiration_year'
														  , 'class' => 'val_req'
														  , 'default' => 'Year&hellip;'
														  , 'value' => $args['value']['expiration_year']
														  , 'values' => $this->useValueKeys($args['years'])
														  )
											 )
							   );
		$this->comboSelect($expDateFields);				   
	}
	
	// Utility
	// Use array values as keys
	public function useValueKeys($array) {
		return array_combine(array_values($array), $array);
	}
	
	private function alertDuplicates() {
		if (DEVELOPMENT == 1) {
			$duplicateIds = findDuplicates($this->ids);
			if (!empty($duplicateIds)) {
				echo 'Duplicate IDs:<br />';
				print_r($duplicateIds );
			}
		}
	}
	
	public function checkboxGroups($items, $numGroups, $args) {
		$groups = array_chunk($items, ceil(count($items) / $numGroups));
		?>
		<label><?= $args['label'] ?></label>
		<?
		$count = 0;
		foreach ($groups as $group) {
			?>
			<ul class="checkboxes">
				<?
				foreach ($group as $item) {
					?>
					<li>
						<input type="checkbox" id="<?= $args['id'] ?>" name="<?= $args['name'] ?>[<?= $count ?>]"<?= $item['checked'] ? ' checked="checked"' : '' ?> value="<?= $item['value'] ?>" />
						<label for=""><?= $item['label'] ?></label>
					</li>
					<?
					$count++;
				}
				?>
			</ul>
			<?
		}
	}
}
?>