<?php
	class participantdal
	{
		function select_participant_list($offset=0,$rpage=0,$sorting='',$cri_arr,$organizer_id)
		{
			$cri_str = $cri_arr[0];
			$cri_str .= " AND organizer_id = $organizer_id";
			$param = $cri_arr[1];
			$qry = "SELECT SQL_CALC_FOUND_ROWS g.gender_name,p.*
					FROM tbl_participant p
					INNER JOIN tbl_gender g ON p.participant_gender_id = g.gender_id ";
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
		function check_duplicate_participant_saving($participant_enroll_no,$id=0)
		{
			$query="SELECT count(participant_id) as count_duplicate_saving FROM tbl_participant WHERE participant_enroll_no=:participant_enroll_no AND participant_id!= :id";
			$result = execute_query($query,array(':participant_enroll_no'=>$participant_enroll_no,'id'=>$id)) or die ("check_duplicate_participant_saving query fail.");
			return new readonlyresultset($result);
		}
		function save_participant($participantinfo)
		{
			$participant_name= $participantinfo->get_participant_name();
			$participant_enroll_no= $participantinfo->get_participant_enroll_no();
			$org_id= $participantinfo->get_org_id();
			$participant_allergy_flag= $participantinfo->get_participant_allergy_flag();
			$others_allergy_food_description= $participantinfo->get_others_allergy_food_description();
			$participant_description= $participantinfo->get_participant_description();
			$participant_gender_id= $participantinfo->get_participant_gender_id();
			$organizer_id= $participantinfo->get_organizer_id();
			$participant_created_datetime= $participantinfo->get_participant_created_datetime();
			$participant_modified_datetime= $participantinfo->get_participant_modified_datetime();
			$allow_preorder = $participantinfo->get_allow_preorder();

			$query ="INSERT INTO  tbl_participant (participant_name,participant_enroll_no,org_id,participant_allergy_flag,others_allergy_food_description,participant_description,participant_gender_id,organizer_id,participant_created_datetime,participant_modified_datetime,allow_preorder)
			VALUES (:participant_name,:participant_enroll_no,:org_id,:participant_allergy_flag,:others_allergy_food_description,:participant_description,:participant_gender_id,:organizer_id,:participant_created_datetime,:participant_modified_datetime,:allow_preorder)";
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
			':participant_modified_datetime'=>$participant_modified_datetime,
			':allow_preorder'=>$allow_preorder
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
			$participant_id = NULL;
			if($participant_food_allergyinfo->get_participant_id() != '')
				$participant_id= $participant_food_allergyinfo->get_participant_id();
			$food_allergy_id= $participant_food_allergyinfo->get_food_allergy_id();
			$predefine_participant_id= $participant_food_allergyinfo->get_predefine_participant_id();
			
			$query ="INSERT INTO  tbl_participant_food_allergy (participant_id,food_allergy_id,predefine_participant_id)
					VALUES (:participant_id,:food_allergy_id,:predefine_participant_id)";
					$param = array(
					':participant_id'=>$participant_id,
					':food_allergy_id'=>$food_allergy_id,
					':predefine_participant_id'=>$predefine_participant_id			
					);
			var_dump($param);echo $query;exit();

			$result = execute_query($query,$param) or die ("save_participant_food_allergy query fail.");
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
				$new_values_arr['predefine_participant_id'] = $predefine_participant_id;
				$event_result = $eventlogbol->save_eventlog('Insert','tbl_participant_food_allergy',$new_values_arr,$old_values);
				return $participant_food_allergy_id;	
			}
			else 
				return false;
		}

		function select_predefined_studentIDs($participant_name, $organizer_name)
		{
			$param = array(':participant_name'=>$participant_name);//, ':organizer_name'=>$organizer_name
			$query = "SELECT std.predefine_participant_enroll_no,std.predefine_participant_name,std.predefine_parent_name,org.org_name
					FROM tbl_student_predefine std 
					INNER JOIN tbl_organization org ON org.org_id = std.predefine_org_id
					WHERE predefine_participant_name LIKE CONCAT('%',:participant_name,'%')
					/*AND predefine_parent_name LIKE CONCAT('%',:organizer_name,'%')*/";
			
			$result = execute_query($query, $param) or die ("select_predefined_studentIDs query fail.");
			return new readonlyresultset($result);
		}

		function delete_participant($participant_id)
		{
			$eventlogbol = new eventlogbol();
			$old_values='';
			$new_values_arr = array();
			$new_values_arr['participant_id'] = $participant_id;
			$event_result = $eventlogbol->save_eventlog('Delete','tbl_participant',$new_values_arr,$old_values);
			$condition_array = array(':participant_id' => $participant_id);
			if($event_result)
			{
				$query_1="DELETE FROM tbl_card WHERE participant_id=:participant_id ";		
				$result_1 = execute_non_query($query_1, $condition_array);
				if($result_1){
					$query_2="DELETE FROM tbl_participant_food_allergy WHERE participant_id=:participant_id ";		
					$result_2 = execute_non_query($query_2, $condition_array); 

					$query="DELETE FROM tbl_participant WHERE participant_id=:participant_id ";		
					$result = execute_non_query($query, $condition_array);
					return $result;
				}
				
			}
			else 
				return false;
			
		}

		function check_using_inPreorderTable($participant_id)
		{
			$query="SELECT pre_order_id FROM tbl_pre_orders WHERE participant_id = :participant_id";	
			$result = execute_query($query,array(':participant_id'=>$participant_id)) or die('check_using_inPreorderTable query fails');
			return $result->rowCount();
		}

		function check_using_inTransactionsTable($participant_id)
		{
			$query="SELECT trans_id FROM tbl_transaction WHERE participant_id = :participant_id";	
			$result = execute_query($query,array(':participant_id'=>$participant_id)) or die('check_using_inTransactionsTable query fails');
			return $result->rowCount();
		}

		function select_participant_byid($id)
		{
			$query ="SELECT g.gender_name,org.org_name,p.* FROM tbl_participant p
					INNER JOIN tbl_organization org ON org.org_id = p.org_id
					LEFT JOIN tbl_participant_food_allergy pfa ON pfa.participant_id = p.participant_id
					INNER JOIN tbl_gender g ON g.gender_id = p.participant_gender_id
					where p.participant_id= :id";
			$result = execute_query($query, array(':id' => $id)) or die ("select_participant_byid query fail.");
			return new readonlyresultset($result); 
		}

		function select_allergyfoods_byid($id)
		{
			$query ="SELECT food_allergy_name FROM tbl_participant_food_allergy pfa 
					INNER JOIN tbl_food_allergy fa ON fa.food_allergy_id = pfa.food_allergy_id 
					where pfa.participant_id= :id";
			$result = execute_query($query, array(':id' => $id)) or die ("select_allergyfoods_byid query fail.");
			return new readonlyresultset($result); 
		}

		function edit_participant($participantinfo,$edt_hid_org_values)
		{
			$participant_id = $participantinfo->get_participant_id();
			$participant_name = $participantinfo->get_participant_name();
			$participant_enroll_no = $participantinfo->get_participant_enroll_no();
			$org_id = $participantinfo->get_org_id();
			$participant_allergy_flag = $participantinfo->get_participant_allergy_flag();
			$others_allergy_food_description = $participantinfo->get_others_allergy_food_description();
			$participant_description = $participantinfo->get_participant_description();
			$participant_gender_id = $participantinfo->get_participant_gender_id();
			$organizer_id = $participantinfo->get_organizer_id();
			$participant_modified_datetime = $participantinfo->get_participant_modified_datetime();
			$allow_preorder = $participantinfo->get_allow_preorder();
			
			$eventlogbol = new eventlogbol();
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
			$new_values_arr['participant_modified_datetime'] = $participant_modified_datetime;
			$new_values_arr['allow_preorder'] = $allow_preorder;

			$event_result = $eventlogbol->save_eventlog('Update','tbl_participant',$new_values_arr,$edt_hid_org_values);
			if($event_result){
				$query = "UPDATE tbl_participant 
						  SET participant_name= :participant_name, 
						  	  participant_enroll_no= :participant_enroll_no, 
						  	  org_id= :org_id, 
						  	  participant_allergy_flag= :participant_allergy_flag, 
						  	  others_allergy_food_description= :others_allergy_food_description, 
						  	  participant_description= :participant_description, 
						  	  participant_gender_id= :participant_gender_id, 
						  	  organizer_id= :organizer_id, 
						  	  participant_modified_datetime= :participant_modified_datetime, 
						  	  allow_preorder= :allow_preorder 
						  	WHERE participant_id= :participant_id";
				$params = array(
							':participant_id'=>$participant_id,
							':participant_name'=>$participant_name,
							':participant_enroll_no'=>$participant_enroll_no,
							':org_id'=>$org_id,
							':participant_allergy_flag'=>$participant_allergy_flag,
							':others_allergy_food_description'=> $others_allergy_food_description, 
							':participant_description'=>$participant_description,
							':participant_gender_id'=>$participant_gender_id,
							':organizer_id'=>$organizer_id,
							':participant_modified_datetime'=>$participant_modified_datetime,
							':allow_preorder'=>$allow_preorder);
				//echo $query; var_dump($params);exit();
				$result = execute_non_query($query, $params) or die( 'edit_participant query fail.');
				
				return $result;
			}
			else 
				return false;
		}
		function delete_participant_food_allergy($participant_id)
		{
			$eventlogbol = new eventlogbol();
			$old_values='';
			$new_values_arr = array();
			$new_values_arr['participant_id'] = $participant_id;

			$event_result = $eventlogbol->save_eventlog('Delete','tbl_participant_food_allergy',$new_values_arr,$old_values);
			if($event_result)
			{
				$query="DELETE FROM tbl_participant_food_allergy WHERE participant_id=:participant_id ";		
				$result = execute_non_query($query, array(':participant_id' => $participant_id));
				return $result;
			}
			else 
				return false;
		}

		function get_organizer_id($user_id)
		{
			$query = "SELECT organizer_id FROM tbl_organizer WHERE user_id=:user_id";
			$param = array(':user_id'=>$user_id);
			$result = execute_query($query, $param) or die ("get_organizer_id query fail.");
			return new readonlyresultset($result);
		}
	}
?>