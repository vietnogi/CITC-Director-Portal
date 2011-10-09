<?
function listEditCampers($campers = array()) {
	?>
	<ul class="campers edit">
		<?
		foreach ($campers as $camperIndex => $camper) {
			$sessionStr = '';
			if (!empty($camper['sessions'])) {
				$sessions = rowsToArray($camper['sessions'], '', 'name');
				$sessionStr =  implode(', ', $sessions);
			}
			?>
			<li>
            	<a href="<?= CR ?>/account/enrollment/camper-information?camper-index=<?= $camperIndex ?>"><?= $camper['first_name'] . ' ' . $camper['last_name'] ?></a> <a href="<?= CR ?>/account/enrollment/session-information?camper-index=<?= $camperIndex ?>"><?= $sessionStr ?></a> <a href="<?= CR ?>/action/account/enrollment/remove-camper?camper-index=<?= $camperIndex ?>&amp;<?= TOKEN ?>" class="remove">x remove</a> 
            </li>
			<?
		}
		?>
	</ul>
	<?
}

function listPPSteps($steps, $parent = NULL){
	global $_pp, $_bc;
	?>
	<ol class="nav">
		<?
		foreach ($steps as $index => $step) {
			if(!is_numeric($index)){ //multi tier
				?>
				<li class="<?= $index ?> parent">
					<span class="parent"><?= uncleanUrl($index) ?></span>
					<?
					$newParent = ($parent == NULL ? $index : $parent . '/' . $index) . '/';
					listPPSteps($step, $newParent);
					?>
				</li>
				<?
			}
			else{
				?>
				<li class="<?= $step['clean_name'] ?><?= $step['clean_name'] == $_bc->page ? ' current' : '' ?>">
					<?
					if($_pp->accessible($step['clean_name'])){
						?>
						<a href="<?= CR ?>/account/parent-pack/<?= $parent ?><?= $step['clean_name'] ?>?parent_packid=<?= $_pp->parentPackid ?>"><?= $step['name'] ?></a>
						<?	
					}
					else{
						?>
						<span><?= $step['name'] ?></span>
						<?	
					}
					?>
				</li>
				<?
			}
		}
		?>
	</ol>
	<?
}

// Confirm Order > Camper Fees
function listCamperFees($campers = array(), $showEditLinks = false) {
	global $_dates, $_bc;
	
	foreach ($campers as $i => $camper) {
		?>
		<h3>Camper <?= ($i + 1) ?><?= $showEditLinks ? ' <a href="' . CR . $_bc->path . '/camper-information?camper-index=' . $i . '" class="edit">Edit This Information</a>' : '' ?></h3>
		<table class="order summary money">
			<tbody>
				<tr class="camper name">
					<td colspan="2"><?= $camper['first_name'] . ' ' . $camper['last_name'] ?></td>
				</tr>
				<tr class="camper dob">
					<td colspan="2"><?= $_dates->usFormat($camper['date_of_birth']) ?></td>
				</tr>
				<?
				$subTotal = 0;
				foreach ($camper['sessions'] as $session) {
					?>
					<tr class="session">
						<td class="session-specialty"><span class="session"><?= $session['name'] ?></span> <span class="specialty">- <?= $session['specialty_name'] ?></span></td>
						<td class="money"><?= moneyFormat($session['specialty_price']) ?></td>
					</tr>
					<?
					$subTotal += $session['specialty_price'];
				}
				?>
			</tbody>
            <tfoot>
				<tr class="highlight subtotal">
					<th scope="row">Subtotal:</th>
					<td class="money"><?= moneyFormat($subTotal) ?></td>
				</tr>
			</tfoot>
		</table>
		<?
	}	
}

