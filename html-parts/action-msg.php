<?
if (isset($_SESSION[CR]['user-success'])) {
	?>
	<p class="user-success"><?= $_SESSION[CR]['user-success'] ?></p>
	<?
	unset($_SESSION[CR]['user-success']);
}
if (isset($_SESSION[CR]['user-error'])) {
	?>
	<p class="user-error"><?= $_SESSION[CR]['user-error'] ?></p>
	<?
	unset($_SESSION[CR]['user-error']);
}
?>
