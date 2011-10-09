<?
$pre = 'pp-camper-info-';

$this->gd['pph']->h2('Camper Information', $this->gd['pp']->camper);
?>

<h3>Basic Camper Information</h3>
<p>
	<strong><?= $this->gd['pp']->camper['first_name'] . ' ' . $this->gd['pp']->camper['last_name'] ?></strong>
	<br />
	<?= $GLOBALS['dates']->usFormat($this->gd['pp']->camper['date_of_birth']) ?>
	<br />
	<?= $this->gd['pp']->camper['sex'] == 'M' ? 'Male' : 'Female' ?>
</p>

<?
$form = new emgForm(array_merge(array(
	'id' => 'pp-camper-info-form'
	, 'action' =>  $this->actionUrl() . '&amp;parent_pack_id=' . $_GET['parent_pack_id']
	, 'method' => 'post'
	, 'class' => 'emg-form val-form columns'
) , $this->gd['emg_form_args']));

$form->legend('Camper Contact Information');

$form->text(array(
	'label' => 'Email Address'
	, 'name' => 'email'
	, 'class' => 'val-email'
	, 'value' => $this->gd['pp']->camper['email']
));

$form->phone(array(
	'label' => 'Cell Phone'
	, 'name' => 'phone'
	, 'class' => 'val-phone'
	, 'value' => phoneFormat($this->gd['pp']->camper['phone'])
));

$form->legend('Bunking Requests');

$numBunkRequests = count($this->ld['bunk_requests']);
foreach (range(1, $numBunkRequests) as $i) {
	$form->text(array(
		'label' => 'Friend ' . $i
		, 'id' => 'friend-' . $i
		, 'name' => 'friends[' . $i . ']'
		, 'value' => $this->ld['bunk_requests'][$i - 1]['name']
		, 'after' => ($i == $numBunkRequests) ? '<p class="supplement add-friend"><a href="#">Add Friend</a></p>' : ''
	));
}

$this->gd['pph']->submitButton($form);
?>
