<h2>Seasons</h2>

<?
require DR . '/html-parts/table-actions.php';
?>

<table class="search-results parents">
	<?
	require DR . '/html-parts/row-headers.php';
	
	foreach ($this->ld['rows'] as $i => $row) {
		// Parent details page
		$detailsUrl = $this->url($GLOBALS['bc']->path . '/details') . '&amp;season_id=' . $row['season_id'];
		?>
		<tr>
			<td class="row-count"><?= $i + 1 ?></td>
			<td class="name">
				<a href="<?= $detailsUrl ?>" title="View Details for <?= $row['name'] ?>">
					<?= $row['name'] ?>
				</a>
				(<?= $row['active'] == '1' ? 'active' : 'inactive' ?>)
			</td>
			<td class="enrollment-start">
				<?= $GLOBALS['dates']->friendlyFormat($row['enrollment_start_date']) ?> - <?= $GLOBALS['dates']->friendlyFormat($row['enrollment_end_date']) ?>
			</td>
			<td class="enrollment-count">
				<a href="mailto:<?= $row['email'] ?>" title="View Enrollments">
					<?= $row['session_count'] ?>
				</a>
			</td>
			<td class="session-count">
				<a href="mailto:<?= $row['email'] ?>" title="View Sessions">
					<?= $row['session_count'] ?>
				</a>
			</td>
			<td class="specialty-count">
				<a href="mailto:<?= $row['email'] ?>" title="View Sessions">
					<?= $row['session_count'] ?>
				</a>
			</td>
		</tr>
		<?
	}
	?>
</table>