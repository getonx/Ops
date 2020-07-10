<?php
class Db{
	private $db_host = 'localhost';
	private $db_user = 'test';
	private $db_pass = 'test';
	private $db_name = 'test';
	public $db_conn;
	
	
	function __construct(){
		try {
			$this->db_conn = new PDO("mysql:host={$this->db_host};dbname={$this->db_name}", $this->db_user, $this->db_pass);
			$this->db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			array_push($errors, $e->getMessage());
		}	
		
	}
	public function insert(array $data, $table){ //runs a simple INSERT query, $data argument is an associative array($data) with keys named as db table($table) columns
		try {
			
            $sql = "INSERT INTO ".$table." (";    
			foreach ($data as $key=>$value){
				$sql .= $key.', '; 
			}
			$sql = rtrim($sql, ', ');
			$sql .= ') VALUES (';
			foreach ($data as $key=>$value){
				$sql .= ':'.$key.', '; 
			}
			$sql = rtrim($sql, ', ');
			$sql .= ')';
			
            $query = $this->db_conn->prepare($sql);
			foreach ($data as $key=>&$value){
				 $query->bindParam(':'.$key, $value);
			};
           $query->execute();
        } catch (PDOException $e) {
            array_push($errors, $e->getMessage());
        }
	}
	
	public function selectAll(array $data, $table, string ...$sql_query){ 
	/*
	runs a simple SELECT * FROM query with single or multiple WHERE params, 
	$data argument is an associative array($data) with keys named as db table($table) columns
	if last elemnt of $data array is set to 'limit=>int' a "LIMIT int" clause will be added at the end of statement
	this function can run any other query by supplying $sql_query arg in form of an SQL statement template
	*/

	try{
			$sql_query = $sql_query[0];
			if(array_key_last($data) == 'limit'){
				$limit = $data['limit'];
				array_pop($data);
			}	
		 
		 if(!isset($sql_query)){
		 
		 $sql = "SELECT * FROM ".$table." WHERE ";  
		 if(count($data>1)){
		 foreach ($data as $key=>$value){
				$sql .= $key.' = :'.$key.' AND '; 
				
		 }
		 $sql = substr($sql, 0, -5);
		 }
		 else{
			 $sql .= array_key_first($data).' = :'.array_key_first($data);
		 }
		if(isset($limit)){
			$sql .= ' LIMIT '.$limit;
		}
		 }
		 else{
			 $sql = $sql_query;
		 }
		 $query = $this->db_conn->prepare($sql);
		 foreach ($data as $key=>&$value){
			$query->bindParam(':'.$key, $value);
		 }
		  $query->execute();
			
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		//echo var_dump($result);
		return $result;
        } catch (PDOException $e) {
            array_push($errors, $e->getMessage());
        }
				 
		 
	}
	
	
}



?>