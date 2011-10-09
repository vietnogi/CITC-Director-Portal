<h2>My Account</h2>
<?
if (empty($this->ld['enrollments'])) {
	?>
	<h3>Getting Started</h3>
	<p class="go"><a href="<?= $this->url('/action/customer/enrollment/enroll-a-new-camper') ?>">Enroll a New <?= l('camper') ?> &raquo;</a></p>
    <?
}
else {
	?>
	<h3>Enrolled <?= l('campers') ?></h3>
	
	<p class="go"><a href="<?= $this->url('/action/customer/enrollment/enroll-a-new-camper') ?>">Enroll a New <?= l('camper') ?> &raquo;</a></p>
	
	<ul class="enrolled-campers account-panels">
		<?
		foreach ($this->ld['enrollments'] as $camperid => $seasons) {
			$sessions = current($seasons); //seasons have redundant data, so we can simply take the first one
			$camper = array(
				'camper_id' => $sessions[0]['camper_id']
				, 'first_name' => $sessions[0]['first_name']
				, 'last_name' => $sessions[0]['last_name']
				, 'age' => $sessions[0]['age']
				, 'sex' => $sessions[0]['sex']
				, 'date_of_birth' => $sessions[0]['date_of_birth']
			);
			?>
			<li class="account-panel">
				<div class="header">
					<div class="primary">
						<h4><?= $camper['first_name'] . ' ' . $camper['last_name'] ?></h4>
						<p class="meta"><?= $camper['sex'] == 'M' ? 'Male' : 'Female' ?> <?= $GLOBALS['dates']->usFormat($camper['date_of_birth']) ?></p>
					</div>
				</div>
				<?
				$i = 1;
				$numSessions = count($seasons);
				foreach ($seasons as $season => $sessions) {
					$ppStarted = ($sessions[0]['parent_pack_id'] == '') ? false : true;
					$ppComplete = ($sessions[0]['parent_pack_locked'] == 'Complete') ? true : false;
					$ppDueDate =$GLOBALS['dates']->usFormat($sessions[0]['parent_pack_due_date']);
					?>
					<div class="body">
						<div class="primary">
							<h5><?= $season ?></h5>
							<dl class="camper-sessions">
								<?
								foreach ($sessions as $session) {
									?>
									<dt><?= $session['session'] ?></dt>
									<dd>
										- <?= $session['specialty'] ?>
										- <?= date('M jS', strtotime($session['session_start'])) ?>
										- <?= date('M jS', strtotime($session['session_end'])) ?>
									</dd>
									<?
								}
								?>
							</dl>
							
							<?
							if ($i == $numSessions) {
								/* disable for now because need time to code additional logic due to PP and same session
								?>
								<p><a href="<?= CR ?>/action/customer/enrollment/enroll-existing-camper?camper_id=<?= $camper['camper_id'] ?>&amp;<?= TOKEN ?>">Enroll <?= $camper['first_name'] ?> in New Session &raquo;</a></p>
								<?*/
							}
							?>
						</div>
						
						<div class="secondary">
							<?
							if ($ppComplete) {
								?>
								<h5 class="complete"><?= l('parent pack') ?> Complete</h5>
								<?
							}
							else {
								?>
								<h5 class="incomplete"><?= l('parent pack') ?> Incomplete</h5>
								<?
							}
							?>
						</div>
						<div class="secondary">
							<?
							if ($ppComplete) {
								?>
								<ul class="parent-pack actions">
									<li><a href="#">View / Print Parent Pack</a></li>
									<li><a href="#">Print Parent Pack Receipt</a></li>
								</ul>
								<?
							}
							else{
								if ($ppStarted) {
									?>
									<p class="go"><a href="<?= $this->url('/customer/parent-pack/camper-information?parent_pack_id=' . $sessions[0]['parent_pack_id']) ?>">Continue <?= l('parent pack') ?></a></p>
									<?
								}
								else {
									?>
									<p class="go"><a href="<?= $this->url('/action/customer/start-parent-pack?enrollment_id=' . $sessions[0]['enrollment_id']) ?>">Start <?= l('parent pack') ?></a></p>
									<?
								}
								?>
								
								<dl class="due">
									<dt>Due:</dt>
									<dd><?= $ppDueDate ?></dd>
								</dl>
								<?
							}
							?>
						</div>
					</div>			
					<?
					$i++;
				}
				?>
			</li>
			<?
		}
		?>
	</ul>
	<p>Please call the office if you would like to modify your enrollment.</p>
    <?
}
?>
<h3>Balance</h3>

<div class="account-panel">
	<div class="header">
		<div class="primary">
			<table class="balance">
				<tbody>
					<tr>
						<th scope="row">Total Fees:</th>
						<td><?= moneyFormat($this->ld['invoiceTotal']) ?></td>
					</tr>
					<tr class="payment">
						<th scope="row">Payments Received:</th>
						<td><?= moneyFormat($this->ld['paymentTotal']) ?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="body">
		<div class="primary">
			<table class="balance remaining">
				<tbody>
					<tr>
						<th scope="row">Outstanding Balance:</th>
						<td><?= moneyFormat($this->ld['balance']) ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="secondary">
			<p class="go"><a href="<?= $this->url('/customer/make-a-payment') ?>">Make a Payment</a></p>
			<ul class="balance actions">
                <li><a href="<?= $this->url('/customer/invoice-history') ?>">View Invoices</a></li>
                <li><a href="<?= $this->url('/customer/payment-history') ?>">View Previous Payments</a></li>
            </ul>
		</div>
	</div>	
</div>