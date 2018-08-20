<?php
	require_once ('library/reference.php');
	require_once ('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');
	
	$studentcardbol = new studentcardbol();
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
	
	if(isset($_GET['card_id']))
	{
		$card_id=(int)$_GET['card_id'];
		
		//checking this user type is used in tbl_card or not.
		$check_result = $studentcardbol->check_card_using($card_id);
		$aRow = $check_result->getNext();
		$count_card_records = $aRow['count_card_records'];
		if($count_card_records==0)
		{
			//delete the record in food_allergy table
			$result= $studentcardbol->delete_card($card_id);
			if($result)	
				$arr = array('success'=>1 , 'msg'=> 'Deleted Successfully');
			else
				$arr = array('success'=>1 , 'msg'=> 'Delete Fail');
		}
		else
			$arr = array('success'=>0 , 'msg'=> 'This card is still in use. Cannot delete.');
		echo json_encode($arr);			
	}
?>