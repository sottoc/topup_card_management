<?php
require_once ('library/reference.php');
	require_once ('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');
	
	$student_predefinebol = new student_predefinebol();
	
	if(isset($_GET['predefine_participant_id']) && isset($_GET['student_enroll_no']))
	{
		$predefine_participant_id=(int)$_GET['predefine_participant_id'];	
		$student_enroll_no = $_GET['student_enroll_no'];
		
		$result= $student_predefinebol->delete_student_predefine($predefine_participant_id);
		if($result)	{
			//delete also uploaded file if have
			if(is_dir("upload/".$student_enroll_no)==1){
				rmdir("upload/".$student_enroll_no);
			}
			/*if(file_exists("upload/".$student_enroll_no)){
				$files = glob("upload/".$student_enroll_no.'*'); // get all file names
				
				foreach($files as $file){ // iterate files
				  if(is_file($file)){
				  	unlink($file); // delete file
				  }
				    
				}
			}*/	
				

			$arr = array('success'=>1 , 'msg'=> 'Delete Successfully!');
		}
		else{
			$arr = array('success'=>1 , 'msg'=> 'Delete Fail!');
		}
		echo json_encode($arr);			
	}
?>