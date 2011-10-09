<h2>Enrollment Confirmed</h2>

<h3>Thank you for enrolling your camper at Woodward West.</h3>
<p>Your enrollment is now complete, <a href="<?= CR ?>/account">click here</a> to continue to your account.</p>
<?
listCamperFees($_enrollment['campers']);
?>

<h3>Order Summary</h3>

<?
feesSummary($_enrollment['fees'], $_enrollment['discounts'], $_enrollment['total']);
?>

<h3>Payment Summary</h3>

<h4>Credit Card</h4>
<?
formattedCreditCard($_enrollment->billing);
?>

<table class="money extension">
	<tfoot>
		<tr class="highlight remaining-balance">
			<th scope="row">Remaining Balance:</th>
			<td class="money"><?= moneyFormat($_enrollment['balance']) ?></td>
		</tr>
	</tfoot>
	<tbody>
		<tr class="payment">
			<td>Payment Recieved:</th>
			<td class="money">- <?= moneyFormat($_enrollment['payment']) ?></td>
		</tr>
	</tbody>
</table>

<div class="info tfoot">
	<div class="inner">
		<p><strong>Balance Due:</strong> <?= $_dates->usFormat($_enrollment['balance_due']) ?></p>
	</div>
</div>

<p><a href="<?= CR ?>/account/invoice-details?invoiceid=<?= $_GET['invoiceid'] ?>">Click here</a> to print your invoice/receipt</p>