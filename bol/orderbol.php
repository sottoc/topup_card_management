<?php
class orderbol{
	function get_participant_by_organizerid($login_user_id)
	{
		$orderdal=new orderdal();
		$result=$orderdal->get_participant_by_organizerid($login_user_id);
		return $result;
	}
	function get_pname_byp_id($pid)
	{
		$orderdal=new orderdal();
		$result=$orderdal->get_pname_byp_id($pid);
		return $result;
	}
	function check_duplicate_preorder_saving($p_id,$category_type_id,$selected_date)
	{
		$orderdal=new orderdal();
		$result=$orderdal->check_duplicate_preorder_saving($p_id,$category_type_id,$selected_date);
		return $result;
	}
	function save_preorder($pre_ordersinfo)
	{
		$orderdal=new orderdal();
		$result=$orderdal->save_preorder($pre_ordersinfo);
		return $result;
	}
	function get_preorder_by_pid($pid_str)
	{
		$orderdal=new orderdal();
		$result=$orderdal->get_preorder_by_pid($pid_str);
		return $result;
	}
	function update_preorder($pre_ordersinfo,$old_item_value_str)
	{
		$orderdal=new orderdal();
		$result=$orderdal->update_preorder($pre_ordersinfo,$old_item_value_str);
		return $result;
	}
	function get_preorder_byid($pre_order_id)
	{
		$orderdal=new orderdal();
		$result=$orderdal->get_preorder_byid($pre_order_id);
		return $result;
	}
	function delete_preorder($delete_id)
	{
		$orderdal=new orderdal();
		$result=$orderdal->delete_preorder($delete_id);
		return $result;
	}
}
?>