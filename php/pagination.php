<?php
/* 4:45 PM 2/10/2011 added if($_GET['pgl_numPerPage'] > 1000){ //safegaurd from viewing too many rows and crashing server
Copyright © 2008 Eckx Media Group, LLC. All rights reserved.
Eckx Media Group respects the intellectual property of others, and we ask our users to do the same.
*/

/*
Usage:
$pl = new pageLinks(2, count($articles));
$pl->show();
for($i = $pl->startItem; $i < ($pl->startItem + $pl->itemPerPage) && $i<$pl->totalItem ; $i++){ 

or 

use the $pl->startItem, $pl->itemPerPage for mysql limit, "$pl->startItem, $pl->itemPerPage" 

*/

// pack: dont replace limit offset upperResults lowerResults

//allows page links to be setup, ie: next page, previous page, page 1, page 2, page 3, etc
class Pagination {
	var $startItem; // listing begins with this index 
	var $itemPerPage; //list this number of item per page
	var $maxItemPerPage;
	var $totalItem; //total of items to be listed
	var $url; //where links will go to
	var $hiddenstr; //to hold current GET variables
	var $totalPages; //all items will be listed in this many pages
	var $curPage; //the current page for listing
	var $offset;
	var $limit;
	var $upperResults;
	var $lowerResults;
	
	var $extremesRange = 1;
	var $midRange = 5;
	var $minPages; // = 13; //($midRange + $extremeRange) + 1
	var $lowThreshold;
	var $highThreshold;
	var $midLower;
	var $midUpper;
	var $highLower;
	var $highUpper;
	
	var $instance = 0; // instance counter

	function pageLinks($itemPerPage, $totalItem, $maxItemPerPage = 0){
		$this->maxItemPerPage = $maxItemPerPage;
		
		$cookieName = cleanUrl('pgl-numPerPage-'.$GLOBALS['bc']->uri);
		if(is_numeric($_COOKIE[$cookieName])){
			$itemPerPage = $_COOKIE[$cookieName];
		}	
		if(is_numeric($_GET['pgl_numPerPage'])){
			if($_GET['pgl_numPerPage'] > 1000){ //safegaurd from viewing too many rows and crashing server
				$_GET['pgl_numPerPage'] = 1000;	
			}
			if($_GET['pgl_numPerPage'] != 0){
				// Check if $_GET['pgl_numPerPage'] < maxItemPerPage
				$itemPerPage = ($this->maxItemPerPage > 0 && $_GET['pgl_numPerPage'] > $this->maxItemPerPage ? $this->maxItemPerPage : $_GET['pgl_numPerPage']);
				if($itemPerPage < 200 && !headers_sent()){ // prevent errors
					setcookie($cookieName, $itemPerPage, time()+2629743, '/', $_SERVER['SERVER_NAME']);
				}
			}
		}

		if(!is_numeric($itemPerPage) || !is_numeric($totalItem)){
			trigger_error('pl-v3: $itemPerPage, $totalItem is not a number');
		}
		elseif($itemPerPage > $totalItem){
			$itemPerPage = $totalItem;
		}
		
		$this->itemPerPage = floor($itemPerPage);
		$this->totalItem = $totalItem;
		if($this->itemPerPage == 0){
			$this->totalPages = 0;
		}
		else{
			$this->totalPages = ceil($this->totalItem / $this->itemPerPage);
		}
		
		if(!is_numeric($_GET['pgl_page']) || $_GET['pgl_page'] > $this->totalPages){ //invalid page number
			$_GET['pgl_page'] = 1;
		}
		
		$this->curPage = $_GET['pgl_page'];
		if(!is_numeric($this->curPage)){
			$this->curPage = 1;
		}
		elseif(($this->curPage > $this->totalItem  && !($this->totalItem == 1 && $this->curPage == 2)) || $this->curPage <= 0){ // prevent bug when number of results is only 1
			$this->curPage = 1;
		}
		$this->startItem =  ($this->curPage -1 ) * $this->itemPerPage;
		$this->offset = $this->startItem;
		$this->limit = $this->itemPerPage;
		
		//create a new url
		$oldUrl = explode('?', $_SERVER['REQUEST_URI']); //seperate get variables
		$this->url = preg_replace('/\/(action|ajax)\//', '/', $oldUrl[0]).'?'; //need to remove ajax
		
		//omit variables to prevent duplicates
		$omitList = array('pgl_page', 'pgl_numPerPage', 'p');
		$requestUri = removeQueryStringVars('pgl_page', 'pgl_numPerPage', 'p', 'tags');
		$this->url .= $requestUri;
		
		//hidden input fields for #results/page form
		parse_str($requestUri, $queryStringVars);
		foreach($queryStringVars as $name => $value){			
			$this->hiddenstr .= '<input type="hidden" name="' . $name . '" value="' . $value . '" />';
		}
		
		// Set values for show algorithm
		$this->lowThreshold = floor($this->midRange / 2) + $this->extremesRange + 2;
		$this->highThreshold = ($this->totalPages - (ceil($this->midRange / 2) + $this->extremesRange + 2)) + 2;
		$this->minPages = ($this->midRange + $this->extremesRange) + 1;
		$this->midLower = $this->curPage - floor($this->midRange / 2);
		$this->midUpper = $this->midLower + $this->midRange - 1;
		$this->highLower = ($this->totalPages - $this->midRange) + 1;
		$this->highUpper = ($this->totalPages - $this->extremesRange) + 1;
		
		
		if ($this->totalItem > 0) {
			$this->lowerResults = (($this->curPage - 1) * $this->itemPerPage) + 1;
		}
		else {
			$this->lowerResults = 0;
		}
		$this->upperResults = $this->curPage * $this->itemPerPage;
		if ($this->upperResults > $this->totalItem) {
			$this->upperResults = $this->totalItem;
		}
	}
	
