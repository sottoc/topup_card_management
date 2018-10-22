<?php
    require_once('api_common.php');
    // # Get JSON as a string
    $json_str = file_get_contents('php://input');
    // # Get as an object
    $json_obj = json_decode($json_str);
    $request = json_decode(json_encode($json_obj), True);

    $card_id = $request['card_id'];
    $last_name = $request['last_name'];

    $query = "SELECT `Last_name`, `Classification` FROM `tbl_card1` WHERE `Card_ID`='".$card_id."'";
    $result = $conn->query($query);
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        if($last_name == $row['Last_name']){
            display_results("Matched!-".$row['Classification']);
        } else{
            display_results("Not Matched!");
        }
    } else{
        display_results("Invalid Card ID!");
    }
    
?>