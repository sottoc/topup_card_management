<?php
	class participantbol
	{
		function select_participant_list($offset,$rpage,$sorting,$cri_arr)
		{
			$participantdal = new participantdal();
			$result = $participantdal->select_participant_list($offset,$rpage,$sorting,$cri_arr);
			return $result;
		}
		function select_allergy_foods($participant_id)
		{
			$participantdal = new participantdal();
			$result = $participantdal->select_allergy_foods($participant_id);
			return $result;
		}
		function check_duplicate_participant_saving($participant_enroll_no)
		{
			$participantdal = new participantdal();
			$result = $participantdal->check_duplicate_participant_saving($participant_enroll_no);
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
	}
?>