	function showLink($start, $end) {
		for ($i = $start; $i <= $end; $i++) {
			?>
			<li<?= ($i == $this->curPage) ? ' class="current"' : '' ?>><a href="<?= $this->url ?>&amp;pgl_page=<?= $i ?>&amp;pgl_numPerPage=<?= $this->itemPerPage ?>" rel="<?= ajaxFlagstr() ?>"><?= $i ?></a></li>
			<?
		}
	}
	function showEllipsis() {
		?>
		<li class="ellipsis">&hellip;</li>
		<?
	}
	
	function show($hide = array(), $pageBottom = false){ //hide is array(0, 1, 2, 3, 4, 5) 0: #result, 1: results of, 2: next, 3:prev, 4: page #, 5: View All
		//links
		?>
		<div class="page-links<?= $pageBottom == true ? ' bottom' : '' ?>">
			<?
			if(!in_array(1, $hide) && $pageBottom == true){ //option to hide
				$this->showResults();
				if (function_exists('topLink')) {
					topLink();
				}
			}
			?>
			<ol class="links">
				<?
				if($this->curPage > 1){
					if(!in_array(3, $hide)){ //option to hide
						?>
						<li class="previous"><a href="<?= $this->url ?>&amp;pgl_page=<?= ($this->curPage-1) ?>&amp;pgl_numPerPage=<?= $this->itemPerPage ?>" rel="<?= ajaxFlagstr() ?>">&laquo; Prev</a></li>
						<?
					}
				}
				if(!in_array(4, $hide)){ //option to hide
					if ($this->totalPages < $this->minPages) {
						$this->showLink(1, $this->totalPages);
					}
					else {
		
						if ($this->curPage < $this->lowThreshold || $this->curPage > $this->highThreshold) {
							if ($this->curPage < $this->lowThreshold) {
								$this->showLink(1, $this->midRange);
								$this->showEllipsis();
								$this->showLink($this->highUpper, $this->totalPages);
							}
							elseif ($this->curPage > $this->highThreshold) {
								$this->showLink(1, $this->extremesRange);
								$this->showEllipsis();
								$this->showLink($this->highLower, $this->totalPages);
							}
						}
						else {
							$this->showLink(1, $this->extremesRange);
							$this->showEllipsis();
							$this->showLink($this->midLower, $this->midUpper);
							$this->showEllipsis();
							$this->showLink($this->highUpper, $this->totalPages);
						}
					}
				}
				if(!in_array(2, $hide)){ //option to hide
					if($this->curPage < $this->totalPages){
						?>
						<li class="next"><a href="<?= $this->url ?>&amp;pgl_page=<?= ($this->curPage+1) ?>&amp;pgl_numPerPage=<?= $this->itemPerPage ?>" rel="<?= ajaxFlagstr() ?>">Next &raquo;</a></li>
						<?
					}
				}
				if (!in_array(5, $hide) && $this->totalPages > 1) {
					?>
					<li class="view-all"><a href="<?= $this->url ?>&amp;pgl_page=1&amp;pgl_numPerPage=<?= $this->totalItem ?>" rel="<?= ajaxFlagstr() ?>">View All</a></li>
					<?
				}
				?>
			</ol>
			<?
			if(!in_array(0, $hide)){ //option to hide
				?>
				<form class="results-per-page emg-form <?= ajaxFlagstr() ?>" method="get" action="<?= $this->url ?>">
					<fieldset class="no-legend">
						<label for="results-page-<?= $this->instance ?>"># Results / Page:</label>
						<input id="results-page-<?= $this->instance ?>" name="pgl_numPerPage" type="text" value="<?= $this->itemPerPage ?>" class="autocomplete-off" />
						<input type="submit" value="Go" class="pgl_numPerPageGo" />
						<input type="hidden" name="pgl_page" value="1" />
						<?= $this->hiddenstr ?>
					</fieldset>
				</form>
				<?
			}
			if(!in_array(1, $hide) && $pageBottom == false){ //option to hide
				$this->showResults();
			}
			?>
		</div>
		<?
		$this->instance++;
	}
	
	function showResults() {
		?>
		<p class="results">
			Showing results <?= $this->lowerResults ?> - <?= $this->upperResults ?> of <?= $this->totalItem ?>.
		</p>
		<?
	}
}
?>