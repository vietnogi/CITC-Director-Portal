<h2>Invoice #<?= $this->ld['invoice']['customer_invoice_id'] ?></h2>

<p class="print-external"><a href="<?= $_bc->uri ?>" rel="external">Printer Friendly</a></p>
	
<?
formattedAddress($this->ld['business_address'], 'shop');
?>

<h3>Bill to:</h3>
<span class="name"><?= $this->ld['invoice']['customer_first_name'] . ' ' . $this->ld['invoice']['customer_last_name'] ?></span>
<?
if (!empty($this->ld['invoice']['customer_phone'])) {
	?>
	<br />
	<span class="name"><?= phoneFormat($this->ld['invoice']['customer_phone']) ?></span>
	<?
}
?>
<br />
<span class="name"><?= $this->ld['invoice']['customer_email'] ?></span>

<table class="invoice details">
	<thead>
		<tr>
			<th scope="col" class="description">Description</td>
            <th scope="col" class="amount">Amount</td>
		</tr>
	</thead>
	<tbody>
    	<?
		if (!empty($this->ld['invoice']['items'])) {
			foreach ($this->ld['invoice']['items'] as $item) {
				?>
                <tr>
                	<td class="description"><?= $item['type'] ?><?= $item['description'] != '' ? ' (' . $item['description'] . ')' : '' ?></td>
                    <td class="amount"><?= moneyFormat($item['amount']) ?></td>
				</tr>
                <?
			}
		}
		
		if (!empty($this->ld['invoice']['payments'])) {
			foreach ($this->ld['invoice']['payments'] as $payment) {
				?>
                <tr>
                	<td class="description"><?= $payment['payment_method'] ?> Payment on <?= $_dates->usFormat($payment['payment_date']) ?></td>
                    <td class="amount"><?= moneyFormat($payment['invoice_payment_amount'] * -1) ?></td>
				</tr>
                <?
			}
		}
		?>
	</tbody>
    <tfoot>
        <tr>
        	<th>Balance:</th>
        	<td><?= moneyFormat($this->ld['invoice']['balance']) ?></td>
        </tr>
    </tfoot>
</table>