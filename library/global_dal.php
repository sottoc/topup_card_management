<?php

	function last_instert_id()
	{
		global $conn;
		return $conn->lastInsertId();	
	}
	
	function execute_non_query($query, $param=array())		//return true on success query and false on fail query
	{
		global $conn;	
		$result=$conn->prepare($query);
		//var_dump($result);
		if(!$result)
		{
			$err_arr = $conn->errorInfo();
			die($err_arr[2] . " " . $query);
		}
		//echo count($param);exit();
		if(count($param)>0)
			return $result->execute($param);
		else
			return $result->execute();
	}
	
	function execute_scalar_query($query, $param=array())		//return true on success query and false on fail query
	{
		global $conn;
		$result=$conn->prepare($query);
		if(!$result)
		{
			$err_arr = $conn->errorInfo();
			die($err_arr[2] . " " . $query);
		}
		
		if(count($param)>0)
			$result->execute($param);
		else
			$result->execute();
		$result->bindColumn(1, $retvalue);
		$result->fetch();
		return $retvalue;
	}
	
	function execute_query($query, $param=array())	//return result on sucess query, die on fail query
	{
		global $conn;	
		$result=$conn->prepare($query);
		if(!$result)
		{
			$err_arr = $conn->errorInfo();
			die($err_arr[2] . " " . $query);
		}
		
		if(count($param)>0){
			$result->execute($param) or die(print_r($conn->errorInfo(), true));
		}
		else{
			$result->execute()  or die(print_r($conn->errorInfo(), true));
		}
		//echo 'enter dal '.$result;exit();
		return $result;
	}
	
	function debugPDO($raw_sql, $parameters)
	{
		$keys = array();
		$values = $parameters;
		foreach ($parameters as $key => $value) 
		{
			// check if named parameters (':param') or anonymous parameters ('?') are used
			if (is_string($key)) 
			{
				if (substr($key, 0, 1) === ':')
					$keys[] = '/'.$key.'/';
				else
					$keys[] = '/:'.$key.'/';
			} 
			else 
			{
				$keys[] = '/[?]/';
			}
			// bring parameter into human-readable format
			if (is_string($value)) {
				$values[$key] = "'" . $value . "'";
			} elseif (is_array($value)) {
				$values[$key] = implode(',', $value);
			} elseif (is_null($value)) {
				$values[$key] = 'NULL';
			}
		}
		$raw_sql = preg_replace($keys, $values, $raw_sql, 1, $count);
		return $raw_sql;
	}
?>