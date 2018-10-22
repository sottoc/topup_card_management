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
    //--- check if valid Card ID ------
    $card_value = 0;
    $query = "SELECT `Card_value` FROM `tbl_card1` WHERE `Card_ID`='".$Card_ID."'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $card_value = $row['Card_value'];
        }
    } else{
        display_results("Invalid Card ID", "ERROR");
        return;
    }
    $card_value = $amount + $card_value;
    $query = "UPDATE `tbl_card1` SET `Card_value`=".$card_value." WHERE `Card_ID`='".$Card_ID."'";
    $result = $conn->query($query);

    //------ save items of food --------
    $items = $request['items'];
    $time = date("Y-m-d H:i:s");
    foreach($items as $index=>$item){
        $query="INSERT INTO `tbl_food_purchase_records` (`card_id`, `pos_id`, `item_name`, `item_price`, `created_time`) VALUES ('".$Card_ID."','".$pos_id."','".$item['name']."','".$item['price']."','".$time."')";
        $result = $conn->query($query);
    }
    display_results("Successfully saved!");

?>