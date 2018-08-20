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
	$localized_result=$localizationbol->get_localization_by_pagename('food_allergy',1);
	while($row=$localized_result->getNext())
	{
		$localized_data[$row['localization_name']]=$row['detail'];
	}
	
	$localized_home_result=$localizationbol->get_localization_by_pagename('home',1);
	while($row=$localized_home_result->getNext())
	{
		$localized_home_data[$row['localization_name']]=$row['detail'];
	}
	
	if(isset($_GET['food_allergy_id']))
	{
		$food_allergy_id=(int)$_GET['food_allergy_id'];
		
		//checking this user type is used in tbl_student_food_allergy or not.
		$check_result = $setupbol->check_food_allergy_using($food_allergy_id);
		$aRow = $check_result->getNext();
		$count_food_allergy_records = $aRow['count_food_allergy_records'];
		if($count_food_allergy_records==0)
		{
			//delete the record in food_allergy table
			$result= $setupbol->delete_food_allergy($food_allergy_id);
			if($result)	
				$arr = array('success'=>1 , 'msg'=> $localized_data['del_food_allergy_msg']);
			else
				$arr = array('success'=>1 , 'msg'=> $localized_home_data['delete_fail_msg']);
		}
		else
			$arr = array('success'=>0 , 'msg'=> $localized_data['stillinuse_food_allergy_msg']);
		echo json_encode($arr);			
	}
?>