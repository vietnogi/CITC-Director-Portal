<?
$__pph->h2('Liability Waiver', $_pp->camper);
$form = new emgForm(array_merge(array('id' => 'pp-liability-waiver-form'
									, 'action' =>  CR . '/action' . $_bc->uri . '?parent_packid=' . $_GET['parent_packid'] . '&amp;' . TOKEN
									, 'method' => 'post'
									, 'class' => 'emg-form val-form columns'
									)
								, $__ppFormArgs
								)
					);
?>
	<p>I/we am aware that in addition to the usual dangers and risks inherent in the sports of Skateboarding, Inline Skating, Freestyle BMX, Mountain Biking, Snowboard/Freeski, Cheerleading, Gymnastics, Acro, Trampoline, Tumbling and other Woodward West activities, certain additional dangers and risks are present when using Woodward Facilities, Woodward Skate/Bike Facilities, Gymnastics Equipment and Trampoline, including, but not limited to, the danger and risk of falling, jumping, landing, misdirected skateboards and bikes, performing tricks and colliding with other staff, campers, media personnel and spectators. By signing this waiver, I/we freely accept and fully assume responsibility for all such dangers and risks and the possibility of personal injury, death, property damage or loss resulting therefrom.</p>
	<p>In consideration of utilizing the Woodward West, LLC, Facilities, Woodward Skate/Bike Facilities, Gymnastics Equipment and Trampolines and for other good and valuable consideration, I/we hereby agree as follows:</p>
	
	<ol class="liability">
		<li>TO WAIVE ANY AND ALL CLAIMS for personal injury including death, illness, and/or property damage that I/we may have against Woodward West, LLC, Sports Management Group, Inc., Sports Partners LP, their shareholders, partners, principals, directors, officers, sponsors, affiliates, agents, employees, contractors, representatives and any volunteers in any way associated with Woodward West, LLC, all of whom are hereinafter collectively referred to as "the Releasees."</li>
		<li>TO RELEASE THE RELEASEES FROM ANY AND ALL LIABILITY for any loss, damage, injury, death, medical or other expense that I/we may suffer or that any other party may suffer as a result of my use of Woodward Facilities, Woodward Skate/Bike Facilities, Gymnastic Equipment and Trampoline or in my  participation in the sports of Skateboarding, Inline Skating, Freestyle BMX, Mountain Biking, Snowboard/Freeski, Cheerleading, Gymnastics, Acro, Trampoline, Tumbling, and other Woodward West activities, due to any cause whatsoever.</li>
		<li>TO HOLD HARMLESS AND INDEMNIFY THE RELEASEES from any and all liability for any property damage or personal injury to any third party, resulting from my use of Woodward Facilities, Woodward Skate/Bike Facilities, Gymnastic Equipment and Trampoline or by my participation in the sports of Skateboarding, Inline Skating, Freestyle BMX, Mountain Biking, Snowboard/Freeski, Cheerleading, Gymnastics, Acro, Trampoline, Tumbling, and other Woodward West activities.</li>
		<li>THIS RELEASE OF LIABILITY SHALL BE EFFECTIVE AND BINDING upon my heirs, next of kin, executors, administrators, successors, and assigns in the event of my personal injury including death, illness, and/or property damage.</li>
		<li>I/WE ADDITIONALLY AGREE not to take unreasonable risks while participating in Skateboarding, Inline Skating, Freestyle BMX, Mountain Biking, Snowboard/Freeski, Cheerleading, Gymnastics, Trampoline, Tumbling, Acro, and other Woodward West activities, including but not limited to attempting skills or tricks that I am not qualified to perform safely or causing any other participants/spectators unreasonable risk of harm.</li>
		<li>I/WE ADDITIONALLY AGREE that I/we shall follow correct safety procedures when using the Woodward Facilities, Woodward Skate/Bike Facilities, Gymnastics Equipment and Trampoline. I/we also expressly grant to the Camp, and any third party authorized by the Camp, the right to film, videotape, photograph, record my voice and make any reproductions of my physical likeness and voice, and the irrevocable right in perpetuity to use, display, and digitally enhance or alter in any manner, such likeness in any media now known or hereafter devised, including, but not limited to, the exhibition and/or online use, broadcast, theatrically or on television, cable or radio, any motion picture film, video tape, DVD, CD or any Internet service or program in which such likeness may be used or otherwise, or any published articles, catalogs, or Web sites in which such likeness may be printed, used or incorporated, and in the advertising, exploiting and publicizing of the Camp, Camp products, licensed products, and all affiliated relationships.</li>
	</ol>
	
	<p>The venue and place of trail of any dispute that may arise out of or be related to this agreement or the services to be performed pursuant to this agreement, or otherwise, to which Woodward West or its agents or employees is a party shall be in the Metropolitan Division Court in Kern County in the State of California.</p>
	
	<p>I/WE HEREBY CERTIFY THAT I/we am covered by my own Medical Insurance, and that I/we have read and understand this Release of Liability prior to signing it, and I/we am aware that by signing this Release of Liability I/we am waiving certain legal rights which I/we or my heirs, next of kin, executors, administrators, successors, and assigns may have against the Releasees.</p>
	
	<ul class="liability">
		<li>Woodward shall have the right to impose any additional conditions which, in the opinion of the Releasees, will further the intent and legal rights and waivers provided herein.</li>
		<li>This Liability Waiver was made and executed in the State of California and shall be governed by, enforced in and construed in accordance with the laws of the State of California. </li>
		<li>I/we acknowledge that in executing this Waiver, I/we are not relying on any inducements, promises, or representations made by the Releasees.</li>
	</ul>
	
	<p>I am acting on behalf of the camper's other parent in signing this contract and I have authority to bind such other parent to the terms and conditions of this contract on his or her behalf.</p>
<?
$form->text(array('label' => 'Parent / Legal Guardian Name'
					 , 'name' => 'liability_waiver_name'
					 , 'value' => $_pp->details['liability_waiver_name']
					 , 'class' => 'val_req'
					 )
				);
$form->date(array('label' => 'Date'
				  , 'value' =>  $_pp->details['liability_waiver_date']
				  , 'name' => 'liability_waiver_date'
				  , 'class' => 'val_req'
				  )
			);
$form->text(array('label' => 'Camper Name'
					 , 'name' => 'liability_waiver_camper_name'
					 , 'value' => $_pp->details['liability_waiver_camper_name']
					 , 'class' => 'val_req'
					 )
				);
$form->date(array('label' => 'Date'
				  , 'value' =>  $_pp->details['liability_waiver_camper_date']
				  , 'name' => 'liability_waiver_camper_date'
				  , 'class' => 'val_req'
				  )
			);
$__pph->submitButton($form);
?>