<?
// Table data
if(!empty($_dbMain->filter->rows)){
	if(empty($_delaction)){
		$_delaction = CR . '/action' . $_bc->path . '/delete';
		if(TOKEN != ''){
			$_delaction = $_delaction . '?' . TOKEN;
		}
		
		if($_SERVER['QUERY_STRING'] != ''){
			$_delaction .= strstr($_delaction, '?') !== false ? '&' : '?';
			$_delaction .= $_SERVER['QUERY_STRING'];
		}
	}
	?>
		<table class="search-results <?= $_bc->crumbs[count($_bc->crumbs) - 2] ?>">
			<?
			// <th> headings and default <td> values
			require DR . '/parts/admin/sort-header.php';
			
			$i = 0;
			$rowCount = $_dbMain->pl->offset + 1;
			$_sums = array(); //footer summation
			foreach($_dbMain->filter->rows as $row){
				$classes = array();
				if ($i % 2 == 1) {
					array_push($classes, 'even');
				}
				if(!empty($_SESSION[CR]['affectedRowids']) && in_array($row[$_dbMain->tb->primary->name], $_SESSION[CR]['affectedRowids'])){
					array_push($classes, 'updated');
				}
				$classStr = implode(' ', $classes);
				?>
				<tr<?= !empty($classStr) ? ' class="' . $classStr . '"' : '' ?> id="<?= $_uniqueidPrefix ?>-tr-<?= $i ?>">
					<?
					if($_rowui->checkbox){
						?>
						<td class="checkbox"><input type="checkbox" id="checkBox-<?= $_uniqueidPrefix ?>-<?= $row[$_dbMain->tb->primary->name] ?>" value="<?= $row[$_dbMain->tb->primary->name] ?>" name="<?= $_dbMain->tb->primary->name ?>[]" /></td>
						<?
					}
					
					if(!$_list['norownum']){ //for #
						?>
						<td class="row-count"><?= $rowCount ?></td>
						<?	
					}
					// Row Data
					$colCount = 0;
					foreach ($_dbMain->tb->fields as $field) {
						if($field == $_dbMain->tb->primary || $field->foreign){
							continue;
						}
						if(!isset($_sums[$field->name])){
							$_sums[$field->name] = NULL;	
						}
						if(is_numeric($row[$field->name])){
							$_sums[$field->name] += $row[$field->name];	
						}
						?>
						<td class="<?= cleanUrl($field->name) ?><?= $colCount == 0 ? ' row-actions' : '' ?>">
							<?
							$_custom = false; //use as a flag to determine if field has customization
							//can customize column with a file, need to set $_custom to false to prevent default
							$columnPath = getDynamicSectionFile('parts', 'columns.php');
							if($columnPath){
								require DR . $columnPath;
							}
							
							//Automate
							if(!$_custom){
								//mm/dd/yyyy format or mm/dd/yyyy hh:mm:ss am/pm format
								$isDate = preg_match('/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/', $row[$field->name]);
								$isDateTime = preg_match('/^[0-9]{4}\-[0-9]{2}\-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/', $row[$field->name]);
								if($isDate || $isDateTime){ 
									$row[$field->name] = $_dates->usFormat($row[$field->name]);
								}
								
								//description text/nl2br or html
								$isHtml = strpos($row[$field->name], '[html]') !== false ? true : false;
								if($isHtml){
									$row[$field->name] = html_entity_decode(str_replace('[html]', '', $row[$field->name]));
									$row[$field->name] = $_htmlParser->parse($row[$field->name]);
								}
								else if($field->type != 'varbinary'){
									$row[$field->name] = nl2br($row[$field->name]);
								}
								//name
								if($field->name == 'name'){
									$row[$field->name] = html_entity_decode($row[$field->name]);
									$row[$field->name] = $_htmlParser->parse($row[$field->name]); //cross site prevention
								}
								//money format
								if(isMoney($row[$field->name]) && $field->name != 'position'){
									$row[$field->name] = moneyFormat($row[$field->name]);	
								}
								//boolean
								if($field->type2 == 'boolean'){
									$row[$field->name] = $row[$field->name] == 1 ? 'Yes' : 'No';
								}
								//decrypt
								if($field->type == 'varbinary'){
									$row[$field->name] = decrypt($row[$field->name]);
								}
								
								//handle columns that are too long
								$maxLength = 320;
								if(isset($row[$field->name][$maxLength]) && !$isHtml){
									$blurbid = $i .'-' . $field->name . '-blurb';
									$fullid = $i .'-' . $field->name . '-full';
									?>
									<div id="<?= $blurbid ?>">
										<?= blurb($row[$field->name], $maxLength) ?>
										<p class="toggle-note">
											<a href="javascript:toggleClassArray(new Array('<?= $blurbid ?>', '<?= $fullid ?>'), 'hidden')">[+] Show More</a>
										</p>
									</div>
									<div id="<?= $fullid ?>" class="hidden">
										<?= $row[$field->name] ?>
										<p class="toggle-note">
											<a href="javascript:toggleClassArray(new Array('<?= $blurbid ?>', '<?= $fullid ?>'), 'hidden')">[-] Show Less</a>
										</p>
									</div>
									<?
								}
								else{
									echo $row[$field->name];
								}
							}
							if ($colCount == 0) {
								?>
								<ul class="row-actions"><? 
									if($_rowui->delete){ //can not automate based on file because some file may not exist
										if($_rowui->deleteMsg != NULL){
											$deleteAlert = $_rowui->getDeleteMsg($row);
										}
										else if($row['name'] != ''){
											$deleteAlert = 'Delete `' . $row['name'] . '`?';
										}
										else{
											$deleteAlert = 'Delete Row #' . $rowCount . '?';
										}
										?><li class="delete"><a href="#" onclick="confirm2(event, '<?= jsClean($deleteAlert) ?>', 'checkAll(\'<?= $_dbMain->tb->primary->name ?>[]\', false); $(\'#checkBox-<?= $_uniqueidPrefix ?>-<?= $row[$_dbMain->tb->primary->name]  ?>\')[0].checked = true; $(\'#<?= $_uniqueidPrefix ?>-del-form\')[0].onsubmit.call(byId(\'<?= $_uniqueidPrefix ?>-del-form\'))'); return false"><span class="tip">Delete</span></a></li><?
									}
									if($_rowui->edit){
										?><li class="edit"><a href="<?= CR ?><?= $_bc->path ?>/edit?<?= $_dbMain->tb->primary->name ?>=<?= $row[$_dbMain->tb->primary->name] ?><?= !empty($_SERVER['QUERY_STRING']) ? '&amp;' . $_SERVER['QUERY_STRING'] : '' ?>&amp;<?= refreshActionstr() ?>" rel="ajax modal" onclick="activeRow(this);"><span class="tip">View/Edit <?= $_rowui->addText ?> Details</span></a></li><?	
									}
									
									$actionPath = getDynamicSectionFile('parts', 'actions.php');
									if($actionPath){
										require DR . $actionPath;
									}
									if($_rowui->print){
										//try to include any keys, because the summary may need the key
										$idFields = rowIdFields($row)
										?><li class="print"><a href="<?= CR ?><?= $_bc->path ?>/print-summary?<?= implode('&amp;', $idFields) ?>&amp;print=1"><span class="tip">Print Summary</span></a></li><?	
									}
									?></ul>
								<?
							}
							$colCount++;
							?>
						</td>
						<?
					}
					?>
				</tr>
				<?
				$i++;
				$rowCount++;
			}
			
			if($_rowui->footer){
				require DR . '/parts/admin/sort-footer.php';
			}	
			?>
		</table>
        <?
		if($_rowui->checkbox){
			?>
            <fieldset class="no-legend check-all">
                <input type="checkbox" id="<?= $_uniqueidPrefix ?>-check-all-btn" onclick="checkAll('<?= $_dbMain->tb->primary->name ?>[]', this.checked);" />
                <label class="checkbox-label" for="<?= $_uniqueidPrefix ?>-check-all-btn">Check All</label>
            </fieldset>
            <?
		}
		?>
	</form>
	<iframe name="<?= $_uniqueidPrefix ?>-del-form-target" src="#" class="hide"></iframe>
	<?
	require DR . '/parts/admin/table-actions.php';
	
	// Page Links
	if($_rowui->pl){
		$_dbMain->pl->show(array(), true);
	}
}
else{
	?>
	<p>No results found.</p>
	<?
}
?>