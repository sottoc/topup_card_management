<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');
	
	$login_user_id = $_SESSION ['login_user_id'];
	
	if(isset($_POST['loginpassword']))
	{
		$password=$_POST['loginpassword'];
		$changepasswordbol = new changepasswordbol();			
		$result=$changepasswordbol->checkuseridandpassword($password,$login_user_id);
		$row = $result->getNext();
		$count_user = $row['count_record'];
		if($count_user==1)
		{
			$json_retrun_arr['success'] = 1;
			$json_retrun_arr['warning'] = '* User Name and Password match!';	
		}
		else
		{
			$json_retrun_arr['success'] = 0;
			$json_retrun_arr['warning'] = '* User Name and Password does not match!';	
		}
		
		//$json_retrun_arr['id'] = $barcodeno;
		echo json_encode($json_retrun_arr);
	}		
?>