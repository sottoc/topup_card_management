<?php
class transactiondal{
	function save_topup_manually($topupinfo)
	{
		$topup_amt= $topupinfo->get_topup_amt();
		$payment_type= $topupinfo->get_payment_type();
		$payment_status= $topupinfo->get_payment_status();
		$pos_slip_id= $topupinfo->get_pos_slip_id();
		$login_user_id = $topupinfo->get_login_user_id();

		$query ="INSERT INTO  tbl_topup (topup_amt,payment_type,payment_status,pos_slip_id,login_user_id) VALUES (:topup_amt,:payment_type,:payment_status,:pos_slip_id,:login_user_id) ";
		$param = array(
		':topup_amt'=>$topup_amt,
		':payment_type'=>$payment_type,
		':payment_status'=>$payment_status,
		':pos_slip_id'=>$pos_slip_id,
		':login_user_id'=>$login_user_id
		);
		$result = execute_query($query,$param) or die ("save_topup_manually query fail.");
		if($result)
		{
			#eventlog for topup manually save
			$topup_id = last_instert_id();
			$eventlogbol = new eventlogbol();
			$old_values='';
			$new_values_arr = array();
			$new_values_arr['topup_id'] = $topup_id;
			$new_values_arr['topup_amt'] = $topup_amt;
			$new_values_arr['payment_type'] = $payment_type;
			$new_values_arr['payment_status'] = $payment_status;
			$new_values_arr['pos_slip_id'] = $pos_slip_id;
			$new_values_arr['login_user_id'] = $login_user_id;
			$event_result = $eventlogbol->save_eventlog('Insert','tbl_topup',$new_values_arr,$old_values);
			return $topup_id;	
		}
		else 
			return false;
	}
	function get_participant_data_byusingID($topup_manual_studentid)
	{
		$query = "SELECT *,p.participant_id as pid from tbl_participant p left join tbl_card c on p.participant_id=c.participant_id where p.participant_enroll_no=:participant_enroll_no";
		$result = execute_query($query,array(':participant_enroll_no'=>$topup_manual_studentid)) or die ("get_participant_data_byusingID query fail.");
		return new readonlyresultset($result); 
	}
	function save_transaction($transactioninfo)
	{
		$trans_type= $transactioninfo->get_trans_type();
		$card_id= $transactioninfo->get_card_id();
		$topup_id= $transactioninfo->get_topup_id();
		$transaction_amt= $transactioninfo->get_transaction_amt();
		$participant_id= $transactioninfo->get_participant_id();
		$transaction_datetime= $transactioninfo->get_transaction_datetime();
		
		$query ="INSERT INTO  tbl_transaction (trans_type,card_id,topup_id,transaction_amt,participant_id,transaction_datetime) VALUES (:trans_type,:card_id,:topup_id,:transaction_amt,:participant_id,:transaction_datetime)";
		$param = array(
		':trans_type'=>$trans_type,
		':card_id'=>$card_id,
		':topup_id'=>$topup_id,
		':transaction_amt'=>$transaction_amt,
		':participant_id'=>$participant_id,
		':transaction_datetime'=>$transaction_datetime
		);
		$result = execute_query($query,$param) or die ("save_transaction query fail.");
		if($result)
		{
			#eventlog for topup manually save
			$trans_id = last_instert_id();
			$eventlogbol = new eventlogbol();
			$old_values='';
			$new_values_arr = array();
			$new_values_arr['trans_id'] = $trans_id;
			$new_values_arr['trans_type'] = $trans_type;
			$new_values_arr['card_id'] = $card_id;
			$new_values_arr['topup_id'] = $topup_id;
			$new_values_arr['transaction_amt'] = $transaction_amt;
			$new_values_arr['participant_id'] = $participant_id;
			$new_values_arr['transaction_datetime'] = $transaction_datetime;
			$event_result = $eventlogbol->save_eventlog('Insert','tbl_transaction->maually topup',$new_values_arr,$old_values);
			return $trans_id;	
		}
		else 
			return false;
	}
	function get_all_category_type()
	{
		$query = "SELECT * from tbl_category_type";
		$result = execute_query($query) or die ("get_all_category_type query fail.");
		return new readonlyresultset($result); 
	}
	function get_student_data_by_studentID($redemption_studentid)
	{
		$query = "SELECT *,p.participant_id as pid from tbl_participant p left join tbl_card c on p.participant_id=c.participant_id where participant_enroll_no=:participant_enroll_no";
		$result = execute_query($query,array(':participant_enroll_no'=>$redemption_studentid)) or die ("get_student_data_by_studentID query fail.");
		return new readonlyresultset($result); 
	}
	function get_pre_order($category_type_id,$participant_id)
	{
		$query = "SELECT * from tbl_pre_orders o 
					LEFT JOIN tbl_participant p on o.participant_id=p.participant_id
					LEFT JOIN tbl_items i on o.item_id=i.item_id
					LEFT JOIN tbl_category_type ct on o.category_type_id=ct.category_type_id
					where o.participant_id=:participant_id and o.category_type_id=:category_type_id and preorder_date=CURDATE() and o.meal_status_id=2";
		$params = array(
		':participant_id'=>$participant_id,
		':category_type_id'=>$category_type_id
		);
		$result = execute_query($query,$params) or die ("get_pre_order query fail.");
		return new readonlyresultset($result);
	}
	function get_total_topupamt_by_stdid($std_id)
	{
		$query = "SELECT sum(transaction_amt) as  total_topupamt from tbl_transaction where participant_id=:participant_id and trans_type='topup'";
		$result = execute_query($query,array(':participant_id'=>$std_id)) or die ("get_total_topupamt_by_stdid query fail.");
		return new readonlyresultset($result);
	}
	function get_total_redemptionamt_by_stdid($std_id)
	{
		$query = "SELECT sum(transaction_amt) as  total_redemptionamt from tbl_transaction where participant_id=:participant_id and trans_type='redemption'";
		$result = execute_query($query,array(':participant_id'=>$std_id)) or die ("get_total_redemptionamt_by_stdid query fail.");
		return new readonlyresultset($result);
	}
	function get_preorder_data_byusing_preorderid($value)
	{
		$value=(int)$value;
		$query = "SELECT * from tbl_pre_orders o LEFT JOIN tbl_items i on o.item_id=i.item_id where o.pre_order_id=:pre_order_id";
		$result = execute_query($query,array(':pre_order_id'=>$value)) or die ("get_preorder_data_byusing_preorderid query fail.");
		return new readonlyresultset($result);
	}
	function save_preorder_redemption($redemptioninfo)
	{
		$redemption_amt= $redemptioninfo->get_redemption_amt();
		$pre_order_id= $redemptioninfo->get_pre_order_id();
		$staff_id = $redemptioninfo->get_user_id();
		
		$query ="INSERT INTO  tbl_redemption (redemption_amt,user_id,pre_order_id) VALUES (:redemption_amt,:user_id,:pre_order_id) ";
		$param = array(
		':redemption_amt'=>$redemption_amt,
		':user_id'=>$staff_id,
		':pre_order_id'=>$pre_order_id
		);
		$result = execute_query($query,$param) or die ("save_topup_manually query fail.");
		if($result)
		{
			#eventlog for preorder redemption save
			$redemption_id = last_instert_id();
			$eventlogbol = new eventlogbol();
			$old_values='';
			$new_values_arr = array();
			$new_values_arr['redemption_id'] = $redemption_id;
			$new_values_arr['redemption_amt'] = $redemption_amt;
			$new_values_arr['staff_id'] = $staff_id;
			$new_values_arr['pre_order_id'] = $pre_order_id;
			$event_result = $eventlogbol->save_eventlog('Insert','tbl_redemption',$new_values_arr,$old_values);
			return $redemption_id;	
		}
		else 
			return false;
	}
	function save_transaction_preorder_redempation($transactioninfo)
	{
		$trans_type= $transactioninfo->get_trans_type();
		$card_id= $transactioninfo->get_card_id();
		if($card_id=='') $card_id=NULL;
		$redempation_id= $transactioninfo->get_redempation_id();
		$transaction_amt= $transactioninfo->get_transaction_amt();
		$participant_id= $transactioninfo->get_participant_id();
		$transaction_datetime= $transactioninfo->get_transaction_datetime();
		
		$query ="INSERT INTO  tbl_transaction (trans_type,card_id,redempation_id,transaction_amt,participant_id,transaction_datetime) VALUES (:trans_type,:card_id,:redempation_id,:transaction_amt,:participant_id,:transaction_datetime)";
		$param = array(
		':trans_type'=>$trans_type,
		':card_id'=>$card_id,
		':redempation_id'=>$redempation_id,
		':transaction_amt'=>$transaction_amt,
		':participant_id'=>$participant_id,
		':transaction_datetime'=>$transaction_datetime
		);
		
		$result = execute_query($query,$param) or die ("save_transaction_preorder_redempation query fail.");
		if($result)
		{
			#eventlog for topup manually save
			$trans_id = last_instert_id();
			$eventlogbol = new eventlogbol();
			$old_values='';
			$new_values_arr = array();
			$new_values_arr['trans_id'] = $trans_id;
			$new_values_arr['trans_type'] = $trans_type;
			$new_values_arr['card_id'] = $card_id;
			$new_values_arr['redempation_id'] = $redempation_id;
			$new_values_arr['transaction_amt'] = $transaction_amt;
			$new_values_arr['participant_id'] = $participant_id;
			$new_values_arr['transaction_datetime'] = $transaction_datetime;
			$event_result = $eventlogbol->save_eventlog('Insert','tbl_transaction',$new_values_arr,$old_values);
			return $trans_id;	
		}
		else 
			return false;
	}
	function check_studentid($student_enroll_number)
	{
		$query="SELECT count(participant_id) as count_participant from tbl_participant where participant_enroll_no=:participant_enroll_no";
		$result = execute_query($query,array(':participant_enroll_no'=>$student_enroll_number)) or die ("check_studentid query fail.");
		return new readonlyresultset($result);
	}
	function update_meal_status($value)
	{
		#eventlog for meal status update
		$eventlogbol = new eventlogbol();
		$old_values="meal_status_id=2";
		$new_values_arr = array();
		$new_values_arr['meal_status_id'] = 1;
		$new_values_arr['pre_order_id'] = $value;
		$event_result = $eventlogbol->save_eventlog('Update','tbl_pre_orders',$new_values_arr,$old_values);
		
		if($event_result)
		{
			$query="UPDATE  tbl_pre_orders SET meal_status_id=1 WHERE pre_order_id= :pre_order_id; ";		
			$param = array(':pre_order_id'=>$value);
			$result = execute_non_query($query,$param);
			return $result;
		}
		else 
			return false;
	}
	function get_price_byitemid($item_id)
	{
		$query="SELECT * from tbl_items where item_id=:item_id";
		$result = execute_query($query,array(':item_id'=>$item_id)) or die ("get_price_byitemid query fail.");
		return new readonlyresultset($result);
	}
	function get_card_data_by_participant_id($p_id)
	{
		$query="SELECT * from tbl_card where participant_id=:participant_id";
		$result = execute_query($query,array(':participant_id'=>$p_id)) or die ("get_card_data_by_participant_id query fail.");
		return new readonlyresultset($result);
	}
	function save_canteen_orders($participant_canteen_ordersinfo)
	{
		$card_id= $participant_canteen_ordersinfo->get_card_id();
		if($card_id=='') $card_id=NULL;
		$participant_id= $participant_canteen_ordersinfo->get_participant_id();
		$participant_canteen_order_datetime= $participant_canteen_ordersinfo->get_participant_canteen_order_datetime();
		$item_id= $participant_canteen_ordersinfo->get_item_id();
		$qty= $participant_canteen_ordersinfo->get_qty();
		$category_type_id= $participant_canteen_ordersinfo->get_category_type_id();
		
		$query ="INSERT INTO  tbl_participant_canteen_orders (card_id,participant_id,participant_canteen_order_datetime,item_id,qty,category_type_id) VALUES (:card_id,:participant_id,:participant_canteen_order_datetime,:item_id,:qty,:category_type_id)";
		$param = array(
		':card_id'=>$card_id,
		':participant_id'=>$participant_id,
		':participant_canteen_order_datetime'=>$participant_canteen_order_datetime,
		':item_id'=>$item_id,
		':qty'=>$qty,
		':category_type_id'=>$category_type_id
		);
		
		$result = execute_query($query,$param) or die ("save_canteen_orders query fail.");
		if($result)
		{
			#eventlog for topup manually save
			$participant_canteen_orders_id = last_instert_id();
			$eventlogbol = new eventlogbol();
			$old_values='';
			$new_values_arr = array();
			$new_values_arr['participant_canteen_orders_id'] = $participant_canteen_orders_id;
			$new_values_arr['card_id'] = $card_id;
			$new_values_arr['participant_id'] = $participant_id;
			$new_values_arr['participant_canteen_order_datetime'] = $participant_canteen_order_datetime;
			$new_values_arr['item_id'] = $item_id;
			$new_values_arr['qty'] = $qty;
			$new_values_arr['category_type_id'] = $category_type_id;
			$event_result = $eventlogbol->save_eventlog('Insert','tbl_participant_canteen_orders',$new_values_arr,$old_values);
			return $participant_canteen_orders_id;	
		}
		else 
			return false;
	}
	function save_canteen_orders_redemption($redemptioninfo)
	{
		$redemption_amt= $redemptioninfo->get_redemption_amt();
		$user_id= $redemptioninfo->get_user_id();
		$participant_canteen_order_id= $redemptioninfo->get_participant_canteen_order_id();
				
		$query ="INSERT INTO  tbl_redemption (redemption_amt,user_id,participant_canteen_order_id) VALUES (:redemption_amt,:user_id,:participant_canteen_order_id)";
		$param = array(
		':redemption_amt'=>$redemption_amt,
		':user_id'=>$user_id,
		':participant_canteen_order_id'=>$participant_canteen_order_id
		);
		
		$result = execute_query($query,$param) or die ("save_canteen_orders_redemption query fail.");
		if($result)
		{
			#eventlog for topup manually save
			$redemption_id = last_instert_id();
			$eventlogbol = new eventlogbol();
			$old_values='';
			$new_values_arr = array();
			$new_values_arr['redemption_amt'] = $redemption_amt;
			$new_values_arr['user_id'] = $user_id;
			$new_values_arr['participant_canteen_order_id'] = $participant_canteen_order_id;
			$event_result = $eventlogbol->save_eventlog('Insert','tbl_redemption',$new_values_arr,$old_values);
			return $redemption_id;	
		}
		else 
			return false;
	}
	function delete_redemption_by_preorderid($pre_order_id)
	{
		#eventlog for redemption delete
		$eventlogbol = new eventlogbol();
		$old_values='';
		$new_values_arr = array();
		$new_values_arr['pre_order_id'] = $pre_order_id;
		$event_result = $eventlogbol->save_eventlog('Delete','tbl_redemption',$new_values_arr,$old_values);
		if($event_result)
		{
			$query="DELETE FROM tbl_redemption WHERE pre_order_id=:pre_order_id ";
			$result = execute_non_query($query, array(':pre_order_id' => $pre_order_id));
			return $result;
		}
		else 
			return false;
	}
	function get_all_redemption_ids_by_preorderid($pre_order_id)
	{
		$query="SELECT * from tbl_redemption where pre_order_id=:pre_order_id";
		$result = execute_query($query,array(':pre_order_id'=>$pre_order_id)) or die ("get_all_redemption_ids_by_preorderid query fail.");
		return new readonlyresultset($result);
	}
	function delete_transaction_by_redemptionid($redemption_ids)
	{
		#eventlog for redemption delete
		$eventlogbol = new eventlogbol();
		$old_values='';
		$new_values_arr = array();
		$new_values_arr['redemption_ids'] = $redemption_ids;
		$event_result = $eventlogbol->save_eventlog('Delete','tbl_transaction',$new_values_arr,$old_values);
		if($event_result)
		{
			$query="DELETE FROM tbl_transaction WHERE redempation_id in ($redemption_ids)";		
			$result = execute_non_query($query);
			return $result;
		}
		else 
			return false;
	}
	function save_payment_detail($transactioninfo)
	{
		$transaction_id= $transactioninfo->get_transaction_id();
		$topup_id= $transactioninfo->get_topup_id();
		$receiver_email= $transactioninfo->get_receiver_email();
		$paypal_status= $transactioninfo->get_paypal_status();
		$payer_email= $transactioninfo->get_payer_email();
		$payment_amount= $transactioninfo->get_payment_amount();
		$paypal_return_data= $transactioninfo->get_paypal_return_data();
		
		$query ="INSERT INTO  tbl_payment_detail (transaction_id,topup_id,receiver_email,payer_email,payment_amount,paypal_status,paypal_return_data) VALUES (:transaction_id,:topup_id,:receiver_email,:payer_email,:payment_amount,:paypal_status,:paypal_return_data)";
		$param = array(
		':transaction_id'=>$transaction_id,
		':topup_id'=>$topup_id,
		':receiver_email'=>$receiver_email,
		':paypal_status'=>$paypal_status,
		':payer_email'=>$payer_email,
		':payment_amount'=>$payment_amount,
		':paypal_return_data'=>$paypal_return_data
		);
		$result = execute_query($query,$param) or die ("save_payment_detail query fail.");
		if($result)
		{
			#eventlog for topup manually save
			$payment_detail_id = last_instert_id();
			$eventlogbol = new eventlogbol();
			$old_values='';
			$new_values_arr = array();
			$new_values_arr['payment_detail_id'] = $payment_detail_id;
			$new_values_arr['transaction_id'] = $transaction_id;
			$new_values_arr['topup_id'] = $topup_id;
			$new_values_arr['receiver_email'] = $receiver_email;
			$new_values_arr['paypal_status'] = $paypal_status;
			$new_values_arr['payer_email'] = $payer_email;
			$new_values_arr['payment_amount'] = $payment_amount;
			$new_values_arr['paypal_return_data'] = $paypal_return_data;
			$event_result = $eventlogbol->save_eventlog('Insert','tbl_payment_detail->paypal',$new_values_arr,$old_values);
			return $payment_detail_id;	
		}
		else 
			return false;
	}
}
?>