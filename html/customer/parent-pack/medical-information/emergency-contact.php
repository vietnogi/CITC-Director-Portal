<?
$__pph->h2('Medical Information &rsaquo; Emergency Contact', $_pp->camper);
$formTarget = 'pp-emergency-contact-form-target';
?>
<p>I understand that I will be contacted as soon as possible in the event that my child is brought to a hospital, clinic, or medical center. If I am not available, please contact:</p>
<?
$form = new emgForm(array_merge(array('id' => 'pp-emergency-contact-form'
									, 'action' =>  CR . '/action' . $_bc->uri . '?parent_packid=' . $_GET['parent_packid'] . '&amp;' . TOKEN
									, 'method' => 'post'
									, 'target' => $formTarget
									, 'class' => 'emg-form val-form columns'
									)
								, $__ppFormArgs
								)
					);

for($i = 0; $i < 2; $i++){
	$form->ulStart();
	if(!empty($_emergencyContacts[$i])){
		$form->hidden(array('name' => 'emergency_contactid[' . $i . ']'
							, 'value' => $_emergencyContacts[$i]['emergency_contactid']
							)
						);
	}
	$form->legend('Emergency Contact ' . ($i + 1));
	$form->text(array('label' => 'Name'
					  , 'name' => 'name[' . $i . ']'
					  , 'class' => 'val_req'
					  , 'value' => $_emergencyContacts[$i]['name']
					  )
				);
	$form->text(array('label' => 'Relationship'
					  , 'name' => 'relationship[' . $i . ']'
					  , 'class' => 'val_req'
					  , 'value' => $_emergencyContacts[$i]['relationship']
					  )
				);
	$form->phone(array('label' => 'Home Phone'
						 , 'name' => 'home_phone[' . $i . ']'
						 , 'value' => $_emergencyContacts[$i]['home_phone']
						 , 'class' => 'val_req'
						 )
					);
	$form->phone(array('label' => 'Work Phone'
						 , 'name' => 'work_phone[' . $i . ']'
						 , 'value' => $_emergencyContacts[$i]['work_phone']
						 , 'class' => 'val_req'
						 )
					);
	$form->phone(array('label' => 'Cell Phone'
						 , 'name' => 'cell_phone[' . $i . ']'
						 , 'value' => $_emergencyContacts[$i]['cell_phone']
						 , 'class' => 'val_req'
						 )
					);
	$form->ulEnd();
}
?>

<h3>Need Heading For This Section</h3>
<p>In the event that I am unavailable for purposes of providing parental consent, I hereby authorize the  physician(s) and staff at the hospital, clinic, or medical center to provide such care that includes diagnostic procedures and medical treatment as necessary to my minor child while said child is enrolled in Woodward West. I also authorize the release of all x-rays, test results, lab work or any other procedure that would be helpful in the follow-up care of my child. This medical treatment is to be given to my child without any further prior permission from the undersigned. I understand that the consent and authorization herein granted does not include major surgical procedures. A photostatic copy of this authorization shall be considered as effective and valid as the original.</p>
<p>I, the undersigned, authorize payment of medical benefits to the hospital, clinic, or medical center for any services furnished to my child by the physician. I understand that I am financially responsible for any amount not covered by my insurance contract. I also authorize you to release to my insurance company information concerning health care, advice, treatment or supplies provided to my child while attending Woodward West. This information will be used for the purpose of evaluating and administering the claim of benefits. This consent is valid for one year from the date indicated. A photostatic copy of this authorization shall be considered as effective and valid as the original.</p>
<p>In the event of an injury or illness requiring transportation to, or an evaluation at, an independent medical facility such as the hospital, clinic, or medical center, I authorize the release of all medical records generated at that facility to the medical staff or their representatives at Woodward West. I understand that this will enable continuity of care upon the camper's return to the camp and provide staff members a means of informing family members of camper's medical condition. I also understand that such records will remain a confidential and protected part of the camper's general record.</p>
<?
$form->ulStart();
$form->text(array('label' => 'Name'
					 , 'name' => 'medical_release_name'
					 , 'value' => $_pp->details['medical_release_name']
					 , 'class' => 'val_req'
					 )
				);
$form->date(array('label' => 'Date ' . ($_pp->details['medical_release_date'])
				  , 'value' => $_pp->details['medical_release_date']
				  , 'name' => 'medical_release_date'
				  , 'class' => 'val_req'
				  )
			);
$form->ulEnd();
?>

<h3>Emergency Credit Card Information</h3>
<p>In the event that your child needs treatment and/or medication and you cannot be reached, we will bill your credit card so that care is not delayed. This information will be kept in strict confidence.</p>

<h3>Billing Information</h3>
<?
/*if (!empty($_medicalCC)) {
	?>
    <h3>Billing Address</h3>
    <?
    formattedAddress($_medicalCC, 'billing');
	?>
    <h3>Credit Card</h3>
    <?
	formattedCreditCard($_medicalCC);
	?>
    <p class="use-different"><a href="#" class="use-different-credit-card">Use Different Credit Card</a></p>
    <?
}*/

require DR . '/parts/credit-card/options.php';

$__pph->submitButton($form);
?>

<iframe name="<?= $formTarget ?>" class="<?= DEVELOPMENT == '1' ? '' : 'hidden' ?>"></iframe>