<?php
    require_once('api_common.php');
    
    // # Get JSON as a string
    $json_str = file_get_contents('php://input');
    // # Get as an object
    $json_obj = json_decode($json_str);
    $request = json_decode(json_encode($json_obj), True);

    $family_code = $request['family_code'];
    $refund_amount = $request['refund_amount'];
    $refund_reason = $request['refund_reason'];
    $issues_person = $request['issues_person'];
    
    //------ save refund record --------
    date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
    $time = date("Y-m-d H:i:s");
    $query="INSERT INTO `tbl_refund_record` (`family_code`, `refund_amount`, `refund_reason`, `issues_person`, `date_created`, `date_updated`) VALUES ('".$family_code."','".$refund_amount."','".$refund_reason."','".$issues_person."','".$time."','".$time."')";
    $result = $conn->query($query);

    //------- update user status -------
    $status = $request['status'];
    $query = "UPDATE `tbl_user` SET `is_active`='".$status."' WHERE `user_id`='".$issues_person."'";
    $result = $conn->query($query);

    //------- update amount of family code ------
    $query = "SELECT amount FROM tbl_family_code_amount WHERE family_code='".$family_code."'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $amount = $row['amount'];
        }
    }
    $amount = floatval($amount) - floatval($refund_amount);
    $amount = round(intval($amount*10000)/100)/100;
    $query = "UPDATE `tbl_family_code_amount` SET `amount`='".$amount."' WHERE `family_code`='".$family_code."'";
    $result = $conn->query($query);
    
    display_results($amount);

?>