// Confirm Order > Fees Summary
function feesSummary($fees = array(), $discounts = array(), $total) {
	?>
	<table class="order summary money">
		<tbody>
			<?
			foreach ($fees as $i => $fee) {
				?>
				<tr>
					<td><?= $fee['name'] ?></td>
					<td class="money"><?= moneyFormat($fee['amount']) ?></td>
				</tr>
				<?
			}
			
			foreach ($discounts as $i => $discount) {
				?>
				<tr class="discount">
					<td><?= $discount['name'] ?></td>
					<td class="money">-<?= moneyFormat($discount['amount']) ?></td>
				</tr>
				<?
			}
			?>
		</tbody>
        <tfoot>
			<tr class="highlight total">
				<th scope="row">Total:</th>
				<td class="money"><?= moneyFormat($total) ?></td>
			</tr>
		</tfoot>
	</table>
	<?
}


function createInvoice($info, $invoiceItems){
	global $_mysql;
	
	$values = array('customerid' => $info['customerid']
					, 'due_date' => $info['due_date']
					, 'type' => $info['type']
					, 'description' => $info['description']
					);
	
	$_mysql->insert('invoice', $values);
	$invoiceid = $_mysql->lastInsertId();
	
	foreach ($invoiceItems as $invoiceItem) {
		$invoiceItem['invoiceid'] = $invoiceid;
		$_mysql->insert('invoice_item', $invoiceItem);
	}
	
	return $invoiceid;
}

function processCCPayment($ccInfo, $total, $authorizePayment, $orderNum = ''){
	$creditCard = new CreditCard();
	$total = number_format($total, 2, '.', '');
	$paymentAction = $authorizePayment ? 'Authorization' : 'Capture';
	$requireVCode = (config('require-credit-card-verification-code') == 'Yes') ? true : false;
	$vcode = $requireVCode ? $ccInfo['credit_card_verification_code'] : NULL; //ccode can not be stored so it may be optional
	
	$creditCard->setProcessor();
	$results = $creditCard->process($total, $ccInfo, $orderNum, $paymentAction, $vcode);
	return $results;
}

function createPayment($info, $invoiceid = false, $customerid = false){
	global $_mysql;
	
	$values = array('customerid' => $info['customerid']
					, 'payment_date' => $info['payment_date']
					, 'payment_method' => $info['payment_method']
					, 'status' => $info['status']
					, 'payment_reference' => $info['payment_reference']
					, 'amount' => $info['amount']
					, 'description' => $info['description']
					);
	$_mysql->insert('payment', $values);
	$paymentid = $_mysql->lastInsertId();
	
	if (is_numeric($invoiceid)) { // allocate payment to invoice
		$values = array('paymentid' => $paymentid
						, 'invoiceid' => $invoiceid
						, 'amount' => $info['amount']
						);
		$_mysql->insert('payment_invoice_link', $values);
	}
	else if (is_numeric($customerid)) { // allocate payment to pending customer invoices
		$query = 'SELECT invoice.*
				  , IFNULL(SUM(invoice_item.amount), 0.00) AS amount
				  , (IFNULL(SUM(invoice_item.amount), 0.00) - (SELECT IFNULL(SUM(payment_invoice_link.amount), 0) AS payment_total
															   FROM payment_invoice_link
															   INNER JOIN payment ON payment_invoice_link.paymentid = payment.paymentid
															   WHERE payment_invoice_link.invoiceid = invoice.invoiceid AND (payment.status = "Completed" OR payment.status = "Pending"))) AS amount_due
				  FROM invoice
				  INNER JOIN customer ON invoice.customerid = customer.customerid
				  LEFT JOIN invoice_item ON invoice.invoiceid = invoice_item.invoiceid
				  WHERE customer.customerid = :customerid
				  GROUP BY invoice.invoiceid
				  ORDER BY invoice.due_date ASC, invoice.invoiceid ASC';
		
		$values = array(':customerid' => $customerid);
		$invoices = $_mysql->get($query, $values);
		
		if (!empty($invoices)) {
			$remainingPaymentAmount = $info['amount'];
			$i = 0;
			while ($remainingPaymentAmount > 0) {
				if ($invoices[$i]['amount_due'] <= 0) {
					$i++;
					continue;
				}
				
				$amountToAllocate = min($invoices[$i]['amount_due'], $remainingPaymentAmount);
				$values = array('paymentid' => $paymentid
								, 'invoiceid' => $invoices[$i]['invoiceid']
								, 'amount' => $amountToAllocate
								);
				$_mysql->insert('payment_invoice_link', $values);
				
				$remainingPaymentAmount -= $amountToAllocate;
				$i++;
			}
		}
	}
	
	return $paymentid;
}

