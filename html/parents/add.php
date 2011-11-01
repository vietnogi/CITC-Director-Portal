<h2>Add a new parent</h2>
<?
$form = new emgForm(array_merge(array(
	'id' => 'parent-add-form'
	, 'action' =>  $this->actionUrl()
	, 'method' => 'post'
	, 'class' => 'emg-form val-form columns'
) , array()));

$form->legend('Parent Information');

$form->text(array(
	'label' => 'First Name'
	, 'class' => 'req'
));
$form->text(array(
	'label' => 'Last Name'
	, 'class' => 'req'
));
$form->text(array(
	'label' => 'Email'
	, 'class' => 'req email'
));
$form->text(array(
	'label' => 'Retype Email'
	, 'class' => 'req email same parent-add-form'
));
$form->password(array(
	'label' => 'Password'
	, 'class' => 'req'
));
$form->password(array(
	'label' => 'Retype Password'
	, 'class' => 'req min 6'
));


$form->submit(array(
	'value' => 'Save'
));
?>