<?php
    require_once('api_common.php');
    // # Get JSON as a string
    $json_str = file_get_contents('php://input');
    // # Get as an object
    $json_obj = json_decode($json_str);
    $request = json_decode(json_encode($json_obj), True);
    
    if(isset($_POST['card_id'])){
        $card_id = $_POST['card_id'];
        $all_data = array();
        //------ get spend history --------
        $query = "SELECT `pos_id`, `total_amount`, `created_time` FROM `tbl_food_bill_records` WHERE `card_id`='".$card_id."'";
        $result = $conn->query($query);
        $data = array();
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()) {
                $record = array();
                $pos_id = $row['pos_id'];
                $total_amount = $row['total_amount'];
                $created_time = $row['created_time'];
                array_push($record, $pos_id);
                array_push($record, $total_amount);
                array_push($record, $created_time);
                array_push($data, $record);
            }
        } 
        array_push($all_data, $data);
        //------- get topup history --------
        $query = "SELECT `pos_id`, `topup_amount`, `date_created` FROM `tbl_food_topup_records` WHERE `payment_detail`='".$card_id."'";
        $result = $conn->query($query);
        $data = array();
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()) {
                $record = array();
                $pos_id = $row['pos_id'];
                $topup_amount = $row['topup_amount'];
                $date_created = $row['date_created'];
                array_push($record, $pos_id);
                array_push($record, $topup_amount);
                array_push($record, $date_created);
                array_push($data, $record);
            }
        } 
        array_push($all_data, $data);
        display_results($all_data);
    }

    if(isset($_POST['family_code'])){
        $family_code = $_POST['family_code'];
        $query = "SELECT * FROM tbl_card1 WHERE Family_code = '".$family_code."'";
        $result = $conn->query($query);
        $all_data = array();
        if($result->num_rows > 0){
            $data_spend = array();
            $data_topup = array();
            while($row = $result->fetch_assoc()) {
                $card_id = $row['Card_ID'];
                //------ get spend history --------
                $query1 = "SELECT `pos_id`, `total_amount`, `created_time` FROM `tbl_food_bill_records` WHERE `card_id`='".$card_id."' ORDER BY created_time DESC";
                $result1 = $conn->query($query1);
                if($result1->num_rows > 0){
                    while($row1 = $result1->fetch_assoc()) {
                        $record = array();
                        $pos_id = $row1['pos_id'];
                        $total_amount = $row1['total_amount'];
                        $created_time = $row1['created_time'];
                        array_push($record, $pos_id);
                        array_push($record, $total_amount);
                        array_push($record, $created_time);
                        array_push($record, $card_id);
                        array_push($data_spend, $record);
                    }
                }
            }

            //------- get topup history --------
            $query2 = "SELECT `payment_type`, `pos_id`, `topup_amount`, `date_created` FROM `tbl_food_topup_records` WHERE `family_code`='".$family_code."' ORDER BY date_created DESC";
            $result2 = $conn->query($query2);
            if($result2->num_rows > 0){
                while($row2 = $result2->fetch_assoc()) {
                    $record = array();
                    $pos_id = $row2['pos_id'];
                    $topup_amount = $row2['topup_amount'];
                    $date_created = $row2['date_created'];
                    $payment_type = $row2['payment_type'];
                    array_push($record, $pos_id);
                    array_push($record, $topup_amount);
                    array_push($record, $date_created);
                    array_push($record, $card_id);
                    array_push($record, $payment_type);
                    array_push($data_topup, $record);
                }
            }

            array_push($all_data, $data_spend);
            array_push($all_data, $data_topup);
        }
        display_results($all_data);
    }
    
    
?>