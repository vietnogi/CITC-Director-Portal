<?
$form = new emgForm(array_merge(array(
	'id' => 'camper-add-form'
	, 'action' =>  $this->actionUrl()
	, 'method' => 'post'
	, 'class' => 'emg-form val-form columns'
) , array()));

$form->legend('Add a camper');

$form->text(array(
	'label' => 'First Name'
	, 'class' => 'req'
));

$form->text(array(
	'label' => 'Last Name'
	, 'class' => 'req'
));

$form->submit(array(
	'value' => 'Save'
));
?>
<div class="modal" data-json="{href: '<?= $this->url('/ajax' . $GLOBALS['bc']->path . '/add') ?>'}">Enroll a new camper</div>