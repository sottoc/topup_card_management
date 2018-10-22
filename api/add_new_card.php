<?php
    require_once('api_common.php');
    // # Get JSON as a string
    $json_str = file_get_contents('php://input');
    // # Get as an object
    $json_obj = json_decode($json_str);
    $request = json_decode(json_encode($json_obj), True);

    $User_code = $request['User_code'];
    $Family_code = $request['Family_code'];
    $Card_ID = $request['Card_ID'];
    $Last_name = $request['Last_name'];
    $First_name = $request['First_name'];
    $Level = $request['Level'];
    $Class = $request['Class'];
    $Classification = $request['Classification'];

    //current date time
	date_default_timezone_set('Asia/Singapore');//Kuala Lumpur, Singapore
    $now_date_time = date('Y-m-d H:i:s');

    $query = "INSERT INTO `tbl_card1`(`User_code`, `Family_code`, `Card_ID`, `Last_name`, `First_name`, `Level`, `Class`, `Image`, `Card_value`, `Card_status`, `Classification`, `Username`, `Password`, `Date_Created`, `Date_Updated`) VALUES ('".$User_code."','".$Family_code."','".$Card_ID."','".$Last_name."','".$First_name."','".$Level."','".$Class."','','0','InActive','".$Classification."','','','".$now_date_time."','".$now_date_time."')";
    $result = $conn->query($query);

    display_results("Saved!");
?>