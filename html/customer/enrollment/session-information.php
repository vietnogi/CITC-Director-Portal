<h2>Session Information</h2>
<?
cancelToBilling($this->ld['completed_campers']);
?>

<h3>Choose a <?= $this->ld['camper']['season']['name'] ?> Session(s) for <?= $this->ld['camper']['first_name'] ?></h3>
<?
$prefix = 'session-information';
?>
<form id="<?= $prefix ?>-form" action="<?= $this->actionUrl() ?>&amp;camper_index=<?= $this->gd['camper_index'] ?>" class="emg-form val-form" method="post" onsubmit="return checkSessionInformation();">
	<ul class="session-specialties accordion">
		<?
		foreach ($this->ld['sessions'] as $i => $session) {
			$camperHasSession = array_key_exists($session['session_id'], $this->ld['camper']['session_specialty_links']) ? true : false;
			$sessionSpecialties = $this->ld['session_specialty_links'][$session['session_id']];
			if (!is_array($sessionSpecialties)) {
				continue;
			}
			
			// Unavailable session
			if (isSessionFull($this->ld['camper']['sex'], $session)) {
				?>
				<li class="unavailable">
					<div class="info">
						<div class="inner">
							<p>
								<strong>Session Unavailable</strong> Please Call: <?= phoneFormat(CONTACT_PHONE) ?>
							</p>
						</div>
					</div>
					<span class="header">
						<span class="session"><?= $session['name'] ?></span>
						<span class="dates"><?= $GLOBALS['dates']->shortFriendlyFormat($session['start']) ?> - <?= $GLOBALS['dates']->shortFriendlyFormat($session['end']) ?></span>
					</span>
				</li>
				<?
			}
			else {
				?>
				<li<?= $camperHasSession ? ' class="expanded"' : '' ?>>
					<a href="#<?= $session['clean_name'] ?>-details" class="header<?= $camperHasSession ? ' expanded' : '' ?>">
						<span class="session"><?= $session['name'] ?></span>
						<span class="dates"><?= $GLOBALS['dates']->shortFriendlyFormat($session['start']) ?> - <?= $GLOBALS['dates']->shortFriendlyFormat($session['end']) ?></span>
					</a>
					<div id="<?= $session['clean_name'] ?>-details" class="inner<?= $camperHasSession ? ' expanded' : '' ?>">
                        <p>Choose a Program for <?= $session['name'] ?>:</p>
                        <ul class="specialty-groups">
                            <?
                            $specialtyGroups = rowsToGroups($sessionSpecialties, 'type');
                            foreach ($specialtyGroups as $type => $specialties) {
                                ?>
                                <li>
                                    <dl class="specialties">
                                        <dt><?= $type ?></dt>
                                        <dd>
                                            <ul class="specialties">
                                                <?
                                                foreach ($specialties as $specialty) {
													$camperHasSpecialty = ($camperHasSession && $this->ld['current_sessions'][$session['session_id']] == $specialty['specialtyid']) ? true : false;
													$htmlName = $prefix . '-' . $session['session_id'];
													$htmlId = $prefix . '-' . $session['session_id'] . '-' . $specialty['clean_name'];
                                                    ?>
                                                    <li<?= $specialty['at_capacity'] ? ' class="unavailable"' : '' ?>>
                                                        <input type="radio" id="<?= $htmlId ?>" name="session_specialty_link_id[]" value="<?= $specialty['session_specialty_link_id'] ?>" <?= $camperHasSpecialty ? ' checked="checked"' : '' ?>/>
                                                        <label for="<?= $htmlId ?>"><span class="specialty"><?= $specialty['name'] ?></span> <span class="price">- <?= moneyFormat($specialty['price']) ?></span></label>
                                                    </li>
                                                    <?
                                                }
                                                ?>
                                            </ul>
                                        </dd>
                                    </dl>
                                </li>
                                <?
                            }
                            ?>
                        </ul>
					</div>
				</li>
				<?
			}
		}
		?>
	</ul>
	<fieldset>
		<input type="submit" name="continue-to-billing" value="Continue to Billing Information" />
		<?
		/*
		<p class="submit"><span class="or">or</span> <a href="#">Save and Add More Campers</a></p>
		*/
		?>
		<p class="or">or</p>
		<input type="submit" name="save-and-add" value="Save and Add More Campers" class="secondary" />
	</fieldset>
</form>