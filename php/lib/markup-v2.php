<?
// Navigation list
// Usage: navList(array('navs' => $__navs
//					  , 'selector' => 'ul#nav.nav'
//					  , 'urlRoot' => CR
//					  , 'level' => 0
//					  , 'current' => ''
//					  , 'disableParentLinks' => false
//					  , 'disableLinks' => false
//					  , 'enableExternalLinks' => false
//					  , 'followExternalLinks' => false
//					  );
function navList($args) {
	// Args
	$navs = $args['navs'];
	$tagParts = css2Html(!empty($args['selector']) ? $args['selector'] : 'ul');
	extract($tagParts);
	$urlRoot = !empty($args['urlRoot']) ? $args['urlRoot'] : CR;
	$level = !empty($args['level']) ? $args['level'] : 0;
	$current = empty($args['current']) ? NULL : $args['current'];
	$disableParentLinks = empty($args['disableParentLinks']) ? false : true;
	$disableLinks = empty($args['disableLinks']) ? false : true;
	$enableExternalLinks = empty($args['enableExternalLinks']) ? false : true;
	$followExternalLinks = empty($args['followExternalLinks']) ? false : true;
	
	// Logic
	if (!notEmptyArray($navs)) {
		return false;
	}
	else {
		// Set current if not provided
		if (empty($current)) {
			global $_bc;
			$current = $_bc->uri;
		}
		echo '<' . $tag . ($level == 0 ? (!empty($id) ? ' id="' . $id . '"' : '') . (!empty($class) ? ' class="' . $class . '"' : '') : '') ?>><?
			foreach ($navs as $nav => $subNavs) {
				$url = '';
				$rel = '';
				// Parent: if subarray provided
				$isParent = is_array($subNavs) ? true : false;
				// Name: English displayed text
				$name = is_numeric($nav) ? $subNavs : $nav;
				// Link
				$link = strip_tags($isParent ? $name : $subNavs);
				
				// External or javascript link
				if (preg_match('/^(http:\/\/|https:\/\/|javascript:|mailto:)/', $link, $matches)) {
					$fullUrl = $link;
					if (($matches[0] == 'http://' || $matches[0] == 'https://') && strpos($fullUrl, DOMAIN) === false && $enableExternalLinks) {
						$rel = 'external';
						if (!$followExternalLinks) {
							$rel .= ' nofollow';
						}
					}
				}
				// Manual external
				else if (preg_match('/^external:/', $link, $matches)) {
					$fullUrl = end(explode('external:', $link));
					$rel = 'external nofollow';
				}
				// Custom curtain link
				else if (preg_match('/^modal:/', $link, $matches)) {
					$fullUrl = end(explode('modal:', $link));
					if (empty($fullUrl)) {
						$fullUrl = '/' . cleanUrl($name);
					}
					// Absolute path
					if ($fullUrl[0] == '/') {
						$fullUrl = CR . $fullUrl;
					}
					$rel = 'ajax modal';
				}
				// Hash
				else if (strpos($link, '#') === 0) {
					$fullUrl = $link;
				}
				// Default cleanUrl
				else {					
					$url = is_numeric($nav) || $isParent ? cleanUrl($link) : $link;
					// Can use absolute link
					if ($link[0] == '/') {
						$fullUrl = CR . $url;
					}
					// or relative link to the current nav tier
					else {
						$fullUrl = $urlRoot . '/' . ('/' . $url . '.php' === DEFAULTPAGE && $level == 0 ? '' : $url);
					}
				}
				
				// Strip query string
				$comparisonFullUrl = $fullUrl;
				if (strpos($fullUrl, '?') !== false) {
					$comparisonFullUrl = reset(explode('?', $fullUrl));
				}
				// CSS class
				$classStr = uniqueClassStr(cleanUrl($name) . (!is_numeric($nav) && !empty($url) ? ' ' . cleanUrl($url) : ''));
				?><li class="<?= $classStr ?><?= CR . $current == $comparisonFullUrl ? ' current' : '' ?><?= $isParent ? ' parent' : '' ?>"><? if ((!$disableParentLinks || !$isParent) && !$disableLinks) { ?><a href="<?= $fullUrl ?>"<?= !empty($rel) ? ' rel="' . $rel . '"' : '' ?>><?= $name ?></a><? } else { ?><span><?= $name ?></span><? }
					// Subnav if subarray is provided
					if ($isParent) {
						navList(array('navs' => $subNavs
									, 'selector' => $tag
									, 'urlRoot' => $fullUrl
									, 'level' => $level + 1
									, 'current' => $current
									, 'disableLinks' => $disableLinks
									, 'enableExternalLinks' => $enableExternalLinks
									, 'followExternalLinks' => $followExternalLinks
									)
							   );
					}
					?></li><?
				$i++;
			}
			?></<?= $tag ?>><?
	}
}

