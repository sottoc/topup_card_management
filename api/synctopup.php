<?php 
    require_once('api_common.php');
    // # Get JSON as a string
    $json_str = file_get_contents('php://input');
    // # Get as an object
    $json_obj = json_decode($json_str);
    $request = json_decode(json_encode($json_obj), True);

    $card_id = $request['card_id'];
    $amount = $request['amount'];
    $payment_type = $request['payment_type'];
    $username = $request['username'];
    $pos_id = $request['pos_id'];
    $created_date = $request['created_date'];

    if($payment_type == "CASH"){
        $payment_type = "Cash";
    }

    date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
    $time = date("Y-m-d h:i:s", $created_date);

    $query = "SELECT Family_code FROM tbl_card1 WHERE Card_ID = '".$card_id."'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $family_code = $row['Family_code'];
        }
    } else{
        //display_results("Can't find family code");
        $family_code = '';
    }

    $query="INSERT INTO `tbl_food_topup_records` (`family_code`, `payment_type`, `pos_id`, `payment_detail`, `topup_amount`, `bonus_amount`, `username`, `date_created`) VALUES ('".$family_code."','".$payment_type."','".$pos_id."','".$card_id."','".$amount."','0','".$username."','".$time."')";
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