function createCustomerCC($customerid, $ccInfo) {
	$creditCard = new CreditCard('customer_credit_card');
	$values = array('customerid' => $customerid);
	$creditCardid = $creditCard->create($values);
	$primaryExists = $creditCard->customerHasPrimaryCC($customerid);
	$creditCardInfo['primary'] = !$primaryExists ? 1 : 0;
	$creditCard->update($creditCardid, $ccInfo);
	
	return $creditCardid;
}

// Enrollment: if adding additional campers, allow user to cancel and go to billing
function cancelToBilling($completedCampers) {
	global $_bc;
	if (!empty($completedCampers)) {
		?>
		<div class="info field">
			<div class="inner">
				<p>
					Don't want to add another camper?
					<br />
					<a href="<?= CR . $_bc->path ?>/billing-information">Cancel &amp; continue to Billing Information.</a>
				</p>
			</div>
		</div>
		<?
	}
}


function isSessionFull ($gender, $session) {
	if ($gender == 'M' && is_numeric($session['male_capacity']) && $session['male_capacity'] <= $session['male_enrollment']) {
		//male is full
		return true;	
	}
	else if ($gender == 'F' && is_numeric($session['female_capacity']) && $session['female_capacity'] <= $session['female_enrollment']) {
		//female is full
		return true;
	}
	else if (is_numeric($session['capacity']) && $session['capacity'] <= ($session['female_enrollment'] + $session['male_enrollment']) ) {
		//normal capacity is full
		return true;	
	}
	
	return false;
}


function creditCardForm ($formArgs, $states, $countries, $months, $years, $cctypes, $creditCard = NULL) {
	if (empty($creditCard)) {
		// standard credit card "array object"
		$creditCard = array(
			'credit_card_id' => NULL
			, 'first_name' => NULL
			, 'last_name' => NULL
			, 'phone' => NULL
			, 'address' => NULL
			, 'address2' => NULL
			, 'city' => NULL
			, 'province' => NULL
			, 'state_id' => NULL
			, 'country_id' => USID
			, 'zip' => NULL
			, 'credit_card_type' => NULL
			, 'expiration_month' => NULL
			, 'expiration_year' => NULL
		);
	}
	
	$ccForm = new EmgForm($formArgs);
	
	$ccForm->legend('Billing Address');
	
	$ccForm->ulStart();
	
	$ccForm->text(array(
		'label' => 'First Name'
		, 'class' => 'val_req'
		, 'value' => $creditCard['first_name']
	));
	$ccForm->text(array(
		'label' => 'Last Name'
		, 'class' => 'val_req'
		, 'value' => $creditCard['last_name']
	));
	$ccForm->phone(array(
		'label' => 'Phone'
		, 'name' => 'phone'
		, 'value' => $creditCard['phone']
	));
	$ccForm->address(array(
		'label' => 'Street Address'
		, 'class' => 'val_req'
		, 'value' => array(
			'address' => $creditCard['address']
			, 'address2' => $creditCard['address2']
			, 'city' => $creditCard['city']
			, 'state_id' => $creditCard['state_id']
			, 'province' => $creditCard['province']
			, 'country_id' => $creditCard['country_id']
			, 'zip' => $creditCard['zip']
		)				 	 
		, 'us' => $creditCard['country_id']
		, 'states' => $states
		, 'countries' => $countries
	));
	$ccForm->ulEnd();
	
	$ccForm->legend('Credit Card');
	
	$ccForm->ulStart();
	
	$ccForm->creditCard(array(
		'value' => array(
			'credit_card_type' => $creditCard['credit_card_type']
			, 'expiration_month' => $creditCard['expiration_month']
			, 'expiration_year' => $creditCard['expiration_year']
		)
		, 'ccvc' => true
		, 'creditCardTypes' => $cctypes
		, 'months' => $months
		, 'years' => $years
	));
	$ccForm->ulEnd();
}
?>