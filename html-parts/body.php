<?
require DR . '/html-parts/header.php';
?>
<div id="content-wrapper" class="content-wrapper body-block">
	<div class="inner">
		<div id="content">
			<?
			require DR . '/html-parts/action-msg.php';
			$html = '/html' . $this->p;
			if(file_exists(DR . $html)){
				require DR . $html;
			}
			else if ($this->cms != NULL) {
				echo $this->cms['body'];
			}
			else{
				trigger_error('No HTML available.', E_USER_WARNING);
			}
			?>
		</div>
		<?
		$sidebarFile = DR . '/html-parts/sidebar.php';
		if (file_exists($sidebarFile)) {
			require $sidebarFile;
		}
		?>
	</div>
</div>
<?
require DR . '/html-parts/footer.php';
?>
