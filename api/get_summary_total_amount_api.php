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
    $search_filter_by = $request['filter_index'];
    $filter_value = $request['filter_value'];

    $total_opening_balance = 0; //------------- total_opening_balance --------------
    $query = "SELECT SUM(opening_balance) FROM tbl_summary_record";
    $param = " WHERE created_time"." >= '".$from."' AND created_time < '".$to."' + interval 1 day";
    $param .= " AND 1=1 ";
    if($search_filter_by == '0'){
        $param .= " AND user_email LIKE '%".$filter_value."%' ";
    }
    if($search_filter_by == '1'){
        $param .= " AND family_code LIKE '%".$filter_value."%' ";
    }
    $query .= $param;
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $total_opening_balance = $row['SUM(opening_balance)'];
        }
    }
    $total_opening_balance = round(intval($total_opening_balance*10000)/100)/100;

    $total_spending = 0; //------------- total_spending --------------
    $query = "SELECT SUM(total_spending) FROM tbl_summary_record";
    $param = " WHERE created_time"." >= '".$from."' AND created_time < '".$to."' + interval 1 day";
    $param .= " AND 1=1 ";
    if($search_filter_by == '0'){
        $param .= " AND user_email LIKE '%".$filter_value."%' ";
    }
    if($search_filter_by == '1'){
        $param .= " AND family_code LIKE '%".$filter_value."%' ";
    }
    $query .= $param;
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $total_spending = $row['SUM(total_spending)'];
        }
    }
    $total_spending = round(intval($total_spending*10000)/100)/100;

    $total_topup_cash = 0; //------------- total_topup_cash --------------
    $query = "SELECT SUM(total_topup_cash) FROM tbl_summary_record";
    $param = " WHERE created_time"." >= '".$from."' AND created_time < '".$to."' + interval 1 day";
    $param .= " AND 1=1 ";
    if($search_filter_by == '0'){
        $param .= " AND user_email LIKE '%".$filter_value."%' ";
    }
    if($search_filter_by == '1'){
        $param .= " AND family_code LIKE '%".$filter_value."%' ";
    }
    $query .= $param;
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $total_topup_cash = $row['SUM(total_topup_cash)'];
        }
    }
    $total_topup_cash = round(intval($total_topup_cash*10000)/100)/100;

    $total_topup_online = 0; //------------- total_topup_online --------------
    $query = "SELECT SUM(total_topup_online) FROM tbl_summary_record";
    $param = " WHERE created_time"." >= '".$from."' AND created_time < '".$to."' + interval 1 day";
    $param .= " AND 1=1 ";
    if($search_filter_by == '0'){
        $param .= " AND user_email LIKE '%".$filter_value."%' ";
    }
    if($search_filter_by == '1'){
        $param .= " AND family_code LIKE '%".$filter_value."%' ";
    }
    $query .= $param;
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $total_topup_online = $row['SUM(total_topup_online)'];
        }
    }
    $total_topup_online = round(intval($total_topup_online*10000)/100)/100;

    $total_refund = 0; //------------- total_refund --------------
    $query = "SELECT SUM(total_refund) FROM tbl_summary_record";
    $param = " WHERE created_time"." >= '".$from."' AND created_time < '".$to."' + interval 1 day";
    $param .= " AND 1=1 ";
    if($search_filter_by == '0'){
        $param .= " AND user_email LIKE '%".$filter_value."%' ";
    }
    if($search_filter_by == '1'){
        $param .= " AND family_code LIKE '%".$filter_value."%' ";
    }
    $query .= $param;
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $total_refund = $row['SUM(total_refund)'];
        }
    }
    $total_refund = round(intval($total_refund*10000)/100)/100;

    $total_data = array();
    array_push($total_data, $total_opening_balance);
    array_push($total_data, $total_spending);
    array_push($total_data, $total_topup_cash);
    array_push($total_data, $total_topup_online);
    array_push($total_data, $total_refund);
    display_results($total_data);
?>