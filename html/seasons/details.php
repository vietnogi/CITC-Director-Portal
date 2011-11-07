<h2>Season: <?= $this->ld['season']['name'] ?></h2>
<div>
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
</div>
<form id="upload-season-image" method="post" action="<?= $this->actionUrl('/upload-image') ?>&amp;season_id=<?= $this->ld['season']['season_id'] ?>" enctype="multipart/form-data" class="ajax-submit">
	<!--input type="input" name="UPLOAD_IDENTIFIER" value="e415457fb14baa4537b22bb46ec8a593" /--> 
    <label>Select File:</label> 
    <input type="file" name="season_image" />
    <input type="submit" value="Upload File" />
</form>