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
		$result= $student_predefinebol->delete_student_predefine($predefine_participant_id);
		if($result)	{
			//delete also uploaded file if have
			
			if(file_exists("upload/".$predefine_participant_id)){

				$files = glob("upload/".$predefine_participant_id.'/*'); // get all file names
				
				foreach($files as $file){ // iterate files
				  	if(is_file($file)){
				  		unlink($file); // delete file
					}
				}
				if(is_dir("upload/".$predefine_participant_id)==1){
					rmdir("upload/".$predefine_participant_id);
				}
			}	
			$arr = array('success'=>1 , 'msg'=> 'Delete Successfully!');
		}
		else{
			$arr = array('success'=>1 , 'msg'=> 'Delete Fail!');
		}
		echo json_encode($arr);			
	}
?>