<?php
// http://shiftingpixel.com/2008/03/03/smart-image-resizer/


/////////////////////
// EXAMPLES
/////////////////////

// Resizing a JPEG:
// <img src="/image.php/image-name.jpg?width=100&amp;height=100&amp;image=/path/to/image.jpg" alt="Don't forget your alt text" />

// Resizing and cropping a JPEG into a square:
// <img src="/image.php/image-name.jpg?width=100&amp;height=100&amp;cropratio=1:1&amp;image=/path/to/image.jpg" alt="Don't forget your alt text" />

// Matting a PNG with #990000:
// <img src="/image.php/image-name.png?color=900&amp;image=/path/to/image.png" alt="Don't forget your alt text" />
class Image {
	// configs
	private $memoryToAllocate = '100M';
	
	private $image = NULL;
	private $cachePath = NULL;
	private $size = NULL;
	private $mime = NULL;
	private $w = NULL;
	private $h = NULL;
	private $maxW = NULL;
	private $maxH = NULL;
	
	/*
	image		absolute path of local image starting with "/" (e.g. /images/toast.jpg)
	maxW		maximum width of final image in pixels (e.g. 700)
	maxH		maximum height of final image in pixels (e.g. 700)
	color		(optional) background hex color for filling transparent PNGs (e.g. 900 or 16a942)
	cropRatio	(optional) ratio of width to height to crop final image (e.g. 1:1 or 3:2)
	nocache		(optional) does not read image from the cache
	quality		(optional, 0-100, default: 90) quality of output image
	*/
	public function __construct ($image, $maxW = false, $maxH = false, $cachedPath = NULL) {
		$this->image = $image;
		$this->size = getimagesize($image);
		$this->mime	= $this->size['mime'];
		
		// Make sure that the requested file is actually an image
		if (substr($this->mime, 0, 6) != 'image/') {
			throw new Exception('The following file is not an image: ' . $this->image);	
		}
		
		// handle w/h
		$this->w = $this->size[0];
		$this->h = $this->size[1];
		
		$this->maxW = $maxW;
		$this->maxH = $maxH;
		
		
		// If either a max width or max height are not specified, we default to something
		// large so the unspecified dimension isn't a constraint on our resized image.
		// If neither are specified but the color is, we aren't going to be resizing at
		// all, just coloring.
		if (!$this->maxW && $this->maxH) {
			$this->maxW = 99999999999999;
		}
		else if ($this->maxW && !$this->maxH) {
			$this->maxH = 99999999999999;
		}
		else if (!$this->maxW && !$this->maxH) {
			$this->maxW = $this->w;
			$this->maxH = $this->h;
		}
		
		$this->cachePath = $cachedPath;
	}
	
	// We store our cached image filenames as a hash of the dimensions and the original filename
	public function cachedFile ($w, $h, $quality, $color) {
		$hash = md5(
				$this->image
				. 'x' . $w
				. 'x' . $h
				. 'x' . $quality
				. 'x' . $color
				);	
		return $this->cachePath . $hash;
	}
	
	public function isCachedOutdated ($w, $h, $quality, $color) {
		$cachedFile = $this->cachedFile($w, $h, $quality, $color);
		if (!file_exists($cachedFile)) {
			return true;
		}
		$imageModified = filemtime($this->image);
		$cachedModified = filemtime($cachedFile);
		
		return ($imageModified > $cachedModified) ? true : false;
	}
	
	/*
	If we don't have a max width or max height, OR the image is smaller than both
	we do not want to resize it, so we simply output the original image and exit
	*/
	public function needToResample () {
		if (!$this->maxW && !$this->maxH) {
			return false;	
		}
		if ($this->maxW >= $this->w && $this->maxH >= $this->h) {
			return false;
		}
		return true;
	}
	
	public function resampleProperties ($ratio = NULL) {
		$properties = array(	
			'offsetX' => 0
			, 'offsetY' => 0
			, 'w' => 0
			, 'h' => 0
		);
		
		if (!empty($ratio)) {
			$ratio = explode(':', $ratio);
			if (count($ratio) == 2) {
				$ratioComputed = $this->w / $this->h;
				$cropRatioComputed	= (float) $ratio[0] / (float) $ratio[1];
				
				if ($ratioComputed < $cropRatioComputed) { // Image is too tall so we will crop the top and bottom
					$origHeight	= $this->h;
					$this->h = $this->w / $cropRatioComputed;
					$properties['offsetY'] = ($origHeight - $this->h) / 2;
				}
				else if ($ratioComputed > $cropRatioComputed) { // Image is too wide so we will crop off the left and right sides
					$origWidth = $this->w;
					$this->w = $this->h * $cropRatioComputed;
					$properties['offsetX'] = ($origWidth - $this->w) / 2;
				}
			}
		}
		// Setting up the ratios needed for resizing. We will compare these below to determine how to
		// resize the image (based on height or based on width)
		$xRatio = $this->maxW / $this->w;
		$yRatio = $this->maxH / $this->h;
		
		if ($xRatio * $this->h < $this->maxH) { // Resize the image based on width
			$properties['h'] = ceil($xRatio * $this->h);
			$properties['w'] = $this->maxW;
		}
		else { // Resize the image based on height
			$properties['w'] = ceil($yRatio * $this->w);
			$properties['h'] = $this->maxH;
		}
		
		return $properties;
	}
	
