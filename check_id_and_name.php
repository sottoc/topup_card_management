<?php
	require_once ('library/reference.php');
	require_once ('autoload.php');
	session_start ();	
	require_once('userauth.php');
	
	if(isset($_GET['name']) && isset($_GET['id']))
	{
		$id = $_GET['id'];	
		$name = $_GET['name'];		
		$participantbol = new participantbol();
		
		$chk_using = 1;
		$chk_result = $participantbol->checkNameAndId($name,$id);
		$data = $chk_result->getNext();
		if($data == null){
			$arr = array('mes'=> 'invalid');		
		}
		else{

			$food_allergy_array = array();

			if($data['student_allergy_flag'] == 1){
				//get allergy foods
				$fa_result = $participantbol->select_allergyfoods_by_predefinedid($data['predefine_participant_id']);
				if($fa_result->rowCount()!= 0){
					while($faRow = $fa_result->getNext())
					{
						$food_allergy_array[]= $faRow['food_allergy_id'];
					}
				}
			}
			
			$arr = array(
				'mes'=> 'valid',
				'predefine_participant_id' => $data['predefine_participant_id'],
				'predefine_participant_name' => $data['predefine_participant_name'],
				'predefine_participant_enroll_no' => $data['predefine_participant_enroll_no'],
				'student_class' => $data['student_class'],
				'finger_print_number' => $data['finger_print_number'],
				'predefine_parent_name' => $data['predefine_parent_name'],
				'student_allergy_flag' => $data['student_allergy_flag'],
				'others_allergy_food_description' => $data['others_allergy_food_description'],
				'student_description' => $data['student_description'],
				'allow_canteen_order' => $data['allow_canteen_order'],
				'gender' => $data['gender'],
				'organization' => $data['organization'],
				'upload_file' => $data['upload_file'],
				'allergy_food_array'=>$food_allergy_array);
		}
		/*if($chk_result == 0){ //not using in preorder table
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
		}*/
		echo json_encode($arr);	
	}
?>