function htmlSel($list, $tagProp, $sel = '', $useKey = false, $default = false){
	if(!is_array($list)){
		trigger_error('htmlSel() $list must be an array', E_USER_ERROR);
	}
	if($useKey == NULL){
		$useKey = isAssociativeArray($list);
	}
	?>
	<select <?= $tagProp ?>>
		<?
		if ($default) {
			?>
			<option value=""><?= $default ?></option>
			<?
		}
		foreach($list as $key => $val){
			if(is_array($val)){
				?>
				<optgroup label="<?= $key ?>">
				<?
				foreach($val as $key2 => $val2){
					$selected = false;
					if($sel != '' && !$selected){
						if(is_array($sel)){
							if( ($useKey == true && in_array($key2, $sel)) ||  ($useKey == false && in_array($val2, $sel)) ){
								$selected = true;
							}
						}
						else{
							if( ($useKey == true && $key2 == $sel) ||  ($useKey == false && $val2 == $sel) ){
								$selected = true;
							}
						}
					}
					?>
					<option value="<?= $useKey ? $key2 : $val2 ?>"<?= $selected ? ' selected="selected"' : '' ?>><?= $val2 ?></option>
					<?
				}
			}
			else{
				$selected = false;
				if($sel != '' && !$selected){
					if(is_array($sel)){
						if( ($useKey == true && in_array($key, $sel)) ||  ($useKey == false && in_array($val, $sel)) ){
							$selected = true;
						}
					}
					else{
						if( ($useKey == true && $key == $sel) ||  ($useKey == false && $val == $sel) ){
							$selected = true;
						}
					}
				}
				?>
				<option value="<?= $useKey ? $key : $val ?>"<?= $selected ? ' selected="selected"' : '' ?>><?= $val ?></option>
				<?
			}
		}
		?>
	</select>
	<?
}

// Takes in actual values instead of string for tag properties, for use with htmlSel()
function htmlSelect($values, $id, $name, $classStr = '', $defaultValue = false, $selected = '', $useKey = false) {
	// Create id/name string
	$idStr = !empty($id) ? 'id="' . $id . '"' : '';
	$nameStr = !empty($name) ? 'name="' . $name . '"' : '';
	$classStr = !empty($classStr) ? 'class="' . $classStr . '"' : '';
	
	// Create properties string
	$propertyValues = array($idStr, $nameStr, $classStr);
	$properties = array();
	foreach ($propertyValues as $propertyValue) {
		if (!empty($propertyValue)) {
			array_push($properties, $propertyValue);
		}
	}
	$propertiesStr = implode(' ', $properties);
	
	htmlSel($values, $propertiesStr, $selected, $useKey, $defaultValue);
}

function pr($array) {
	?>
	<ul>
		<?
		foreach ($array as $index => $value) {
			?>
			<li>
				<strong><?= $index ?>:</strong>
				<?
					if (is_array($value) || is_object($value)) {
						pr($value);	
					}
					else {
						echo $value;
					}
				?>
			</li>
			<?
		}
		?>
	</ul>
	<?
}

function formattedAddress($address, $classStr = '') {
	if (empty($address['country_abbrv'])) {
		$address['country_abbrv'] = 'US';	
	}
	$state = $address['country_abbrv'] == 'US' || $address['country_abbrv'] == '' ? $address['state_abbrv'] : $address['province'];
	?>
	<p class="address<?= !empty($classStr) ? ' ' . $classStr : '' ?>">
		<?
		$addressFields = array('name' => !empty($address['name']) ? $address['name'] : $address['first_name'] . ' ' . $address['last_name']
							 , 'address' => $address['address']
							 , 'address-2' => $address['address2']
							 , 'city-state-zip' => $address['city'] . ', ' . $state . ' '. $address['zip']
							 , 'country' => $address['country_abbrv']
							 , 'phone' => phoneFormat($address['phone'])
							 , 'url' => $address['url']
							 );
		$addressFields = array_filter($addressFields);
		array_walk($addressFields, 'formattedSpans');
		echo implode('<br />', $addressFields);
		?>
	</p>
	<?
}

// Helper callback for 'formatted' functions
function formattedSpans(&$value, $key) {
	$value = !empty($value) ? '<span class="' . $key . '">' . $value . '</span>' : '';
} 
?>