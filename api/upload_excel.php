<?php
    require_once('api_common.php');
    $fileName = '';
    if(isset($_FILES["file"]["type"]))
    {
        $fileName = $_FILES["file"]["name"];
        $sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
        $targetPath = $uploadPath.'/'.$fileName; // Target path where file is to be stored
        if (file_exists($uploadPath.'/'.$fileName)) {
            unlink($uploadPath.'/'.$fileName);
        }
        move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file
        echo "Successfully uploaded!";
    }

    // if($image_name == ''){
    //     $image_name = $_POST['image_name'];
    // }
    // $query = "UPDATE `tbl_card1` SET `User_code`='".$user_code."',`Family_code`='".$family_code."',`Card_ID`='".$card_id."',`Last_name`='".$last_name."',`First_name`='".$first_name."',`Level`='".$level."',`Image`='".$image_name."',`Card_status`='".$status."' WHERE `id`= '".$id."'";
    // $result = $conn->query($query);
    //echo "Successfully saved!";

?>
