<?php
require_once ('library/reference.php');
	require_once ('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');
	
	$checkduplicatebol = new checkduplicatebol();
	
	if(isset($_GET['table']) && isset($_GET['field_name']) )
	{
		$table = $_GET['table'];	
		$field_name = $_GET['field_name'];
		$condition_arr = array();
		if(isset($_GET['condition_str'])){
			$condition_arr = json_decode($_GET['condition_str']);
		}
		
		$result= $checkduplicatebol->check_duplicate_field($table,$field_name,$condition_arr);
		$row = $result->getNext();
		$field_count = $row['field_count'];
		echo $field_count;			
	}
?>