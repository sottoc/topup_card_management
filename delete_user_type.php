<?php
	require_once ('library/reference.php');
	require_once ('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');
	
	$userbol = new userbol();
	$localizationbol= new localizationbol();

	//localization
	$localized_result=$localizationbol->get_localization_by_pagename('user_type',1);
	while($row=$localized_result->getNext())
	{
		$localized_data[$row['localization_name']]=$row['detail'];
	}
	
	$localized_home_result=$localizationbol->get_localization_by_pagename('home',1);
	while($row=$localized_home_result->getNext())
	{
		$localized_home_data[$row['localization_name']]=$row['detail'];
	}
	
	if(isset($_GET['user_type_id']))
	{
		$user_type_id=(int)$_GET['user_type_id'];
		
		//checking this user type is used in user table or not.
		$check_result = $userbol->check_user_type_using($user_type_id);
		$aRow = $check_result->getNext();
		$count_user_records = $aRow['count_user_records'];
		if($count_user_records==0)
		{
			//delete the record in user_type table
			$result= $userbol->delete_user_type($user_type_id);
			if($result)	
				$arr = array('success'=>1 , 'msg'=> $localized_data['del_user_type_msg']);
			else
				$arr = array('success'=>1 , 'msg'=> $localized_home_data['delete_fail_msg']);
		}
		else
			$arr = array('success'=>0 , 'msg'=> $localized_data['stillinuse_user_type_msg']);
		echo json_encode($arr);			
	}
?>