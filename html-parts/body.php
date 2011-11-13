<div id="container"<?= LOGGEDIN !== true ? ' class="no-login"' : '' ?>>
	<?	
	// Admin navigation
	if (LOGGEDIN === true){
		require DR . '/html-parts/sidebar.php';
	}
	?>
	<div id="content-container">
		<?
		require DR . '/html-parts/header.php';
		?>
		<div id="content">
			<?
			require DR . '/html-parts/user-msg.php';
			$html = '/html' . $this->p;
			if (file_exists(DR . $html)) {
				require DR . $html;
			}
			else {
				trigger_error('No HTML available.', E_USER_WARNING);
			}
			?>
		</div>
	</div>
</div>
