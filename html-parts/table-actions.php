<div class="table-actions">
	<?
	// If first item is Add
	$firstAction = array_slice($this->ld['table_actions'], 0, 1);
	$name = key($firstAction);
	$url = $firstAction[$name];
	if (strpos($name, 'Add') === 0) {
		?>
		<p class="go add"><a href="<?= $url ?>">+ <?= $name ?></a></p>
		<?
	}
	
	foreach ($this->ld['table_actions'] as $name => $url) {
	}
	?>
</div>