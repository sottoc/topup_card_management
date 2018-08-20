<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');
	$reportbol = new reportbol();	
	// update meal check status by id
	
	if(isset($_POST['pre_order_id']))
	{
		$pre_order_id=$_POST['pre_order_id'];		
		$result=$reportbol->checkmealstatustofinish($pre_order_id);
		if($result)
		{
			$json_retrun_arr['success'] = 1;
			$json_retrun_arr['message'] = '* Successfully Updated!';	
		}
		else
		{
			$json_retrun_arr['success'] = 0;
			$json_retrun_arr['message'] = '* Please Check your data!';	
		}
		
		echo json_encode($json_retrun_arr);
		exit();
	}	

	// update all meal status id by all check
	if ( isset($_POST["pre_order_id_str"]) )
	{
		
		$pre_order_id_str = clean($_POST["pre_order_id_str"]);
		$preorder_arr = explode(", ", $pre_order_id_str);
		
		//print_r($preorder_arr);
		//exit();
		
		foreach($preorder_arr as $pre_order_id)
		{
			$result=$reportbol->checkmealstatustofinish($pre_order_id);			
			if($result)
			{
				$json_retrun_arr['success'] = 1;
				$json_retrun_arr['message'] = '* Successfully Updated!';	
			}
			else
			{
				$json_retrun_arr['success'] = 0;
				$json_retrun_arr['message'] = '* Please Check your data!';	
			}
		}
		//print_r($json_retrun_arr);
		//exit();
				
		echo json_encode($json_retrun_arr);
		exit();
	}
?>