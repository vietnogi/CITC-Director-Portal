<h2>Confirm Order</h2>

<?
listCamperFees($_enrollment->getCompletedCampers(), true);
?>

<h3>Payment Summary</h3>
<form id="enrollment-promo-form" action="<?= CR ?>/action/account/enrollment/appy-promo?<?= TOKEN ?>" method="post" class="emg-form">
	<ul class="fields">
		<li>
			<label for="enrollment-promo-code">Coupon Code</label>
			<input type="text" id="enrollment-promo-code" name="promo-code" class="" />
		</li>
	</ul>
	<fieldset class="apply">
		<input type="submit" value="Apply" />
	</fieldset>
</form>

<form id="enrollment-payment-form" action="<?= actionUrl() ?>" method="post" class="emg-form val-form">
	<h4>Fees Summary:</h4>
	<?
	feesSummary($_enrollment->fees, $_enrollment->discounts, $_enrollment->total);
	?>
    <h4>Billing Address</h4>
    <?
    formattedAddress($_enrollment->billing, 'billing');
	?>
    <h4>Credit Card</h4>
    <?
    formattedCreditCard($_enrollment->billing);
	?>
    <?
	if ($_allowDeposit) {
		require DR . '/parts/payment-options.php';
	}
	
	require DR . '/parts/payment-agreement.php';
	?>
	
	<fieldset>
		<input type="submit" value="Register Your Camper(s)" />
	</fieldset>
</form>