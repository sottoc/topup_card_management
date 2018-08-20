<?php
	require_once ('library/reference.php');
	require_once ('autoload.php');
	session_start ();	
	require_once('userauth.php');
	
	if(isset($_GET['participant_id']))
	{
		$participant_id = (int)$_GET['participant_id'];		
		$participantbol = new participantbol();
		//before delete, check this participant id using in transactions and preorder tables
		$chk_using = 1;
		$chk_result = $participantbol->check_using_inPreorderTable($participant_id);
		if($chk_result == 0){ //not using in preorder table
			$chk_result = $participantbol->check_using_inTransactionsTable($participant_id);
			if($chk_result == 0){ //not using in transactions table
				$chk_using = 0;
			}
		}

		if($chk_using == 1){
			$arr = array('success'=>1 , 'mes'=> 'This student can not be delete.');	
		}
		else{
			//Delete
			$result= $participantbol->delete_participant($participant_id);
			if($result)	
				$arr = array('success'=>1 , 'mes'=> 'Student deleted successfully!');			
			else
				$arr = array('success'=>0 , 'mes'=> 'Failed in delete');	
		}
		echo json_encode($arr);	
	}
?>