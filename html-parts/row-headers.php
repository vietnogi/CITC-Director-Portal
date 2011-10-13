<?
// 6:11 PM 11/29/2010
?>
<thead>
    <tr>
    	<?
		foreach ($this->ld['row_headers'] as $header => $sort) {
			?>
			<th scope="col" class="<?= cleanUrl($header) ?>">
			<?
			if (empty($sort)) {
				echo $header;
			}
			else {
				$sortClass = NULL;
				if ($this->ld['inputs']['sort_field'] == $sort) {
					$sortClass = $this->ld['inputs']['sort_desc'] == '0' ? 'sort-asc' : 'sort-desc';
				}
				
				//build href with query string
				parse_str($_SERVER['QUERY_STRING'], $queryVars);
				
				$queryVars['sort_field'] = $sort;
				$queryVars['sort_desc'] = empty($this->ld['inputs']['sort_desc']) ? '1' : '0';
				$queryStr = http_build_query($queryVars);
				?>
				<a href="<?= $this->url($GLOBALS['bc']->uri) . '&amp;' . $queryStr ?>" title="Sort by <?= $header ?>"<?= !empty($class) ? ' class="' . $class . '"' : '' ?>><?= $header ?></a>
				<?
			}
			?>
			</th>
			<?
		}
		?>
    </tr>
</thead>
