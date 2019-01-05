<?php
    require_once('api_common.php');
    // # Get JSON as a string
    $json_str = file_get_contents('php://input');
    // # Get as an object
    $json_obj = json_decode($json_str);
    $request = json_decode(json_encode($json_obj), True);

    $query = "SELECT id, date_created FROM tbl_food_topup_records";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $datetime = $row['date_created'];
            $id = $row['id'];
            $str = explode(" ", $datetime);
            if($str[0] == "2019-01-04"){
                echo $id."---";
                $datetime = '2018-12-30 '.$str[1];
                $query1 = "UPDATE tbl_food_topup_records SET date_created = '".$datetime."' WHERE id = '".$id."'";
                $result1 = $conn->query($query1);
            }
        }
    }

?>