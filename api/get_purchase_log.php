<?php
    require_once('api_common.php');
    // # Get JSON as a string
    $json_str = file_get_contents('php://input');
    // # Get as an object
    $json_obj = json_decode($json_str);
    $request = json_decode(json_encode($json_obj), True);
    
    $card_id = $_POST['card_id'];

    $query = "SELECT `pos_id`, `item_name`, `item_price`, `created_time` FROM `tbl_food_purchase_records` WHERE `card_id`='".$card_id."'";
    $result = $conn->query($query);
    $data = array();
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()) {
            $record = array();
            $pos_id = $row['pos_id'];
            $item_name = $row['item_name'];
            $item_price = $row['item_price'];
            $created_time = $row['created_time'];
            array_push($record, $pos_id);
            array_push($record, $item_name);
            array_push($record, $item_price);
            array_push($record, $created_time);
            array_push($data, $record);
        }
    } 
    display_results($data);
    
?>