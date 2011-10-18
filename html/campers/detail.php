<h2>camper <?= $this->ld['camper']['first_name'] ?></h2>
<div>
	<h3>Detail Information</h3>
	<div id="detail">
		<?
		pr($this->ld['camper']);
		?>
		<a href="javascript: void(1)" class="toggle" metadata="{class: 'hidden', selector: '#detail, #edit-detail'}">Edit</a>
	</div>
	
	<div id="edit-detail" class="hide">
		<?
		$form = new emgForm(array_merge(array(
			'id' => 'camper-info-form'
			, 'action' =>  $this->actionUrl() . '&amp;camper_id=' . $this->ld['camper']['camper_id']
			, 'method' => 'post'
			, 'class' => 'emg-form val-form columns'
		) , array()));
		
		$form->legend('Camper Information');
		
		$form->text(array(
			'label' => 'First Name'
			, 'class' => 'req'
			, 'value' => $this->ld['camper']['first_name']
		));
		
		$form->text(array(
			'label' => 'Last Name'
			, 'class' => 'req'
			, 'value' => $this->ld['camper']['last_name']
		));
		
		$form->text(array(
			'label' => 'Email'
			, 'class' => 'req email'
			, 'value' => $this->ld['camper']['email']
		));
		
		
		$form->submit(array(
			'value' => 'Save'
		));
		?>
		<a href="javascript: void(1)" class="toggle" metadata="{class: 'hidden', selector: '#detail, #edit-detail'}">Cancel Edit</a>
	</div>
</div>
<div class="ajax-fill" data-json="{href: '<?= $this->url('/ajax/comments/main') ?>&amp;camper_id=<?= $this->ld['camper']['camper_id'] ?>&amp;for=camper'}"></div>
