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
	$localized_result=$localizationbol->get_localization_by_pagename('card_status',1);
	while($row=$localized_result->getNext())
	{
		$localized_data[$row['localization_name']]=$row['detail'];
	}
	
	$localized_home_result=$localizationbol->get_localization_by_pagename('home',1);
	while($row=$localized_home_result->getNext())
	{
		$localized_home_data[$row['localization_name']]=$row['detail'];
	}
	
	if(isset($_GET['card_status_id']))
	{
		$card_status_id=(int)$_GET['card_status_id'];
		
		//checking this user type is used in tbl_card or not.
		$check_result = $setupbol->check_card_status_using($card_status_id);
		$aRow = $check_result->getNext();
		$count_card_records = $aRow['count_card_records'];
		if($count_card_records==0)
		{
			//delete the record in food_allergy table
			$result= $setupbol->delete_card_status($card_status_id);
			if($result)	
				$arr = array('success'=>1 , 'msg'=> $localized_data['del_card_status_msg']);
			else
				$arr = array('success'=>1 , 'msg'=> $localized_home_data['delete_fail_msg']);
		}
		else
			$arr = array('success'=>0 , 'msg'=> $localized_data['stillinuse_card_status_msg']);
		echo json_encode($arr);			
	}
?>