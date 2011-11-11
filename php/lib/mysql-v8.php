<?php
//10:23 AM 4/4/2011 added getParentTable
// 6:36 PM 1/10/2011
// 2:46 PM 12/29/2010
// 3:56 PM 9/16/2010
class mysql{

private $pdo;
private $transaction = false;
private $functions = array('NOW()');

	//constructor, connects to database
 	public function __construct($db = MYSQLDB, $user = MYSQLUSER, $pass = MYSQLPASS, $host = MYSQLHOST){
		try {
			$dsn = 'mysql:dbname=' . $db . ';host=' . $host;
			$this->pdo = new PDO($dsn, $user, $pass);
		} catch (PDOException $e) {
			die('Connection failed: ' . $e->getMessage());
		}
	}
	
	public function close(){
		$this->pdo = NULL;
	}
	
	
	public function prepareExec($sql, $values, $function = 'Custom'){
		 $sth = $this->pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		 if (!$sth){
			 $this->errorHandler($function, $sql, $this->pdo->errorInfo());
		 	return false;
		 }
		 if(!$sth->execute($values)){
			if($this->transaction){
				$this->pdo->rollBack();
				$this->transaction = false;
			}
			$this->errorHandler($function, $sql, $sth->errorInfo());
			return false;
		}
		if($this->transaction){
			$this->pdo->commit();
			$this->transaction = false;
		}
		return $sth;
	}
	
	private function errorHandler($function, $sql, $errorInfo){
		$error = $_SERVER['REQUEST_URI'] . "\nMysql->" . $function . "() \n" . $errorInfo[2] . "\n" . $sql;
		if(DEVELOPMENT == '1' || !function_exists('logError')){ //development
			echo nl2br($error);
			pr(debug_backtrace());
		}
		else{ //production
			logError($error);
		}
	}
	
	public function get($sql, $values = array()){
		$sth = $this->prepareExec($sql, $values);
		$data = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}
	
	public function getSingle($sql, $values = array()){
		$sql .= ' LIMIT 1';
		$data = $this->get($sql, $values);
		if($data){
			return $data[0];	
		}
		return array();
	}
	
	//return array of table fields, other wise false
	public function getFields($table, $full = false){
		$query = 'SHOW ' . ($full ? ' FULL ' : '' ) . ' COLUMNS FROM `' . $table . '`';
		$sth = $this->prepareExec($query, array());
		$data = $sth->fetchAll(PDO::FETCH_ASSOC);
		return empty($data) ? false : $data;
	}
	
	//return array of table fields, other wise false
	public function getIndexes($table, $unique = false){
		$query = 'SHOW INDEXES FROM `' . $table . '`' . ($unique ? ' WHERE Non_unique = 0' : '');
		$sth = $this->prepareExec($query, array());
		$data = $sth->fetchAll(PDO::FETCH_ASSOC);
		return empty($data) ? false : $data;
	}
	
	public function insert($table, $fields, $replace = false){
		
		$values = array();
		$prepValues = array();
		foreach($fields as $field => $value){
			if(is_null($value)){
				array_push($values, ' NULL ');
			}
			else if(in_array($value, $this->functions) && is_string($value)){ // is_string is needed because value may not be a string type
				array_push($values, ' ' . $value . ' ');
			}
			else{
				array_push($values, ':' . $field );
				$prepValues[':' . $field] = $value;
			}
		}
		
		$sql = ($replace == false ? 'INSERT' : 'REPLACE') . ' INTO `' . $table . '`  (`' . implode('`, `', array_keys($fields)) . '`) VALUES (' . implode(', ', $values) . ')';
		
		$this->prepareExec($sql, $prepValues);
		
		return true;
	}
	
	
	//--------------------------------------------//
	//insert many new entries into the mySql table fast
	//ex:	$valueLists = array(array("userid" => '45', "title" => 'sex and the city', "isbn" => '12345'), array("userid" => '46', "title" => 'sex and the city 2', "isbn" => '12345'));
	public function insertMany($table, $valueLists){
		
		if(!is_array($valueLists[0])){
			trigger_error('Mysql->insertMany():  2nd argument should be a double array.', E_USER_ERROR);	
		}
		
		//make sure values are safe
		$valueStrs = array();
		$prepValues = array();
		foreach($valueLists as $i => $valueList){
			$values = array();
			foreach($valueList as $field => $value){
				if(is_null($value)){
					array_push($values, ' NULL ');
				}
				else if(in_array($value, $this->functions) && is_string($value)){ // is_string is needed because value may not be a string type
					array_push($values, ' ' . $value . ' ');		
				}
				else{
					array_push($values, ':' . $field . $i);
					$prepValues[':' .  $field . $i] = $value;
				}
			}
			
			array_push($valueStrs, '(' . implode(',', $values) . ')' );
		}
		
		$sql = 'INSERT INTO `' . $table . '` (`' . implode('`, `', array_keys($valueLists[0])) . '`) VALUES ' . implode(', ', $valueStrs);

		//handle rollwback in case if anything goes wrong with insert
		$this->pdo->beginTransaction();
		$this->transaction = true;
		return $this->prepareExec($sql, $prepValues); //will handle roll back/commit
	}
	
