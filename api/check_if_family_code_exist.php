<?php
    require_once('api_common.php');
    // # Get JSON as a string
    $json_str = file_get_contents('php://input');
    // # Get as an object
    $json_obj = json_decode($json_str);
    $request = json_decode(json_encode($json_obj), True);

    $family_code = $request['family_code'];

    $query1 = "SELECT amount FROM tbl_family_code_amount WHERE family_code = '".$family_code."'";
    $result1 = $conn->query($query1);
    if($result1->num_rows > 0){
        $row1 = $result1->fetch_assoc();
        $amount = $row1['amount'];
    } else{
        $amount = 0;
    }

    $query = "SELECT amount FROM tbl_user u LEFT JOIN tbl_family_code_amount fa ON u.family_code=fa.family_code WHERE u.family_code='".$family_code."'";
    $result = $conn->query($query);
    if($result->num_rows > 0){
        //$row = $result->fetch_assoc();
        //$amount = $row['amount'];
        display_results(['Already used!', $amount]);
    } else{
        display_results(["Not used!", $amount]);
    }
    
?>