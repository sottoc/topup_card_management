<?php 
    require_once('api_common.php');
    // # Get JSON as a string
    $json_str = file_get_contents('php://input');
    // # Get as an object
    $json_obj = json_decode($json_str);
    $request = json_decode(json_encode($json_obj), True);

    $user_id = $request['user_id'];
    $user_email = $request['user_email'];
    $first_name = $request['first_name'];
    $last_name = $request['last_name'];
    $user_status = $request['user_status'];
    $user_type = $request['user_type'];
    $family_code = $request['family_code'];
    $amount = $request['amount'];
    date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
    $datetime = date("Y-m-d h:i:s");

    $query = "UPDATE `tbl_user` SET `user_email`='".$user_email."', `user_first_name`='".$first_name."', `user_last_name`='".$last_name."', `user_type_id`='".$user_type."', `is_active`='".$user_status."', `user_modified_datetime`='".$datetime."' WHERE `user_id`='".$user_id."'";
    $result = $conn->query($query);

    display_results("Successfully Saved!");
?>