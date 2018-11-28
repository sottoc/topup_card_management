<?php
    require_once('api_common.php');
    // # Get JSON as a string
    $json_str = file_get_contents('php://input');
    // # Get as an object
    $json_obj = json_decode($json_str);
    $request = json_decode(json_encode($json_obj), True);
    
    $bill_id = $request['bill_id'];

    $query = "SELECT `item_name`, `item_price`, `item_qty` FROM `tbl_food_purchase_records` WHERE `bill_id`='".$bill_id."'";
    $result = $conn->query($query);
    $data = array();
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()) {
            $record = array();
            $item_name = $row['item_name'];
            $item_price = $row['item_price'];
            $qty = $row['item_qty'];
            array_push($record, $item_name);
            array_push($record, $item_price);
            array_push($record, $qty);
            array_push($data, $record);
        }
    }
    display_results($data);
?>