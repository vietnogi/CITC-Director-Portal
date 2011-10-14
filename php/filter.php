<?
class Filter {
	private $keytable = NULL;
	private $keyfield = NULL;
	private $queryParts = array(
		'select' => '*'
		, 'from' => NULL
		, 'where' => NULL
		, 'orderby' => NULL
		, 'groupby' => NULL
	);
	private $comparisons = array(
		'string' => array('like', 'not like', '=', '!=', 'is blank', 'not blank')
		, 'numeric' => array('=', '!=', '<', '>', '<=', '>=', 'is blank', 'not blank')
		, 'date' => array('=', '!=', '<', '>', '<=', '>=', 'is blank', 'not blank')
		, 'boolean' => array('=', '!=', 'is blank', 'not blank')
	);
	private $preps = array(); // for PDO
	
	public function __construct ($keytable, $fieldList, $from, $queryParts = array(), $keyfield = NULL) {
		if (empty($keytable)) {
			trigger_error('$keytable is empty.', E_USER_ERROR);	
		}
		$this->keytable = $keytable;
		if (empty($keyfield)) {
			$this->keyfield = $this->keytable . '_id';
		}
		$this->queryParts = array_merge($this->queryParts, $queryParts);
		
		if (empty($this->queryParts['select'])) {
			trigger_error("queryParts['select'] is empty", E_USER_ERROR);
		}
		if (empty($this->queryParts['from'])) {
			trigger_error("queryParts['from'] is empty", E_USER_ERROR);
		}
		
		// build where string
		$values = $this->getValues($from, $fieldList);
		$andstr = $this->andstr($values);
		$orstr = $this->orstr($fieldList, $this->getKeywords($from));
		$this->queryParts['where'] = $this->wherestr($andstr, $orstr, $this->queryParts['where']);
		
		$this->queryParts['orderby'] = empty($this->queryParts['orderby']) ? $this->orderbystr($from, $fieldList) : $this->queryParts['orderby'];

	}
	
	private function orderbystr ($from, $fieldList) {
		$from['sort_field'] = empty($from['sort_field']) ? NULL : $from['sort_field'];
		// default to keyfield
		$tmpOrderby = '`' . $this->keytable . '`.' . $this->keyfield;
		foreach ($fieldList as $table => $fields) {
			foreach ($fields as $field => $type) {
				if ($from['sort_field'] == $field) {
					$tmpOrderby = (empty($table) ? '' : "`$table.`") . "`$field`";
				}
			}
		}
		return $tmpOrderby  . ' ' . $this->getOrderDirection($from);
	}
	
	private function getOrderDirection ($from) {
		$from['sort_desc'] = !isset($from['sort_desc']) ? NULL : $from['sort_desc'];
		return ($from['sort_desc'] === '0') ? 'ASC' : 'DESC';
	}
	
	private function getKeywords ($from) {
		$from['keyword'] = !isset($from['keyword']) ? '' : $from['keyword'];
		
		// dont use empty, because $keyword can be 0
		if ($from['keyword'] == '') {
			return array();	
		}
		
		$tmpKeywords = explode(',', $from['keyword']);
		$keywords = array();
		foreach ($tmpKeywords as $i => $keyword) {
			$keyword = trim($keyword);
			if ($keyword == '') {
				continue;	
			}
			array_push($keywords, $keyword);
		}
		
		return $keywords;
	}

	private function getValues ($from, $fieldList) {
		$values = array();
		$count = count($from);
		foreach ($fieldList as $table => $fields) {
			foreach ($fields as $field => $type) {
				// since a field can be use unlimited of time.
				for ($i = 0; $i <= $count; $i++) {
					$name = $table . '-' . $field . '-' . $i;
					if (!isset($from[$name])) { 
						break;
					}
					
					
					$value = $from[$name];
					//html_entity_decode is needed because framwork converts it.
					$comparison = html_entity_decode($from[$name . '-comp']);
					if (($value == '' && $comparison != 'is blank' && $comparison != 'not blank')   || $comparison == '') {
						//value is blank
						continue;
					}
					
					//automate date formatting
					if ($type == 'date') {
						$value = $GLOBALS['dates']->standardFormat($value);
					}
					if (!isset($this->comparisons[$type])) {
						logError('Invalid comparison:' . $comparison);
					}
					array_push($values, array(
						'field' => (empty($table) ? '' : "`$table.`") . "`$field`"
						, 'comparison' => $comparison
						, 'value' => $value
					));
				}
			}
		}
		
		return $values;
	}
	
