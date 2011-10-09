<?
// 1:51 PM 6/15/2010
class Breadcrumbs {
	public $crumbs;	// array of crumbs
	public $levels;	// crumbs count
	public $section; // first level dir
	public $area; // dir before page
	public $page; // current page (last crumb)
	public $path; // path minus current page
	public $uri; // full path

	function __construct() {
		$uri = $this->removeQuery($_SERVER['REQUEST_URI']); // Edit out query string (GET)
		
		// Remove 'action' and 'ajax'
		// Add additonal keywords if needed
		$uri = preg_replace('/\/(action|ajax)\//', '', $uri);
		if (defined('CR')) {
			$this->crumbs = explode('/', preg_replace('/' . str_replace('/', '\/', CR) . '\/?/', '', $uri, 1));
		}
		else {
			$this->crumbs = explode('/', substr($uri, 1));
		}
		$this->levels = count($this->crumbs);
		
		// Section, Page, Area
		$this->section = $this->crumbs[0];
		$this->area = $this->levels > 1 ? $this->crumbs[$this->levels - 2] : NULL;
		$this->page = $this->levels > 0 ? $this->crumbs[$this->levels - 1] : NULL;
		
		
		// Path
		for ($i = 0; $i < $this->levels - 1; $i++) {
			$this->path .= '/' . $this->crumbs[$i];
		}
		$this->uri = $this->path . '/' . $this->page;
	}
	
	private function removeQuery($url){
		$pos = strpos($url, '?');
		if($pos === false){
			return $url;
		}
		return substr($url, 0, $pos);
	}
	
	// Prints breadcrumbs ordered list
	function ol($delimiter = '', $maxLength = 0, $removeLinkStart = 0, $hideLevels = -1, $queryString = '') {
		global $__cleanNameMap;
		?>
		<ol class="breadcrumbs">
			<?
			if ($hideLevels < 0) {
				?>
				<li><a href="<?= CR ?>/<?= $queryString ?>" title="<?= SITENAME ?>"><?= SITENAME ?></a><?= $this->crumbs[0] ? ' ' . $delimiter . ' ' : ''?></li>
				<?
			}
			$bcUrl = CR;
			for ($i = 0; $i < $this->levels; $i++) {
				if (!empty($this->crumbs[$i])) {
					$bcUrl .= '/' . $this->crumbs[$i];
					$unClean = !empty($__cleanNameMap[$this->crumbs[$i]]) ? $__cleanNameMap[$this->crumbs[$i]] : uncleanUrl($this->crumbs[$i]);
					
					if ($i >= $hideLevels) {
						?>
						<li<?= $i == ($this->levels - 1) ? ' class="current"' : '' ?>>
							<?
							// Remove link
							if ($removeLinkStart > 0 && $i >= $removeLinkStart - 1) {
								?>
								<a href="<?= CR . str_replace('/' . $this->crumbs[$i], '', $this->uri) . $queryString ?>" class="remove">x</a>
								<?
							}
							if ($i == ($this->levels - 1)) {
								?>
								<?= $maxLength > 0 ? blurb($unClean, $maxLength, '&hellip;') : $unClean ?>
								<?
							}
							else {
								?>
								<a href="<?= $bcUrl . $queryString ?>" title="<?= strip_tags($unClean) ?>"><?= $maxLength > 0 ? blurb($unClean, $maxLength, '&hellip;') : $unClean ?></a> <?= $delimiter ?> 
								<?
							}
							?>
						</li>
						<?
					}
				}
			}
			?>
		</ol>
		<?
	}
}
?>