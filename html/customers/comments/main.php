<h3>Comments (<?= count($this->ld['comments']) ?>)</h3>
<?
foreach ($this->ld['comments'] as $comment) {
	?>
	<p>
		<strong>Alex Tran</strong> <em>13 Oct 2011, 5:57pm</em>
	</p>
	<p>
		Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
	</p>
	<?
}
$form = new emgForm(array_merge(array(
	'id' => 'new-comment-form'
	, 'action' => $this->url('/action' . $GLOBALS['bc']->path . '/add') . '&amp;customer_id=' . $this->ld['inputs']['customer_id']
	, 'method' => 'post'
	, 'class' => 'emg-form val-form ajax-submit columns'
) , array()));

$form->textarea(array(
	'label' => NULL
	, 'name' => 'comment'
	, 'class' => 'req default-clear'
	, 'value' => 'add a comment'
));


$form->submit(array(
	'value' => 'Post Comment'
));
?>