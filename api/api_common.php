<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "topup_card_management_1";
    $uploadPath = $_SERVER['DOCUMENT_ROOT'].'/2018-8-13-Singapore/work/topup_card_management/upload/profile';
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    mysqli_set_charset($conn,"utf8");

    function display_results($data,$status = 'OK'){
        $response = array();
        if($status == 'OK'){    
            $response['response']['status'] = 'OK';
        }elseif($status == 'ERROR'){
            $response['response']['status'] = 'ERROR';
        }
        $response['response']['data'] = $data;
        echo json_encode($response);
    }
?>