	//$whereSql and $whereValues are used as prepared statements
	public function update($table, $fields, $whereSql = false, $whereValues = false, $operators = false){
		$prepValues = array();
		$sql = 'UPDATE `' . $table . '` SET ';
		$sets = array();
		foreach($fields as $field => $value){
			if($operators[$field] != ''){
				$set = '`' . $field . '` = `' . $field . '` ' . $operators[$field] . ' ' . $value;
			}
			else{
				$set = '`' . $field . '` = ';
				if(is_null($value)){
					$set .=	'NULL';	
				}
				else if(in_array($value, $this->functions) && is_string($value)){ // is_string is needed because value may not be a string type
					$set .=	$value;
				}
				else{
					//prevent using same key has $whereValues
					$i = 0;
					$key = ':' . $field. '_' . $i;
					while(isset($whereValues[$key])){
						$i++;
						$key = ':' . $field. '_' . $i;
					}
					$set .= $key;
					$prepValues[$key] = $value;
				}
			}
			array_push($sets, $set);
		}
		
		$sql .= implode($sets, ', ');
		
		if($whereSql){
			if(strpos($whereSql, ':') === false){
				trigger_error('Mysql->update():  3rd argument should use prepared statements.', E_USER_ERROR);
			}
			if(!is_array($whereValues)){
				trigger_error('Mysql->update():  4th argument should be an associative array with prepared values for the prepared statements.', E_USER_ERROR);	
			}
			$prepValues = array_merge($prepValues, $whereValues);
			$sql .= ' WHERE ' . $whereSql;
		}
		
		return $this->prepareExec($sql, $prepValues);
	}
	
	
	public function delete($table, $whereSql, $prepValues){
		if(strpos($whereSql, ':') === false){
			trigger_error('Mysql->delete():  2nd argument should use prepared statements.', E_USER_ERROR);
		}
		
		$sql =  'DELETE FROM `' . $table . '` WHERE ' . $whereSql;
		return $this->prepareExec($sql, $prepValues);
	}
	
	public function syncTables($tableA, $tableB, $wherestr = false){
		$sql = 'UPDATE `' . $tableA . '`, `' . $tableB . '` SET ';
		$fields = $this->getFields($tableA);
		
		$sets = array();
		foreach($fields as $field){
			array_push($sets, $tableA . '.' . $field['Field'] . ' = ' . $tableB . '.' . $field['Field']);
		}
		
		$sql .= implode(', ', $sets) . ' WHERE ';
		if($wherestr){
			$sql .= $wherestr;
		}
		else{
			$sql .= $tableA . '.' . $fields[0]['Field'] . ' = ' . $tableB . '.'.$fields[0]['Field'];
		}
		return $this->prepareExec($sql, array());
	}
	
	//make sure all fields are valid for table
	public function validateFields($table, $fields, &$errorstr = ''){
		$existingFields = $this->getFields($table);
		if(!$existingFields){
			trigger_error('validateFields():  error, no result for table: '.$table, E_USER_ERROR);
		}
		if(!is_array($fields)){
			trigger_error('validateFields():  expecting $fields argument to be an array ', E_USER_ERROR);	
		}
		$existingFields = transpose($existingFields);
		foreach($fields as $field){
			if(!in_array($field, $existingFields['Field'])){
				$errorstr .= ', mysql->validateFields(): field =>'.$field;
			}
		}
		return $errorstr == '' ? true : false;
	}

	public function tableExists($table){
		$query = 'SHOW TABLES WHERE Tables_in_' . MYSQLDB . ' = :table';
		$values = array(':table' => $table
						);
		$tableExists = $this->get($query, $values);
		return !empty($tableExists);
	}
	
	public function lastInsertId(){
		return $this->pdo->lastInsertId();	
	}
	
	//check if a field name is the primary key of a table
	public function isForeignKey($field, &$foreignTable = NULL, &$foreignIndexes = NULL){
		do {
			$foreignTable = substr($field, 0, count($field) - 3); // removes 'id'
			$exists = $this->tableExists($foreignTable); //check if table exists
			if($exists){
				$foreignIndexes = $this->getIndexes($foreignTable);
				if(!$foreignIndexes){
					return false;	
				}
				//check if field is set as primary
				foreach($foreignIndexes as $foreignIndex){
					if($foreignIndex['Column_name'] == $field && $foreignIndex['Key_name'] == 'PRIMARY'){
						return true;
					}
				}
			}
			//check for extended nameing convention, ie. refered_userid
			$extend = strpos($foreignTable, '_');
			if($extend !== false){
				$field = substr($field, $extend  + 1);
			}
		} while($extend !== false && !$exists);
		
		return false;
	}
	
	public function getParentTable($tableName){
		while(1){
			$delimiterPos = strrpos($tableName, '_');
			if($delimiterPos === false){
				return NULL;	
			}
			$tableName = substr($tableName, 0, $delimiterPos);
			if($this->tableExists($tableName)){
				return $tableName;
			}
		}
	}
	
	public function getEnumValues($table, $field) {
		if (empty($table) || empty($field)) {
			trigger_error('getEnumValues(): $table or $field is empty.', E_USER_ERROR);
		}
		$enum_array = array();
		
		//cant use pdo on godaddy server for unknown reason
		$query = 'SHOW COLUMNS FROM `' . $table . '` LIKE "' . $field . '"';
		$values = array();
		
		$result = $this->get($query, $values);
		$row = array_values($result[0]);
		// Mysql delimits ' with ' ?
		// Ex. World's => World''s
		$row[1] = str_replace("''", "'", $row[1]);
		
		$row[1] = rtrim(rtrim(ltrim($row[1], "enum('"), ")"), "'");
		
		return explode("','", $row[1]);
	}
}

?>