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
    date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
    $time = date("Y-m-d H:i:s");

    $query='INSERT INTO `tbl_user` (`user_email`, `user_password`, `family_code`, `user_type_id`, `user_first_name`, `user_last_name`, `user_address`, `user_phone`, `user_gender_id`, `is_active`, `user_created_datetime`, `user_modified_datetime`) VALUES ("'.$email.'","'.$password.'","'.$family_code.'","'.$type.'","'.$first_name.'","'.$last_name.'","'.$address.'","'.$phone.'","'.$gender.'","'.$status.'","'.$time.'","'.$time.'")';
    $result = $conn->query($query);

    $query = "SELECT * FROM tbl_family_code_amount WHERE family_code='".$family_code."'";
    $result = $conn->query($query);
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
    } else{
        $query2 = "INSERT INTO `tbl_family_code_amount` (family_code, amount, date_created, date_updated) VALUES ('".$family_code."', '0', '".$time."', '".$time."')";
        $result2 = $conn->query($query2);
    }
    display_results("Successfully added!");
?>