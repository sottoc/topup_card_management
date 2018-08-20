<?php
	class participantdal
	{
		function select_participant_list($offset=0,$rpage=0,$sorting='',$cri_arr)
		{
			$cri_str = $cri_arr[0];
			$param = $cri_arr[1];
			$qry = "SELECT SQL_CALC_FOUND_ROWS g.gender_name,p.*
					FROM tbl_participant p
					INNER JOIN tbl_gender g ON p.participant_gender_id = g.gender_id";
			$qry .= $cri_str ;
			$qry .= $sorting;
			if ($rpage != 0)
				$qry .= " LIMIT $offset, $rpage";					
			$result = execute_query($qry, $param) or die('select_participant_list query fails');
			return new readonlyresultset($result);
		}
		function select_allergy_foods($id='')
		{
			$qry = "SELECT fa.food_allergy_name
					FROM tbl_participant_food_allergy pfa 
					INNER JOIN tbl_food_allergy fa ON fa.food_allergy_id=pfa.food_allergy_id
					WHERE pfa.participant_id=  $id";
			$result = execute_query($qry) or die('select_allergy_foods query fails');
			return new readonlyresultset($result);
		}
		function check_duplicate_participant_saving($participant_enroll_no)
		{
			$query="SELECT count(participant_id) as count_duplicate_saving FROM tbl_participant WHERE participant_enroll_no=:participant_enroll_no";
			$result = execute_query($query,array(':participant_enroll_no'=>$participant_enroll_no)) or die ("check_duplicate_participant_saving query fail.");
			return new readonlyresultset($result);
		}
		function save_participant($participantinfo)
		{
			$participant_name= $participantinfo->get_participant_name();
			$participant_enroll_no= $participantinfo->get_participant_enroll_no();
			$org_id= $participantinfo->get_org_id();
			$participant_allergy_flag= $participantinfo->get_participant_allergy_flag();
			if($participant_allergy_flag=="on")
				$participant_allergy_flag=1;
			else
				$participant_allergy_flag=0;
			$others_allergy_food_description= $participantinfo->get_others_allergy_food_description();
			$participant_description= $participantinfo->get_participant_description();
			$participant_gender_id= $participantinfo->get_participant_gender_id();
			$organizer_id= $participantinfo->get_organizer_id();
			$participant_created_datetime= $participantinfo->get_participant_created_datetime();
			$participant_modified_datetime= $participantinfo->get_participant_modified_datetime();
			
			$query ="INSERT INTO  tbl_participant (participant_name,participant_enroll_no,org_id,participant_allergy_flag,others_allergy_food_description,participant_description,participant_gender_id,organizer_id,participant_created_datetime,participant_modified_datetime)
			VALUES (:participant_name,:participant_enroll_no,:org_id,:participant_allergy_flag,:others_allergy_food_description,:participant_description,:participant_gender_id,:organizer_id,:participant_created_datetime,:participant_modified_datetime)";
			$param = array(
			':participant_name'=>$participant_name,
			':participant_enroll_no'=>$participant_enroll_no,
			':org_id'=>$org_id,
			':participant_allergy_flag'=>$participant_allergy_flag,
			':others_allergy_food_description'=>$others_allergy_food_description,
			':participant_description'=>$participant_description,
			':participant_gender_id'=>$participant_gender_id,
			':organizer_id'=>$organizer_id,
			':participant_created_datetime'=>$participant_created_datetime,
			':participant_modified_datetime'=>$participant_modified_datetime
			);
			//echo "query=".$query;
			//echo "param=";print_r($param);exit();
			$result = execute_query($query,$param) or die ("save_participant query fail.");
			if($result)
			{
				#eventlog for save_participant
				$participant_id = last_instert_id();
				$eventlogbol = new eventlogbol();
				$old_values='';
				$new_values_arr = array();
				$new_values_arr['participant_id'] = $participant_id;
				$new_values_arr['participant_name'] = $participant_name;
				$new_values_arr['participant_enroll_no'] = $participant_enroll_no;
				$new_values_arr['org_id'] = $org_id;
				$new_values_arr['participant_allergy_flag'] = $participant_allergy_flag;
				$new_values_arr['others_allergy_food_description'] = $others_allergy_food_description;
				$new_values_arr['participant_description'] = $participant_description;
				$new_values_arr['participant_gender_id'] = $participant_gender_id;
				$new_values_arr['organizer_id'] = $organizer_id;
				$event_result = $eventlogbol->save_eventlog('Insert','tbl_participant',$new_values_arr,$old_values);
				return $participant_id;	
			}
			else 
				return false;
		}
		function save_participant_food_allergy($participant_food_allergyinfo)
		{
			$participant_id= $participant_food_allergyinfo->get_participant_id();
			$food_allergy_id= $participant_food_allergyinfo->get_food_allergy_id();
			
			$query ="INSERT INTO  tbl_participant_food_allergy (participant_id,food_allergy_id)
			VALUES (:participant_id,:food_allergy_id)";
			$param = array(
			':participant_id'=>$participant_id,
			':food_allergy_id'=>$food_allergy_id			
			);
			$result = execute_query($query,$param) or die ("save_participant query fail.");
			if($result)
			{
				#eventlog for save_participant
				$participant_food_allergy_id = last_instert_id();
				$eventlogbol = new eventlogbol();
				$old_values='';
				$new_values_arr = array();
				$new_values_arr['participant_food_allergy_id'] = $participant_food_allergy_id;
				$new_values_arr['participant_id'] = $participant_id;
				$new_values_arr['food_allergy_id'] = $food_allergy_id;
				$event_result = $eventlogbol->save_eventlog('Insert','tbl_participant_food_allergy',$new_values_arr,$old_values);
				return $participant_food_allergy_id;	
			}
			else 
				return false;
		}
	}
?>