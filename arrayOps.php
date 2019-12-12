<?php

function array_to_db_table(array $array, $tableName, $connection){
	$query = 'CREATE TABLE `'.$tableName.'` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT, ';
 foreach ($array as $key=>$value){
	 $query  .= $key.' TEXT,';
 }
 $query = rtrim($query, ',');
 $query .= ' , PRIMARY KEY (`id`)';
 $query .=')';
 
 $pdo = $connection->prepare($query);
 $pdo->execute();
}

function make_array(){
	$x = array();
	return $x;
}

function echo_array($array){
	foreach ($array as $key=>$value){
		if(!is_array($value)){
		echo $key.' = '.$value;
		echo '</br>';
		}
		else{
			echo_array($value);
			echo '</br>';
			echo '</br>';
		}
	}
}




?>