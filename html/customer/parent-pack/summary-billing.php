<?
$__pph->h2('Summary / Billing', $_pp->camper);
?>

<p>Please review each section for accuracy using the navigation on the left.</p>

<p>This Parent Pack will not be available for editing after submission.</p>

<h3>Transportation Payment Summary <a href="<?= CR . $_bc->path ?>/transportation?parent_packid=<?= $_GET['parent_packid'] ?>" class="edit">Edit This Information</a></h3>

<table class="order summary money">
	<tbody>
		<?
		$roundTrip = false;
		$subtotal = 0;
		foreach($_transportations as $i => $transportation){
			$subtotal += $transportation['cost'];
			?>
			<tr class="camper name">
				<td>Session <?= $transportation['session'] ?></td>
			</tr>
			<tr class="camper dob">
				<td><?= $transportation['type'] ?> : <?= $transportation['name'] ?> <?= $transportation['date_time'] ?> <?= $transportation['carrier_company'] ?> <?= $transportation['number'] ?> <?= $transportation['unaccompanied_minor'] ?></td>
				<td class="money"><?= moneyFormat($transportation['cost']) ?></td>
			</tr>
			<?	
		}
		?>
	</tbody>
	<tfoot>
		<tr class="highlight subtotal">
			<th scope="row">Subtotal:</th>
			<td class="money"><?= moneyFormat($subtotal) ?></td>
		</tr>
	</tfoot>
</table>

<h3>Camp Money <a href="<?= CR . $_bc->path ?>/camp-money?parent_packid=<?= $_GET['parent_packid'] ?>" class="edit">Edit This Information</a></h3>
<table class="order summary money">
	<tfoot>
		<tr class="highlight subtotal">
			<th scope="row">Camp Money Allotment:</th>
			<td class="money"><?= moneyFormat($_pp->details['camp_money']) ?></td>
		</tr>
	</tfoot>
</table>

<h3>Total Parent Pack Payment</h3>
<table class="order summary money">
	<tfoot>
		<tr class="highlight subtotal">
			<th scope="row">Amount to Be Placed on Card:</th>
			<td class="money"><?= moneyFormat($subtotal + $_pp->details['camp_money']) ?></td>
		</tr>
	</tfoot>
</table>

<?
$formTarget = 'pp-summary-billing-form-target';
$form = new emgForm(array_merge(array('id' => 'pp-summary-billing-form'
									, 'action' =>  CR . '/action' . $_bc->uri . '?parent_packid=' . $_GET['parent_packid'] . '&amp;' . TOKEN
									, 'method' => 'post'
									, 'target' => $formTarget
									, 'class' => 'emg-form val-form'
									)
								, $__ppFormArgs
								)
					);
if ($subtotal + $_pp->details['camp_money'] > 0) {
	?>
	<h3>Billing Information</h3>
	<?
	require DR . '/parts/credit-card/options.php';
}

$__pph->submitButton($form, 'Complete Parent Pack');
?>

<iframe name="<?= $formTarget ?>" class="<?= DEVELOPMENT == '1' ? '' : 'hidden' ?>"></iframe>