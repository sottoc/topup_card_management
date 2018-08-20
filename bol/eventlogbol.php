<?php
class eventlogbol{
	
	function save_eventlog($action_name,$action_table,$changes_mode,$original_mode)
	{
		//get ip address
		$ipaddress = getenv('REMOTE_ADDR');
		
		$action_user_id='1';
		//session user id
		
		$eventlogdal=new eventlogdal();
		$changes_mode_str='';
		$original_mode_str='';
		if(is_array($changes_mode))
		{
			foreach($changes_mode as $key=>$value)
			{
				if($changes_mode_str!='')
				{
					$changes_mode_str = $changes_mode_str.','.$key."=>".$value;
				}
				else
					$changes_mode_str = $key."=>".$value;
			}
		}
		
		//for update
		if($original_mode!='')
			$original_mode_str=$original_mode;
			
		$result=$eventlogdal->save_eventlog($action_name,$action_table,$changes_mode_str,$original_mode_str,$action_user_id,$ipaddress);
		return $result;
	}


}
?>