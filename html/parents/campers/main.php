<h3>Campers (<?= count($this->ld['campers']) ?>)</h3>
<?
foreach ($this->ld['campers'] as $camper) {
	?>
	<p>
		<a href="<?= $this->url('/campers/detail') ?>&amp;camper_id=<?= $camper['camper_id'] ?>"><?= $camper['first_name'] ?> <?= $camper['last_name'] ?></a>
	</p>
	<?
}
?>
<a href="<?= $this->url('/bare' . $GLOBALS['bc']->path . '/add') ?>" class="modal">Enroll a new camper</a>