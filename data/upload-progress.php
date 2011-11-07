<?
logError('a bug');
$inputs = array(
	'hash' => newInput('hash', $_GET, 'min 1')
);

$this->ld['progress'] = uploadprogress_get_info($inputs['hash']);

// prevent divison by 0
if (!empty($this->ld['progress']) && $this->ld['progress']['bytes_total'] !== 0) {
	$this->ld['progress']['percentage'] =  round(($this->ld['progress']['bytes_uploaded'] / $this->ld['progress']['bytes_total']) * 100);
}
?>