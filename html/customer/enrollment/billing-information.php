<h2>Billing Information</h2>
<form id="credit-card-info-form" action="<?= $this->actionUrl() ?>&amp;camper_index=<?= $this->gd['camper_index'] ?>" method="get" class="emg-form val-form">
	<div class="credit-card-options">
	<?
	// if customer has existing CCs, we can show the options for it
	if (!empty($this->ld['credit_cards'])) {
		require DR . '/html-parts/credit-card/options.php';
	}
	?>
	</div>
	<div id="credit-card-new-details" class="inner">
		<div id="credit-card-fields">
			<?
			$formArgs = array_merge(
				$this->gd['emg_form_args']
				, array(
					'id' => 'billing-info'
					, 'legendTag' => 'h4'
				)
			);
				
			creditCardForm(
				$formArgs
				, $this->ld['states']
				, $this->ld['countries']
				, $this->ld['months']
				, $this->ld['years']
				, $this->ld['credit_card_types']
			);
			?>
		</div>
	</div>
	
	<fieldset class="columns no-legend">
		<input type="submit" name="credit-card-info-submit" id="credit-card-info-submit" value="Review Order" class="" />
	</fieldset>
</form>