<?php
class eventlogdal{

	function save_eventlog($action_name,$action_table,$changes_mode_str,$original_mode_str,$action_user_id,$ipaddress)
	{
		$query="INSERT INTO tbl_eventlog (action_name, action_table, changes_mode, original_mode, action_user_id, ip_address, action_datetime) 
				VALUES ( :action_name, :action_table, :changes_mode, :original_mode, :action_user_id, :ip_address, :action_datetime)";
		$param = array(
		':action_name' => $action_name,
		':action_table' =>$action_table,
		':changes_mode' =>$changes_mode_str,
		':original_mode' =>$original_mode_str,
		':action_user_id' => (int)$action_user_id,
		':ip_address' =>$ipaddress,
		':action_datetime' => date("Y-m-d H:i:s")
		);
		
		$result = execute_query($query, $param) or die ("save eventlog query fail.");	
		return $result;
	}
}
?>