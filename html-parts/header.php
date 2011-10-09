<div id="header" class="header body-block">
	<div class="inner">
		<h1 id="logo"><a href="<?= CR ?>/" title="<?= SITENAME ?>"><?= SITENAME ?></a></h1>
		
		<?
		navList(array('navs' => $this->gd['navs']
					, 'selector' => 'ul#nav.nav'
					)
			   );
		?>
	</div>
</div>
