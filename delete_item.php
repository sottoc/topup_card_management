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
	$localized_result=$localizationbol->get_localization_by_pagename('items',1);
	while($row=$localized_result->getNext())
	{
		$localized_data[$row['localization_name']]=$row['detail'];
	}
	
	$localized_home_result=$localizationbol->get_localization_by_pagename('home',1);
	while($row=$localized_home_result->getNext())
	{
		$localized_home_data[$row['localization_name']]=$row['detail'];
	}
	
	if(isset($_GET['item_id']))
	{
		$item_id=(int)$_GET['item_id'];
		
		//checking this item_id is used in pre_order table or not.
		$check_result1 = $setupbol->check_item_using_in_preorder($item_id);
		$aRow1 = $check_result1->getNext();
		$count_preorder_records = $aRow1['count_preorder_records'];
				
		//checking this item_id is used in tbl_participant_canteen_orders table or not.
		$check_result2 = $setupbol->check_item_using_in_canteenorder($item_id);
		$aRow2 = $check_result2->getNext();
		$count_canteen_order_records = $aRow2['count_canteen_order_records'];
		
		if($count_preorder_records==0 && $count_canteen_order_records==0)
		{
			//delete the record in item table
			$result= $setupbol->delete_item($item_id);
			if($result)
			{	
				$jpg_ext = 'ItemImages/'.$item_id.".jpg";
				$png_ext = 'ItemImages/'.$item_id.".png";
				$jpeg_ext = 'ItemImages/'.$item_id.".jpeg";
				$gif_ext = 'ItemImages/'.$item_id.".gif";
				if(file_exists($jpg_ext))
					unlink($jpg_ext);
				if(file_exists($png_ext))
					unlink($png_ext);
				if(file_exists($jpeg_ext))
					unlink($jpeg_ext);
				if(file_exists($gif_ext))
					unlink($gif_ext);
				$arr = array('success'=>1 , 'msg'=> $localized_data['del_item_msg']);
			}
			else
				$arr = array('success'=>1 , 'msg'=> $localized_home_data['delete_fail_msg']);
		}
		else
			$arr = array('success'=>0 , 'msg'=> $localized_data['stillinuse_item_msg']);
		
		echo json_encode($arr);			
	}
?>