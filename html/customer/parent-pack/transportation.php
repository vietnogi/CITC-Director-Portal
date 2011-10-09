<?
$__pph->h2('Transportation Services', $_pp->camper);
?>

<ul class="columns-2">
	<li class="important-information">
		<h3>Important Information</h3>
		<p>Transportation services are available on the first (Sunday) and last (Saturday) days of all sessions ONLY - the camp office MUST receive your travel request and full payment at least two weeks prior to arrival to guarantee service. Requests received with less than 2 weeks' notice cannot be guaranteed and will be granted on a space-available basis.</p>
	</li>
	<li class="price-list">
		<h4>Price List</h4>
		<table class="transportation price-list">
			<thead>
				<th scope="col">Location</th>
				<th scope="col">Round Trip</th>
				<th scope="col">One Way</th>
			</thead>
			<tbody>
				<?
				$locations = array('Bakersfield'
								   , 'Los Angeles'
								   , 'Tehachapi Airport'
								   , 'Northern California'
								   );
				$prices = array();
				foreach ($_locations as $location => $methods) {
					foreach ($methods as $i => $method) {
						if(!isset($prices[$location])){
							$prices[$location] = array('one' => $method['one_way_price']
													   , 'round' => $method['round_trip_price']
													   );
						}
					}
				}
				foreach ($prices as $location => $price) {
					?>
					<tr>
						<th scope="row"><?= $location ?></th>
						<td><?= moneyFormat($price['round']) ?></td>
						<td><?= moneyFormat($price['one']) ?></td>
					</tr>
					<?
				}
				?>
			</tbody>
		</table>
	</li>
</ul>

<?
$form = new emgForm(array_merge(array('id' => 'pp-transportation-form'
									, 'action' =>  CR . '/action' . $_bc->uri . '?parent_packid=' . $_GET['parent_packid'] . '&amp;' . TOKEN
									, 'method' => 'post'
									, 'class' => 'emg-form val-form columns'
									, 'onsubmit' => 'return ppTransSubmitCheck()'
									)
								, $__ppFormArgs
								)
					);

