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
	
	if(isset($_GET['category_type_id']))
	{
		$category_type_id=(int)$_GET['category_type_id'];
		
		//checking this category_type is used in  tbl_pre_orders or not.
			$check_result_1 = $setupbol->check_category_type_using_in_preorders($category_type_id);
			$aRow1 = $check_result_1->getNext();
			$count_pre_order_records = $aRow1['count_pre_order_records'];
			
		//checking this category_type is used in  tbl_student_canteen_orders or not.
			$check_result_2 = $setupbol->check_category_type_using_in_participant_canteenorders($category_type_id);
			$aRow2 = $check_result_2->getNext();
			$count_participant_canteen_order_records = $aRow2['count_participant_canteen_order_records'];
		
		if($count_pre_order_records==0 && $count_participant_canteen_order_records==0)
		{
			//delete the record in category_type table
			$result= $setupbol->delete_category_type($category_type_id);
			if($result)	
				$arr = array('success'=>1 , 'msg'=> "Deleted Successfully");
			else
				$arr = array('success'=>1 , 'msg'=> "Deleted Fail");
		}
		else
			$arr = array('success'=>0 , 'msg'=> "This category type is still in use!.");
		echo json_encode($arr);			
	}
?>