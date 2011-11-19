<?
require_once(DR . '/php/image.php');

// prevent attack on caching
$allowedConfigs = array(
	array(
		'w' => '100'
		, 'h' => '100'
		, 'color' => NULL
		, 'ratio' => '1:1'
		, 'quality' => '90'
	)
	// upload crop preview
	, array(
		'w' => '500'
		, 'h' => '500'
		, 'color' => NULL
		, 'ratio' => NULL
		, 'quality' => 90
	)
);


/*
image		absolute path of local image starting with "/" (e.g. /images/toast.jpg)
maxW		maximum width of final image in pixels (e.g. 700)
maxH		maximum height of final image in pixels (e.g. 700)
color		(optional) background hex color for filling transparent PNGs (e.g. 900 or 16a942)
cropRatio	(optional) ratio of width to height to crop final image (e.g. 1:1 or 3:2)
nocache		(optional) does not read image from the cache
quality		(optional, 0-100, default: 90) quality of output image
*/
$inputs = array(
	'path' => newInput('path', $_GET, 'min 1 path')
	, 'w' => newInput('w', $_GET, 'int')
	, 'h' => newInput('h', $_GET, 'int')
	, 'color' => newInput('color', $_GET)
	, 'ratio' => newInput('ratio', $_GET)
	, 'quality' => newInput('quality', $_GET, 'int', 90)
	, 'cache' => newInput('nocache', $_GET, 'int', '1')
);

// prevent attack on caching
foreach ($allowedConfigs as $config) {
	$allowed = true;
	foreach ($config as $property => $value) {
		if ($inputs[$property] != $value) {
			$allowed = false;	
		}
	}
	if ($allowed) {
		break;	
	}
}
if (!$allowed) {
	throw new Exception('Image configuration is not allowed.');
}

// determine if tmp file or client files
$imagePath = (dirname($inputs['path']) == 'tmp') ? '/' . $inputs['path'] : CLIENTFILES . '/' . $inputs['path'];
// make sure image exists and is readable
$extensions = array('gif', 'jpg', 'png');
$imageExt = NULL;
foreach ($extensions as $extension) {
	if (is_readable($imagePath . '.' . $extension)) {
		$imageExt = $extension;
	}
}
if ($imageExt === NULL) {
	throw new Exception('Image was not readable: ' . $imagePath);
}

$cachedPath = CLIENTFILES . '/image-cache/' ;
$image = new Image($imagePath . '.' . $imageExt, $inputs['w'], $inputs['h'], $cachedPath);

// no need to resample, use original file
if (!$image->needToResample() && !empty($inputs['color'])) {
	$image->output($imagePath . '.' . $imageExt);
}
else {
	// resample and cache file if needed
	$properties = $image->resampleProperties($inputs['ratio']);
	$oudatedCachedFile = $image->isCachedOutdated($properties['w'], $properties['h'], $inputs['quality'], $inputs['color']);
	if ($oudatedCachedFile === true || $inputs['cache'] === '0') {
		$image->resampleAndCache ($properties, $inputs['quality'], $inputs['color']);
	}
	
	// output image
	$cachedFile = $image->cachedFile($properties['w'], $properties['h'], $inputs['quality'], $inputs['color']);
	$image->output($cachedFile);
}
?>