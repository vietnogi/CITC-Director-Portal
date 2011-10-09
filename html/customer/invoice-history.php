<h2>Invoice History</h2>
<? 
if (empty($this->ld['invoices'])) {
	?>
	<p>You currently do not have any invoices.</p>
	<?
}
else {
	$total = 0;
	?>
	<table class="money invoice history">
		<thead>
			<tr class="highlight">
				<th scope="col">Date</th>
				<th scope="col">Due Date</th>
				<th scope="col">Invoice #</th>
                <th scope="col">Type</th>
				<th scope="col">Amount Due</th>
				<th scope="col">Details</th>
			</tr>
		</thead>
		<tbody>
			<?
			foreach ($this->ld['invoices'] as $invoice) {
				?>
				<tr class="invoice">
					<td class="date"><?= $GLOBALS['dates']->usFormat($invoice['created_date'], false) ?></td>
					<td class="date"><?= $GLOBALS['dates']->usFormat($invoice['due_date']) ?></td>
					<td class="invoice-number"><?= $invoice['customer_invoice_id'] ?></td>
                    <td class="type"><?= $invoice['type'] ?></td>
					<td class="amount money"><?= moneyFormat($invoice['amount_due']) ?></td>
					<td class="amount money"><a href="<?= $this->url('/customer/invoice-details?customer_invoice_id=' . $invoice['customer_invoice_id']) ?>">View Details</a></td>
				</tr>
				<?
				$total += $invoice['amount_due'];
			}
			?>
		</tbody>
		<tfoot>
			<tr class="highlight">
				<th scope="row" colspan="2">Total Amount Due</th>
				<td class="money" colspan="4"><?= moneyFormat($total) ?></td>
			</tr>
		</tfoot>
	</table>
	<?
}
?>