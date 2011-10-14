<h2>Customer <?= $this->ld['customer']['first_name'] ?></h2>
<div>
	<h3>Detail Information</h3>
	<div id="detail">
		<?
		pr($this->ld['customer']);
		?>
		<a href="javascript: void(1)" class="toggle" metadata="{class: 'hidden', selector: '#detail, #edit-detail'}">Edit</a>
	</div>
	
	<div id="edit-detail" class="hide">
		<?
		$form = new emgForm(array_merge(array(
			'id' => 'customer-info-form'
			, 'action' =>  $this->actionUrl() . '&amp;customer_id=' . $this->ld['customer']['customer_id']
			, 'method' => 'post'
			, 'class' => 'emg-form val-form columns'
		) , array()));
		
		$form->legend('Customer Information');
		
		$form->text(array(
			'label' => 'First Name'
			, 'class' => 'req'
			, 'value' => $this->ld['customer']['first_name']
		));
		
		$form->text(array(
			'label' => 'Last Name'
			, 'class' => 'req'
			, 'value' => $this->ld['customer']['last_name']
		));
		
		$form->text(array(
			'label' => 'Email'
			, 'class' => 'req email'
			, 'value' => $this->ld['customer']['email']
		));
		
		
		$form->submit(array(
			'value' => 'Save'
		));
		?>
		<a href="javascript: void(1)" class="toggle" metadata="{class: 'hidden', selector: '#detail, #edit-detail'}">Cancel Edit</a>
	</div>
</div>
<div class="ajax-fill" metadata="{url: '<?= $this->url('/ajax' . $GLOBALS['bc']->path . '/comments/main') ?>&customer_id=<?= $this->ld['customer']['customer_id'] ?>'}"></div>