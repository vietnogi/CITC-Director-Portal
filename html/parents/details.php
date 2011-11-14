<h2>Parent <?= $this->ld['parent']['first_name'] ?></h2>
<div>
	<h3>Detail Information</h3>
	<div id="detail">
		<?
		pr($this->ld['parent']);
		?>
		<a href="javascript: void(1)" class="toggle" data-json="{class: 'hidden', selector: '#detail, #edit-detail'}">Edit</a>
	</div>
	
	<div id="edit-detail" class="hide">
		<?
		$form = new emgForm(array_merge(array(
			'id' => 'parent-info-form'
			, 'action' =>  $this->actionUrl() . '&amp;parent_id=' . $this->ld['parent']['parent_id']
			, 'method' => 'post'
			, 'class' => 'emg-form val-form columns'
		) , array()));
		
		$form->legend('Parent Information');
		
		$form->text(array(
			'label' => 'First Name'
			, 'class' => 'req'
			, 'value' => $this->ld['parent']['first_name']
		));
		
		$form->text(array(
			'label' => 'Last Name'
			, 'class' => 'req'
			, 'value' => $this->ld['parent']['last_name']
		));
		
		$form->text(array(
			'label' => 'Email'
			, 'class' => 'req email'
			, 'value' => $this->ld['parent']['email']
		));
		
		
		$form->submit(array(
			'value' => 'Save'
		));
		?>
		<a href="javascript: void(1)" class="toggle" data-json="{class: 'hidden', selector: '#detail, #edit-detail'}">Cancel Edit</a>
	</div>
</div>
<div class="ajax-fill" data-json="{href: '<?= $this->url('/bare/comments/main') ?>?parent_id=<?= $this->ld['parent']['parent_id'] ?>&amp;for=parent'}"></div>
<div class="ajax-fill" data-json="{href: '<?= $this->url('/bare' . $GLOBALS['bc']->path . '/campers/main') ?>?parent_id=<?= $this->ld['parent']['parent_id'] ?>'}"></div>

<h3>Outstanding Balance</h3>

<h3>Invoice History</h3>

<h3>Payment History</h3>
<a href="">Make a payment</a>