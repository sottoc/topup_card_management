<?php
	require_once ('library/reference.php');
	require_once ('autoload.php');
	
	$transactionbol = new transactionbol();
	$participant_canteen_ordersinfo = new participant_canteen_ordersinfo();
	$redemptioninfo = new redemptioninfo();
	$transactioninfo = new transactioninfo();
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');
	
	//current date time
	date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
    $now_date_time = date('Y-m-d H:i:s');
	$total_topupamt = 0;
	
	if(isset($_GET['redemption_studentid']))
	{
		$redemption_studentid = $_GET['redemption_studentid'];
		$res = $transactionbol->get_student_data_by_studentID($redemption_studentid);
		if($res->rowCount()>0)
		{
			$row = $res->getNext();
			$std_id = $row['pid'];
			$allow_preorder = $row['allow_preorder'];
			$card_id = $row['card_id'];
			//find topup total amt by student_id
			$topup_total_res = $transactionbol->get_total_topupamt_by_stdid($std_id);
			if($topup_total_res->rowCount()>0)
			{
				$row_topup = $topup_total_res->getNext();
				$total_topupamt = $row_topup['total_topupamt'];
			}
			
			$redemption_total_res = $transactionbol->get_total_redemptionamt_by_stdid($std_id);
			if($redemption_total_res->rowCount()>0)
			{
				$row_redem = $redemption_total_res->getNext();
				$total_redemptionamt = $row_redem['total_redemptionamt'];
			}
			//echo "total_topupamt=".$total_topupamt;
			//echo "total_redemptionamt=".$total_redemptionamt;
			if($total_topupamt > $total_redemptionamt)
				$current_amt= $total_topupamt - $total_redemptionamt;
			else
				$current_amt=0;
			$arr = array('status'=>'success','std_id'=> $std_id ,'allow_preorder'=>$allow_preorder,'current_amt'=>$current_amt,'card_id'=>$card_id);
		}
		else
			$arr = array('status'=>'fail','std_id'=> '' ,'allow_preorder'=>'','current_amt'=>0,'card_id'=>'');
		echo json_encode($arr);
	}
	if(isset($_POST['sel_redem_category_type']))
	{
		$category_type_id = $_POST['sel_redem_category_type'];
		$participant_id = $_POST['hid_std_id'];
		$response = array();
		$total_amt = 0;
		$pre_order_id_list='';
		$res_item = $transactionbol -> get_pre_order($category_type_id,$participant_id);//to check meal_status
		if($res_item->rowCount()>0)
		{
			while($row_item = $res_item->getNext())
			{
				if($pre_order_id_list=='')
					$pre_order_id_list=$row_item['pre_order_id'];
				else
					$pre_order_id_list=$pre_order_id_list.','.$row_item['pre_order_id'];
				$tmp = array();
				$tmp["item_name"] = $row_item['item_name'];
				$tmp["qty"] = $row_item['qty'];
				$tmp["price"] = $row_item['item_price'];
				$mul_res = $row_item['qty']*$row_item['item_price'];
				$tmp['mul_res'] = $mul_res;
				$total_amt = $total_amt + $mul_res;
				$response[] = $tmp;
			}
			echo json_encode(array("status"=>"success","tbl_info"=>$response,"total_amt"=>$total_amt,"pre_order_id_list"=>$pre_order_id_list));
		}
		else
			echo json_encode(array("status"=>"fail","tbl_info"=>'',"total_amt"=>0,"pre_order_id_list"=>''));
	}
	if(isset($_POST['item_id']))
	{
		$item_id = $_POST['item_id'];
		$price_res = $transactionbol -> get_price_byitemid($item_id);
		if($price_res->rowCount()>0)
		{
			$row_price = $price_res->getNext();
			$item_price = $row_price['item_price'];
			$arr = array('status'=>'success','item_id'=> $item_id ,'item_price'=>$item_price);
		}
		else
			$arr = array('status'=>'fail','item_id'=> '' ,'item_price'=>'');
		echo json_encode($arr);
	}
	if(isset($_POST['student_enroll_number']))
	{
		$student_enroll_number = $_POST['student_enroll_number'];
		$sel_meal_type = $_POST['sel_meal_type'];
		$hid_items_list = $_POST['hid_items_list'];
		$canteen_order_total_costs = $_POST['canteen_order_total_costs'];
		//Eg:student_enroll_number=k0012	sel_meal_type=-1	hid_items_list=1@#@2,3@#@2,0
		
		//find student_id and card_id from student_enroll_number
		$stu_res = $transactionbol->get_student_data_by_studentID($student_enroll_number);
		if($stu_res->rowCount()>0)
		{
			$stu_row = $stu_res->getNext();
			$stu_id = (int)$stu_row['pid'];
			$card_id = $stu_row['card_id'];
			if($card_id=='')
				$card_id = NULL;
			
			//check current balance is zero or not
			//find topup total amt by student_id
			$topup_total_res = $transactionbol->get_total_topupamt_by_stdid($stu_id);
			if($topup_total_res->rowCount()>0)
			{
				$row_topup = $topup_total_res->getNext();
				$total_topupamt = $row_topup['total_topupamt'];
			}
			
			$redemption_total_res = $transactionbol->get_total_redemptionamt_by_stdid($stu_id);
			if($redemption_total_res->rowCount()>0)
			{
				$row_redem = $redemption_total_res->getNext();
				$total_redemptionamt = $row_redem['total_redemptionamt'];
			}
			//echo "total_topupamt=".$total_topupamt;
			//echo "total_redemptionamt=".$total_redemptionamt;
			//echo "canteen_order_total_costs=".$canteen_order_total_costs;
			if($total_topupamt > $total_redemptionamt)
				$current_amt= $total_topupamt - $total_redemptionamt;
			else
				$current_amt=0;
			//echo "current_amt=".$current_amt;	
			if($current_amt>0)
			{
				if($canteen_order_total_costs>$current_amt)
				{
					echo "Need to fill topup.Current Balance is not enough to pay.";
				}
				else
				{
					//find item_id and qty from hid_items_list
					$item_arr = explode(",",$hid_items_list);
					$save_count=0;
					foreach($item_arr as $key=>$value)
					{
						if($value!=0)
						{
							//save in tbl_participant_canteen_orders (participant_id,card_id,now_date,item_id,qty,sel_meal_type)
							$value_arr = explode("@#@",$value);
							$item_id = $value_arr[0];
							$qty = $value_arr[1];
							
							//find item_price by itemid
							$price_res = $transactionbol->get_price_byitemid($item_id);
							$price_row = $price_res->getNext();
							$item_price = $price_row['item_price'];
							$redempation_amt =  $item_price * $qty;
							
							$staff_id = $_SESSION ['login_user_id'];
							
							$participant_canteen_ordersinfo->set_card_id($card_id);
							$participant_canteen_ordersinfo->set_participant_id($stu_id);
							$participant_canteen_ordersinfo->set_participant_canteen_order_datetime($now_date_time);
							$participant_canteen_ordersinfo->set_item_id($item_id);
							$participant_canteen_ordersinfo->set_qty($qty);
							$participant_canteen_ordersinfo->set_category_type_id($sel_meal_type);
							$participant_canteen_orders_id = $transactionbol->save_canteen_orders($participant_canteen_ordersinfo);
							if($participant_canteen_orders_id)
							{
								//save in tbl_redemption(participant_canteen_order_id,redemption_amt,session user's id)
								$redemptioninfo->set_redemption_amt($redempation_amt);
								$redemptioninfo->set_user_id($staff_id);
								$redemptioninfo->set_participant_canteen_order_id($participant_canteen_orders_id);
								$redemption_id = $transactionbol->save_canteen_orders_redemption($redemptioninfo);
								if($redemption_id)
								{
									//save in tbl_transaction(trans_type=redemption,card_id,redemption_id,transaction_amt,participant_id,now_date)
									$transactioninfo->set_trans_type('redemption');
									$transactioninfo->set_card_id($card_id);
									$transactioninfo->set_redempation_id($redemption_id);
									$transactioninfo->set_transaction_amt($redempation_amt);
									$transactioninfo->set_participant_id($stu_id);
									$transactioninfo->set_transaction_datetime($now_date_time);
									$trans_id=$transactionbol->save_transaction_preorder_redempation($transactioninfo);//this function can also use for canteen order redemption
									if($trans_id)
									{
										$save_count++;
										//to change meal_status in preorder table
										$change_meal_status_res = $transactionbol->update_meal_status($value);
									}
								}
							}
						}
					}
					if($save_count>0)
						echo "Redemption is successfully";
					else if($save_count==0)
						echo "Redemption is not successfully";
				}
			}
			else
			{
				echo "Need to fill topup.Your current amount is zero";
			}
		}
		else
			echo "Invalid student ID.";
	}
?>