<h2>Season: <?= $this->ld['season']['name'] ?></h2>
<h3>Detail Information</h3>
<div id="detail">
	<?
	pr($this->ld['season']);
	?>
	<a href="javascript: void(1)" class="toggle" data-json="{class: 'hidden', selector: '#detail, #edit-detail'}">Edit</a>
</div>

<div id="edit-detail" class="hide">
	{For Pending}
	<a href="javascript: void(1)" class="toggle" data-json="{class: 'hidden', selector: '#detail, #edit-detail'}">Cancel Edit</a>
</div>

<h3>Image</h3>
<img src="<?= $this->url('/image?path=season/1&amp;w=100&amp;h=100&amp;quality=90&amp;ratio=1:1') ?>" id="season-img" />
<a href="#" class="upload-image-crop" data-json="{for: 'season', id: '<?= $this->ld['season']['season_id'] ?>', crop: {aspectRatio: 4/3}}">Upload Image</a>

<h3>Description</h3>

<form id="season-description-form" action="<?= $this->actionUrl('description') ?>&amp;season_id=<?= $this->ld['season']['season_id'] ?>" method="post">
	<textarea id="wysiwyg" rows="10" cols="130" name="description"><?= $this->ld['season']['description'] ?></textarea>
	<input type="submit" value="save" />
</form>