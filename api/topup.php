<?php
    require_once('api_common.php');
    // # Get JSON as a string
    $json_str = file_get_contents('php://input');
    // # Get as an object
    $json_obj = json_decode($json_str);
    $request = json_decode(json_encode($json_obj), True);

    $Card_ID = $request['card_id'];
    $amount = $request['amount'];
    $pos_id = $request['pos_id'];
    $payment_type = $request['payment_type'];
    $username = $request['username'];
    $created_date = $request['created_date'];

    //--- get family code from card number ------
    $family_code = '';
    $query = "SELECT `Family_code` FROM `tbl_card1` WHERE `Card_ID`='".$Card_ID."'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $family_code = $row['Family_code'];
        }
    } else{
        display_results("Invalid card number", "Info!");
        return;
    }

    //---- get amount from family code ------
    date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
    $time = date("Y-m-d h:i:s", $created_date);
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

    $new_amount = floatval($amount) + floatval($origin_amount);

    $query = "UPDATE `tbl_family_code_amount` SET `amount`=".$new_amount.", `date_updated`='".$time."'  WHERE `family_code`='".$family_code."'";
    $result = $conn->query($query);

    //-------- save topup record -----------
    if($payment_type == "CASH"){
        $payment_type = "Cash";
    }
    $payment_detail = $Card_ID;
    $query="INSERT INTO `tbl_food_topup_records` (`family_code`, `payment_type`, `pos_id`, `payment_detail`, `topup_amount`, `bonus_amount`, `username`, `date_created`) VALUES ('".$family_code."','".$payment_type."','".$pos_id."','".$Card_ID."','".$amount."','0','".$username."','".$time."')";
    $result = $conn->query($query);

    $query = "SELECT id FROM tbl_food_topup_records ORDER BY id DESC LIMIT 1";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $id = $row['id'];
        }
    }

    $data = array('id'=>$id);

    display_results($data);
?>