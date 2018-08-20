<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
	{ 
		session_start(); 
	}
	require_once('userauth.php');
	
	//bol
	$setupbol = new setupbol();
	$orderbol = new orderbol();
	$transactionbol = new transactionbol();
	$localizationbol= new localizationbol();
	
	//common info
	$pre_ordersinfo= new pre_ordersinfo();
	$redemptioninfo= new redemptioninfo();
	$transactioninfo= new transactioninfo();
	
	//localization
	$localized_home_result=$localizationbol->get_localization_by_pagename('home',1);
	while($row=$localized_home_result->getNext())
	{
		$localized_home_data[$row['localization_name']]=$row['detail'];
	}
	
	$localized_result=$localizationbol->get_localization_by_pagename('pre_order',1);
	while($row=$localized_result->getNext())
	{
		$localized_data[$row['localization_name']]=$row['detail'];
	}
	
	//current date time
	date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
	$now_date_time = date('Y-m-d H:i:s');
	
	$login_user_id = $_SESSION ['login_user_id']; //organizer or parent_id
	$status_array = array();
	if(isset($_POST['action_type']))
	{
		$delete_transaction_res=false;
		$total_topupamt=0;
		$total_redemptionamt=0;
		$total_preorder_amt=0;
		$action_type = $_POST['action_type'];
		$selected_date=$_POST['selected_date'];
		$p_id=(int)$_POST['p_id'];
		$item_id=(int)$_POST['item_id'];
		$category_type_id=(int)$_POST['category_type_id'];
		$category_type_name=$_POST['category_type_name'];
		$pre_order_id = (int)$_POST['pre_order_id'];
		$qty=(int)$_POST['qty'];
		$staff_id = $_SESSION ['login_user_id'];
		
		//find student's card data
		$card_id = NULL;
		$card_res = $transactionbol->get_card_data_by_participant_id($p_id);
		if($card_res->rowCount()>0)
		{
			$crow = $card_res->getNext();
			$card_id = $crow['card_id'];
			if($card_id=='')
				$card_id = NULL;
		}
		//echo "card id=".$card_id;
		
		//find unit price and total_preorder_amt
		$item_price=0;
		$item_price_res = $transactionbol->get_price_byitemid($item_id);
		if($item_price_res->rowCount()>0)
		{
			$row_price = $item_price_res->getNext();
			$item_price = $row_price['item_price'];
		}
		$total_preorder_amt=$item_price*$qty;
		//echo "<br/>total_preorder_amt=".$total_preorder_amt;
		
		//find current balance
		$topup_total_res = $transactionbol->get_total_topupamt_by_stdid($p_id);
		if($topup_total_res->rowCount()>0)
		{
			$row_topup = $topup_total_res->getNext();
			$total_topupamt = $row_topup['total_topupamt'];
		}
		
		$redemption_total_res = $transactionbol->get_total_redemptionamt_by_stdid($p_id);
		if($redemption_total_res->rowCount()>0)
		{
			$row_redem = $redemption_total_res->getNext();
			$total_redemptionamt = $row_redem['total_redemptionamt'];
		}
		//echo "<br/>total_topupamt=".$total_topupamt;
		//echo "<br/>total_redemptionamt=".$total_redemptionamt;
		
		if($total_topupamt > $total_redemptionamt)
			$current_amt= $total_topupamt - $total_redemptionamt;
		else
			$current_amt=0;
		//echo "<br/>current_amt=".$current_amt;
		
		if($current_amt>0)
		{
			if($total_preorder_amt>$current_amt)
			{
				$status_array['status'] = 'fail';
				$status_array['msg'] = 'Need to fill topup.Current Balance is not enough to preorder food.';
			}
			else
			{
				$pre_ordersinfo->set_card_id($card_id);
				$pre_ordersinfo->set_participant_id($p_id);
				$pre_ordersinfo->set_preorder_date($selected_date);
				$pre_ordersinfo->set_item_id($item_id);
				$pre_ordersinfo->set_qty($qty);
				$pre_ordersinfo->set_category_type_id($category_type_id);
				$pre_ordersinfo->set_meal_status_id(2);//2 is not finish
				$pre_ordersinfo->set_modified_datetime($now_date_time);
				if($action_type == 'add_new_preorder')
				{
					//save in tbl_pre_order
					$pre_ordersinfo->set_created_datetime($now_date_time);
					$pre_ordersinfo->set_modified_datetime($now_date_time);
					$pre_order_id=$orderbol->save_preorder($pre_ordersinfo);
					//echo "<br/>pre_order_id=".$pre_order_id;
					if($pre_order_id!=null)
					{
						//save in tbl_redemption(pre_order_id,redemption_amt,session user's id)
						$redemptioninfo->set_redemption_amt($total_preorder_amt);
						$redemptioninfo->set_user_id($staff_id);
						$redemptioninfo->set_pre_order_id($pre_order_id);
						$redemption_id = $transactionbol->save_preorder_redemption($redemptioninfo);
						if($redemption_id)
						{
							//save in tbl_transaction(trans_type=redemption,card_id,redemption_id,transaction_amt,participant_id,now_date)
							$transactioninfo->set_trans_type('redemption');
							$transactioninfo->set_card_id($card_id);
							$transactioninfo->set_redempation_id($redemption_id);
							$transactioninfo->set_transaction_amt($total_preorder_amt);
							$transactioninfo->set_participant_id($p_id);
							$transactioninfo->set_transaction_datetime($now_date_time);
							$trans_id=$transactionbol->save_transaction_preorder_redempation($transactioninfo);
							if($trans_id)
							{
								$status_array['status'] = 'success';
								$status_array['msg'] = 'Saved Successfully.';
							}
						}
					}
					else
					{	
						$status_array['status'] = 'fail';
						$status_array['msg'] = 'Saved Fail.';
					}
				}
				else if($action_type == 'update_preorder')
				{
					$pre_ordersinfo->set_pre_order_id($pre_order_id);
				
					$old_result=$orderbol->get_preorder_byid($pre_order_id);
					$oldrow=$old_result->getNext();
					
					$old_item_value_str="pre_order_id=>".$oldrow['pre_order_id'].",participant_id=>".$oldrow['participant_id'].",preorder_date=>".$oldrow['preorder_date'].",item_id=>".$oldrow['item_id'].",category_type_id=>".$oldrow['category_type_id'].",qty=>".$oldrow['qty'];
					
					//check finish eat or not
					$meal_status = $oldrow['meal_status_id'];
					if($meal_status!=1)//not equal to finish(Finish is 1)
					{
						//delete transactions
						$redemption_ids_res = $transactionbol->get_all_redemption_ids_by_preorderid($pre_order_id);
						if($redemption_ids_res)
						{
							$redemption_ids='';
							while($redemption_ids_row = $redemption_ids_res->getNext())
							{
								if($redemption_ids == '')
									$redemption_ids = $redemption_ids_row['redemption_id'];
								else
									$redemption_ids = $redemption_ids.",'".$redemption_ids_row['redemption_id']."'";
							}
						}
						
						if($redemption_ids!='')
							$delete_transaction_res = $transactionbol->delete_transaction_by_redemptionid($redemption_ids);
						if($delete_transaction_res==true)
						{
							//delete redemption
							$delete_redemption_res = $transactionbol->delete_redemption_by_preorderid($pre_order_id);
							if($delete_redemption_res)
							{
								//update preorder
								$update_preorder_result = $orderbol->update_preorder($pre_ordersinfo,$old_item_value_str);
								if($update_preorder_result)
								{
									//add redemption
									//save in tbl_redemption(pre_order_id,redemption_amt,session user's id)
									$redemptioninfo->set_redemption_amt($total_preorder_amt);
									$redemptioninfo->set_user_id($staff_id);
									$redemptioninfo->set_pre_order_id($pre_order_id);
									$redemption_id = $transactionbol->save_preorder_redemption($redemptioninfo);
									if($redemption_id)
									{
										//add transaction
										//save in tbl_transaction(trans_type=redemption,card_id,redemption_id,transaction_amt,participant_id,now_date)
										$transactioninfo->set_trans_type('redemption');
										$transactioninfo->set_card_id($card_id);
										$transactioninfo->set_redempation_id($redemption_id);
										$transactioninfo->set_transaction_amt($total_preorder_amt);
										$transactioninfo->set_participant_id($p_id);
										$transactioninfo->set_transaction_datetime($now_date_time);
										$trans_id=$transactionbol->save_transaction_preorder_redempation($transactioninfo);
										if($trans_id)
										{
											$status_array['status'] = 'success';
											$status_array['msg'] = 'Saved Successfully.';
										}
										else
										{	
											$status_array['status'] = 'fail';
											$status_array['msg'] = 'Updated Fail.';
										}
									}
									else
									{	
										$status_array['status'] = 'fail';
										$status_array['msg'] = 'Updated Fail.';
									}
								}
							}
						}
						else
						{	
							$status_array['status'] = 'fail';
							$status_array['msg'] = 'Updated Fail.';
						}
					}
					else
					{
						$status_array['status'] = 'fail';
						$status_array['msg'] = 'Cannot edit this pre order.This pre order is already finished.';
					}
				}
			}
		}
		else
		{
			$status_array['status'] = 'fail';
			$status_array['msg'] = 'Need to fill topup.Your current amount is zero';
		}
	}
	if(isset($_GET['delete_id']))
	{
		//delete
		//check meal status to delete
		$delete_transaction_res=false;
		$chk_del_result = $orderbol->get_preorder_byid($_GET['delete_id']);
		$chk_del_row = $chk_del_result->getNext();
		$meal_status = $chk_del_row['meal_status_id'];
		if($meal_status!=1)//not equal to finish(Finish is 1)
		{
			//delete transactions
			$redemption_ids_res = $transactionbol->get_all_redemption_ids_by_preorderid($_GET['delete_id']);
			if($redemption_ids_res)
			{
				$redemption_ids='';
				while($redemption_ids_row = $redemption_ids_res->getNext())
				{
					if($redemption_ids == '')
						$redemption_ids = $redemption_ids_row['redemption_id'];
					else
						$redemption_ids = $redemption_ids.",'".$redemption_ids_row['redemption_id']."'";
				}
			}
			//echo "redemption_ids=".$redemption_ids;
			if($redemption_ids!='')
				$delete_transaction_res = $transactionbol->delete_transaction_by_redemptionid($redemption_ids);
			else//only having data in preorder table
			{
				$delete_result = $orderbol->delete_preorder($_GET['delete_id']);
				if($delete_result){
					$status_array['status'] = 'success';
					$status_array['msg'] = 'Deleted Successfully.';
				}
				else
				{	
					$status_array['status'] = 'fail';
					$status_array['msg'] = 'Deleted Fail.';
				}
			}
			if($delete_transaction_res==true)
			{
				//delete redemption
				$delete_redemption_res = $transactionbol->delete_redemption_by_preorderid($_GET['delete_id']);
				if($delete_redemption_res)
				{
					//delete preorder
					$delete_result = $orderbol->delete_preorder($_GET['delete_id']);
					if($delete_result){
						$status_array['status'] = 'success';
						$status_array['msg'] = 'Deleted Successfully.';
					}
					else
					{	
						$status_array['status'] = 'fail';
						$status_array['msg'] = 'Deleted Fail.';
					}
				}
			}
		}
		else
		{
			$status_array['status'] = 'fail';
			$status_array['msg'] = 'Cannot delete this pre order.This pre order is already finished.';
		}
		
	}
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
	header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
	header("Cache-Control: no-cache, must-revalidate" );
	header("Pragma: no-cache" );
	header("Content-type: text/x-json");
	//var_dump($status_array);exit();
	echo json_encode($status_array);
?>