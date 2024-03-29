<?

$form = new emgForm(array(
	'id' => 'login_box'
	, 'action' => $this->actionUrl()
	, 'method' => 'post'
	, 'class' => 'emg-form val-form'
));

$form->ulStart();

$form->text(array(
	'before' => NULL
	, 'label' => 'Email Address'
	, 'name' => 'login'
	, 'id' => 'login'
	, 'class' => 'val-req val-email'
	, 'value' => NULL
));
$form->password(array(
	'label' => 'Password'
	, 'name' => 'password'
	, 'id' => 'password'
	, 'class' => 'val_req'
	, 'value' => NULL
));

$form->checkboxes(array(array(
	'labelAfter' => 'Stay Signed In'
	, 'name' => 'stay_signed_in'
	, 'id' => 'stay_signed_in'
	, 'value' => '1'
)));

$form->ulEnd();

?>
<ul class="login-links">
	<li class="reset-password"><strong><a href="<?= $this->url('/reset-password-link') ?>">Forgot Password?</a></strong></li>
</ul>
<?

$form->submit(array('value' => 'Login'));
?>