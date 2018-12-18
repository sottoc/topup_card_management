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
    $bill_id = $request['bill_id'];

    $amount = 0 - floatval($amount);
    
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
    $origin_amount = 0;
    $query = "SELECT `amount` FROM `tbl_family_code_amount` WHERE `family_code`='".$family_code."'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $origin_amount = $row['amount'];
        }
    } else{
        display_results("Can't find family code", "Info!");
        return;
    }

    $new_amount = floatval($origin_amount) - floatval($amount);
    $query = "UPDATE `tbl_family_code_amount` SET `amount`=".$new_amount." WHERE `family_code`='".$family_code."'";
    $result = $conn->query($query);

    //------ save items of food --------
    $items = $request['items'];
    date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
    $time = date("Y-m-d H:i:s");
    $query="INSERT INTO `tbl_food_bill_records` (`bill_id`, `card_id`, `pos_id`, `total_amount`, `created_time`) VALUES ('".$bill_id."','".$Card_ID."','".$pos_id."','".$amount."','".$time."')";
    $result = $conn->query($query);
    foreach($items as $index=>$item){
        $query="INSERT INTO `tbl_food_purchase_records` (`bill_id`, `item_name`, `item_price`, `item_qty`, `created_time`) VALUES ('".$bill_id."','".$item['name']."','".$item['price']."','".$item['qty']."','".$time."')";
        $result = $conn->query($query);
    }
    display_results("Successfully saved!");

?>