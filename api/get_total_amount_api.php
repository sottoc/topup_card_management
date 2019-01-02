<?php
    require_once('api_common.php');
    // # Get JSON as a string
    $json_str = file_get_contents('php://input');
    // # Get as an object
    $json_obj = json_decode($json_str);
    $request = json_decode(json_encode($json_obj), True);
    
    $report_type = $request['report_type'];
    $total_amount = 0;
    if($report_type == "spend"){
        $query = "SELECT SUM(total_amount) FROM `tbl_food_bill_records` t left join tbl_card1 top on t.card_id=top.Card_ID";
        $from = $request['sel_date_from'];
        $to = $request['sel_date_to'];
        if (strpos($from, '-') != true) {
            $from = '2010-01-01';
        }
        if (strpos($to, '-') != true) {
            $to = '2030-01-01';
        }
        $search_filter_by = $request['filter_index'];
        $filter_value = $request['filter_value'];
        $param = " WHERE created_time"." >= '".$from."' AND created_time < '".$to."' + interval 1 day";
        $param .= " AND 1=1 ";
        if($search_filter_by == '0'){
            $param .= " AND User_code LIKE '%".$filter_value."%' ";
        }
        if($search_filter_by == '1'){
            $param .= " AND top.Card_ID LIKE '%".$filter_value."%' ";
        }
        if($search_filter_by == '2'){
            $param .= " AND Family_code LIKE '%".$filter_value."%' ";
        }
        if($search_filter_by == '3'){
            $param .= " AND First_name LIKE '%".$filter_value."%' ";
        }
        if($search_filter_by == '4'){
            $param .= " AND Last_name LIKE '%".$filter_value."%' ";
        }
        if($search_filter_by == '5'){
            $param .= " AND total_amount LIKE '%".$filter_value."%' ";
        }
        if($search_filter_by == '6'){
            $param .= " AND pos_id LIKE '%".$filter_value."%' ";
        }
        $query .= $param;
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $total_amount = $row['SUM(total_amount)'];
            }
        }
        $total_amount = round(intval($total_amount*10000)/100)/100;
    }

    if($report_type == "topup"){
        $total_amount = array();
        $query = "SELECT SUM(topup_amount), SUM(bonus_amount) FROM tbl_food_topup_records";
        $from = $request['sel_date_from'];
        $to = $request['sel_date_to'];
        if (strpos($from, '-') != true) {
            $from = '2010-01-01';
        }
        if (strpos($to, '-') != true) {
            $to = '2030-01-01';
        }
        $search_filter_by = $request['filter_index'];
        $filter_value = $request['filter_value'];
        $param = " WHERE date_created"." >= '".$from."' AND date_created < '".$to."' + interval 1 day";
        $param .= " AND 1=1 ";
        if($search_filter_by == '0'){
            $param .= " AND family_code LIKE '%".$filter_value."%' ";
        }
        if($search_filter_by == '1'){
            $param .= " AND topup_amount LIKE '%".$filter_value."%' ";
        }
        if($search_filter_by == '2'){
            $param .= " AND payment_type LIKE '%".$filter_value."%' ";
        }
        $query .= $param;
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $topup_amount = $row['SUM(topup_amount)'];
                $bonus_amount = $row['SUM(bonus_amount)'];
            }
        }
        $total_amount[] = round(intval($topup_amount*10000)/100)/100;
        $total_amount[] = round(intval($bonus_amount*10000)/100)/100;
    }

    if($report_type == "user"){
        $query = "SELECT sum(amount), COUNT(*) FROM tbl_family_code_amount";
        $from = $request['sel_date_from'];
        $to = $request['sel_date_to'];
        if (strpos($from, '-') != true) {
            $from = '2010-01-01';
        }
        if (strpos($to, '-') != true) {
            $to = '2030-01-01';
        }
        $search_filter_by = $request['filter_index'];
        $filter_value = $request['filter_value'];
        $param = " WHERE date_created"." >= '".$from."' AND date_created < '".$to."' + interval 1 day";
        $param .= " AND 1=1 ";
        if($search_filter_by == '0'){
            $param .= " AND family_code LIKE '%".$filter_value."%' ";
        }
        // if($search_filter_by == '1'){
        //     $param .= " AND user_email LIKE '%".$filter_value."%' ";
        // }
        if($search_filter_by == '2'){
            $param .= " AND amount LIKE '%".$filter_value."%' ";
        }
        $query .= $param;
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $total_amount1 = $row['sum(amount)'];
                $total_amount2 = $row['COUNT(*)'];
            }
        }
        $total_amount = array();
        $total_amount[] = round(intval($total_amount1*10000)/100)/100;
        $total_amount[] = $total_amount2;
    }

    if($report_type == "refund"){
        $query = "SELECT SUM(refund_amount) FROM tbl_refund_record r LEFT JOIN tbl_user u on r.issues_person=u.user_id";
        $from = $request['sel_date_from'];
        $to = $request['sel_date_to'];
        if (strpos($from, '-') != true) {
            $from = '2010-01-01';
        }
        if (strpos($to, '-') != true) {
            $to = '2030-01-01';
        }
        $search_filter_by = $request['filter_index'];
        $filter_value = $request['filter_value'];
        $param = " WHERE date_created"." >= '".$from."' AND date_created < '".$to."' + interval 1 day";
        $param .= " AND 1=1 ";
        if($search_filter_by == '0'){
            $param .= " AND u.family_code LIKE '%".$filter_value."%' ";
        }
        if($search_filter_by == '1'){
            $param .= " AND user_email LIKE '%".$filter_value."%' ";
        }
        if($search_filter_by == '2'){
            $param .= " AND refund_amount LIKE '%".$filter_value."%' ";
        }
        $query .= $param;
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $total_amount = $row['SUM(refund_amount)'];
            }
        }
        $total_amount = round(intval($total_amount*10000)/100)/100;
    }

    display_results($total_amount);
?>