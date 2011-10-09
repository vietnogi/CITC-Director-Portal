<?
$prefix = 'pp-parent-info-';
$this->gd['pph']->h2('Parent Information', $this->gd['pp']->camper);
$form = new emgForm(array_merge(array(
	'id' => 'pp-parent-info-form'
	, 'action' =>  $this->actionUrl() . '&amp;parent_pack_id=' . $this->gd['pp']->id
	, 'method' => 'post'
	, 'class' => 'emg-form val-form columns'
) , $this->gd['emg_form_args']));

for ($i = 0; $i < $this->ld['guardian_count']; $i++) {
	$req = ($i == 0) ? 'val_req' : '';
	$form->legend('Legal Guardian ' . ($i + 1) . ' Information' . ($i == 0 ? ' (Required)' : '' ));
	?>
	<input type="hidden" name="legal_guardian_id[<?= $i ?>]" value="<?= $this->ld['guardians'][$i]['legal_guardian_id'] ?>" />
	<?	
	$form->text(array(
		'label' => 'Parent First Name'
		, 'name' => 'first_name[' . $i . ']'
		, 'class' => $req
		, 'value' => $this->ld['guardians'][$i]['first_name']
	));
	$form->text(array(
		'label' => 'Parent Last Name'
		, 'name' => 'last_name[' . $i . ']'
		, 'class' => $req
		, 'value' => $this->ld['guardians'][$i]['last_name']
	));
	$form->address(array(
		'label' => 'Parent Address'
		, 'name' => '[' . $i . ']'
		, 'class' => $req
		, 'value' => $address = array(
			'address' => $this->ld['guardians'][$i]['street']
			, 'address2' => $this->ld['guardians'][$i]['street_2']
			, 'city' => $this->ld['guardians'][$i]['city']
			, 'state_id' => $this->ld['guardians'][$i]['state_id']
			, 'province' => $this->ld['guardians'][$i]['province']
			, 'country_id' => $this->ld['guardians'][$i]['country_id']
			, 'zip' => $this->ld['guardians'][$i]['zip']
		)
		, 'states' => $this->gd['states']
		, 'countries' => $this->gd['countries']
	));
	$form->phone(array(
		'label' => 'Parent Work Phone'
		, 'name' => 'work_phone[' . $i . ']'
		, 'value' => phoneFormat($this->ld['guardians'][$i]['work_phone'])
		, 'class' => $req
	));
	$form->phone(array(
		'label' => 'Parent Cell Phone'
		, 'name' => 'cell_phone[' . $i . ']'
		, 'value' => phoneFormat($this->ld['guardians'][$i]['cell_phone'])
		, 'class' => $req
	));
	$form->phone(array(
		'label' => 'Parent Other Phone'
		, 'name' => 'other_phone[' . $i . ']'
		, 'value' => phoneFormat($this->ld['guardians'][$i]['other_phone'])	
	));
}

$this->gd['pph']->submitButton($form);
?>