//session choices
foreach($_sessions as $session){
	$sections = array('arrival' => array('heading' => 'Select An Arrival Location <span class="hint">(How is your camper getting to camp?)</span>'
										 )
					  , 'departure' => array('heading' => 'Select A Departure Location <span class="hint">(How is your camper leaving from camp?)</span>'
										   )
					  );
	?>
	<h3>Session <?= $session['number'] ?></h3>
	<?
	//arriaval/departure choices
	foreach($sections as $type => $section){
		$sessionidTypePrefix = $session['sessionid'] . '-' . $type . '-';
		
		$noTrans = false;
		if($_visited){
			$enteredTransportation = NULL;
			foreach($_transportations as $transportation){
				if($transportation['sessionid'] == $session['sessionid'] && strtolower($transportation['type']) == $type ){
					$enteredTransportation = $transportation;
				}
			}
			if($enteredTransportation == NULL){
				$noTrans = true;	
			}
		}
		?>
		<h4><?= $section['heading'] ?></h4>
		<?
		//id needed for js selector
		?>
		<ul class="session-<?= $session['sessionid'] ?> <?= $type ?> transportation location-areas accordion" id="accordion-session-<?= $session['sessionid'] ?>">
			<li id="<?= $sessionidTypePrefix ?>no" <?= $noTrans ? 'class="expanded"' : '' ?>>
				<input type="radio" name="<?= $sessionidTypePrefix ?>location" id="<?= $sessionidTypePrefix ?>no-input" value="None" class="hide" <?= $noTrans ? 'checked="checled"' : '' ?> />
				<a href="#<?= $sessionidTypePrefix ?>no-transportation-details" class="header">
					<span class="heading">I do not require transportation services for <?= $type ?></span>
				</a>
				<div id="<?= $sessionidTypePrefix ?>no-transportation-details" class="inner">
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
				</div>
			</li>
			<?
			//location choices
			foreach ($_locations as $location => $methods) {
				$locationPrefix = cleanUrl($location) . '-' . $type;
				$cleanLocation = cleanUrl($location);
				$transportation_locationid = $methods[0]['transportation_locationid'];
				$methodNamess = array();
				foreach($methods as $method){
					array_push($methodNamess, $method['name']);
				}
				?>
				<li id="<?= $sessionidTypePrefix . $cleanLocation ?>" <?= $transportation_locationid == $enteredTransportation['transportation_locationid'] ? 'class="expanded"' : '' ?>>
					<input type="radio" name="<?= $sessionidTypePrefix ?>location" id="<?= $sessionidTypePrefix . $cleanLocation ?>-input" value="<?= $transportation_locationid ?>" class="hide" />
					<a href="#<?= $sessionidTypePrefix . $cleanLocation ?>-transportation-details" class="header">
						<span class="heading"><?= $location ?> <span class="hint">(<?= implode(', ', $methodNamess) ?>)</span></span>
					</a>
					<div id="<?= $sessionidTypePrefix . $cleanLocation ?>-transportation-details" class="inner">
						<?
						// Method choices
						$form->ulStart();
						$methodRadios = array();
						foreach ($methods as $i => $method) {
							array_push($methodRadios, array('name' =>  $sessionidTypePrefix . 'method'
														  , 'id' => $sessionidTypePrefix . $method['transportation_methodid']
														  , 'value' => $method['transportation_methodid']
														  , 'label' => 'Transportation Method'
														  , 'labelAfter' => $method['name']
														  , 'class' => 'transportation-method-choice' //class needed for js selector
														  , 'checked' => $method['transportation_methodid'] == $enteredTransportation['transportation_methodid'] ? true : false
														  )
										);
						}
						
						//if only one option, hide it and use js to select it
						$singleMethod = count($methods) == 1 ?  true : false;
						?>
						<div <?= $singleMethod ? 'class="hide"' : '' ?>>
							<?
							$form->radios($methodRadios);
							?>
						</div>
						<?
						$form->ulEnd();
						?>
						<div class="" id="">
							<ul class="locations">
								<?
								foreach ($methods as $method) {
									$sessionidTypeMethodidPrefix = $sessionidTypePrefix . $method['transportation_methodid'] . '-';
									$chosenMethod = $method['transportation_methodid'] == $enteredTransportation['transportation_methodid'] ? true : false;
									$hideInfo = ($chosenMethod || $singleMethod) ? false : true;
									if($chosenMethod){
										if($enteredTransportation['date_time'] != ''){
											list($enteredTransportation['date'], $enteredTransportation['time']) = explode(' ', $enteredTransportation['date_time']);
										}
										$values = array('date' => $enteredTransportation['date']
														, 'time' => $enteredTransportation['time']
														, 'carrier_company' => $enteredTransportation['carrier_company']
														, 'number' => $enteredTransportation['number']
														, 'unaccompanied' => false
														);
									}
									else{
										$values = array();	
									}
									?>
									<li class="<?= $session['sessionid'] ?>-transportation-information <?=  $hideInfo  ? 'hide' : '' ?>" id="<?= $sessionidTypeMethodidPrefix ?>information">
										<h4><?= ucwords($type) ?> Information</h4>
										<h5><?= $method['name'] ?></h5>
										<?
										$form->ulStart();
										if($method['type'] != 'Drop Off / Pick Up'){
											
											$form->date(array('label' => 'Date'
															  , 'id' => $sessionidTypeMethodidPrefix . 'date'
															  , 'name' => $sessionidTypeMethodidPrefix  . 'date'
															  , 'value' => $values['date']
															  )
														);
											$form->time(array('label' => 'Time'
															  , 'hours' => $_dates->hours12
															  , 'minutes' => $_dates->minutes()
															  , 'ampms' => array('am', 'pm')
															  , 'name' => $sessionidTypeMethodidPrefix  . 'time'
															  , 'value' => $values['time']
															  )
														);
											$form->text(array('label' => $method['type']
															  , 'id' => $sessionidTypeMethodidPrefix
															  , 'name' => $sessionidTypeMethodidPrefix
															  , 'value' => $values['carrier_company']
															  )
														);
											$form->text(array('label' => ($method['type'] == 'Airline' ? 'Flight' : $method['type']) . ' #'
															  , 'id' => $sessionidTypeMethodidPrefix . 'number'
															  , 'name' => $sessionidTypeMethodidPrefix . 'number'
															  , 'value' => $values['number']
															  )
														);
											if($method['type'] == 'Train'){
												$form->checkboxes(array(array('labelAfter' => 'Camper is traveling as an unaccompanied minor'
																			  , 'id' => $sessionidTypeMethodidPrefix . 'unaccompanied'
																			  , 'name' => $sessionidTypeMethodidPrefix . 'unaccompanied'
																			  )
																		)
																  );
											}
										}
										$form->ulEnd();
										
										if (!empty($method['description'])) {
											?>
											<h6>Notes:</h6>
											<ul class="notes">
												<?
												$notes = explode("\r\n\r\n", $method['description']);
												foreach ($notes as $note) {
													?>
													<li><?= html_entity_decode($note) ?></li>
													<?
												}
												?>
											</ul>
											<?
										}
										?>
									</li>
									<?
								}
								?>
							</ul>
						</div>
					</div>
				</li>
				<?
			}
			?>
		</ul>
		<?	
	}
}
?>

<h3>Transportation Services Checklist</h3>

<p><strong>Please acknowledge these final steps before saving your information</strong></p>

<ul class="checklist">
	<?
	$checklistItems = array('Please fax a copy of your itinerary to ' . OFFICE_FAX . ' or email to <a href="mailto:' . OFFICE_EMAIL . '">' . OFFICE_EMAIL . '</a>'
							, 'Advise the transportation director of any changes to this itinerary immediately.'
							, 'Please make sure camper has our phone number when s/he is traveling.'
							, 'Unaccompanied Minors - Please call the transportation director in the camp office the day before departure to learn the name of the person picking up your child.'
							);
	foreach ($checklistItems as $item) {
		?>
		<li><?= $item ?></li>
		<?
	}
	?>
</ul>

<?			
$__pph->submitButton($form);
?>
