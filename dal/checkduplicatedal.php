<?php
class checkduplicatedal{
		function check_duplicate_field($table, $field_name, $condition_arr)
		{
			$condition = '';
			foreach($condition_arr as $key => $value){
				if($key !='' ){
					$condition.= $key."='".$value."' AND ";
				}
			}

			$query_condition = '';
			if($condition != ''){
				$condition = substr($condition,0,strlen($condition)-4);
				$query_condition = " WHERE ".$condition;
			}

			$query = "SELECT count($field_name) as field_count FROM $table $query_condition ";
			$result = execute_query($query) or die ("check_duplicate_field query fail.");
			return new readonlyresultset($result);
		}
		
}
?>