<div id="header" class="header">
	<h1 id="logo"><a href="<?= $this->url('/dashboard') ?>"><?= SITENAME ?></a></h1>
	<?
	if (LOGGEDIN === true) {
		?>
		<div class="account-info">
			<p class="go"><a href="<?= $this->url('/action/logout') ?>">Logout</a></p>
			<p class="welcome">Welcome, <strong><?= $this->gd['staff']['first_name'] ?></strong></p>
		</div>
		<?
	}
	?>
</div>
