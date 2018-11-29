<?php
    require_once('api_common.php');
    // # Get JSON as a string
    $json_str = file_get_contents('php://input');
    // # Get as an object
    $json_obj = json_decode($json_str);
    $request = json_decode(json_encode($json_obj), True);

    $user_code = $request['user_code'];

    $query = "SELECT * FROM `tbl_card1` WHERE `user_code`='".$user_code."'";
    $result = $conn->query($query);
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        display_results("Exist!");
    } else{
        display_results("No exist!");
    }
    
?>