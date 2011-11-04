<?
$inputs = array(
	'hash' => newInput('hash', $_GET, 'min 1')
);

$progress = uploadprogress_get_info($inputs['hash']);
// prevent divison by 0
if (empty($progress) || $progress['bytes_total'] == 0) {
	$this->ld['percent'] = 100;
}
else {
	$this->ld['percent'] =  round(($progress['bytes_uploaded'] / $progress['bytes_total']) * 100);
}
?>