<?php
class studentcarddal{
	function get_card_list($offset, $rpage ,$sorting,$cri_arr)
	{
		$cri_str = $cri_arr[0];
		$param = $cri_arr[1];
		$query = "SELECT SQL_CALC_FOUND_ROWS * FROM tbl_card c
				  inner join tbl_participant p on c.participant_id=p.participant_id
				  inner join tbl_card_status cs on c.card_status_id=cs.card_status_id";
		$query .= $cri_str;
		$query .=$sorting;
		if($rpage!=-1)
			$query.= " LIMIT $offset,$rpage";
		$result = execute_query($query,$param) or die ("get_card_list query fail.");
			
		return new readonlyresultset($result);
	}
	function get_card_count_by_pid($participant_id)
	{
		$query="SELECT count(card_id) as card_count FROM tbl_card WHERE participant_id=:participant_id";
		$result = execute_query($query,array(':participant_id'=>$participant_id)) or die ("get_card_count_by_pid query fail.");
		return new readonlyresultset($result); 
	}
	function check_duplicate_card_saving($txt_card_number)
	{
		$query="SELECT count(card_id) as count_duplicate_saving FROM tbl_card WHERE card_number=:card_number";
		$result = execute_query($query,array(':card_number'=>$txt_card_number)) or die ("check_duplicate_card_saving query fail.");
		return new readonlyresultset($result);
	}
	function save_card($cardinfo)
	{
		$card_number= $cardinfo->get_card_number();
		$participant_id= $cardinfo->get_participant_id();
		$card_description= $cardinfo->get_card_description();
		$card_issued_datetime= $cardinfo->get_card_issued_datetime();
		$card_expired_datetime= $cardinfo->get_card_expired_datetime();
		$current_card_amt= $cardinfo->get_current_card_amt();
		$card_status_id= $cardinfo->get_card_status_id();
		$card_data_modified_datetime= $cardinfo->get_card_data_modified_datetime();
		
		$query ="INSERT INTO  tbl_card (card_number,participant_id,card_description,card_issued_datetime,card_expired_datetime,current_card_amt,card_status_id,card_data_modified_datetime) VALUES (:card_number,:participant_id,:card_description,:card_issued_datetime,:card_expired_datetime,:current_card_amt,:card_status_id,:card_data_modified_datetime)";
		$param = array(
		':card_number'=>$card_number,
		':participant_id'=>$participant_id,
		':card_description'=>$card_description,
		':card_issued_datetime'=>$card_issued_datetime,
		':card_expired_datetime'=>$card_expired_datetime,
		':current_card_amt'=>$current_card_amt,
		':card_status_id'=>$card_status_id,
		':card_data_modified_datetime'=>$card_data_modified_datetime,
		);
		$result = execute_query($query,$param) or die ("save_card query fail.");
		if($result)
		{
			#eventlog for card save
			$card_id = last_instert_id();
			$eventlogbol = new eventlogbol();
			$old_values='';
			$new_values_arr = array();
			$new_values_arr['card_number'] = $card_number;
			$new_values_arr['participant_id'] = $participant_id;
			$new_values_arr['card_description'] = $card_description;
			$new_values_arr['card_issued_datetime'] = $card_issued_datetime;
			$new_values_arr['card_expired_datetime'] = $card_expired_datetime;
			$new_values_arr['current_card_amt'] = $current_card_amt;
			$new_values_arr['card_status_id'] = $card_status_id;
			$new_values_arr['card_data_modified_datetime'] = $card_data_modified_datetime;
			$event_result = $eventlogbol->save_eventlog('Insert','tbl_card',$new_values_arr,$old_values);
			return $card_id;	
		}
		else 
			return false;
	}
	function get_card_byid($card_id)
	{
		$query="SELECT * FROM tbl_card WHERE card_id=:card_id";
		$result = execute_query($query,array(':card_id'=>$card_id)) or die ("get_card_byid query fail.");
		return new readonlyresultset($result);
	}
	function check_duplicate_card_updating($edt_txt_card_number,$edt_hid_card_id)
	{
		$query="SELECT count(card_id) as count_duplicate_updating FROM tbl_card WHERE card_number=:card_number and card_id <> :card_id";
		$result = execute_query($query,array(':card_number'=>$edt_txt_card_number,':card_id'=>$edt_hid_card_id)) or die ("check_duplicate_card_updating query fail.");
		return new readonlyresultset($result);
	}
	function update_card($cardinfo,$edt_hid_card_values)
	{
		$card_id= $cardinfo->get_card_id();
		$card_number= $cardinfo->get_card_number();
		$card_description= $cardinfo->get_card_description();
		$card_data_modified_datetime= $cardinfo->get_card_data_modified_datetime();
		
		#eventlog for card update
		$eventlogbol = new eventlogbol();
		$old_values = $edt_hid_card_values;
		$new_values_arr = array();
		$new_values_arr['card_id'] = $card_id;
		$new_values_arr['card_number'] = $card_number;
		$new_values_arr['card_description'] = $card_description;
		$event_result = $eventlogbol->save_eventlog('Update','tbl_card',$new_values_arr,$old_values);
		
		if($event_result)
		{
			$query="UPDATE  tbl_card SET card_number= :card_number,card_description=:card_description,card_data_modified_datetime=:card_data_modified_datetime WHERE card_id= :card_id; ";		
			$param = array(
			':card_id'=>$card_id,
			':card_number'=>$card_number,
			':card_description'=>$card_description,
			':card_data_modified_datetime'=>$card_data_modified_datetime
			);
			//echo "query=".$query;
			//print_r($param);exit();
			$result = execute_non_query($query,$param);
			return $result;
		}
		else 
			return false;
	}
	function check_card_using($card_id)
	{
		$query="SELECT count(card_id) as count_card_records FROM tbl_transaction WHERE card_id=:card_id";
		$result = execute_query($query,array(':card_id'=>$card_id)) or die ("check_card_using query fail.");
		return new readonlyresultset($result);
	}
	function delete_card($card_id)
	{
		#eventlog for meal_status delete
		$eventlogbol = new eventlogbol();
		$old_values='';
		$new_values_arr = array();
		$new_values_arr['card_id'] = $card_id;
		$event_result = $eventlogbol->save_eventlog('Delete','tbl_card',$new_values_arr,$old_values);
		if($event_result)
		{
			$query="DELETE FROM tbl_card WHERE card_id=:card_id ";		
			$result = execute_non_query($query, array(':card_id' => $card_id));
			return $result;
		}
		else 
			return false;
	}
}
?>