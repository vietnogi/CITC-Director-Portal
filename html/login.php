<?
$prefix = 'create-account';
?>
<h2>Login</h2>

<div class="login-container">
	<form id="<?= $prefix ?>-form" action="<?= CR ?>/action/create-customer" class="emg-form val-form login-form" method="post">
		<h3>Create an Account</h3>
		<p class="hint"><em class="required">*</em> is required.</p>
		<ul class="field-sets">
			<li>
				<ul class="fields">
					<li>
						<label for="<?= $prefix ?>-first-name">First Name <em>*</em></label>
						<input type="text" id="<?= $prefix ?>-first-name" name="first_name" class="val-req" />
					</li>
					<li>
						<label for="<?= $prefix ?>-last-name">Last Name <em>*</em></label>
						<input type="text" id="<?= $prefix ?>-last-name" name="last_name" class="val-req" />
					</li>
				</ul>
			</li>
			<li>
				<ul class="fields">
					<li>
						<label for="<?= $prefix ?>-email">Email Address <em>*</em></label>
						<input type="text" id="<?= $prefix ?>-email" name="email" class="val-req val-email autocomplete-off" />
					</li>
					<li>
						<label for="<?= $prefix ?>-email-confirm">Confirm Email Address <em>*</em></label>
						<input type="text" id="<?= $prefix ?>-email-confirm" name="email-confirm" class="val-req val-same <?= $prefix ?>-email autocomplete-off" />
					</li>
					<li>
						<ul class="options">
							<li>
								<input type="checkbox" id="<?= $prefix ?>-offers" name="offers" value="1" />
								<label for="<?= $prefix ?>-offers">Check this box if you would like to receive<br />information and offers from Woodward.</label>
							</li>
						</ul>
					</li>
				</ul>
			</li>
			<li>
				<ul class="fields">
					<li>
						<label for="<?= $prefix ?>-password">Password <em>*</em></label>
						<input type="password" id="<?= $prefix ?>-password" name="password" class="val-req val-min-len 8 autocomplete-off" />
						<div class="info field">
							<div class="inner">
								<p>Password should be 8 characters minimum.</p>
							</div>
						</div>
					</li>
					<li>
						<label for="<?= $prefix ?>-password-confirm">Confirm Password <em>*</em></label>
						<input type="password" id="<?= $prefix ?>-password-confirm" name="password-confirm" class="val-req val-same <?= $prefix ?>-password autocomplete-off" />
					</li>
				</ul>
			</li>
			<?
			if (notEmptyArray($this->ld['referrers'])) {
				?>
				<li>
					<ul class="fields">
						<li>
							<label for="<?= $prefix ?>-referrer">How did you hear about us? <em>*</em></label>
							<?
							htmlSel($this->ld['referrers'], 'id="' . $prefix . '-referrer" name="referrer" class="val-req"', '', false, 'Please select&hellip;');
							?>
						</li>
					</ul>
				</li>
				<?
			}
			?>
		</ul>
		<div class="info submit">
			<div class="inner">
				<p>We won't share or sell your email address.<? /*<br />View our <a href="#">Privacy Policy</a>. */ ?></p>
			</div>
		</div>
		<fieldset>
			<input type="submit" value="Create Account" />
		</fieldset>
	</form>
	<form id="login-form" action="<?= $this->actionUrl() ?>" class="emg-form val-form login-form" method="post">
		<h3><span class="hint">or</span> Login with Your Account</h3>
		<p class="hint"><em class="required">*</em> is required.</p>
		<ul class="fields">
			<li>
				<label for="login-login">Email Address <em>*</em></label>
				<input type="text" id="login-login" name="login" class="val-req" />
			</li>
			<li>
				<label for="login-password">Password <em>*</em></label>
				<input type="password" id="login-password" name="password" class="val-req" />
			</li>
		</ul>
		<p class="hint"><a href="#">Forgot your password?</a></p>
		<fieldset>
			<input type="submit" value="Login" />
		</fieldset>
	</form>
</div>