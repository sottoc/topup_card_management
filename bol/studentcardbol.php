<?php
class studentcardbol{
	function get_card_list($offset, $rpage ,$sorting,$cri_arr)
	{
		$studentcarddal=new studentcarddal();
		$result=$studentcarddal->get_card_list($offset, $rpage ,$sorting,$cri_arr);
		return $result;
	}
	function get_card_count_by_pid($participant_id)
	{
		$studentcarddal=new studentcarddal();
		$result=$studentcarddal->get_card_count_by_pid($participant_id);
		return $result;
	}
	function check_duplicate_card_saving($txt_card_number)
	{
		$studentcarddal=new studentcarddal();
		$result=$studentcarddal->check_duplicate_card_saving($txt_card_number);
		return $result;
	}
	function save_card($cardinfo)
	{
		$studentcarddal=new studentcarddal();
		$result=$studentcarddal->save_card($cardinfo);
		return $result;
	}
	function get_card_byid($card_id)
	{
		$studentcarddal=new studentcarddal();
		$result=$studentcarddal->get_card_byid($card_id);
		return $result;
	}
	function check_duplicate_card_updating($edt_txt_card_number,$edt_hid_card_id)
	{
		$studentcarddal=new studentcarddal();
		$result=$studentcarddal->check_duplicate_card_updating($edt_txt_card_number,$edt_hid_card_id);
		return $result;
	}
	function update_card($cardinfo,$edt_hid_card_values)
	{
		$studentcarddal=new studentcarddal();
		$result=$studentcarddal->update_card($cardinfo,$edt_hid_card_values);
		return $result;
	}
	function check_card_using($card_id)
	{
		$studentcarddal=new studentcarddal();
		$result=$studentcarddal->check_card_using($card_id);
		return $result;
	}
	function delete_card($card_id)
	{
		$studentcarddal=new studentcarddal();
		$result=$studentcarddal->delete_card($card_id);
		return $result;
	}
}
?>