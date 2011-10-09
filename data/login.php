<?
$query = 'SELECT lead_referrer.* FROM lead_referrer';
$this->data['referrers'] = $GLOBALS['mysql']->get($query);
$this->data['referrers'] = rowsToArray($this->data['referrers'], 'lead_referrer_id', 'name'); //for htmlSel
?>