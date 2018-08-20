<?php
	require_once ('library/reference.php');
	require_once ('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');
	
	$setupbol = new setupbol();
	$localizationbol= new localizationbol();

	//localization
	$localized_result=$localizationbol->get_localization_by_pagename('meal_status',1);
	while($row=$localized_result->getNext())
	{
		$localized_data[$row['localization_name']]=$row['detail'];
	}
	
	$localized_home_result=$localizationbol->get_localization_by_pagename('home',1);
	while($row=$localized_home_result->getNext())
	{
		$localized_home_data[$row['localization_name']]=$row['detail'];
	}
	
	if(isset($_GET['meal_status_id']))
	{
		$meal_status_id=(int)$_GET['meal_status_id'];
		
		//checking this user type is used in user table or not.
		$check_result = $setupbol->check_meal_status_using($meal_status_id);
		$aRow = $check_result->getNext();
		$count_pre_order_records = $aRow['count_pre_order_records'];
		if($count_pre_order_records==0)
		{
			//delete the record in meal_status table
			$result= $setupbol->delete_meal_status($meal_status_id);
			if($result)	
				$arr = array('success'=>1 , 'msg'=> $localized_data['del_meal_status_msg']);
			else
				$arr = array('success'=>1 , 'msg'=> $localized_home_data['delete_fail_msg']);
		}
		else
			$arr = array('success'=>0 , 'msg'=> $localized_data['stillinuse_meal_status_msg']);
		echo json_encode($arr);			
	}
?>