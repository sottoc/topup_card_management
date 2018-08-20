<?php

class checkduplicatebol{

		function check_duplicate_field($table, $field_name, $condition_arr)
		{
			$checkduplicatedal=new checkduplicatedal();
			$result=$checkduplicatedal->check_duplicate_field($table, $field_name, $condition_arr);
			return $result;
		}
}
?>