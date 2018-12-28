<?php 
    require_once('api_common.php');
    // # Get JSON as a string
    $json_str = file_get_contents('php://input');
    // # Get as an object
    $json_obj = json_decode($json_str);
    $request = json_decode(json_encode($json_obj), True);

    $from = $request['sel_date_from'];
    $to = $request['sel_date_to'];
    if (strpos($from, '-') != true) {
        $from = '2010-01-01';
    }
    if (strpos($to, '-') != true) {
        $to = '2030-01-01';
    }

    

    display_results("Successfully updated!");
?>