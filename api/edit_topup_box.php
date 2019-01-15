<?php
    require_once('api_common.php');
    // # Get JSON as a string
    $json_str = file_get_contents('php://input');
    // # Get as an object
    $json_obj = json_decode($json_str);
    $request = json_decode(json_encode($json_obj), True);

    $box_id = $request['id'];
    $amount = $request['amount'];
    $description = $request['description'];
    $bonus_value = $request['bonus_value'];
    $limit_times = $request['limit_times'];
    $group_id = $request['group_id'];
    $sel_date_from = $request['sel_date_from'];
    $sel_time_from = $request['sel_time_from'];
    $sel_date_to = $request['sel_date_to'];
    $sel_time_to = $request['sel_time_to'];

    //current date time
    $datetime_from = $sel_date_from.' '.$sel_time_from;
    $datetime_to = $sel_date_to.' '.$sel_time_to;

    $query = "UPDATE `tbl_topup_box` SET `group_id`='".$group_id."', `amount`='".$amount."', `description`='".$description."', `bonus_value`='".$bonus_value."', `limit_times`='".$limit_times."', `datetime_from`='".$datetime_from."', `datetime_to`='".$datetime_to."' WHERE `box_id`='".$box_id."'";
    $result = $conn->query($query);

    display_results("Successfully Updated!");
?>