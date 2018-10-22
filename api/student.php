<?php
    require_once('api_common.php');

    if(isset($_GET['card_id'])){
        $Card_ID = $_GET['card_id'];
        $query = "SELECT * FROM `tbl_card1` WHERE `Card_ID`='".$Card_ID."'";
        $result = $conn->query($query);
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
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
                display_results($row, 'OK');
            }
        } else{
            display_results("No result", "ERROR");
        }
    }

?>