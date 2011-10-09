<h2>Select a Season</h2>
<ul class="seasons">
	<?
	foreach ($this->ld['seasons'] as $season) {
		$url = $this->actionUrl() . '&amp;camper_index=' . $this->gd['camper_index'] . '&amp;season_id=' . $season['season_id'];
		?>
		<li>
			<h3><a href="<?= $url ?>"><?= $season['name'] ?></a></h3>
			<p class="image"><a href="<?= $url ?>" style="display: block; height: 130px; background: #eee;">(image)</a></p>
			<p class="description">Short description</p>
			<p class="go"><a href="<?= $url ?>">Select <?= $season['name'] ?></a></p>
		</li>
		<?
	}
	?>
</ul>