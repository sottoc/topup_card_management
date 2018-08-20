<?php
class orderdal{
	function get_participant_by_organizerid($login_user_id)
	{
		$query="SELECT * FROM tbl_participant where organizer_id in (select organizer_id from tbl_organizer where user_id=:organizer_id)";
		$result = execute_query($query,array(":organizer_id"=>$login_user_id)) or die ("get_participant_by_organizerid query fail.");
		return new readonlyresultset($result);
	}
	function get_pname_byp_id($pid)
	{
		$query="SELECT * FROM tbl_participant where participant_id=:pid";
		$result = execute_query($query,array(":pid"=>$pid)) or die ("get_pname_byp_id query fail.");
		return new readonlyresultset($result);
	}
	function check_duplicate_preorder_saving($p_id,$category_type_id,$selected_date)
	{
		$query="SELECT pre_order_id as count_duplicate_saving FROM tbl_pre_orders where participant_id=:pid and preorder_date=:selected_date and category_type_id=:category_type_id";
		$params=array(
		":pid"=>$p_id,
		":selected_date"=>$selected_date,
		":category_type_id"=>$category_type_id
		);
		$result = execute_query($query,$params) or die ("check_duplicate_preorder_saving query fail.");
		return new readonlyresultset($result);
	}
	function save_preorder($pre_ordersinfo)
	{
		$card_id= $pre_ordersinfo->get_card_id();
		$participant_id= $pre_ordersinfo->get_participant_id();
		$preorder_date= $pre_ordersinfo->get_preorder_date();
		$item_id= $pre_ordersinfo->get_item_id();
		$qty= $pre_ordersinfo->get_qty();
		$category_type_id= $pre_ordersinfo->get_category_type_id();
		$meal_status_id= $pre_ordersinfo->get_meal_status_id();
		$created_datetime= $pre_ordersinfo->get_created_datetime();
		$modified_datetime= $pre_ordersinfo->get_modified_datetime();
		
		$query ="INSERT INTO  tbl_pre_orders (card_id,participant_id,preorder_date,item_id,qty,category_type_id,meal_status_id,created_datetime,modified_datetime) VALUES (:card_id,:participant_id,:preorder_date,:item_id,:qty,:category_type_id,:meal_status_id,:created_datetime,:modified_datetime)";
		$param = array(
		':card_id'=>$card_id,
		':participant_id'=>$participant_id,
		':preorder_date'=>$preorder_date,
		':item_id'=>$item_id,
		':qty'=>$qty,
		':category_type_id'=>$category_type_id,
		':meal_status_id'=>$meal_status_id,
		':created_datetime'=>$created_datetime,
		':modified_datetime'=>$modified_datetime
		);
		$result = execute_query($query,$param) or die ("save_preorder query fail.");
		if($result)
		{
			#eventlog for pre order save
			$pre_order_id = last_instert_id();
			$eventlogbol = new eventlogbol();
			$old_values='';
			$new_values_arr = array();
			$new_values_arr['pre_order_id'] = $pre_order_id;
			$new_values_arr['card_id'] = $card_id;
			$new_values_arr['participant_id'] = $participant_id;
			$new_values_arr['preorder_date'] = $preorder_date;
			$new_values_arr['item_id'] = $item_id;
			$new_values_arr['qty'] = $qty;
			$new_values_arr['category_type_id'] = $category_type_id;
			$new_values_arr['meal_status_id'] = $meal_status_id;
			$event_result = $eventlogbol->save_eventlog('Insert','tbl_pre_orders',$new_values_arr,$old_values);
			return $pre_order_id;	
		}
		else 
			return false;
	}
	function get_preorder_by_pid($pid_str)
	{
		$query="SELECT *,pre_order.participant_id as pid FROM tbl_pre_orders pre_order
				inner join tbl_participant p on pre_order.participant_id=p.participant_id
				inner join tbl_items i on pre_order.item_id = i.item_id
				inner join tbl_category_type c on c.category_type_id=pre_order.category_type_id
				inner join tbl_meal_status m on m.meal_status_id=pre_order.meal_status_id
				where pre_order.participant_id in ($pid_str)";
		$result = execute_query($query) or die ("get_preorder_by_pid query fail.");
		return new readonlyresultset($result);
	}
	function update_preorder($pre_ordersinfo,$old_item_value_str)
	{
		$pre_order_id= $pre_ordersinfo->get_pre_order_id();
		$card_id= $pre_ordersinfo->get_card_id();
		$participant_id= $pre_ordersinfo->get_participant_id();
		$preorder_date= $pre_ordersinfo->get_preorder_date();
		$item_id= $pre_ordersinfo->get_item_id();
		$qty= $pre_ordersinfo->get_qty();
		$category_type_id= $pre_ordersinfo->get_category_type_id();
		$meal_status_id= $pre_ordersinfo->get_meal_status_id();
		$modified_datetime= $pre_ordersinfo->get_modified_datetime();
		
		#eventlog for preorder update
		$eventlogbol = new eventlogbol();
		$old_values=$old_item_value_str;
		$new_values_arr = array();
		$new_values_arr['pre_order_id'] = $pre_order_id;
		$new_values_arr['card_id'] = $card_id;
		$new_values_arr['participant_id'] = $participant_id;
		$new_values_arr['preorder_date'] = $preorder_date;
		$new_values_arr['item_id'] = $item_id;
		$new_values_arr['qty'] = $qty;
		$new_values_arr['category_type_id'] = $category_type_id;
		$new_values_arr['meal_status_id'] = $meal_status_id;
		$event_result = $eventlogbol->save_eventlog('Update','tbl_pre_orders',$new_values_arr,$old_values);
		
		if($event_result)
		{
			$query="UPDATE  tbl_pre_orders SET participant_id= :participant_id,item_id= :item_id,qty= :qty,category_type_id=:category_type_id,meal_status_id=:meal_status_id WHERE pre_order_id= :pre_order_id; ";			
			$param = array(
			':pre_order_id'=>$pre_order_id,
			':participant_id'=>$participant_id,
			':item_id'=>$item_id,
			':qty'=>$qty,
			':category_type_id'=>$category_type_id,
			':meal_status_id'=>$meal_status_id
			);
			$result = execute_non_query($query,$param);
			return $result;
		}
		else 
			return false;
	}
	function get_preorder_byid($pre_order_id)
	{
		$query="SELECT * FROM tbl_pre_orders where pre_order_id=:pre_order_id";
		$result = execute_query($query,array(":pre_order_id"=>$pre_order_id)) or die ("get_preorder_byid query fail.");
		return new readonlyresultset($result);
	}
	function delete_preorder($delete_id)
	{
		#eventlog for preorder delete
			$eventlogbol = new eventlogbol();
			$old_values='';
			$new_values_arr = array();
			$new_values_arr['pre_order_id'] = $delete_id;
			$event_result = $eventlogbol->save_eventlog('Delete','tbl_pre_orders',$new_values_arr,$old_values);
			if($event_result)
			{
				$query="DELETE FROM tbl_pre_orders WHERE pre_order_id=:pre_order_id ";
				$result = execute_non_query($query, array(':pre_order_id' => $delete_id));
				return $result;
			}
			else 
				return false;
	}
}
?>