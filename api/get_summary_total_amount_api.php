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
    $from = $from.' 00:00:00';
    $to = $to.' 23:59:59';

    $total_spending = 0; //------------- total_spending ------------------ (B)
    $query = "SELECT SUM(total_amount) FROM tbl_food_bill_records WHERE created_time >= '".$from."' AND created_time < '".$to."'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $total_spending = $row['SUM(total_amount)'];
        }
    }
    $total_spending = round(intval($total_spending*10000)/100)/100;


    $total_topup_cash = 0; //------------- total_topup_cash -------------- (C)
    $query = "SELECT SUM(topup_amount) FROM tbl_food_topup_records WHERE LOWER(payment_type) = 'cash' AND date_created >= '".$from."' AND date_created < '".$to."'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $total_topup_cash = $row['SUM(topup_amount)'];
        }
    }
    $total_topup_cash = round(intval($total_topup_cash*10000)/100)/100;


    $total_topup_online = 0; //------------- total_topup_online ------------- (D)
    $query = "SELECT SUM(topup_amount) FROM tbl_food_topup_records WHERE LOWER(payment_type) = 'paypal' AND date_created >= '".$from."' AND date_created < '".$to."'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $total_topup_online = $row['SUM(topup_amount)'];
        }
    }
    $total_topup_online = round(intval($total_topup_online*10000)/100)/100;


    $total_bonus = 0; //------------- total_bonus ------------- (E)
    $query = "SELECT SUM(bonus_amount) FROM tbl_food_topup_records WHERE date_created >= '".$from."' AND date_created < '".$to."'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $total_bonus = $row['SUM(bonus_amount)'];
        }
    }
    $total_bonus = round(intval($total_bonus*10000)/100)/100;


    $total_refund = 0; //------------- total_refund ------------------------ (F)
    $query = "SELECT SUM(refund_amount) FROM tbl_refund_record WHERE date_created >= '".$from."' AND date_created < '".$to."'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $total_refund = $row['SUM(refund_amount)'];
        }
    }
    $total_refund = round(intval($total_refund*10000)/100)/100;

    
    $total_opening_balance = 0; //------------- total_opening_balance --------------
    $query = "SELECT SUM(amount) FROM tbl_family_code_amount";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $family_code_sum = $row['SUM(amount)'];
        }
    }
    $family_code_sum = round(intval($family_code_sum*10000)/100)/100;
    //-------------------------------- get total balance ---------------------------------------------------------
        $spending = 0; //------------- spending ------------------
        $query = "SELECT SUM(total_amount) FROM tbl_food_bill_records WHERE created_time > '".$to."'";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $spending = $row['SUM(total_amount)'];
            }
        }
        $spending = round(intval($spending*10000)/100)/100;


        $topup_cash = 0; //------------- topup_cash -------------- 
        $query = "SELECT SUM(topup_amount) FROM tbl_food_topup_records WHERE LOWER(payment_type) = 'cash' AND date_created > '".$to."'";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $topup_cash = $row['SUM(topup_amount)'];
            }
        }
        $topup_cash = round(intval($topup_cash*10000)/100)/100;


        $topup_online = 0; //------------- topup_online -------------
        $query = "SELECT SUM(topup_amount) FROM tbl_food_topup_records WHERE LOWER(payment_type) = 'paypal' AND date_created > '".$to."'";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $topup_online = $row['SUM(topup_amount)'];
            }
        }
        $topup_online = round(intval($topup_online*10000)/100)/100;


        $bonus = 0; //------------- bonus -------------
        $query = "SELECT SUM(bonus_amount) FROM tbl_food_topup_records WHERE date_created > '".$to."'";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $bonus = $row['SUM(bonus_amount)'];
            }
        }
        $bonus = round(intval($bonus*10000)/100)/100;


        $refund = 0; //------------- refund ------------------------
        $query = "SELECT SUM(refund_amount) FROM tbl_refund_record WHERE date_created > '".$to."'";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $refund = $row['SUM(refund_amount)'];
            }
        }
        $refund = round(intval($refund*10000)/100)/100;
        $total_balance = $family_code_sum + $spending - $topup_cash - $topup_online - $bonus + $refund;
    //--------------------------------- END total balance ----------------------------------------------------------------------
    $total_opening_balance = $total_balance + $total_spending - $total_topup_cash - $total_topup_online - $total_bonus + $total_refund;
    $total_opening_balance = round(intval($total_opening_balance*10000)/100)/100;


    $total_data = array();
    array_push($total_data, number_format((float)$total_opening_balance, 2, '.', ''));
    array_push($total_data, number_format((float)$total_spending, 2, '.', ''));
    array_push($total_data, number_format($total_topup_cash, 2, '.', ''));
    array_push($total_data, number_format((float)$total_topup_online, 2, '.', ''));
    array_push($total_data, number_format((float)$total_bonus, 2, '.', ''));
    array_push($total_data, number_format((float)$total_refund, 2, '.', ''));
    array_push($total_data, number_format((float)$total_balance, 2, '.', ''));
    display_results($total_data);
?>