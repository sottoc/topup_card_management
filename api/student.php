<?php
    require_once('api_common.php');

    if(isset($_GET['card_id'])){
        $Card_ID = $_GET['card_id'];
        $query = "SELECT * FROM `tbl_card1` WHERE `Card_ID`='".$Card_ID."'";
        $result = $conn->query($query);
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $family_code = $row['Family_code'];
                $query1 = "SELECT amount FROM `tbl_family_code_amount` WHERE `family_code`='".$family_code."'";
                $result1 = $conn->query($query1);
                $amount = 0;
                if ($result1->num_rows > 0) {
                    while($row1 = $result1->fetch_assoc()) {
                        $amount = $row1['amount'];
                    }
                }
                $row['Card_value'] = $amount;
                display_results($row, 'OK');
            }

        } else{
            display_results("No result", "ERROR");
        }
    }

    if(isset($_POST['card_id'])){
        $Card_ID = $_POST['card_id'];
        
        $query = "SELECT * FROM `tbl_card1` WHERE `Card_ID`='".$Card_ID."'";
        $result = $conn->query($query);
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $family_code = $row['Family_code'];
                $query1 = "SELECT amount FROM `tbl_family_code_amount` WHERE `family_code`='".$family_code."'";
                $result1 = $conn->query($query1);
                $amount = 0;
                if ($result1->num_rows > 0) {
                    while($row1 = $result1->fetch_assoc()) {
                        $amount = $row1['amount'];
                    }
                }
                $row['Card_value'] = $amount;
                display_results($row, 'OK');
            }

        } else{
            display_results("No result", "ERROR");
        }
    }

?>