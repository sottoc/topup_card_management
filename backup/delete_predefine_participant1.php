<?php
require_once ('library/reference.php');
	require_once ('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');
	
	$student_predefinebol = new student_predefinebol();
	
	if(isset($_GET['predefine_participant_id']))
	{
		$predefine_participant_id=(int)$_GET['predefine_participant_id'];	
		//delete the record in predefine participant
		
		$result= $student_predefinebol->delete_student_predefine($predefine_participant_id);
		if($result)	
			$arr = array('success'=>1 , 'msg'=> 'Delete Successfully!');
		else
			$arr = array('success'=>1 , 'msg'=> 'Delete Fail!');
		echo json_encode($arr);			
	}
?>