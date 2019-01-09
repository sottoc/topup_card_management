<?php
    require_once('api_common.php');
    if(isset($_POST['user_code'])){
        $user_code = $_POST['user_code'];
        $card_id = $_POST['card_id'];
        $level = $_POST['level'];
        $family_code = $_POST['family_code'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        if($_POST['status'] == '1'){
            $status = 'Active';
        } else{
            $status = 'InActive';
        }
    }
    $image_name = '';
    if(isset($_FILES["file"]["type"]))
    {
        $validextensions = array("jpeg", "jpg", "png");
        $temporary = explode(".", $_FILES["file"]["name"]);
        $file_extension = end($temporary);
        if ((($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg")) 
            && ($_FILES["file"]["size"] < 200000)//Approx. 100kb files can be uploaded.
            && in_array($file_extension, $validextensions)) 
        {
            if ($_FILES["file"]["error"] > 0)
            {
                echo "Return Code: " . $_FILES["file"]["error"];
            }
            else
            {
                $image_name = $card_id.'.'.$temporary[1];
                if (file_exists($uploadPath.'/'.$image_name)) {
                    unlink($uploadPath.'/'.$image_name);
                   // echo $_FILES["file"]["name"] . " <span id='invalid'><b>already exists.</b></span> ";
                }
               
                $sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
                $targetPath = $uploadPath.'/'.$image_name; // Target path where file is to be stored
                move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file
                //echo "Image Uploaded Successfully...!!";
                // echo "<br/><b>File Name:</b> " . $_FILES["file"]["name"] . "<br>";
                // echo "<b>Type:</b> " . $_FILES["file"]["type"] . "<br>";
                // echo "<b>Size:</b> " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
                // echo "<b>Temp file:</b> " . $_FILES["file"]["tmp_name"] . "<br>";
            }
        }
        else
        {
            echo "***Invalid image Size or Type***  ";
        }
    }
    if($image_name == ''){
        $image_name = $_POST['image_name'];
    }
    date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
    $time = date("Y-m-d H:i:s");
    $class = '';
    $classification = 'Staff';
    $username = $user_code;
    $password = $user_code;
    $query = 'INSERT INTO `tbl_card1` (`User_code`, `Family_code`, `Card_ID`, `Last_name`, `First_name`, `Level`, `Class`, `Image`, `Card_status`, `Classification`, `Username`, `Password`, `Date_Created`, `Date_Updated`) VALUES ("'.$user_code.'","'.$family_code.'","'.$card_id.'","'.$last_name.'","'.$first_name.'","'.$level.'","'.$class.'","'.$image_name.'","'.$status.'","'.$classification.'","'.$username.'","'.$password.'","'.$time.'","'.$time.'")';
    $result_main = $conn->query($query);

    $query = "SELECT * FROM tbl_family_code_amount WHERE family_code='".$family_code."'";
    $result = $conn->query($query);
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
    } else{
        $query2 = "INSERT INTO `tbl_family_code_amount` (family_code, amount, date_created, date_updated) VALUES ('".$family_code."', '0', '".$time."', '".$time."')";
        $result2 = $conn->query($query2);
    }
    echo "Successfully saved!";

?>
