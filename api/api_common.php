<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "topup_card_management";
    $uploadPath = $_SERVER['DOCUMENT_ROOT'].'/topup_card_management/upload/profile';
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

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