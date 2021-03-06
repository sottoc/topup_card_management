<?php
class student_predefinedal{

//----------student predefine setup----------//
		function get_student_predefine_list($offset, $rpage ,$sorting,$cri_arr)
		{
			$cri_str = $cri_arr[0];
			$param = $cri_arr[1];
			$query = "SELECT SQL_CALC_FOUND_ROWS * FROM tbl_student_predefine s
					LEFT JOIN tbl_organization o on s.predefine_org_id=o.org_id";				
			$query .= $cri_str;
			$query .=$sorting;
			if($rpage!=-1)
				$query.= " LIMIT $offset,$rpage";
			//echo $query;exit();
			$result = execute_query($query,$param) or die ("get_student_predefine_list query fail.");
				
			return new readonlyresultset($result);
		}
		function check_duplicate_stunameandparname_saving($txt_student_name,$txt_parent_name)
		{
			$query="SELECT count(predefine_participant_id) as count_duplicate_saving FROM tbl_student_predefine WHERE predefine_participant_name=:predefine_participant_name and predefine_parent_name=:predefine_parent_name";
			$result = execute_query($query,array(':predefine_participant_name'=>$txt_student_name,'predefine_parent_name'=>$txt_parent_name)) or die ("check_duplicate_stunameandparname_saving query fail.");
			return new readonlyresultset($result);
		}
		function check_duplicate_stunameandparname_updating($edt_txt_student_name,$edt_txt_parent_name,$edt_hid_participant_id)
		{
			$query="SELECT count(predefine_participant_id) as count_duplicate_updating FROM tbl_student_predefine WHERE predefine_participant_name=:predefine_participant_name and predefine_parent_name=:predefine_parent_name and predefine_participant_id<>:predefine_participant_id";
			$result = execute_query($query,array(':predefine_participant_name'=>$edt_txt_student_name,'predefine_parent_name'=>$edt_txt_parent_name,'predefine_participant_id'=>$edt_hid_participant_id)) or die ("check_duplicate_stunameandparname_saving query fail.");
			return new readonlyresultset($result);
		}
		function save_student_predefine($student_predefineinfo)
		{
			//$predefine_participant_id= $student_predefineinfo->get_predefine_participant_id();
			$predefine_participant_name= $student_predefineinfo->get_predefine_participant_name();
			$predefine_participant_enroll_no= $student_predefineinfo->get_predefine_participant_enroll_no();
			$predefine_org_id= $student_predefineinfo->get_predefine_org_id();			
			$finger_print_number= $student_predefineinfo->get_finger_print_number();
			$predefine_parent_name= $student_predefineinfo->get_predefine_parent_name();
			
			$query ="INSERT INTO tbl_student_predefine (predefine_participant_name,predefine_participant_enroll_no,predefine_org_id,finger_print_number,predefine_parent_name) VALUES (:predefine_participant_name,:predefine_participant_enroll_no,:predefine_org_id,:finger_print_number,:predefine_parent_name) ";
			$param = array(
			':predefine_participant_name'=>$predefine_participant_name,
			':predefine_participant_enroll_no'=>$predefine_participant_enroll_no,
			':predefine_org_id'=>$predefine_org_id,
			':finger_print_number'=>$finger_print_number,
			':predefine_parent_name'=>$predefine_parent_name			
			);
			$result = execute_query($query,$param) or die ("save_item query fail.");
			if($result)
			{
				#eventlog 
				$predefine_participant_id = last_instert_id();
				$eventlogbol = new eventlogbol();
				$old_values='';
				$new_values_arr = array();
				$new_values_arr['predefine_participant_id'] = $predefine_participant_id;
				$new_values_arr['predefine_participant_name'] = $predefine_participant_name;
				$new_values_arr['predefine_participant_enroll_no'] = $predefine_participant_enroll_no;
				$new_values_arr['predefine_org_id'] = $predefine_org_id;
				$new_values_arr['finger_print_number'] = $finger_print_number;
				$new_values_arr['predefine_parent_name'] = $predefine_parent_name;
				$event_result = $eventlogbol->save_eventlog('Insert','tbl_student_predefine',$new_values_arr,$old_values);
				return $predefine_participant_id;	
			}
			else 
				return false;
		}
		function get_student_predefine_byid($predefine_participant_id)
		{
			$query="SELECT * FROM tbl_student_predefine WHERE predefine_participant_id=:predefine_participant_id";
			$result = execute_query($query,array(':predefine_participant_id'=>$predefine_participant_id)) or die ("get_student_predefine_byid query fail.");
			return new readonlyresultset($result); 
		}
	
		function update_student_predefine($student_predefineinfo,$edt_hid_student_values)
		{
			$predefine_participant_id= $student_predefineinfo->get_predefine_participant_id();
			$predefine_participant_name= $student_predefineinfo->get_predefine_participant_name();
			$predefine_participant_enroll_no= $student_predefineinfo->get_predefine_participant_enroll_no();
			$predefine_org_id= $student_predefineinfo->get_predefine_org_id();			
			$finger_print_number= $student_predefineinfo->get_finger_print_number();
			$predefine_parent_name= $student_predefineinfo->get_predefine_parent_name();
			
			#eventlog for user_type update
			$eventlogbol = new eventlogbol();
			$old_values=$edt_hid_student_values;
			$new_values_arr = array();
			$new_values_arr['predefine_participant_id'] = $predefine_participant_id;
			$new_values_arr['predefine_participant_name'] = $predefine_participant_name;
			$new_values_arr['predefine_participant_enroll_no'] = $predefine_participant_enroll_no;
			$new_values_arr['predefine_org_id'] = $predefine_org_id;
			$new_values_arr['finger_print_number'] = $finger_print_number;
			$new_values_arr['predefine_parent_name'] = $predefine_parent_name;
			$event_result = $eventlogbol->save_eventlog('Update','tbl_student_predefine',$new_values_arr,$old_values);
			
			if($event_result)
			{
				$query="UPDATE tbl_student_predefine SET predefine_participant_name= :predefine_participant_name,predefine_participant_enroll_no=:predefine_participant_enroll_no,predefine_org_id=:predefine_org_id,finger_print_number= :finger_print_number,predefine_parent_name=:predefine_parent_name WHERE predefine_participant_id= :predefine_participant_id; ";		
				$param = array(
				':predefine_participant_id'=>$predefine_participant_id,
				':predefine_participant_name'=>$predefine_participant_name,
				':predefine_participant_enroll_no'=>$predefine_participant_enroll_no,
				':predefine_org_id'=>$predefine_org_id,
				':finger_print_number'=>$finger_print_number,
				':predefine_parent_name'=>$predefine_parent_name
				);
				$result = execute_non_query($query,$param);
				return $result;
			}
			else 
				return false;
		}
		
		function delete_student_predefine($predefine_participant_id)
		{
			#eventlog for delete
			$eventlogbol = new eventlogbol();
			$old_values='';
			$new_values_arr = array();
			$new_values_arr['predefine_participant_id'] = $predefine_participant_id;
			$event_result = $eventlogbol->save_eventlog('Delete','tbl_student_predefine',$new_values_arr,$old_values);
			if($event_result)
			{
				$query="DELETE FROM tbl_student_predefine WHERE predefine_participant_id=:predefine_participant_id ";		
				$result = execute_non_query($query, array(':predefine_participant_id' => $predefine_participant_id));
				return $result;
			}
			else 
				return false;
		}
	//-------------------------------//
}
?>