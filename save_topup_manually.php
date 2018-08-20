<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');
	
	$topupinfo = new topupinfo();
	$transactioninfo = new transactioninfo();
	$transactionbol = new transactionbol();
	$login_user_id = $_SESSION ['login_user_id'];
	if(isset($_POST['topup_manual_studentid']))
	{
		$topup_manual_studentid = $_POST['topup_manual_studentid'];
		$topup_manual_amt = $_POST['topup_manual_amt'];
		$topup_pos_receipt_no = $_POST['topup_pos_receipt_no'];
		$payment_type = "Cash";
		$payment_status = "Paid";
		
		//print_r("stu-id" . $topup_manual_studentid);exit();
					
		//check student id is right or wrong
		$count_participant_res=$transactionbol->check_studentid($topup_manual_studentid);
		$aRow = $count_participant_res->getNext();
		$count_participant = $aRow['count_participant'];
		if($count_participant==0)
		{
			echo '<h3 id="h3_msg" style="margin-left:168px;padding-top: 13px;color:red;">Invalid Student ID.Try to topup again.</h3>';
			sleep(5);//sleep for 5 seconds
		}
		else if($count_participant>0)
		{
			$topupinfo->set_topup_amt($topup_manual_amt);
			$topupinfo->set_payment_type($payment_type);
			$topupinfo->set_payment_status($payment_status);
			$topupinfo->set_pos_slip_id($topup_pos_receipt_no);
			$topupinfo->set_login_user_id($login_user_id);
			
			$topup_id=$transactionbol->save_topup_manually($topupinfo);//saving in tbl_topup
			if($topup_id!=null)
			{
				//current date time
				date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
				$now_date_time = date('Y-m-d H:i:s');
				//find card_id student_primary_id and card_id
				$participant_result = $transactionbol->get_participant_data_byusingID($topup_manual_studentid);
				if($participant_result)
				{
					$prow = $participant_result->getNext();
					$pid = $prow['pid'];
					$card_id = $prow['card_id'];
					//saving in tbl_transaction
					$transactioninfo->set_trans_type('topup');
					$transactioninfo->set_card_id($card_id);
					$transactioninfo->set_topup_id($topup_id);
					$transactioninfo->set_transaction_amt($topup_manual_amt);
					$transactioninfo->set_participant_id($pid);
					$transactioninfo->set_transaction_datetime($now_date_time);
					$trans_id=$transactionbol->save_transaction($transactioninfo);
					//print_r("trans id" . $trans_id);exit();
				}
				$json_retrun_arr['success'] = 1;
				$json_retrun_arr['warning'] = 'TopUp amount is saving successfully.';	
			}
			else
			{
				$json_retrun_arr['success'] = 0;
				$json_retrun_arr['warning'] = 'TopUp amount is saving fail.';	
			}
		}
		echo json_encode($json_retrun_arr);
	}

?>

