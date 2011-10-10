<div id="sidebar" class="sidebar">
	<div class="inner">
		<ul class="nav">
			<?
			foreach ($this->gd['navs'] as $cat => $navs) {
				if (!is_numeric($cat)) {
					listNavs($cat, $navs);
				}
				else {
					?>
					<li class="grouped">
						<?
						foreach ($navs as $cat2 => $navs2) {
							?>
							<ul>
								<?
								listNavs($cat2, $navs2, 1);
								?>
							</ul>
							<?
						}
						?>
					</li>
					<?
				}
			}
			?>
		</ul>
	</div>
	<div class="shadow"></div>
</div>
<?
function listNavs($cat, $navs) {
	$cleanCat = cleanUrl($cat);
	?>
	<li class="<?= $cleanCat ?>">
		<span><?= $cat ?></span>
		<ul class="navs">
			<?
			foreach ($navs as $nav => $url) {
				$dirname = dirname($url);
				?>
				<li<?= $GLOBALS['bc']->path == $dirname ? ' class="current"' : '' ?>>
					<a href="<?= CR . $url ?>"><?= $nav ?></a>
				</li>
				<?
			}
			?>
		</ul>
	</li>
	<?
}
?>