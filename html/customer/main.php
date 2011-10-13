<h2>Customers List</h2>
<div class="table-actions">
	<p class="go add"><a href="<?= $this->url('/customer/add') ?>" title="Add a new row">+ Add a new customer</a></p>
</div>
<table class="search-results">
	<?
	require DR . '/html-parts/row-headers.php';
	foreach ($this->ld['rows'] as $i => $row) {
		?>
		<tr class="<?= $i % 2 == 1 ? 'even' : 'odd' ?>">
			<td class="row-count"><?= $i + 1 ?></td>
			<?
			foreach ($row as $field => $value) {
				?>
				<td class="<?= cleanUrl($field) ?>">
				<?= $value ?>
				</td>
				<?
			}
			?>
		</tr>
		<?
	}
	?>
</table>