<?php
    require_once('api_common.php');
    // # Get JSON as a string
    $json_str = file_get_contents('php://input');
    // # Get as an object
    $json_obj = json_decode($json_str);
    $request = json_decode(json_encode($json_obj), True);
    
    $email = $request['email'];
    $password = $request['password'];
    $md5_password = md5($password);

    $query = "SELECT `user_id` FROM `tbl_user` WHERE `user_email`='".$email."'";
    $result = $conn->query($query);
    if($result->num_rows > 0){
        $query1 = "UPDATE `tbl_user` SET `user_password`= '".$md5_password."' WHERE `user_email`='".$email."'";
        $result1 = $conn->query($query1);
        echo 'OK';
    } else{
        echo 'No user';
    }
    
?>