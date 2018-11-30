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
        $total_amount = round(intval($total_amount*100)/10)/10;
    }

    if($report_type == "topup"){
        $query = "SELECT SUM(topup_amount) FROM tbl_food_topup_records";
        $from = $request['sel_date_from'];
        $to = $request['sel_date_to'];
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
                $total_amount = $row['SUM(topup_amount)'];
            }
        }
        $total_amount = round(intval($total_amount*100)/10)/10;
    }

    if($report_type == "user"){
        $query = "SELECT SUM(amount) FROM tbl_user u LEFT JOIN tbl_family_code_amount fa ON u.family_code=fa.family_code";
        $from = $request['sel_date_from'];
        $to = $request['sel_date_to'];
        $search_filter_by = $request['filter_index'];
        $filter_value = $request['filter_value'];
        $param = " WHERE user_created_datetime"." >= '".$from."' AND user_created_datetime < '".$to."' + interval 1 day";
        $param .= " AND 1=1 ";
        if($search_filter_by == '0'){
            $param .= " AND u.family_code LIKE '%".$filter_value."%' ";
        }
        if($search_filter_by == '1'){
            $param .= " AND user_email LIKE '%".$filter_value."%' ";
        }
        if($search_filter_by == '2'){
            $param .= " AND amount LIKE '%".$filter_value."%' ";
        }
        $query .= $param;
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $total_amount = $row['SUM(amount)'];
            }
        }
        $total_amount = round(intval($total_amount*100)/10)/10;
    }

    if($report_type == "refund"){
        $query = "SELECT SUM(refund_amount) FROM tbl_refund_record r LEFT JOIN tbl_user u on r.issues_person=u.user_id";
        $from = $request['sel_date_from'];
        $to = $request['sel_date_to'];
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
        $total_amount = round(intval($total_amount*100)/10)/10;
    }

    display_results($total_amount);
?>