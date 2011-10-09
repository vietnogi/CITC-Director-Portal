<?
$this->gd['pph']->h2('Medical Information &rsaquo; Insurance Detail', $this->gd['pp']->camper);
$form = new emgForm(array_merge(array(
	'id' => 'pp-insurance-form'
	, 'action' =>  $this->actionUrl() . '&amp;parent_pack_id=' . $_GET['parent_pack_id']
	, 'method' => 'post'
	, 'class' => 'emg-form val-form columns'
), $this->gd['emg_form_args']));

$form->legend('Insurance Information');
?>
<input type="hidden" name="insurance_id" value="<?= $this->ld['insurance']['insurance_id'] ?>" />
<?

$form->ssn(array(
	'label' => 'Camper Social Security #'
	, 'name' => 'camper_ssn'
	, 'value' => $this->ld['insurance']['camper_ssn']
	, 'class' => 'val-req'
));

$form->legend('Camper\'s Insurance Information');
?>
<div class="info">
	<div class="inner">
		<p>Please send us a copy of both sides of your insurance cards.</p>
	</div>
</div>
<?
$form->text(array(
	'label' => 'Insurance Carrier'
	, 'name' => 'carrier'
	, 'class' => 'val-req'
	, 'value' => $this->ld['insurance']['carrier']
));
$form->address(array(
	'label' => 'Insurance Carrier Address'
	, 'name' => '[0]'
	, 'class' => 'val-req'
	, 'value' => array(
		'address' => $this->ld['insurance']['street']
		, 'address2' => $this->ld['insurance']['street_2']
		, 'city' => $this->ld['insurance']['city']
		, 'state_id' => $this->ld['insurance']['state_id']
		, 'province' => $this->ld['insurance']['province']
		, 'country_id' => $this->ld['insurance']['country_id']
		, 'zip' => $this->ld['insurance']['zip']
	)
	, 'states' => $this->gd['states']
	, 'countries' => $this->gd['countries']
	)
);
$form->phone(array(
	'label' => 'Insurance Carrier Phone'
	, 'name' => 'phone'
	, 'value' => $this->ld['insurance']['phone']
	, 'class' => 'val-req'
));
$form->text(array(
	'label' => 'Policy Number'
	, 'class' => 'val-req'
	, 'value' => $this->ld['insurance']['policy_number']
	, 'name' => 'policy_number'
));
$form->text(array(
	'label' => 'Group Number'
	, 'class' => 'val-req'
	, 'value' => $this->ld['insurance']['group_number']
	, 'name' => 'group_number'
));
$form->date(array(
	'label' => 'Effective Date'
	, 'value' => $this->ld['insurance']['effective_date']
	, 'name' => 'effective_date'
	, 'class' => 'val-req'
));
$form->text(array(
	'label' => 'Policy Subscriber Name'
	, 'name' => 'subscriber_name'
	, 'class' => 'val-req'
	, 'value' => $this->ld['insurance']['subscriber_name']
));
$form->date(array(
	'label' => 'Subscriber Date Of Birth'
	, 'value' => $this->ld['insurance']['subscriber_date_of_birth']
	, 'name' => 'subscriber_date_of_birth'
	, 'class' => 'val-req'
));
$form->ssn(array(
	'label' => 'Subscriber Social Security #'
	, 'value' => $this->ld['insurance']['subscriber_ssn']
	, 'name' => 'subscriber_ssn'
	, 'class' => 'val-req'
));
$form->address(array(
	'label' => 'Subscriber Address'
	, 'name' => '[1]'
	, 'class' => 'val-req'
	, 'value' => array(
		'address' => $this->ld['insurance']['subscriber_street']
		, 'address2' => $this->ld['insurance']['subscriber_street_2']
		, 'city' => $this->ld['insurance']['subscriber_city']
		, 'state_id' => $this->ld['insurance']['subscriber_state_id']
		, 'province' => $this->ld['insurance']['subscriber_province']
		, 'country_id' => $this->ld['insurance']['subscriber_country_id']
		, 'zip' => $this->ld['insurance']['subscriber_zip']
	)
	, 'states' => $this->gd['states']
	, 'countries' => $this->gd['countries']
));
$form->phone(array(
	'label' => 'Subscriber Phone'
	, 'value' => $this->ld['insurance']['subscriber_phone']
	, 'class' => 'val-req'
));
$this->gd['pph']->submitButton($form);
?>