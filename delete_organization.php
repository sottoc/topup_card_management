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
	$localized_result=$localizationbol->get_localization_by_pagename('organization',1);
	while($row=$localized_result->getNext())
	{
		$localized_data[$row['localization_name']]=$row['detail'];
	}
	
	$localized_home_result=$localizationbol->get_localization_by_pagename('home',1);
	while($row=$localized_home_result->getNext())
	{
		$localized_home_data[$row['localization_name']]=$row['detail'];
	}
	
	if(isset($_GET['org_id']))
	{
		$org_id=(int)$_GET['org_id'];
		
		//checking this school is used in student table or not.
		$check_result = $setupbol->check_org_using($org_id);
		$aRow = $check_result->getNext();
		$count_org_records = $aRow['count_org_records'];
		if($count_org_records==0)
		{
			//delete the record in school table
			$result= $setupbol->delete_org($org_id);
			if($result)	
				$arr = array('success'=>1 , 'msg'=> $localized_data['del_org_msg']);
			else
				$arr = array('success'=>1 , 'msg'=> $localized_home_data['delete_fail_msg']);
		}
		else
			$arr = array('success'=>0 , 'msg'=> $localized_data['stillinuse_org_msg']);
		echo json_encode($arr);			
	}
?>