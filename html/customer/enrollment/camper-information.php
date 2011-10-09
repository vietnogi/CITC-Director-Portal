<h2>Camper Information</h2>
<?
$prefix = 'camper-information';
?>
<form id="<?= $prefix ?>-form"  action="<?= $this->actionUrl() ?>&amp;camper_index=<?= $this->gd['camper_index'] ?>"  method="post" class="emg-form val-form">
	<?
	if (is_numeric($this->ld['camper']['camper_id'])) { // existing camper, show details
		?>
        <p>
            <strong><?= $this->ld['camper']['first_name'] . ' ' . $this->ld['camper']['last_name'] ?></strong>
            <br />
            <?= $GLOBALS['dates']->usFormat($this->ld['camper']['date_of_birth']) ?>
            <br />
            <?= $this->ld['camper']['sex'] == 'M' ? 'Male' : 'Female' ?>
        </p>

		<?
	}
	else { // new camper
		?>
        <ul class="fields">
            <li>
                <label for="<?= $prefix ?>-first-name">Camper First Name <em>*</em></label>
                <input type="text" id="<?= $prefix ?>-first-name" name="first_name" class="val_req" maxlength="50" value="<?= $this->ld['camper']['first_name'] ?>" />
				<?
				cancelToBilling($this->ld['completed_campers']);
				?>
            </li>
            <li>
                <label for="<?= $prefix ?>-last-name">Camper Last Name <em>*</em></label>
                <input type="text" id="<?= $prefix ?>-last-name" name="last_name" class="val_req" maxlength="50" value="<?= $this->ld['camper']['last_name'] ?>" />
            </li>
            <li>
                <label for="<?= $prefix ?>-dob-mm">Camper Date of Birth <em>*</em></label>
                <ul class="field-combo">
					<?
					list($yyyy, $mm, $dd) = isset($this->ld['camper']['date_of_birth']) ? explode('-', $this->ld['camper']['date_of_birth']) : array('', '', '');
					?>
                    <li>
                        <input type="text" id="<?= $prefix ?>-dob-mm" name="dob[]" class="max-length-next <?= $prefix ?>-dob-dd val_req val_int val_combo <?= $prefix ?>-dob-mm val_errorAfter <?= $prefix ?>-dob-yyyy" maxlength="2" value="<?= $mm ?>" />
                        <label for="<?= $prefix ?>-dob-mm" class="field-hint">mm</label>
                    </li>
                    <li>
                        <input type="text" id="<?= $prefix ?>-dob-dd" name="dob[]" class="max-length-next <?= $prefix ?>-dob-yyyy val_req val_int val_combo <?= $prefix ?>-dob-mm val_errorAfter <?= $prefix ?>-dob-yyyy" maxlength="2" value="<?= $dd ?>" />
                        <label for="<?= $prefix ?>-dob-dd" class="field-hint">dd</label>
                    </li>
                    <li>
                        <input type="text" id="<?= $prefix ?>-dob-yyyy" name="dob[]" class="val_req val_int val_combo <?= $prefix ?>-dob-mm" maxlength="4" value="<?= $yyyy ?>" />
                        <label for="<?= $prefix ?>-dob-yyyy" class="field-hint">yyyy</label>
                    </li>
                </ul>
            </li>
            <li>
                <label for="<?= $prefix ?>-sex-male">Camper Sex <em>*</em></label>
                <ul class="options">
                    <li>
                        <input type="radio" id="<?= $prefix ?>-sex-male" name="sex" class="val_req val_errorAfter <?= $prefix ?>-sex-female-label" value="M"<?= $this->ld['camper']['sex'] == 'M' ? ' checked="checked"' : '' ?> />
                        <label for="<?= $prefix ?>-sex-male">Male</label>
                    </li>
                    <li>
                        <input type="radio" id="<?= $prefix ?>-sex-female" name="sex" value="F"<?= $this->ld['camper']['sex'] == 'F' ? ' checked="checked"' : '' ?>  />
                        <label for="<?= $prefix ?>-sex-female" id="<?= $prefix ?>-sex-female-label">Female</label>
                    </li>
                </ul>
            </li>
        </ul>
        <?
	}
	?>
	<fieldset>
		<input name="submit-continue" type="submit" value="Continue to Session Information" />
	</fieldset>
</form>