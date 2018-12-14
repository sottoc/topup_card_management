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
    $query = "SELECT user_email, family_code FROM tbl_summary_record WHERE created_time"." >= '".$from."' AND created_time < '".$to."' + interval 1 day";
    $result = $conn->query($query);
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()) {
            $user_email = $row['user_email'];
            $family_code = $row['family_code'];
            $query1 = "SELECT amount FROM tbl_family_code_amount WHERE family_code = '".$family_code."'";
            $result1 = $conn->query($query1);
            $opening_balance = 0; //---------- opening_balance ----------------
            if($result1->num_rows > 0){
                while($row1 = $result1->fetch_assoc()){
                    $opening_balance = $row1['amount'];
                }
            }
            $query2 = "UPDATE `tbl_summary_record` SET opening_balance = '".$opening_balance."' WHERE `user_email`='".$user_email."'";
            $result2 = $conn->query($query2);
            $total_spending = 0; //--------- total_spending -----------------
            $query3 = "SELECT * FROM tbl_card1 WHERE Family_code = '".$family_code."'";
            $result3 = $conn->query($query3);
            if($result3->num_rows > 0){
                while($row3 = $result3->fetch_assoc()) {
                    $card_id = $row3['Card_ID'];
                    $query4 = "SELECT `total_amount` FROM `tbl_food_bill_records`" ;
                    $param = " WHERE 1=1 ";
                    $param .= " AND `card_id`='".$card_id."'";
                    $param .= " AND created_time"." >= '".$from."' AND created_time < '".$to."' + interval 1 day";
                    $query4 .= $param;
                    $result4 = $conn->query($query4);
                    if($result4->num_rows > 0){
                        while($row4 = $result4->fetch_assoc()){
                            $total_spending += $row4['total_amount'];
                        }
                    }
                }
            }
            $query6 = "UPDATE `tbl_summary_record` SET total_spending = '".$total_spending."' WHERE `user_email`='".$user_email."'";
            $result6 = $conn->query($query6);
            $total_topup_cash = 0; //--------- total_topup_cash -----------------
            $query7 = "SELECT SUM(topup_amount) FROM tbl_food_topup_records";
            $param = " WHERE 1=1 ";
            $param .= " AND payment_type = '1' AND family_code='".$family_code."'";
            $param .= " AND date_created"." >= '".$from."' AND date_created < '".$to."' + interval 1 day";
            $query7 .= $param;
            $result7 = $conn->query($query7);
            if ($result7->num_rows > 0) {
                while($row7 = $result7->fetch_assoc()) {
                    $total_topup_cash = $row7['SUM(topup_amount)'];
                }
            }
            $query8 = "UPDATE `tbl_summary_record` SET total_topup_cash = '".$total_topup_cash."' WHERE `user_email`='".$user_email."'";
            $result8 = $conn->query($query8);
            $total_topup_online = 0; //--------- total_topup_online -----------------
            $query9 = "SELECT SUM(topup_amount) FROM tbl_food_topup_records";
            $param = " WHERE 1=1 ";
            $param .= " AND payment_type = '0' AND family_code='".$family_code."'";
            $param .= " AND date_created"." >= '".$from."' AND date_created < '".$to."' + interval 1 day";
            $query9 .= $param;
            $result9 = $conn->query($query9);
            if ($result9->num_rows > 0) {
                while($row9 = $result9->fetch_assoc()) {
                    $total_topup_cash = $row9['SUM(topup_amount)'];
                }
            }
            $query10 = "UPDATE `tbl_summary_record` SET total_topup_cash = '".$total_topup_cash."' WHERE `user_email`='".$user_email."'";
            $result10 = $conn->query($query10);
            $total_refund = 0; //------------ total_refund -----------------
            $query11 = "SELECT SUM(refund_amount) FROM tbl_refund_record";
            $param = " WHERE 1=1 ";
            $param .= " AND family_code='".$family_code."'";
            $param .= " AND date_created"." >= '".$from."' AND date_created < '".$to."' + interval 1 day";
            $query11 .= $param;
            $result11 = $conn->query($query11);
            if ($result11->num_rows > 0) {
                while($row11 = $result11->fetch_assoc()) {
                    $total_refund = $row11['SUM(refund_amount)'];
                }
            }
            $query12 = "UPDATE `tbl_summary_record` SET total_refund = '".$total_refund."' WHERE `user_email`='".$user_email."'";
            $result12 = $conn->query($query12);
            $balance = 0; //------------- balance --------------
            $balance = $opening_balance - $total_spending + $total_topup_cash + $total_topup_online;
            $query13 = "UPDATE `tbl_summary_record` SET balance = '".$balance."' WHERE `user_email`='".$user_email."'";
            $result13 = $conn->query($query13);
        }
    }

    display_results("Successfully updated!");
?>