	public function resampleAndCache ($properties, $quality = 90, $color = NULL) {
		ini_set('memory_limit', $this->memoryToAllocate);
		
		$color = !empty($color) ? preg_replace('/[^0-9a-fA-F]/', '', $color) : false;
		$_quality = $quality; // need original quality for cache file
		
		// Set up a blank canvas for our resized image (destination)
		$resampled = imagecreatetruecolor($properties['w'], $properties['h']);
		
		// Set up the appropriate image handling functions based on the original image's mime type
		switch ($this->mime) {
			case 'image/gif':
				// We will be converting GIFs to PNGs to avoid transparency issues when resizing GIFs
				// This is maybe not the ideal solution, but IE6 can suck it
				$creationFunction = 'ImageCreateFromGif';
				$outputFunction = 'ImagePng';
				$mime = 'image/png'; // We need to convert GIFs to PNGs
				$doSharpen = false;
				$quality = round(10 - ($quality / 10)); // We are converting the GIF to a PNG and PNG needs a compression level of 0 (no compression) through 9
			break;
			
			case 'image/x-png':
			case 'image/png':
				$creationFunction = 'ImageCreateFromPng';
				$outputFunction = 'ImagePng';
				$doSharpen = false;
				$quality = round(10 - ($quality / 10)); // PNG needs a compression level of 0 (no compression) through 9
			break;
			
			default:
				$creationFunction = 'ImageCreateFromJpeg';
				$outputFunction = 'ImageJpeg';
				$doSharpen = true;
			break;
		}
		
		// Read in the original image
		$src = $creationFunction($this->image);
		
		if (in_array($this->mime, array('image/gif', 'image/png'))) {
			if (!$color) {
				// If this is a GIF or a PNG, we need to set up transparency
				imagealphablending($resampled, false);
				imagesavealpha($resampled, true);
			}
			else {
				// Fill the background with the specified color for matting purposes
				if ($color[0] == '#') {
					$color = substr($color, 1);
				}
				$background	= false;
				
				if (strlen($color) == 6) {
					$background	= imagecolorallocate($resampled, hexdec($color[0] . $color[1]), hexdec($color[2] . $color[3]), hexdec($color[4] . $color[5]));
				}
				else if (strlen($color) == 3) {
					$background	= imagecolorallocate($resampled, hexdec($color[0] . $color[0]), hexdec($color[1] . $color[1]), hexdec($color[2] . $color[2]));
				}
				if ($background) {
					imagefill($resampled, 0, 0, $background);
				}
			}
		}

		// Resample the original image into the resized canvas we set up earlier
		imagecopyresampled($resampled, $src, 0, 0, $properties['offsetX'], $properties['offsetY'], $properties['w'], $properties['h'], $this->w, $this->h);
		
		if ($doSharpen) {
			// Sharpen the image based on two things:
			//	(1) the difference between the original size and the final size
			//	(2) the final size
			$sharpness = $this->findSharp($this->w, $properties['w']);
			
			$sharpenMatrix = array(
				array(-1, -2, -1)
				, array(-2, $sharpness + 12, -2)
				, array(-1, -2, -1)
			);
			$divisor = $sharpness;
			$offset = 0;
			imageconvolution($resampled, $sharpenMatrix, $divisor, $offset);
		}
		
		// Write the resized image to the cache
		$cacheFile = $this->cachedFile($properties['w'], $properties['h'], $_quality, $color);
		$outputFunction($resampled, $cacheFile, $quality);
		
		// Clean up the memory
		imagedestroy($src);
		imagedestroy($resampled);
	}
	
	public function output ($path) {
		$data = file_get_contents($path);
		
		$lastModifiedString	= gmdate('D, d M Y H:i:s', filemtime($path)) . ' GMT';
		$etag = md5($data);
		
		$this->doConditionalGet($etag, $lastModifiedString);
		
		header('Content-type: ' . $this->mime);
		header('Content-Length: ' . strlen($data));
		
		echo $data;
		unset($data); // this should bet unset at end of funciton, but since its so big, feels safe unsetting it
	}
	
	private function doConditionalGet ($etag, $lastModified) {
		header('Last-Modified: ' . $lastModified);
		header('ETag: "{' .  $etag . '}"');
			
		$if_none_match = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) : false;
		
		$if_modified_since = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? stripslashes($_SERVER['HTTP_IF_MODIFIED_SINCE']) : false;
		
		if (!$if_modified_since && !$if_none_match) {
			return;
		}
		// etag is there but doesn't match
		if ($if_none_match && $if_none_match != $etag && $if_none_match != '"' . $etag . '"') {
			return;
		}
		// if-modified-since is there but doesn't match
		if ($if_modified_since && $if_modified_since != $lastModified) {
			return; 
		}
		// Nothing has changed since their last request - serve a 304 and exit
		header('HTTP/1.1 304 Not Modified');
	}
	
	private function findSharp ($orig, $final) {
		$final = $final * (750.0 / $orig);
		$a = 52;
		$b = -0.27810650887573124;
		$c = .00047337278106508946;
		
		$result = $a + $b * $final + $c * $final * $final;
		
		return max(round($result), 0);
	}
}
?>