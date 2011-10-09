<h3>I Will Pay With</h3>		
<ul class="billing accordion">
	<?
	$htmlName = 'credit_card_id';
	foreach ($this->data['credit_cards'] as $i => $cc) {
		$htmlid = $htmlName . '-' . $cc['customer_credit_cardid'];
		$checked = ($_useCreditCardid == $cc['customer_credit_cardid']) ? ' checked="checked"' : '';
		?>
		<li id="<?= $htmlid ?>"<?= $checked ? ' class="expanded"' : '' ?>>
			<input type="radio" value="<?= $cc['customer_credit_cardid'] ?>" name="<?= $htmlName ?>" id="<?= $htmlid ?>-input" class="hide payment-method autocomplete-off"<?= $checked ?>/>
			<a href="#<?= $htmlid ?>-details" class="header">
				<span class="heading"><?= $cc['credit_card_type'] ?> <span class="cc">ending in <?= maskCC($cc['credit_card_number']) ?></span></span>
			</a>
			<div id="<?= $htmlid ?>-details" class="inner">
				<h5>Billing Address</h5>
				<?
				formattedAddress($cc, 'billing');
				?>
			</div>
		</li>
		<?	
	}
	?>
	<li id="<?= $htmlName ?>-new">
		<input type="radio" value="new-credit-card" name="<?= $htmlName ?>" id="<?= $htmlName ?>-new-input" class="hide payment-method" />
		<a href="#<?= $htmlName ?>-new-details" class="header">
			<span class="heading">Add a new Credit Card</span>
		</a>
	</li>
</ul>