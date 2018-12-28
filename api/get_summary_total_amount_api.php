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

    $total_spending = 0; //------------- total_spending --------------
    $total_spending = round(intval($total_spending*10000)/100)/100;

    $total_topup_cash = 0; //------------- total_topup_cash --------------
    $total_topup_cash = round(intval($total_topup_cash*10000)/100)/100;

    $total_topup_online = 0; //------------- total_topup_online --------------
    $total_topup_online = round(intval($total_topup_online*10000)/100)/100;

    $total_refund = 0; //------------- total_refund --------------
    $total_refund = round(intval($total_refund*10000)/100)/100;

    $total_opening_balance = 0; //------------- total_opening_balance --------------
    $total_opening_balance = round(intval($total_opening_balance*10000)/100)/100;

    $total_data = array();
    array_push($total_data, $total_opening_balance);
    array_push($total_data, $total_spending);
    array_push($total_data, $total_topup_cash);
    array_push($total_data, $total_topup_online);
    array_push($total_data, $total_refund);
    display_results($total_data);
?>