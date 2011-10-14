<h2>Customers List</h2>
<div class="table-actions">
	<p class="go add"><a href="<?= $this->url('/customer/add') ?>" title="Add a new row">+ Add a new customer</a></p>
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
		<tr class="<?= $i % 2 == 1 ? 'even' : 'odd' ?>" href="<?= $this->url($GLOBALS['bc']->path . '/detail') ?>&amp;customer_id=<?= $row['customer_id'] ?>" title="View <?= $row['first_name'] ?>">
			<td class="row-count"><?= $i + 1 ?></td>
			<td class="first-name"><?= $row['first_name'] ?></td>
			<td class="last-name"><?= $row['last_name'] ?></td>
			<td class="email"><?= $row['email'] ?></td>
			<td class="camper-count"><?= $row['camper_count'] ?></td>
		</tr>
		<?
	}
	?>
</table>