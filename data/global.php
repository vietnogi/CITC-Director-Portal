<?
$this->gd['navs'] = array();

//globals for customer section
if ($GLOBALS['bc']->section == 'customer') {
	if (!is_numeric(USERID)) {
		logError('USERID is not numeric');	
	}
	$customer = getCustomer(USERID);
	if (empty($customer)) {
		logError('$customer is empty');	
	}
	$this->gd['customer'] = $customer;
}

//dosnt feel like this is the ideal place to put this
$this->gd['emg_form_args'] = array('legendTag' => 'h3'
					 			, 'labelColon' => false
					 			, 'labelAsteriskPrepend' => false
					 			, 'groupedFieldSets' => array('address')
								);
?>