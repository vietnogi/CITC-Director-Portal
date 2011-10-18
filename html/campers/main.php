<h2>Campers List</h2>
<div class="table-actions">
	<p class="go add"><a href="<?= $this->url($GLOBALS['bc']->path . '/add') ?>" title="Add a new row">+ Add a new camper</a></p>
</div>
<style>
	tr:hover {
		cursor: pointer;
		background-color: #eee;
	}
</style>
<table class="search-results">
	<?
	require DR . '/html-parts/row-headers.php';
	foreach ($this->ld['rows'] as $i => $row) {
		?>
		<tr class="js-link <?= $i % 2 == 1 ? 'even' : 'odd' ?>" data-json="{href: '<?= $this->url($GLOBALS['bc']->path . '/detail') ?>&amp;camper_id=<?= $row['camper_id'] ?>'}" title="View <?= $row['first_name'] ?>">
			<td class="row-count"><?= $i + 1 ?></td>
			<td class="first-name"><?= $row['first_name'] ?></td>
			<td class="last-name"><?= $row['last_name'] ?></td>
			<td class="email"><?= $row['email'] ?></td>
		</tr>
		<?
	}
	?>
</table>