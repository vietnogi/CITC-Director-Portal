<?
require DR . '/php/html-purifier/html-purifier.php';

$inputs = array(
	'season_id' => newInput('season_id', $_GET, 'min 1 int')
	, 'description' => newInput('description', $_POST)
);

// purify html
$config = HTMLPurifier_Config::createDefault();
// configuration goes here:
$config->set('Core.Encoding', 'UTF-8'); // replace with your encoding
$config->set('HTML.Doctype', 'XHTML 1.0 Transitional'); // replace with your doctype
$purifier = new HTMLPurifier($config);
$pureHtml = $purifier->purify($inputs['description']);

// update db
$values = array(
	'description' => $pureHtml
);
$where = 'season_id = :season_id';
$wherevals = array(
	':season_id' => $inputs['season_id']
);

$GLOBALS['mysql']->update('season', $values, $where, $wherevals);

$_SESSION[CR]['user-success'] = 'Season updated';
$this->redirect = $_SERVER['HTTP_REFERER'];
?>