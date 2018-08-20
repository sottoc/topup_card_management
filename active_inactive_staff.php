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
	$localized_result=$localizationbol->get_localization_by_pagename('user',1);
	while($row=$localized_result->getNext())
	{
		$localized_data[$row['localization_name']]=$row['detail'];
	}
	
	if(isset($_GET['staff_id']))
	{
		$staff_id=(int)$_GET['staff_id'];
		$status_to_do=$_GET['status_to_do'];
		if($status_to_do=='active')
		{
			$is_active=1;
			$old_values='staff_id=>'.$staff_id.",is_active=>0";
			$msg = 'Staff is changed successfully to active status!';
		}
		else
		{
			$is_active=0;
			$old_values='staff_id=>'.$staff_id.",is_active=>1";
			$msg = 'Staff is changed successfully to in-active status!';
		}	
		$upd_result = $userbol->update_user_isactive($staff_id,$is_active,$old_values);
		if($upd_result)
			$arr = array('success'=>1 , 'msg'=> $msg);
		else
			$arr = array('success'=>0 , 'msg'=> $msg);
		echo json_encode($arr);			
	}
?>