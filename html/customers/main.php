<h2>Customers List</h2>

<?
require DR . '/html-parts/table-actions.php';
?>

<table class="search-results customers">
	<?
	require DR . '/html-parts/row-headers.php';
	
	foreach ($this->ld['rows'] as $i => $row) {
		// Customer details page
		$detailsUrl = $this->url($GLOBALS['bc']->path . '/details') . '&amp;customer_id=' . $row['customer_id'];
		$customerFullName = $row['first_name'] . ' ' . $row['last_name'];
		?>
		<tr>
			<td class="row-count"><?= $i + 1 ?></td>
			<td class="first-name">
				<a href="<?= $detailsUrl ?>" title="View Details for <?= $customerFullName ?>">
					<?= $row['first_name'] ?>
				</a>
			</td>
			<td class="last-name">
				<a href="<?= $detailsUrl ?>" title="View Details for <?= $customerFullName ?>">
					<?= $row['last_name'] ?>
				</a>
			</td>
			<td class="email">
				<a href="mailto:<?= $row['email'] ?>" title="Email <?= $customerFullName ?>">
					<?= $row['email'] ?>
				</td>
			<td class="camper-count"><?= $row['camper_count'] ?></td>
		</tr>
		<?
	}
	?>
</table>