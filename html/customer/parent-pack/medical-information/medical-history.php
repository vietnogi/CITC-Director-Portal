<?
$__pph->h2('Medical Information &rsaquo; Medical History', $_pp->camper);
$form = new emgForm(array_merge(array('id' => 'pp-medical-history-form'
									, 'action' =>  CR . '/action' . $_bc->uri . '?parent_packid=' . $_GET['parent_packid'] . '&amp;' . TOKEN
									, 'method' => 'post'
									, 'class' => 'emg-form val-form columns'
									)
								, $__ppFormArgs
								)
					);
$form->legend('Physical Exam Information');
?>
<div class="info field">
	<div class="inner">
		<p>Each camper must have a physical exam within <strong class="no-wrap">one year</strong> prior to camp.</p>
	</div>
</div>
<?
if(!empty($_medicalHistory)){
	$form->hidden(array('name' => 'medical_historyid'
						, 'value' => $_medicalHistory['medical_historyid']
						)
					);
}
$form->dateMonthYear(array('label' => 'Last Exam Date'
						   , 'name'=> 'last_exam_date'
						   , 'value' => $_medicalHistory['last_exam_date']
						   , 'class' => 'val_req'
						   )
					 );
$form->text(array('label' => 'Examined By'
				  , 'name' => 'examined_by'
				  , 'class' => 'val_req'
				  , 'value' => $_medicalHistory['examined_by']
				  )
			);
$form->text(array('label' => 'Family Physician'
				  , 'name' => 'family_physician'
				  , 'class' => 'val_req'
				  , 'value' => $_medicalHistory['family_physician']
				  )
			);
$form->phone(array('label' => 'Physician Phone'
				   , 'name' => 'physician_phone'
				   , 'value' => $_medicalHistory['physician_phone']
				   , 'class' => 'val_req'
				   )
			 );
$form->address(array('label' => 'Physician Address'
					   , 'class' => 'val_req'
					   , 'value' => array('address' => $_medicalHistory['physician_street']
										  , 'address2' => $_medicalHistory['physician_street_2']
										  , 'city' => $_medicalHistory['physician_city']
										  , 'stateid' => $_medicalHistory['physician_stateid']
										  , 'province' => $_medicalHistory['physician_province']
										  , 'countryid' => $_medicalHistory['physician_countryid']
										  , 'zip' => $_medicalHistory['physician_zip']
										  )
					   , 'us' => $_us
					   , 'states' => $_states
					   , 'countries' => $_countries
					   )
				);
$form->ulEnd();
?>
<h3>Select All Disorders That Your Camper Has Experienced</h3>		
<?
$form->ulStart();
$checkBoxes = array();
foreach($_availableDisorders as $i => $availableDisorder){
	$checkBox = array('label' => 'Disorders'
					  , 'name' => 'disorder[' . $i . ']'
					  , 'labelAfter' => $availableDisorder['name']
					  , 'value' => $availableDisorder['medical_disorderid']
					  , 'checked' => false
					  );
	//check if disorder has been selected
	foreach($_disorders as $disorder){
		if($availableDisorder['medical_disorderid'] == $disorder['medical_disorderid']){
			$checkBox['checked'] = true;	
		}
	}
	
	array_push($checkBoxes, $checkBox);
}
$form->checkboxes($checkBoxes);
/*?>
<li>
	<?
	$form->checkboxGroups($checkBoxes
						  , 2
						  , array('label' => 'Disorders'
								  , 'id' => 'disorder'
								  , 'name' => 'disorder'
								  )
						  );
	?>
</li>
<?*/
	
$form->textarea(array('label' => 'Detail disorders that were selected'
					  , 'name' => 'information_on_disorders'
					  , 'value' => $_medicalHistory['information_on_disorders']
					  )
				);
$form->textarea(array('label' => 'List all previous surgeries'
					  , 'name' => 'previous_surgeries'
					  , 'value' => $_medicalHistory['previous_surgeries']
				)
			);
$form->textarea(array('label' => 'List all allergies'
					  , 'name' => 'information_on_allergies'
					  , 'value' => $_medicalHistory['information_on_allergies']
					  )
				);


$checkBoxes = array();
foreach($_availableAllergies as $i => $availableAllergy){
	$checkBox = array('label' => 'Does camper have any allergic reactions to the following'
					  , 'name' => 'allergy[' . $i . ']'
					  , 'labelAfter' => $availableAllergy['name']
					  , 'value' => $availableAllergy['medical_allergyid']
					  , 'checked' => false
					  );
	//check if disorder has been selected
	foreach($_allergies as $allergy){
		if($availableAllergy['medical_allergyid'] == $allergy['medical_allergyid']){
			$checkBox['checked'] = true;	
		}
	}
	
	array_push($checkBoxes, $checkBox);
}
$form->checkboxes($checkBoxes);
/*?>
<li>
	<?
	$form->checkboxGroups($checkBoxes
						  , 2
						  , array('label' => 'Does camper have any allergic reactions to the following'
								  , 'id' => 'allergy'
								  , 'name' => 'allergy'
								  )
						  );
	?>
</li>
<?*/
$form->dateMonthYear(array('label' => 'Date of last DPT or DT Booster'
						   , 'name' => 'last_dpt_dt_booster_date'
						   , 'value' => $_medicalHistory['last_dpt_dt_booster_date']
						   )
					 );
$form->ulEnd();
?>
<h3>Non-Prescription Drugs</h3>

<p>My child may be given non-prescription, over-the-counter medications as needed <span class="hint">(Examples: Tylenol, antihistamines, antacids)</span></p>

<?
$form->ulStart();
$form->radios(array(array('label' => 'Non-Prescription Drugs'
						, 'labelAfter' => 'Yes'
						, 'name' => 'non_prescription_medications'
						, 'id' => 'non_prescription_medications_yes'
						, 'value' => '1'
						, 'checked' => $_medicalHistory['non_prescription_medications'] == '1'
						)
				  , array('label' => 'Non-Prescription Drugs'
						, 'labelAfter' => 'No'
						, 'name' => 'non_prescription_medications'
						, 'id' => 'non_prescription_medications_no'
						, 'value' => 0
						, 'checked' => $_medicalHistory['non_prescription_medications'] == '0'
						)
				  )
			);
			
$form->textarea(array('label' => 'Exceptions'
					  , 'name' => 'non_prescription_medication_exceptions'
					  , 'value' => $_medicalHistory['non_prescription_medication_exceptions']
					  )
				);
$form->ulEnd();
?>
<h3>Prescription Drugs</h3>

<p>Written physician's directions should accompany any prescription medicines sent to camp for the nurse to dispense. These directions must include: medication, dosage, frequency, condition being treated, physician's signature and DEA number. Please be sure meds are labeled with accurate dosage information. Let your child know that s/he will be reporting to the infirmary for scheduled medications.</p>

<?
$form->ulStart();
$form->textarea(array('label' => 'Medications'
					  , 'name' => 'medications'
					  , 'value' => $_medicalHistory['medications']
					  )
				);
$form->ulEnd();

$__pph->submitButton($form);
?>