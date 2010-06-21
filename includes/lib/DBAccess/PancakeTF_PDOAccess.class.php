<?php
require_once dirname(__FILE__) . "/interfaces/PancakeTF_DBAccessI.class.php";
require_once dirname(__FILE__) . "/PancakeTF_PDOIterator.class.php";
class PancakeTF_PDOAccess implements PancakeTF_DBAccessI {
	
	/** 
	 * @var PDO a PDO instance
	 * @access private
	 * @static
	 */
	private static $pdo = null;
	
	/**
	 * @var array a list of prepared statements
	 * @access private
	 * @static
	 */
	private $statements = array();
	
	/**
	 * connecs to the database
	 * @param $type string connection driver
	 * @param $host string host name
	 * @param $dbname string database name
	 * @param $user string user name
	 * @param $password string password
	 *
	 * @access public
	 * @static
	 */
	public static function connect($type,$host,$dbname,$user,$password){
		self::$pdo = new PDO($type . ':host=' . $host . ';dbname=' . $dbname,$user,$password);
		self::$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		self::$pdo->query("SET NAMES 'utf8'");
	}
	
	/**
	 * performs a query to the database and returns the result as an array or rows (represented as arrays)
	 * 	@param string $sql    an sql query before sanitazation (question marks instead of paramater values)
	 * 	@param array  $params an array of paramaters to pass to the query
	 * @access public
	 * @return array
	 */
	public function queryArray($sql, $params = array()){
		if (isset($this->statements[$sql])){
			$st = $this->statements[$sql];
		}else{
			$this->statements[$sql] = $st = self::$pdo->prepare($sql);	
		}
		$st->execute($params);
		return $st->fetchAll(PDO::FETCH_ASSOC);
	}	
	
	/**
	 * performs a query to the database and returns the result`s 1st row as an array
	 * 	@param string $sql    an sql query before sanitazation (question marks instead of paramater values)
	 * 	@param array  $params an array of paramaters to pass to the query
	 * @access public
	 * @return array
	 */
	public function queryRow( $sql, $params = array()){
		if (isset($this->statements[$sql])){
			$st = $this->statements[$sql];
		}else{
			$this->statements[$sql] = $st = self::$pdo->prepare($sql);	
		}
		$st->execute($params);
		return $st->fetch(PDO::FETCH_ASSOC);
	}
	
	/**
	 * performs an update query to the database
	 *  @param string $sql    an sql query before sanitazation (question marks instead of paramater values)
	 * 	@param array  $params an array of paramaters to pass to the query
	 * @access public
	 * @return int number of affected rows
	 */
	public function update( $sql, $params=array()){
		if (isset($this->statements[$sql])){
			$st = $this->statements[$sql];
		}else{
			$this->statements[$sql] = $st = self::$pdo->prepare($sql);	
		}
		$st->execute($params);
		return $st->rowCount();
	}
	
	/**
	 * performs a simple cout action on a table acording to specified conditions
	 * 	@param string $table a table to count from
	 * 	@param array $condition an associative array of table fields and their required value (array('name'=>'arieh'))
	 * @access public
	 * @return int 
	 */
	public function count( $table, $conditions=array()){
		$fields = array_keys($conditions);
		$sql = "SELECT COUNT(" .( (count($fields)>0) ? "`$table`.`".$fields[0]."`" : '*' ) . ") as `c` FROM `$table` ";
		$values = array();
		if (count($fields)>0){
			$sql .= " WHERE ";
			$sep ='';
			foreach ($conditions as $field => $value){
				$sql .= "$sep `$field` = ? ";
				$sep = ' AND ';
				$values[]=$value;
			}
		}
		$row = $this->queryRow($sql,$values);

		return (int)$row['c'];
	}
	
	/**
	 * returns the last id generated by an insert query
	 * @access public
	 * @return int
	 */
	public function getLastId(){
		return self::$pdo->lastInsertId();
	}
	
	public function generateSQL($sql,$params){
		$nparams[] = str_replace('?','%s',$sql);
		foreach ($params as $param) $nparams[] = self::$pdo->quote($param);
		return call_user_func_array('sprintf',$nparams);
	}
	
	/**
	 * returns an Iterator for the query results
	 * 	@param string $sql    an sql query before sanitazation (question marks instead of paramater values)
	 * 	@param array  $params an array of paramaters to pass to the query
	 * @access public
	 * @return Iterator,Countable 
	 */
	public function queryIterator($sql,$params=array()){
		if (isset($this->statements[$sql])){
			$st = $this->statements[$sql];
			$st->execute($params);
		}else{
			$this->statements[$sql] = $st = self::$pdo->prepare($sql);	
		}
		$st->execute($params);
		return new PancakeTF_PDOIterator($st);
	}
	
	/**
	 * generates a IN-clause paramater list for sql queries, escaping the paramaters where needed
	 * 	@param array $array an array of variables to generate the IN list from
	 * @access public
	 * @return string
	 */
	public function generateInList(array $array){
		foreach ($array as &$param){
			if (!is_numeric($param)) $param = self::$pdo->quote($param);
		} 
		return implode(',',$array);
	}
}
class PancakeTF_PDOAccessException extends Exception{}