<?php
    require_once('api_common.php');
    
    // # Get JSON as a string
    $json_str = file_get_contents('php://input');
    // # Get as an object
    $json_obj = json_decode($json_str);
    $request = json_decode(json_encode($json_obj), True);

    //--------------- save single user ---------------
    $email = $request['email'];
    $password = md5('lycee123');
    $family_code = $request['family_code'];
    $type = $request['account_type'];
    $first_name = $request['first_name'];
    $last_name = $request['last_name'];
    $address = 'address';
    $phone = '123456789';
    $gender = '1';
    $status = $request['status'];
    $time = date("Y-m-d H:i:s");

    $query="INSERT INTO `tbl_user` (`user_email`, `user_password`, `family_code`, `user_type_id`, `user_first_name`, `user_last_name`, `user_address`, `user_phone`, `user_gender_id`, `is_active`, `user_created_datetime`, `user_modified_datetime`) VALUES ('".$email."','".$password."','".$family_code."','".$type."','".$first_name."','".$last_name."','".$address."','".$phone."','".$gender."','".$status."','".$time."','".$time."')";
    $result = $conn->query($query);
    display_results("Successfully added!");

?>