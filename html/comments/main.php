<h3>Comments (<?= count($this->ld['comments']) ?>)</h3>
<p><strong>Keyword Search:</strong></p>
<?
$for = $this->gd['comment_for'];
foreach ($this->ld['comments'] as $comment) {
	$commentid = $comment[$for . '_comment_id'];
	?>
	<p>
		<strong><?= $comment['first_name'] . ' ' . $comment['last_name'] ?></strong>
		<em><?= date('d M Y, g:ia', strtotime($comment['created'])) ?></em>
	</p>
	<p id="comment-<?= $commentid ?>"><?= $comment['comment'] ?></p>
	<form id="edit-comment-form-<?= $commentid ?>" action="<?= $this->actionUrl('edit') ?>&amp;parent_comment_id=<?= $commentid ?>&amp;for=<?= $for ?>" method="post" class="emg-form val-form ajax-submit columns hide">
		<textarea id="edit-comment-form-<?= $commentid ?>-comment" name="comment" class="req comment-edit" rows="" cols=""><?= $comment['comment'] ?></textarea>
		<input type="submit" value="update" />
		<li><a href="javascript: void(1)" class="toggle" data-json="{class: 'hidden', selector: '#comment-<?= $commentid ?>, #edit-comment-form-<?= $commentid ?>'}">Cancel Edit</a></li>
	</form>
	<ul>
		<li><a href="javascript: void(1)" class="toggle" data-json="{class: 'hidden', selector: '#comment-<?= $commentid ?>, #edit-comment-form-<?= $commentid ?>'}">Edit</a></li>
		<li><a href="<?= $this->actionUrl('delete') ?>&amp;parent_comment_id=<?= $commentid ?>&amp;for=<?= $for ?>" class="ajax-submit">Delete</a></li>
	</ul>
	<? 
}
$form = new emgForm(array_merge(array(
	'id' => 'new-comment-form'
	, 'action' => $this->actionUrl('add') . '&amp;' . $for . '_id=' . $this->ld['inputs'][$for . '_id'] . '&amp;for=' . $for
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