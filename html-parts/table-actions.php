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
	<ul class="global buttons">
		<li class="export csv"><a href="<?= $this->url('/bare' . $GLOBALS['bc']->path . '/csv') ?>&amp;<?= $_SERVER['QUERY_STRING'] ?>" title="Export entire list (all pages) to CSV/Excel">Export All to CSV</a></li>
		<li class="print"><a href="<?= $this->url('/main', true) ?><?= empty($_SERVER['QUERY_STRING']) ? '' : '&amp;' . $_SERVER['QUERY_STRING'] ?>&amp;print=1" rel="external" title="Print the list you are currently viewing">Printer Friendly</a></li>
	</ul>
</div>
