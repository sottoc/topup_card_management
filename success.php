<?php
/*
Array ( [transaction_subject] => [txn_type] => web_accept [payment_date] => 01:05:08 Dec 11, 2016 PST [last_name] => buyer [residence_country] => US [pending_reason] => unilateral [item_name] => PHPGang Payment [payment_gross] => [mc_currency] => SGD [payment_type] => instant [protection_eligibility] => Ineligible [payer_status] => verified [verify_sign] => AFcWxV21C7fd0v3bYYYRCpSSRl31ASScnEHpWBJchveY63QJVhS-Z-np [tax] => 0.00 [test_ipn] => 1 [payer_email] => ydn.smile-buyer-1@gmail.com [txn_id] => 01554132M8763043N [quantity] => 1 [receiver_email] => ydn.smile-facilitator-1@gmail.com [first_name] => test [invoice] => aa002 [payer_id] => QJD7KM7XCE9HL [item_number] => 1 [handling_amount] => 0.00 [payment_status] => Pending [shipping] => 0.00 [mc_gross] => 10.00 [custom] => [charset] => windows-1252 [notify_version] => 3.8 [auth] => AxZZt0CdMcBZ6g1QoFe8eicLFqORZD1NOUdZGJFc3fupGfMVdbQCSI7zENOBNnH.bcVYPGB-24NsLGwuVT0Jc4g ) 
*/
//print_r($_REQUEST);
	require_once('library/reference.php');
	require_once('autoload.php');
	require_once('header.php');
	$transactionbol = new transactionbol();
	$topupinfo = new topupinfo();
	$payment_detailinfo = new payment_detailinfo();
	$transactioninfo = new transactioninfo();
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');
	
	$send_mail_result = false;
	$login_user_id = $_SESSION ['login_user_id'];
	$student_name = $_REQUEST['item_name'];
	$student_id = $_REQUEST['item_number'];
	$transaction_id = $_REQUEST['txn_id'];  // Paypal transaction ID
	$amount = $_REQUEST['mc_gross']; 	   // Paypal received amount
	$currency = $_REQUEST['mc_currency']; // Paypal received currency type
	$paypal_payment_status = $_REQUEST['payment_status'];
	$payer_email = $_REQUEST['payer_email'];
	//$receiver_email = $_REQUEST['receiver_email'];
	$paypal_return_arr = $_REQUEST;
	$str='';
	foreach ($paypal_return_arr as $key => $value)
	{
		if($str=='')
			$str = $key.'='.$value;
		else
			$str=$str.','.$key.'='.$value;
	}
	$paypal_return_data = $str;

	//---------------------------------- code by Qiang -----------------------------
	$user_email = $_SESSION ['login_user_email'];

	//---------------- save in table --------------
	require_once('api/api_common.php');
	$uploadPath = $rootpath.'/upload/profile';
	$query = "SELECT `family_code` FROM `tbl_user` WHERE `user_email`='".$user_email."'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $family_code = $row['family_code'];
        }
	}
	//check bonus for box
	$box_id = $student_id;
	$query = "SELECT * FROM tbl_topup_box WHERE box_id = '".$box_id."'";
	$result = $conn->query($query);
	if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
			$bonus_value = $row['bonus_value'];
			$limit_times = $row['limit_times'];
			$datetime_to = date($row['datetime_to']);
			$group_id = $row['group_id'];
		}
	}
	if($bonus_value != '0'){
		date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
		$time_now = date("Y-m-d h:i:s");
		if($time_now < $datetime_to) {
			$query = "SELECT * FROM tbl_topup_limit_record WHERE family_code = '".$family_code."' AND group_id = '".$group_id."'";
			$result = $conn->query($query);
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					$limit_used = $row['limit_used'];
					if((int)$limit_used < (int)$limit_times){
						$limit_used = (int)$limit_used + 1;
						$query1 = "UPDATE `tbl_topup_limit_record` SET `limit_used`=".$limit_used." WHERE family_code = '".$family_code."' AND group_id = '".$group_id."'";
						$result1 = $conn->query($query1);
					} else if((int)$limit_used == (int)$limit_times){
						$bonus_value = '0';
					}
				}
			} else{
				$limit_used = 1;
				$query2 = "INSERT INTO `tbl_topup_limit_record` (`family_code`, `group_id`, `limit_used`) VALUES ('".$family_code."','".$group_id."','".$limit_used."')";
				$result2 = $conn->query($query2);
			}
		}
	}
	date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
	$time = date("Y-m-d H:i:s");
    $origin_amount = 0;
    $query = "SELECT `amount` FROM `tbl_family_code_amount` WHERE `family_code`='".$family_code."'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $origin_amount = $row['amount'];
        }
    } else{
        $query="INSERT INTO `tbl_family_code_amount` (`family_code`, `amount`, `date_created`, `date_updated`) VALUES ('".$family_code."','".$origin_amount."','".$time."','".$time."')";
        $result = $conn->query($query);
    }
    $new_amount = floatval($amount) + floatval($origin_amount) + floatval($bonus_value);
    $query = "UPDATE `tbl_family_code_amount` SET `amount`=".$new_amount.", `date_updated`='".$time."'  WHERE `family_code`='".$family_code."'";
	$result = $conn->query($query);
	
	$payment_type = "Paypal";
	$pos_id = $transaction_id;
	$Card_ID = $transaction_id;
    $query="INSERT INTO `tbl_food_topup_records` (`family_code`, `payment_type`, `pos_id`, `payment_detail`, `topup_amount`, `bonus_amount`, `username`, `date_created`) VALUES ('".$family_code."','".$payment_type."','".$pos_id."','".$Card_ID."','".$amount."','".$bonus_value."','Paypal','".$time."')";
	$result = $conn->query($query);
	//------- end save in table ------
	echo "<div style='margin:40px'>";
	echo "<h2>Your payment is successful</h2>";
	echo "<div><a href='index.php'>Back to Home</a></div>";
	echo "</div>";
	exit;
	
