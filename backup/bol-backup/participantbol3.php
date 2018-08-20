<?php
	class participantbol
	{
		function select_participant_list($offset,$rpage,$sorting,$cri_arr,$organizer_id)
		{
			$participantdal = new participantdal();
			$result = $participantdal->select_participant_list($offset,$rpage,$sorting,$cri_arr,$organizer_id);
			return $result;
		}
		function select_allergy_foods($participant_id)
		{
			$participantdal = new participantdal();
			$result = $participantdal->select_allergy_foods($participant_id);
			return $result;
		}
		function check_duplicate_participant_saving($participant_enroll_no,$id=0)
		{
			$participantdal = new participantdal();
			$result = $participantdal->check_duplicate_participant_saving($participant_enroll_no,$id);
			return $result;
		}
		function save_participant($participantinfo)
		{
			$participantdal = new participantdal();
			$result = $participantdal->save_participant($participantinfo);
			return $result;
		}
		function save_participant_food_allergy($participant_food_allergyinfo)
		{
			$participantdal = new participantdal();
			$result = $participantdal->save_participant_food_allergy($participant_food_allergyinfo);
			return $result;
		}
		
		function select_predefined_studentIDs($participant_name, $organizer_name)
		{
			$participantdal = new participantdal();
			$result = $participantdal->select_predefined_studentIDs($participant_name, $organizer_name);
			return $result;
		}

		function delete_participant($participant_id)
		{
			$participantdal = new participantdal();
			$result = $participantdal->delete_participant($participant_id);
			return $result;
		}
		
		function check_using_inPreorderTable($participant_id)
		{
			$participantdal = new participantdal();
			$result = $participantdal->check_using_inPreorderTable($participant_id);
			return $result;
		}
		
		function check_using_inTransactionsTable($participant_id)
		{
			$participantdal = new participantdal();
			$result = $participantdal->check_using_inTransactionsTable($participant_id);
			return $result;
		}

		function select_participant_byid($id)
		{
			$participantdal = new participantdal();
			$result = $participantdal -> select_participant_byid($id);
			return $result;
		}

		function select_allergyfoods_byid($id)
		{
			$participantdal = new participantdal();
			$result = $participantdal -> select_allergyfoods_byid($id);
			return $result;
		}

		function edit_participant($participantinfo,$edt_hid_participant_values) 
		{
			$participantdal = new participantdal();
			$result = $participantdal -> edit_participant($participantinfo,$edt_hid_participant_values);
			return $result;
		}

		function delete_participant_food_allergy($participant_id)
		{
			$participantdal=new participantdal();
			$result=$participantdal->delete_participant_food_allergy($participant_id);
			return $result;
		}

		function get_organizer_id($user_id)
		{
			$participantdal=new participantdal();
			$result=$participantdal->get_organizer_id($user_id);
			return $result;
		}
	}
?>