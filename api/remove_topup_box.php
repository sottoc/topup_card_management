<?php
    require_once('api_common.php');
    // # Get JSON as a string
    $json_str = file_get_contents('php://input');
    // # Get as an object
    $json_obj = json_decode($json_str);
    $request = json_decode(json_encode($json_obj), True);

    $id = $request['id'];

    $query = "UPDATE `tbl_topup_box` SET `box_status`='0' WHERE box_id = '".$id."'";
    $result = $conn->query($query);

    display_results("Successfully removed!");
?>