//save in topup table
	$topupinfo->set_topup_amt($amount);
	$topupinfo->set_payment_type('paypal');
	$topupinfo->set_payment_status($paypal_payment_status);
	$topupinfo->set_pos_slip_id('paypal');
	$topupinfo->set_login_user_id($login_user_id);
	$topup_id=$transactionbol->save_topup_manually($topupinfo);//saving in tbl_topup
//save in transaction table
	if($topup_id!=null)
	{
		//current date time
		date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
		$now_date_time = date('Y-m-d H:i:s');
		//find card_id student_primary_id and card_id
		$participant_result = $transactionbol->get_participant_data_byusingID($student_id);
		if($participant_result)
		{
			$prow = $participant_result->getNext();
			$pid = $prow['pid'];
			$card_id = $prow['card_id'];
			//saving in tbl_transaction
			$transactioninfo->set_trans_type('topup');
			$transactioninfo->set_card_id($card_id);
			$transactioninfo->set_topup_id($topup_id);
			$transactioninfo->set_transaction_amt($amount);
			$transactioninfo->set_participant_id($pid);
			$transactioninfo->set_transaction_datetime($now_date_time);
			$trans_id=$transactionbol->save_transaction($transactioninfo);
		}
		if($trans_id!=null)
		{
			//save in payment detail table
			$payment_detailinfo->set_transaction_id($trans_id);
			$payment_detailinfo->set_topup_id($topup_id);
			$payment_detailinfo->set_receiver_email($receiver_email);
			$payment_detailinfo->set_payer_email($payer_email);
			$payment_detailinfo->set_payment_amount($amount);
			$payment_detailinfo->set_paypal_status($paypal_payment_status);
			$payment_detailinfo->set_paypal_return_data($paypal_return_data);
			$payment_detail_id=$transactionbol->save_payment_detail($payment_detailinfo);
			if($payment_detail_id)
			{
				//send email to seller account's email
				$message = "  Hello,
							  <br /><br />
							  Welcome to Topup Management System!<br/>
							  To complete $payer_email payment,please accept money in seller(business) account.<br/>
							  After accepting money, please go to our admin website and change payment status to PAID.
							  <br /><br />
							  Thanks,";

				$subject = "Accept Payment";	
				$send_mail_result = send_mail($receiver_email,$message,$subject); 
				if($send_mail_result==true)
				{
					echo "<h2>Email will be sent to seller account to inform payment accept.<br/>
						After admin is accepted your payment,your topup amount will be arrive in your card.</h2>";
				}
			}
		}
	}
?>


<script>
	$(document).ready(function(){
		$("#go_to_home").html("Return Home");
	});
</script>