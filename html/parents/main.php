<h2>Parents List</h2>

<?
require DR . '/html-parts/table-actions.php';
?>

<table class="search-results parents">
	<?
	require DR . '/html-parts/row-headers.php';
	
	foreach ($this->ld['rows'] as $i => $row) {
		// Parent details page
		$detailsUrl = $this->url($GLOBALS['bc']->path . '/details') . '&amp;parent_id=' . $row['parent_id'];
		$parentFullName = $row['first_name'] . ' ' . $row['last_name'];
		?>
		<tr>
			<td class="row-count"><?= $i + 1 ?></td>
			<td class="first-name">
				<a href="<?= $detailsUrl ?>" title="View Details for <?= $parentFullName ?>">
					<?= $row['first_name'] ?>
				</a>
			</td>
			<td class="last-name">
				<a href="<?= $detailsUrl ?>" title="View Details for <?= $parentFullName ?>">
					<?= $row['last_name'] ?>
				</a>
			</td>
			<td class="email">
				<a href="mailto:<?= $row['email'] ?>" title="Email <?= $parentFullName ?>">
					<?= $row['email'] ?>
				</a>
			</td>
			<td class="balance">$123.45</td>
			<td class="camper-count">{Undecided Session, Undecided Specialty, Balance Due, Parent Pack Due, Comments}</td>
		</tr>
		<?
	}
	?>
</table>