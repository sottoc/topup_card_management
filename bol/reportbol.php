<?php
class reportbol{
	function get_pre_order_report($offset, $rpage ,$sorting,$cri_arr)
	{
		$reportdal=new reportdal();
		$result=$reportdal->get_pre_order_report($offset, $rpage ,$sorting,$cri_arr);
		return $result;
	}
	function get_student_by_loginusertype($login_user_type_id,$login_user_id)
	{
		$reportdal=new reportdal();
		$result=$reportdal->get_student_by_loginusertype($login_user_type_id,$login_user_id);
		return $result;
	}
	function get_redemption_report($offset, $rpage ,$sorting,$cri_arr)
	{
		$reportdal=new reportdal();
		$result=$reportdal->get_redemption_report($offset, $rpage ,$sorting,$cri_arr);
		return $result;
	}
	function get_transaction_report($DisplayStart,$DisplayLength,$SortingCols,$cri_arr)
	{
		$reportdal=new reportdal();
		$result=$reportdal->get_transaction_report($DisplayStart,$DisplayLength,$SortingCols,$cri_arr);
		return $result;
	}
	function get_summary_report($DisplayStart,$DisplayLength,$SortingCols,$cri_arr)
	{
		$reportdal=new reportdal();
		$result=$reportdal->get_summary_report($DisplayStart,$DisplayLength,$SortingCols,$cri_arr);
		return $result;
	}
	function get_spending_history($DisplayStart,$DisplayLength,$SortingCols,$cri_arr)
	{
		$reportdal=new reportdal();
		$result=$reportdal->get_spending_history($DisplayStart,$DisplayLength,$SortingCols,$cri_arr);
		return $result;
	}
	function get_topup_history($DisplayStart,$DisplayLength,$SortingCols,$cri_arr)
	{
		$reportdal=new reportdal();
		$result=$reportdal->get_topup_history($DisplayStart,$DisplayLength,$SortingCols,$cri_arr);
		return $result;
	}
	function get_user_report($DisplayStart,$DisplayLength,$SortingCols,$cri_arr)
	{
		$reportdal=new reportdal();
		$result=$reportdal->get_user_report($DisplayStart,$DisplayLength,$SortingCols,$cri_arr);
		return $result;
	}
	function get_refund_report($DisplayStart,$DisplayLength,$SortingCols,$cri_arr)
	{
		$reportdal=new reportdal();
		$result=$reportdal->get_refund_report($DisplayStart,$DisplayLength,$SortingCols,$cri_arr);
		return $result;
	}
	function get_card_report($offset, $rpage ,$sorting,$cri_arr)
	{
			$reportdal=new reportdal();
			$result=$reportdal->get_card_report($offset, $rpage ,$sorting,$cri_arr);
			return $result;
	}
	function get_card_report_new($offset, $rpage ,$sorting,$cri_arr)
	{
			$reportdal=new reportdal();
			$result=$reportdal->get_card_report_new($offset, $rpage ,$sorting,$cri_arr);
			return $result;
	}
	function get_prepaid_card($offset, $rpage ,$sorting,$cri_arr)
	{
			$reportdal=new reportdal();
			$result=$reportdal->get_prepaid_card($offset, $rpage ,$sorting,$cri_arr);
			return $result;
	}
	function get_children_list($offset, $rpage ,$sorting,$cri_arr)
	{
			$reportdal=new reportdal();
			$result=$reportdal->get_children_list($offset, $rpage ,$sorting,$cri_arr);
			return $result;
	}
	function get_topup_report($offset, $rpage ,$sorting,$cri_arr)
	{
		$reportdal=new reportdal();
		$result=$reportdal->get_topup_report($offset, $rpage ,$sorting,$cri_arr);
		return $result;
	}

	function get_order_schedule_report($offset, $rpage ,$sorting,$cri_arr)
	{
		$reportdal=new reportdal();
		$result=$reportdal->get_order_schedule_report($offset, $rpage ,$sorting,$cri_arr);
		return $result;
	}
	
	function checkmealstatustofinish($pre_order_id)
	{
		$reportdal=new reportdal();
		$result=$reportdal->checkmealstatustofinish($pre_order_id);
		return $result;
	}
	function get_order_schedule_summary_report($offset, $rpage ,$sorting,$cri_arr)
	{
		$reportdal=new reportdal();
		$result=$reportdal->get_order_schedule_summary_report($offset, $rpage ,$sorting,$cri_arr);
		return $result;
	}
	
	function get_order_schedule_table_report($preorder_date)
	{
		$reportdal=new reportdal();
		$result=$reportdal->get_order_schedule_table_report($preorder_date);
		return $result;
	}
		
	function get_order_scheduledetail_table_report($preorder_date)
	{
		$reportdal=new reportdal();
		$result=$reportdal->get_order_scheduledetail_table_report($preorder_date);
		return $result;
	}
	
	function get_topup_history_report($participant_id,$all_data)
	{
		$reportdal=new reportdal();
		$result=$reportdal->get_topup_history_report($participant_id,$all_data);
		return $result;
	}
	
	function get_purchase_history_report($participant_id,$all_data)
	{
		$reportdal=new reportdal();
		$result=$reportdal->get_purchase_history_report($participant_id,$all_data);
		return $result;
	}
	function get_amount_of_user($user_id)
	{
		$reportdal=new reportdal();
		$result = $reportdal->get_amount_of_user($user_id);
		return $result;
	}
}
?>