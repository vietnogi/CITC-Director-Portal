<div id="aside">
	<div id="navs">
		<div class="box">
			<h2 class="heading"><?= SITENAME ?></h2>
			
			<ul id="nav" class="nav">
				<?
				foreach ($_navs as $text => $link) {
					$dirname = dirname($link);
					if(!$_permission->isauthorize($link) && $text != 'Logout'){
						continue;
					}
					?>
					<li<?= $_bc->path == $dirname ? ' class="current"' : '' ?>><a href="<?= CR ?><?= $link ?>"><?= $text ?></a></li>
					<?
				}
				?>
			</ul>
			
			<p id="nav-date"><?= date('D M j Y') ?></p>
		</div>
		
		<?
		if ($_bc->page == 'summary' && !empty($_areas)) {
			?>
			<div id="nav-summary" class="box">
				<h3 class="heading">Summary</h3>
				<ul class="nav icons">
					<?
					$areas = array('Top' => '') + $_areas;
					foreach($areas as $area => $path){
						$cleanArea = cleanUrl($area);
						$link = (!empty($path) ? 'summary-' : '') . cleanUrl($area);
						
						if(!$_permission->isauthorize($_bc->path.$path)){
							continue;
						}
						?>
						<li class="<?= $cleanArea ?>"><a href="#<?= $link ?>" title="Scroll to <?= $area ?>"><?= $area ?></a></li>
						<?	
					}
					if($_bc->path == '/projects' && $_permission->isauthorize('/clients')){
						?>
						<li class="client"><a href="<?= CR ?>/clients/summary?clientid=<?= $_project['clientid'] ?>">View Client</a></li>
						<?
					}
					?>
					<li class="print"><a href="<?= CR . $_bc->path ?>/print-summary?<?= $_keyField ?>=<?= $_GET[$_keyField] ?>&amp;print=1">Print</a></li>
				</ul>
			</div>
			<?
		}
		?>
	</div>
</div>
