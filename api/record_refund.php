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
    $time = date("Y-m-d H:i:s");
    $query="INSERT INTO `tbl_refund_record` (`family_code`, `refund_amount`, `refund_reason`, `issues_person`, `date_created`, `date_updated`) VALUES ('".$family_code."','".$refund_amount."','".$refund_reason."','".$issues_person."','".$time."','".$time."')";
    $result = $conn->query($query);
    display_results("Successfully saved!");

?>