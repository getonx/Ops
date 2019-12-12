<?php

Class DbOps{

private $db_conn;

public function __construct($pdo){
	$this->db_conn = $pdo;
}
	

public function getBaseData($table, $values = ['*']){
	$sql = 'SELECT ';
	foreach ($values as $val){
		$sql .= $val.', ';
	}
	$sql = rtrim($sql, ', ');
	$sql .=' FROM '.$table;
	$query = $this->db_conn->prepare($sql);
	$query->execute();
	$result = $query->fetchAll(PDO::FETCH_ASSOC);
	return $result;
}

public function getSpecificData($table, $values = ['*'], $where){//run a query with $where statement
	$sql = 'SELECT ';
	foreach ($values as $val){
		$sql .= $val.', ';
	}
	$sql = rtrim($sql, ', ');
	$sql .=' FROM '.$table;
	$sql .=' WHERE '.$where;
	$query = $this->db_conn->prepare($sql);
	$query->execute();
	$result = $query->fetchAll(PDO::FETCH_ASSOC);
	return $result;
}

public function updateData($table, $column, $load, $id){
	$sql = 'UPDATE '.$table.' SET '.$column.' = :load WHERE id = :id';
	$query = $this->db_conn->prepare($sql);
	$query->bindParam(':load', $load);
	$query->bindParam(':id', $id);
	$query->execute();
}
public function checkIfExists($table, $load, $ref){
	$sql ='SELECT * FROM '.$table.' WHERE '.$ref.' = :load';
	$query = $this->db_conn->prepare($sql);
	$query->bindParam(':load', $load);
	$query->execute();
	$result = $query->fetchAll(PDO::FETCH_ASSOC);
	if (!empty($result)){
		return true;
	}
	else{
		return false;
	}
	
}

}
?>