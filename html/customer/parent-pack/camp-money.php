<?
$__pph->h2('Camp Money', $_pp->camper);

$form = new emgForm(array_merge(array('id' => 'pp-camp-money-form'
									, 'action' =>  CR . '/action' . $_bc->uri . '?parent_packid=' . $_GET['parent_packid'] . '&amp;' . TOKEN
									, 'method' => 'post'
									, 'class' => 'emg-form val-form columns'
									)
								, $__ppFormArgs
								)
					);
?>

<p>Our camp bank works just like a bank account. Each camper receives a number that is their bank account for the week. We take their money at registration to start their account, and they can spend their money as they please. They are able to take out small amounts of pocket change for sodas and arcade games. This system helps prevent theft and loss. The campers do not have to carry any cash on them. We keep it safe for them, and at the end of the week, anything they do not spend, they get returned.</p>
<?
$form->text(array('label' => 'Amount <span class="hint">($)</span>'
				, 'name' => 'camp_money'
				, 'class' => 'val_money val_req'
				, 'value' => $_pp->details['camp_money']
				)
			);
$form->textarea(array('label' => 'Excluded Items'
				, 'name' => 'excluded_items'
				, 'class' => ''
				, 'value' => $_pp->details['excluded_items']
				)
			);
$__pph->submitButton($form);
?>