	// retrieve or str
	private function orstr ($fieldList, $keywords) {
		$ors = array();
		if (empty($keywords) || empty($fieldList)) {
			return '';	
		}
		$prepCnt = 0; // for PDO
		foreach ($fieldList as $table => $fields) {
			foreach ($fields as $field => $type) {
				foreach ($keywords as $i => $keyword) {
					// for PDO replacement, make sure its unique
					$prepCnt++;
					$prepStr = ':field_keyword_' . $prepCnt;
					array_push($ors, (empty($table) ? '' : "`$table.`") . "`$field` LIKE $prepStr");
					$this->preps[$prepStr] = '%' . $keyword . '%';
				}
			}
		}
		
		return implode(' OR ', $ors);
	}
	
	private function andstr ($values) {
		$prepCnt = 0; // for PDO
		$ands = array();
		foreach ($values as $value) {
			$prepCnt++;
			$prepStr = ':field_' . $prepCnt; //for PDO replacement, make sure its unique
			
			if ($value['comparison'] == 'is blank' || $value['comparison'] == 'not blank') {
				if($value['comparison'] == 'is blank'){
					array_push($ands, $value['field'] . ' IS NULL');	
				}
				else{
					array_push($ands, $value['field'] . ' IS NOT NULL');
				}
			}
			else {
				// automate date format
				$searchTime = strpos($value['value'], ':') === false ? false : true;
				if ($type == 'date' && !$searchTime) {
					array_push($ands, 'DATE_FORMAT(' . $value['field'] . ', "%Y-%m-%d") ' . $value['comparison'] . ' ' . $prepStr);
				}
				else {
					array_push($ands, $value['field'] . ' ' . $value['comparison'] . ' ' . $prepStr);
				}
				if ($value['comparison'] == 'like' || $value['comparison'] == 'not like') {
					$this->preps[$prepStr] = '%' . $value['value'] . '%';
				}
				else {
					$this->preps[$prepStr] = $value['value'];
				}
			}
		}
		
		return implode(' AND ', $ands);
	}
	
	public function rowCount () {
		$query = 'SELECT COUNT(*) AS count FROM ' . $this->queryParts['from'];
		if (!empty($this->queryParts['where'])) {
			$query .= ' WHERE ' . $this->queryParts['where'];
		}
		$count = $GLOBALS['mysql']->getSingle($query, $this->preps);
		return empty($count) ? '0' : $count['count'];
	}
	
	/*
	from old class not sure if still need
	public function getFilters(){
		$filters = array();
		if($this->primaryValue != NULL){
			$filters['keyfield'] = array('name' => $this->tb->primary->name
										 , 'label' => $this->tb->primary->label
										 , 'value' => $this->primaryValue
										 );
		}
		else{
			if(!empty($this->keywords)){
				$filters['keywords'] = $this->keywords;
			}
			if(!empty($this->fields)){
				$filters['fields'] = $this->fields;
			}
		}
		return $filters;
	}*/
	
	private function wherestr ($andstr = '', $orstr = '', $wherestr = '') {
		if ($andstr != '') {
			$wherestr .= ' ' .  $andstr;
		}
		if ($orstr != '') {
			$wherestr = $wherestr == '' ? $orstr : $wherestr . ' AND (' . $orstr . ')';
		}
		return $wherestr;
	}
	
	public function getRows($offset = NULL, $limit = NULL) {
		if (empty($this->queryParts['from'])) {
			$from = '`' . $this->tb->name . '`';	
		}
		
		$query = 'SELECT ' . $this->queryParts['select'] . ' FROM ' . $this->queryParts['from'];
		if (!empty($this->queryParts['where'])) {
			$query .= ' WHERE ' . $this->queryParts['where'];
		}
		if (!empty($this->queryParts['groupby'])) {
			$query .= ' GROUP BY ' . $this->queryParts['groupby'];
		}
		if (!empty($this->queryParts['orderby'])) {
			$query .= ' ORDER BY ' . $this->queryParts['orderby'];
		}
		if($limit != NULL && $offset == NULL){
			$offset = '0';
		}
		if($offset != NULL){
			$query .= ' LIMIT ' . $offset;
		}
		if($limit != NULL){
			$query .= ', ' . $limit;
		}
		
		return $GLOBALS['mysql']->get($query, $this->preps);
	}
	
}
?>