<?php
class transactionbol{
	function save_topup_manually($topupinfo)
	{
		$transactiondal=new transactiondal();
		$result=$transactiondal->save_topup_manually($topupinfo);
		return $result;
	}
	function get_participant_data_byusingID($topup_manual_studentid)
	{
		$transactiondal=new transactiondal();
		$result=$transactiondal->get_participant_data_byusingID($topup_manual_studentid);
		return $result;
	}
	function save_transaction($transactioninfo)
	{
		$transactiondal=new transactiondal();
		$result=$transactiondal->save_transaction($transactioninfo);
		return $result;
	}
	function get_all_category_type()
	{
		$transactiondal=new transactiondal();
		$result=$transactiondal->get_all_category_type();
		return $result;
	}
	function get_student_data_by_studentID($redemption_studentid)
	{
		$transactiondal=new transactiondal();
		$result=$transactiondal->get_student_data_by_studentID($redemption_studentid);
		return $result;
	}
	function get_pre_order($category_type_id,$participant_id)
	{
		$transactiondal=new transactiondal();
		$result=$transactiondal->get_pre_order($category_type_id,$participant_id);
		return $result;
	}
	function get_total_topupamt_by_stdid($std_id)
	{
		$transactiondal=new transactiondal();
		$result=$transactiondal->get_total_topupamt_by_stdid($std_id);
		return $result;
	}
	function get_total_redemptionamt_by_stdid($std_id)
	{
		$transactiondal=new transactiondal();
		$result=$transactiondal->get_total_redemptionamt_by_stdid($std_id);
		return $result;
	}
	function save_preorder_redemption($redemptioninfo)
	{
		$transactiondal=new transactiondal();
		$result=$transactiondal->save_preorder_redemption($redemptioninfo);
		return $result;
	}
	function get_preorder_data_byusing_preorderid($value)
	{
		$transactiondal=new transactiondal();
		$result=$transactiondal->get_preorder_data_byusing_preorderid($value);
		return $result;
	}
	function save_transaction_preorder_redempation($transactioninfo)
	{
		$transactiondal=new transactiondal();
		$result=$transactiondal->save_transaction_preorder_redempation($transactioninfo);
		return $result;
	}
	function check_studentid($student_enroll_number)
	{
		$transactiondal=new transactiondal();
		$result=$transactiondal->check_studentid($student_enroll_number);
		return $result;
	}
	function update_meal_status($value)
	{
		$transactiondal=new transactiondal();
		$result=$transactiondal->update_meal_status($value);
		return $result;
	}
	function get_price_byitemid($item_id)
	{
		$transactiondal=new transactiondal();
		$result=$transactiondal->get_price_byitemid($item_id);
		return $result;
	}
	function save_canteen_orders($participant_canteen_ordersinfo)
	{
		$transactiondal=new transactiondal();
		$result=$transactiondal->save_canteen_orders($participant_canteen_ordersinfo);
		return $result;
	}
	function save_canteen_orders_redemption($redemptioninfo)
	{
		$transactiondal=new transactiondal();
		$result=$transactiondal->save_canteen_orders_redemption($redemptioninfo);
		return $result;
	}
	function get_card_data_by_participant_id($p_id)
	{
		$transactiondal=new transactiondal();
		$result=$transactiondal->get_card_data_by_participant_id($p_id);
		return $result;
	}
	function delete_redemption_by_preorderid($pre_order_id)
	{
		$transactiondal=new transactiondal();
		$result=$transactiondal->delete_redemption_by_preorderid($pre_order_id);
		return $result;
	}
	function get_all_redemption_ids_by_preorderid($pre_order_id)
	{
		$transactiondal=new transactiondal();
		$result=$transactiondal->get_all_redemption_ids_by_preorderid($pre_order_id);
		return $result;
	}
	function delete_transaction_by_redemptionid($redemption_ids)
	{
		$transactiondal=new transactiondal();
		$result=$transactiondal->delete_transaction_by_redemptionid($redemption_ids);
		return $result;
	}
	function save_payment_detail($payment_detailinfo)
	{
		$transactiondal=new transactiondal();
		$result=$transactiondal->save_payment_detail($payment_detailinfo);
		return $result;
	}
}
?>