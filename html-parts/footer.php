<div id="footer" class="footer body-block">
	<div class="inner">
		<?
		navList(array('navs' => $this->gd['navs']
					, 'selector' => 'ul.nav'
					)
			   );
		?>
		
		<p class="legal">&copy; <?= date('Y') ?> <a href="<?= CR ?>/"><?= SITENAME ?></a>. All Rights Reserved.</p>
	</div>
</div>
